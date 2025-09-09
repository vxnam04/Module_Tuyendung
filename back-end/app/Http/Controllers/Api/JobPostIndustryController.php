<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // 👈 cần có dòng này
use App\Models\JobPostIndustry;
use Illuminate\Http\Request;

class JobPostIndustryController extends Controller
{
    public function index()
    {
        $industries = JobPostIndustry::select('industry_name')
            ->distinct()
            ->orderBy('industry_name')
            ->get();

        return response()->json($industries);
    }
}
