<?php

namespace Database\Factories;

use App\Models\GuestLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GuestLog>
 */
class GuestLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = fake()->randomElement(['Mahasiswa', 'Dosen / Staf', 'Umum']);
        $identitas = match ($kategori) {
            'Mahasiswa' => fake()->numerify('2023######'),
            'Dosen / Staf' => fake()->numerify('19850101########'),
            default => fake()->address(),
        };

        return [
            'nama' => fake()->name(),
            'kategori' => $kategori,
            'identitas' => $identitas,
            'waktu_masuk' => now(),
        ];
    }
}
