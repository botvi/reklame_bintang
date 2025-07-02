<?php
namespace App\Http\Controllers\admin;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barang_keluars = BarangKeluar::with('barang_masuk', 'satuan')->get();
        $barang_masuks = BarangMasuk::with('satuan')->get();
        $satuans = Satuan::all();

        return view('pageadmin.barang_keluar.index', compact('barang_keluars', 'barang_masuks', 'satuans'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'barang_masuk_id' => 'required|exists:barang_masuks,id',
                'jumlah_beli' => 'required|numeric|min:1',
                'harga_persatuan' => 'required|numeric|min:0',
                'satuan_id' => 'required|exists:satuans,id',
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
            $total_harga = $request->jumlah_beli * $request->harga_persatuan;

            // Simpan barang keluar
            BarangKeluar::create([
                'user_id' => $request->user_id,
                'barang_masuk_id' => $request->barang_masuk_id,
                'jumlah_beli' => $request->jumlah_beli,
                'harga_persatuan' => $request->harga_persatuan,
                'total_harga' => $total_harga,
                'satuan_id' => $request->satuan_id,
            ]);

            Alert::success('Sukses', 'Barang keluar berhasil disimpan.');
            return redirect()->route('barang_keluar.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return back()->withInput();
        }
    }
}
