<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangan = [
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'RT-1',
                'nama_ruangan' => 'Ruang Teori 1'
            ],
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'RT-2',
                'nama_ruangan' => 'Ruang Teori 2'
            ],
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'RT-3',
                'nama_ruangan' => 'Ruang Teori 3'
            ],
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'RT-4',
                'nama_ruangan' => 'Ruang Teori 4'
            ],
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'RT-5',
                'nama_ruangan' => 'Ruang Teori 5'
            ],
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'RT-6',
                'nama_ruangan' => 'Ruang Teori 6'
            ],
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'RT-7',
                'nama_ruangan' => 'Ruang Teori 7'
            ],
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'LPY-1',
                'nama_ruangan' => 'Lab Proyek 1'
            ],
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'RTEK-1',
                'nama_ruangan' => 'Ruang Teknisi 1'
            ],
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'TL-5',
                'nama_ruangan' => 'Toilet Laki-Laki 5'
            ],
            [
                'id_lantai' => 5,
                'kode_ruangan' => 'TP-5',
                'nama_ruangan' => 'Toilet Perempuan 5'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RD-1',
                'nama_ruangan' => 'Ruang Dosen 1'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RD-2',
                'nama_ruangan' => 'Ruang Dosen 2'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RD-3',
                'nama_ruangan' => 'Ruang Dosen 3'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RD-4',
                'nama_ruangan' => 'Ruang Dosen 4'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RD-5',
                'nama_ruangan' => 'Ruang Dosen 5'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RD-6',
                'nama_ruangan' => 'Ruang Dosen 6'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RJS',
                'nama_ruangan' => 'Ruang Jurusan'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RBC',
                'nama_ruangan' => 'Ruang Baca'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'LSI-1',
                'nama_ruangan' => 'Ruang Lab Sistem Informasi 1'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'LSI-2',
                'nama_ruangan' => 'Ruang Lab Sistem Informasi 2'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'LSI-3',
                'nama_ruangan' => 'Ruang Lab Sistem Informasi 3'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'LPY-2',
                'nama_ruangan' => 'Ruang Lab Proyek 2'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'LPY-3',
                'nama_ruangan' => 'Ruang Lab Proyek 3'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RAS',
                'nama_ruangan' => 'Ruang Arsip'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RW3',
                'nama_ruangan' => 'Ruang W3'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'RPT-1',
                'nama_ruangan' => 'Ruang Rapat 1'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'TL-6',
                'nama_ruangan' => 'Toilet Laki-Laki 6'
            ],
            [
                'id_lantai' => 6,
                'kode_ruangan' => 'TP-6',
                'nama_ruangan' => 'Toilet Perempuan 6'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LPR-1',
                'nama_ruangan' => 'Lab Praktikum 1'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LPR-2',
                'nama_ruangan' => 'Lab Praktikum 2'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LPR-3',
                'nama_ruangan' => 'Lab Praktikum 3'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LPR-4',
                'nama_ruangan' => 'Lab Praktikum 4'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LPR-5',
                'nama_ruangan' => 'Lab Praktikum 5'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LPR-6',
                'nama_ruangan' => 'Lab Praktikum 6'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LPR-7',
                'nama_ruangan' => 'Lab Praktikum 7'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LPR-8',
                'nama_ruangan' => 'Lab Praktikum 8'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LKJ-1',
                'nama_ruangan' => 'Lab Komputer Jaringan 1'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LKJ-2',
                'nama_ruangan' => 'Lab Komputer Jaringan 2'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LKJ-3',
                'nama_ruangan' => 'Lab Komputer Jaringan 3'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LIG-1',
                'nama_ruangan' => 'Lab Internet 1'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LIG-2',
                'nama_ruangan' => 'Lab Internet 2'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LPY-4',
                'nama_ruangan' => 'Lab Proyek 4'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LERP',
                'nama_ruangan' => 'Lab ERP'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'LAI1',
                'nama_ruangan' => 'Lab AI 1'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'TL-7',
                'nama_ruangan' => 'Toilet Laki-Laki 7'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'TP-7',
                'nama_ruangan' => 'Toilet Perempuan 7'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'RTEK-2',
                'nama_ruangan' => 'Ruang Teknisi 2'
            ],
            [
                'id_lantai' => 7,
                'kode_ruangan' => 'RSVR',
                'nama_ruangan' => 'Ruang Server'
            ],
            [
                'id_lantai' => 8,
                'kode_ruangan' => 'RADT',
                'nama_ruangan' => 'Ruang Auditorium'
            ],
            [
                'id_lantai' => 8,
                'kode_ruangan' => 'RT-12',
                'nama_ruangan' => 'Ruang Teori 12'
            ],
            [
                'id_lantai' => 8,
                'kode_ruangan' => 'RT-13',
                'nama_ruangan' => 'Ruang Teori 13'
            ],
            [
                'id_lantai' => 8,
                'kode_ruangan' => 'RT-14',
                'nama_ruangan' => 'Ruang Teori 14'
            ],

            [
                'id_lantai' => 8,
                'kode_ruangan' => 'RPT-2',
                'nama_ruangan' => 'Ruang Rapat 2'
            ],
            [
                'id_lantai' => 8,
                'kode_ruangan' => 'RKTN',
                'nama_ruangan' => 'Kantin'
            ],
            [
                'id_lantai' => 8,
                'kode_ruangan' => 'TL-8',
                'nama_ruangan' => 'Toilet Laki-Laki 8'
            ],
            [
                'id_lantai' => 8,
                'kode_ruangan' => 'TP-8',
                'nama_ruangan' => 'Toilet Perempuan 8'
            ],
        ];

        foreach ($ruangan as $data) {
            Ruangan::create($data);
        }
    }
}
