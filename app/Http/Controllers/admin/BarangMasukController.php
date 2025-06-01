<?php

namespace App\Http\Controllers\admin;

use App\Models\BarangMasuk;
use App\Models\Satuan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barang_masuks = BarangMasuk::with('satuan', 'supplier')->get();
        return view('pageadmin.barang_masuk.index', compact('barang_masuks'));
    }

    public function create()
    {
        $satuans = Satuan::all();
        $suppliers = Supplier::all();
        return view('pageadmin.barang_masuk.create', compact('satuans', 'suppliers'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'satuan_id' => 'required|exists:satuans,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'kode_barang' => 'required|string|max:255 |unique:barang_masuks',
                'harga_satuan' => 'required|numeric|min:0',
                'stok_barang' => 'required|integer|min:1',
                'total_harga' => 'required|numeric|min:0',
                'tanggal_kadaluarsa' => 'required|date',
                'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }

        try {
            $barang_masuk = BarangMasuk::create([
                'user_id' => Auth::user()->id,
                'satuan_id' => $request->satuan_id,
                'supplier_id' => $request->supplier_id,
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'harga_satuan' => $request->harga_satuan,
                'stok_barang' => $request->stok_barang,
                'total_harga' => $request->total_harga,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            ]);

            if ($request->hasFile('gambar')) {
                $gambar = $request->file('gambar');
                $namaFile = time() . '_' . $gambar->getClientOriginalName();
                $gambar->move('uploads/barang_masuk', $namaFile);
                $barang_masuk->gambar = $namaFile;
                $barang_masuk->save();
            }

            Alert::toast('Barang Masuk berhasil ditambahkan!', 'success')->position('top-end');
            return redirect()->route('barang_masuk.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $barang_masuk = BarangMasuk::findOrFail($id);
            $satuans = Satuan::all();
            $suppliers = Supplier::all();
            return view('pageadmin.barang_masuk.edit', compact('barang_masuk', 'satuans', 'suppliers'));
        } catch (\Exception $e) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('barang_masuk.index');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'satuan_id' => 'required|exists:satuans,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'kode_barang' => 'required|string|max:255 |unique:barang_masuks,kode_barang,' . $id,
                'harga_satuan' => 'required|numeric|min:0',
                'stok_barang' => 'required|integer|min:1',  
                'total_harga' => 'required|numeric|min:0',
                'tanggal_kadaluarsa' => 'required|date',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }

        try {
            $barang_masuk = BarangMasuk::findOrFail($id);
            
            // Update data dasar
            $barang_masuk->update([
                'nama_barang' => $request->nama_barang,
                'satuan_id' => $request->satuan_id,
                'supplier_id' => $request->supplier_id,
                'kode_barang' => $request->kode_barang,
                'harga_satuan' => $request->harga_satuan,
                'stok_barang' => $request->stok_barang,
                'total_harga' => $request->total_harga,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            ]);

            // Handle upload gambar
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada
                if ($barang_masuk->gambar && file_exists(public_path('uploads/barang_masuk/' . $barang_masuk->gambar))) {
                    unlink(public_path('uploads/barang_masuk/' . $barang_masuk->gambar));
                }

                $gambar = $request->file('gambar');
                $namaFile = time() . '_' . $gambar->getClientOriginalName();
                $gambar->move('uploads/barang_masuk', $namaFile);
                $barang_masuk->gambar = $namaFile;
                $barang_masuk->save();
            }

            Alert::toast('Barang Masuk berhasil diubah!', 'success')->position('top-end');
            return redirect()->route('barang_masuk.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            $barang_masuk = BarangMasuk::findOrFail($id);
            
            // Hapus gambar jika ada
            if ($barang_masuk->gambar && file_exists(public_path('uploads/barang_masuk/' . $barang_masuk->gambar))) {
                unlink(public_path('uploads/barang_masuk/' . $barang_masuk->gambar));
            }
            
            $barang_masuk->delete();
            Alert::toast('Barang Masuk berhasil dihapus!', 'success')->position('top-end');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
        
        return redirect()->route('barang_masuk.index');
    }
}
