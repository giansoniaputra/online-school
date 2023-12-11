<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Models\HistoriKelas;
use Illuminate\Http\Request;

class HistoriKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title_page' => 'Kelas',
            'title' => 'Histori Kelas',
            'kelas7' => Kelas::where('kelas', 7)->get(),
            'kelas8' => Kelas::where('kelas', 8)->get(),
            'kelas9' => Kelas::where('kelas', 9)->get(),
        ];
        return view('histori_kelas.index', $data);
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
    public function show(HistoriKelas $historiKelas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HistoriKelas $historiKelas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HistoriKelas $historiKelas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HistoriKelas $historiKelas)
    {
        //
    }

    public function get_kelas(Kelas $kelas)
    {
        $cek = Kelas::where('kelas', '>', $kelas->kelas)->get();
        return response()->json(['success' => $cek]);
    }

    public function get_siswa(Request $request)
    {
        $siswas = explode('/', $request->siswas);
        $list_siswa = '';
        array_pop($siswas);
        foreach ($siswas as $siswa) {
            $query = Student::where('unique', $siswa)->first();
            $list_siswa .= '<p class="text-start">- &nbsp' . $query->nama . '</p>';
        }
        return response()->json(['data' => $list_siswa]);
    }

    public function naik_kelas(Request $request)
    {
        $siswas = explode('/', $request->siswas);
        $jumlah_siswa = 0;
        array_pop($siswas);
        foreach ($siswas as $siswa) {
            Student::where('unique', $siswa)->update(['kelas' => $request->kelas_baru]);
            HistoriKelas::create([
                'unique' => Str::orderedUuid(),
                'unique_student' => $siswa,
                'unique_kelas' => $request->kelas_baru,
            ]);
            $jumlah_siswa++;
        }
        return response()->json(['success' => $jumlah_siswa . ' Siswa Berhasil Naik Kelas']);
    }
}
