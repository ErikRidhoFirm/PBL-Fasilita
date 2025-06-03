<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanFasilitas;
use Illuminate\Support\Facades\Auth;
use App\Models\PelaporLaporanFasilitas;

class PelaporLaporanFasilitasController extends Controller
{

    public function vote(Request $request, $id)
    {
        // User yang login
        $userId = $request->user()->id_pengguna;

        // Cari LaporanFasilitas
        $lapfas = LaporanFasilitas::find($id);
        if (! $lapfas) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Laporan fasilitas tidak ditemukan.',
            ], 404);
        }

        // Cek apakah user sudah vote
        $alreadyVoted = PelaporLaporanFasilitas::where('id_laporan_fasilitas', $id)
            ->where('id_pengguna', $userId)
            ->exists();

        if ($alreadyVoted) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda sudah mem‐vote fasilitas ini.',
            ], 422);
        }

        // Simpan vote baru
        PelaporLaporanFasilitas::create([
            'id_laporan_fasilitas' => $id,
            'id_pengguna'          => $userId,
        ]);

        // Hitung ulang
        $newCount = $lapfas->pelaporLaporanFasilitas()->count();

        return response()->json([
            'status'      => 'success',
            'message'     => 'Terima kasih telah mem‐vote!',
            'votes_count' => $newCount,
        ]);
    }

    /**
     * Aksi unvote (DELETE) untuk satu LaporanFasilitas.
     */
    public function unvote(Request $request, $id)
    {
        // User yang login
        $userId = $request->user()->id_pengguna;

        // Cari record vote-nya
        $vote = PelaporLaporanFasilitas::where('id_laporan_fasilitas', $id)
            ->where('id_pengguna', $userId)
            ->first();

        if (! $vote) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Vote tidak ditemukan atau sudah dibatalkan.',
            ], 404);
        }

        // Hapus vote
        $vote->delete();

        // Hitung ulang (opsional)
        $lapfas = LaporanFasilitas::find($id);
        $newCount = $lapfas ? $lapfas->pelaporLaporanFasilitas()->count() : 0;

        return response()->json([
            'status'      => 'success',
            'message'     => 'Vote berhasil dibatalkan.',
            'votes_count' => $newCount,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(PelaporLaporanFasilitas $pelaporLaporanFasilitas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PelaporLaporanFasilitas $pelaporLaporanFasilitas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PelaporLaporanFasilitas $pelaporLaporanFasilitas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PelaporLaporanFasilitas $pelaporLaporanFasilitas)
    {
        //
    }
}
