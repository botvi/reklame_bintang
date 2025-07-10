<?php

namespace App\Http\Controllers\admin;

use App\Models\BarangMasuk;
use App\Models\Supplier;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;    
use RealRashid\SweetAlert\Facades\Alert;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barang_masuks = BarangMasuk::with('supplier', 'satuan')->get();
        return view('pageadmin.barang_masuk.index', compact('barang_masuks'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $satuans = Satuan::all();
        return view('pageadmin.barang_masuk.create', compact('suppliers', 'satuans'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_barang' => 'required|string|max:255',
                'supplier_id' => 'required|exists:suppliers,id',
                'kode_barang' => 'required|string|max:255 |unique:barang_masuks',
                'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'tanggal_kadaluarsa' => 'nullable|date',
                'stok_awal' => 'required|numeric',
                'satuan_id' => 'required|exists:satuans,id',
                'harga_persatuan' => 'required|numeric',
            ]);
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }

        try {
            $barang_masuk = BarangMasuk::create([
                'user_id' => Auth::user()->id,
                'supplier_id' => $request->supplier_id,
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'stok_awal' => $request->stok_awal,
                'satuan_id' => $request->satuan_id,
                'harga_persatuan' => $request->harga_persatuan,
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
            $suppliers = Supplier::all();
            $satuans = Satuan::all();
            return view('pageadmin.barang_masuk.edit', compact('barang_masuk', 'suppliers', 'satuans'));
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
                'supplier_id' => 'required|exists:suppliers,id',
                'kode_barang' => 'required|string|max:255 |unique:barang_masuks,kode_barang,' . $id,
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'tanggal_kadaluarsa' => 'nullable|date',
                'stok_awal' => 'required|numeric',
                'satuan_id' => 'required|exists:satuans,id',
                'harga_persatuan' => 'required|numeric',
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
                'supplier_id' => $request->supplier_id,
                'kode_barang' => $request->kode_barang,
                'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
                'stok_awal' => $request->stok_awal,
                'satuan_id' => $request->satuan_id,
                'harga_persatuan' => $request->harga_persatuan,
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

    public function tambahstok($id, Request $request)
    {
        try {
            $request->validate([
                'stok_awal' => 'required|numeric|min:0',
                'stok_tambah' => 'required|numeric|min:1',
            ]);

            $barang_masuk = BarangMasuk::findOrFail($id);
            
            // Ambil nilai stok awal dan stok tambah dari form
            $stok_awal = $request->stok_awal;
            $stok_tambah = $request->stok_tambah;
            
            // Hitung total stok baru
            $total_stok = $stok_awal + $stok_tambah;
            
            // Update stok_awal dengan total baru
            $barang_masuk->stok_awal = $total_stok;
            $barang_masuk->save();
            
            Alert::toast('Stok berhasil ditambahkan! Total stok sekarang: ' . $total_stok, 'success')->position('top-end');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menambah stok: ' . $e->getMessage());
        }
        
        return redirect()->route('barang_masuk.index');
    }
}
