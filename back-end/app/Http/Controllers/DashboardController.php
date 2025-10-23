<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobPosts\JobPost;
use App\Models\JobApplication;
use App\Models\CvStatus;

class DashboardController extends Controller
{
    /**
     * Lấy dữ liệu dashboard cho admin
     */
    public function index()
    {
        try {
            // Tổng số tin tuyển dụng
            $totalJobPosts = JobPost::count();

            // Tổng số ứng viên đã nộp
            $totalApplications = JobApplication::count();

            // Số CV đang chờ phê duyệt (status = 'Pending')
            $pendingStatus = CvStatus::where('name', 'Pending')->first();
            $pendingApproval = $pendingStatus
                ? JobApplication::where('cv_status_id', $pendingStatus->id)->count()
                : 0;

            // Tỉ lệ thành công = số CV Approved / tổng CV
            $approvedStatus = CvStatus::where('name', 'Approved')->first();
            $approvedCount = $approvedStatus
                ? JobApplication::where('cv_status_id', $approvedStatus->id)->count()
                : 0;

            $successRate = $totalApplications ? round($approvedCount / $totalApplications * 100, 0) : 0;

            // Lấy 10 job mới nhất kèm teacher và applications
            $latestJobs = JobPost::with(['teacher', 'applications'])
                ->latest()
                ->take(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_job_posts' => $totalJobPosts,
                    'total_applications' => $totalApplications,
                    'pending_approval' => $pendingApproval,
                    'success_rate' => $successRate,
                    'latest_jobs' => $latestJobs,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching dashboard data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
