<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobPostPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'position_name',
        'quantity',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
