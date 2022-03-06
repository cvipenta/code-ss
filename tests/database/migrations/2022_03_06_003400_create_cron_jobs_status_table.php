<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronJobsStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_jobs_status', function (Blueprint $table) {
            $table->string('job_name', 30)->default('')->unique('job_name');
            $table->dateTime('job_start')->default('0000-00-00 00:00:00');
            $table->dateTime('job_end')->default('0000-00-00 00:00:00');
            $table->boolean('job_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cron_jobs_status');
    }
}
