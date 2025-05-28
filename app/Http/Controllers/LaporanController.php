<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Fasilitas;
use App\Models\Gedung;
use App\Models\KategoriKerusakan;
use App\Models\Lantai;
use App\Models\Status;
use App\Models\Laporan;
use App\Models\Ruangan;
use App\Models\Kriteria;
use App\Models\Pengguna;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use App\Models\LaporanFasilitas;
use App\Models\PenilaianPengguna;
use Illuminate\Support\Facades\DB;
use App\Models\SkorKriteriaLaporan;
use App\Models\RiwayatLaporanFasilitas;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        return view('laporan.index', compact('activeMenu', 'breadcrumbs'));
    }

    public function list()
    {
        $data = Laporan::select('id_laporan', 'id_pengguna', 'id_gedung', 'id_lantai', 'id_ruangan', 'created_at')
            ->with(['pengguna','gedung','lantai','ruangan', 'laporanFasilitas'])
             ->whereHas('laporanFasilitas', function($query) {
            $query->where('id_status', '1');
        }, '=', DB::raw('(SELECT COUNT(*) FROM laporan_fasilitas WHERE laporan_fasilitas.id_laporan = laporan.id_laporan)'))
        ->whereDoesntHave('laporanFasilitas', function($query) {
            $query->where('id_status', '!=', '1');
        });

        return DataTables::of($data)
            ->addIndexColumn()

            // format tanggal
            ->editColumn('created_at', fn($row) => Carbon::parse($row->created_at)->format('d-m-Y'))

            ->addColumn('aksi', function($row) {

            $btns = '<div class="btn-group">';
                $btns .= '<button onclick="modalAction(\''
                      . url("/laporan/{$row->id_laporan}/verifikasi")
                      . '\')"
                      class="btn btn-success btn-sm" title="Verifikasi">
                        <i class="mdi mdi-checkbox-multiple-marked"></i>
                      </button>';

            // $btns .= '<button onclick="modalAction(\'' . url('/laporan/edit/' . $row->id_laporan) . '\')" type="button" class="btn btn-warning btn-sm btn-edit">
            //             <i class="mdi mdi-pencil"></i>
            //         </button>';

            // // dll. tombol edit/hapus sesuai kebutuhan
            // $btns .= '<button onclick="modalAction(\'' . url('/laporan/show/' . $row->id_laporan) . '\')" type="button" class="btn btn-info btn-sm btn-edit">
            //             <i class="mdi mdi-file-document-box"></i>
            //         </button>';

            // $btns .= '<button onclick="modalAction(\'' . url('/laporan/delete/' . $row->id_laporan) . '\')" type="button" class="btn btn-danger btn-sm btn-delete" data-id="' . $row->id_laporan . '">
            //             <i class="mdi mdi-delete"></i>
            //         </button>';

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
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Halaman Laporan', 'url' => route('laporan.index')],
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
            'breadcrumbs' => $breadcrumbs
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
                'redirect' => url('/laporan')
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
            'id_laporan'                 => ['required','exists:laporan,id_laporan'],
            'details'                    => ['required','array'],
            'details.*.id'               => ['required','integer','exists:laporan_fasilitas,id_laporan_fasilitas'],
            'details.*.verif_status'    => ['required', Rule::in([
                Status::VALID,
                Status::TIDAK_VALID,
                Status::DITOLAK,
            ])],
            'details.*.catatan'          => ['nullable','string','max:500'],
            'details.*.skor'             => ['nullable','array'],
            'details.*.skor.*'          => ['nullable','integer','min:1','max:5'],
        ]);

        $headerId = $r->id_laporan;

        foreach ($r->details as $det) {
            $lf = LaporanFasilitas::findOrFail($det['id']);

            // 1) Buat riwayat
            RiwayatLaporanFasilitas::create([
                'id_laporan_fasilitas' => $lf->id_laporan_fasilitas,
                'id_status'            => $det['verif_status'],
                'id_pengguna'          => auth()->id(),
                'catatan'              => $det['catatan'] ?? '',
            ]);

            $lf->id_status = $det['verif_status'];
            $lf->save();

            if ($det['verif_status'] == Status::VALID) {
                $pen = Penilaian::create([
                    'id_laporan_fasilitas'=> $lf->id_laporan_fasilitas,
                    'id_pengguna'         => auth()->id(),
                    'dinilai_pada'        => now(),
                ]);

                foreach ($det['skor'] ?? [] as $kode => $val) {
                    if ($val !== null &&
                        $k = Kriteria::where('kode_kriteria', $kode)->first()
                    ) {
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


    // /**
    //  * Display the specified resource.
    //  */
    // public function show($id)
    // {
    //     $laporan = Laporan::find($id);
    //     $laporanFasilitas = LaporanFasilitas::with([
    //         'fasilitas',
    //         'kategoriKerusakan',
    //         'status',
    //         'riwayatLaporanFasilitas',
    //         'penugasan',
    //         'penilaian',
    //         'skorTopsis'
    //     ])->where('id_laporan', $id)->get();
    //     return view('laporan.show', [
    //         'laporan' => $laporan,
    //         'laporanFasilitas' => $laporanFasilitas
    //     ],);
    // }

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
