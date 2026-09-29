<?php

namespace Database\Factories;

use App\Enums\CabinStatus;
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
        return [
            'code' => 'WY-'.str_pad((string) fake()->unique()->numberBetween(1, 99), 2, '0', STR_PAD_LEFT),
            'name' => fake()->unique()->words(2, true),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->optional()->sentence(),
            'capacity' => 7,
            'base_occupancy' => 4,
            'status' => CabinStatus::Active,
            'check_in_time' => null,
            'check_out_time' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CabinStatus::Inactive,
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CabinStatus::Maintenance,
        ]);
    }

    public function withTranslations(): static
    {
        return $this->afterCreating(function (Cabin $cabin): void {
            $cabin->translations()->createMany([
                ['locale' => 'id', 'name' => $cabin->name.' ID', 'description' => 'Deskripsi '.$cabin->name],
                ['locale' => 'en', 'name' => $cabin->name.' EN', 'description' => 'Description '.$cabin->name],
            ]);
        });
    }
}
