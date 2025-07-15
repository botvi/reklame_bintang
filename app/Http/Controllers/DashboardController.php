<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\DB;
        
class DashboardController extends Controller
{
 public function index(){
      $barang_masuk = BarangMasuk::with('barang.supplier')->count();
      $barang_keluar = BarangKeluar::count();
      $supplier = Supplier::count();

      // Cek stok awal yang menipis (minimal 20)
      $stok_menipis = BarangMasuk::with('satuan')
          ->where('stok_awal', '<=', 20)
          ->where('stok_awal', '>', 0)
          ->get();

      // Total harga modal (akumulasi modal barang masuk)
      $total_harga_modal = BarangMasuk::sum(DB::raw('harga_modal'));

      // Total harga jual (akumulasi penjualan barang keluar)
      $total_harga_jual = BarangKeluar::sum(DB::raw('total_harga'));

      // Total keuntungan
      $total_keuntungan = $total_harga_jual - $total_harga_modal;

    return view('pageadmin.dashboard.index', compact(
        'barang_masuk', 'barang_keluar', 'supplier', 'stok_menipis',
        'total_harga_modal', 'total_harga_jual', 'total_keuntungan'
    ));
 }
}

