<?php

namespace App\Http\Controllers;

use App\Models\Lantai;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Fasilitas;
use App\Models\Gedung;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class RuanganController extends Controller
{
    // 1️⃣ Index: daftar untuk satu lantai
    public function index(Lantai $lantai)
    {
        return view('ruangan.index', compact('lantai'));
    }

    // 2️⃣ AJAX list untuk DataTables
    public function list(Lantai $lantai)
    {
        $query = $lantai->ruangan()
            ->select(['id_ruangan', 'kode_ruangan', 'nama_ruangan']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                // URL detail fasilitas
                $urlFas  = route('ruangan.fasilitas.index', $row->id_ruangan);
                $urlEdit = route('ruangan.edit',           $row->id_ruangan);
                $urlDel  = route('ruangan.delete',         $row->id_ruangan);

                return <<<HTML
                <!-- Detail ↪ fasilitas -->
                <button onclick="window.location='$urlFas'" class="btn btn-success btn-sm m-1">
                <i class="mdi mdi-format-list-bulleted m-0"></i>
                </button>

                <!-- Edit -->
                <button onclick="modalAction('$urlEdit')" class="btn btn-warning btn-sm m-1">
                <i class="mdi mdi-pencil m-0"></i>
                </button>

                <!-- Delete -->
                <button onclick="modalAction('$urlDel')" class="btn btn-danger btn-sm m-1">
                <i class="mdi mdi-delete m-0"></i>
                </button>
                </button>
                HTML;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    // 3️⃣ Create form
    public function create(Lantai $lantai)
    {
        return view('ruangan.create', compact('lantai'));
    }

    // 4️⃣ Store new
    public function store(Request $r, Lantai $lantai)
    {
        $validator = Validator::make(
            $r->all(),
            [
                'kode_ruangan' => 'required|max:20|unique:ruangan,kode_ruangan',
                'nama_ruangan' => 'required|max:100',
            ],
            [
                'kode_ruangan.unique' => 'Kode ruangan sudah digunakan',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false, // response status, false: error/gagal, true: berhasil
                'message' => 'Terjadi kesalahan',
                'msgField' => $validator->errors(), // pesan error validasi
            ]);
        }

        $lantai->ruangan()->create($r->only('kode_ruangan', 'nama_ruangan'));

        return response()->json([
            'status' => true,
            'message' => 'Ruangan ditambahkan'
        ]);
    }

    // 5️⃣ Edit form
    public function edit(Ruangan $ruangan)
    {
        return view('ruangan.edit', compact('ruangan'));
    }

    // 6️⃣ Update existing
    public function update(Request $r, Ruangan $ruangan)
    {
        $validator = Validator::make(
            $r->all(),
            [
                'kode_ruangan' => [
                    'required',
                    'max:20',
                    Rule::unique('ruangan', 'kode_ruangan')
                        ->ignore($ruangan->id_ruangan, 'id_ruangan'),
                ],
                'nama_ruangan' => 'required|max:100',
            ],
            [
                'kode_ruangan.unique' => 'Kode ruangan sudah digunakan',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false, // response status, false: error/gagal, true: berhasil
                'message' => 'Terjadi kesalahan',
                'msgField' => $validator->errors(), // pesan error validasi
            ]);
        }

        $ruangan->update($r->only('kode_ruangan', 'nama_ruangan'));

        return response()->json(['status' => true, 'message' => 'Ruangan diperbarui']);
    }

    // 7️⃣ Delete confirmation form
    public function delete(Ruangan $ruangan)
    {
        return view('ruangan.delete', compact('ruangan'));
    }

    // 8️⃣ Destroy
    public function destroy(Ruangan $ruangan)
    {
        // 1) Hapus dulu semua fasilitas di ruangan ini
        $ruangan->fasilitas()->delete();

        // 2) Baru hapus ruangan-nya
        $ruangan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Ruangan dan fasilitasnya berhasil dihapus'
        ]);
    }

    // 9️⃣ Show detail
    public function show(Ruangan $ruangan)
    {
        return view('ruangan.show', compact('ruangan'));
    }

    public function fasilitas()
    {
        return $this->hasMany(Fasilitas::class, 'id_ruangan');
    }

    public function exportPdf(Lantai $lantai)
    {
        // Load relasi 'gedung' agar bisa digunakan di view
        $lantai->load('gedung');

        // Ambil semua ruangan yang punya id_lantai sama
        $ruangan = $lantai->ruangan()->orderBy('kode_ruangan')->get();

        // Kirim ke view
        $pdf = PDF::loadView('ruangan.export_pdf', compact('ruangan', 'lantai'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan_Ruangan_Lantai_' . $lantai->nomor_lantai . '_' . date('Y-m-d_H-i-s') . '.pdf');
    }


    public function import(Lantai $lantai)
    {
        return view('ruangan.import', compact('lantai'));
    }

    public function importAjax(Request $request, Lantai $lantai)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_ruangan' => ['required', 'mimes:xlsx', 'max:2048'],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $file = $request->file('file_ruangan');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $insert = [];
            foreach ($rows as $i => $row) {
                if ($i === 1) continue; // header
                if (empty($row['A']) && empty($row['B'])) continue;

                $insert[] = [
                    'id_lantai'    => $lantai->id_lantai,
                    'kode_ruangan' => $row['A'],
                    'nama_ruangan' => $row['B'],
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            if (count($insert) === 0) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }

            Ruangan::insertOrIgnore($insert);

            return response()->json([
                'status'  => true,
                'message' => 'Data ruangan berhasil diimport'
            ]);
        }

        return redirect()->route('lantai.ruangan.index', $lantai);
    }
}
