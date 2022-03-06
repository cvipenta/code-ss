<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsUseragentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_useragents', function (Blueprint $table) {
            $table->integer('ua_id')->primary();
            $table->string('ua_ip', 15)->default('');
            $table->string('ua_name')->default(' COMMENT 'HTTP_USER_AGENT'');
            
            $table->unique(['ua_ip', 'ua_name'], 'ua_ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_useragents');
    }
}
