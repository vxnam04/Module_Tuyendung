<?php

namespace App\Models\JobPosts;

use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'job_title',
        'company_name',
        'description',
        'application_deadline',
        'contact_email',
        'contact_phone',
    ];

    // Địa điểm làm việc (1 job nhiều địa điểm)
    public function addresses()
    {
        return $this->hasMany(JobPostAddress::class);
    }

    // Loại công việc (1 job nhiều loại)
    public function workTypes()
    {
        return $this->hasMany(JobPostWorkType::class);
    }

    // Lương (1 job 1 lương)
    public function salary()
    {
        return $this->hasOne(JobPostSalary::class);
    }

    // Kinh nghiệm (1 job nhiều kinh nghiệm, nếu cần)
    public function experiences()
    {
        return $this->hasMany(JobPostExperience::class);
    }

    // Kỹ năng (1 job nhiều kỹ năng)
    public function skills()
    {
        return $this->hasMany(JobPostSkill::class);
    }

    // levels
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }


    // Trình độ học vấn
    public function educationLevels()
    {
        return $this->hasMany(JobPostEducationLevel::class);
    }

    // Benefits
    public function benefits()
    {
        return $this->hasMany(JobPostBenefit::class);
    }

    // Ngày làm việc
    public function workingDays()
    {
        return $this->hasMany(JobPostWorkingDay::class);
    }

    // Thời gian làm việc
    public function workingTimes()
    {
        return $this->hasMany(JobPostWorkingTime::class);
    }

    // Teacher (người tạo job post)
    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'teacher_id');
    }
}
