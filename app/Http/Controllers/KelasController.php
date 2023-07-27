<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'Kelas',
            'title' => 'Data Kelas',
        ];
        return view('kelas.index', $data);
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
            'kelas' => 'required',
        ];
        $pesan = [
            'kelas.required' => 'Kelas tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'kelas' => $request->kelas,
            ];
            if ($request->kelas) {
                $data['huruf'] = strtoupper($request->huruf);
            } else {
                $data['huruf'] = '';
            }
            Kelas::create($data);
            return response()->json(['success' => 'Data Kelas Berhasil Ditambahkan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas, Request $request)
    {
        $query = Kelas::where('unique', $request->unique)->first();
        return response()->json(['data' => $query]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $rules = [
            'kelas' => 'required',
        ];
        $pesan = [
            'kelas.required' => 'Kelas tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'kelas' => $request->kelas,
            ];
            if ($request->kelas) {
                $data['huruf'] = strtoupper($request->huruf);
            } else {
                $data['huruf'] = '';
            }
            Kelas::where('unique', $request->unique)->update($data);
            return response()->json(['success' => 'Data Kelas Berhasil Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas, Request $request)
    {
        $cek = Kelas::where('unique', $request->unique)->delete();
        return response()->json(['success' => 'Data Kelas Berhasil Dihpus']);
    }

    public function dataTables(Request $request)
    {
        $query = Kelas::all();
        foreach ($query as $row) {
            if ($row->huruf == NULL) {
                $row->huruf = 'Tidak memiliki tingkat';
            } else {
                $row->huruf = $row->huruf;
            }
        }
        return DataTables::of($query)->addColumn('action', function ($row) {
            $actionBtn =
                '
                <button class="btn btn-rounded btn-sm btn-warning text-dark edit-kelas-button" title="Edit Data" data-unique="' . $row->unique . '"><i class="fas fa-edit"></i></button>
                <button class="btn btn-rounded btn-sm btn-danger text-white delete-kelas-button" title="Hapus Data" data-unique="' . $row->unique . '" data-token="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></button>';
            return $actionBtn;
        })->make(true);
    }
}
