<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsReferersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_referers', function (Blueprint $table) {
            $table->integer('ref_id')->primary();
            $table->string('ref_string')->default('')->index('ref_string');
            $table->string('ref_url')->default('');
            $table->string('ref_uri')->default('');
            $table->string('ref_ip', 15)->default('');
            $table->timestamp('ref_time')->useCurrent();
            
            $table->index(['ref_url', 'ref_uri', 'ref_ip'], 'ref_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_referers');
    }
}
