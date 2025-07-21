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

      // Ambil data barang keluar beserta relasi barang masuk dan barang
      $barang_keluar_data = BarangKeluar::with(['barang_masuk.barang'])
          ->get()
          ->groupBy(function($item) {
              // Cek null relasi barang_masuk dan barang
              if (!$item->barang_masuk || !$item->barang_masuk->barang) return null;
              return $item->barang_masuk->barang->id;
          });

      $rekap_barang = [];
      foreach ($barang_keluar_data as $barang_id => $items) {
          if ($barang_id === null) continue;
          $first = $items->first();
          $nama_barang = ($first && $first->barang_masuk && $first->barang_masuk->barang) ? $first->barang_masuk->barang->nama_barang : '-';
          $total_harga_jual = $items->sum('total_harga');
          $total_jumlah_keluar = $items->sum('jumlah_beli');
          // Ambil harga modal dari barang masuk terkait (asumsi: harga modal terakhir)
          $harga_modal = ($first && $first->barang_masuk) ? $first->barang_masuk->harga_modal : 0;
          $total_harga_modal = $harga_modal;
          $keuntungan = $total_harga_jual - $total_harga_modal;

          $rekap_barang[] = [
              'nama_barang' => $nama_barang,
              'harga_modal' => $harga_modal,
              'total_harga_modal' => $total_harga_modal,
              'total_harga_jual' => $total_harga_jual,
              'keuntungan' => $keuntungan,
              'total_keluar' => $total_jumlah_keluar,
          ];
      }

      // Total harga modal dan keuntungan hanya dari barang keluar
      $total_harga_modal = collect($rekap_barang)->sum('total_harga_modal');
      $total_harga_jual = collect($rekap_barang)->sum('total_harga_jual');
      $total_keuntungan = collect($rekap_barang)->sum('keuntungan');

    return view('pageadmin.dashboard.index', compact(
        'barang_masuk', 'barang_keluar', 'supplier', 'stok_menipis',
        'total_harga_modal', 'total_harga_jual', 'total_keuntungan', 'rekap_barang'
    ));
 }
}

