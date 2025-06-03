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
                'id_peran' => 1,
                'username' => 'admin',
                'nama' => 'Natalia Willy',
                'password' => Hash::make('12345'),
                'email' => 'Natalia@example.com'
            ],
            [
                'id_peran' => 2,
                'username' => 'mahasiswa',
                'nama' => 'Rizky Maulana',
                'password' => Hash::make('12345'),
                'email' => 'Rizky@example.com'
            ],
            [
                'id_peran' => 3,
                'username' => 'dosen',
                'nama' => 'Dr. Andi Saputra',
                'password' => Hash::make('12345'),
                'email' => 'Andi@example.com'
            ],
            [
                'id_peran' => 4,
                'username' => 'tendik',
                'nama' => 'Budi Santoso',
                'password' => Hash::make('12345'),
                'email' => 'Budi@example.com'
            ],
            [
                'id_peran' => 5,
                'username' => 'sarpras',
                'nama' => 'Siti Aminah',
                'password' => Hash::make('12345'),
                'email' => 'Siti@example.com'
            ],
            [
                'id_peran' => 6,
                'username' => 'teknisi',
                'nama' => 'Joko Susilo',
                'password' => Hash::make('12345'),
                'email' => 'Joko@example.com'
            ],
        ];

        foreach ($pengguna as $data) {
            Pengguna::create($data);
        }
    }
}
