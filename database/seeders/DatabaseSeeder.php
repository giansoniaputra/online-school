<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'unique' => Str::orderedUuid(),
            'username' => '1234567',
            'nama' => 'Gian Sonia',
            'email' => 'giansonia@gmail.com',
            'password' => bcrypt(1234567),
            'role' => 'GURU',
        ]);
        $user = User::latest()->first();
        \App\Models\Teacher::factory()->create([
            'unique' => $user->unique,
            'npk' => '1234567',
            'nama_guru' => 'Gian Sonia',
            'alamat' => 'Citerewes Bungursari',
            'telepon' => '082321634181',
            'role' => 'GURU',
        ]);
        \App\Models\TahunAjaran::factory()->create([
            'unique' => 'c895ef78-0435-4405-b38a-a86d0b993659',
            'tahun_awal' => '2022',
            'tahun_akhir' => '2023',
            'periode' => 'Ganjil',
            'status' => 1,
        ]);
    }
}
