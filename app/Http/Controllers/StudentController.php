<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absen;
use App\Models\HistoriKelas;
use App\Models\Kelas;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'Siswa',
            'title' => 'Data Siswa',
            'kelas' => Kelas::all(),
        ];
        return view('student.index', $data);
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
            'nisn' => 'unique:students',
            'nis' => 'required|unique:students',
            'nama' => 'required',
            'kelas' => 'required',
            'telepon_ortu' => 'required',
        ];
        $pesan = [
            'nisn.unique' => 'Data NISN sudah terdaftar',
            'nis.required' => 'NIS tidak boleh kosong',
            'nis.unique' => 'Data nis sudah terdaftar',
            'nama.required' => 'Nama Siswa tidak boleh kosong',
            'kelas.required' => 'Kelas tidak boleh kosong',
            'telepon_ortu.required' => 'Nomor telepon orang tua tidak boleh kosong'
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'asal_sekolah' => $request->asal_sekolah,
                'telepon_ortu' => $request->telepon_ortu,
                'agama' => $request->agama,
                'ayah' => $request->ayah,
                'ibu' => $request->ibu,
                'wali' => $request->wali,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'pekerjaan_wali' => $request->pekerjaan_wali,
                'nama' => ucwords(strtolower($request->nama)),
                'kelas' => $request->kelas,
                'role' => 'SISWA',
            ];
            $data_user = [
                'unique' => Str::orderedUuid(),
                'username' => $request->nis,
                'password' => bcrypt($request->nis),
                'nama' => ucwords(strtolower($request->nama)),
                'role' => 'SISWA',
            ];
            //Masukan Data Murid
            Student::create($data);
            // Masukan Data User Murid
            User::create($data_user);
            $last = Student::latest()->first();
            $data_histori_kelas = [
                'unique' => Str::orderedUuid(),
                'unique_student' => $last->unique,
                'unique_kelas' => $request->kelas,
            ];
            HistoriKelas::create($data_histori_kelas);
            return response()->json(['success' => 'Data Berhasi Disimpan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return response()->json(['data' => $student]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $rules = [
            'nama' => 'required',
            'kelas' => 'required',
            'telepon_ortu' => 'required',
        ];
        $pesan = [
            'nama.required' => 'Nama Siswa tidak boleh kosong',
            'kelas.required' => 'Kelas tidak boleh kosong',
            'telepon_ortu.required' => 'Kelas tidak boleh kosong'
        ];

        if ($student->nis == $request->nis) {
            $rules["nis"] = 'required';
            $pesan["nis.required"] = 'NIS tidak boleh kosong';
        } else {
            $rules["nis"] = 'required|unique:students';
            $pesan["nis.required"] = 'NIS tidak boleh kosong';
            $pesan["nis.unique"] = 'Data nis sudah terdaftar';
        }

        if ($student->nisn == $request->nisn) {
            $rules["nisn"] = 'required';
            $pesan["nisn.required"] = 'NISN tidak boleh kosong';
        } else {
            $rules["nisn"] = 'required|unique:students';
            $pesan["nisn.required"] = 'NISN tidak boleh kosong';
            $pesan["nisn.unique"] = 'Data NISN sudah terdaftar';
        }
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'asal_sekolah' => $request->asal_sekolah,
                'telepon_ortu' => $request->telepon_ortu,
                'agama' => $request->agama,
                'ayah' => $request->ayah,
                'ibu' => $request->ibu,
                'wali' => $request->wali,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'pekerjaan_wali' => $request->pekerjaan_wali,
                'nama' => ucwords(strtolower($request->nama)),
                'kelas' => $request->kelas,
                'role' => 'SISWA',
            ];
            $data_user = [
                'username' => $request->nis,
                'password' => bcrypt($request->nis),
                'nama' => ucwords(strtolower($request->nama)),
                'role' => 'SISWA',
            ];
            User::where('username', $student->nis)->update($data_user);
            HistoriKelas::where('unique_student', $student->unique)
                ->where('unique_kelas', $student->kelas)
                ->update(['unique_kelas' => $request->kelas]);
            Student::where('unique', $student->unique)->update($data);
            return response()->json(['success' => 'Data Berhasi Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $cek = Absen::where('student_unique', $student->unique)->first();
        if ($cek) {
            return response()->json(['errors' => 'Data Siswa Tidak Bisa Dihapus']);
        } else {
            Student::where('unique', $student->unique)->delete();
            HistoriKelas::where('unique_student', $student->unique)->delete();
            User::where('username', $student->nis)->delete();
            return response()->json(['success' => 'Data Berhasi Dihapus']);
        }
    }

    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            if ($request->matpel == "ALL") {
                $query = DB::table('students as a')
                    ->join('kelas as b', 'a.kelas', '=', 'b.unique')
                    ->select('a.*', 'b.unique as kelas_unique', 'b.kelas as kelas2', 'b.huruf')
                    ->get();
            } else {
                $query = DB::table('students as a')
                    ->join('kelas as b', 'a.kelas', '=', 'b.unique')
                    ->select('a.*', 'b.unique as kelas_unique', 'b.kelas as kelas2', 'b.huruf')
                    ->where('b.unique', $request->matpel)
                    ->get();
            }
            foreach ($query as $row) {
                $row->kelas2 = $row->kelas2 . $row->huruf;
            }
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '
                    <button class="btn btn-rounded btn-sm btn-info text-white info-siswa-button" title="Detail Siswa" data-unique="' . $row->unique . '" data-nama="' . $row->nama . '"><i class="ri-eye-line"></i></button>
                    <button class="btn btn-rounded btn-sm btn-info text-white histori-siswa-button" title="Hsitori Kelas" data-unique="' . $row->unique . '" data-nama="' . $row->nama . '"><i class=" ri-history-line"></i></button>
                    <button class="btn btn-rounded btn-sm btn-warning text-dark edit-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '"><i class="ri-edit-line"></i></button>
                    <button class="btn btn-rounded btn-sm btn-danger text-white hapus-siswa-button" title="Hapus Siswa" data-unique="' . $row->unique . '" data-token="' . csrf_token() . '"><i class="ri-delete-bin-line"></i></button>';
                return $actionBtn;
            })->make(true);
        }
    }

    public function cek_histori_kelas(Request $request)
    {
        $histori = DB::table('histori_kelas as a')
            ->join('kelas as b', 'a.unique_kelas', 'b.unique')
            ->where('unique_student', $request->unique)->get();
        return response()->json(['data' => $histori]);
    }
}
