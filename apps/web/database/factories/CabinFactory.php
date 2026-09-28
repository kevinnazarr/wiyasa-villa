<?php

namespace Database\Factories;

use App\Models\Cabin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cabin>
 */
class CabinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'code' => 'WY-'.fake()->unique()->numberBetween(1, 99),
            'name' => $name,
            'slug' => fake()->unique()->slug(2),
            'description' => fake()->sentence(),
            'capacity' => 7,
            'base_occupancy' => 4,
            'status' => 'ACTIVE',
            'check_in_time' => null,
            'check_out_time' => null,
        ];
    }
}
