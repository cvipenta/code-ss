<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_admin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 20)->nullable()->unique('username');
            $table->string('parola', 20)->nullable();
            $table->string('pages')->nullable();
            $table->date('data')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_admin');
    }
}
