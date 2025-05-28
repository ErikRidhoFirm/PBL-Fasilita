<?php

namespace App\Http\Controllers;


use App\Models\Status;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use App\Models\LaporanFasilitas;
use App\Models\RiwayatLaporanFasilitas;
use Yajra\DataTables\Facades\DataTables;


class RiwayatLaporanFasilitasController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard',          'url' => route('dashboard')],
            ['title' => 'Riwayat Laporan Fasilitas', 'url' => route('riwayat.index')],
        ];
        $activeMenu = 'laporanFasilitas';


        $statuses = Status::select('id_status','nama_status')->get();

        $petugas  = Pengguna::whereHas('peran', fn($q)=>
                      $q->whereIn('kode_peran',['ADM','SPR']))
                    ->select('id_pengguna','nama')
                    ->get();

        return view('riwayat.index', compact(
            'breadcrumbs','activeMenu','statuses','petugas'
        ));
    }

    public function list(Request $request)
    {
        $latest = RiwayatLaporanFasilitas::selectRaw('MAX(id_riwayat_laporan_fasilitas) as id')
                ->groupBy('id_laporan_fasilitas');

        $query = RiwayatLaporanFasilitas::with([
                    'laporanFasilitas.fasilitas',
                    'laporanFasilitas.laporan.pengguna',
                    'status',
                    'pengguna'
                ])
                ->whereIn('id_riwayat_laporan_fasilitas', function($q) use ($latest) {
                    $q->fromSub($latest, 'x')->select('x.id');
                });


        if ($request->filled('status_id')) {
            $query->where('id_status', $request->status_id);
        }


        if ($request->filled('petugas_id')) {
            $query->where('id_pengguna', $request->petugas_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('pelapor', fn($r) => $r->laporanFasilitas->laporan->pengguna->nama)
            ->addColumn('fasilitas', fn($r) => $r->laporanFasilitas->fasilitas->nama_fasilitas)
            ->addColumn('status', fn($r) => $r->status->nama_status)
            ->addColumn('petugas', fn($r) => $r->pengguna->nama)
            ->addColumn('waktu', fn($r) => $r->created_at->format('d-m-Y H:i'))
            ->addColumn('aksi', function($r) {
                // Gunakan id_laporan_fasilitas untuk route
                $lapfasId = $r->id_laporan_fasilitas;

                $showUrl = route('riwayat.show', $lapfasId);
                $delUrl  = route('riwayat.destroy', $lapfasId);

                return
                "<a href='$showUrl'
                    class='btn btn-sm btn-outline-primary'>
                    <i class='mdi mdi-file-document-box'></i>
                </a> ";
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($lapfasId)
    {
        $lapfas = LaporanFasilitas::with([
            'fasilitas',
            'kategoriKerusakan',
            'laporan.pengguna',
            'laporan.gedung',
            'laporan.lantai',
            'laporan.ruangan',
        ])->findOrFail($lapfasId);

        $riwayats = RiwayatLaporanFasilitas::with('status','pengguna')
                    ->where('id_laporan_fasilitas', $lapfasId)
                    ->orderBy('created_at')
                    ->get();

        return view('riwayat.show', compact('lapfas','riwayats'));
    }


    public function detailModal($riwayatId)
    {
        $riwayat = RiwayatLaporanFasilitas::with([
            'laporanFasilitas.fasilitas',
            'laporanFasilitas.laporan.pengguna',
            'status',
            'pengguna.peran',
            'laporanFasilitas.penilaian.skorKriteriaLaporan.kriteria',
            'laporanFasilitas.penugasan.perbaikan',
            'laporanFasilitas.penugasan.teknisi',
        ])->findOrFail($riwayatId);

        // also find the “Valid” timestamp and “Ditugaskan” timestamp for durasi
        $all = RiwayatLaporanFasilitas::where('id_laporan_fasilitas', $riwayat->id_laporan_fasilitas)
                     ->orderBy('created_at')
                     ->get();
        $t_valid = optional($all->firstWhere(fn($r)=> $r->status->nama_status==='Valid'))->created_at;
        $t_tug   = optional($all->firstWhere(fn($r)=> $r->status->nama_status==='Ditugaskan'))->created_at;

        return view('riwayat.detail_modal', compact('riwayat','t_valid','t_tug'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($lapfasId)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $lapfasId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($lapfasId)
    {

    }
}
