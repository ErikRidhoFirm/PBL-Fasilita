<?php

namespace Database\Seeders;

use App\Models\SkoringKriteria;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SkoringKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            // C1: Tingkat Kerusakan
['id_kriteria' => 1, 'parameter' => 'Kerusakan ringan',   'nilai_referensi' => 1],
['id_kriteria' => 1, 'parameter' => 'Kerusakan cukup ringan', 'nilai_referensi' => 2],
['id_kriteria' => 1, 'parameter' => 'Kerusakan sedang',    'nilai_referensi' => 3],
['id_kriteria' => 1, 'parameter' => 'Kerusakan parah',     'nilai_referensi' => 4],
['id_kriteria' => 1, 'parameter' => 'Kerusakan kritis',    'nilai_referensi' => 5],

            // C2: Frekuensi Digunakan
            ['id_kriteria' => 2, 'parameter' => '< 1x/minggu',      'nilai_referensi' => 1],
            ['id_kriteria' => 2, 'parameter' => '1–2x/minggu',      'nilai_referensi' => 2],
            ['id_kriteria' => 2, 'parameter' => '3–5x/minggu',      'nilai_referensi' => 3],
            ['id_kriteria' => 2, 'parameter' => '6–10x/minggu',     'nilai_referensi' => 4],
            ['id_kriteria' => 2, 'parameter' => '> 10x/minggu',     'nilai_referensi' => 5],

            // C3: Jumlah Unit Rusak
            ['id_kriteria' => 3, 'parameter' => '1 unit rusak',    'nilai_referensi' => 1],
            ['id_kriteria' => 3, 'parameter' => '2–3 unit rusak',  'nilai_referensi' => 2],
            ['id_kriteria' => 3, 'parameter' => '4–5 unit rusak',  'nilai_referensi' => 3],
            ['id_kriteria' => 3, 'parameter' => '6–7 unit rusak',  'nilai_referensi' => 4],
            ['id_kriteria' => 3, 'parameter' => '≥ 8 unit rusak',   'nilai_referensi' => 5],

            // C4: Dampak Akademik
            ['id_kriteria' => 4, 'parameter' => 'Tidak berdampak signifikan',                     'nilai_referensi' => 1],
            ['id_kriteria' => 4, 'parameter' => 'Gangguan minor pada beberapa sesi',               'nilai_referensi' => 2],
            ['id_kriteria' => 4, 'parameter' => 'Mengganggu satu mata kuliah penuh',              'nilai_referensi' => 3],
            ['id_kriteria' => 4, 'parameter' => 'Mengganggu banyak sesi atau lab penting',        'nilai_referensi' => 4],
            ['id_kriteria' => 4, 'parameter' => 'Menghentikan seluruh kegiatan belajar/mengajar', 'nilai_referensi' => 5],

            // C5: Estimasi Biaya
            ['id_kriteria' => 5, 'parameter' => '< Rp 500.000',               'nilai_referensi' => 1],
            ['id_kriteria' => 5, 'parameter' => 'Rp 500.000–1.000.000',       'nilai_referensi' => 2],
            ['id_kriteria' => 5, 'parameter' => 'Rp 1.000.000–2.000.000',     'nilai_referensi' => 3],
            ['id_kriteria' => 5, 'parameter' => 'Rp 2.000.000–5.000.000',     'nilai_referensi' => 4],
            ['id_kriteria' => 5, 'parameter' => '> Rp 5.000.000',            'nilai_referensi' => 5],

            // C6: Potensi Bahaya
            ['id_kriteria' => 6, 'parameter' => 'Risiko minimal (potensi kecil)',         'nilai_referensi' => 1],
            ['id_kriteria' => 6, 'parameter' => 'Risiko rendah (cedera ringan mungkin)',   'nilai_referensi' => 2],
            ['id_kriteria' => 6, 'parameter' => 'Risiko sedang (cedera sedang mungkin)',  'nilai_referensi' => 3],
            ['id_kriteria' => 6, 'parameter' => 'Risiko tinggi (cedera serius mungkin)',  'nilai_referensi' => 4],
            ['id_kriteria' => 6, 'parameter' => 'Risiko kritis (bahaya fatal mungkin)',   'nilai_referensi' => 5],
        ];

        foreach ($datas as $data) {
            SkoringKriteria::create($data);
        }
    }
}
