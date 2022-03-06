<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsArticoleWwwhealthcomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_articole_wwwhealthcom', function (Blueprint $table) {
            $table->integer('art_id')->primary();
            $table->string('art_title')->default('');
            $table->text('art_text')->nullable();
            $table->text('art_text_raw');
            $table->string('art_link', 150)->default('');
            $table->integer('art_update')->default(0);
            $table->integer('art_hits');
            
            $table->index(['art_link`(30'], 'art_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_articole_wwwhealthcom');
    }
}
