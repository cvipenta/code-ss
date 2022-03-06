<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsArticoleMedicalnewstodayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_articole_medicalnewstoday', function (Blueprint $table) {
            $table->integer('mnt_id')->primary();
            $table->string('mnt_link', 10)->default('');
            $table->string('mnt_title_en')->default('');
            $table->string('mnt_title_ro')->default('');
            $table->string('mnt_key_en')->default('');
            $table->string('mnt_key_ro')->default('');
            $table->string('mnt_desc_en')->default('');
            $table->string('mnt_desc_ro')->default('');
            $table->text('mnt_content_en');
            $table->text('mnt_content_ro');
            
            $table->index(['mnt_title_en`(20'], 'mnt_title_en');
            $table->index(['mnt_title_ro`(20'], 'mnt_title_ro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_articole_medicalnewstoday');
    }
}
