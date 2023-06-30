<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
        ];
        $pesan = [
            'nisn.unique' => 'Data NISN sudah terdaftar',
            'nis.required' => 'NIS tidak boleh kosong',
            'nis.unique' => 'Data nis sudah terdaftar',
            'nama.required' => 'Nama Siswa tidak boleh kosong',
            'kelas.required' => 'Kelas tidak boleh kosong'
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
            Student::create($data);
            User::create($data_user);
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
        ];
        $pesan = [
            'nama.required' => 'Nama Siswa tidak boleh kosong',
            'kelas.required' => 'Kelas tidak boleh kosong'
        ];

        if ($student->nis == $request->nis) {
            $rules["nis"] = 'required';
            $pesan["nis.required"] = 'NIS tidak boleh kosong';
        } else {
            $rules["nis"] = 'required|unique:students';
            $pesan["nis.required"] = 'NIS tidak boleh kosong';
            $pesan["nis.unique"] = 'Data nis sudah terdaftar';
        }

        if ($student->nisn !== $request->nisn) {
            $rules["nisn"] = 'unique:students';
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
            Student::where('unique', $student->unique)->update($data);
            return response()->json(['success' => 'Data Berhasi Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        Student::where('unique', $student->unique)->delete();
        User::where('username', $student->nis)->delete();
        return response()->json(['success' => 'Data Berhasi Dihapus']);
    }

    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = Student::all();
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '
                    <button class="btn btn-rounded btn-sm btn-info text-white info-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">Detail</button>
                    <button class="btn btn-rounded btn-sm btn-warning text-dark edit-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '">Edit</button>
                    <button class="btn btn-rounded btn-sm btn-danger text-white hapus-siswa-button" title="Edit Siswa" data-unique="' . $row->unique . '" data-token="' . csrf_token() . '">Hapus</button>';
                return $actionBtn;
            })->make(true);
        }
    }
}
