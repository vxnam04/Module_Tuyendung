<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'job_title',
        'company_name',
        'description',
        'application_deadline',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function address()
    {
        return $this->hasOne(JobPostAddress::class);
    }

    public function experience()
    {
        return $this->hasOne(JobPostExperience::class);
    }

    public function industry()
    {
        return $this->hasOne(JobPostIndustry::class);
    }

    public function position()
    {
        return $this->hasOne(JobPostPosition::class);
    }

    public function salary()
    {
        return $this->hasOne(JobPostSalary::class);
    }

    public function workType()
    {
        return $this->hasOne(JobPostWorkType::class);
    }
}
