<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\Status;
use App\Models\Pengguna;
use App\Models\SkorTipe;
use App\Models\Penugasan;
use App\Models\SkorTopsis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RiwayatLaporanFasilitas;
use Yajra\DataTables\Facades\DataTables;

class SkorTopsisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
{
    $breadcrumbs = [
        ['title' => 'Dashboard', 'url' => route('dashboard')],
        ['title' => 'Prioritas Perbaikan', 'url' => route('skorTopsis.index')],
    ];

    $roles = Peran::whereIn('kode_peran', ['MHS', 'DSN', 'TDK'])
                ->select('id_peran', 'nama_peran')
                ->orderBy('nama_peran')
                ->get();

    return view('skorTopsis.index', compact('breadcrumbs', 'roles'));
}

public function list(Request $request)
{
    $run = SkorTipe::where('tipe', 'global')->latest('id_skor_tipe')->first();
    if (!$run) {
        return DataTables::of(collect())->make(true);
    }

    $query = DB::table('skor_topsis as s')
        ->join('laporan_fasilitas as lf', 's.id_laporan_fasilitas', '=', 'lf.id_laporan_fasilitas')
        ->join('fasilitas as f', 'lf.id_fasilitas', '=', 'f.id_fasilitas')
        ->join('laporan as l', 'lf.id_laporan', '=', 'l.id_laporan')
        ->join('pengguna as u', 'l.id_pengguna', '=', 'u.id_pengguna')
        ->join('peran as p', 'u.id_peran', '=', 'p.id_peran')
        ->leftJoin('penugasan as a', 'lf.id_laporan_fasilitas', '=', 'a.id_laporan_fasilitas')
        ->where('s.id_skor_tipe', $run->id_skor_tipe)
        ->whereIn('p.kode_peran', ['MHS', 'DSN', 'TDK'])
        ->when($request->filled('role_id'), function($q) use($request) {
            $q->where('p.id_peran', $request->role_id);
        })
        ->select([
            's.id_skor_topsis',
            'lf.id_laporan_fasilitas',
            'f.nama_fasilitas AS alternatif',
            'u.nama AS pelapor',
            'p.nama_peran AS peran',
            'p.kode_peran AS kode_peran',
            's.skor',
            'a.id_penugasan',
            'a.id_pengguna AS teknisi',
        ]);

    return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('skor', fn($row) => number_format($row->skor, 4))
        ->addColumn('aksi', function($row) {
            if ($row->id_penugasan) {
                return "<button class='btn btn-sm btn-info' disabled>Sudah Ditugaskan</button>";
            }
            $url = route('skorTopsis.assignForm', $row->id_skor_topsis);
            return "<button class='btn btn-sm btn-success assign-form' data-url='$url'>Tugaskan</button>";
        })
        ->rawColumns(['aksi'])
        ->orderColumn('skor', 's.skor $1')
        ->make(true);
}

    public function assignForm($id_skor_topsis)
    {
        $sk = SkorTopsis::findOrFail($id_skor_topsis);
        $lap = $sk->laporanFasilitas;

        $teknisis = Pengguna::whereHas('peran', fn($q)=> $q->where('kode_peran','TNS'))->get();

        return view('skorTopsis.assign', compact('sk','lap','teknisis'));
    }

    public function assign(Request $r, $id_skor_topsis)
    {
        $r->validate([
            'teknisi_id'=>'required|exists:pengguna,id_pengguna'
        ]);

        $sk = SkorTopsis::findOrFail($id_skor_topsis);
        $lapfasId = $sk->id_laporan_fasilitas;

        Penugasan::create([
            'id_laporan_fasilitas'=>$lapfasId,
            'id_pengguna'=>$r->teknisi_id
        ]);

        $sk->laporanFasilitas()->update(['id_status'=>5]);
        $teknisi = Pengguna::findOrFail($r->teknisi_id);

        RiwayatLaporanFasilitas::create([
            'id_laporan_fasilitas' => $sk->id_laporan_fasilitas,
            'id_status'            => Status::DITUGASKAN,
            'id_pengguna'          => auth()->id(),
            'catatan'              => 'Menugaskan perbaikan ke Teknisi '. $teknisi->nama,
        ]);

        return response()->json(['message'=>'Berhasil menugaskan ke teknisi.']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SkorTopsis $skorTopsis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SkorTopsis $skorTopsis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SkorTopsis $skorTopsis)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SkorTopsis $skorTopsis)
    {
        //
    }
}
