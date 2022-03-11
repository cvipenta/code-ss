<?php

namespace Database\Seeders;

use App\Models\MedicalTestCategory;
use Database\Factories\MedicalTestCategoryFactory;
use Illuminate\Database\Seeder;

class MedicalTestCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MedicalTestCategory::factory(
            MedicalTestCategoryFactory::getCategoriesCount()
        )->create();
    }
}
