<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;

class JobPostWorkingDay extends Model
{
    protected $fillable = [
        'job_post_id',
        'day_name'
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
