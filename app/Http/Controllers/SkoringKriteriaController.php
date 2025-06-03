<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\SkoringKriteria;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SkoringKriteriaController extends Controller
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
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();
        return view('subKriteria.index', compact('kriterias'));
    }

    public function list($id){
        $query = SkoringKriteria::where('id_kriteria', $id)
                 ->select('id_skoring_kriteria','parameter','nilai_referensi');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                return
                    '<button class="btn btn-warning btn-sm" onclick="modalAction(\''.route('skoring.edit',$row->id_skoring_kriteria).'\')">'.
                      '<i class="mdi mdi-pencil"></i>'.
                    '</button> '.
                    '<button class="btn btn-danger btn-sm" onclick="modalAction(\''.route('skoring.confirm',$row->id_skoring_kriteria).'\')">'.
                      '<i class="mdi mdi-delete"></i>'.
                    '</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $kriteria = Kriteria::find($id);
        return view('subKriteria.create', compact('kriteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        if ($request->ajax() && $request->wantsJson()) {

            $validator = Validator::make($request->all(), [
                'parameter'       => 'required|string|max:255',
                'nilai_referensi' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            SkoringKriteria::create([
                'id_kriteria'      => $id,
                'parameter'        => $request->parameter,
                'nilai_referensi'  => $request->nilai_referensi,
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Skoring berhasil ditambahkan',
            ]);
        }
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sk = SkoringKriteria::find($id);
        $k = $sk->kriteria;
        return view('subKriteria.edit', compact('sk','k'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax() && $request->wantsJson()) {
            $sk = SkoringKriteria::find($id);

            $v = Validator::make($request->all(), [
                'parameter'       => 'required|string|max:255',
                'nilai_referensi' => 'required|integer|min:0',
            ]);

            if ($v->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $v->errors()
                ]);
            }

            $sk->update($request->only(['parameter','nilai_referensi']));
            return response()->json([
                'status'  => true,
                'message' => 'Skoring berhasil diperbarui'
            ]);
        }
        return redirect('/');
    }

    /**
     * Show the form for confirming the deletion of the specified resource.
     */
    public function confirm($id)
    {
        $sk = SkoringKriteria::find($id);
        return view('subKriteria.delete', compact('sk'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->ajax() && $request->wantsJson()) {

            SkoringKriteria::destroy($id);
            return response()->json([
                'status'  => true,
                'message' => 'Skoring berhasil dihapus'
            ]);
        }
    }
}
