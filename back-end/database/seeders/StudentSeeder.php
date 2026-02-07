<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [];

        for ($i = 1; $i <= 30; $i++) {
            $students[] = [
                'student_account_id' => $i,
                'masv' => 'SV' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('students')->insert($students);
    }
}
