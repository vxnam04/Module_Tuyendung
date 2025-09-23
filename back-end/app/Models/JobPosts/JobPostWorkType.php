<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;

class JobPostWorkType extends Model
{
    protected $fillable = [
        'job_post_id',
        'work_type'
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
