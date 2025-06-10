<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengguna = [
            [
                'id_peran' => 2,
                'no_induk' => '1230084748',
                'username' => 'admin',
                'nama' => 'Natalia Willy',
                'password' => Hash::make('12345'),
            ],
            [
                'id_peran' => 3,
                'no_induk' => '1230084749',
                'username' => 'mahasiswa',
                'nama' => 'Rizky Maulana',
                'password' => Hash::make('12345'),
            ],
            [
                'id_peran' => 4,
                'no_induk' => '1230084750',
                'username' => 'dosen',
                'nama' => 'Dr. Andi Saputra',
                'password' => Hash::make('12345'),
            ],
            [
                'id_peran' => 5,
                'no_induk' => '1230084751',
                'username' => 'tendik',
                'nama' => 'Budi Santoso',
                'password' => Hash::make('12345'),
            ],
            [
                'id_peran' => 6,
                'no_induk' => '1230084752',
                'username' => 'sarpras',
                'nama' => 'Siti Aminah',
                'password' => Hash::make('12345'),
            ],
            [
                'id_peran' => 7,
                'no_induk' => '1230084753',
                'username' => 'teknisi',
                'nama' => 'Joko Susilo',
                'password' => Hash::make('12345'),
            ],
        ];

        foreach ($pengguna as $data) {
            Pengguna::create($data);
        }
    }
}
