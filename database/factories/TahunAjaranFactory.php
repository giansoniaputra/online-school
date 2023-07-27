<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TahunAjaran>
 */
class TahunAjaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'unique' => 'c895ef78-0435-4405-b38a-a86d0b993659',
            'tahun_awal' => fake()->name(),
            'tahun_akhir' => fake()->name(),
            'periode' => fake()->name(),
            'status' => fake()->name(),
        ];
    }
}
