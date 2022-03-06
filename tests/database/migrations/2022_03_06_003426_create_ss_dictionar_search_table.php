<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsDictionarSearchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_dictionar_search', function (Blueprint $table) {
            $table->integer('s_id')->index('s_id');
            $table->string('s_string', 100)->default('');
            $table->integer('s_results')->default(0);
            $table->integer('s_hits')->default(0);
            $table->dateTime('s_time')->default('0000-00-00 00:00:00');
            $table->string('s_ip', 15)->default('');
            $table->timestamp('s_lastchange')->useCurrent()->useCurrentOnUpdate()->index('s_lastchange');
            
            $table->unique(['s_string`(20'], 's_string');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_dictionar_search');
    }
}
