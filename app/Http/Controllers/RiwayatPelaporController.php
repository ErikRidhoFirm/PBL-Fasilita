<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use App\Models\LaporanFasilitas;
use App\Models\PenilaianPengguna;
use Illuminate\Support\Facades\Auth;

class RiwayatPelaporController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // hitung per-status sesuai dengan model Status
        $counts = [
            'Menunggu' => LaporanFasilitas::whereHas('laporan', fn($q) => $q->where('id_pengguna', $userId))
                                ->where('id_status', Status::MENUNGGU)->count(),
            'Tidak Valid' => LaporanFasilitas::whereHas('laporan', fn($q) => $q->where('id_pengguna', $userId))
                                ->where('id_status', Status::TIDAK_VALID)->count(),
            'Ditolak' => LaporanFasilitas::whereHas('laporan', fn($q) => $q->where('id_pengguna', $userId))
                                ->where('id_status', Status::DITOLAK)->count(),
            'Valid' => LaporanFasilitas::whereHas('laporan', fn($q) => $q->where('id_pengguna', $userId))
                                ->where('id_status', Status::VALID)->count(),
            'Ditugaskan' => LaporanFasilitas::whereHas('laporan', fn($q) => $q->where('id_pengguna', $userId))
                                ->where('id_status', Status::DITUGASKAN)->count(),
            'Selesai' => LaporanFasilitas::whereHas('laporan', fn($q) => $q->where('id_pengguna', $userId))
                                ->where('id_status', Status::SELESAI)->count(),
        ];

        $reports = LaporanFasilitas::with([
                        'laporan',
                        'fasilitas.ruangan.lantai.gedung',
                        'status'
                    ])
                    ->whereHas('laporan', fn($q) => $q->where('id_pengguna', $userId))
                    ->orderByDesc('created_at')
                    ->get();

        return view('riwayatPelapor.index', compact('counts', 'reports'));
    }

    public function show($id)
    {
        $lf = LaporanFasilitas::with([
                    'laporan',
                    'fasilitas.ruangan.lantai.gedung',
                    'status','riwayatLaporanFasilitas.status','riwayatLaporanFasilitas.pengguna'
                ])
                ->whereHas('laporan', fn($q)=> $q->where('id_pengguna',Auth::id()))
                ->findOrFail($id);

        // cek apakah status terakhir = Edit Laporan
        $last = $lf->riwayatLaporanFasilitas->sortBy('created_at')->last();

        return view('riwayatPelapor.show', [
            'lf'      => $lf,
            'canEdit' => $last && $last->status->nama_status === 'Edit Laporan'
        ]);
    }

    public function edit($id)
    {
        $lf = LaporanFasilitas::with('riwayatLaporanFasilitas.status')
                ->whereHas('laporan', fn($q)=> $q->where('id_pengguna',Auth::id()))
                ->findOrFail($id);

        return view('riwayatPelapor.edit', compact('lf'));
    }

    public function update(Request $request, $id)
    {
        $lf = LaporanFasilitas::whereHas('laporan', fn($q) => $q->where('id_pengguna', Auth::id()))
                ->findOrFail($id);

        $validated = $request->validate([
            'deskripsi' => 'required|string',
            'path_foto' => 'nullable|image|max:2048',
        ]);

        // Update record
        $lf->update([
            'deskripsi' => $validated['deskripsi'],
            'path_foto' => $request->file('path_foto') ?
                        $request->file('path_foto')->store('laporan_foto', 'public') :
                        $lf->path_foto,
            'id_status' => 1
        ]);

        // Add history record
        $lf->riwayatLaporanFasilitas()->create([
            'id_status' => 1,
            'id_pengguna' => Auth::id(),
            'catatan' => 'Mahasiswa edit ulang laporan',
        ]);

        return response()->json([
            'message' => 'Laporan berhasil diperbarui!'
        ]);
    }
}
