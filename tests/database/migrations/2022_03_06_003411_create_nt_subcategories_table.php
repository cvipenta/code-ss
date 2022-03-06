<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNtSubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nt_subcategories', function (Blueprint $table) {
            $table->smallInteger('subcat_id')->primary();
            $table->smallInteger('category_id')->default(0)->index('category_id');
            $table->string('subcat_shortname', 100);
            $table->boolean('subcat_priority')->default(0);
            $table->string('subcat_name_ro');
            $table->string('subcat_name_en');
            $table->boolean('subcat_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nt_subcategories');
    }
}
