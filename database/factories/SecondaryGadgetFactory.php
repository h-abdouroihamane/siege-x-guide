<?php

namespace Database\Factories;

use App\Models\SecondaryGadget;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SecondaryGadget>
 */
class SecondaryGadgetFactory extends Factory
{
    protected $model = SecondaryGadget::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'side' => fake()->randomElement(['Attack', 'Defense']),
        ];
    }
}
