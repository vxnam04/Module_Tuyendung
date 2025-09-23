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
        Schema::table('job_post_working_days', function (Blueprint $table) {
            $table->unsignedBigInteger('job_post_id')->after('id');
            $table->string('day_name')->after('job_post_id');

            $table->foreign('job_post_id')->references('id')->on('job_posts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('job_post_working_days', function (Blueprint $table) {
            $table->dropForeign(['job_post_id']);
            $table->dropColumn(['job_post_id', 'day_name']);
        });
    }
};
