<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustriesSeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            ['job_post_id' => 1, 'industry_name' => 'Công nghệ thông tin'],
            ['job_post_id' => 1, 'industry_name' => 'Phần mềm / Lập trình'],
            ['job_post_id' => 1, 'industry_name' => 'Kinh doanh / Sales'],
            ['job_post_id' => 1, 'industry_name' => 'Marketing / Truyền thông'],
            ['job_post_id' => 1, 'industry_name' => 'Tài chính – Ngân hàng'],
            ['job_post_id' => 1, 'industry_name' => 'Kế toán / Kiểm toán'],
            ['job_post_id' => 1, 'industry_name' => 'Nhân sự / Hành chính'],
            ['job_post_id' => 1, 'industry_name' => 'Thiết kế – Mỹ thuật / Creative'],
            ['job_post_id' => 1, 'industry_name' => 'Y tế – Dược'],
            ['job_post_id' => 1, 'industry_name' => 'Giáo dục – Đào tạo'],
            ['job_post_id' => 1, 'industry_name' => 'Du lịch – Nhà hàng – Khách sạn'],
            ['job_post_id' => 1, 'industry_name' => 'Sản xuất – Vận hành'],
            ['job_post_id' => 1, 'industry_name' => 'Xây dựng / Kiến trúc'],
            ['job_post_id' => 1, 'industry_name' => 'Logistics / Vận tải'],
            ['job_post_id' => 1, 'industry_name' => 'Điện tử – Điện lạnh'],
            ['job_post_id' => 1, 'industry_name' => 'Cơ khí – Chế tạo'],
            ['job_post_id' => 1, 'industry_name' => 'Luật / Pháp lý'],
            ['job_post_id' => 1, 'industry_name' => 'Báo chí / Truyền hình'],
            ['job_post_id' => 1, 'industry_name' => 'Nông nghiệp / Thủy sản'],
            ['job_post_id' => 1, 'industry_name' => 'Môi trường / Xử lý chất thải'],
            ['job_post_id' => 1, 'industry_name' => 'Khoa học – Nghiên cứu'],
            ['job_post_id' => 1, 'industry_name' => 'Chăm sóc khách hàng / Support'],
            ['job_post_id' => 1, 'industry_name' => 'Bất động sản'],
            ['job_post_id' => 1, 'industry_name' => 'Hàng không / Vận tải hàng không'],
            ['job_post_id' => 1, 'industry_name' => 'Thời trang / Mỹ phẩm'],
            ['job_post_id' => 1, 'industry_name' => 'Điện / Năng lượng'],
            ['job_post_id' => 1, 'industry_name' => 'Dịch vụ / Tư vấn'],
            ['job_post_id' => 1, 'industry_name' => 'Công nghệ sinh học'],
            ['job_post_id' => 1, 'industry_name' => 'An ninh – Quân đội – Cảnh sát'],
            ['job_post_id' => 1, 'industry_name' => 'Khác'],
        ];

        DB::table('job_post_industries')->insert($industries);
    }
}
