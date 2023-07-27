<?php

use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MatpelController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TahunAjaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $data = [
        'title_page' => 'Dashborad',
        'title' => 'Dashborad',
    ];
    return view('dashboard.index', $data);
})->middleware('auth');
Route::get('/home', function () {
    $data = [
        'title_page' => 'Dashborad',
        'title' => 'Dashborad',
    ];
    return view('dashboard.index', $data);
})->middleware('auth');

Route::post('/authenticate', [AuthController::class, 'authenticate']);
Route::get('/auth', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout']);

//SISWA
Route::resource('/student', StudentController::class)->middleware('auth');
//GURU
Route::resource('/teacher', TeacherController::class)->middleware('auth');
//Refresh Ampuan
Route::get('/refresh_ampuan', [TeacherController::class, 'refresh_ampuan'])->middleware('auth');
//Tambah dan Hapus Ampuan
Route::get('/tambah_ampuan', [TeacherController::class, 'tambah_ampuan'])->middleware('auth');
Route::get('/hapus_ampuan', [TeacherController::class, 'hapus_ampuan'])->middleware('auth');

//MATA PELAJARAN
Route::resource('/matpel', MatpelController::class)->middleware('auth');

//ABSEN
Route::resource('/absen', AbsenController::class)->middleware('auth');
//ambil data BAP
Route::get('/getCurrentBAP', [AbsenController::class, 'get_current_BAP'])->middleware('auth');
Route::get('/deletBAP', [AbsenController::class, 'deleteBAP'])->middleware('auth');

//TAHUN AJARAN
Route::resource('/tahun_ajaran', TahunAjaranController::class)->middleware('auth');
Route::get('/changeTahunAjaran', [TahunAjaranController::class, 'tahun_aktif'])->middleware('auth');
Route::get('/refreshTahunAjaran', [TahunAjaranController::class, 'refresh_tahun_aktif'])->middleware('auth');

//MATA PELAJARAN
Route::resource('/kelas', KelasController::class)->middleware('auth');


//DATATABLES
Route::get('/datatablesSiswa', [StudentController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesAbsen', [AbsenController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesGuru', [TeacherController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesMatpel', [MatpelController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesBAP', [AbsenController::class, 'dataTablesBAP'])->middleware('auth');
Route::get('/datatablesTahunAjaran', [TahunAjaranController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesKelas', [KelasController::class, 'dataTables'])->middleware('auth');
