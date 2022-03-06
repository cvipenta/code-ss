<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsReferersStringTopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_referers_string_top', function (Blueprint $table) {
            $table->integer('top_id')->primary();
            $table->integer('top_contor')->default(0);
            $table->string('top_string')->default('')->unique('top_uri');
            $table->string('top_uri')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_referers_string_top');
    }
}
