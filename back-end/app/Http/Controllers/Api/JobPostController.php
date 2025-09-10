<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\JobPosts\JobPost;
use App\Models\JobPosts\JobPostAddress;
use App\Models\JobPosts\JobPostExperience;
use App\Models\JobPosts\JobPostIndustry;
use App\Models\JobPosts\JobPostPosition;
use App\Models\JobPosts\JobPostSalary;
use App\Models\JobPosts\JobPostWorkType;
use App\Models\Teacher;

class JobPostController extends Controller
{
    /**
     * Tạo job post mới
     */
    public function store(Request $request)
    {
        try {
            // ✅ Lấy user từ JWT token (user có lecturer_id)
            $user = JWTAuth::parseToken()->authenticate();

            // ✅ Tìm teacher theo lecturer_id
            $teacher = Teacher::where('lecturer_id', $user->id)->first();
            if (!$teacher) {
                return response()->json([
                    'message' => 'Không tìm thấy teacher cho lecturer_id = ' . $user->id
                ], 404);
            }

            // ✅ Validate dữ liệu từ request
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

            // ✅ Transaction để đảm bảo dữ liệu đồng bộ
            $job = DB::transaction(function () use ($validated, $teacher) {
                // 1. job_posts
                $job = $teacher->jobPosts()->create([
                    'job_title'            => $validated['job_title'],
                    'company_name'         => $validated['company_name'],
                    'description'          => $validated['description'] ?? null,
                    'application_deadline' => $validated['application_deadline'] ?? null,
                ]);

                // 2. address
                if (!empty($validated['street']) || !empty($validated['city'])) {
                    $job->address()->create([
                        'street'      => $validated['street'] ?? null,
                        'city'        => $validated['city'] ?? null,
                        'state'       => $validated['state'] ?? null,
                        'country'     => $validated['country'] ?? null,
                        'postal_code' => $validated['postal_code'] ?? null,
                    ]);
                }

                // 3. experience
                if (isset($validated['years_experience'])) {
                    $job->experience()->create([
                        'years' => $validated['years_experience'],
                    ]);
                }

                // 4. industry
                if (!empty($validated['industry_name'])) {
                    $job->industry()->create([
                        'industry_name' => $validated['industry_name'],
                    ]);
                }

                // 5. position
                if (!empty($validated['position_name'])) {
                    $job->position()->create([
                        'position_name' => $validated['position_name'],
                        'quantity'      => $validated['quantity'] ?? 1,
                    ]);
                }

                // 6. salary
                if (isset($validated['salary_min']) || isset($validated['salary_max'])) {
                    $job->salary()->create([
                        'salary_min' => $validated['salary_min'] ?? null,
                        'salary_max' => $validated['salary_max'] ?? null,
                        'currency'   => 'VND',
                    ]);
                }

                // 7. work type
                if (!empty($validated['work_type'])) {
                    $job->workType()->create([
                        'name' => $validated['work_type'],
                    ]);
                }

                return $job;
            });

            return response()->json([
                'message'  => 'Job post created successfully',
                'job_post' => $job->load([
                    'address',
                    'experience',
                    'industry',
                    'position',
                    'salary',
                    'workType',
                    'teacher'
                ]),
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error creating job post: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error creating job post',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy tất cả job posts kèm quan hệ
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
     * Lấy chi tiết 1 job post
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
}
