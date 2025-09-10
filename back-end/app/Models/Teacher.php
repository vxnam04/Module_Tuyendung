<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JobPosts\JobPost;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'lecturer_id',
        'avatar_url',
    ];

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class, 'teacher_id');
    }
}
