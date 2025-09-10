<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPostIndustry extends Model
{
    use HasFactory;
    protected $table = 'job_post_industries';
    protected $fillable = ['job_post_id', 'industry_name'];
}
