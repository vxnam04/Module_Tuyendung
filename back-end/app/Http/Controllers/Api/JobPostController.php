<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JobPosts\JobPost;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Support\Facades\Log;
use App\Models\JobPosts\JobPostPosition;
use App\Models\JobPosts\JobPostIndustry;
use App\Models\JobPosts\JobPostExperience;
use App\Models\JobPosts\JobPostWorkType;

class JobPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth')->only(['store', 'update', 'destroy']);
    }

    public function store(Request $request)
    {
        try {
            // Lấy user từ JWT middleware
            $user = $request->attributes->get('user');
            if (!$user || !isset($user['sub'])) return response()->json(['message' => 'Unauthorized'], 401);

            $teacher = Teacher::where('lecturer_id', $user['sub'])->first();
            if (!$teacher) return response()->json(['message' => 'Không tìm thấy teacher'], 404);

            // Validate input
            $validated = $request->validate([
                'job_title'            => 'required|string|max:255',
                'company_name'         => 'required|string|max:255',
                'description'          => 'nullable|string',
                'application_deadline' => 'nullable|date',
                'contact_email'        => 'nullable|email',
                'contact_phone'        => 'nullable|string|max:20',

                'work_location'        => 'nullable|array',
                'work_location.*.street' => 'nullable|string|max:255',
                'work_location.*.city'   => 'nullable|string|max:100',
                'work_location.*.state'  => 'nullable|string|max:100',
                'work_location.*.country' => 'nullable|string|max:100',
                'work_location.*.postal_code' => 'nullable|string|max:20',

                'job_type'             => 'nullable|array',
                'job_type.*'           => 'string|max:50',

                'salary_min'           => 'nullable|integer|min:0',
                'salary_max'           => 'nullable|integer|min:0',

                'experience_required'  => 'nullable|integer|min:0',
                'skills_required'      => 'nullable|array',
                'skills_required.*'    => 'string|max:100',

                'education_level'      => 'nullable|string|max:100',

                'benefits'             => 'nullable|array',
                'benefits.*'           => 'string|max:255',
                'additional_benefits'  => 'nullable|array',
                'additional_benefits.*' => 'string|max:255',

                'working_days'         => 'nullable|array',
                'working_days.*'       => 'string|max:20',

                'work_time.start'      => 'nullable|string',
                'work_time.end'        => 'nullable|string',
            ]);

            $job = DB::transaction(function () use ($validated, $teacher) {
                $job = $teacher->jobPosts()->create([
                    'job_title'            => $validated['job_title'],
                    'company_name'         => $validated['company_name'],
                    'description'          => $validated['description'] ?? null,
                    'application_deadline' => $validated['application_deadline'] ?? null,
                    'contact_email'        => $validated['contact_email'] ?? null,
                    'contact_phone'        => $validated['contact_phone'] ?? null,
                ]);

                // Work locations
                if (!empty($validated['work_location'])) {
                    foreach ($validated['work_location'] as $loc) {
                        $job->addresses()->create($loc);
                    }
                }

                // Work types
                if (!empty($validated['job_type'])) {
                    foreach ($validated['job_type'] as $type) {
                        $job->workTypes()->create(['work_type' => $type]);
                    }
                }

                // Salary
                $job->salary()->create([
                    'salary_min' => $validated['salary_min'] ?? null,
                    'salary_max' => $validated['salary_max'] ?? null,
                    'currency'   => 'VND'
                ]);

                // Experience
                if (isset($validated['experience_required'])) {
                    $job->experiences()->create(['years' => $validated['experience_required']]);
                }

                // Skills
                if (!empty($validated['skills_required'])) {
                    foreach ($validated['skills_required'] as $skill) {
                        $job->skills()->create(['skill_name' => $skill]);
                    }
                }

                // Education
                if (!empty($validated['education_level'])) {
                    $job->educationLevels()->create(['education_level' => $validated['education_level']]);
                }

                // Benefits
                if (!empty($validated['benefits'])) {
                    foreach ($validated['benefits'] as $b) {
                        $job->benefits()->create(['benefit_type' => 'main', 'description' => $b]);
                    }
                }
                if (!empty($validated['additional_benefits'])) {
                    foreach ($validated['additional_benefits'] as $b) {
                        $job->benefits()->create(['benefit_type' => 'additional', 'description' => $b]);
                    }
                }

                // Working days
                if (!empty($validated['working_days'])) {
                    foreach ($validated['working_days'] as $day) {
                        $job->workingDays()->create(['day_name' => $day]);
                    }
                }

                // Working times
                if (!empty($validated['work_time'])) {
                    $job->workingTimes()->create([
                        'start_time' => $validated['work_time']['start'] ?? null,
                        'end_time' => $validated['work_time']['end'] ?? null
                    ]);
                }

                return $job;
            });

            return response()->json([
                'message' => 'Job post created successfully',
                'job_post' => $job->load([
                    'addresses',
                    'workTypes',
                    'salary',
                    'experiences',
                    'skills',
                    'educationLevels',
                    'benefits',
                    'workingDays',
                    'workingTimes',
                    'teacher'
                ])
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating job post: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error creating job post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // index, show, update, destroy có thể giữ nguyên, chỉ cần fix quan hệ tương tự



    /**
     * Lấy tất cả job posts (public)
     */
    public function index()
    {
        $jobs = JobPost::with([
            'address',
            'experience',
            'industry',
            'position',
            'salary',
            'workType',
            'teacher'
        ])->latest()->get();

        return response()->json($jobs);
    }

    /**
     * Lấy chi tiết 1 job post (public)
     */
    public function show($id)
    {
        $job = JobPost::with([
            'address',
            'experience',
            'industry',
            'position',
            'salary',
            'workType',
            'teacher'
        ])->findOrFail($id);

        return response()->json($job);
    }

    /**
     * Cập nhật job post (yêu cầu token)
     */
    public function update(Request $request, $id)
    {
        try {
            $user = $request->attributes->get('user');
            $job = JobPost::findOrFail($id);

            if ($job->teacher->lecturer_id != ($user['sub'] ?? null)) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            $validated = $request->validate([
                'job_title'    => 'sometimes|string|max:255',
                'company_name' => 'sometimes|string|max:255',
                'description'  => 'nullable|string',
                'application_deadline' => 'nullable|date',
            ]);

            $job->update($validated);

            return response()->json([
                'message' => 'Job post updated successfully',
                'job_post' => $job
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating job post: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating job post', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Xóa job post (yêu cầu token)
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->attributes->get('user');
            $job = JobPost::findOrFail($id);

            if ($job->teacher->lecturer_id != ($user['sub'] ?? null)) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            $job->delete();

            return response()->json(['message' => 'Job post deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting job post: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting job post', 'error' => $e->getMessage()], 500);
        }
    }

    public function positions()
    {
        $positions = JobPostPosition::select('id', 'position_name')->get();
        return response()->json($positions);
    }

    // public function industries()
    // {
    //     $industries = JobPostIndustry::select('id', 'industry_name as name')->get();
    //     return response()->json($industries);
    // }

    public function experiences()
    {
        $experiences = JobPostExperience::select('id', 'years as name')->get();
        return response()->json($experiences);
    }
}
