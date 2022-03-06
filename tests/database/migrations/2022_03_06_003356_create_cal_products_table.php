<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cal_products', function (Blueprint $table) {
            $table->integer('p_id')->primary();
            $table->string('p_titlu')->unique('p_titlu');
            $table->string('p_categorie');
            $table->integer('p_calorii')->nullable();
            $table->double('p_proteine', 4, 1)->nullable();
            $table->double('p_lipide', 4, 1)->nullable();
            $table->double('p_carbohidrati', 4, 1)->nullable();
            $table->double('p_fibre', 4, 1)->nullable();
            $table->double('p_aproximari', 4, 1)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cal_products');
    }
}
