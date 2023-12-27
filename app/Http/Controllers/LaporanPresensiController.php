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

class LaporanPresensiController extends Controller
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

    public function getPDF(Request $request)
    {
        // return $request->all();
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $bulanan = $request->bulanan;
        $unique_tahun_ajaran = $request->unique_tahun_ajaran;
        $unique_kelas = $request->unique_kelas;
        $hari_ini = $request->hari_ini;
        $pdf = new FPDF();
        $pdf->AddPage('P', 'A4');
        //Header
        $pdf->SetFont('Arial', 'B', 13);
        $pdf->Cell(30);
        $pdf->Cell(140, 5, 'SEKOLAH MENENGAH PERTAMA', 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(30);
        $pdf->Cell(140, 9, 'PERSIS GANDOK', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0);
        $pdf->Cell(30);
        $pdf->Cell(140, 5, strtoupper('jl gandok'), 0, 1, 'C');

        // Menambahkan garis header
        $pdf->SetLineWidth(1);
        $pdf->Line(10, 36, 200, 36);
        $pdf->SetLineWidth(0);
        $pdf->Line(10, 37, 200, 37);
        $pdf->Ln();

        $cek_tahun = TahunAjaran::where('unique', $unique_tahun_ajaran)->first();
        $cek_kelas = Kelas::where('unique', $unique_kelas)->first();
        if ($request->bulanan != "") {
            if ($bulanan == "ALL") {
                $query = DB::table('absen_alls as a')
                    ->join('students as b', 'a.student_unique', '=', 'b.unique')
                    ->join('tahun_ajarans as c', 'a.tahun_ajaran_unique', '=', 'c.unique')
                    ->select('a.student_unique')
                    ->where('a.student_kelas', $request->unique_kelas)
                    ->where('a.tahun_ajaran_unique', $request->unique_tahun_ajaran)
                    ->distinct()
                    ->groupBy('a.student_unique')
                    ->get();
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
                $judul = "Periode  $cek_tahun->tahun_awal/$cek_tahun->tahun_akhir $cek_tahun->periode";
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

                if ($bulanan == 1) {
                    $namaBulan = "Januari";
                } elseif ($bulanan == 2) {
                    $namaBulan = "Februari";
                } elseif ($bulanan == 3) {
                    $namaBulan = "Maret";
                } elseif ($bulanan == 4) {
                    $namaBulan = "April";
                } elseif ($bulanan == 5) {
                    $namaBulan = "Mei";
                } elseif ($bulanan == 6) {
                    $namaBulan = "Juni";
                } elseif ($bulanan == 7) {
                    $namaBulan = "Juli";
                } elseif ($bulanan == 8) {
                    $namaBulan = "Agustus";
                } elseif ($bulanan == 9) {
                    $namaBulan = "September";
                } elseif ($bulanan == 10) {
                    $namaBulan = "Oktober";
                } elseif ($bulanan == 11) {
                    $namaBulan = "November";
                } elseif ($bulanan == 12) {
                    $namaBulan = "Desember";
                }
                $judul = "Bulan $namaBulan Periode $cek_tahun->tahun_awal/$cek_tahun->tahun_akhir $cek_tahun->periode";
            }
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(0, 16, "Laporan Presensi $judul", '0', 1, 'C');
            $pdf->Cell(0, 2, "Kelas $cek_kelas->kelas$cek_kelas->huruf", '0', 1, 'C');
            $pdf->Ln();
            $pdf->Ln();
        } else if ($request->tanggal_awal != null) {
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
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(0, 16, "Laporan Presensi " . tanggal_hari($tanggal_awal) . " - " . tanggal_hari($tanggal_akhir), '0', 1, 'C');
            $pdf->Cell(0, 2, "Kelas $cek_kelas->kelas$cek_kelas->huruf", '0', 1, 'C');
            $pdf->Ln();
            $pdf->Ln();
        } else if ($request->hari_ini != '0') {
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
            $pdf->SetFont('Arial', 'B', '14');
            $pdf->Cell(0, 16, "Laporan Presensi " . tanggal_hari(date('Y-m-d', strtotime(Carbon::now()))), '0', 1, 'C');
            $pdf->Cell(0, 2, "Kelas $cek_kelas->kelas$cek_kelas->huruf", '0', 1, 'C');
            $pdf->Ln();
            $pdf->Ln();
        }

        //Membuat kolom judul tabel
        $pdf->SetFont('Arial', '', '8');
        $pdf->SetFillColor(9, 132, 227);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(8, 7, 'No', 1, '0', 'C', true);
        $pdf->Cell(59, 7, 'Nama Siswa', 1, '0', 'C', true);
        $pdf->Cell(40, 7, 'Hadir', 1, '0', 'C', true);
        $pdf->Cell(29, 7, 'Izin', 1, '0', 'C', true);
        $pdf->Cell(29, 7, 'Sakit', 1, '0', 'C', true);
        $pdf->Cell(27, 7, 'Alfa', 1, '0', 'C', true);
        $pdf->Ln();

        //isi data cash
        //Membuat kolom isi tabel
        $pdf->SetFont('Arial', '', '8');
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetTextColor(0);
        $no = 1;
        foreach ($query as $row) {
            $pdf->Cell(8, 7, $no++, 1, '0', 'C', true);
            $pdf->Cell(59, 7, $row->nama, 1, '0', 'C', true);
            $pdf->Cell(40, 7, $row->hadir, 1, '0', 'C', true);
            $pdf->Cell(29, 7, $row->izin, 1, '0', 'C', true);
            $pdf->Cell(29, 7, $row->sakit, 1, '0', 'C', true);
            $pdf->Cell(27, 7, $row->alfa, 1, '0', 'C', true);
            $pdf->Ln();
        }



        // Output to browser
        $pdf->Output('Laporan Penjualan Presensi.pdf', 'I');
        exit;
    }
}
