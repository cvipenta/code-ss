<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_contact', function (Blueprint $table) {
            $table->integer('contact_id')->primary();
            $table->string('contact_ip', 15)->default('');
            $table->integer('contact_time')->default(0);
            $table->text('contact_details');
            $table->timestamp('contact_realtime')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_contact');
    }
}
