<?php

namespace Database\Factories;

use App\Models\Operation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operator>
 */
class OperatorFactory extends Factory
{
    public function definition(): array
    {
        $operation = Operation::factory()->create();

        return [
            'name' => fake()->unique()->name(),
            'description' => fake()->sentence(),
            'side' => fake()->randomElement(['Attack', 'Defense']),
            'year' => $operation->year,
            'season' => $operation->season,
            'operation_id' => $operation->id,
        ];
    }
}
