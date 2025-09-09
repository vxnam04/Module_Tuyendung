<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobPostIndustriesSeeder extends Seeder
{
    public function run()
    {
        // 1️⃣ Tạo 1 teacher demo
        // Nếu bạn có module users khác, đảm bảo user_id tồn tại
        $userId = 1; // ID user tồn tại trong bảng users
        $departmentId = null; // hoặc ID department tồn tại nếu muốn

        $teacherId = DB::table('teachers')->insertGetId([
            'user_id' => $userId,
            'full_name' => 'Demo Teacher',
            'email' => 'demo.teacher@example.com',
            'phone' => '0123456789',
            'avatar_url' => null,
            'department_id' => $departmentId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2️⃣ Tạo job_post demo liên kết teacher vừa tạo
        $jobPostId = DB::table('job_posts')->insertGetId([
            'teacher_id' => $teacherId,
            'job_title' => 'Demo Job',
            'company_name' => 'Demo Company',
            'description' => 'Mô tả công việc demo',
            'application_deadline' => now()->addWeeks(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3️⃣ Danh sách ngành nghề
        $industries = [
            'Nhân viên kinh doanh',
            'Kế toán',
            'Marketing',
            'Hành chính nhân sự',
            'Chăm sóc khách hàng',
            'Ngân hàng',
            'IT',
            'Lao động phổ thông',
            'Senior',
            'Kỹ sư xây dựng',
            'Thiết kế đồ họa',
            'Bất động sản',
            'Giáo dục',
            'Telesales',
        ];

        // 4️⃣ Chèn ngành nghề cho job_post vừa tạo
        foreach ($industries as $industry) {
            DB::table('job_post_industries')->insert([
                'job_post_id' => $jobPostId,
                'industry_name' => $industry,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
