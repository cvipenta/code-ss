<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtReportImportRssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nt_report_import_rss', function (Blueprint $table) {
            $table->integer('import_id')->primary();
            $table->string('report_ID', 11)->default('0');
            $table->string('import_link')->default('');
            $table->dateTime('import_time')->default('0000-00-00 00:00:00');
            $table->integer('import_news_in_feed')->default(0);
            $table->integer('import_count')->default(0);
            $table->text('import_count_explain');
            $table->integer('import_category_id')->default(0);
            $table->integer('import_rss_id')->default(0)->index('import_rss_id');
            $table->integer('import_rss_subcategory')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nt_report_import_rss');
    }
}
