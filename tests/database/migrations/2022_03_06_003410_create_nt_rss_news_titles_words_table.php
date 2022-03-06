<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtRssNewsTitlesWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nt_rss_news_titles_words', function (Blueprint $table) {
            $table->string('ntw_word')->default('')->unique('ntw_word');
            $table->text('ntw_newsids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nt_rss_news_titles_words');
    }
}
