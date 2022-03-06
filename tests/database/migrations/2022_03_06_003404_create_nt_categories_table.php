<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nt_categories', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->string('cat_shortname');
            $table->boolean('cat_priority')->default(0);
            $table->string('cat_name_ro');
            $table->string('cat_name_en');
            $table->boolean('cat_active')->default(1);
            $table->text('priority');
            $table->text('frecventa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nt_categories');
    }
}
