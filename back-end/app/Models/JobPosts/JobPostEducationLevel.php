<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;

class JobPostEducationLevel extends Model
{
    protected $fillable = [
        'job_post_id',
        'education_level'
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
