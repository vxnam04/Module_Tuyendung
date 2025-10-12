<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobPosts\JobPost;

class JobApplication extends Model
{
    protected $fillable = [
        'job_post_id',
        'student_cv_id',
        'cv_status_id',
        'cover_letter',
    ];

    // Nếu bảng job_applications có cột job_post_id
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }

    // JobApplication.php
    public function studentCv()
    {
        return $this->belongsTo(StudentCv::class, 'student_cv_id');
    }

    public function status()
    {
        return $this->belongsTo(CvStatus::class, 'cv_status_id');
    }
}
