<?php
namespace App\Http\Controllers\admin;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Satuan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barang_keluars = BarangKeluar::with('barang_masuk.barang', 'satuan', 'pelanggan')->get();
        $barang_masuks = BarangMasuk::with('satuan', 'barang')->get();
        $satuans = Satuan::all();
        $pelanggans = Pelanggan::all();
        return view('pageadmin.barang_keluar.index', compact('barang_keluars', 'barang_masuks', 'satuans', 'pelanggans'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'barang_masuk_id' => 'required|exists:barang_masuks,id',
                'jumlah_beli' => 'required|numeric|min:1',
                'harga_jual' => 'required|numeric|min:0',
                'satuan_id' => 'required|exists:satuans,id',
                'pelanggan_id' => 'nullable|exists:pelanggans,id',
            ]);

            $barangMasuk = BarangMasuk::findOrFail($request->barang_masuk_id);
            $satuanMasuk = Satuan::findOrFail($barangMasuk->satuan_id);
            $satuanKeluar = Satuan::findOrFail($request->satuan_id);

            // Pastikan jenis satuan sama
            if ($satuanMasuk->jenis !== $satuanKeluar->jenis) {
                Alert::error('Gagal', 'Jenis satuan tidak sama!');
                return back()->withInput();
            }

            // Konversi stok dan permintaan ke satuan dasar
            $stok_dasar = $barangMasuk->stok_awal * $satuanMasuk->konversi_ke_dasar;
            $keluar_dasar = $request->jumlah_beli * $satuanKeluar->konversi_ke_dasar;

            // Pastikan stok cukup
            if ($stok_dasar < $keluar_dasar) {
                Alert::error('Gagal', 'Stok tidak mencukupi!');
                return back()->withInput();
            }

            // Kurangi stok_awal (stok berjalan)
            $sisa_dasar = $stok_dasar - $keluar_dasar;
            $barangMasuk->stok_awal = $sisa_dasar / $satuanMasuk->konversi_ke_dasar;
            $barangMasuk->save();

            // Hitung total harga di backend
            $total_harga = $request->jumlah_beli * $request->harga_jual;
            
            // Logika diskon
            $diskon_terpakai = 0;
            $total_harga_setelah_diskon = $total_harga;
            
            // Cek apakah jumlah beli melebihi max_pembelian_to_diskon dan satuan_id sama dengan satuan barang masuk
            if ($barangMasuk->max_pembelian_to_diskon && 
                $barangMasuk->diskon && 
                $request->jumlah_beli >= $barangMasuk->max_pembelian_to_diskon &&
                $request->satuan_id == $barangMasuk->satuan_id) {
                
                // Hitung diskon dalam persen
                $diskon_terpakai = ($total_harga * $barangMasuk->diskon) / 100;
                $total_harga_setelah_diskon = $total_harga - $diskon_terpakai;
            }

            // Simpan barang keluar
            BarangKeluar::create([
                'user_id' => $request->user_id,
                'barang_masuk_id' => $request->barang_masuk_id,
                'jumlah_beli' => $request->jumlah_beli,
                'harga_jual' => $request->harga_jual,
                'total_harga' => $total_harga,
                'diskon_terpakai' => $diskon_terpakai,
                'total_harga_setelah_diskon' => $total_harga_setelah_diskon,
                'satuan_id' => $request->satuan_id,
                'pelanggan_id' => $request->pelanggan_id,
            ]);

            if ($diskon_terpakai > 0) {
                Alert::success('Sukses', 'Barang keluar berhasil disimpan dengan diskon Rp. ' . number_format($diskon_terpakai, 0, ',', '.') . '!');
            } else {
                Alert::success('Sukses', 'Barang keluar berhasil disimpan.');
            }
            return redirect()->route('barang_keluar.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return back()->withInput();
        }
    }
}
