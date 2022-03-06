<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsClicksLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_clicks_location', function (Blueprint $table) {
            $table->integer('click_id')->default(0)->primary();
            $table->string('click_url_from')->default('');
            $table->string('click_url_to')->default('');
            $table->string('click_ip', 15)->default('');
            $table->timestamp('click_time')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_clicks_location');
    }
}
