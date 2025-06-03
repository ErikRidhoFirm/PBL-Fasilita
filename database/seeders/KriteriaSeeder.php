<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriterias = [
            [
                'kode_kriteria'  => 'C1',
                'nama_kriteria'  => 'Tingkat Kerusakan',
                'bobot_kriteria' => 0.30,
                'tipe_kriteria'  => 'benefit',
                'deskripsi'      => 'Seberapa parah kerusakan mengganggu fungsi fasilitas (1 = minor, 5 = kritis)'
            ],
            [
                'kode_kriteria'  => 'C2',
                'nama_kriteria'  => 'Frekuensi Digunakan',
                'bobot_kriteria' => 0.15,
                'tipe_kriteria'  => 'benefit',
                'deskripsi'      => 'Seberapa sering fasilitas tersebut dipakai (1 = jarang, 5 = sangat sering)'
            ],
            [
                'kode_kriteria'  => 'C3',
                'nama_kriteria'  => 'Jumlah Unit Rusak',
                'bobot_kriteria' => 0.15,
                'tipe_kriteria'  => 'benefit',
                'deskripsi'      => 'Banyaknya unit rusak dalam satu laporan (diskor 1–5 berdasarkan rentang jumlah unit)'
            ],
            [
                'kode_kriteria'  => 'C4',
                'nama_kriteria'  => 'Dampak Akademik',
                'bobot_kriteria' => 0.25,
                'tipe_kriteria'  => 'benefit',
                'deskripsi'      => 'Besarnya gangguan pada proses belajar/mengajar (1 = kecil, 5 = kelas terhenti)'
            ],
            [
                'kode_kriteria'  => 'C5',
                'nama_kriteria'  => 'Estimasi Biaya',
                'bobot_kriteria' => 0.10,
                'tipe_kriteria'  => 'cost',
                'deskripsi'      => 'Perkiraan biaya perbaikan (1 = rendah, 5 = tinggi)'
            ],
            [
                'kode_kriteria'  => 'C6',
                'nama_kriteria'  => 'Potensi Bahaya',
                'bobot_kriteria' => 0.05,
                'tipe_kriteria'  => 'benefit',
                'deskripsi'      => 'Risiko keselamatan jika kerusakan dibiarkan (1 = rendah, 5 = tinggi)'
            ],
        ];

        foreach ($kriterias as $data) {
            Kriteria::create($data);
        }
    }
}
