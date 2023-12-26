<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Student;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use FPDF;

class LaporanPresensiController extends PDFController
{
    public function index()
    {
        $data = [
            'title_page' => 'Laporan',
            'title' => 'Laporan Absensi',
            'tahun_ajaran' => TahunAjaran::all(),
            'tahun_aktif' => TahunAjaran::where('status', '1')->first(),
        ];
        return view('laporan.index', $data);
    }
    public function dataTables(Request $request)
    {
        $query = TahunAjaran::all();
        foreach ($query as $row) {
            $row->trimTahun = $row->tahun_awal . '/' . $row->tahun_akhir . ' ' . $row->periode;
        }
        return DataTables::of($query)->addColumn('action', function ($row) {
            $actionBtn =
                '
                <button class="btn btn-rounded btn-sm btn-success pilih-kelas" title="Pilih Kelas" data-unique="' . $row->unique . '" data-tahun="' . $row->tahun_awal . '/' . $row->tahun_akhir . ' ' . $row->periode . '"><i class="ri-eye-line"></i></button>';
            return $actionBtn;
        })->make(true);
    }

    public function get_kelas($unique_tahun_ajaran)
    {
        $element = '';
        $query = DB::table('kelas as a')
            ->join('absen_alls as b', 'a.unique', '=', 'b.student_kelas')
            ->select('b.student_kelas')
            ->where('b.tahun_ajaran_unique', $unique_tahun_ajaran)
            ->distinct('b.student_kelas')
            ->groupBy('b.student_kelas')
            ->get();
        foreach ($query as $row) {
            $kelas = Kelas::where('unique', $row->student_kelas)->first();
            $element .= '
                <div class="col-sm-4">
                    <div class="card card-hover" id="card-kelas" data-unique-kelas="' . $row->student_kelas . '" data-kelas="' . $kelas->kelas . $kelas->huruf . '">
                        <div class="card-body d-flex justify-conten-center flex-column align-items-center">
                            <h3 class="card-title">' . $kelas->kelas . $kelas->huruf . '</h3>
                            <p class="card-text">Klik untuk memilih kelas</p>
                        </div>
                    </div>
                </div>';
        }
        return response()->json(['data' => $element]);
    }

    public function dataTables_laporan_all(Request $request)
    {
        //JIKA FILTER BULANAN
        if ($request->tanggal_awal == "" && $request->tanggal_awal == "" && $request->bulanan != "" && $request->hari_ini == "0") {
            if ($request->bulanan == "ALL") {
                $query = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('a.student_unique')
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->distinct()
                    ->groupBy('a.student_unique')
                    ->get();
                //QUERY
                foreach ($query as $row) {
                    $student = Student::where('unique', $row->student_unique)->first();
                    $hadir = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'H')
                        ->count('a.kehadiran');
                    $sakit = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'S')
                        ->count('a.kehadiran');
                    $izin = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'I')
                        ->count('a.kehadiran');
                    $alfa = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'A')
                        ->count('a.kehadiran');
                    $row->nama = $student->nama;
                    $row->hadir = $hadir;
                    $row->alfa = $alfa;
                    $row->sakit = $sakit;
                    $row->izin = $izin;
                }
            } else {
                $query = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('a.student_unique')
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where(DB::raw("DATE_FORMAT(a.tanggal_absen,'%m')"), $request->bulanan)
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->distinct()
                    ->groupBy('a.student_unique')
                    ->get();
                //QUERY
                foreach ($query as $row) {
                    $student = Student::where('unique', $row->student_unique)->first();
                    $hadir = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where(DB::raw("DATE_FORMAT(a.tanggal_absen,'%m')"), $request->bulanan)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'H')
                        ->count('a.kehadiran');
                    $sakit = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where(DB::raw("DATE_FORMAT(a.tanggal_absen,'%m')"), $request->bulanan)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'S')
                        ->count('a.kehadiran');
                    $izin = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where(DB::raw("DATE_FORMAT(a.tanggal_absen,'%m')"), $request->bulanan)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'I')
                        ->count('a.kehadiran');
                    $alfa = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where(DB::raw("DATE_FORMAT(a.tanggal_absen,'%m')"), $request->bulanan)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'A')
                        ->count('a.kehadiran');
                    $row->nama = $student->nama;
                    $row->hadir = $hadir;
                    $row->alfa = $alfa;
                    $row->sakit = $sakit;
                    $row->izin = $izin;
                }
            }
        } else if ($request->bulanan == "" && $request->tanggal_awal != "" && $request->tanggal_akhir != "" && $request->hari_ini == "0") {
            $query = DB::table('absen_alls as a')
                ->join('students as b', 'a.student_unique', '=', 'b.unique')
                ->select('a.student_unique')
                ->where('a.student_kelas', $request->unique_kelas)
                ->where('a.tanggal_absen', '>=', $request->tanggal_awal)
                ->where('a.tanggal_absen', '<=', $request->tanggal_akhir)
                ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                ->distinct()
                ->groupBy('a.student_unique')
                ->get();
            //QUERY
            foreach ($query as $row) {
                $student = Student::where('unique', $row->student_unique)->first();
                $hadir = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('b.nama', 'a.*')
                    ->where('a.student_unique', $row->student_unique)
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tanggal_absen', '>=', $request->tanggal_awal)
                    ->where('a.tanggal_absen', '<=', $request->tanggal_akhir)
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->where('a.kehadiran', 'H')
                    ->count('a.kehadiran');
                $sakit = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('b.nama', 'a.*')
                    ->where('a.student_unique', $row->student_unique)
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tanggal_absen', '>=', $request->tanggal_awal)
                    ->where('a.tanggal_absen', '<=', $request->tanggal_akhir)
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->where('a.kehadiran', 'S')
                    ->count('a.kehadiran');
                $izin = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('b.nama', 'a.*')
                    ->where('a.student_unique', $row->student_unique)
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tanggal_absen', '>=', $request->tanggal_awal)
                    ->where('a.tanggal_absen', '<=', $request->tanggal_akhir)
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->where('a.kehadiran', 'I')
                    ->count('a.kehadiran');
                $alfa = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('b.nama', 'a.*')
                    ->where('a.student_unique', $row->student_unique)
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tanggal_absen', '>=', $request->tanggal_awal)
                    ->where('a.tanggal_absen', '<=', $request->tanggal_akhir)
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->where('a.kehadiran', 'A')
                    ->count('a.kehadiran');
                $row->nama = $student->nama;
                $row->hadir = $hadir;
                $row->alfa = $alfa;
                $row->sakit = $sakit;
                $row->izin = $izin;
            }
        } else if ($request->bulanan == "" && $request->tanggal_awal == "" && $request->tanggal_akhir == "" && $request->hari_ini == "1") {
            $query = DB::table('absen_alls as a')
                ->join('students as b', 'a.student_unique', '=', 'b.unique')
                ->select('a.student_unique')
                ->where('a.student_kelas', $request->unique_kelas)
                ->where('a.tanggal_absen', date('Y-m-d', strtotime(Carbon::now())))
                ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                ->distinct()
                ->groupBy('a.student_unique')
                ->get();
            //QUERY
            foreach ($query as $row) {
                $student = Student::where('unique', $row->student_unique)->first();
                $hadir = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('b.nama', 'a.*')
                    ->where('a.student_unique', $row->student_unique)
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tanggal_absen', date('Y-m-d', strtotime(Carbon::now())))
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->where('a.kehadiran', 'H')
                    ->count('a.kehadiran');
                $sakit = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('b.nama', 'a.*')
                    ->where('a.student_unique', $row->student_unique)
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tanggal_absen', date('Y-m-d', strtotime(Carbon::now())))
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->where('a.kehadiran', 'S')
                    ->count('a.kehadiran');
                $izin = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('b.nama', 'a.*')
                    ->where('a.student_unique', $row->student_unique)
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tanggal_absen', date('Y-m-d', strtotime(Carbon::now())))
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->where('a.kehadiran', 'I')
                    ->count('a.kehadiran');
                $alfa = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('b.nama', 'a.*')
                    ->where('a.student_unique', $row->student_unique)
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tanggal_absen', date('Y-m-d', strtotime(Carbon::now())))
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->where('a.kehadiran', 'A')
                    ->count('a.kehadiran');
                $row->nama = $student->nama;
                $row->hadir = $hadir;
                $row->alfa = $alfa;
                $row->sakit = $sakit;
                $row->izin = $izin;
            }
        } else if ($request->tanggal_awal == "" && $request->tanggal_awal == "" && $request->bulanan == "" && $request->hari_ini == "0") {
            if ($request->bulanan == "ALL") {
                $query = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('a.student_unique')
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->distinct()
                    ->groupBy('a.student_unique')
                    ->get();
                //QUERY
                foreach ($query as $row) {
                    $student = Student::where('unique', $row->student_unique)->first();
                    $hadir = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'H')
                        ->count('a.kehadiran');
                    $sakit = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'S')
                        ->count('a.kehadiran');
                    $izin = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'I')
                        ->count('a.kehadiran');
                    $alfa = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'A')
                        ->count('a.kehadiran');
                    $row->nama = $student->nama;
                    $row->hadir = $hadir;
                    $row->alfa = $alfa;
                    $row->sakit = $sakit;
                    $row->izin = $izin;
                }
            } else {
                $query = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->select('a.student_unique')
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where(DB::raw("DATE_FORMAT(a.tanggal_absen,'%m')"), $request->bulanan)
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->distinct()
                    ->groupBy('a.student_unique')
                    ->get();
                //QUERY
                foreach ($query as $row) {
                    $student = Student::where('unique', $row->student_unique)->first();
                    $hadir = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where(DB::raw("DATE_FORMAT(a.tanggal_absen,'%m')"), $request->bulanan)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'H')
                        ->count('a.kehadiran');
                    $sakit = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where(DB::raw("DATE_FORMAT(a.tanggal_absen,'%m')"), $request->bulanan)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'S')
                        ->count('a.kehadiran');
                    $izin = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where(DB::raw("DATE_FORMAT(a.tanggal_absen,'%m')"), $request->bulanan)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'I')
                        ->count('a.kehadiran');
                    $alfa = DB::table('absen_alls as a')
                        ->join('students as b', 'a.student_unique', '=', 'b.unique')
                        ->select('b.nama', 'a.*')
                        ->where('a.student_unique', $row->student_unique)
                        ->where('a.student_kelas', $request->unique_kelas)
                        ->where(DB::raw("DATE_FORMAT(a.tanggal_absen,'%m')"), $request->bulanan)
                        ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                        ->where('a.kehadiran', 'A')
                        ->count('a.kehadiran');
                    $row->nama = $student->nama;
                    $row->hadir = $hadir;
                    $row->alfa = $alfa;
                    $row->sakit = $sakit;
                    $row->izin = $izin;
                }
            }
        }
        return DataTables::of($query)->make(true);
    }

    public function generatePDF()
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'Hello, World!');

        // Output to browser
        $pdfContent = $pdf->Output('', 'S'); // Get PDF content as string

        return response($pdfContent)->header('Content-Type', 'application/pdf');
    }

    public function getPDF()
    {
        $pdf = $this->generatePDF();
        return response($pdf)->header('Content-Type', 'application/pdf');
    }
}
