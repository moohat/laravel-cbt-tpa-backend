<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UjianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //user_id
            'user_id' => $this->faker->numberBetween(1, 11),
            //nilai angka, verbal, dan logika
            'nilai_angka' => $this->faker->numberBetween(1, 100),
            'nilai_verbal' => $this->faker->numberBetween(1, 100),
            'nilai_logika' => $this->faker->numberBetween(1, 100),
            //hasil
            'hasil' => $this->faker->randomElement(['lulus', 'Tidak Lulus']),
        ];
    }
}
