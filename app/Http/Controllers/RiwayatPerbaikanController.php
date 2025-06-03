<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perbaikan;
use Yajra\DataTables\Facades\DataTables;

class RiwayatPerbaikanController extends Controller
{
    /**
     * Tampilkan halaman index yang berisi DataTable.
     */
    public function index()
    {
        return view('riwayat-perbaikan.index');
    }

    /**
     * Mengembalikan JSON untuk DataTable (server-side processing).
     */
    public function list()
    {
        // Eager‐load seluruh relasi yang diperlukan
        $query = Perbaikan::with([
            'penugasan.teknisi',
            'penugasan.laporanFasilitas.laporan.gedung',
            'penugasan.laporanFasilitas.laporan.lantai',
            'penugasan.laporanFasilitas.laporan.ruangan',
            'penugasan.laporanFasilitas.fasilitas',
            'penugasan.laporanFasilitas.kategoriKerusakan',
            'penugasan.laporanFasilitas.status'
        ])->orderBy('updated_at', 'desc');

        return DataTables::of($query)
            // Tambahkan kolom urutan otomatis
            ->addIndexColumn()
            // Kolom fasilitas
            ->addColumn('fasilitas', function($item) {
                return optional($item->penugasan->laporanFasilitas->fasilitas)->nama_fasilitas ?? '-';
            })
            // Kolom gedung
            ->addColumn('gedung', function($item) {
                return optional($item->penugasan->laporanFasilitas->laporan->gedung)->nama_gedung ?? '-';
            })
            // Kolom ruangan
            ->addColumn('ruangan', function($item) {
                return optional($item->penugasan->laporanFasilitas->laporan->ruangan)->nama_ruangan ?? '-';
            })
            // Kolom status (badge)
            ->addColumn('status', function($item) {
                $namaStatus = optional($item->penugasan->laporanFasilitas->status)->nama_status ?? '-';
                $lower = strtolower($namaStatus);
                if ($lower === 'selesai' || $lower === 'valid') {
                    return '<span class="badge badge-success">'.$namaStatus.'</span>';
                }
                if ($lower === 'menunggu' || $lower === 'pending') {
                    return '<span class="badge badge-warning">'.$namaStatus.'</span>';
                }
                return '<span class="badge badge-secondary">'.$namaStatus.'</span>';
            })
            // Kolom aksi: tombol Edit (jika status != selesai) dan tombol Detail
            ->addColumn('aksi', function($item) {
                $showUrl = route('riwayat-perbaikan.show', $item->id_perbaikan);
                $editUrl = route('riwayat-perbaikan.edit', $item->id_perbaikan);
                $namaStatus = optional($item->penugasan->laporanFasilitas->status)->nama_status;
                $lower = strtolower($namaStatus);

                $buttons = '<div class="d-flex justify-content-center">';
                // Tombol Edit hanya jika status bukan "selesai" atau "valid"
                if ($lower !== 'selesai' && $lower !== 'valid') {
                    $buttons .= '
                        <button class="btn btn-sm btn-warning btn-hover mr-1"
                                onclick="modalAction(\''.$editUrl.'\')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>';
                }
                // Tombol Detail selalu muncul
                $buttons .= '
                    <button class="btn btn-sm btn-info btn-hover"
                            onclick="modalAction(\''.$showUrl.'\')" title="Detail">
                        <i class="fas fa-list"></i>
                    </button>';
                $buttons .= '</div>';
                return $buttons;
            })
            // Karena kita mengembalikan HTML untuk kolom status & aksi, kita rawColumns
            ->rawColumns(['status','aksi'])
            ->make(true);
    }

    /**
     * Menampilkan partial view untuk detail perbaikan (modal).
     */
    public function show(Perbaikan $perbaikan)
    {
        // Eager-load relasi yang akan dipakai di view
        $perbaikan->load([
            'penugasan.teknisi',
            'penugasan.laporanFasilitas.laporan.gedung',
            'penugasan.laporanFasilitas.laporan.lantai',
            'penugasan.laporanFasilitas.laporan.ruangan',
            'penugasan.laporanFasilitas.fasilitas',
            'penugasan.laporanFasilitas.kategoriKerusakan',
            'penugasan.laporanFasilitas.status',
            'penugasan.laporanFasilitas.riwayatLaporanFasilitas.pengguna.peran'
        ]);

        // Debug singkat: jika masih error, aktifkan baris dd() ini untuk inspect
        // dd($perbaikan->toArray());

        // Kembalikan partial view (hanya konten modal)
        return view('riwayat-perbaikan.show', compact('perbaikan'));
    }

    /**
     * (Opsional) Menampilkan form edit di modal.
     */
    public function edit(Perbaikan $perbaikan)
    {
        $perbaikan->load([
            'penugasan.teknisi',
            'penugasan.laporanFasilitas.laporan.gedung',
            'penugasan.laporanFasilitas.laporan.lantai',
            'penugasan.laporanFasilitas.laporan.ruangan',
            'penugasan.laporanFasilitas.fasilitas',
            'penugasan.laporanFasilitas.kategoriKerusakan',
            'penugasan.laporanFasilitas.status',
        ]);

        return view('riwayat-perbaikan.edit', compact('perbaikan'));
    }

    /**
     * (Opsional) Proses update data perbaikan dari modal edit.
     */
    public function update(Request $request, Perbaikan $perbaikan)
    {
        $request->validate([
            'jenis_perbaikan'    => 'required|string',
            'deskripsi_perbaikan'=> 'required|string',
            // 'foto_perbaikan'  => 'nullable|image|max:2048',
        ]);

        $perbaikan->jenis_perbaikan     = $request->jenis_perbaikan;
        $perbaikan->deskripsi_perbaikan = $request->deskripsi_perbaikan;

        if ($request->hasFile('foto_perbaikan')) {
            $file     = $request->file('foto_perbaikan');
            $namaFile = time().'_'.$file->getClientOriginalName();
            $file->storeAs('foto_perbaikan', $namaFile, 'public');
            $perbaikan->foto_perbaikan = $namaFile;
        }

        $perbaikan->save();

        return redirect()->route('riwayat-perbaikan.index')
                         ->with('success', 'Data perbaikan berhasil diperbarui.');
    }
}
