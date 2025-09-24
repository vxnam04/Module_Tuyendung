<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobPostLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels = [
            'Thực tập sinh',
            'Nhân viên',
            'Trưởng nhóm',
            'Quản lý',
            'Giám đốc',
        ];

        $now = Carbon::now();

        foreach ($levels as $level) {
            DB::table('job_post_levels')->insert([
                'job_post_id' => 2, // Thay bằng job_post_id phù hợp nếu bạn muốn liên kết với bài đăng cụ thể
                'name' => $level,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
