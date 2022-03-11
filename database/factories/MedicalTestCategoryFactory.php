<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory
 */
class MedicalTestCategoryFactory extends Factory
{
    /**
     * @var array|string[]
     */
    protected static array $categories = ['BIOCHIMIE', 'HEMATOLOGIE', 'COAGULARE', 'IMUNOLOGIE', 'CITOLOGIE', 'MICROBIOLOGIE', 'BIOLOGIE MOLECULARA', 'ELECTROFOREZA', 'URINA'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = array_shift(static::$categories);

        return [
            'name' => $category,
            'slug' => Str::slug($category)
        ];
    }

    /**
     * @return int|null
     */
    public static function getCategoriesCount(): ?int
    {
        return count(static::$categories);
    }
}
