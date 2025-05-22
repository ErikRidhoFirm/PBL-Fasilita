<?php

namespace App\Http\Controllers;

use App\Models\KategoriFasilitas;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriFasilitasController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Kategori Fasilitas', 'url' => route('kategoriF.index')],
        ];

        $activeMenu = 'kategori-fasilitas';
        return view('kategori-fasilitas.index', compact('activeMenu', 'breadcrumbs'));
    }

    public function list()
{
    $data = KategoriFasilitas::select('id_kategori', 'kode_kategori', 'nama_kategori');

    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function ($row) {
            $editBtn = '<button type="button"
                            class="btn btn-warning btn-sm btn-edit d-inline-flex align-items-center justify-content-center"
                            style="margin-right: 8px;"
                            onclick="modalAction(\'' . url('/kategori-fasilitas/edit/' . $row->id_kategori) . '\')">
                            <i class="mdi mdi-pencil m-0"></i>
                        </button>';

            $showBtn = '<button type="button"
                            class="btn btn-info btn-sm btn-show d-inline-flex align-items-center justify-content-center"
                            style="margin-right: 8px;"
                            onclick="modalAction(\'' . url('/kategori-fasilitas/show/' . $row->id_kategori) . '\')">
                            <i class="mdi mdi-file-document-box m-0"></i>
                        </button>';

            $deleteBtn = '<button type="button"
                            class="btn btn-danger btn-sm btn-delete d-inline-flex align-items-center justify-content-center"
                            onclick="modalAction(\'' . url('/kategori-fasilitas/delete/' . $row->id_kategori) . '\')">
                            <i class="mdi mdi-delete m-0"></i>
                        </button>';

            return '<div class="d-flex">' . $editBtn . $showBtn . $deleteBtn . '</div>';
        })
        ->rawColumns(['aksi'])
        ->make(true);
}


    public function create()
    {
        return view('kategori-fasilitas.create');
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_kategori' => 'required|string|min:2|unique:kategori_fasilitas,kode_kategori',
                'nama_kategori' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            KategoriFasilitas::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data kategori fasilitas berhasil disimpan.'
            ]);
        }
        return redirect('/');
    }

    public function show($id)
    {
        $kategori = KategoriFasilitas::find($id);
        return view('kategori-fasilitas.show', compact('kategori'));
    }

    public function edit($id)
    {
        $kategori = KategoriFasilitas::find($id);
        return view('kategori-fasilitas.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_kategori' => 'required|string|min:2|unique:kategori_fasilitas,kode_kategori,' . $id . ',id_kategori',
                'nama_kategori' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $kategori = KategoriFasilitas::find($id);
            if ($kategori) {
                $kategori->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data kategori berhasil diperbarui.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan.'
                ]);
            }
        }
        return redirect('/');
    }

    public function delete($id)
    {
        $kategori = KategoriFasilitas::find($id);
        return view('kategori-fasilitas.delete', compact('kategori'));
    }

    public function destroy(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriFasilitas::find($id);
            if ($kategori) {
                $kategori->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan.'
                ]);
            }
        }
        return redirect('/');
    }

    public function exportPdf()
    {
        $kategoriFasilitas = KategoriFasilitas::select('id_kategori', 'kode_kategori', 'nama_kategori')
            ->orderBy('kode_kategori')
            ->get();

        $pdf = PDF::loadView('kategori-fasilitas.export_pdf', compact('kategoriFasilitas'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Kategori_Fasilitas_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}