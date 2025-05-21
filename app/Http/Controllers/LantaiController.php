<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use App\Models\Lantai;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class LantaiController extends Controller
{
    /* ----------  HALAMAN LIST  ---------- */
    public function index(Gedung $gedung)
    {
        return view('lantai.index', compact('gedung'));
    }

    /* ----------  DATA AJAX DATATABLES  ---------- */
    public function list(Gedung $gedung)
    {
        $data = $gedung->lantai()
                       ->select(['id_lantai', 'nomor_lantai']);   // ← hanya kolom yg ada

                       return DataTables::of($data)
                       ->addIndexColumn()
                       ->addColumn('pilih', function($row){
            return '<button
                        onclick="window.location=\''.route('lantai.ruangan.index',$row->id_lantai).'\'"
                        class="btn btn-primary btn-pilih">
                        <i class="mdi mdi-door-open"></i>
                        <span class="ms-1">Pilih</span>
                    </button>';
        })
                       ->addColumn('aksi', function ($row) {
                           return '
                           <div class="btn-group">
                   
                               <!-- tombol Edit -->
                <button onclick="modalAction(\''.route('lantai.edit',   $row->id_lantai).'\')"
                        class="btn btn-warning btn-sm">
                    <i class="mdi mdi-pencil"></i>
                </button>

                   
                               <!-- Hapus -->
                               <button onclick="modalAction(\''.route('lantai.delete',$row->id_lantai).'\')"
                                       class="btn btn-danger btn-sm">
                                 <i class="mdi mdi-delete"></i>
                               </button>
                           </div>';
                       })
                       ->rawColumns(['aksi', 'pilih']) // kolom yang berisi HTML
                       ->make(true);                   
    }

    /* ----------  TAMBAH  ---------- */
    public function create(Gedung $gedung)
    {
        return view('lantai.create', compact('gedung'));
    }

    public function store(Request $r, Gedung $gedung)
    {
        $r->validate([
            'nomor_lantai' => 'required|max:10',
        ]);

        $gedung->lantai()->create($r->only('nomor_lantai'));

        return response()->json(['status' => true, 'message' => 'Lantai ditambahkan']);
    }

    /* ----------  EDIT  ---------- */
    public function edit(Lantai $lantai)
    {
        return view('lantai.edit', compact('lantai'));
    }

    public function update(Request $r, Lantai $lantai)
    {
        $r->validate([
            'nomor_lantai' => 'required|max:10',
        ]);

        $lantai->update($r->only('nomor_lantai'));

        return response()->json(['status' => true, 'message' => 'Lantai diperbarui']);
    }

    /* ----------  DELETE  ---------- */
    public function delete(Lantai $lantai)
    {
        return view('lantai.delete', compact('lantai'));
    }

    public function destroy(Lantai $lantai)
    {
        // Hapus dulu semua ruangan yang ada di lantai ini
        $lantai->ruangan()->delete();

        // Baru hapus lantainya
        $lantai->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Lantai beserta ruangan-nya berhasil dihapus',
        ]);
    }
    /* ----------  DETAIL  ---------- */
    public function show(Lantai $lantai)
    {
        return view('lantai.show', compact('lantai'));
    }
}
