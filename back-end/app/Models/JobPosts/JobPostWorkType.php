<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobPostWorkType extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_post_id',
        'work_type',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }
}
