<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanFasilitas;
use App\Models\PenilaianPengguna;
use Illuminate\Support\Facades\Auth;

class PenilaianPenggunaController extends Controller
{
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
    public function create($id)
    {
        // Cek status terakhir harus “Selesai”
        $lf = LaporanFasilitas::with('riwayatLaporanFasilitas.status')
             ->findOrFail($id);

        $lastStatus = $lf->riwayatLaporanFasilitas->last()->status->nama_status;
        if($lastStatus !== 'Selesai') {
            abort(404);
        }

        return view('riwayatPelapor.feedback', compact('lf'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $r, $id)
    {
        $r->validate([
            'nilai'  => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        PenilaianPengguna::create([
            'id_laporan_fasilitas' => $id,
            'id_pengguna'          => Auth::id(),
            'nilai'                => $r->nilai,
            'komentar'             => $r->komentar,
        ]);

        return response()->json(['message'=>'Terima kasih atas feedback Anda!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(PenilaianPengguna $penilaianPengguna)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $penilaian = PenilaianPengguna::find($id);
        return view('riwayatPelapor.feedback_edit', compact('penilaian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $penilaian = PenilaianPengguna::find($id);
        $request->validate([
            'nilai'  => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);
        $penilaian->update([
            'nilai'    => $request->nilai,
            'komentar' => $request->komentar,
        ]);
        return response()->json(['message' => 'Feedback berhasil diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penilaian = PenilaianPengguna::find($id);
        if ($penilaian) {
            $penilaian->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Feedback berhasil dihapus!'
            ]);
        }
        return response()->json([
            'status'  => 'error',
            'message' => 'Data feedback tidak ditemukan.'
        ], 404);
    }
}
