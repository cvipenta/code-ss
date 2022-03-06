<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsBannedWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_banned_words', function (Blueprint $table) {
            $table->integer('we_id')->primary();
            $table->string('we_string')->default('')->unique('we_string');
            $table->integer('we_reason')->default(1)->comment("motivul excluziunii 1. comun 2. trivial etc");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_banned_words');
    }
}
