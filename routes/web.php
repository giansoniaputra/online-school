<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MatpelController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AbsenAllController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\HistoriKelasController;
use App\Http\Controllers\TagihanSiswaController;
use App\Http\Controllers\SettingTagihanController;
use App\Http\Controllers\GenerateTagihanController;
use App\Http\Controllers\JenisPembayaranController;
use App\Http\Controllers\LaporanPresensiController;

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

Route::get('/', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/home', [DashboardController::class, 'index'])->middleware('auth');
Route::resource("/user", AuthController::class)->middleware('auth');
Route::post('/authenticate', [AuthController::class, 'authenticate']);
Route::get('/auth', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::get('/register', [AuthController::class, 'register'])->middleware('auth');
Route::post('/registerUser', [AuthController::class, 'register_user'])->middleware('auth');
Route::get('/logout', [AuthController::class, 'logout']);

//SISWA
Route::resource('/student', StudentController::class)->middleware('auth');
Route::get('/historiKelas', [StudentController::class, 'cek_histori_kelas'])->middleware('auth');
//GURU
Route::resource('/teacher', TeacherController::class)->middleware('auth');
//Refresh Ampuan
Route::get('/refresh_ampuan', [TeacherController::class, 'refresh_ampuan'])->middleware('auth');
//Tambah dan Hapus Ampuan
Route::get('/tambah_ampuan', [TeacherController::class, 'tambah_ampuan'])->middleware('auth');
Route::get('/hapus_ampuan', [TeacherController::class, 'hapus_ampuan'])->middleware('auth');

//MATA PELAJARAN
Route::resource('/matpel', MatpelController::class)->middleware('auth');

// WALI KELAS
Route::resource('/wali_kelas', WaliKelasController::class)->middleware('auth');
Route::get('/getWali', [WaliKelasController::class, 'get_wali'])->middleware('auth');
Route::POST('/updateWaliKelas', [WaliKelasController::class, 'update_perwalian'])->middleware('auth');

//ABSEN
Route::resource('/absen', AbsenController::class)->middleware('auth');
Route::get('/inputAbsen', [AbsenController::class, 'input_absen'])->middleware('auth');
Route::get('/absenHadir', [AbsenController::class, 'absen_hadir'])->middleware('auth');
Route::get('/absenSakit', [AbsenController::class, 'absen_sakit'])->middleware('auth');
Route::get('/absenIzin', [AbsenController::class, 'absen_izin'])->middleware('auth');
Route::get('/absenAlfa', [AbsenController::class, 'absen_alfa'])->middleware('auth');
//ambil data BAP
Route::get('/getCurrentBAP', [AbsenController::class, 'get_current_BAP'])->middleware('auth');
Route::get('/getAllClass', [AbsenController::class, 'get_kelas'])->middleware('auth');
Route::get('/deletBAP', [AbsenController::class, 'deleteBAP'])->middleware('auth');

//TAHUN AJARAN
Route::resource('/tahun_ajaran', TahunAjaranController::class)->middleware('auth');
Route::get('/changeTahunAjaran', [TahunAjaranController::class, 'tahun_aktif'])->middleware('auth');
Route::get('/refreshTahunAjaran', [TahunAjaranController::class, 'refresh_tahun_aktif'])->middleware('auth');

//MATA PELAJARAN
Route::resource('/kelas', KelasController::class)->middleware('auth');

//ABSEN ALL
Route::resource('/absen_all', AbsenAllController::class)->middleware('auth');
Route::get('/inputAbsenAll', [AbsenAllController::class, 'input_absen'])->middleware('auth');
Route::get('/hadirAll', [AbsenAllController::class, 'absen_hadir'])->middleware('auth');
Route::get('/sakitAll', [AbsenAllController::class, 'absen_sakit'])->middleware('auth');
Route::get('/izinAll', [AbsenAllController::class, 'absen_izin'])->middleware('auth');
Route::get('/alfaAll', [AbsenAllController::class, 'absen_alfa'])->middleware('auth');
Route::get('/hadirSemua', [AbsenAllController::class, 'hadir_semua'])->middleware('auth');

//ROLES
Route::resource('/roles', RoleController::class)->middleware('auth');

//JENIS PEMBAYARAN
Route::resource('/jenis_pembayaran', JenisPembayaranController::class)->middleware('auth');

//GENERATE TAGIHAN
Route::resource('/generate_tagihan', GenerateTagihanController::class)->middleware('auth');
Route::get('/cekSettingTagihan', [GenerateTagihanController::class, 'cek_setting_tagihan'])->middleware('auth');
Route::get('/generateTagihan', [GenerateTagihanController::class, 'generate_tagihan'])->middleware('auth');

//TAGIHAN SISWA
Route::resource('/tagihan_siswa', TagihanSiswaController::class)->middleware('auth');
Route::get('/pilihSiswa', [TagihanSiswaController::class, 'pilih_siswa'])->middleware('auth');
Route::get('/getDataTagihan', [TagihanSiswaController::class, 'get_data_tagihan'])->middleware('auth');
Route::get('/getDataTagihanLunas', [TagihanSiswaController::class, 'get_data_tagihan_lunas'])->middleware('auth');
Route::get('/cekCurrentTagihan', [TagihanSiswaController::class, 'cek_tagihan_terbayar'])->middleware('auth');

//SETTING TAGIHAN
Route::resource('/setting_tagihan', SettingTagihanController::class)->middleware('auth');
Route::get('/updateNominal', [SettingTagihanController::class, 'update_nominal'])->middleware('auth');
Route::get('/cekDataTagihan', [SettingTagihanController::class, 'cek_data_tagihan'])->middleware('auth');
Route::post('/settingTagihan', [SettingTagihanController::class, 'setting_tagihan'])->middleware('auth');
Route::get('/cariSiswa', [SettingTagihanController::class, 'cari_siswa'])->middleware('auth');

//HISTORI KELAS
Route::resource('/histori-kelas', HistoriKelasController::class)->middleware('auth');
Route::get('/getKelas/{kelas:unique}', [HistoriKelasController::class, 'get_kelas'])->middleware('auth');
Route::get('/naikKelas', [HistoriKelasController::class, 'naik_kelas'])->middleware('auth');
Route::get('/getSiswa', [HistoriKelasController::class, 'get_siswa'])->middleware('auth');

// LAPORAN PRESENSI
Route::get('/laporan', [LaporanPresensiController::class, 'index'])->middleware('auth');
Route::get('/getKelasLaporan/{unique_tahun_ajaran}', [LaporanPresensiController::class, 'get_kelas'])->middleware('auth');
Route::post('/get-laporan', [LaporanPresensiController::class, 'getPDF']);

//DATATABLES
Route::get('/datatablesSiswa', [StudentController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesAbsen', [AbsenController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesAbsenAll', [AbsenAllController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesGuru', [TeacherController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesMatpel', [MatpelController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesBAP', [AbsenController::class, 'dataTablesBAP'])->middleware('auth');
Route::get('/datatablesTahunAjaran', [TahunAjaranController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesKelas', [KelasController::class, 'dataTables'])->middleware('auth');
Route::get('/dataTablesUser', [AuthController::class, 'dataTables'])->middleware('auth');
Route::get('/dataTablesRoles', [RoleController::class, 'dataTables'])->middleware('auth');
Route::get('/dataTablesJenisPembayaran', [JenisPembayaranController::class, 'dataTables'])->middleware('auth');
Route::get('/dataTablesSettingTagihan', [SettingTagihanController::class, 'dataTables'])->middleware('auth');
Route::get('/dataTablesListTagihan', [GenerateTagihanController::class, 'dataTables_list_tagihan'])->middleware('auth');
Route::get('/dataTablesTagihanSiswaGenerate', [GenerateTagihanController::class, 'dataTables_tagihan_siswa_generate'])->middleware('auth');
Route::get('/datatablesLaporanTahunAjaran', [LaporanPresensiController::class, 'dataTables'])->middleware('auth');
Route::get('/datatablesLaporanPresensiAll', [LaporanPresensiController::class, 'dataTables_laporan_all'])->middleware('auth');


Route::get('/test', [LaporanPresensiController::class, 'laporan']);

// Route::get('/generate-pdf', [LaporanPresensiController::class, 'generatePDF']);
