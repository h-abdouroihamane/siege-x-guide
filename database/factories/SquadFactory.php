<?php

namespace Database\Factories;

use App\Models\Squad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Squad>
 */
class SquadFactory extends Factory
{
    protected $model = Squad::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'rank' => fake()->numberBetween(1, 20),
        ];
    }
}
