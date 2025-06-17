<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templateFasilitas = [
            [
                'id_kategori' => 2,
                'nama_fasilitas' => 'Kursi',
            ],
            [
                'id_kategori' => 2,
                'nama_fasilitas' => 'Meja',
            ],
            [
                'id_kategori' => 2,
                'nama_fasilitas' => 'Papan Tulis',
            ],
            [
                'id_kategori' => 4,
                'nama_fasilitas' => 'Proyektor',
            ],
            [
                'id_kategori' => 4,
                'nama_fasilitas' => 'Layar Proyektor',
            ],
        ];

        $ruanganByLantai = [
            5 => range(1, 8),
            6 => range(12, 24),
            7 => range(30, 45),
            8 => range(51, 53),
        ];

        foreach ($ruanganByLantai as $idLantai => $idRuangans) {
            foreach ($idRuangans as $idRuangan) {
                foreach ($templateFasilitas as $fasilitas) {
                    Fasilitas::create([
                        'id_ruangan' => $idRuangan,
                        'id_kategori' => $fasilitas['id_kategori'],
                        'nama_fasilitas' => $fasilitas['nama_fasilitas'],
                    ]);
                }
            }
        }
    }
}
