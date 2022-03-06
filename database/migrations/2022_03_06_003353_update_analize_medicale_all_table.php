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
        Schema::table('analize_medicale_all', static function (Blueprint $table) {
            $table->string('am_slug')->default('')->after('am_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('analize_medicale_all', static function (Blueprint $table) {
            $table->dropColumn('am_slug');
        });
    }
};
