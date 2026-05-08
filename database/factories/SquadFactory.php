<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Squad>
 */
class SquadFactory extends Factory
{
    public function definition(): array
    {
        static $rank = 0;

        return [
            'name' => fake()->unique()->word(),
            'rank' => ++$rank,
        ];
    }
}
