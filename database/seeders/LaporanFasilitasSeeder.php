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
                'id_kategori_kerusakan' => 10,
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap1_foto1.jpg',
                'deskripsi'             => 'Kursi dekat jendela retak di sandaran.',
                'is_active'   => true,
                'created_at'            => now()->subDays(4),
                'updated_at'            => now()->subDays(4),
            ],
            // laporan ke-2: meja cat mengelupas
            [
                'id_laporan'            => 1,
                'id_fasilitas'          => 2,
                'id_kategori_kerusakan' => 10,
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap2_foto1.jpg',
                'deskripsi'             => 'Cat meja mengelupas di sudut kanan.',
                'is_active'   => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // laporan ke-3: proyektor mati total
            [
                'id_laporan'            => 2,
                'id_fasilitas'          => 4,
                'id_kategori_kerusakan' => 3,
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap3_foto1.jpg',
                'deskripsi'             => 'Proyektor tidak menyala sama sekali.',
                'is_active'   => true,
                'created_at'            => now(),
                'updated_at'            => now(),
            ],
            // Laporan ID 3 - First facility report
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 1, // Kursi
                'id_kategori_kerusakan' => 10, // Ringan
                'id_status'             => 1,
                'jumlah_rusak'          => 2,
                'path_foto'             => 'uploads/laporan/lap3_foto1.jpg',
                'deskripsi'             => 'Dua kursi di baris depan kaki tidak stabil.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 3 - Second facility report
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 3, // Papan Tulis
                'id_kategori_kerusakan' => 10, // Ringan
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap3_foto2.jpg',
                'deskripsi'             => 'Papan tulis sulit dibersihkan, bekas spidol menempel.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],

            // Laporan ID 4 - First facility report
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 4, // Proyektor
                'id_kategori_kerusakan' => 3, // Berat
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto1.jpg',
                'deskripsi'             => 'Proyektor gambar buram dan berkedip-kedip.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 4 - Second facility report
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 5, // LCD Proyektor
                'id_kategori_kerusakan' => 2, // Sedang
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto2.jpg',
                'deskripsi'             => 'LCD proyektor warna tidak akurat.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 3 - First facility report
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 1, // Kursi
                'id_kategori_kerusakan' => 10, // Ringan
                'id_status'             => 1,
                'jumlah_rusak'          => 2,
                'path_foto'             => 'uploads/laporan/lap3_foto1.jpg',
                'deskripsi'             => 'Dua kursi di baris depan kaki tidak stabil.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 3 - Second facility report
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 3, // Papan Tulis
                'id_kategori_kerusakan' => 10, // Ringan
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap3_foto2.jpg',
                'deskripsi'             => 'Papan tulis sulit dibersihkan, bekas spidol menempel.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],

            // Laporan ID 4 - First facility report
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 4, // Proyektor
                'id_kategori_kerusakan' => 3, // Berat
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto1.jpg',
                'deskripsi'             => 'Proyektor gambar buram dan berkedip-kedip.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 4 - Second facility report
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 5, // LCD Proyektor
                'id_kategori_kerusakan' => 2, // Sedang
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto2.jpg',
                'deskripsi'             => 'LCD proyektor warna tidak akurat.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 3 - First facility report
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 1, // Kursi
                'id_kategori_kerusakan' => 10, // Ringan
                'id_status'             => 1,
                'jumlah_rusak'          => 2,
                'path_foto'             => 'uploads/laporan/lap3_foto1.jpg',
                'deskripsi'             => 'Dua kursi di baris depan kaki tidak stabil.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 3 - Second facility report
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 3, // Papan Tulis
                'id_kategori_kerusakan' => 10, // Ringan
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap3_foto2.jpg',
                'deskripsi'             => 'Papan tulis sulit dibersihkan, bekas spidol menempel.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],

            // Laporan ID 4 - First facility report
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 4, // Proyektor
                'id_kategori_kerusakan' => 3, // Berat
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto1.jpg',
                'deskripsi'             => 'Proyektor gambar buram dan berkedip-kedip.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 4 - Second facility report
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 5, // LCD Proyektor
                'id_kategori_kerusakan' => 2, // Sedang
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto2.jpg',
                'deskripsi'             => 'LCD proyektor warna tidak akurat.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 3 - First facility report
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 1, // Kursi
                'id_kategori_kerusakan' => 10, // Ringan
                'id_status'             => 1,
                'jumlah_rusak'          => 2,
                'path_foto'             => 'uploads/laporan/lap3_foto1.jpg',
                'deskripsi'             => 'Dua kursi di baris depan kaki tidak stabil.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 3 - Second facility report
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 3, // Papan Tulis
                'id_kategori_kerusakan' => 10, // Ringan
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap3_foto2.jpg',
                'deskripsi'             => 'Papan tulis sulit dibersihkan, bekas spidol menempel.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],

            // Laporan ID 4 - First facility report
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 4, // Proyektor
                'id_kategori_kerusakan' => 3, // Berat
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto1.jpg',
                'deskripsi'             => 'Proyektor gambar buram dan berkedip-kedip.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 4 - Second facility report
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 5, // LCD Proyektor
                'id_kategori_kerusakan' => 2, // Sedang
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto2.jpg',
                'deskripsi'             => 'LCD proyektor warna tidak akurat.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 3 - First facility report
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 1, // Kursi
                'id_kategori_kerusakan' => 10, // Ringan
                'id_status'             => 1,
                'jumlah_rusak'          => 2,
                'path_foto'             => 'uploads/laporan/lap3_foto1.jpg',
                'deskripsi'             => 'Dua kursi di baris depan kaki tidak stabil.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 3 - Second facility report
            [
                'id_laporan'            => 3,
                'id_fasilitas'          => 3, // Papan Tulis
                'id_kategori_kerusakan' => 10, // Ringan
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap3_foto2.jpg',
                'deskripsi'             => 'Papan tulis sulit dibersihkan, bekas spidol menempel.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],

            // Laporan ID 4 - First facility report
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 4, // Proyektor
                'id_kategori_kerusakan' => 3, // Berat
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto1.jpg',
                'deskripsi'             => 'Proyektor gambar buram dan berkedip-kedip.',
                'is_active'             => true,
                'created_at'            => now()->subDays(2),
                'updated_at'            => now()->subDays(2),
            ],
            // Laporan ID 4 - Second facility report
            [
                'id_laporan'            => 4,
                'id_fasilitas'          => 5, // LCD Proyektor
                'id_kategori_kerusakan' => 2, // Sedang
                'id_status'             => 1,
                'jumlah_rusak'          => 1,
                'path_foto'             => 'uploads/laporan/lap4_foto2.jpg',
                'deskripsi'             => 'LCD proyektor warna tidak akurat.',
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
