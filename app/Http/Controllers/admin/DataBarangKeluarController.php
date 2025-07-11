<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangKeluar;

class DataBarangKeluarController extends Controller
{
    public function index()
    {   
        $barang_keluars = BarangKeluar::with('barang_masuk.barang.supplier', 'barang_masuk.barang', 'user', 'satuan')->get();
        return view('pageadmin.data_barang_keluar.index', compact('barang_keluars'));
    }
}