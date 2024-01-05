<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Student;
use App\Models\Teacher;
use Twilio\Rest\Client;
use App\Models\AbsenAll;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AbsenAllController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher = Teacher::where('npk', auth()->user()->username)->first();
        $data = [
            'title_page' => 'Absen Siswa',
            'title' => 'Absen Siswa',
            'kelas' => DB::table('kelas as a')
                ->join('wali_kelas as b', 'a.unique', '=', 'b.unique_kelas')
                ->select('a.*', 'b.unique_teacher')
                ->where('b.unique_teacher', $teacher->unique)
                ->get()
        ];
        return view('absen_all.index', $data);
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
    public function show(AbsenAll $absenAll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AbsenAll $absenAll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AbsenAll $absenAll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AbsenAll $absenAll)
    {
        //
    }

    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('absen_alls as a')
                ->join('students as b', 'a.student_unique', "=", "b.unique")
                ->join('kelas as c', 'b.kelas', "=", "c.unique")
                ->select("a.*", "b.nama", "b.unique as unique_student", "b.telepon_ortu", "c.kelas as kelas2", "c.huruf")
                ->where('b.kelas', $request->kelas)
                ->where('tanggal_absen', $request->tanggal_absen)
                ->get();

            foreach ($query as $row) {
                $row->kelas2 = $row->kelas2 . $row->huruf;
                $row->tanggal_absen = tanggal_hari($row->tanggal_absen);
            }
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '
                    <button class="btn btn-rounded btn-sm btn-primary text-white hadir-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '" data-unique-siswa="' . $row->unique_student . '">H</button>
                    <button class="btn btn-rounded btn-sm btn-info text-white sakit-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">S</button>
                    <button class="btn btn-rounded btn-sm btn-warning text-dark izin-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">I</button>
                    <button class="btn btn-rounded btn-sm btn-danger text-white alfa-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">A</button>';
                return $actionBtn;
            })->make(true);
        }
    }

    public function input_absen(Request $request)
    {
        //Cek pakah Absen Pernah Dilakukan
        $siswa = DB::table('students as a')
            ->join('kelas as b', 'a.kelas', '=', 'b.unique')
            ->select('a.*', 'b.kelas as kelas2', 'b.huruf')
            ->where('b.unique', $request->kelas)
            ->get();
        $cek = AbsenAll::where('student_kelas', $request->kelas)
            ->where('tanggal_absen', $request->tanggal_absen)
            ->first();
        if ($cek) {
            return response()->json(['data' => $cek]);
        } else {
            foreach ($siswa as $row) {
                $data = [
                    'unique' => Str::orderedUuid(),
                    'student_unique' => $row->unique,
                    'student_kelas' => $row->kelas,
                    'tahun_ajaran_unique' => $request->tahun_ajaran,
                    'tanggal_absen' => $request->tanggal_absen,
                    'kehadiran' => '',
                ];
                AbsenAll::create($data);
            }
            return response()->json(['data' => AbsenAll::latest()->first()]);
        }
    }

    //Absen Hadir
    public function absen_hadir(Request $request)
    {
        AbsenAll::where('unique', $request->unique)->update(['kehadiran' => "H"]);
        $siswa = Student::where('unique', $request->student_unique)->first();
        $pesan = "Assalamualaikum Wr. Wb. \nAyah/Bunda putra anda tercinta *$siswa->nama* telah Menghadiri kelas pada hari ini " . tanggal_hari(date('Y-m-d', strtotime($request->tanggal_absen)), true);

        // Ganti dengan nomor penerima WhatsApp yang sesuai
        $nomorPenerima = 'whatsapp:+62' . $siswa->telepon_ortu;

        //Kirim pesan menggunakan Twilio
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        $message = $twilio->messages->create(
            $nomorPenerima,
            [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => $pesan,
            ]
        );
        return response()->json(['success' => 'Berhasil']);
    }
    public function absen_sakit(Request $request)
    {
        AbsenAll::where('unique', $request->unique)->update(['kehadiran' => "S"]);
        return response()->json(['success' => 'Berhasil']);
    }
    public function absen_izin(Request $request)
    {
        AbsenAll::where('unique', $request->unique)->update(['kehadiran' => "I"]);
        return response()->json(['success' => 'Berhasil']);
    }
    public function absen_alfa(Request $request)
    {
        AbsenAll::where('unique', $request->unique)->update(['kehadiran' => "A"]);
        return response()->json(['success' => 'Berhasil']);
    }

    public function hadir_semua(Request $request)
    {
        AbsenAll::where('student_kelas', $request->kelas)->where('tanggal_absen', $request->tanggal)->update(['kehadiran' => "H"]);
        // $siswa = DB::table('absen_alls as a')
        //     ->join('students as b', 'a.student_unique', 'b.unique')
        //     ->where('student_kelas', $request->kelas)->where('tanggal_absen', $request->tanggal)
        //     ->get();
        // foreach ($siswa as $row) {
        //     $pesan = "Assalamualaikum Wr. Wb. \nAyah/Bunda putra anda tercinta *$row->nama* telah Menghadiri kelas pada hari ini " . tanggal_hari(date('Y-m-d', strtotime($request->tanggal)), true);

        //     // Ganti dengan nomor penerima WhatsApp yang sesuai
        //     $nomorPenerima = 'whatsapp:+62' . $row->telepon_ortu;

        //     //Kirim pesan menggunakan Twilio
        //     $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        //     $message = $twilio->messages->create(
        //         $nomorPenerima,
        //         [
        //             'from' => env('TWILIO_PHONE_NUMBER'),
        //             'body' => $pesan,
        //         ]
        //     );
        // }
        return response()->json(['success' => 'Berhasil']);
    }
}
