<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'avatar_url',
    ];

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }
}
