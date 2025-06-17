<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perbaikan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class RiwayatPerbaikanTeknisiController extends Controller
{
    /**
     * Tampilkan halaman index untuk teknisi (DataTable).
     */
    public function index()
    {
        return view('riwayat-perbaikan-teknisi.index');
    }

    /**
     * Mengembalikan JSON untuk DataTable (hanya perbaikan yang ditugaskan ke teknisi ini).
     */
    public function list()
    {
        // Ambil id_pengguna teknisi yang sedang login
        $teknisiId = Auth::user()->id_pengguna;

        // Query Perbaikan, eager‐load relasi yang diperlukan
        $query = Perbaikan::with([
            'penugasan.teknisi',
            'penugasan.laporanFasilitas.laporan.gedung',
            'penugasan.laporanFasilitas.laporan.ruangan',
            'penugasan.laporanFasilitas.fasilitas',
        ])
        ->whereHas('penugasan', function($q) use ($teknisiId) {
            $q->where('id_pengguna', $teknisiId);
        })
        ->orderBy('updated_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()

            // Kolom Gedung
            ->addColumn('gedung', function($item) {
                return optional(
                    optional($item->penugasan->laporanFasilitas->laporan)->gedung
                )->nama_gedung ?? '-';
            })

            // Kolom Ruangan
            ->addColumn('ruangan', function($item) {
                return optional(
                    optional($item->penugasan->laporanFasilitas->laporan)->ruangan
                )->nama_ruangan ?? '-';
            })

            // Kolom Nama Fasilitas
            ->addColumn('fasilitas', function($item) {
                return optional($item->penugasan->laporanFasilitas->fasilitas)
                       ->nama_fasilitas ?? '-';
            })

            // Kolom Nama Teknisi (biasanya sama dengan auth user,
            // tapi tampilkan juga untuk konsistensi)
            ->addColumn('teknisi', function($item) {
                return optional($item->penugasan->teknisi)->nama ?? '-';
            })

            // Kolom Aksi: tombol Detail (memanggil same show partial)
            ->addColumn('aksi', function($item) {
                $showUrl = route('riwayat-perbaikan-teknisi.show', $item->id_perbaikan);
                return '
                  <div class="text-center">
                    <button class="btn btn-sm btn-info btn-hover"
                            onclick="modalAction(\''.$showUrl.'\')"
                            title="Detail">
                      <i class="fas fa-list"></i>
                    </button>
                  </div>';
            })

            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Menampilkan partial view detail perbaikan (modal) —
     * memanggil view yang sama seperti di admin.
     */
    public function show(Perbaikan $perbaikan)
    {
        // Pastikan hanya teknisi yang ditugaskan dapat melihat detail ini
        if ($perbaikan->penugasan->id_pengguna !== Auth::user()->id_pengguna) {
            abort(403);
        }

        // Eager‐load relasi
        $perbaikan->load([
            'penugasan.teknisi',
            'penugasan.laporanFasilitas.laporan.gedung',
            'penugasan.laporanFasilitas.laporan.lantai',
            'penugasan.laporanFasilitas.laporan.ruangan',
            'penugasan.laporanFasilitas.fasilitas',
            'penugasan.laporanFasilitas.tingkatKerusakan',
            'penugasan.laporanFasilitas.dampakPengguna',
            'penugasan.laporanFasilitas.status',
            'penugasan.laporanFasilitas.riwayatLaporanFasilitas.pengguna.peran'
        ]);

        return view('riwayat-perbaikan-teknisi/show', compact('perbaikan'));
    }
}
