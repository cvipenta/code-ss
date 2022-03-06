<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsArticoleCopyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_articole_copy', function (Blueprint $table) {
            $table->integer('art_id')->default(0)->index('art_id');
            $table->string('art_category')->default('0');
            $table->string('art_title')->default('');
            $table->string('art_keywords')->default('');
            $table->text('art_text');
            $table->string('art_link')->default('');
            $table->string('art_image')->nullable();
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
        Schema::dropIfExists('ss_articole_copy');
    }
}
