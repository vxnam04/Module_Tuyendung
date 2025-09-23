<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;

class JobPostExperience extends Model
{
    protected $fillable = [
        'job_post_id',
        'years'
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
