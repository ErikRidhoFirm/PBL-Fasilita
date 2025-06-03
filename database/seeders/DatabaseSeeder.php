<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use App\Models\KategoriFasilitas;
use App\Models\KategoriKerusakan;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            PeranSeeder::class,
            GedungSeeder::class,
            KategoriFasilitasSeeder::class,
            KategoriKerusakanSeeder::class,
            KriteriaSeeder::class,
            StatusSeeder::class,
            PenggunaSeeder::class,
            LantaiSeeder::class,
            RuanganSeeder::class,
            FasilitasSeeder::class,
            SkoringKriteriaSeeder::class,
            LaporanSeeder::class,
            LaporanFasilitasSeeder::class,
        ]);
    }
}
