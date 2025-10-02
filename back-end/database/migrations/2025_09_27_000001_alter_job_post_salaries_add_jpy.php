<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // For MySQL enum, we need to modify the column to include JPY
        Schema::table('job_post_salaries', function (Blueprint $table) {
            \DB::statement("ALTER TABLE job_post_salaries MODIFY COLUMN currency ENUM('USD','VND','EUR','JPY','Other') DEFAULT 'VND'");
        });
    }

    public function down()
    {
        Schema::table('job_post_salaries', function (Blueprint $table) {
            \DB::statement("ALTER TABLE job_post_salaries MODIFY COLUMN currency ENUM('USD','VND','EUR','Other') DEFAULT 'VND'");
        });
    }
};



