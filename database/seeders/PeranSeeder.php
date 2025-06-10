<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peran;
use Illuminate\Support\Facades\DB;

class PeranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('peran')->insert([
            ['kode_peran' => 'GST', 'nama_peran' => 'Guest'],
            ['kode_peran' => 'ADM', 'nama_peran' => 'Admin'],
            ['kode_peran' => 'MHS', 'nama_peran' => 'Mahasiswa'],
            ['kode_peran' => 'DSN', 'nama_peran' => 'Dosen'],
            ['kode_peran' => 'TDK', 'nama_peran' => 'Tendik'],
            ['kode_peran' => 'SPR', 'nama_peran' => 'Sarpras'],
            ['kode_peran' => 'TNS', 'nama_peran' => 'Teknisi'],
        ]);
    }
}
