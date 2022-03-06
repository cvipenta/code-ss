<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsMedicamenteNomenclatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_medicamente_nomenclator', function (Blueprint $table) {
            $table->integer('mn_id')->default(0)->primary();
            $table->string('mn_denumire_comerciala', 100)->default('');
            $table->string('mn_dci', 60)->default('')->index('mn_dci');
            $table->string('mn_concentratie')->default('');
            $table->string('mn_cod_atc', 10)->default('')->index('mn_cod_atc');
            $table->string('mn_actiune_terapeutica', 150)->default('')->index('mn_actiune_terapeutica');
            $table->string('mn_prescriptie')->default('');
            $table->string('mn_ambalaj')->default('');
            $table->string('mn_volum_ambalaj', 20)->default('');
            $table->string('mn_valabilitate_ambalaj', 100)->default('');
            $table->string('mn_cod_cim', 20)->default('');
            $table->string('mn_firma_tara_producatoare', 100)->default('')->index('mn_firma_tara_producatoare');
            $table->string('mn_firma_tara_detinatoare', 100)->default('');
            $table->string('mn_nr_data_ambalaj', 20)->default('');
            
            $table->index(['mn_denumire_comerciala`(20'], 'mn_denumire_comerciala');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_medicamente_nomenclator');
    }
}
