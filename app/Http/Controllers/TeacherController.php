<?php

namespace App\Http\Controllers;

use App\Models\BAP;
use App\Models\User;
use App\Models\Ampuan;
use App\Models\Matpel;
use App\Models\Teacher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'Guru',
            'title' => 'Data Guru',
            'matpels' => Matpel::all(),
        ];
        return view('teacher.index', $data);
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
            'npk' => 'required|unique:teachers',
            'nama_guru' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ];
        $pesan = [
            'npk.required' => 'NPK tidak boleh kosong',
            'npk.unique' => 'NPK sudah terdaftar',
            'nama_guru.required' => 'Nama Guru tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'telepon.required' => 'Nomor telepon tidak boleh kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'npk' => $request->npk,
                'nama_guru' => $request->nama_guru,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'role' => "GURU",
            ];
            $data_user = [
                'unique' => Str::orderedUuid(),
                'username' => $request->npk,
                'password' => bcrypt($request->npk),
                'nama' => ucwords(strtolower($request->nama_guru)),
                'role' => 'GURU',
            ];
            Teacher::create($data);
            User::create($data_user);
            return response()->json(['success' => "Data Berhasil Ditambahkan"]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        return response()->json(['data' => $teacher]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $rules = [
            'nama_guru' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ];
        $pesan = [
            'nama_guru.required' => 'Nama Guru tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'telepon.required' => 'Nomor telepon tidak boleh kosong',
        ];
        if ($teacher->npk == $request->npk) {
            $rules["npk"] = 'required';
            $pesan["npk.required"] = 'NPK tidak boleh kosong';
        } else {
            $rules["npk"] = 'required|unique:teachers';
            $pesan["npk.required"] = 'NPK tidak boleh kosong';
            $pesan["npk.unique"] = 'Data NPK sudah terdaftar';
        }
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'npk' => $request->npk,
                'nama_guru' => $request->nama_guru,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
            ];
            $data_user = [
                'username' => $request->npk,
                'password' => bcrypt($request->npk),
                'nama' => ucwords(strtolower($request->nama_guru)),
            ];
            User::where('username', $teacher->npk)->update($data_user);
            Teacher::where('unique', $teacher->unique)->update($data);
            return response()->json(['success' => "Data Berhasil Diubah"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $cek = BAP::where('guru_unique', $teacher->unique)->first();
        if ($cek) {
            return response()->json(['errors' => "Data Tidak Bisa Dihapus"]);
        } else {
            Teacher::where('unique', $teacher->unique)->delete();
            return response()->json(['success' => "Data Berhasil Dihapus"]);
        }
    }

    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = Teacher::all();
            return DataTables::of($query)->addColumn('action', function ($row) {
                $actionBtn =
                    '
                    <button class="btn btn-rounded btn-sm btn-info text-white ampu-guru-button" title="Edit guru" data-unique="' . $row->unique . '">Ampuan</button>
                    <button class="btn btn-rounded btn-sm btn-warning text-dark edit-guru-button" title="Edit guru" data-unique="' . $row->unique . '">Edit</button>
                    <button class="btn btn-rounded btn-sm btn-danger text-white hapus-guru-button" title="Edit guru" data-unique="' . $row->unique . '" data-token="' . csrf_token() . '">Hapus</button>';
                return $actionBtn;
            })->make(true);
        }
    }

    public function refresh_ampuan(Request $request)
    {
        $query = DB::table('ampuans as a')
            ->join('matpels as b', 'a.matpel_unique', '=', 'b.unique')
            ->where('a.teacher_unique', $request->unique)->get();
        foreach ($query as $row) {
            echo '
                <button type="button" class="btn btn-primary">' . $row->nama_matpel . ' - ' . $row->kelas . '</button>
            ';
        }
    }

    public function tambah_ampuan(Request $request)
    {
        $cek = Ampuan::where('matpel_unique', $request->unique_matpel)
            ->where('teacher_unique', $request->unique_guru)->first();
        if (!$cek) {
            $data = [
                'unique' => Str::orderedUuid(),
                'matpel_unique' => $request->unique_matpel,
                'teacher_unique' => $request->unique_guru,
            ];
            Ampuan::create($data);
        }
        return response()->json(['success' => "Data Berhasil Dihapus"]);
    }
    public function hapus_ampuan(Request $request)
    {
        $cek = Ampuan::where('matpel_unique', $request->unique_matpel)
            ->where('teacher_unique', $request->unique_guru)->first();
        if ($cek) {
            Ampuan::where('matpel_unique', $request->unique_matpel)
                ->where('teacher_unique', $request->unique_guru)->delete();
        }
        return response()->json(['success' => "Data Berhasil Dihapus"]);
    }
}
