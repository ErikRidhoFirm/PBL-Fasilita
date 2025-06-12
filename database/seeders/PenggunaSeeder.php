<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengguna')->insert([
            // 1 Admin
['id_peran' => 2, 'no_induk' => '198005152010091002', 'username' => 'admin', 'nama' => 'Sarah Abellia', 'password' => Hash::make('12345'), 'created_at' => now()],

// 10 Mahasiswa
    ['id_peran' => 3, 'no_induk' => '2021770123', 'username' => 'andiprasetyo', 'nama' => 'Andi Prasetyo', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 3, 'no_induk' => '2313130456', 'username' => 'rinakartika', 'nama' => 'Rina Kartika', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 3, 'no_induk' => '2431310789', 'username' => 'budihartono', 'nama' => 'Budi Hartono', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 3, 'no_induk' => '2241760345', 'username' => 'sitiaisyah', 'nama' => 'Siti Aisyah', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 3, 'no_induk' => '2351250789', 'username' => 'agusfirmansyah', 'nama' => 'Agus Firmansyah', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 3, 'no_induk' => '2342333123', 'username' => 'linamarlina', 'nama' => 'Lina Marlina', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 3, 'no_induk' => '2551120123', 'username' => 'fajarnugroho', 'nama' => 'Fajar Nugroho', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 3, 'no_induk' => '2441580567', 'username' => 'wulanayu', 'nama' => 'Wulan Ayu', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 3, 'no_induk' => '2341620999', 'username' => 'jokosusanto', 'nama' => 'Joko Susanto', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 3, 'no_induk' => '2521770001', 'username' => 'fitrianilestari', 'nama' => 'Fitriani Lestari', 'password' => Hash::make('12345'), 'created_at' => now()],

    // 5 Dosen
    ['id_peran' => 4, 'no_induk' => '198005152010091001', 'username' => 'ahmadsyahputra', 'nama' => 'Dr. Ahmad Syahputra, M.T.', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 4, 'no_induk' => '197212012000121123', 'username' => 'mariayuliani', 'nama' => 'Prof. Dr. Maria Yuliani, M.Sc.', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 4, 'no_induk' => '199003152010051234', 'username' => 'bambangsantoso', 'nama' => 'Dr. Bambang Santoso, M.Kom.', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 4, 'no_induk' => '196511202000011345', 'username' => 'sulastri', 'nama' => 'Dr. Hj. Sulastri, M.Pd.', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 4, 'no_induk' => '200001012018071456', 'username' => 'deniramadhan', 'nama' => 'Dr. Deni Ramadhan, M.Si.', 'password' => Hash::make('12345'), 'created_at' => now()],

    // 5 Tendik
    ['id_peran' => 5, 'no_induk' => '198809302014081567', 'username' => 'rizkyyuliana', 'nama' => 'Rizky Yuliana', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 5, 'no_induk' => '197610051995101678', 'username' => 'suhardi', 'nama' => 'Suhardi', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 5, 'no_induk' => '195503252005052789', 'username' => 'murniati', 'nama' => 'Murniati', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 5, 'no_induk' => '194904152000111890', 'username' => 'sudarman', 'nama' => 'Sudarman', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 5, 'no_induk' => '200506302024061901', 'username' => 'intankurniawati', 'nama' => 'Intan Kurniawati', 'password' => Hash::make('12345'), 'created_at' => now()],

    // 5 Sarpras
    ['id_peran' => 6, 'no_induk' => '198212312010051012', 'username' => 'arifprasetya', 'nama' => 'Arif Prasetya', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 6, 'no_induk' => '199511012014021125', 'username' => 'rahmawati', 'nama' => 'Rahmawati', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 6, 'no_induk' => '194808152010031230', 'username' => 'ismailmarzuki', 'nama' => 'Ismail Marzuki', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 6, 'no_induk' => '196703042015072345', 'username' => 'sitikomariah', 'nama' => 'Siti Komariah', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 6, 'no_induk' => '198501262005082876', 'username' => 'dodihermawan', 'nama' => 'Dodi Hermawan', 'password' => Hash::make('12345'), 'created_at' => now()],

    // 5 Teknisi
    ['id_peran' => 7, 'no_induk' => '197905202019112345', 'username' => 'ekosusilo', 'nama' => 'Eko Susilo', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 7, 'no_induk' => '196001011980011567', 'username' => 'samsulhuda', 'nama' => 'Samsul Huda', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 7, 'no_induk' => '199702282020022234', 'username' => 'yuliarahma', 'nama' => 'Yulia Rahma', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 7, 'no_induk' => '195912312010121890', 'username' => 'nuraini', 'nama' => 'Nur Aini', 'password' => Hash::make('12345'), 'created_at' => now()],
    ['id_peran' => 7, 'no_induk' => '198311052003101234', 'username' => 'baguspranoto', 'nama' => 'Bagus Pranoto', 'password' => Hash::make('12345'), 'created_at' => now()],

        ]);
    }
}
