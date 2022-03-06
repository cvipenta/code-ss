<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsIpDenyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_ip_deny', function (Blueprint $table) {
            $table->string('deny_ip', 15)->unique('deny_ip');
            $table->integer('deny_count');
            $table->dateTime('deny_starttime');
            $table->timestamp('deny_lastcheck')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_ip_deny');
    }
}
