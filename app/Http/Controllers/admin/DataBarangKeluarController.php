<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangKeluar;

class DataBarangKeluarController extends Controller
{
    public function index()
    {   
        $barang_keluar = BarangKeluar::with('barang_masuk', 'user')->get();
        return view('pageadmin.data_barang_keluar.index', compact('barang_keluar'));
    }
}