<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('students')->insert([
            'student_account_id' => 1,
            'masv' => 'SV00001',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
