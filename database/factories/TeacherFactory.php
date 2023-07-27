<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
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
            'npk' => fake()->name(),
            'nama_guru' => fake()->name(),
            'alamat' => fake()->name(),
            'telepon' => fake()->name(),
            'role' => 'GURU', // password
        ];
    }
}
