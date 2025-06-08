<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\{
    DashboardController,
    LoginController,
};

use App\Http\Controllers\admin\{
    BarangMasukController,
    BarangKeluarController,
    ProfilAdminController,
    SatuanController,
    SupplierController,
    LaporanController,
    MasterAkunPemilikController,    
    DataBarangKeluarController,
};
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


Route::get('/run-admin', function () {
    Artisan::call('db:seed', [
        '--class' => 'SuperAdminSeeder'
    ]);

    return "AdminSeeder has been create successfully!";
});
Route::get('/', [LoginController::class, 'showLoginForm'])->name('formlogin');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['role:admin']], function () {
    Route::resource('satuan', SatuanController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('barang_masuk', BarangMasukController::class);
    Route::resource('barang_keluar', BarangKeluarController::class);
    Route::resource('master_akun_pemilik', MasterAkunPemilikController::class);
});

Route::group(['middleware' => ['role:admin,pemilik_toko']], function () {
    Route::get('/profil-admin', [ProfilAdminController::class, 'index'])->name('admin.profil_admin');
    Route::put('/profil-admin', [ProfilAdminController::class, 'update'])->name('admin.update_profil_admin');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/barang_masuk', [LaporanController::class, 'laporan_semua_barang_masuk'])->name('laporan.barang_masuk');
    Route::get('/laporan/barang_keluar', [LaporanController::class, 'laporan_semua_barang_keluar'])->name('laporan.barang_keluar');
    Route::get('/laporan/barang_masuk_per_bulan', [LaporanController::class, 'laporan_barang_masuk_per_bulan'])->name('laporan.barang_masuk_per_bulan');
    Route::get('/laporan/barang_keluar_per_bulan', [LaporanController::class, 'laporan_barang_keluar_per_bulan'])->name('laporan.barang_keluar_per_bulan');
    Route::get('/laporan/stok_sebelum_seminggu', [LaporanController::class, 'laporan_stok_sebelum_seminggu'])->name('laporan.stok_sebelum_seminggu');
    Route::get('/laporan/stok_habis', [LaporanController::class, 'laporan_stok_habis'])->name('laporan.stok_habis');

    Route::get('/data_barang_keluar', [DataBarangKeluarController::class, 'index'])->name('data_barang_keluar');
});
