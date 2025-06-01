<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
class LaporanController extends Controller
{
    public function index()
    {
        return view('pageadmin.laporan.index');
    }


    public function laporan_semua_barang_masuk()
    {
        $laporan = BarangMasuk::with('supplier', 'satuan')->get();
        if($laporan->isEmpty()) {
            Alert::error('Error', 'Data barang masuk tidak ditemukan');
            return redirect()->back();
        }

        $data = User::where('role', 'pemilik_toko')->first();
        return view('pageadmin.laporan.laporan_barang_masuk', compact('laporan', 'data'));
    }

    public function laporan_semua_barang_keluar()
    {
        $laporan = BarangKeluar::with('barang_masuk')->get();
        if($laporan->isEmpty()) {
            Alert::error('Error', 'Data barang keluar tidak ditemukan');
            return redirect()->back();
        }
        $data = User::where('role', 'pemilik_toko')->first();
        return view('pageadmin.laporan.laporan_barang_keluar', compact('laporan', 'data'));
    }

    public function laporan_barang_masuk_per_bulan()
    {
        $laporan = BarangMasuk::where('created_at', '>=', Carbon::now()->subMonth())->with('supplier', 'satuan')->get();
        if($laporan->isEmpty()) {
            Alert::error('Error', 'Data barang masuk bulan ini tidak ditemukan');
            return redirect()->back();
        }
        $data = User::where('role', 'pemilik_toko')->first();
        return view('pageadmin.laporan.laporan_barang_masuk_per_bulan', compact('laporan', 'data'));
    }

    public function laporan_barang_keluar_per_bulan()
    {
        $laporan = BarangKeluar::where('created_at', '>=', Carbon::now()->subMonth())->with('barang_masuk')->get();
        if($laporan->isEmpty()) {
            Alert::error('Error', 'Data barang keluar bulan ini tidak ditemukan');
            return redirect()->back();
        }
        $data = User::where('role', 'pemilik_toko')->first();
        return view('pageadmin.laporan.laporan_barang_keluar_per_bulan', compact('laporan', 'data'));
    }

    public function laporan_stok_sebelum_seminggu()
    {
        $laporan = BarangMasuk::where('tanggal_kadaluarsa', '<=', Carbon::now()->addWeek())->with('supplier', 'satuan')->get();
        if($laporan->isEmpty()) {
            Alert::error('Error', 'Tidak ada barang yang kadaluarsa dalam seminggu terakhir');
            return redirect()->back();
        }
        $data = User::where('role', 'pemilik_toko')->first();
        return view('pageadmin.laporan.laporan_stok_sebelum_seminggu', compact('laporan', 'data'));
    }

    public function laporan_stok_habis()
    {
        $laporan = BarangMasuk::where('stok_barang', '<=', 0)->with('supplier', 'satuan')->get();
        if($laporan->isEmpty()) {
            Alert::error('Error', 'Tidak ada barang dengan stok habis');
            return redirect()->back();
        }
        $data = User::where('role', 'pemilik_toko')->first();
        return view('pageadmin.laporan.laporan_stok_habis', compact('laporan', 'data'));
    }
    
    
    
    
}
