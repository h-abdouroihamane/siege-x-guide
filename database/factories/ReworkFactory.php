<?php

namespace Database\Factories;

use App\Models\Operation;
use App\Models\Operator;
use App\Models\Rework;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rework>
 */
class ReworkFactory extends Factory
{
    protected $model = Rework::class;

    public function definition(): array
    {
        return [
            'operator_id' => Operator::factory(),
            'operation_id' => Operation::factory(),
        ];
    }
}
