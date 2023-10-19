<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Student;
use App\Models\TahunAjaran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SettingTagihan;
use App\Models\GenerateTagihan;
use App\Models\JenisPembayaran;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class GenerateTagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'Tagihan',
            'title' => 'Generate Tagihan',
            'kelas' => Kelas::all(),
            'jenis' => JenisPembayaran::all(),
            'sum_jenis' => DB::table('jenis_pembayarans')->count('id'),
        ];
        return view('generate_tagihan.index', $data);
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
    public function show(GenerateTagihan $generateTagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GenerateTagihan $generateTagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GenerateTagihan $generateTagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GenerateTagihan $generateTagihan)
    {
        //
    }

    public function dataTables_list_tagihan(Request $request)
    {
        $query = TahunAjaran::all();
        foreach ($query as $row) {
            $row->tahun = $row->tahun_awal . '/' . $row->tahun_akhir . ' ' . $row->periode;
        }
        return DataTables::of($query)->addColumn('action', function ($row) {
            $actionBtn =
                '
        <button class="btn btn-rounded btn-sm btn-primary text-white generate-button histori-button" title="Generate Tagihan" data-unique="' . $row->unique . '"><i class="ri-ai-generate"></i></button>';
            return $actionBtn;
        })->make(true);
    }

    public function dataTables_tagihan_siswa_generate(Request $request)
    {
        $query = Student::where('kelas', $request->unique_kelas)->get();
        return DataTables::of($query)->addColumn('action', function ($row) {
            $actionBtn =
                '
                <input type="checkbox" name="siswa[]" value="' . $row->unique . '">
                ';
            return $actionBtn;
        })->make(true);
    }

    public function cek_setting_tagihan(Request $request)
    {
        $cek = SettingTagihan::where('unique_tahun_ajaran', $request->unique_tahun_ajaran)->first();
        if ($cek) {
            return response()->json(['oke' => 'oke']);
        } else {
            return response()->json(['errors' => 'Silahkan Generate Terlebih Dahulu Nominal Tagihan']);
        }
    }

    public function generate_tagihan(Request $request)
    {
        $tagihan = explode(",", $request->unique_tagihan);
        $siswa = $request->unique_siswa;
        $tahun = $request->unique_tahun_ajaran;
        $count = 1;
        foreach ($tagihan as $row) {
            $cek = GenerateTagihan::where('unique_tahun_ajaran', $tahun)
                ->where('unique_tagihan', $row)
                ->where('unique_siswa', $siswa)
                ->first();
            $data = [
                'unique' => Str::orderedUuid(),
                'unique_tahun_ajaran' => $tahun,
                'unique_tagihan' => $row,
                'unique_siswa' => $siswa,
                'status' => 0
            ];
            if (!$cek) {
                $count++;
                GenerateTagihan::create($data);
            }
        }
        return response()->json(['success' => $count]);
    }
}
