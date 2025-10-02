<?php

// app/Models/StudentCv.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCv extends Model
{
    use HasFactory;

    protected $table = 'student_cvs';

    protected $fillable = [
        'student_id',
        'full_name',
        'phone',
        'email',
        'title',
        'file_url',
        'summary',
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'student_cv_id');
    }
    public function Student()
    {
        return $this->hasMany(Student::class, 'student_id');
    }
}
