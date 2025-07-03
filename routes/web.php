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

Route::get('/get-satuan', function () {
    Artisan::call('db:seed', [
        '--class' => 'SatuanSeeder'
    ]);
    return redirect()->back()->with('success', 'SatuanSeeder has been create successfully!');
});

Route::get('/', [LoginController::class, 'showLoginForm'])->name('formlogin');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/delete-satuan', [SatuanController::class, 'deletealldata'])->name('satuan.deletealldata');
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
    Route::get('/laporan/barang-masuk', [LaporanController::class, 'laporanBarangMasuk'])->name('laporan.barang_masuk');
    Route::get('/laporan/barang-masuk/print', [LaporanController::class, 'printLaporanBarangMasuk'])->name('laporan.barang_masuk.print');
    Route::get('/laporan/barang-keluar', [LaporanController::class, 'laporanBarangKeluar'])->name('laporan.barang_keluar');
    Route::get('/laporan/barang-keluar/print', [LaporanController::class, 'printLaporanBarangKeluar'])->name('laporan.barang_keluar.print');
    Route::get('/laporan/stok-habis', [LaporanController::class, 'laporanStokHabis'])->name('laporan.stok_habis');
    Route::get('/laporan/stok-habis/print', [LaporanController::class, 'printLaporanStokHabis'])->name('laporan.stok_habis.print');
    Route::get('/laporan/mendekati-kadaluarsa', [LaporanController::class, 'laporanMendekatiKadaluarsa'])->name('laporan.mendekati_kadaluarsa');
    Route::get('/laporan/mendekati-kadaluarsa/print', [LaporanController::class, 'printLaporanMendekatiKadaluarsa'])->name('laporan.mendekati_kadaluarsa.print');
   

    Route::get('/data_barang_keluar', [DataBarangKeluarController::class, 'index'])->name('data_barang_keluar');
});
