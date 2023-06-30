<?php

namespace App\Http\Controllers;

use App\Models\Matpel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class MatpelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'Mata Peljaran',
            'title' => 'Data Mata Pelajaran',
        ];
        return view('matpel.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_matpel' => 'required',
            'kelas' => 'required',
        ];
        $pesan = [
            'nama_matpel.required' => 'Mata pelajaran tidak boleh kosong',
            'kelas.required' => 'Kelas tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'nama_matpel' => ucwords(strtolower($request->nama_matpel)),
                'kelas' => $request->kelas
            ];
            Matpel::create($data);
            return response()->json(['success' => 'Data Berhasil Ditambahkan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Matpel $matpel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Matpel $matpel)
    {
        return response()->json(['data' => $matpel]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Matpel $matpel)
    {
        $rules = [
            'nama_matpel' => 'required',
            'kelas' => 'required',
        ];
        $pesan = [
            'nama_matpel.required' => 'Mata pelajaran tidak boleh kosong',
            'kelas.required' => 'Kelas tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'nama_matpel' => ucwords(strtolower($request->nama_matpel)),
                'kelas' => $request->kelas
            ];
            Matpel::where('unique', $matpel->unique)->update($data);
            return response()->json(['success' => 'Data Berhasil Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Matpel $matpel)
    {
        Matpel::where('unique', $matpel->unique)->delete();
        return response()->json(['success' => 'Data Berhasil Dihaspus']);
    }
    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = Matpel::all();
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '
                    <button class="btn btn-rounded btn-sm btn-warning text-dark edit-matpel-button" title="Edit Siswa" data-unique="' . $row->unique . '">Edit</button>
                    <button class="btn btn-rounded btn-sm btn-danger text-white hapus-matpel-button" title="Edit Siswa" data-unique="' . $row->unique . '" data-token="' . csrf_token() . '">Hapus</button>';
                return $actionBtn;
            })->make(true);
        }
    }
}
