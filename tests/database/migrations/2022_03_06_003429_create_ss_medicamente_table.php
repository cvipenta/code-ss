<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsMedicamenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_medicamente', function (Blueprint $table) {
            $table->integer('medicament_id')->primary();
            $table->string('medicament_termen', 100)->default('');
            $table->text('medicament_text');
            
            $table->index(['medicament_termen`(10'], 'medicament_termen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_medicamente');
    }
}
