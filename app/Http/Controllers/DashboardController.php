<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanFasilitas;
use App\Models\Pengguna;
use App\Models\RiwayatLaporanFasilitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Pengguna $user */
        $user = Auth::user();

        if ($user->hasAnyRole(['MHS', 'DSN', 'TDK'])) {
            return redirect()->route('dashboard-pelapor.index');
        }
        
         // Hitung jumlah laporan berdasarkan id_status
        $jumlahStatus = [
        'menunggu' => LaporanFasilitas::where('id_status', 1)->count(),
        'tidak_valid' => LaporanFasilitas::where('id_status', 2)->count(),
        'ditolak' => LaporanFasilitas::where('id_status', 3)->count(),
        'valid' => LaporanFasilitas::where('id_status', 4)->count(),
        'ditugaskan' => LaporanFasilitas::where('id_status', 5)->count(),
        'selesai' => LaporanFasilitas::where('id_status', 6)->count(),
    ];        

     // Hitung jumlah laporan berdasarkan peran pelapor
    $laporanPerPeran = Pengguna::withCount('laporan')
        ->whereHas('peran', function ($query) {
            $query->whereIn('kode_peran', ['MHS', 'DSN', 'TDK']);
        })
        ->get()
        ->groupBy(fn($pengguna) => $pengguna->peran->kode_peran)
        ->map(fn($group) => $group->sum('laporan_count'))
        ->toArray();

    // Ambil jumlah laporan terverifikasi per bulan
    $verifikasiPerBulan = LaporanFasilitas::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
        ->where('id_status', 4) // mengambil id_status dari tabel status dan mengambil value nya 4 yaitu nilai yang valid / terverifikasi
        ->whereYear('created_at', now()->year)
        ->groupByRaw('MONTH(created_at)')
        ->orderBy('bulan')
        ->pluck('jumlah', 'bulan')
        ->toArray();

         // Buat array 12 bulan (Jan - Des)
    $bulan = range(1, 12);
    $verifikasiData = array_map(fn($b) => $verifikasiPerBulan[$b] ?? 0, $bulan);

    // Laporan diperbaiki (dari riwayat) per bulan
    $perbaikanPerBulan = RiwayatLaporanFasilitas::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
        ->where('id_status', 6) // status SELESAI
        ->whereYear('created_at', now()->year)
        ->groupByRaw('MONTH(created_at)')
        ->orderBy('bulan')
        ->pluck('jumlah', 'bulan')
        ->toArray();

    $perbaikanData = array_map(fn($b) => $perbaikanPerBulan[$b] ?? 0, range(1, 12));
     return view('dashboard.index', compact('jumlahStatus', 'laporanPerPeran', 'verifikasiData', 'perbaikanData'));
    }

    public function getRepairData()
    {
        // Ambil data 6 bulan terakhir
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        // Query untuk mengambil data perbaikan yang selesai
        $repairData = DB::table('perbaikan')
            ->join('penugasan', 'perbaikan.id_penugasan', '=', 'penugasan.id_penugasan')
            ->join('laporan_fasilitas', 'penugasan.id_laporan_fasilitas', '=', 'laporan_fasilitas.id_laporan_fasilitas')
            ->where('penugasan.is_complete', true)
            ->where('perbaikan.created_at', '>=', $sixMonthsAgo)
            ->select(
                DB::raw('MONTH(perbaikan.created_at) as month'),
                'perbaikan.jenis_perbaikan',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month', 'jenis_perbaikan')
            ->orderBy('month')
            ->get();

        // Siapkan data untuk chart
        $labels = [];
        $perbaikanData = [];
        $penggantianData = [];

         // Iterasi untuk 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->month;
            $monthName = Carbon::now()->subMonths($i)->format('M'); // Nama bulan (Jan, Feb, dll.)
            $labels[] = $monthName;

            // Hitung jumlah untuk setiap jenis perbaikan
            $perbaikanCount = $repairData->where('month', $month)
                                        ->where('jenis_perbaikan', 'perbaikan')
                                        ->sum('count');
            $penggantianCount = $repairData->where('month', $month)
                                          ->where('jenis_perbaikan', 'penggantian')
                                          ->sum('count');

            $perbaikanData[] = $perbaikanCount;
            $penggantianData[] = $penggantianCount;
        }

          // Kembalikan data dalam format JSON
        return response()->json([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Perbaikan',
                    'data' => $perbaikanData,
                    'backgroundColor' => '#98BDFF' // Warna untuk perbaikan
                ],
                [
                    'label' => 'Penggantian',
                    'data' => $penggantianData,
                    'backgroundColor' => '#4B49AC' // Warna untuk penggantian
                ]
            ]
        ]);
    }

     public function getStatusChartData()
    {
        // Tentukan rentang 6 bulan terakhir
        $now = Carbon::now();
        $sixMonthsAgo = $now->copy()->subMonths(5)->startOfMonth();

        // Ambil semua laporan_fasilitas aktif sejak 6 bulan terakhir,
        // join dengan tabel status agar kita tahu nama status-nya
        $raw = DB::table('laporan_fasilitas as lf')
            ->join('status as s', 'lf.id_status', '=', 's.id_status')
            ->where('lf.is_active', true)
            ->where('lf.created_at', '>=', $sixMonthsAgo)
            ->select(
                DB::raw('MONTH(lf.created_at) as month'),
                's.nama_status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month', 's.nama_status')
            ->orderBy('month')
            ->get();

              // Inisialisasi label (nama bulan) dan array untuk dua dataset
        $labels = [];
        $waitingData = [];
        $doneData = [];

        // Loop tiap bulan dari 5 bulan lalu sampai sekarang
        for ($i = 5; $i >= 0; $i--) {
            $dt = $now->copy()->subMonths($i);
            $monthNum = $dt->month;
            $labels[] = $dt->format('M'); // Jan, Feb, dst.

            // Ambil jumlah “menunggu” & “selesai” untuk bulan ini
            $waiting = $raw
                ->where('month', $monthNum)
                ->where('nama_status', 'Menunggu')
                ->sum('total');

            $done = $raw
                ->where('month', $monthNum)
                ->where('nama_status', 'Selesai')
                ->sum('total');

            $waitingData[] = $waiting;
            $doneData[]    = $done;
        }

        // Kembalikan JSON dengan format Chart.js-compatible
        return response()->json([
            'labels'   => $labels,
            'datasets' => [
                [
                    'label'           => 'Menunggu diverifikasi',
                    'data'            => $waitingData,
                    'borderColor'     => '#4747A1',  // warna garis untuk “menunggu”
                    'borderWidth'     => 2,
                    'fill'            => false,
                    'tension'         => 0.35,
                ],
                [
                    'label'           => 'Sudah selesai',
                    'data'            => $doneData,
                    'borderColor'     => '#F09397',  // warna garis untuk “selesai”
                    'borderWidth'     => 2,
                    'fill'            => false,
                    'tension'         => 0.35,
                ],
            ]
   ]);
}
}