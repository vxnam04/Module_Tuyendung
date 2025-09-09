<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Như vậy: giáo viên không cần quan hệ trực tiếp với job_applications, vì thông qua job_posts đã quản lý được toàn bộ CV apply.
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->constrained('job_posts')->onDelete('cascade');
            $table->foreignId('student_cv_id')->constrained('student_cvs')->onDelete('cascade');
            $table->foreignId('cv_status_id')->constrained('cv_statuses')->onDelete('cascade')->comment('Trạng thái ứng tuyển');
            $table->text('cover_letter')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
