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
                'pelanggan_id' => 'nullable|exists:pelanggans,id',
                'items' => 'required|array|min:1',
                'items.*.barang_masuk_id' => 'required|exists:barang_masuks,id',
                'items.*.jumlah_beli' => 'required|numeric|min:1',
                'items.*.harga_jual' => 'required|numeric|min:0',
                'items.*.satuan_id' => 'required|exists:satuans,id',
            ]);

            $transaksiKode = 'TRX-' . now()->format('YmdHis') . '-' . substr(uniqid('', true), -4);

            foreach ($request->items as $item) {
                $barangMasuk = BarangMasuk::findOrFail($item['barang_masuk_id']);
                $satuanMasuk = Satuan::findOrFail($barangMasuk->satuan_id);
                $satuanKeluar = Satuan::findOrFail($item['satuan_id']);

                if ($satuanMasuk->jenis !== $satuanKeluar->jenis) {
                    Alert::error('Gagal', 'Jenis satuan tidak sama untuk item ' . optional($barangMasuk->barang)->nama_barang . '!');
                    return back()->withInput();
                }

                $stok_dasar = $barangMasuk->stok_awal * $satuanMasuk->konversi_ke_dasar;
                $keluar_dasar = $item['jumlah_beli'] * $satuanKeluar->konversi_ke_dasar;

                if ($stok_dasar < $keluar_dasar) {
                    Alert::error('Gagal', 'Stok tidak mencukupi untuk item ' . optional($barangMasuk->barang)->nama_barang . '!');
                    return back()->withInput();
                }

                $sisa_dasar = $stok_dasar - $keluar_dasar;
                $barangMasuk->stok_awal = $sisa_dasar / $satuanMasuk->konversi_ke_dasar;
                $barangMasuk->save();

                $total_harga = $item['jumlah_beli'] * $item['harga_jual'];

                $diskon_terpakai = 0;
                $total_harga_setelah_diskon = $total_harga;
                if ($barangMasuk->max_pembelian_to_diskon &&
                    $barangMasuk->diskon &&
                    $item['jumlah_beli'] >= $barangMasuk->max_pembelian_to_diskon &&
                    $item['satuan_id'] == $barangMasuk->satuan_id) {
                    $diskon_terpakai = ($total_harga * $barangMasuk->diskon) / 100;
                    $total_harga_setelah_diskon = $total_harga - $diskon_terpakai;
                }

                BarangKeluar::create([
                    'transaksi_kode' => $transaksiKode,
                    'user_id' => $request->user_id,
                    'barang_masuk_id' => $item['barang_masuk_id'],
                    'jumlah_beli' => $item['jumlah_beli'],
                    'harga_jual' => $item['harga_jual'],
                    'total_harga' => $total_harga,
                    'diskon_terpakai' => $diskon_terpakai,
                    'total_harga_setelah_diskon' => $total_harga_setelah_diskon,
                    'satuan_id' => $item['satuan_id'],
                    'pelanggan_id' => $request->pelanggan_id,
                ]);
            }

            Alert::success('Sukses', 'Transaksi berhasil disimpan.');
            return redirect()->route('transaksi.struk', ['transaksi_kode' => $transaksiKode]);
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function struk(BarangKeluar $barang_keluar)
    {
        $barang_keluar->load('barang_masuk.barang', 'satuan', 'pelanggan', 'user');
        return view('pageadmin.barang_keluar.struk', compact('barang_keluar'));
    }

    public function strukTransaksi(string $transaksi_kode)
    {
        $items = BarangKeluar::with(['barang_masuk.barang', 'satuan', 'pelanggan', 'user'])
            ->where('transaksi_kode', $transaksi_kode)
            ->get();

        if ($items->isEmpty()) {
            abort(404);
        }

        $pelanggan = $items->first()->pelanggan;
        $user = $items->first()->user;
        $subtotal = $items->sum('total_harga');
        $totalDiskon = $items->sum(function ($i) { return $i->diskon_terpakai ?? 0; });
        $totalBayar = $items->sum(function ($i) { return $i->total_harga_setelah_diskon ?? $i->total_harga; });

        return view('pageadmin.barang_keluar.struk_transaksi', compact(
            'items', 'transaksi_kode', 'pelanggan', 'user', 'subtotal', 'totalDiskon', 'totalBayar'
        ));
    }
}
