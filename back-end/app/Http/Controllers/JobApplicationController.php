<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\StudentCv;
use App\Models\JobPosts\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobApplicationController extends Controller
{
    /**
     * Student apply job
     */
    public function apply(Request $request)
    {
        // ✅ LẤY USER ĐÚNG TỪ JWT MIDDLEWARE
        $user = $request->attributes->get('user');
        if (!$user || !isset($user['sub'])) {
            return response()->json(['message' => 'User chưa đăng nhập'], 401);
        }

        $studentId = $user['sub'];
        $cvId = $request->student_cv_id;

        // ✅ VALIDATE
        $request->validate([
            'job_post_id'   => 'required|integer|exists:job_posts,id',
            'cover_letter'  => 'nullable|string',
            'student_cv_id' => 'nullable|integer|exists:student_cvs,id',
            'full_name'     => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email',
            'title'         => 'nullable|string|max:255',
            'summary'       => 'nullable|string',
            'file'          => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        /**
         * ===============================
         * 1️⃣ XỬ LÝ CV
         * ===============================
         */

        // ❌ Không chọn CV có sẵn → upload CV mới
        if (!$cvId) {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('cvs'), $fileName);

                $cv = StudentCv::create([
                    'student_id' => $studentId,
                    'full_name'  => $request->full_name,
                    'phone'      => $request->phone,
                    'email'      => $request->email,
                    'title'      => $request->title,
                    'summary'    => $request->summary,
                    'file_url'   => url('cvs/' . $fileName),
                ]);

                $cvId = $cv->id;
            } else {
                // 👉 Không upload → lấy CV mới nhất
                $latestCv = StudentCv::where('student_id', $studentId)
                    ->latest('created_at')
                    ->first();

                if (!$latestCv) {
                    return response()->json(['message' => 'Bạn chưa có CV nào'], 400);
                }

                $cvId = $latestCv->id;
            }
        }

        /**
         * ===============================
         * 2️⃣ CHECK CV CÓ THUỘC STUDENT
         * ===============================
         */
        $cv = StudentCv::where('id', $cvId)
            ->where('student_id', $studentId)
            ->first();

        if (!$cv) {
            return response()->json([
                'message' => 'CV không hợp lệ hoặc không thuộc về bạn'
            ], 403);
        }

        /**
         * ===============================
         * 3️⃣ CHỐNG APPLY TRÙNG
         * (1 job – 1 student chỉ apply 1 lần)
         * ===============================
         */
        $exists = JobApplication::where('job_post_id', $request->job_post_id)
            ->whereHas('studentCv', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Bạn đã ứng tuyển công việc này rồi'
            ], 400);
        }

        /**
         * ===============================
         * 4️⃣ TẠO APPLICATION
         * ===============================
         */
        $application = JobApplication::create([
            'job_post_id'   => $request->job_post_id,
            'student_cv_id' => $cvId,
            'cv_status_id'  => 1, // pending
            'cover_letter'  => $request->cover_letter,
        ]);

        return response()->json([
            'message' => 'Ứng tuyển thành công',
            'data'    => $application
        ], 201);
    }

    /**
     * Student lấy tất cả application của mình
     */
    public function getAll(Request $request)
    {
        $user = $request->attributes->get('user');
        if (!$user || !isset($user['sub'])) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $studentId = $user['sub'];

        $applications = JobApplication::with(['studentCv', 'jobPost', 'status'])
            ->whereHas('studentCv', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json($applications);
    }

    /**
     * Student lấy CV mới nhất
     */
    public function getcvnew(Request $request)
    {
        $user = $request->attributes->get('user');
        if (!$user || !isset($user['sub'])) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $studentId = $user['sub'];

        $latestCv = StudentCv::where('student_id', $studentId)
            ->latest('created_at')
            ->first();

        return response()->json($latestCv);
    }

    /**
     * Lecturer lấy tất cả ứng viên apply vào job mình đăng
     */
    public function getAlllecturer(Request $request)
    {
        $user = $request->attributes->get('user');
        if (!$user || !isset($user['sub'])) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $teacherId = $user['sub'];

        $applications = JobApplication::with([
            'studentCv.student',
            'jobPost',
            'status'
        ])
            ->whereHas('jobPost', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json($applications);
    }

    /**
     * Lecturer xem ứng viên theo job_post
     */
    public function getApplicants(Request $request, $id)
    {
        $job = JobPost::with([
            'applications' => function ($q) {
                $q->with(['studentCv', 'status']);
            }
        ])->findOrFail($id);

        return response()->json($job->applications);
    }

    /**
     * Lecturer cập nhật trạng thái CV
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'cv_status_id' => 'required|integer|exists:cv_statuses,id'
        ]);

        $application = JobApplication::findOrFail($id);
        $application->cv_status_id = $request->cv_status_id;
        $application->save();

        return response()->json([
            'message' => 'Cập nhật trạng thái thành công',
            'data'    => $application
        ]);
    }

    /**
     * Lấy tất cả CV mà sinh viên đã nộp
     */
    public function getMyApplications(Request $request)
    {
        $user = $request->attributes->get('user');
        if (!$user || !isset($user['sub'])) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $studentId = $user['sub'];

        $applications = JobApplication::with(['studentCv', 'jobPost', 'status'])
            ->whereHas('studentCv', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json($applications);
    }

    /**
     * Lấy top 8 ngành nghề có nhiều ứng tuyển nhất
     */
    public function getTopIndustries()
    {
        $topIndustries = DB::table('job_post_industries as jpi')
            ->join('job_posts as jp', 'jp.id', '=', 'jpi.job_post_id')
            ->leftJoin('job_applications as ja', 'ja.job_post_id', '=', 'jp.id')
            ->select(
                'jpi.industry_name',
                DB::raw('COUNT(DISTINCT ja.id) as applied_count')
            )
            ->where('jpi.industry_name', 'not like', '%khác%')
            ->groupBy('jpi.industry_name')
            ->orderByDesc('applied_count')
            ->limit(8)
            ->get();

        return response()->json($topIndustries);
    }
}
