<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Đổi tên user_id -> student_account_id
            $table->renameColumn('user_id', 'student_account_id');

            // Xóa các cột thông tin cá nhân dư thừa
            $table->dropColumn(['full_name', 'email', 'phone', 'avatar_url', 'dob', 'gender']);

            // Thêm mã sinh viên
            $table->string('masv', 50)->unique()->after('student_account_id');

            // Thêm foreign key tham chiếu tới student_account
            $table->foreign('student_account_id')
                ->references('id')
                ->on('student_account') // bảng bên module khác
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Xóa khóa ngoại trước
            $table->dropForeign(['student_account_id']);

            // Đổi lại tên cột
            $table->renameColumn('student_account_id', 'user_id');

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
