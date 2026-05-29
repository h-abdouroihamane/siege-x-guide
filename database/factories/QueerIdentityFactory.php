<?php

namespace Database\Factories;

use App\Models\QueerIdentity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QueerIdentity>
 */
class QueerIdentityFactory extends Factory
{
    protected $model = QueerIdentity::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
