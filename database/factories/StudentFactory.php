<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;



class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create('ro_RO');

        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'birthdate' => $this->faker->dateTimeBetween('-20 years', '-19 years'),
            'year' => $this->faker->numberBetween(1, 3),
            'program_id' => $this->faker->numberBetween(1, 4),
        ];
    }
}
