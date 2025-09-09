<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id(); // Khóa chính (id tự tăng)
            $table->foreignId('teacher_id') // Khóa ngoại tham chiếu sang bảng teachers
                ->constrained('teachers')
                ->onDelete('cascade'); // Nếu teacher bị xóa thì profile cũng bị xóa theo
            $table->string('specialization')->nullable(); // Chuyên ngành (có thể null)
            $table->integer('experience_years')->nullable(); // Số năm kinh nghiệm (có thể null)
            $table->text('bio')->nullable(); // Tiểu sử / mô tả giáo viên (có thể null)
            $table->timestamps(); // created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_profiles'); // Xóa bảng nếu rollback
    }
};
