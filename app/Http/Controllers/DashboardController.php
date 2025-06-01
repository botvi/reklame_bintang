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

    return view('pageadmin.dashboard.index', compact('barang_masuk', 'barang_keluar', 'supplier'));
 }
}
