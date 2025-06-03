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
        $fasilitas = [
            [
                'id_ruangan' => 1,
                'id_kategori' => 2,
                'nama_fasilitas' => 'Kursi',
                'jumlah_fasilitas' => 51,
            ],
            [
                'id_ruangan' => 1,
                'id_kategori' => 2,
                'nama_fasilitas' => 'Meja',
                'jumlah_fasilitas' => 1,
            ],
            [
                'id_ruangan' => 1,
                'id_kategori' => 2,
                'nama_fasilitas' => 'Papan Tulis',
                'jumlah_fasilitas' => 1,
            ],
            [
                'id_ruangan' => 1,
                'id_kategori' => 4,
                'nama_fasilitas' => 'Proyektor',
                'jumlah_fasilitas' => 1,
            ],
            [
                'id_ruangan' => 1,
                'id_kategori' => 4,
                'nama_fasilitas' => 'Layar Proyektor',
                'jumlah_fasilitas' => 1,
            ],
        ];

        foreach ($fasilitas as $data) {
            Fasilitas::create($data);
        }
    }
}
