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
        Schema::table('student_cvs', function (Blueprint $table) {
            $table->string('full_name')->after('student_id')->comment('Họ và tên');
            $table->string('phone', 20)->after('full_name')->comment('Số điện thoại');
            $table->string('email')->after('phone')->comment('Email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_cvs', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'phone', 'email']);
        });
    }
};
