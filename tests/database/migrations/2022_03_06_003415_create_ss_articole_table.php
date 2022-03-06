<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsArticoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_articole', function (Blueprint $table) {
            $table->unsignedSmallInteger('art_id')->default(0)->primary();
            $table->unsignedSmallInteger('art_category')->default(0);
            $table->string('art_title', 1000)->default('');
            $table->string('art_keywords')->default('');
            $table->text('art_text');
            $table->string('art_source')->nullable();
            $table->string('art_link')->default('');
            $table->string('art_image')->nullable();
            $table->date('art_date')->comment("publication date");
            $table->integer('art_hits')->default(0);
            
            $table->index(['art_title`(20'], 'art_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_articole');
    }
}
