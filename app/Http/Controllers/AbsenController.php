<?php

namespace App\Http\Controllers;

use App\Models\BAP;
use App\Models\Absen;
use App\Models\Kelas;
use App\Models\Matpel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TahunAjaran;
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
                'title_page' => 'Agenda Pembelajaran',
                'title' => 'Agenda Pembelajaran',
                'matpels' => DB::table('ampuans as a')
                    ->join('matpels as b', 'a.matpel_unique', '=', 'b.unique')
                    ->select('a.*', 'b.nama_matpel', 'b.kelas')
                    ->where('a.teacher_unique', $unique->unique)
                    ->get(),
                'tahun_ajaran' => TahunAjaran::all(),
                'tahun_aktif' => TahunAjaran::where('status', '1')->first(),
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
            'kelas' => 'required',
            'tahun_ajaran_unique' => 'required',
            'bap' => 'required',
        ];
        $pesan = [
            'pertemuan.required' => 'Pertemuan tidak boleh kosong',
            'tahun_ajaran_unique.required' => 'Tahun ajaran tidak boleh kosong',
            'kelas.required' => 'Kelas tidak boleh kosong',
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
                'tahun_ajaran_unique' => $request->tahun_ajaran_unique,
                'kelas' => $request->kelas,
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
            $query = DB::table('absens as a')
                ->join('students as b', 'a.student_unique', "=", "b.unique")
                ->join('kelas as c', 'b.kelas', "=", "c.unique")
                ->select("a.*", "b.nama", "c.kelas as kelas2", "c.huruf")
                ->where('a.bap_unique', $request->unique)
                ->get();

            foreach ($query as $row) {
                $row->kelas2 = $row->kelas2 . $row->huruf;
            }
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '
                    <button class="btn btn-rounded btn-sm btn-primary text-white hadir-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">H</button>
                    <button class="btn btn-rounded btn-sm btn-info text-white sakit-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">S</button>
                    <button class="btn btn-rounded btn-sm btn-warning text-dark izin-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">I</button>
                    <button class="btn btn-rounded btn-sm btn-danger text-white alfa-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">A</button>';
                return $actionBtn;
            })->make(true);
        }
    }
    public function dataTablesBAP(Request $request)
    {
        if ($request->ajax()) {
            if ($request->f_matpel == 'ALL') {
                $query = DB::table('b_a_p_s as a')
                    ->join('matpels as b', 'a.matpel_unique', "=", "b.unique")
                    ->join('tahun_ajarans as c', 'a.tahun_ajaran_unique', "=", "c.unique")
                    ->join('kelas as d', 'a.kelas', "=", "d.unique")
                    ->select("a.*", "b.nama_matpel", "b.kelas as kelas2", "c.tahun_awal", "c.tahun_akhir", DB::raw('CONCAT(d.kelas, d.huruf) as kelas_siswa'))
                    ->where('a.guru_unique', auth()->user()->unique)
                    ->where('a.tahun_ajaran_unique', $request->tahun_ajaran)
                    ->get();
            } else {
                $query = DB::table('b_a_p_s as a')
                    ->join('matpels as b', 'a.matpel_unique', "=", "b.unique")
                    ->join('tahun_ajarans as c', 'a.tahun_ajaran_unique', "=", "c.unique")
                    ->join('kelas as d', 'a.kelas', "=", "d.unique")
                    ->select("a.*", "b.nama_matpel", "b.kelas as kelas2", "c.tahun_awal", "c.tahun_akhir", DB::raw('CONCAT(d.kelas, d.huruf) as kelas_siswa'))
                    ->where('a.guru_unique', auth()->user()->unique)
                    ->where('a.matpel_unique', $request->f_matpel)
                    ->where('a.tahun_ajaran_unique', $request->tahun_ajaran)
                    ->get();
            }

            foreach ($query as $row) {
                $row->pengampu = $row->nama_matpel . ' - ' . $row->kelas2;
                $row->tanggal_bap = tanggal_hari($row->tanggal_bap, true);
                $row->tahun_aktif = $row->tahun_awal . '/' . $row->tahun_akhir;
            }
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '
                    <button class="btn btn-rounded btn-sm btn-primary text-white absen-button" title="Absen Siswa" data-unique="' . $row->unique . '" data-matpel="' . $row->nama_matpel . '" data-tahun-ajaran="' . $row->tahun_awal . '/' . $row->tahun_akhir . '" data-tanggal-bap="' . $row->tanggal_bap . '" data-kelas="' . $row->kelas . '">Absen</button>';
                return $actionBtn;
            })->make(true);
        }
    }

    public function get_current_BAP(Request $request)
    {
        $query = BAP::where('matpel_unique', $request->matpel)
            ->where('pertemuan', $request->pertemuan)
            ->where('tahun_ajaran_unique', $request->tahun_ajaran)
            ->where('kelas', $request->kelas)
            ->where('guru_unique', auth()->user()->unique)
            ->first();

        if ($query) {
            return response()->json(['success' => $query]);
        } else {
            return response()->json(['error' => 'Invalid']);
        }
    }

    //Ambil Kelas
    public function get_kelas(Request $request)
    {
        $matpel = Matpel::where('unique', $request->matpel)->first();
        $kelas = Kelas::where('kelas', $matpel->kelas)->get();
        echo '<div class="row pl-3 pr-3" id="kelas_echo">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="kelas">Kelas</label>
                <select class="form-control" name="kelas" id="kelas">
                    <option selected disabled value="">Pilih Kelas...</option>';
        foreach ($kelas as $row) {
            echo '<option value="' . $row->unique . '">' . $row->kelas . $row->huruf . '</option>';
        }
        echo '</select>
            </div>
        </div>
    </div>';
    }

    public function input_absen(Request $request)
    {
        $siswa = DB::table('students as a')
            ->join('kelas as b', 'a.kelas', '=', 'b.unique')
            ->select('a.*', 'b.unique as unique_kelas')
            ->where('b.unique', $request->kelas)
            ->get();
        $cek = Absen::where('bap_unique', $request->unique_bap)->first();
        $bap = BAP::where('unique', $request->unique_bap)->first();
        if ($cek) {
            return response()->json(['data' => $cek]);
        } else {
            foreach ($siswa as $row) {
                $data = [
                    'unique' => Str::orderedUuid(),
                    'student_unique' => $row->unique,
                    'student_kelas' => $row->unique_kelas,
                    'bap_unique' => $bap->unique,
                    'tahun_ajaran_unique' => $request->tahun_ajaran,
                    'tanggal_absen' => $bap->tanggal_bap,
                    'kehadiran' => 'A',
                ];
                Absen::create($data);
            }
            return response()->json(['data' => Absen::latest()->first()]);
        }
    }


    //Absen Hadir
    public function absen_hadir(Request $request)
    {
        Absen::where('unique', $request->unique)->update(['kehadiran' => "H"]);
        return response()->json(['success' => 'Berhasil']);
    }
    //Absen SAKIT
    public function absen_sakit(Request $request)
    {
        Absen::where('unique', $request->unique)->update(['kehadiran' => "S"]);
        return response()->json(['success' => 'Berhasil']);
    }
    //Absen Izin
    public function absen_izin(Request $request)
    {
        Absen::where('unique', $request->unique)->update(['kehadiran' => "I"]);
        return response()->json(['success' => 'Berhasil']);
    }
    //Absen Hadir
    public function absen_alfa(Request $request)
    {
        Absen::where('unique', $request->unique)->update(['kehadiran' => "A"]);
        return response()->json(['success' => 'Berhasil']);
    }
}
