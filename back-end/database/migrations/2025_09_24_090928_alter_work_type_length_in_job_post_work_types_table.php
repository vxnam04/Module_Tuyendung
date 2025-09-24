<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_post_work_types', function (Blueprint $table) {
            $table->string('work_type', 50)->change(); // tăng độ dài lên 50 ký tự
        });
    }

    public function down(): void
    {
        Schema::table('job_post_work_types', function (Blueprint $table) {
            $table->string('work_type', 10)->change(); // trả về mặc định cũ nếu cần
        });
    }
};
