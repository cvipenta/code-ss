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
        $category = MedicalTestCategory::findOrFail(rand(1,10));

        return array(
            'title' => $title . ' [' . $category->name . ']',
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraphs(5, $asText = true),
            'category_id' => $category,
            'hits' => $this->faker->randomDigitNotZero()
        );
    }
}
