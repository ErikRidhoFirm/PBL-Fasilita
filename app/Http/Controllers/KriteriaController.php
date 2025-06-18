<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Kriteria', 'url' => route('kriteria.index')],
        ];
        $activeMenu = 'kriteria';

        // eager load skoring for each kriteria
        $kriteria = Kriteria::with('skoringKriterias')->orderBy('kode_kriteria')->get();

        return view('kriteria.index', compact('activeMenu', 'breadcrumbs', 'kriteria'));
    }

    public function list()
    {
        $query = Kriteria::select('id_kriteria', 'kode_kriteria', 'nama_kriteria', 'bobot_kriteria', 'tipe_kriteria');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn(
                'aksi',
                fn($kriteria) =>
                '<button onclick="modalAction(\'' . route("kriteria.show", $kriteria->id_kriteria) . '\')" class="btn btn-info btn-sm"><i class="mdi mdi-file-document-box"></i></button> '
                    .  '<button onclick="modalAction(\'' . route("kriteria.edit", $kriteria->id_kriteria) . '\')" class="btn btn-warning btn-sm"><i class="mdi mdi-pencil"></i></button> '
                    . '<button onclick="modalAction(\'' . route("kriteria.confirm", $kriteria->id_kriteria) . '\')" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>'
            )
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for displaying the specified resource.
     */
    public function show($id)
    {
        $kriteria = Kriteria::find($id);
        return view('kriteria.show', compact('kriteria'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kriteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $validator = Validator::make(
            $request->all(),
            [
                'kode_kriteria'  => 'required|string|max:10|unique:kriteria,kode_kriteria',
                'nama_kriteria'  => 'required|string|max:100',
                'bobot_kriteria' => 'required|numeric|min:0.01|max:1.00',
                'tipe_kriteria'  => 'required|in:benefit,cost',
                'deskripsi'      => 'nullable|string',
            ],
            [
                'kode_kriteria.unique' => 'Kode kriteria sudah digunakan.',
                'bobot_kriteria.max'   => 'Bobot kriteria tidak boleh lebih dari 1.00',
                'bobot_kriteria.min'   => 'Bobot kriteria tidak boleh kurang dari 0.01',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        try {
            // Simpan data baru
            $baru = Kriteria::create($request->only([
                'kode_kriteria', 'nama_kriteria', 'bobot_kriteria', 'tipe_kriteria', 'deskripsi'
            ]));

            // Ambil semua kriteria termasuk yang baru
            $kriterias = Kriteria::all();
            $totalBobot = $kriterias->sum('bobot_kriteria');

            if ($totalBobot > 0) {
                foreach ($kriterias as $k) {
                    $bobotBaru = ($k->bobot_kriteria / $totalBobot) * 1.00;
                    $k->bobot_kriteria = round($bobotBaru, 4);
                    $k->save();
                }
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Kriteria berhasil ditambahkan dan bobot disesuaikan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan kriteria: ' . $e->getMessage()
            ]);
        }
    }
    return redirect('/');
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kriteria = Kriteria::find($id);
        return view('kriteria.edit', compact('kriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'kode_kriteria'  => 'required|string|max:10|unique:kriteria,kode_kriteria,' . $id . ',id_kriteria',
                'nama_kriteria'  => 'required|string|max:100',
                'bobot_kriteria' => 'required|numeric|min:0.01|max:1.00',
                'tipe_kriteria'  => 'required|in:benefit,cost',
                'deskripsi'      => 'nullable|string',
            ], [
                'kode_kriteria.unique' => 'Kode kriteria sudah digunakan.',
                'bobot_kriteria.max'   => 'Bobot kriteria tidak boleh lebih dari 1.00',
                'bobot_kriteria.min'   => 'Bobot kriteria tidak boleh kurang dari 0.01',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $newBobot = (float) $request->bobot_kriteria;

            DB::beginTransaction();
            try {
                // Ambil semua kriteria selain yang sedang diupdate
                $kriteriaLain = Kriteria::where('id_kriteria', '!=', $id)->get();
                $totalBobotLain = $kriteriaLain->sum('bobot_kriteria');

                $sisaBobot = 1.00 - $newBobot;

                if ($kriteriaLain->count() > 0) {
                    if ($sisaBobot <= 0) {
                        // Jika bobot baru sudah >= 1.00, maka lainnya semua jadi 0
                        foreach ($kriteriaLain as $k) {
                            $k->bobot_kriteria = 0;
                            $k->save();
                        }
                    } elseif ($totalBobotLain == 0) {
                        // Jika total bobot lain 0 (semua 0 sebelumnya), bagi rata saja
                        $rata = $sisaBobot / $kriteriaLain->count();
                        foreach ($kriteriaLain as $k) {
                            $k->bobot_kriteria = round($rata, 4);
                            $k->save();
                        }
                    } else {
                        // Sesuaikan proporsional terhadap sisaBobot
                        foreach ($kriteriaLain as $k) {
                            $bobotBaru = ($k->bobot_kriteria / $totalBobotLain) * $sisaBobot;
                            $k->bobot_kriteria = round($bobotBaru, 4);
                            $k->save();
                        }
                    }
                }

                // Update kriteria yang sedang diedit
                Kriteria::find($id)->update($request->only([
                    'kode_kriteria',
                    'nama_kriteria',
                    'bobot_kriteria',
                    'tipe_kriteria',
                    'deskripsi'
                ]));

                DB::commit();
                return response()->json(['status' => true, 'message' => 'Kriteria berhasil diubah dan bobot disesuaikan.']);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat mengubah kriteria: ' . $e->getMessage()
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm($id)
    {
        $kriteria = Kriteria::find($id);
        return view('kriteria.delete', compact('kriteria'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
        DB::beginTransaction();
        try {
            $deleted = Kriteria::findOrFail($id);
            $deletedBobot = $deleted->bobot_kriteria;

            // Hapus data
            $deleted->delete();

            // Ambil sisa kriteria
            $kriteriaSisa = Kriteria::all();
            $totalBobotSisa = $kriteriaSisa->sum('bobot_kriteria');

            if ($kriteriaSisa->count() > 0) {
                // Total bobot setelah penghapusan harus disesuaikan ke 1.00
                foreach ($kriteriaSisa as $k) {
                    // Bagi proporsional dari total sisa
                    $bobotBaru = ($k->bobot_kriteria / $totalBobotSisa) * 1.00;
                    $k->bobot_kriteria = round($bobotBaru, 4);
                    $k->save();
                }
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Kriteria berhasil dihapus dan bobot disesuaikan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus kriteria: ' . $e->getMessage(),
            ]);
        }
    }
        return redirect('/');
    }
}
