<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JobPosts\JobPost;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_account_id',
        'masv',
    ];


    public function studentCv()
    {
        return $this->belongsTo(StudentCv::class, 'student_cv_id');
    }
}
