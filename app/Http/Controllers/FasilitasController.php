<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class FasilitasController extends Controller
{
    // 1️⃣ Tampil index (daftar fasilitas suatu ruangan)
    public function index(Ruangan $ruangan)
    {
        return view('fasilitas.index', compact('ruangan'));
    }

    // 2️⃣ AJAX DataTables
    public function list(Ruangan $ruangan)
    {
        $qry = $ruangan->fasilitas()
                       ->select(['id_fasilitas','nama_fasilitas','jumlah_fasilitas']);

        return DataTables::of($qry)
            ->addIndexColumn()
->addColumn('aksi', function($row){
    $edit = route('fasilitas.edit', $row->id_fasilitas);
    $del  = route('fasilitas.delete', $row->id_fasilitas);
    return <<<HTML
    <button onclick="modalAction('$edit')" class="btn btn-sm btn-warning m-1">
        <i class="mdi mdi-pencil m-0"></i>
    </button>
    <button onclick="modalAction('$del')" class="btn btn-sm btn-danger m-1">
        <i class="mdi mdi-delete m-0"></i>
    </button>
    HTML;
})
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // 3️⃣ Form Create
    public function create(Ruangan $ruangan)
{
    $kategories = \App\Models\KategoriFasilitas::all();
    return view('fasilitas.create', compact('ruangan','kategories'));
}

    // 4️⃣ Store baru
    public function store(Request $r, Ruangan $ruangan)
{
    $r->validate([
        'id_kategori'      => 'required|exists:kategori_fasilitas,id_kategori',
        'nama_fasilitas'   => 'required|string|max:100',
        'jumlah_fasilitas' => 'required|integer|min:1',
    ]);

    $f = new Fasilitas;
    $f->id_ruangan       = $ruangan->id_ruangan;
    $f->id_kategori      = $r->input('id_kategori');
    $f->nama_fasilitas   = $r->input('nama_fasilitas');
    $f->jumlah_fasilitas = $r->input('jumlah_fasilitas');
    $f->save();

    return response()->json([
        'status'  => true,
        'message' => 'Fasilitas berhasil ditambahkan'
    ]);
}

    // 5️⃣ Form Edit
    public function edit(Fasilitas $fasilitas)
    {
        return view('fasilitas.edit', compact('fasilitas'));
    }

    // 6️⃣ Update
    public function update(Request $r, Fasilitas $fasilitas)
{
    $r->validate([
        'nama_fasilitas'   => 'required|string|max:100',
        'jumlah_fasilitas' => 'required|integer|min:1',
    ]);

    // assign manual supaya kolom jumlah_fasilitas benar-benar tersimpan
    $fasilitas->nama_fasilitas   = $r->input('nama_fasilitas');
    $fasilitas->jumlah_fasilitas = $r->input('jumlah_fasilitas');
    $fasilitas->save();

    return response()->json([
        'status'  => true,
        'message' => 'Fasilitas berhasil diperbarui',
    ]);
}


    // 7️⃣ Form Hapus
    public function delete(Fasilitas $fasilitas)
    {
        return view('fasilitas.delete', compact('fasilitas'));
    }

    // 8️⃣ Destroy
    public function destroy(Fasilitas $fasilitas)
    {
        $fasilitas->delete();
        return response()->json(['status'=>true,'message'=>'Fasilitas berhasil dihapus']);
    }

    // 9️⃣ Detail (opsional)
    public function show(Fasilitas $fasilitas)
    {
        return view('fasilitas.show', compact('fasilitas'));
    }

    public function exportPdf(Ruangan $ruangan)
    {
        $fasilitas = $ruangan->fasilitas()
                            ->select('id_fasilitas', 'nama_fasilitas', 'jumlah_fasilitas')
                            ->orderBy('nama_fasilitas')
                            ->get();

        $lantai = $ruangan->lantai; // pastikan relasi ini ada di model Ruangan
        $gedung = $lantai->gedung; // pastikan relasi ini ada di model Lantai

        $pdf = PDF::loadView('fasilitas.export_pdf', compact('ruangan', 'fasilitas', 'lantai', 'gedung'))
                ->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Fasilitas_Ruangan_' . $ruangan->nama_ruangan . '_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
