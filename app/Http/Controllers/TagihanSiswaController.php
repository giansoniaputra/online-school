<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Support\Str;
use App\Models\TagihanSiswa;
use Illuminate\Http\Request;
use App\Models\SettingTagihan;
use App\Models\GenerateTagihan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TagihanSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'Tagihan',
            'title' => 'Tagihan Siswa',
            'siswa' => Student::orderBy('nama', 'asc')
                ->get(),

        ];
        return view('tagihan_siswa.index', $data);
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
            'nominal' => 'required',
            'tanggal_bayar' => 'required',
        ];
        $pesan = [
            'nominal.required' => 'Nominal tidak boleh kosong',
            'tanggal_bayar.required' => 'Tangal bayar tidak boleh kosong',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            //Jika Sudah Ada Tagihan
            $cek = TagihanSiswa::where('unique_generate', $request->unique_generate)->first();
            //Jumlah nominal yang harus di bayae
            $nominal = preg_replace('/[,]/', '', $request->nominal) - preg_replace('/[,]/', '', $request->kembali);
            //Jika Unique Generate telah ada
            if ($cek) {
                $data = [
                    'unique' => Str::orderedUuid(),
                    'unique_student' => $request->unique_student,
                    'unique_kelas' => $request->unique_kelas,
                    'unique_tahun_ajaran' => $request->unique_tahun_ajaran,
                    'unique_jenis_pembayaran' => $request->unique_jenis_pembayaran,
                    'unique_generate' => $request->unique_generate,
                    'periode_tagihan' => $request->periode_tagihan,
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'nominal' => $nominal,
                ];
                //Cek Jika jenis pembayaran adalah BULANAN dan jumlah bayar kurang dari nominal yang di tagihkan
                if ($request->periode_tagihan == "BULANAN" && $nominal < $request->nominal_tagihan) {
                    return response()->json(['minus' => 'Jumlah Pembayaran Tidak Boleh Kurang Dari Nominal yang Ditagihkan']);
                } else {
                    //Simpan Tagihan
                    TagihanSiswa::create($data);
                    //Menjumlahkan Nominal yang telah terbayarkan dari unique generate
                    $sum = TagihanSiswa::where('unique_generate', $request->unique_generate)->sum('nominal');
                    //Menghitung Jumlah pembayaran yang telah terbayarkan dari unique generate
                    $count = TagihanSiswa::where('unique_generate', $request->unique_generate)->count('id');
                    //Jika pembayaran adalah SEKALI BAYAR
                    if ($request->nominal_tagihan == $sum && $request->periode_tagihan == "SEKALI BAYAR") {
                        GenerateTagihan::where('unique', $request->unique_generate)->update(['status' => 1]);
                    }
                    //Jika pembayaran adalah BULANAN
                    else if ($count == 6 && $request->periode_tagihan == "BULANAN") {
                        GenerateTagihan::where('unique', $request->unique_generate)->update(['status' => 1]);
                    }
                    return response()->json(['success' => "Pembayaran Berhasil Dilakukan"]);
                }
            }
            //Jika Unique Generate belum pernah ada
            else {
                $data = [
                    'unique' => Str::orderedUuid(),
                    'unique_student' => $request->unique_student,
                    'unique_kelas' => $request->unique_kelas,
                    'unique_tahun_ajaran' => $request->unique_tahun_ajaran,
                    'unique_jenis_pembayaran' => $request->unique_jenis_pembayaran,
                    'unique_generate' => $request->unique_generate,
                    'periode_tagihan' => $request->periode_tagihan,
                    'tanggal_bayar' => $request->tanggal_bayar,
                    'nominal' => $nominal,
                ];
                //Cek Jika jenis pembayaran adalah BULANAN dan jumlah bayar kurang dari nominal yang di tagihkan
                if ($request->periode_tagihan == "BULANAN" && $nominal < $request->nominal_tagihan) {
                    return response()->json(['minus' => 'Jumlah Pembayaran Tidak Boleh Kurang Dari Nominal yang Ditagihkan']);
                } else {
                    TagihanSiswa::create($data);
                    $sum = TagihanSiswa::where('unique_generate', $request->unique_generate)->sum('nominal');
                    $count = TagihanSiswa::where('unique_generate', $request->unique_generate)->count('id');
                    if ($request->nominal_tagihan == $sum && $request->periode_tagihan == "SEKALI BAYAR") {
                        GenerateTagihan::where('unique', $request->unique_generate)->update(['status' => 1]);
                    } else if ($count == 6 && $request->periode_tagihan == "BULANAN") {
                        GenerateTagihan::where('unique', $request->unique_generate)->update(['status' => 1]);
                    }
                    return response()->json(['success' => "Pembayaran Berhasil Dilakukan"]);
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TagihanSiswa $tagihanSiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TagihanSiswa $tagihanSiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TagihanSiswa $tagihanSiswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TagihanSiswa $tagihanSiswa)
    {
        //
    }

    public function pilih_siswa(Request $request)
    {
        $siswa = DB::table('students as a')
            ->join('kelas as b', 'a.kelas', '=', 'b.unique')
            ->select('a.*', DB::raw('CONCAT(b.kelas, b.huruf) as kelas2'))
            ->where('a.unique', '=', $request->unique)
            ->first();
        return response()->json(['data' => $siswa]);
    }

    public function get_data_tagihan(Request $request)
    {
        $siswa = DB::table('students as a')
            ->join('generate_tagihans as b', 'a.unique', '=', 'b.unique_siswa')
            ->select('a.*', 'b.unique_tahun_ajaran', 'b.unique_tagihan', 'b.unique as unique_generate', 'b.status')
            ->where('a.unique', '=', $request->unique)
            ->where('b.status', '=', 0)
            ->get();
        $no = 1;
        foreach ($siswa as $row) {
            $tagihan = DB::table('setting_tagihans as a')
                ->join('jenis_pembayarans as b', 'a.unique_jenis_pembayaran', 'b.unique')
                ->join('tahun_ajarans as c', 'a.unique_tahun_ajaran', 'c.unique')
                // ->join('kelas as d', 'a.unique_kelas', 'd.unique')
                ->select('a.*', 'b.jenis_pembayaran', 'c.tahun_awal', 'c.tahun_akhir', 'c.periode', 'b.periode as jenis_periode')
                ->where('a.unique_jenis_pembayaran', $row->unique_tagihan)
                ->where('a.unique_tahun_ajaran', $row->unique_tahun_ajaran)
                ->where('a.unique_kelas', $row->kelas)
                ->get();
            foreach ($tagihan as $row2) {
                echo '
                                <tr>
                                    <td>' . $no++ . '</td>
                                    <td>' . $row2->tahun_awal . '/' . $row2->tahun_akhir . ' ' . $row2->periode . '</td>
                                    <td>' . $row2->jenis_pembayaran . '</td>
                                    <td>' . rupiah($row2->nominal) . '</td>
                                    <td class="text-center">
                                        <button type="button" id="btn-bayar" class="btn btn-primary" data-siswa="' . $row->unique . '" data-kelas="' . $row->kelas . '" data-unique="' . $row->unique_generate . '" data-tahun="' . $row2->tahun_awal . '/' . $row2->tahun_akhir . ' ' . $row2->periode . '" data-nominal="' . $row2->nominal . '" data-periode="' . $row2->jenis_periode . '" data-unique-tahun="' . $row->unique_tahun_ajaran . '" data-unique-pembayaran="' . $row->unique_tagihan . '" data-jenis="' . $row2->jenis_pembayaran . '">Bayar</button>
                                    </td>
                                </tr>
                                
                                ';
            }
        }
    }
    public function get_data_tagihan_lunas(Request $request)
    {
        $siswa = DB::table('students as a')
            ->join('generate_tagihans as b', 'a.unique', '=', 'b.unique_siswa')
            ->select('a.*', 'b.unique_tahun_ajaran', 'b.unique_tagihan', 'b.unique as unique_generate', 'b.status')
            ->where('a.unique', '=', $request->unique)
            ->where('b.status', '=', 1)
            ->get();
        $no = 1;
        foreach ($siswa as $row) {
            $tagihan = DB::table('setting_tagihans as a')
                ->join('jenis_pembayarans as b', 'a.unique_jenis_pembayaran', 'b.unique')
                ->join('tahun_ajarans as c', 'a.unique_tahun_ajaran', 'c.unique')
                // ->join('kelas as d', 'a.unique_kelas', 'd.unique')
                ->select('a.*', 'b.jenis_pembayaran', 'c.tahun_awal', 'c.tahun_akhir', 'c.periode', 'b.periode as jenis_periode')
                ->where('a.unique_jenis_pembayaran', $row->unique_tagihan)
                ->where('a.unique_tahun_ajaran', $row->unique_tahun_ajaran)
                ->where('a.unique_kelas', $row->kelas)
                ->get();
            foreach ($tagihan as $row2) {
                echo '
                                <tr>
                                    <td>' . $no++ . '</td>
                                    <td>' . $row2->tahun_awal . '/' . $row2->tahun_akhir . ' ' . $row2->periode . '</td>
                                    <td>' . $row2->jenis_pembayaran . '</td>
                                    <td>' . rupiah($row2->nominal) . '</td>
                                    <td class="text-center">
                                        <button type="button" id="btn-bayar" class="btn btn-primary" data-siswa="' . $row->unique . '" data-kelas="' . $row->kelas . '" data-unique="' . $row->unique_generate . '" data-tahun="' . $row2->tahun_awal . '/' . $row2->tahun_akhir . ' ' . $row2->periode . '" data-nominal="' . $row2->nominal . '" data-periode="' . $row2->jenis_periode . '" data-unique-tahun="' . $row->unique_tahun_ajaran . '" data-unique-pembayaran="' . $row->unique_tagihan . '" data-jenis="' . $row2->jenis_pembayaran . '">Riwayat</button>
                                    </td>
                                </tr>
                                
                                ';
            }
        }
    }

    public function cek_tagihan_terbayar(Request $request)
    {
        $cek = TagihanSiswa::where('unique_generate', $request->unique_generate)->first();
        // return response()->json(['data_once' => 'oke']);
        if ($request->periode_tagihan == "SEKALI BAYAR") {
            if ($cek) {
                $sum = TagihanSiswa::where('unique_generate', $request->unique_generate)->sum('nominal');
                $current_nominal = $request->current_nominal - $sum;
                return response()->json(['data_once' => $current_nominal]);
            } else {
                $current_nominal = $request->current_nominal - 0;
                return response()->json(['data_once' => $current_nominal]);
            }
        } else if ($request->periode_tagihan == "BULANAN") {
            if ($cek) {
                $count = TagihanSiswa::where('unique_generate', $request->unique_generate)->count('id');
                return response()->json(['data_month' => 6 - $count]);
            } else {
                $count = 6;
                return response()->json(['data_month' => $count]);
            }
        }
    }
}
