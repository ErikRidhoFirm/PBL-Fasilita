<?php

namespace Database\Seeders;

use App\Models\Laporan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laporans = [
            [
                'id_pengguna' => 2,
                'id_gedung'   => 1,
                'id_lantai'   => 5,
                'id_ruangan'  => 1,
                'created_at'  => now()->subDays(4),
                'updated_at'  => now()->subDays(4),
            ],
            [
                'id_pengguna' => 2,
                'id_gedung'   => 1,
                'id_lantai'   => 5,
                'id_ruangan'  => 1,
                'created_at'  => now()->subDays(2),
                'updated_at'  => now()->subDays(2),
            ],

        ];

        foreach ($laporans as $data) {
            Laporan::create($data);
        }
    }
}
