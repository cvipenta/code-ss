<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsDictionarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_dictionar', function (Blueprint $table) {
            $table->integer('dictionar_id')->primary();
            $table->string('dictionar_termen', 100)->default('');
            $table->text('dictionar_definitie');
            $table->text('dictionar_articolereferinta');
            
            $table->index(['dictionar_termen`(10'], 'dictionar_termen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_dictionar');
    }
}
