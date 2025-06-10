<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Services\NoIndukVerifierService;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Validation\ValidationException;
use App\Helpers\GuestCountManager;


class PenggunaController extends Controller
{
    protected $noIndukVerifier;

    public function __construct(NoIndukVerifierService $noIndukVerifier)
    {
        $this->noIndukVerifier = $noIndukVerifier;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Halaman Pengguna', 'url' => route('pengguna.index')]
        ];

        $activeMenu = 'pengguna';
        $peran = Peran::select('id_peran','nama_peran')->get();
        return view('pengguna.index', compact('activeMenu', 'breadcrumbs', 'peran'));
    }

    public function list(Request $request)
    {
        $query = Pengguna::select('id_pengguna','username','nama','id_peran')
                 ->with('peran:id_peran,nama_peran');

        // filter by role_id jika dikirim
        if ($request->filled('role_id')) {
            $query->where('id_peran', $request->role_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $editBtn = '<button type="button"
                                class="btn btn-sm btn-outline-warning"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                                style="margin-right: 8px;"
                                onclick="modalAction(\'' . route('pengguna.edit', $row->id_pengguna) . '\')">
                                <i class="mdi mdi-pencil"></i>
                            </button>';

                $showBtn = '<button type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail"
                                style="margin-right: 8px;"
                                onclick="modalAction(\'' . route('pengguna.show', $row->id_pengguna) . '\')">
                                <i class="mdi mdi-file-document-box"></i>
                            </button>';

                $deleteBtn = '<button type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"
                                onclick="modalAction(\'' . route('pengguna.delete', $row->id_pengguna) . '\')">
                                <i class="mdi mdi-delete"></i>
                            </button>';

                return '<div class="d-flex">' . $editBtn . $showBtn . $deleteBtn . '</div>';
            })
            ->addColumn('peran_nama', function ($row) {
                return $row->peran->nama_peran;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function guestCountStream(Request $request)
    {
        // Supaya skrip tidak timeout
        set_time_limit(0);

        // Ambil nilai terakhir dari query param
        $lastCount = intval($request->query('lastCount', 0));
        $startTime = time();

        do {
            // Hitung ulang jumlah Guest
            $count = Pengguna::whereHas('peran', function($q) {
                $q->where('nama_peran', 'Guest');
            })->count();

            // Begitu berubah, langsung kirim response
            if ($count !== $lastCount) {
                return response()->json(['guestCount' => $count]);
            }

            // Delay sebelum cek lagi (2 detik)
            sleep(2);

        // Loop hingga 30 detik berlalu
        } while (time() - $startTime < 30);

        // Jika timeout, kembalikan nilai yang sama agar client rekursif tanpa update UI
        return response()->json(['guestCount' => $lastCount]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $peran = Peran::select('id_peran','nama_peran')->get();

        return view('pengguna.create', compact('peran'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // aturan validasi
         $validator = Validator::make($request->all(), [
            'no_induk' => 'required|string|max:20|unique:pengguna,no_induk',
            'username'   => 'required|string|min:4|max:50|unique:pengguna,username',
            'nama'       => 'required|string|min:3|max:255',
            'password'   => 'required|string|min:5',
            'id_peran'   => 'required|exists:peran,id_peran',
        ], [
            'id_peran.required' => 'Peran harus dipilih',
            'id_peran.exists'   => 'Peran tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'    => 'Validasi gagal, cek input Anda',
                'msgField'   => $validator->errors(),
            ]);
        }

        try {
            $ver = $this->noIndukVerifier->verify($request->input('no_induk'));

            if (
                $ver['type'] === 'Tidak Valid' ||
                $ver['type'] === 'Tidak Diketahui' ||
                !empty($ver['errors'])
            ) {
                throw ValidationException::withMessages([
                    'no_induk' => ['Nomor induk tidak valid']
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal, cek input Anda',
                'msgField' => $e->errors(),
            ], 422);
        }

        // simpan pengguna baru
        Pengguna::create([
            'no_induk'   => $request->no_induk,
            'username'   => $request->username,
            'nama'       => $request->nama,
            'password'   => $request->password, // di-hash via cast di model
            'id_peran'   => $request->id_peran,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Pengguna berhasil ditambahkan',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         // Cari pengguna beserta perannya
            $pengguna = Pengguna::with('peran')
            ->findOrFail($id);

        // Tampilkan partial view untuk modal
        return view('pengguna.show', compact('pengguna'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, NoIndukVerifierService $verifier)
    {
        $pengguna = Pengguna::findOrFail($id);
        $peran    = Peran::select('id_peran','nama_peran')->get();
        $verificationInfo = $verifier->verify($pengguna->no_induk);
        return view('pengguna.edit', compact('pengguna','peran', 'verificationInfo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'no_induk' => ['required', 'string', 'max:20', 'unique:pengguna,no_induk,' . $id . ',id_pengguna'],
                'id_peran' => ['required', 'integer'],
                'username' => ['required', 'max:20', 'unique:pengguna,username,' . $id . ',id_pengguna'],
                'nama' => ['required', 'max:100'],
                'password' => ['nullable', 'min:6', 'max:20'],
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
            $ver = $this->noIndukVerifier->verify($request->input('no_induk'));

            if (
                $ver['type'] === 'Tidak Valid' ||
                $ver['type'] === 'Tidak Diketahui' ||
                !empty($ver['errors'])
            ) {
                throw ValidationException::withMessages([
                    'no_induk' => ['Nomor induk tidak valid']
                ]);
            }
        } catch (ValidationException $e) {
            // Kembalikan JSON agar JS dapat menampilkannya di form
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal.',
                'msgField' => $e->errors(),
            ], 422);
        }

            $check = Pengguna::find($id);
            if ($check) {
                $data = $request->only(['no_induk', 'username','nama','id_peran']);

                if ($request->filled('password')) {
                    $data['password'] = $request->password;
                }

                $check->update($data);
                if (Auth::id() == $id && $request->id_peran != Peran::where('nama_peran','ADM')->value('id_peran')) {
                    Auth::logout(); // invalidasi
                    return response()->json([
                        'status' => true,
                        'redirect' => route('login'),
                        'message' => 'Peran Anda diubah. Silakan login ulang.'
                    ]);
                }
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
        return redirect('/');
    }

    /**
     * Show delete confirmation dialog.
     */
    public function confirm($id)
    {
        $pengguna = Pengguna::findOrFail($id);
        return view('pengguna.delete', compact('pengguna'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Pengguna::destroy($id);
        return response()->json([
            'status'  => true,
            'message' => 'Pengguna berhasil dihapus',
        ]);
    }

     /**
     * Tampilkan form modal import
     */
    public function import()
    {
        return view('pengguna.import');
    }

    /**
     * Proses import via AJAX
     */
    public function importAjax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_user' => ['required', 'mimes:xlsx', 'max:2048'],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $file = $request->file('file_user');
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
                    'id_peran'   => $row['A'],
                    'no_induk'   => $row['B'],
                    'username'   => $row['C'],
                    'nama'       => $row['D'],
                    'password'   => Hash::make($row['E']),
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

            // Gunakan insertOrIgnore agar tidak error bila duplikat
            Pengguna::insertOrIgnore($insert);

            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diimport'
            ]);
        }

        return redirect()->route('pengguna.index');
    }

    /**
     * Export data pengguna ke Excel (.xlsx)
     */
    public function exportExcel()
    {
        // ambil data
        $users = Pengguna::with('peran:id_peran,nama_peran')
            ->select('id_pengguna', 'no_induk', 'username', 'nama', 'id_peran')
            ->orderBy('id_peran')
            ->orderBy('username')
            ->get();

        // buat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'No Induk');
        $sheet->setCellValue('C1', 'Username');
        $sheet->setCellValue('D1', 'Nama Pengguna');
        $sheet->setCellValue('E1', 'Peran');

        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Isi baris
        $rowNum = 2;
        foreach ($users as $idx => $u) {
            $sheet->setCellValue('A'.$rowNum, $idx + 1);
            $sheet->setCellValue('B'.$rowNum, $u->no_induk);
            $sheet->setCellValue('C'.$rowNum, $u->username);
            $sheet->setCellValue('D'.$rowNum, $u->nama);
            $sheet->setCellValue('E'.$rowNum, $u->peran->nama_peran ?? '-');
            $rowNum++;
        }

        // Autosize kolom
        foreach (range('A','E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Prepare download
        $filename = 'Pengguna_'.date('Y-m-d_H-i-s').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header(sprintf('Content-Disposition: attachment; filename="%s"', $filename));
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    /**
     * Export data pengguna ke PDF
     */
    public function exportPdf()
    {
        $users = Pengguna::with('peran:id_peran,nama_peran')
            ->select('id_pengguna', 'no_induk','username','nama','id_peran')
            ->orderBy('id_peran')
            ->orderBy('username')
            ->get();

        $pdf = Pdf::loadView('pengguna.export_pdf', compact('users'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Pengguna_'.date('Y-m-d_H-i-s').'.pdf');
    }
}
