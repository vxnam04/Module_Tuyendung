<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;

class JobPostWorkingTime extends Model
{
    protected $fillable = [
        'job_post_id',
        'start_time',
        'end_time'
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
