<?php

namespace App\Http\Controllers;

use App\Models\BAP;
use App\Models\Absen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'BAP',
            'title' => 'Barita Acara Pemeriksaan',
            // 'matpel' => DB::table('matpels as a')
            //     ->join('teachers as b', 'a.unique', '=', 'b.pengampu')
            //     ->where('b.unique', auth()->user()->unique)
            //     ->get()
        ];
        return view('absen.index', $data);
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
    public function update(Request $request, Absen $absen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absen $absen)
    {
        //
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
                ->select("a.*", "b.nama_matpel")
                ->where('a.guru_unique', auth()->user()->unique)
                ->get();
            foreach ($query as $row) {
                $row->pengampu = $row->nama_matpel . ' - ' . $row->kelas;
            }
            return DataTables::of($query)->make(true);
        }
    }
}
