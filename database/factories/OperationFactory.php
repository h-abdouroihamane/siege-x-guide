<?php

namespace Database\Factories;

use App\Models\Operation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Operation>
 */
class OperationFactory extends Factory
{
    protected $model = Operation::class;

    public function definition(): array
    {
        // The real "Y{year}S{season}" id is only meaningful to the seeders;
        // tests just need a unique string primary key, so generate one that
        // never collides when several operations share a year/season.
        return [
            'id' => 'OP-' . fake()->unique()->numerify('#####'),
            'name' => fake()->unique()->words(2, true),
            'year' => fake()->numberBetween(1, 10),
            'season' => fake()->numberBetween(1, 4),
            'release_date' => fake()->date(),
        ];
    }
}
