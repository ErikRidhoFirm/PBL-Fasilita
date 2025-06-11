<?php

namespace App\Http\Controllers;

use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GedungController extends Controller
{
    /* ----------  LIST PAGE  ---------- */
    public function index()
    {
        return view('gedung.index');
    }

    /* ----------  DATATABLES AJAX  ---------- */
    public function list()
    {
        $data = Gedung::select('id_gedung', 'kode_gedung', 'nama_gedung');

        return DataTables::of($data)
            ->addIndexColumn()
            // Kolom PILIH, tombol full-width dan warna primer
            ->addColumn('pilih', function ($row) {
                return '<button
                onclick="window.location=\'' . route('gedung.lantai.index', $row->id_gedung) . '\'"
                class="btn btn-primary btn-pilih">
                <i class="mdi mdi-layers"></i>
                <span class="ms-1">Pilih</span>
            </button>';
            })
            ->addColumn('aksi', function ($row) {
                return '
                <div>
                    <button onclick="modalAction(\'' . route('gedung.edit', $row->id_gedung) . '\')" class="btn btn-warning btn-sm" style="margin-right:8px;">
                        <i class="mdi mdi-pencil m-0"></i>
                    </button>

                    <button onclick="modalAction(\'' . route('gedung.delete', $row->id_gedung) . '\')" class="btn btn-danger btn-sm">
                        <i class="mdi mdi-delete m-0"></i>
                    </button>
                </div>';
            })

            ->rawColumns(['pilih', 'aksi']) // kolom yang berisi HTML
            ->make(true);
    }

    /* ----------  MODAL TAMBAH  ---------- */
    public function create()
    {
        return view('gedung.create');   // modal blade
    }

    public function store(Request $r)
    {
        $validator = Validator::make(
            $r->all(),
            [
                'kode_gedung' => 'required|max:10|unique:gedung,kode_gedung',
                'nama_gedung' => 'required|max:100',
            ],
            [
                'kode_gedung.unique' => 'Kode gedung sudah digunakan',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false, // response status, false: error/gagal, true: berhasil
                'message' => 'Terjadi kesalahan',
                'msgField' => $validator->errors(), // pesan error validasi
            ]);
        }

        Gedung::create($r->only('kode_gedung', 'nama_gedung'));
        return response()->json([
            'status' => true,
            'message' => 'Gedung ditambahkan'
        ]);
    }

    /* ----------  MODAL EDIT / UPDATE  ---------- */
    public function edit(Gedung $gedung)
    {
        return view('gedung.edit', compact('gedung'));
    }

    public function update(Request $r, Gedung $gedung)
    {
        $validator = Validator::make(
            $r->all(),
            [
                'kode_gedung' => [
                    'required',
                    'max:10',
                    Rule::unique('gedung', 'kode_gedung')->ignore($gedung->id_gedung, 'id_gedung'),
                ],
                'nama_gedung' => 'required|max:100',
            ],
            [
                'kode_gedung.unique' => 'Kode gedung sudah digunakan',
            ]
            );

        if ($validator->fails()) {
            return response()->json([
                'status' => false, // response status, false: error/gagal, true: berhasil
                'message' => 'Terjadi kesalahan',
                'msgField' => $validator->errors(), // pesan error validasi
            ]);
        }

        $gedung->update($r->only('kode_gedung', 'nama_gedung'));
        return response()->json(['status' => true, 'message' => 'Gedung diperbarui']);
    }

    /* ----------  MODAL HAPUS / DESTROY  ---------- */
    public function delete(Gedung $gedung)
    {
        return view('gedung.delete', compact('gedung'));
    }

    public function destroy(Gedung $gedung)
    {
        $gedung->delete();
        return response()->json(['status' => true, 'message' => 'Gedung dihapus']);
    }

    /* ----------  MODAL DETAIL  ---------- */
    public function show(Gedung $gedung)
    {
        return view('gedung.show', compact('gedung'));
    }


    public function import()
    {
        return view('gedung.import');
    }

    public function importAjax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_gedung' => ['required', 'mimes:xlsx', 'max:2048'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $file = $request->file('file_gedung');
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
                    'kode_gedung' => $row['A'],
                    'nama_gedung' => $row['B'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (count($insert) === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }

            Gedung::insertOrIgnore($insert);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diimport'
            ]);
        }

        return redirect()->route('gedung.index');
    }


    /* ----------  EXPORT PDF  ---------- */
    public function exportPdf()
    {
        $gedung = Gedung::select('id_gedung', 'kode_gedung', 'nama_gedung')
            ->orderBy('kode_gedung')
            ->get();

        $pdf = PDF::loadView('gedung.export_pdf', compact('gedung'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Gedung_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
