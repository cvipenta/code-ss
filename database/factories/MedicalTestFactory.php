<?php

namespace Database\Factories;

use App\Models\MedicalTestCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class MedicalTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();

        return array(
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(),
            'category_id' => MedicalTestCategory::findOrFail(rand(1,10)),
            'hits' => $this->faker->randomDigitNotZero()
        );
    }
}
