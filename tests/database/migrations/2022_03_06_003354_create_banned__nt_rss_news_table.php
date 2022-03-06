<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannedNtRssNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banned__nt_rss_news', function (Blueprint $table) {
            $table->integer('news_id')->primary();
            $table->integer('news_rss_id')->default(0)->index('news_rss_id');
            $table->date('news_pubdate')->default('0000-00-00')->index('news_pubdate');
            $table->string('news_title')->nullable();
            $table->string('news_link')->default('');
            $table->text('news_description')->nullable();
            $table->string('news_category', 100)->nullable();
            $table->string('news_image')->nullable();
            $table->string('news_image_local', 20)->nullable();
            $table->integer('hits')->default(0);
            $table->string('md5_title', 32)->default('0')->unique('md5_title');
            $table->timestamp('last_change')->useCurrent()->useCurrentOnUpdate();
            $table->integer('status')->default(1)->comment("1 shown 0 not shown");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banned__nt_rss_news');
    }
}
