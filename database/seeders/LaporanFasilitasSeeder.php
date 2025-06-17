<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaporanFasilitas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LaporanFasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laporanF = [
            // laporan ke-1: kursi pecah ringan
            [
                'id_laporan'            => 1,
                'id_fasilitas'          => 1,
                'id_status'             => 1,
                'path_foto'             => 'uploads/laporan/lap1_foto1.jpg',
                'deskripsi'             => 'Kursi dekat jendela retak di sandaran.',
                'id_tingkat_kerusakan'  => 3,
                'id_dampak_pengguna'    => 16,
                'is_active'   => true,
                'created_at'            => now()->subDays(4),
                'updated_at'            => now()->subDays(4),
            ],
            // laporan ke-2: meja cat mengelupas
            [
                'id_laporan'            => 1,
                'id_fasilitas'          => 2,
                'id_status'             => 1,
                'path_foto'             => 'uploads/laporan/lap2_foto1.jpg',
                'deskripsi'             => 'Cat meja mengelupas di sudut kanan.',
                'id_tingkat_kerusakan'  => 1,
                'id_dampak_pengguna'    => 16,
                'is_active'   => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // laporan ke-3: proyektor mati total
            [
                'id_laporan'            => 2,
                'id_fasilitas'          => 4,
                'id_status'             => 1,
                'path_foto'             => 'uploads/laporan/lap3_foto1.jpg',
                'deskripsi'             => 'Proyektor tidak menyala sama sekali.',
                'id_tingkat_kerusakan'  => 3,
                'id_dampak_pengguna'    => 18,
                'is_active'   => true,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 3, // Papan Tulis // Ringan
                'id_status'             => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto1.jpg',
                'deskripsi'             => 'Papan tulis sulit dibersihkan, bekas spidol menempel.',
                'id_tingkat_kerusakan'  => 1,
                'id_dampak_pengguna'    => 16,
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 5, // LCD Proyektor
                'id_status'             => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto2.jpg',
                'deskripsi'             => 'LCD proyektor warna tidak akurat.',
                'id_tingkat_kerusakan'  => 2,
                'id_dampak_pengguna'    => 16,
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],

        ];

        foreach ($laporanF as $data) {
            LaporanFasilitas::create($data);
        }
    }
}
