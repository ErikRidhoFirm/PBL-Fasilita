<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class PeranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Halaman Peran', 'url' => route('peran.index')]
        ];

        $activeMenu = 'peran';
        return view('peran.index', compact('activeMenu', 'breadcrumbs'));
    }

    public function list(){
    $data = Peran::select('id_peran', 'kode_peran', 'nama_peran');
    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function($row) {
            $editBtn = '<button onclick="modalAction(\'' . url('/peran/edit/' . $row->id_peran) . '\')"
                            type="button"
                            class="btn btn-sm btn-outline-warning"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                                style="margin-right: 8px;">
                            <i class="mdi mdi-pencil m-0"></i>
                        </button>';

            $showBtn = '<button onclick="modalAction(\'' . url('/peran/show/' . $row->id_peran) . '\')"
                            type="button"
                            class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail"
                                style="margin-right: 8px;">
                            <i class="mdi mdi-file-document-box m-0"></i>
                        </button>';

            $deleteBtn = '<button onclick="modalAction(\'' . url('/peran/delete/' . $row->id_peran) . '\')"
                            type="button"
                            class="btn btn-sm btn-outline-danger"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"
                            data-id="'.$row->id_peran.'">
                            <i class="mdi mdi-delete m-0"></i>
                        </button>';

            return '<div class="d-flex">' . $editBtn . $showBtn . $deleteBtn . '</div>';
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('peran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_peran' => 'required|string|min:3|unique:peran,kode_peran',
                'nama_peran' => 'required|string|max:100',
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules, [
                'kode_peran.unique' => 'Kode peran sudah digunakan',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Terjadi kesalahan',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            Peran::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data peran berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $peran = Peran::find($id);
        return view('peran.show',[
            'peran' => $peran
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $peran = Peran::find($id);
        return view('peran.edit',[
            'peran' => $peran
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_peran' => 'required|string|min:3|unique:peran,kode_peran,'.$id.',id_peran',
                'nama_peran' => 'required|string|max:100',
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules,[
                'kode_peran.unique' => 'Kode peran sudah digunakan',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,    // respon json, true: berhasil, false: gagal
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error
                ]);
            }

            $check = Peran::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        redirect('/');
    }

    /**
     * Show data for confirmation before deletion.
     */
    public function delete(string $id)
    {
        $peran = Peran::find($id);
        return view('peran.delete',[
            'peran' => $peran
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $peran = Peran::find($id);
            if ($peran) {
                $peran->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        redirect('/');
    }


    public function import()
    {
        return view('peran.import');
    }

    public function importAjax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_peran' => ['required', 'mimes:xlsx', 'max:2048'],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $file = $request->file('file_peran');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $insert = [];
            foreach ($rows as $i => $row) {
                if ($i === 1) continue; // skip header
                if (empty($row['A']) && empty($row['B'])) continue;
                $insert[] = [
                    'kode_peran' => $row['A'],
                    'nama_peran' => $row['B'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (count($insert) === 0) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }

            Peran::insertOrIgnore($insert);

            return response()->json([
                'status'  => true,
                'message' => 'Data peran berhasil diimport'
            ]);
        }

        return redirect()->route('peran.index');
    }


    public function exportPdf()
    {
        $peran = Peran::orderBy('kode_peran')->get();

        $pdf = Pdf::loadView('peran.export_pdf', compact('peran'))
                ->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Peran_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
