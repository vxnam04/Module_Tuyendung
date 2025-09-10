<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobPosts\JobPostAddress;

class JobPostAddressController extends Controller
{
    public function index()
    {
        // Lấy danh sách distinct city (có thể thêm state, country tùy bạn)
        $locations = JobPostAddress::select('city', 'state', 'country')
            ->distinct()
            ->get();

        return response()->json($locations);
    }
}
