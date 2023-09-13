<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
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
        $data = [
            'title_page' => 'Absen Siswa',
            'title' => 'Absen Siswa',
            'kelas' => Kelas::all()
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
        return $request->unique_siswa;
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
                    'kehadiran' => 'A',
                ];
                AbsenAll::create($data);
            }
            return response()->json(['data' => 'Oke']);
        }
    }

    //Absen Hadir
    public function absen_hadir(Request $request)
    {
        AbsenAll::where('unique', $request->unique)->update(['kehadiran' => "H"]);
        $pesan = "Assalamualaikum Wr. Wb. \nAyah/Bunda putra anda tercinta telah Menghadiri kelas pada hari ini tanggal " . tanggal_hari(date('Y-m-d', strtotime($request->tanggal_absen)), true);

        // Ganti dengan nomor penerima WhatsApp yang sesuai
        $nomorPenerima = 'whatsapp:+6282321634181';

        //Kirim pesan menggunakan Twilio
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        $message = $twilio->messages->create(
            $nomorPenerima,
            [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => $pesan,
            ]
        );

        // $sid    = "AC9f50536408fd5b310a85a471042ba5b9";
        // $token  = "ce182c5ba9a13207ac8123b91aa7c9ee";
        // $twilio = new Client($sid, $token);

        // $message = $twilio->messages
        //     ->create(
        //         "whatsapp:+6282321634181", // to
        //         array(
        //             "from" => "whatsapp:+14155238886",
        //             "body" => "Anda telah basen"
        //         )
        //     );
        return response()->json(['success' => 'Berhasil']);
    }
}