<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\MatpelController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

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
});
//SISWA
Route::resource('/student', StudentController::class);
//GURU
Route::resource('/teacher', TeacherController::class);
//MATA PELAJARAN
Route::resource('/matpel', MatpelController::class);
//ABSEN
Route::resource('/absen', AbsenController::class);

//DATATABLES
Route::get('/datatablesSiswa', [StudentController::class, 'dataTables']);
Route::get('/datatablesAbsen', [AbsenController::class, 'dataTables']);
Route::get('/datatablesGuru', [TeacherController::class, 'dataTables']);
Route::get('/datatablesMatpel', [MatpelController::class, 'dataTables']);
