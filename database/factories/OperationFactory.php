<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operation>
 */
class OperationFactory extends Factory
{
    public function definition(): array
    {
        $year = fake()->numberBetween(1, 10);
        $season = fake()->numberBetween(1, 4);

        return [
            'id' =>
                'Y' . $year . 'S' . $season . '_' . fake()->unique()->word(),
            'name' => 'Operation ' . fake()->unique()->word(),
            'year' => $year,
            'season' => $season,
            'release_date' => fake()->date(),
        ];
    }
}
