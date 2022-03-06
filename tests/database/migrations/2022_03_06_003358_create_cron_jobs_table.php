<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->integer('cron_id')->primary();
            $table->string('cron_name')->nullable();
            $table->string('cron_details')->nullable();
            $table->string('cron_fileonserver')->nullable();
            $table->string('cron_result')->nullable();
            $table->integer('cron_frequency')->default(12)->comment("defined in hours");
            $table->integer('cron_status')->default(1)->comment("1 - active 0 - inactive ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cron_jobs');
    }
}
