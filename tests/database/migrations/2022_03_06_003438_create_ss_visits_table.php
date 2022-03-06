<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_visits', function (Blueprint $table) {
            $table->integer('visit_hitID')->primary();
            $table->char('visit_sessionID', 10)->default('');
            $table->string('visit_uri')->default('');
            $table->unsignedInteger('visit_ip')->comment("IP2LONG");
            $table->boolean('visit_type')->default(1)->comment("1. normal  2. bot  3.devIP");
            $table->dateTime('visit_time')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_visits');
    }
}
