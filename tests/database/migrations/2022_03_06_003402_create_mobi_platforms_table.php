<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobiPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobi_platforms', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 40)->default('')->unique('name');
            $table->string('shortname', 32)->default('');
            $table->mediumInteger('imgwidth')->default(0);
            $table->mediumInteger('imgheight')->default(0);
            $table->mediumInteger('screenwidth')->default(0);
            $table->mediumInteger('screenheight')->default(0);
            $table->string('imgtype', 5)->default('');
            $table->string('parser', 16)->default('wml');
            $table->text('agent');
            $table->string('agentb', 32)->default('');
            $table->unsignedInteger('manufacturer')->default(0);
            $table->boolean('3g')->default(0);
            $table->unsignedSmallInteger('wifi')->default(0)->index('wifi');
            
            $table->index(['agent`(10)', 'agentb`(10'], 'Agent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobi_platforms');
    }
}
