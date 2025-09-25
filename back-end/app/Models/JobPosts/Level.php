<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = 'job_post_levels';

    protected $fillable = [
        'job_post_id',
        'name', // vì bảng bạn có cột name
    ];
}
