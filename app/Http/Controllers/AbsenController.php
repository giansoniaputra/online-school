<?php

namespace App\Http\Controllers;

use App\Models\BAP;
use App\Models\Absen;
use App\Models\Teacher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unique = Teacher::where('npk', auth()->user()->username)->first();
        if ($unique) {
            $data = [
                'title_page' => 'BAP',
                'title' => 'Barita Acara Pemeriksaan',
                'matpels' => DB::table('ampuans as a')
                    ->join('matpels as b', 'a.matpel_unique', '=', 'b.unique')
                    ->select('a.*', 'b.nama_matpel', 'b.kelas')
                    ->where('a.teacher_unique', $unique->unique)
                    ->get()
            ];
            return view('absen.index', $data);
        } else {
            abort(403);
        }
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
            'pertemuan' => 'required',
            'matpel_unique' => 'required',
            'bap' => 'required',
        ];
        $pesan = [
            'pertemuan.required' => 'Pertemuan tidak boleh kosong',
            'matpel_unique.required' => 'Mata pelajaran tidak boleh kosong',
            'bap.required' => 'BAP tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'matpel_unique' => $request->matpel_unique,
                'guru_unique' => auth()->user()->unique,
                'pertemuan' => $request->pertemuan,
                'tanggal_bap' => Carbon::now(),
                'bap' => $request->bap,
            ];
            BAP::create($data);
            return response()->json(['success' => 'BAP Berhasil Ditambahkan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Absen $absen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absen $absen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BAP $bap)
    {
        $rules = [
            'bap' => 'required',
        ];
        $pesan = [
            'bap.required' => 'BAP tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'bap' => $request->bap,
            ];
            BAP::where('unique', $request->unique)->update($data);
            return response()->json(['success' => 'BAP Berhasil Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absen $absen)
    {
        //
    }

    public function deleteBAP(Request $request)
    {
        BAP::where('unique', $request->unique)->delete();
        return response()->json(['success' => 'BAP Berhasil Dihapus']);
    }

    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = Absen::all();
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '
                    <button class="btn btn-rounded btn-sm btn-info text-white hadir-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">H</button>
                    <button class="btn btn-rounded btn-sm btn-warning text-dark sakit-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">S</button>
                    <button class="btn btn-rounded btn-sm btn-danger text-white alfa-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">A</button>';
                return $actionBtn;
            })->make(true);
        }
    }
    public function dataTablesBAP(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('b_a_p_s as a')
                ->join('matpels as b', 'a.matpel_unique', "=", "b.unique")
                ->select("a.*", "b.nama_matpel", "b.kelas")
                ->where('a.guru_unique', auth()->user()->unique)
                ->get();
            foreach ($query as $row) {
                $row->pengampu = $row->nama_matpel . ' - ' . $row->kelas;
                $row->tanggal_bap = tanggal_hari($row->tanggal_bap, true);
            }
            return DataTables::of($query)->make(true);
        }
    }

    public function get_current_BAP(Request $request)
    {
        $query = BAP::where('matpel_unique', $request->matpel)
            ->where('pertemuan', $request->pertemuan)
            ->where('guru_unique', auth()->user()->unique)
            ->first();
        if ($query) {
            return response()->json(['success' => $query]);
        } else {
            return response()->json(['error' => 'Invalid']);
        }
    }
}
