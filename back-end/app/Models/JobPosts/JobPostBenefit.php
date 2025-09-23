<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;

class JobPostBenefit extends Model
{
    protected $fillable = [
        'job_post_id',
        'benefit_type', // main / additional
        'description'
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
