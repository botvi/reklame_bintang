<?php

namespace App\Http\Controllers\admin;

use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use App\Models\BarangMasuk;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barang_keluars = BarangKeluar::with('barang_masuk')->get();
        $barang_masuks = BarangMasuk::all();
        return view('pageadmin.barang_keluar.index', compact('barang_keluars', 'barang_masuks'));
    }

  
    public function store(Request $request)
    {
        try {
            $request->validate([
                'barang_masuk_id' => 'required|exists:barang_masuks,id',
                'jumlah_keluar' => 'required|integer|min:1',
                'total_harga' => 'required|numeric|min:0',
            ]);

            // Cek stok barang
            $barangMasuk = BarangMasuk::findOrFail($request->barang_masuk_id);
            if ($barangMasuk->stok_barang < $request->jumlah_keluar) {
                Alert::error('Error', 'Stok tidak mencukupi!');
                return redirect()->back();
            }

            // Update stok barang
            $barangMasuk->stok_barang -= $request->jumlah_keluar;
            $barangMasuk->save();

            // Buat record barang keluar
            $barang_keluar = BarangKeluar::create([
                'user_id' => Auth::user()->id,
                'barang_masuk_id' => $request->barang_masuk_id,
                'jumlah_keluar' => $request->jumlah_keluar,
                'total_harga' => $request->total_harga,
            ]);

            Alert::toast('Barang Keluar berhasil ditambahkan!', 'success')->position('top-end');
            return redirect()->route('barang_keluar.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

}
