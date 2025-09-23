<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;

class JobPostSkill extends Model
{
    protected $fillable = [
        'job_post_id',
        'skill_name'
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
