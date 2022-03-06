<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronSemaphoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_semaphore', function (Blueprint $table) {
            $table->string('cron_name')->unique('cron_name');
            $table->integer('cron_running_status');
            $table->timestamp('cron_last_access')->default('0000-00-00 00:00:00')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cron_semaphore');
    }
}
