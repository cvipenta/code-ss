<?php

namespace Database\Seeders;

use App\Models\MedicalTest;
use Illuminate\Database\Seeder;

class MedicalTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MedicalTest::factory(250)->create();
    }
}
