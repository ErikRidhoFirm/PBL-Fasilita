<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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

        return view('kriteria.index', compact('activeMenu','breadcrumbs','kriteria'));
    }

     public function list()
    {
        $query = Kriteria::select('id_kriteria','kode_kriteria','nama_kriteria','bobot_kriteria', 'tipe_kriteria', 'deskripsi');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', fn($kriteria) =>
                '<button onclick="modalAction(\''.route("kriteria.show",$kriteria->id_kriteria).'\')" class="btn btn-info btn-sm"><i class="mdi mdi-file-document-box"></i></button> '
              .  '<button onclick="modalAction(\''.route("kriteria.edit",$kriteria->id_kriteria).'\')" class="btn btn-warning btn-sm"><i class="mdi mdi-pencil"></i></button> '
              . '<button onclick="modalAction(\''.route("kriteria.confirm",$kriteria->id_kriteria).'\')" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>'
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
            $validator = Validator::make($request->all(), [
                'kode_kriteria'  => 'required|string|max:10|unique:kriteria,kode_kriteria',
                'nama_kriteria'  => 'required|string|max:100',
                'bobot_kriteria' => 'required|numeric',
                'tipe_kriteria'  => 'required|in:benefit,cost',
                'deskripsi'      => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            Kriteria::create($request->only(['kode_kriteria', 'nama_kriteria', 'bobot_kriteria', 'tipe_kriteria', 'deskripsi']));
            return response()->json(['status' => true, 'message' => 'Kriteria berhasil ditambahkan']);
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
                'bobot_kriteria' => 'required|numeric',
                'tipe_kriteria'  => 'required|in:benefit,cost',
                'deskripsi'      => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            Kriteria::find($id)->update($request->only(['kode_kriteria', 'nama_kriteria', 'bobot_kriteria', 'tipe_kriteria', 'deskripsi']));
            return response()->json(['status' => true, 'message' => 'Kriteria berhasil diubah']);
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
            Kriteria::destroy($id);
            return response()->json(['status' => true, 'message' => 'Kriteria berhasil dihapus']);
        }
        return redirect('/');
    }
}
