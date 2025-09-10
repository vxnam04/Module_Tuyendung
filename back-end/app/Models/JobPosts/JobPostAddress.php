<?php

namespace App\Models\JobPosts;

use Illuminate\Database\Eloquent\Model;

class JobPostAddress extends Model
{
    protected $table = 'job_post_addresses';

    protected $fillable = [
        'job_post_id',
        'street',
        'city',
        'state',
        'country',
        'postal_code'
    ];
}
