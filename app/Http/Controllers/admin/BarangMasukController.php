<?php

namespace App\Http\Controllers\admin;

use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;    
use RealRashid\SweetAlert\Facades\Alert;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barang_masuks = BarangMasuk::with('barang', 'satuan')->get();
        $satuans = Satuan::all();
        return view('pageadmin.barang_masuk.index', compact('barang_masuks', 'satuans'));
    }

    public function create()
    {
        $barangs = Barang::all();
        $satuans = Satuan::all();
        return view('pageadmin.barang_masuk.create', compact('barangs', 'satuans'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'barang_id' => 'required|exists:barangs,id',
                'stok_awal' => 'required|numeric',
                'satuan_id' => 'required|exists:satuans,id',
                'harga_persatuan' => 'required|numeric',
                'harga_modal' => 'required|numeric',
                'harga_jual' => 'required|numeric',
            ]);
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }

        try {
            $barang_masuk = BarangMasuk::create([
                'user_id' => Auth::user()->id,
                'barang_id' => $request->barang_id,
                'stok_awal' => $request->stok_awal,
                'satuan_id' => $request->satuan_id,
                'harga_persatuan' => $request->harga_persatuan,
                'harga_modal' => $request->harga_modal,
                'harga_jual' => $request->harga_jual,
                'max_pembelian_to_diskon' => $request->max_pembelian_to_diskon,
                'diskon' => $request->diskon,
            ]);

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
            $barangs = Barang::all();

            // Ambil satuan dengan jenis yang sama seperti satuan_id pada barang_masuk saat ini
            $satuanSaatIni = Satuan::find($barang_masuk->satuan_id);
            $satuans = Satuan::where('jenis', $satuanSaatIni ? $satuanSaatIni->jenis : null)->get();

            return view('pageadmin.barang_masuk.edit', compact('barang_masuk', 'barangs', 'satuans'));
        } catch (\Exception $e) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('barang_masuk.index');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'barang_id' => 'required|exists:barangs,id',
                'stok_awal' => 'required|numeric',
                'satuan_id' => 'required|exists:satuans,id',
                'harga_persatuan' => 'required|numeric',
                'harga_modal' => 'required|numeric',
                'harga_jual' => 'required|numeric',
                'max_pembelian_to_diskon' => 'nullable|numeric',
                'diskon' => 'nullable|numeric',
                ]);
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }

        try {
            $barang_masuk = BarangMasuk::findOrFail($id);
            
            // Update data dasar
            $barang_masuk->update([
                'barang_id' => $request->barang_id,
                'stok_awal' => $request->stok_awal,
                'satuan_id' => $request->satuan_id,
                'harga_persatuan' => $request->harga_persatuan,
                'harga_modal' => $request->harga_modal,
                'harga_jual' => $request->harga_jual,
                'max_pembelian_to_diskon' => $request->max_pembelian_to_diskon,
                'diskon' => $request->diskon,
                    ]);

            // Handle upload gambar
            

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
                'satuan_id' => 'required|exists:satuans,id',
                // 'harga_modal' => 'required|numeric', // Tidak perlu validasi harga_modal dari input
            ]);

            $barang_masuk = BarangMasuk::findOrFail($id);
            
            // Ambil nilai stok awal dan stok tambah dari form
            $stok_awal = $request->stok_awal;
            $stok_tambah = $request->stok_tambah;
            $satuan_id = $request->satuan_id;
            // Ambil harga persatuan dari database (bisa juga dari input jika ingin support perubahan harga)
            $harga_persatuan = $barang_masuk->harga_persatuan;
            $harga_modal_lama = $barang_masuk->harga_modal;

            // Hitung total stok baru
            $total_stok = $stok_awal + $stok_tambah;
            // Hitung harga modal baru
            $harga_modal_baru = $harga_modal_lama + ($stok_tambah * $harga_persatuan);
            
            // Update stok_awal, satuan_id, dan harga_modal dengan nilai baru
            $barang_masuk->stok_awal = $total_stok;
            $barang_masuk->satuan_id = $satuan_id;
            $barang_masuk->harga_modal = $harga_modal_baru;
            $barang_masuk->save();
            
            Alert::toast('Stok berhasil ditambahkan! Total stok sekarang: ' . $total_stok, 'success')->position('top-end');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menambah stok: ' . $e->getMessage());
        }
        
        return redirect()->route('barang_masuk.index');
    }
}
