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
        // bảng này lưu các cv mà sinh gửi lên, lưu bên phía sinh viên thôi 
        Schema::create('student_cvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('title')->comment('Tên CV, ví dụ: CV Frontend Developer');
            $table->string('file_url')->nullable()->comment('File CV upload (PDF, DOCX)');
            $table->text('summary')->nullable()->comment('Tóm tắt bản thân / mục tiêu nghề nghiệp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_cvs');
    }
};
