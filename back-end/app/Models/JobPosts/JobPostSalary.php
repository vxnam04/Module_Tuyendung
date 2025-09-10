<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobPostSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'salary_min',
        'salary_max',
        'currency',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
