<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobPostExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'years',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
