<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cv_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Tên trạng thái CV: Tiếp nhận, Phù hợp, Hẹn phỏng vấn, ...');
            $table->timestamps();
        });

        // Tạo dữ liệu mặc định
        DB::table('cv_statuses')->insert([
            ['name' => 'Tiếp nhận', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Phù hợp', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hẹn phỏng vấn', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gửi đề nghị', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nhận việc', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chưa phù hợp', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_statuses');
    }
};
