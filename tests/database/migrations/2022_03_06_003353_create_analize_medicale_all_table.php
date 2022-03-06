<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalizeMedicaleAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('analize_medicale_all', static function (Blueprint $table) {
            $table->integer('am_id')->primary();
            $table->string('am_title')->default('');
            $table->string('am_slug')->default('');
            $table->text('am_description');
            $table->string('am_category')->nullable();
            $table->string('am_payment')->nullable();
            $table->string('am_results')->nullable();
            $table->string('am_price')->nullable();
            $table->integer('am_hits')->default(0);
            $table->timestamp('am_lastupdate')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('analize_medicale_all');
    }
}
