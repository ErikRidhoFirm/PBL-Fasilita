<?php

namespace App\Http\Controllers;

use App\Models\KategoriKerusakan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class KategoriKerusakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Halaman Kategori Kerusakan', 'url' => route('kategori_kerusakan.index')]
        ];

        $activeMenu = 'kategoriKerusakan';
        return view('kategori_kerusakan.index', compact('activeMenu', 'breadcrumbs'));
    }
//id_kerusakan
    public function list()
{
    $data = KategoriKerusakan::all();

    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function ($row) {
            $editBtn = '<button type="button"
                            class="btn btn-warning btn-sm btn-edit d-inline-flex align-items-center justify-content-center"
                            style="margin-right: 8px;"
                            onclick="modalAction(\'' . url('kategori_kerusakan/edit/' . $row->id_kategori_kerusakan) . '\')">
                            <i class="mdi mdi-pencil m-0"></i>
                        </button>';

            $showBtn = '<button type="button"
                            class="btn btn-info btn-sm btn-show d-inline-flex align-items-center justify-content-center"
                            style="margin-right: 8px;"
                            onclick="modalAction(\'' . url('kategori_kerusakan/show/' . $row->id_kategori_kerusakan) . '\')">
                            <i class="mdi mdi-file-document-box m-0"></i>
                        </button>';

            $deleteBtn = '<button type="button"
                            class="btn btn-danger btn-sm btn-delete d-inline-flex align-items-center justify-content-center"
                            data-id="' . $row->id_kategori_kerusakan . '"
                            onclick="modalAction(\'' . url('kategori_kerusakan/delete/' . $row->id_kategori_kerusakan) . '\')">
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
        return view('kategori_kerusakan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        // 1) Validasi prefix (harus huruf, tanpa strip)
        $rules = [
            'prefix'          => ['required', 'string', 'max:10', 'regex:/^[A-Za-z]+$/'],
            'nama_kerusakan'  => 'required|string|max:100',
        ];
        $messages = [
            'prefix.regex' => 'Hanya boleh berisi huruf (tanpa angka atau tanda “-”).',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        // 2) Ambil prefix murni (buang apa pun di belakang strip jika ada)
        $raw = strtoupper($request->input('prefix'));
        $parts = explode('-', $raw);
        $prefix = $parts[0]; // misal “HW” dari “HW-001” atau “HW”

        // 3) Cari kode terakhir yang benar-benar pakai prefix itu
        $last = KategoriKerusakan::where('kode_kerusakan', 'like', $prefix . '-%')
            ->orderBy('kode_kerusakan', 'desc')
            ->first();

        $lastNumber = 0;
        if ($last) {
            // Ambil angka setelah strip terakhir
            $lastNumber = (int) Str::afterLast($last->kode_kerusakan, '-');
        }

        $nextNumber    = $lastNumber + 1;
        $numberPadded  = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $fullCode      = $prefix . '-' . $numberPadded;

        // 4) Simpan
        $kategori = KategoriKerusakan::create([
            'kode_kerusakan' => $fullCode,
            'nama_kerusakan' => $request->input('nama_kerusakan'),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Data berhasil disimpan dengan kode: ' . $fullCode,
            'data'    => $kategori,
        ]);
    }

    return redirect('/');
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kategoriKerusakan = KategoriKerusakan::find($id);
        return view('kategori_kerusakan.show',[
            'kategoriKerusakan' => $kategoriKerusakan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategoriKerusakan = kategoriKerusakan::find($id);
        return view('kategori_kerusakan.edit',[
            'kategoriKerusakan' => $kategoriKerusakan
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
                'kode_kerusakan' => 'required|string|min:3|unique:kategori_kerusakan,kode_kerusakan,'.$id.',id_kategori_kerusakan',
                'nama_kerusakan' => 'required|string|max:100',
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules,[
                'kode_kerusakan.unique' => 'Kode kerusakan sudah digunakan',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,    // respon json, true: berhasil, false: gagal
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error
                ]);
            }

            $check = kategoriKerusakan::find($id);
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
        $kategoriKerusakan = kategoriKerusakan::find($id);
        return view('kategori_kerusakan.delete',[
            'kategoriKerusakan' => $kategoriKerusakan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kategoriKerusakan = kategoriKerusakan::find($id);
            if ($kategoriKerusakan) {
                $kategoriKerusakan->delete();
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

    public function exportPdf()
    {
        $kategoriKerusakan = KategoriKerusakan::select('id_kategori_kerusakan', 'kode_kerusakan', 'nama_kerusakan')
            ->orderBy('kode_kerusakan')
            ->get();

        $pdf = PDF::loadView('kategori_kerusakan.export_pdf', compact('kategoriKerusakan'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Kategori_Kerusakan_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function import()
    {
        return view('kategori_kerusakan.import');
    }

    public function importAjax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_kategori_kerusakan' => ['required', 'mimes:xlsx', 'max:2048'],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $file = $request->file('file_kategori_kerusakan');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $insert = [];
            foreach ($rows as $i => $row) {
                if ($i === 1) continue; // Skip header
                if (empty($row['A']) && empty($row['B'])) continue;
                $insert[] = [
                    'kode_kerusakan' => $row['A'],
                    'nama_kerusakan' => $row['B'],
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

            KategoriKerusakan::insertOrIgnore($insert);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diimport'
            ]);
        }

        return redirect()->route('kategori_kerusakan.index');
    }

}
