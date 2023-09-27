<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SettingTagihan;
use App\Models\JenisPembayaran;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SettingTagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'Setting Tagihan',
            'title' => 'Setting Tagihan',
            'query' => SettingTagihan::all(),
            'kelas' => Kelas::all(),
            'tahun_ajaran' => TahunAjaran::all(),
            'jenis_pembayaran' => JenisPembayaran::all(),
        ];
        return view('setting_tagihan.index', $data);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SettingTagihan $settingTagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SettingTagihan $settingTagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SettingTagihan $settingTagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SettingTagihan $settingTagihan)
    {
        //
    }

    public function dataTables(Request $request)
    {
        $query = DB::table('setting_tagihans as a')
            ->join('kelas as b', 'a.unique_kelas', '=', 'b.unique')
            ->join('jenis_pembayarans as c', 'a.unique_jenis_pembayaran', '=', 'c.unique')
            ->select('a.*', 'b.kelas', 'b.huruf', 'c.jenis_pembayaran')
            ->where('unique_jenis_pembayaran', $request->unique_jenis_pembayaran)
            ->where('unique_tahun_ajaran', $request->unique_tahun_ajaran)
            ->get();
        foreach ($query as $row) {
            $row->kelas2 = $row->kelas . $row->huruf;
            $row->nominal = $row->nominal . '+' . $row->unique;
        }
        return DataTables::of($query)->make(true);
    }

    public function update_nominal(Request $reqeust)
    {
        $validator = Validator::make($reqeust->all(), ['nominal' => 'required'], ['nominal.required' => 'Nominal tidak boleh kosong.']);
        if ($validator->fails()) {
            return response()->json(['errors' => "Nominal tidak boleh kosong"]);
        } else {
            SettingTagihan::where('unique', $reqeust->unique)->update(['nominal' => preg_replace('/[,]/', '', $reqeust->nominal)]);
            return response()->json(['success' => 'Oke']);
        }
    }

    public function cek_data_tagihan(Request $request)
    {
        $cek = SettingTagihan::where('unique_jenis_pembayaran', $request->unique_jenis_pembayaran)
            ->where('unique_tahun_ajaran', $request->unique_tahun_ajaran)
            ->first();
        if ($cek) {
            return response()->json(['oke' => 'oke']);
        } else {
            return response()->json(['kosong' => 'tidak oke']);
        }
    }

    public function setting_tagihan(Request $request)
    {
        $kelas = Kelas::all();
        foreach ($kelas as $row) {
            $data = [
                'unique' => Str::orderedUuid(),
                'unique_jenis_pembayaran' => $request->unique_jenis_pembayaran,
                'unique_tahun_ajaran' => $request->unique_tahun_ajaran,
                'unique_kelas' => $row->unique,
                'nominal' => 0
            ];
            SettingTagihan::create($data);
        }
        return response()->json(['success' => 'Tagihan Berhasil Setting']);
    }

    public function cari_siswa(Request $request)
    {
        return response()->json(['success' => $request->all()]);
    }
}
