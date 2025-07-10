<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;

class DashboardController extends Controller
{
 public function index(){
      $barang_masuk = BarangMasuk::count();
      $barang_keluar = BarangKeluar::count();
      $supplier = Supplier::count();

      // Cek stok awal yang menipis (minimal 20)
      $stok_menipis = BarangMasuk::with('satuan')
          ->where('stok_awal', '<=', 20)
          ->where('stok_awal', '>', 0)
          ->get();

    return view('pageadmin.dashboard.index', compact('barang_masuk', 'barang_keluar', 'supplier', 'stok_menipis'));
 }
}
