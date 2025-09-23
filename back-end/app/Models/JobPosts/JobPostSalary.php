<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;

class JobPostSalary extends Model
{
    protected $fillable = [
        'job_post_id',
        'salary_min',
        'salary_max',
        'currency'
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
