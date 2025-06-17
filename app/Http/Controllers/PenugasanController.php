<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Penilaian;
use App\Models\Penugasan;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use App\Models\LaporanFasilitas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\RiwayatLaporanFasilitas;
use Yajra\DataTables\Facades\DataTables;

class PenugasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('penugasan.index')
        ;
    }

    public function list(Request $r)
    {
        $query = Penugasan::with(['laporanFasilitas.fasilitas','laporanFasilitas.laporan.pengguna', 'laporanFasilitas.status'])
                 ->where('id_pengguna', Auth::id())
                 ->whereHas('laporanFasilitas.status', function($q) {
                    $q->where(function($query) {
                        $query->where('nama_status', 'Ditugaskan')
                            ->orWhere('id_status', 5);
                    });
                });

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('fasilitas', fn($p)=> $p->laporanFasilitas->fasilitas->nama_fasilitas)
            ->addColumn('pelapor',    fn($p)=> $p->laporanFasilitas->laporan->pengguna->nama)
            ->addColumn('status',     fn($p)=> $p->laporanFasilitas->status->nama_status)
            ->addColumn('aksi', function($p){
                return "<button class='btn btn-sm btn-outline-primary btn-detail'
                            data-bs-toggle='modal'
                            data-bs-target='#dynamicModal'
                            data-url='".route('penugasan.show', $p->id_laporan_fasilitas)."'>
                            <i class='mdi mdi-wrench'></i>
                        </button>";
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function perbaikanForm($id)
    {
        $lapfas = LaporanFasilitas::with([
            'fasilitas',
            'tingkatKerusakan',
            'dampakPengguna',
            'laporan.pengguna',
            'laporan.gedung',
            'laporan.lantai',
            'laporan.ruangan',
            'riwayatLaporanFasilitas.pengguna',
            'penugasan.teknisi',
        ])->findOrFail($id);

        return view('penugasan.perbaikan', compact('lapfas'));
    }

    public function perbaikanSubmit(Request $r, $id)
    {

        // validasi
        $r->validate([
            'foto_perbaikan'      => 'required|image|max:4096',
            'deskripsi_perbaikan' => 'required|string',
            'jenis_perbaikan'    => 'required|in:perbaikan,penggantian',
        ]);

        $penugasan = Penugasan::where('id_laporan_fasilitas',$id)->first();

        // pastikan direktori uploads/perbaikan ada
        $perbaikanDir = public_path('storage/uploads/perbaikan');
        if (! File::isDirectory($perbaikanDir)) {
            File::makeDirectory($perbaikanDir, 0755, true);
        }

        // simpan file ke public/uploads/perbaikan
        $file     = $r->file('foto_perbaikan');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->move($perbaikanDir, $filename);
        $path     = "uploads/perbaikan/{$filename}";

        Perbaikan::create([
            'id_penugasan'        => $penugasan->id_penugasan,
            'foto_perbaikan'      => $path,
            'deskripsi_perbaikan' => $r->deskripsi_perbaikan,
            'jenis_perbaikan'     => $r->jenis_perbaikan,
        ]);

        LaporanFasilitas::where('id_laporan_fasilitas', $id)->update([
            'id_status' => Status::SELESAI,
        ]);

        $penugasan->update([
            'is_complete' => true,
        ]);

        // buat catatan riwayat
        RiwayatLaporanFasilitas::create([
            'id_laporan_fasilitas' => $id,
            'id_status'            => Status::SELESAI,
            'id_pengguna'          => auth()->id(),
            'catatan'              => 'Teknisi menyelesaikan perbaikan',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Perbaikan berhasil disimpan! Laporan telah ditandai sebagai selesai.'
        ]);
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
    public function show(Penugasan $penugasan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penugasan $penugasan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penugasan $penugasan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penugasan $penugasan)
    {
        //
    }
}
