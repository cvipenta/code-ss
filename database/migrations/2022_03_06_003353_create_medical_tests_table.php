<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('medical_tests',
            static function (Blueprint $table) {
                $table->integer('id')->primary();
                $table->string('title')->default('');
                $table->string('slug')->default('');
                $table->text('description');
                $table->string('category')->nullable();
                $table->integer('hits')->default(0);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_tests');
    }
};
