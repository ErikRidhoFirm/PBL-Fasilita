<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Laporan;
use App\Models\LaporanFasilitas;
use App\Models\Status;


class PelaporDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id_pengguna;

        // 1) Total laporan milik user
        $totalLaporan = Laporan::where('id_pengguna', $userId)->count();

        // 2) Laporan “In Process” milik user (VALID = 4 atau DITUGASKAN = 5)
        $inProcess = LaporanFasilitas::whereHas('laporan', function($q) use ($userId) {
                $q->where('id_pengguna', $userId);
            })
            ->whereIn('id_status', [Status::VALID, Status::DITUGASKAN])
            ->count();

        // 3) Laporan “Completed” milik user (SELESAI = 6)
        $completed = LaporanFasilitas::whereHas('laporan', function($q) use ($userId) {
                $q->where('id_pengguna', $userId);
            })
            ->where('id_status', Status::SELESAI)
            ->count();

        // 4) Top 5 Fasilitas Terbanyak Di‐Vote User
        //    Sekarang: gunakan withCount('pelaporLaporanFasilitas') bukan penilaianPengguna
        $topVoted = LaporanFasilitas::withCount('pelaporLaporanFasilitas')
            ->orderByDesc('pelapor_laporan_fasilitas_count')
            ->limit(5)
            ->with('fasilitas')
            ->get();

        // Ambil nama fasilitas dan jumlah vote
        $pieLabels = $topVoted->map(fn($item) => $item->fasilitas->nama_fasilitas ?? '-')->toArray();
        $pieData   = $topVoted->pluck('pelapor_laporan_fasilitas_count')->toArray();

        // 5) Fear & Greed Index (contoh hard‐coded)
        $fearAndGreed = 65;

        return view('dashboard-pelapor.index', compact(
            'totalLaporan',
            'inProcess',
            'completed',
            'pieLabels',
            'pieData',
            'fearAndGreed'
        ));
    }
}