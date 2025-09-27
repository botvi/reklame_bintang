<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\Pelanggan;

class DataBarangKeluarController extends Controller
{
    public function index()
    {   
        $barang_keluars = BarangKeluar::with('barang_masuk.barang.supplier', 'barang_masuk.barang', 'user', 'satuan', 'pelanggan')->get();
        $pelanggans = Pelanggan::all();
        return view('pageadmin.data_barang_keluar.index', compact('barang_keluars', 'pelanggans'));
    }
}