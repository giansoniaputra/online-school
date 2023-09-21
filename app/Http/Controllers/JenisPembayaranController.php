<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\JenisPembayaran;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class JenisPembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'Jenis Pembayaran',
            'title' => 'Jenis Pembayaran',
        ];
        return view('jenis_pembayaran.index', $data);
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
            'jenis_pembayaran' => 'required',
            'periode' => 'required',
        ];
        $pesan = [
            'jenis_pembayaran.required' => 'Jenis Pembayaran tidak boleh kosong',
            'periode.required' => 'Periode tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'jenis_pembayaran' => strtoupper($request->jenis_pembayaran),
                'periode' => strtoupper($request->periode),
            ];
            JenisPembayaran::create($data);
            return response()->json(['success' => 'Data Berhasil Ditambahkan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisPembayaran $jenisPembayaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisPembayaran $jenisPembayaran)
    {
        return response()->json(['data' => $jenisPembayaran]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisPembayaran $jenisPembayaran)
    {
        $rules = [
            'jenis_pembayaran' => 'required',
            'periode' => 'required',
        ];
        $pesan = [
            'jenis_pembayaran.required' => 'Jenis Pembayaran tidak boleh kosong',
            'periode.required' => 'Periode tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'jenis_pembayaran' => strtoupper($request->jenis_pembayaran),
                'periode' => strtoupper($request->periode),
            ];
            JenisPembayaran::where('unique', $jenisPembayaran->unique)->update($data);
            return response()->json(['success' => 'Data Berhasil Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisPembayaran $jenisPembayaran)
    {
        JenisPembayaran::where('unique', '=', $jenisPembayaran->unique)->delete();
        return response()->json(['success' => 'Data Berhasil Dihapus']);
    }

    public function dataTables(Request $request)
    {
        $query = JenisPembayaran::all();
        return DataTables::of($query)->addColumn('action', function ($row) {
            $actionBtn =
                '
                <button class="btn btn-rounded btn-sm btn-warning text-dark edit-jenis-button" title="Edit Data jenis" data-unique="' . $row->unique . '"><i class="ri-edit-2-line"></i></button>
                <button class="btn btn-rounded btn-sm btn-danger text-white hapus-jenis-button" title="Hapus Data jenis" data-unique="' . $row->unique . '" data-token="' . csrf_token() . '"><i class="ri-delete-bin-line"></i></button>
           ';
            return $actionBtn;
        })->make(true);
    }
}
