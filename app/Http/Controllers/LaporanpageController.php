<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;          // Model Laporan
use App\Models\Fasilitas;        // (jika diperlukan untuk relasi)

class LaporanController extends Controller
{
    /**
     * Tampilkan daftar laporan fasilitas yang sudah selesai diperbaiki.
     */
    public function index()
    {
        // 1. Ambil semua laporan dengan status 'selesai'
        //    (atau bisa status = 'fixed' tergantung enum/status tabel di DB).
        // 2. Sertakan relasi dengan model Fasilitas (jika mau menampilkan nama fasilitas-nya).
        // 3. Urutkan berdasarkan tanggal terakhir di‐update (updated_at desc) supaya yang terbaru muncul di atas.
        
        $laporanSelesai = Laporan::with('fasilitas')
                            ->where('status', 'selesai')
                            ->orderBy('updated_at', 'desc')
                            ->get();

        // Kirimkan data ke view: resources/views/laporan/index.blade.php
        return view('laporan.index', compact('laporanSelesai'));
    }
}
