<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {


            // Xóa các cột thông tin cá nhân dư thừa
            $table->dropColumn(['full_name', 'email', 'phone', 'avatar_url', 'dob', 'gender']);

            // Thêm mã sinh viên
            $table->string('masv', 50)->unique()->after('student_account_id');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Xóa khóa ngoại trước
            $table->dropForeign(['student_account_id']);

            // Xóa student_code
            $table->dropColumn('masv');

            // Thêm lại các cột cũ
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('avatar_url')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
        });
    }
};
