<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvStatus extends Model
{
    protected $fillable = [
        'name'
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'cv_status_id');
    }
}
