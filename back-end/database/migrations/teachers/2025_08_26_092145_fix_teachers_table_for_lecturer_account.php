<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Xóa bảng teacher_profiles nếu tồn tại
        Schema::dropIfExists('teacher_profiles');

        // Xóa FK và cột department_id trong teachers trước khi drop departments
        if (Schema::hasTable('teachers') && Schema::hasColumn('teachers', 'department_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropForeign(['department_id']); // drop FK
                $table->dropColumn('department_id');   // drop column
            });
        }

        // Xóa bảng departments
        Schema::dropIfExists('departments');

        // Sửa bảng teachers
        if (Schema::hasTable('teachers')) {
            Schema::table('teachers', function (Blueprint $table) {
                // Đổi user_id -> lecturer_id nếu tồn tại
                if (Schema::hasColumn('teachers', 'user_id')) {
                    $table->renameColumn('user_id', 'lecturer_id');
                }

                // Xóa các cột thông tin cá nhân nếu tồn tại
                foreach (['full_name', 'email', 'phone'] as $col) {
                    if (Schema::hasColumn('teachers', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tạo lại bảng departments trống
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Tạo lại bảng teacher_profiles
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->string('specialization')->nullable();
            $table->integer('experience_years')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        // Sửa lại bảng teachers
        if (Schema::hasTable('teachers')) {
            Schema::table('teachers', function (Blueprint $table) {
                // Đổi lecturer_id -> user_id
                if (Schema::hasColumn('teachers', 'lecturer_id')) {
                    $table->renameColumn('lecturer_id', 'user_id');
                }

                // Thêm lại các cột cá nhân
                $table->string('full_name');
                $table->string('email')->nullable();
                $table->string('phone', 20)->nullable();
                $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            });
        }
    }
};
