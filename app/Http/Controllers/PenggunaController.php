<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\GuestCountManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Services\NoIndukVerifierService;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Validation\ValidationException;


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
        $currentId = Auth::id();

        $query = Pengguna::select('id_pengguna','username','nama','id_peran')
                 ->with('peran:id_peran,nama_peran');

        // filter by role_id jika dikirim
        if ($request->filled('role_id')) {
            $query->where('id_peran', $request->role_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) use ($currentId) {
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

                $deleteBtn = '';
                if ($row->id_pengguna !== $currentId) {
                    $deleteBtn = '<button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="modalAction(\''.route('pengguna.delete', $row->id_pengguna).'\')">
                                        <i class="mdi mdi-delete"></i>
                                    </button>';
                }

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
        // 1) Validasi input
        $validator = Validator::make($request->all(), [
            'no_induk'  => 'required|string|max:20|unique:pengguna,no_induk',
            'username'  => 'required|string|min:4|max:50|unique:pengguna,username',
            'nama'      => 'required|string|min:3|max:255',
            'password'  => 'required|string|min:5',
            'id_peran'  => 'required|exists:peran,id_peran',
        ], [
            'id_peran.required' => 'Peran harus dipilih',
            'id_peran.exists'   => 'Peran tidak valid',
            'no_induk.required'  => 'Nomor induk wajib diisi',
            'no_induk.unique'    => 'Nomor induk sudah terdaftar',
            'username.required'  => 'Username wajib diisi',
            'username.unique'    => 'Username sudah digunakan',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal, periksa input Anda.',
                'msgField' => $validator->errors(),
            ], 422);
        }

        // 2) Verifikasi format no_induk
        try {
            $ver = $this->noIndukVerifier->verify($request->no_induk);
            if (in_array($ver['type'], ['Tidak Valid','Tidak Diketahui']) || !empty($ver['errors'])) {
                throw ValidationException::withMessages([
                    'no_induk' => ['Nomor induk tidak valid']
                ]);
            }
        } catch (ValidationException $ex) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal, periksa input Anda.',
                'msgField' => $ex->errors(),
            ], 422);
        }

        // 3) Simpan ke DB dengan penanganan exception
        try {
            Pengguna::create([
                'no_induk'  => $request->no_induk,
                'username'  => $request->username,
                'nama'      => $request->nama,
                'password'  => $request->password, // cast hashed di model
                'id_peran'  => $request->id_peran,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Pengguna berhasil ditambahkan.',
            ]);
        } catch (QueryException $qe) {
            // kemungkinan duplikasi di DB
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan: data sudah ada atau duplikat.',
            ], 409);
        } catch (\Exception $e) {
            // error umum
            // \Log::error($e); // opsional: log untuk debugging
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan pada server, silakan coba lagi.',
            ], 500);
        }
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
        // 1) Validasi input
        $rules = [
            'no_induk' => 'required|string|max:20|unique:pengguna,no_induk,'.$id.',id_pengguna',
            'username' => 'required|string|min:4|max:50|unique:pengguna,username,'.$id.',id_pengguna',
            'nama'     => 'required|string|min:3|max:255',
            'password' => 'nullable|string|min:5',
            'id_peran' => 'required|exists:peran,id_peran',
        ];
        $validator = Validator::make($request->all(), $rules, [
            'id_peran.required' => 'Peran harus dipilih',
            'id_peran.exists'   => 'Peran tidak valid',
            'no_induk.required'  => 'Nomor induk wajib diisi',
            'no_induk.unique'    => 'Nomor induk sudah terdaftar',
            'username.required'  => 'Username wajib diisi',
            'username.unique'    => 'Username sudah digunakan',
            'nama.required'      => 'Nama wajib diisi',
            'id_peran.required'  => 'Peran harus dipilih',
            'id_peran.exists'    => 'Peran tidak valid',
            'password.min'       => 'Password minimal 5 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal, periksa input Anda.',
                'msgField' => $validator->errors(),
            ], 422);
        }

        // 2) Verifikasi no_induk
        try {
            $ver = $this->noIndukVerifier->verify($request->no_induk);
            if (in_array($ver['type'], ['Tidak Valid','Tidak Diketahui']) || !empty($ver['errors'])) {
                throw ValidationException::withMessages([
                    'no_induk' => ['Nomor induk tidak valid']
                ]);
            }
        } catch (ValidationException $ex) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal, periksa input Anda.',
                'msgField' => $ex->errors(),
            ], 422);
        }

        // 3) Update record
        $pengguna = Pengguna::find($id);
        if (! $pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Data pengguna tidak ditemukan.',
            ], 404);
        }

        $data = $request->only(['no_induk','username','nama','id_peran']);
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        try {
            $pengguna->update($data);

            // Jika admin sedang mengubah perannya sendiri, minta relogin
            if (Auth::id() == $id && $request->id_peran != Peran::where('nama_peran','ADM')->value('id_peran')) {
                Auth::logout();
                return response()->json([
                    'status'   => true,
                    'redirect' => route('login'),
                    'message'  => 'Peran Anda berubah, silakan login ulang.',
                ]);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diupdate.',
            ]);
        } catch (QueryException $qe) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memperbarui: data sudah ada atau duplikat.',
            ], 409);
        } catch (\Exception $e) {
            // \Log::error($e);
            return response()->json([
                'status'  => false,
                'message' => 'Terjadi kesalahan pada server, silakan coba lagi.',
            ], 500);
        }
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
        if ($id == Auth::id()) {
            return response()->json([
                'status'  => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ], 403);
        }

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
