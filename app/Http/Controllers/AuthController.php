<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.index');
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $cek = User::where('role', 'ADMIN')->count('id');
        if ($cek == 1) {
            return response()->json(['errors' => 'Data Tidak Bisa Dihapus']);
        } else {
            User::where('unique', $user->unique)->delete();
            return response()->json(['success' => "Data User Berhasil Dihapus"]);
        }
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->with('error', 'Username atau Password Salah');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth');
    }

    public function register(Request $request)
    {
        $data = [
            'title_page' => 'User',
            'title' => 'Data User',
        ];
        return view('auth.register', $data);
    }

    public function register_user(Request $request)
    {
        $rules = [
            'username' => 'required|min:7',
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:7',
            'password_confirmation' => 'required',
        ];
        $pesan = [
            'username.required' => 'Username Tidak Boleh Kosong',
            'username.min' => 'Username Minimal 7 Character',
            'nama.required' => 'Nama Tidak Boleh Kosong',
            'email.required' => 'Email Tidak boleh kosong',
            'email.email' => 'Email Harus Valid',
            'email.unique' => 'Email Sudah Terdaftar',
            'password.required' => 'Password Tidak Boleh Kosong',
            'password.confirmed' => 'Password Tidak Sesuai/Sama',
            'password.min' => 'Password Minimal 7 Karakter',
            'password_confirmation.required' => 'Konfirmasi Password Tidak Boleh Kosong',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $data = [
                'unique' => Str::orderedUuid(),
                'username' => $request->username,
                'nama' => ucwords(strtolower($request->nama)),
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => "ADMIN",
            ];
            User::create($data);
            return response()->json(['success' => "Data User Berhasil Ditambahakan"]);
        }
    }


    public function dataTables(Request $request)
    {
        $query = DB::table("users")
            ->where("role", "!=", "GURU")
            ->where("role", "!=", "SISWA")
            ->get();
        return DataTables::of($query)->addColumn('action', function ($row) {
            $actionBtn =
                '
                <button class="btn btn-rounded btn-sm btn-warning text-dark edit-button" title="Edit Data" data-unique="' . $row->unique . '"><i class="fas fa-edit"></i></button>
                <button class="btn btn-rounded btn-sm btn-danger text-white delete-button" title="Hapus Data" data-unique="' . $row->unique . '" data-token="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></button>';
            return $actionBtn;
        })->make(true);
    }
}
