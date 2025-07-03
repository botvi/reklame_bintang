<?php

namespace App\Http\Controllers\admin;

use App\Models\Satuan;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::orderBy('jenis')->orderBy('konversi_ke_dasar', 'asc')->get();
        $satuansGrouped = $satuans->groupBy('jenis');
        return view('pageadmin.satuan.index', compact('satuans', 'satuansGrouped'));
    }

    public function deletealldata()
    {
        try {
            // Hapus data barang masuk yang terkait dengan satuan
            BarangMasuk::whereNotNull('satuan_id')->delete();
            
            // Hapus data barang keluar yang terkait dengan satuan
            BarangKeluar::whereNotNull('satuan_id')->delete();
            
            // Hapus semua data satuan
            Satuan::query()->delete();
            
            Alert::toast('Success', 'Data satuan dan data terkait berhasil dihapus')->position('top-end');
            return redirect()->route('satuan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
            return redirect()->route('satuan.index');
        }
    }

    public function create()
    {
        return view('pageadmin.satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255',
        ]);
        Satuan::create($request->all());
        Alert::toast('Success', 'Satuan berhasil ditambahkan' )->position('top-end');
        return redirect()->route('satuan.index');
    }

    public function edit($id)
    {
        $satuan = Satuan::find($id);
        return view('pageadmin.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255',
        ]);
        $satuan = Satuan::find($id);
        $satuan->update($request->all());
        Alert::toast('Success', 'Satuan berhasil diubah' )->position('top-end');
        return redirect()->route('satuan.index');
    }

    public function destroy($id)
    {
        $satuan = Satuan::find($id);
        $satuan->delete();
        Alert::toast('Success', 'Satuan berhasil dihapus' )->position('top-end');
        return redirect()->route('satuan.index');
    }
}
