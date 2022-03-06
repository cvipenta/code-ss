<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtRssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nt_rss', function (Blueprint $table) {
            $table->integer('rss_id')->primary();
            $table->integer('rss_subcategory')->default(0)->index('rss_subcategory');
            $table->string('rss_url')->default('');
            $table->string('rss_news_category', 100)->nullable();
            $table->dateTime('rss_last_update')->default('0000-00-00 00:00:00');
            $table->unsignedInteger('rss_last_update_nr')->default(0);
            $table->integer('rss_update_frequency')->default(12)->comment("no of hours between updates");
            $table->boolean('rss_status')->default(1)->comment("1 active 0 inactive");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nt_rss');
    }
}
