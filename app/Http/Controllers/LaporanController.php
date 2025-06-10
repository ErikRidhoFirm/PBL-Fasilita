<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Gedung;
use App\Models\Lantai;
use App\Models\Status;
use App\Models\Laporan;
use App\Models\Ruangan;
use App\Models\Kriteria;
use App\Models\Pengguna;
use App\Models\Fasilitas;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\LaporanFasilitas;
use App\Models\KategoriKerusakan;
use App\Models\PenilaianPengguna;
use Illuminate\Support\Facades\DB;
use App\Models\SkorKriteriaLaporan;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatLaporanFasilitas;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Halaman Laporan', 'url' => route('laporan.index')]
        ];

        $activeMenu = 'laporan';
        $authUser = Auth::user();
        return view('laporan.index', compact('activeMenu', 'breadcrumbs', 'authUser'));
    }

    public function list()
    {
        $data = Laporan::select(
            'id_laporan',
            'id_pengguna',
            'id_gedung',
            'id_lantai',
            'id_ruangan',
            'created_at',
            DB::raw('
                (SELECT COUNT(DISTINCT plf.id_pengguna)
                FROM pelapor_laporan_fasilitas plf
                JOIN laporan_fasilitas lf ON plf.id_laporan_fasilitas = lf.id_laporan_fasilitas
                WHERE lf.id_laporan = laporan.id_laporan) as jumlah_vote'
            )
        )
        ->with(['pengguna', 'gedung', 'lantai', 'ruangan', 'laporanFasilitas'])
        ->whereHas('laporanFasilitas', function($query) {
            $query->where('id_status', '1');
        }, '=', DB::raw('(SELECT COUNT(*) FROM laporan_fasilitas WHERE laporan_fasilitas.id_laporan = laporan.id_laporan)'))
        ->whereDoesntHave('laporanFasilitas', function($query) {
            $query->where('id_status', '!=', '1');
        });

        return DataTables::of($data)
            ->addIndexColumn()
            // Format tanggal
            ->editColumn('created_at', fn($row) => Carbon::parse($row->created_at)->format('d-m-Y'))
            ->addColumn('aksi', function($row) {
                $btns = '<div class="btn-group">';
                $btns .= '<button onclick="modalAction(\''
                    . url("/laporan/{$row->id_laporan}/verifikasi")
                    . '\')"
                    class="btn btn-success btn-sm" title="Verifikasi">
                        <i class="mdi mdi-checkbox-multiple-marked"></i>
                    </button>';
                return $btns;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authUser = Auth::user();

        $halamanLaporan = in_array($authUser->peran->kode_peran, ['ADM', 'SPR', 'TNS'])
                            ? 'laporan.index'
                            : 'laporanPelapor.index';

        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Halaman Laporan', 'url' => route($halamanLaporan)],
            ['title' => 'Tambah Laporan', 'url' => route('laporan.create')]
        ];

        $activeMenu = 'laporan';

        $gedung = Gedung::all();
        $lantai = Lantai::all();
        $ruangan = Ruangan::all();
        $kategoriKerusakan = KategoriKerusakan::all();

        return view('laporan.create', [
            'gedung' => $gedung,
            'lantai' => $lantai,
            'ruangan' => $ruangan,
            'kategoriKerusakan' => $kategoriKerusakan,
            'activeMenu' => $activeMenu,
            'breadcrumbs' => $breadcrumbs,
            'authUser' => Auth::user(),
        ]);
    }

    public function getLantai($idGedung)
    {
        return response()->json(Lantai::where('id_gedung', $idGedung)->get());
    }

    public function getRuangan($idLantai)
    {
        return response()->json(Ruangan::where('id_lantai', $idLantai)->get());
    }

    public function getFasilitas($idRuangan)
    {
        return response()->json(Fasilitas::where('id_ruangan', $idRuangan)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pengguna' => 'required|integer',
            'id_gedung' => 'required|integer',
            'id_lantai' => 'required|integer',
            'id_ruangan' => 'required|integer',
            'id_fasilitas' => 'required|array',
            'id_fasilitas.*' => 'required|integer',
            'id_kategori_kerusakan' => 'required|array',
            'id_kategori_kerusakan.*' => 'required|integer',
            'jumlah_rusak' => 'required|array',
            'jumlah_rusak.*' => 'required|integer|min:1',
            'deskripsi' => 'required|array',
            'deskripsi.*' => 'required|string',
            'path_foto' => 'required|array',
            'path_foto.*' => 'required|file|image|max:2048', // max 2MB per file
        ]);

        try {

            // Simpan laporan utama
            $laporan = Laporan::create([
                'id_pengguna' => $request->id_pengguna,
                'id_gedung' => $request->id_gedung,
                'id_lantai' => $request->id_lantai,
                'id_ruangan' => $request->id_ruangan,
                'created_at' => now(),
            ]);

            // Simpan detail laporan
            foreach ($request->id_fasilitas as $index => $idFasilitas) {
                $foto = $request->file('path_foto')[$index];
                $fotoPath = $foto->store('uploads/laporan', 'public');

                LaporanFasilitas::create([
                    'id_laporan' => $laporan->id_laporan,
                    'id_fasilitas' => $idFasilitas,
                    'id_kategori_kerusakan' => $request->id_kategori_kerusakan[$index],
                    'id_status' => 1,
                    'jumlah_rusak' => $request->jumlah_rusak[$index],
                    'deskripsi' => $request->deskripsi[$index],
                    'path_foto' => $fotoPath,
                    'is_active' => 1,
                    'created_at' => now(),
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data laporan berhasil disimpan',
                'redirect' => url('/')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data laporan gagal disimpan: ' . $e->getMessage()
            ]);
        }
    }

    public function formByLaporan($id_laporan)
    {
        $laporan = Laporan::with(['laporanFasilitas.fasilitas','laporanFasilitas.kategoriKerusakan'])
                          ->findOrFail($id_laporan);
         $kriterias = Kriteria::with(['skoringKriterias' /* sesuaikan nama relasi di model */])
                         ->orderBy('kode_kriteria')
                         ->get();

    return view('laporan.verifikasi_by_laporan', compact('laporan','kriterias'));
    }

    public function storeByLaporan(Request $r)
{
    $r->validate([
        'id_laporan'              => ['required','exists:laporan,id_laporan'],
        'details'                 => ['required','array'],
        'details.*.id'            => ['required','integer','exists:laporan_fasilitas,id_laporan_fasilitas'],
        'details.*.verif_status'  => ['required', Rule::in([
            Status::VALID,
            Status::TIDAK_VALID,
            Status::DITOLAK,
        ])],
        'details.*.catatan'       => ['nullable','string','max:500'],
        'details.*.skor'          => ['nullable','array'],
        'details.*.skor.*'        => ['nullable','integer','min:1','max:5'],
    ]);

    foreach ($r->details as $det) {
        /** @var LaporanFasilitas $lf */
        $lf = LaporanFasilitas::findOrFail($det['id']);

        // 1) Buat entri riwayat dengan status baru
        RiwayatLaporanFasilitas::create([
            'id_laporan_fasilitas' => $lf->id_laporan_fasilitas,
            'id_status'            => $det['verif_status'],
            'id_pengguna'          => auth()->id(),
            'catatan'              => $det['catatan'] ?? '',
        ]);
        // —–––––––
        // _Observer_ akan otomatis membuat notifikasi di sini

        // 2) Update kolom status di LaporanFasilitas
        $lf->update([ 'id_status' => $det['verif_status'] ]);

        // 3) Jika status VALID, buat penilaian + skor
        if ($det['verif_status'] == Status::VALID) {
            $pen = Penilaian::create([
                'id_laporan_fasilitas'=> $lf->id_laporan_fasilitas,
                'id_pengguna'         => auth()->id(),
                'dinilai_pada'        => now(),
            ]);
            foreach ($det['skor'] ?? [] as $kode => $val) {
                if ($val !== null && $k = Kriteria::where('kode_kriteria', $kode)->first()) {
                    SkorKriteriaLaporan::create([
                        'id_penilaian' => $pen->id_penilaian,
                        'id_kriteria'  => $k->id_kriteria,
                        'nilai_mentah' => $val,
                    ]);
                }
            }
        }
    }

    return response()->json([
        'status'  => true,
        'message' => 'Semua detail laporan berhasil diverifikasi.',
    ]);
}


    public function indexPelapor()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Halaman Laporan', 'url' => route('laporanPelapor.index')]
        ];

        $activeMenu = 'laporan';
        return view('laporanPelapor.index', compact('activeMenu', 'breadcrumbs'));
    }

    // RiwayatLaporanFasilitasController.php (atau controller yang kamu gunakan)
    public function listPelapor(Request $request)
    {
        // ID user login
        $userId = $request->user()->id_pengguna;

        // Query dasar: hanya LaporanFasilitas yang id_status = 1
        $query = LaporanFasilitas::with([
            'fasilitas',
            'laporan.gedung',
            'laporan.lantai',
            'laporan.ruangan',
        ])->where('id_status', 1);

        // Misalnya 8 kartu per halaman
        $perPage = 8;
        $page    = $request->get('page', 1);

        $paginated = $query->orderBy('id_laporan_fasilitas', 'desc')
                           ->paginate($perPage, ['*'], 'page', $page);

        // Mapping hasil paginate ke format JSON yang di‐inginkan
        $data = $paginated->getCollection()->map(function ($lf) use ($userId) {
            return [
                'id_laporan_fasilitas' => $lf->id_laporan_fasilitas,
                'nama_fasilitas'       => optional($lf->fasilitas)->nama_fasilitas,
                'path_foto'            => $lf->path_foto,
                'nama_gedung'          => optional($lf->laporan->gedung)->nama_gedung,
                'nomor_lantai'         => optional($lf->laporan->lantai)->nomor_lantai,
                'nama_ruangan'         => optional($lf->laporan->ruangan)->nama_ruangan,
                'votes_count'          => $lf->pelaporLaporanFasilitas()->count(),
                'voted_by_me'          => $lf->pelaporLaporanFasilitas()
                                            ->where('id_pengguna', $userId)
                                            ->exists(),
            ];
        });

        // Gantikan koleksi paginate ke array data
        $paginated->setCollection($data);

        return response()->json($paginated);
    }


    // /**
    //  * Display the specified resource.
    //  */
    public function show($id)
    {
        $laporanFasilitas = LaporanFasilitas::with([
            'laporan.gedung',
            'laporan.lantai',
            'laporan.ruangan',
            'laporan.pengguna',
            'fasilitas',
            'kategoriKerusakan',
            'status',
        ])->findOrFail($id);

        return view('laporanPelapor.show', [
            'laporanFasilitas' => $laporanFasilitas
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     $laporan = Laporan::find($id);

    //     return view('laporan.edit', [
    //         'laporan' => $laporan
    //     ]);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, $id)
    // {
    //     // cek apakah request dari ajax
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $rules = [
    //             'kode_peran' => 'required|string|min:3|unique:peran,kode_peran,' . $id . ',id_peran',
    //             'nama_peran' => 'required|string|max:100',
    //         ];
    //         // use Illuminate\Support\Facades\Validator;
    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status'   => false,    // respon json, true: berhasil, false: gagal
    //                 'message'  => 'Validasi gagal.',
    //                 'msgField' => $validator->errors()  // menunjukkan field mana yang error
    //             ]);
    //         }

    //         $check = Laporan::find($id);
    //         if ($check) {
    //             $check->update($request->all());
    //             return response()->json([
    //                 'status'  => true,
    //                 'message' => 'Data berhasil diupdate'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'Data tidak ditemukan'
    //             ]);
    //         }
    //     }
    //     redirect('/');
    // }

    // /**
    //  * Show data for confirmation before deletion.
    //  */
    // public function delete(string $id)
    // {
    //     $peran = Laporan::find($id);
    //     return view('peran.delete', [
    //         'peran' => $peran
    //     ]);
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Request $request, $id)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $peran = Laporan::find($id);
    //         if ($peran) {
    //             $peran->delete();
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'Data berhasil dihapus'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Data tidak ditemukan'
    //             ]);
    //         }
    //     }
    //     redirect('/');
    // }
}
