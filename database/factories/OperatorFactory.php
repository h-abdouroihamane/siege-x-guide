<?php

namespace Database\Factories;

use App\Models\Operation;
use App\Models\Operator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Operator>
 */
class OperatorFactory extends Factory
{
    protected $model = Operator::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->firstName(),
            'description' => fake()->sentence(),
            'side' => fake()->randomElement(['Attack', 'Defense']),
            'year' => fake()->numberBetween(1, 10),
            'season' => fake()->numberBetween(1, 4),
            'operation_id' => Operation::factory(),
        ];
    }
}
