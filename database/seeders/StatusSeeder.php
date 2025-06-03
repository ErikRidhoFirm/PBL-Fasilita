<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = [
            ['nama_status' => 'Menunggu'],
            ['nama_status' => 'Tidak Valid'],
            ['nama_status' => 'Ditolak'],
            ['nama_status' => 'Valid'],
            ['nama_status' => 'Ditugaskan'],
            ['nama_status' => 'Selesai'],
            ['nama_status' => 'Ditutup'],
        ];

        foreach ($status as $status) {
            Status::create($status);
        }
    }
}
