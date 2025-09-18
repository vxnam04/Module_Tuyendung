<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JobPosts\JobPost;
use App\Models\JobPosts\JobPostAddress;
use App\Models\JobPosts\JobPostExperience;
use App\Models\JobPosts\JobPostIndustry;
use App\Models\JobPosts\JobPostPosition;
use App\Models\JobPosts\JobPostSalary;
use App\Models\JobPosts\JobPostWorkType;
use App\Models\Teacher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class JobPostController extends Controller
{
    /**
     * Constructor
     * Middleware tự viết JWTAuthentication
     */
    public function __construct()
    {
        $this->middleware('jwt.auth')->only(['store', 'update', 'destroy']);
    }

    /**
     * Tạo job post mới (yêu cầu token)
     */
    public function store(Request $request)
    {
        try {
            // Lấy user từ JWT middleware
            $user = $request->attributes->get('user');
            if (!$user || !isset($user['sub'])) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Lấy teacher theo JWT sub
            $teacher = Teacher::where('lecturer_id', $user['sub'])->first();
            if (!$teacher) {
                return response()->json(['message' => 'Không tìm thấy teacher'], 404);
            }

            // Validate input
            $validated = $request->validate([
                'job_title'            => 'required|string|max:255',
                'company_name'         => 'required|string|max:255',
                'description'          => 'nullable|string',
                'application_deadline' => 'nullable|date',
                'street'      => 'nullable|string|max:255',
                'city'        => 'nullable|string|max:100',
                'state'       => 'nullable|string|max:100',
                'country'     => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'years_experience' => 'nullable|integer|min:0',
                'industry_name'    => 'nullable|string|max:100',
                'position_name'    => 'nullable|string|max:100',
                'quantity'         => 'nullable|integer|min:1',
                'salary_min' => 'nullable|integer|min:0',
                'salary_max' => 'nullable|integer|min:0',
                'work_type' => 'nullable|string|max:50',
            ]);

            // Tạo job post trong transaction
            $job = DB::transaction(function () use ($validated, $teacher) {
                $job = $teacher->jobPosts()->create([
                    'job_title'            => $validated['job_title'],
                    'company_name'         => $validated['company_name'],
                    'description'          => $validated['description'] ?? null,
                    'application_deadline' => $validated['application_deadline'] ?? null,
                ]);

                // Address
                if (!empty($validated['street']) || !empty($validated['city'])) {
                    $job->address()->create([
                        'street'      => $validated['street'] ?? null,
                        'city'        => $validated['city'] ?? null,
                        'state'       => $validated['state'] ?? null,
                        'country'     => $validated['country'] ?? null,
                        'postal_code' => $validated['postal_code'] ?? null,
                    ]);
                }

                // Experience
                if (isset($validated['years_experience'])) {
                    $job->experience()->create(['years' => $validated['years_experience']]);
                }

                // Industry
                if (!empty($validated['industry_name'])) {
                    $job->industry()->create(['industry_name' => $validated['industry_name']]);
                }

                // Position
                if (!empty($validated['position_name'])) {
                    $job->position()->create([
                        'position_name' => $validated['position_name'],
                        'quantity'      => $validated['quantity'] ?? 1,
                    ]);
                }

                // Salary
                if (isset($validated['salary_min']) || isset($validated['salary_max'])) {
                    $job->salary()->create([
                        'salary_min' => $validated['salary_min'] ?? null,
                        'salary_max' => $validated['salary_max'] ?? null,
                        'currency'   => 'VND',
                    ]);
                }

                // Work type
                if (!empty($validated['work_type'])) {
                    $job->workType()->create(['name' => $validated['work_type']]);
                }

                return $job;
            });

            return response()->json([
                'message'  => 'Job post created successfully',
                'job_post' => $job->load(['address', 'experience', 'industry', 'position', 'salary', 'workType', 'teacher']),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating job post: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error creating job post',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

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
}
