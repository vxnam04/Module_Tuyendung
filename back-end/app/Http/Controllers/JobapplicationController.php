<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\StudentCv;
use App\Models\JobPosts\JobPost;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    /**
     * Student apply job
     */
    public function apply(Request $request)
    {
        $user = $request->get('user'); // Lấy user từ middleware JWT
        if (!$user) {
            return response()->json(['message' => 'User chưa đăng nhập'], 401);
        }

        $studentId = $user['sub']; // ID sinh viên từ token
        $cvId = $request->student_cv_id;

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

        // Nếu không chọn CV có sẵn → kiểm tra upload CV mới
        if (!$cvId) {
            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('cvs', 'public');

                $cv = StudentCv::create([
                    'student_id' => $studentId,
                    'full_name'  => $request->full_name,
                    'phone'      => $request->phone,
                    'email'      => $request->email,
                    'title'      => $request->title,
                    'summary'    => $request->summary,
                    'file_url'   => $path,
                ]);

                $cvId = $cv->id;
            } else {
                $latestCv = StudentCv::where('student_id', $studentId)
                    ->latest('created_at')
                    ->first();

                if (!$latestCv) {
                    return response()->json(['message' => 'Bạn chưa có CV nào'], 400);
                }
                $cvId = $latestCv->id;
            }
        }

        // Kiểm tra nếu đã apply job này với cùng CV
        $exists = JobApplication::where('job_post_id', $request->job_post_id)
            ->where('student_cv_id', $cvId)
            ->first();

        if ($exists) {
            return response()->json(['message' => 'Bạn đã ứng tuyển công việc này rồi'], 400);
        }

        $application = JobApplication::create([
            'job_post_id'   => $request->job_post_id,
            'student_cv_id' => $cvId,
            'cv_status_id'  => 1, // default: pending
            'cover_letter'  => $request->cover_letter,
        ]);

        return response()->json([
            'message' => 'Ứng tuyển thành công',
            'data'    => $application
        ]);
    }

    /**
     * Student lấy tất cả application của mình
     */
    public function getAll(Request $request)
    {
        $user = $request->get('user');
        if (!$user) {
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
        $user = $request->get('user');
        if (!$user) {
            return response()->json(['message' => 'User chưa đăng nhập'], 401);
        }

        $studentId = $user['sub'];

        $latestCv = StudentCv::where('student_id', $studentId)
            ->latest('created_at')
            ->first();

        return response()->json($latestCv);
    }

    /**
     * Lecturer xem danh sách ứng viên theo job_post
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
}
