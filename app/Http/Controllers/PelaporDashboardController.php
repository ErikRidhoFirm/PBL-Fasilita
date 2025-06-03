<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Laporan;
use App\Models\LaporanFasilitas;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class PelaporDashboardController extends Controller
{
    public function index()
    {
        // ID user yang sedang login
        $userId = Auth::user()->id_pengguna;

        // 1) Total Laporan (count di tabel laporan)
        $totalLaporan = Laporan::where('id_pengguna', $userId)->count();

        // 2) Laporan "In Process": 
        //    kita anggap status VALID (4) atau DITUGASKAN (5) sebagai in‐process
        $inProcess = LaporanFasilitas::whereHas('laporan', function($q) use ($userId) {
                $q->where('id_pengguna', $userId);
            })
            ->whereIn('id_status', [
                Status::VALID, 
                Status::DITUGASKAN
            ])
            ->count();

        // 3) Laporan "Completed": status SELESAI (6)
        $completed = LaporanFasilitas::whereHas('laporan', function($q) use ($userId) {
                $q->where('id_pengguna', $userId);
            })
            ->where('id_status', Status::SELESAI)
            ->count();

        // 4) Pie Chart: "Top 5 Fasilitas yang paling sering dilaporkan"
        //    Kita group by id_fasilitas di laporan_fasilitas, hitung count, urutkan desc.
        $topFasilitas = LaporanFasilitas::select('id_fasilitas', DB::raw('COUNT(*) as total'))
            ->whereHas('laporan', function($q) use ($userId) {
                $q->where('id_pengguna', $userId);
            })
            ->groupBy('id_fasilitas')
            ->orderByDesc('total')
            ->with('fasilitas')  // eager‐load relasi fasilitas untuk ambil nama
            ->limit(5)
            ->get();

        // Siapkan data pie chart: label dan values
        $pieLabels = $topFasilitas->map(fn($item) => $item->fasilitas->nama_fasilitas)->toArray();
        $pieData   = $topFasilitas->pluck('total')->toArray();

        return view('dashboard-pelapor.index', compact(
            'totalLaporan',
            'inProcess',
            'completed',
            'pieLabels',
            'pieData'
        ));
    }
}
