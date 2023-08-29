<?php

namespace App\Http\Controllers;

use App\Models\BAP;
use App\Models\TahunAjaran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'Tahun Ajaran',
            'title' => 'Data Tahun Ajaran',
        ];
        return view('TahunAjaran.index', $data);
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
            'tahun_awal' => 'required',
            'tahun_akhir' => 'required',
            'periode' => 'required'
        ];
        $pesan = [
            'tahun_awal.required' => 'Tahun awal tidak boleh kosong',
            'tahun_akhir.required' => ' Tahun akhir tidak boleh kosong',
            'periode.required' => ' Periode tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'tahun_awal' => $request->tahun_awal,
                'tahun_akhir' => $request->tahun_akhir,
                'periode' => $request->periode,
                'status' => '0',
            ];
            TahunAjaran::create($data);
            return response()->json(['success' => 'Tahun Ajaran Berhasil Ditambahkan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAjaran $tahunAjaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAjaran $tahunAjaran)
    {
        return response()->json(['data' => $tahunAjaran]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $rules = [
            'tahun_awal' => 'required',
            'tahun_akhir' => 'required',
            'periode' => 'required'
        ];
        $pesan = [
            'tahun_awal.required' => 'Tahun awal tidak boleh kosong',
            'tahun_akhir.required' => ' Tahun akhir tidak boleh kosong',
            'periode.required' => ' Periode tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'tahun_awal' => $request->tahun_awal,
                'tahun_akhir' => $request->tahun_akhir,
                'periode' => $request->periode,
            ];
            TahunAjaran::where('unique', $tahunAjaran->unique)->update($data);
            return response()->json(['success' => 'Tahun Ajaran Berhasil Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAjaran $tahunAjaran)
    {
        $cek = TahunAjaran::where('unique', $tahunAjaran->unique)->first();
        $cek_bap = BAP::where('tahun_ajaran_unique', $tahunAjaran->unique)->first();

        if ($cek->status == '1') {
            return response()->json(['errors' => 'Tahun Ajaran Aktif Tidak Bisa Dihapus!']);
        } else {
            if ($cek_bap) {
                return response()->json(['errors' => 'Tahun Ajaran Tidak Bisa Dihapus!']);
            } else {
                TahunAjaran::where('unique', $tahunAjaran->unique)->delete();
                return response()->json(['success' => 'Data Tahun Ajaran Berhasil Dihapus!']);
            }
        }
    }

    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = TahunAjaran::all();
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '
                    <button class="btn btn-rounded btn-sm btn-info text-white button-aktif" title="Set Aktif" data-unique="' . $row->unique . '">Jadikan Aktif</button>
                    <button class="btn btn-rounded btn-sm btn-warning text-white button-edit" title="Edit Tahun Ajaran" data-unique="' . $row->unique . '">Edit</button>
                    <button class="btn btn-rounded btn-sm btn-danger text-white button-delete" title="Hapus Tahun Ajaran" data-unique="' . $row->unique . '" data-token="' . csrf_token() . '">Hapus</button>
                    ';
                return $actionBtn;
            })->make(true);
        }
    }

    public function tahun_aktif(Request $request)
    {
        $cek = TahunAjaran::where('status', '1')->first();
        TahunAjaran::where('unique', $cek->unique)->update(['status' => '0']);
        TahunAjaran::where('unique', $request->unique)->update(['status' => '1']);
        return response()->json(['success' => 'Tahun Ajaran Aktif Berhasil Diubah!']);
    }

    public function refresh_tahun_aktif()
    {
        $cek = TahunAjaran::where('status', '1')->first();
        echo $cek->tahun_awal . '/' . $cek->tahun_akhir . ' ' . $cek->periode . '<input type="hidden" id="input-tahun-ajaran-aktif" value="' . $cek->unique . '">';
    }
}
