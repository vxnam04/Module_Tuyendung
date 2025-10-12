<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Student2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Chèn nhiều sinh viên cùng lúc
        DB::table('students')->insert([

            [
                'student_account_id' => 2,
                'masv' => 'SV00002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
