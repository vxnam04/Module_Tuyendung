<?php

namespace App\Http\Controllers\Api;

use App\Models\JobPosts\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JobPosts\JobPost;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Support\Facades\Log;
use App\Models\JobPosts\JobPostPosition;
use App\Models\JobPosts\JobPostExperience;
use App\Models\JobPosts\JobPostIndustry;
use App\Models\JobPosts\JobPostAddress;
use App\Models\JobPosts\JobPostWorkType;

class JobPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth')->only(['store', 'update', 'destroy']);
    }

    /**
     * Tạo mới job post
     */
    public function store(Request $request)
    {
        try {
            $user = $request->attributes->get('user');
            if (!$user || !isset($user['sub'])) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $teacher = Teacher::where('lecturer_id', $user['sub'])->first();
            if (!$teacher) return response()->json(['message' => 'Không tìm thấy teacher'], 404);

            $validated = $request->validate([
                'job_title'            => 'required|string|max:255',
                'company_name'         => 'required|string|max:255',
                'positions'            => 'required|string|max:255', // frontend gửi string
                'description'          => 'nullable|string',
                'application_deadline' => 'nullable|date',
                'contact_email'        => 'nullable|email',
                'contact_phone'        => 'nullable|string|max:20',

                'addresses'            => 'nullable|array',
                'addresses.*.street'   => 'nullable|string|max:255',
                'addresses.*.city'     => 'nullable|string|max:100',
                'addresses.*.state'    => 'nullable|string|max:100',

                'job_type'             => 'required|string',

                'salary'               => 'nullable|array',
                'salary.salary_min'    => 'nullable|integer|min:0',
                'salary.salary_max'    => 'nullable|integer|min:0',

                'experience_required'  => 'nullable|integer|min:0',
                'skills_required'      => 'nullable|array',
                'skills_required.*'    => 'string|max:100',

                'education_level'      => 'nullable|string|max:100',

                'benefits'             => 'string|max:255',
                'additional_benefits'  => 'string|max:255',

                'working_days'         => 'nullable',
                'working_time'         => 'nullable|array',
                'working_time.start'   => 'nullable|string',
                'working_time.end'     => 'nullable|string',

                'level_id'             => 'nullable|string|max:100', // lưu name trực tiếp
                'industries' => 'required|string|max:100', // chỉ nhận 1 ngành nghề


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

                // Positions
                if (!empty($validated['positions'])) {
                    $job->positions()->create([
                        'position_name' => $validated['positions']
                    ]);
                }

                // Addresses
                if (!empty($validated['addresses'])) {
                    foreach ($validated['addresses'] as $loc) {
                        $job->addresses()->create($loc);
                    }
                }

                // Job type
                if (!empty($validated['job_type'])) {
                    $job->workTypes()->create(['work_type' => $validated['job_type']]);
                }

                // Salary
                if (!empty($validated['salary'])) {
                    $job->salary()->create([
                        'salary_min' => $validated['salary']['salary_min'] ?? null,
                        'salary_max' => $validated['salary']['salary_max'] ?? null,
                        'currency'   => 'VND'
                    ]);
                }

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
                    $job->benefits()->create([
                        'benefit_type' => 'main',
                        'description'  => $validated['benefits'],
                    ]);
                }

                if (!empty($validated['additional_benefits'])) {
                    $job->benefits()->create([
                        'benefit_type' => 'additional',
                        'description'  => $validated['additional_benefits'],
                    ]);
                }

                // Working days
                $workingDays = $validated['working_days'] ?? [];
                if (is_string($workingDays)) $workingDays = [$workingDays];
                foreach ($workingDays as $day) {
                    if (!empty($day)) $job->workingDays()->create(['day_name' => $day]);
                }

                // Working times
                if (!empty($validated['working_time'])) {
                    $job->workingTimes()->create([
                        'start_time' => $validated['working_time']['start'] ?? null,
                        'end_time'   => $validated['working_time']['end'] ?? null
                    ]);
                }

                // Levels
                if (!empty($validated['level_id'])) {
                    Level::create([
                        'job_post_id' => $job->id,
                        'name'        => $validated['level_id'], // lưu trực tiếp name
                    ]);
                }

                // Industries
                if (!empty($validated['industries'])) {
                    $job->industries()->create([
                        'industry_name' => $validated['industries'],
                    ]);
                }


                return $job;
            });

            return response()->json([
                'message' => 'Job post created successfully',
                'job_post' => $job->load([
                    'positions',
                    'addresses',
                    'workTypes',
                    'salary',
                    'experiences',
                    'skills',
                    'educationLevels',
                    'benefits',
                    'workingDays',
                    'workingTimes',
                    'teacher',
                    'level',
                    'industries'
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

    /**
     * Lấy tất cả job posts
     */
    public function index()
    {
        try {
            $jobs = JobPost::with([
                'addresses',
                'positions',
                'industries',
                'workTypes',
                'salary',
                'experiences',
                'skills',
                'educationLevels',
                'benefits',
                'workingDays',
                'workingTimes',
                'teacher',
                'level'
            ])->latest()->get();

            return response()->json([
                'success' => true,
                'data' => $jobs
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching job posts: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error fetching job posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy chi tiết 1 job post
     */
    public function show($id)
    {
        $job = JobPost::with([
            'addresses',
            'positions',
            'industries',
            'experiences',
            'salary',
            'skills',
            'educationLevels',
            'benefits',
            'workingDays',
            'workingTimes',
            'workTypes',
            'teacher',
            'level'
        ])->findOrFail($id);

        return response()->json($job);
    }

    /**
     * Cập nhật job post
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
                'level_id'     => 'nullable|string|max:100', // lưu name trực tiếp
                'industries'   => 'nullable|array',
                'industries.*' => 'string|max:100',
            ]);

            $job->update($request->only(['job_title', 'company_name', 'description', 'application_deadline', 'quantity']));

            // Update level
            if (array_key_exists('level_id', $validated)) {
                if ($validated['level_id']) {
                    Level::updateOrCreate(
                        ['job_post_id' => $job->id],
                        ['name' => $validated['level_id']]
                    );
                } else {
                    $job->level()->delete();
                }
            }

            // Update industries
            if (array_key_exists('industries', $validated)) {
                $job->industries()->delete(); // xóa cũ
                foreach ($validated['industries'] as $industryName) {
                    $job->industries()->create([
                        'industry_name' => $industryName
                    ]);
                }
            }

            return response()->json([
                'message' => 'Job post updated successfully',
                'job_post' => $job->fresh()->load(['positions', 'level', 'industries'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating job post: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating job post', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Xóa job post
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

    /**
     * Lấy positions
     */
    public function positions()
    {
        $positions = JobPostPosition::select('id', 'position_name')->get();
        return response()->json($positions);
    }

    /**
     * Lấy experiences
     */
    public function experiences()
    {
        $experiences = JobPostExperience::select('id', 'years as name')->get();
        return response()->json($experiences);
    }

    /**
     * Lấy levels hard-code
     */
    public function levels()
    {
        return response()->json([
            ['id' => 1, 'name' => 'Thực tập'],
            ['id' => 2, 'name' => 'Nhân viên'],
            ['id' => 3, 'name' => 'Trưởng nhóm'],
            ['id' => 4, 'name' => 'Quản lý'],
            ['id' => 5, 'name' => 'Giám đốc'],
        ]);
    }

    /**
     * Lấy industries hard-code
     */
    public function industries()
    {
        return response()->json([
            ['id' => 1, 'name' => 'Công nghệ thông tin'],
            ['id' => 2, 'name' => 'Phần mềm / Lập trình'],
            ['id' => 3, 'name' => 'Kinh doanh / Sales'],
            ['id' => 4, 'name' => 'Marketing / Truyền thông'],
            ['id' => 5, 'name' => 'Tài chính – Ngân hàng'],
            ['id' => 6, 'name' => 'Kế toán / Kiểm toán'],
            ['id' => 7, 'name' => 'Nhân sự / Hành chính'],
            ['id' => 8, 'name' => 'Thiết kế – Mỹ thuật / Creative'],
            ['id' => 9, 'name' => 'Y tế – Dược'],
            ['id' => 10, 'name' => 'Giáo dục – Đào tạo'],
            ['id' => 11, 'name' => 'Du lịch – Nhà hàng – Khách sạn'],
            ['id' => 12, 'name' => 'Sản xuất – Vận hành'],
            ['id' => 13, 'name' => 'Xây dựng / Kiến trúc'],
            ['id' => 14, 'name' => 'Logistics / Vận tải'],
            ['id' => 15, 'name' => 'Điện tử – Điện lạnh'],
            ['id' => 16, 'name' => 'Cơ khí – Chế tạo'],
            ['id' => 17, 'name' => 'Luật / Pháp lý'],
            ['id' => 18, 'name' => 'Báo chí / Truyền hình'],
            ['id' => 19, 'name' => 'Nông nghiệp / Thủy sản'],
            ['id' => 20, 'name' => 'Môi trường / Xử lý chất thải'],
            ['id' => 21, 'name' => 'Khoa học – Nghiên cứu'],
            ['id' => 22, 'name' => 'Chăm sóc khách hàng / Support'],
            ['id' => 23, 'name' => 'Bất động sản'],
            ['id' => 24, 'name' => 'Hàng không / Vận tải hàng không'],
            ['id' => 25, 'name' => 'Thời trang / Mỹ phẩm'],
            ['id' => 26, 'name' => 'Điện / Năng lượng'],
            ['id' => 27, 'name' => 'Dịch vụ / Tư vấn'],
            ['id' => 28, 'name' => 'Công nghệ sinh học'],
            ['id' => 29, 'name' => 'An ninh – Quân đội – Cảnh sát'],
            ['id' => 30, 'name' => 'Khác'],
        ]);
    }

    /**
     * Lấy location
     */
    public function location()
    {
        $locations = JobPostAddress::select('street', 'city', 'state')->get();
        return response()->json($locations);
    }
}
