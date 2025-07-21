<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class LaporanController extends Controller
{
    public function index()
    {
        return view('pageadmin.laporan.index');
    }

    public function laporanBarangMasuk(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        
        $barangMasuk = BarangMasuk::with(['barang', 'satuan', 'user'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBarang = $barangMasuk->count();
        $totalNilai = $barangMasuk->sum(function($item) {
            return $item->stok_awal * $item->harga_persatuan;
        });

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tahunList = range(Carbon::now()->year - 5, Carbon::now()->year + 1);

        return view('pageadmin.laporan.laporan_barang_masuk', compact(
            'barangMasuk', 
            'bulan', 
            'tahun', 
            'bulanList', 
            'tahunList',
            'totalBarang',
            'totalNilai'
        ));
    }

    public function printLaporanBarangMasuk(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        
        $barangMasuk = BarangMasuk::with(['barang', 'barang.supplier', 'satuan', 'user'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalBarang = $barangMasuk->count();
        $totalNilai = $barangMasuk->sum('harga_modal');

        // Ambil data pemilik toko
        $pemilikToko = User::where('role', 'pemilik_toko')->first();
        
        // Jika tidak ada pemilik toko, buat data default
        if (!$pemilikToko) {
            $pemilikToko = (object) [
                'nama' => 'Pemilik Toko',
                'no_wa' => '-'
            ];
        }

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('pageadmin.laporan.print.print_laporan_barang_masuk', compact(
            'barangMasuk', 
            'bulan', 
            'tahun', 
            'bulanList',
            'totalBarang',
            'totalNilai',
            'pemilikToko'
        ));
    }

    public function laporanBarangKeluar(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        
        $barangKeluar = BarangKeluar::with(['barang_masuk.barang', 'satuan', 'user', 'barang_masuk'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kelompokkan berdasarkan kode_barang
        $grouped = $barangKeluar->groupBy(function($item) {
            return $item->barang_masuk->barang->kode_barang;
        });

        $result = [];
        foreach ($grouped as $kode_barang => $items) {
            $first = $items->first();
            $jumlah_beli = $items->sum('jumlah_beli');
            $harga_persatuan = $first->barang_masuk->harga_persatuan;
            $result[] = [
                'kode_barang' => $kode_barang,
                'nama_barang' => $first->barang_masuk->barang->nama_barang,
                'jumlah_beli' => $jumlah_beli,
                'satuan' => $first->satuan->nama_satuan,
                'harga_jual' => $first->harga_jual,
                'harga_modal' => $jumlah_beli * $harga_persatuan,
                'harga_persatuan' => $harga_persatuan,
                'total_harga' => $items->sum('total_harga'),
            ];
        }

        $totalBarang = collect($result)->sum('jumlah_beli');
        $totalNilai = collect($result)->sum('total_harga');

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tahunList = range(Carbon::now()->year - 5, Carbon::now()->year + 1);

        return view('pageadmin.laporan.laporan_barang_keluar', compact(
            'result', 
            'bulan', 
            'tahun', 
            'bulanList', 
            'tahunList',
            'totalBarang',
            'totalNilai'
        ));
    }

    public function printLaporanBarangKeluar(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        
        $barangKeluar = BarangKeluar::with(['barang_masuk.barang', 'satuan', 'user', 'barang_masuk'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kelompokkan berdasarkan kode_barang
        $grouped = $barangKeluar->groupBy(function($item) {
            return $item->barang_masuk->barang->kode_barang;
        });

        $result = [];
        foreach ($grouped as $kode_barang => $items) {
            $first = $items->first();
            $jumlah_beli = $items->sum('jumlah_beli');
            $harga_persatuan = $first->barang_masuk->harga_persatuan;
            $result[] = [
                'kode_barang' => $kode_barang,
                'nama_barang' => $first->barang_masuk->barang->nama_barang,
                'jumlah_beli' => $jumlah_beli,
                'satuan' => $first->satuan->nama_satuan,
                'harga_jual' => $first->harga_jual,
                'harga_modal' => $jumlah_beli * $harga_persatuan,
                'harga_persatuan' => $harga_persatuan,
                'total_harga' => $items->sum('total_harga'),
            ];
        }

        $totalBarang = collect($result)->sum('jumlah_beli');
        $totalNilai = collect($result)->sum('total_harga');

        // Ambil data pemilik toko
        $pemilikToko = User::where('role', 'pemilik_toko')->first();
        
        // Jika tidak ada pemilik toko, buat data default
        if (!$pemilikToko) {
            $pemilikToko = (object) [
                'nama' => 'Pemilik Toko',
                'no_wa' => '-'
            ];
        }

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('pageadmin.laporan.print.print_laporan_barang_keluar', compact(
            'result', 
            'bulan', 
            'tahun', 
            'bulanList',
            'totalBarang',
            'totalNilai',
            'pemilikToko'
        ));
    }

    public function laporanStokHabis(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        
        // Ambil semua barang masuk yang stoknya habis
        $barangMasuk = BarangMasuk::with(['barang', 'barang.supplier', 'satuan', 'user', 'barangKeluar'])
            ->get()
            ->filter(function($item) {
                // Hitung total barang keluar untuk item ini
                $totalKeluar = $item->barangKeluar->sum('jumlah');
                // Stok habis jika stok_awal - total_keluar <= 0
                return ($item->stok_awal - $totalKeluar) <= 0;
            })
            ->filter(function($item) use ($bulan, $tahun) {
                // Filter berdasarkan bulan dan tahun barang keluar terakhir
                $barangKeluarTerakhir = $item->barangKeluar()
                    ->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun)
                    ->first();
                
                // Jika ada barang keluar di bulan/tahun tersebut yang menyebabkan stok habis
                if ($barangKeluarTerakhir) {
                    return true;
                }
                
                // Atau jika barang masuk di bulan/tahun tersebut dan stoknya habis
                return $item->created_at->month == $bulan && $item->created_at->year == $tahun;
            });

        $totalBarang = $barangMasuk->count();
        $totalNilai = $barangMasuk->sum(function($item) {
            $totalKeluar = $item->barangKeluar->sum('jumlah');
            $stokHabis = max(0, $item->stok_awal - $totalKeluar);
            return $stokHabis * $item->harga_persatuan;
        });

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $tahunList = range(Carbon::now()->year - 5, Carbon::now()->year + 1);

        return view('pageadmin.laporan.laporan_stok_habis', compact(
            'barangMasuk', 
            'bulan', 
            'tahun', 
            'bulanList', 
            'tahunList',
            'totalBarang',
            'totalNilai'
        ));
    }

    public function printLaporanStokHabis(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        
        // Ambil semua barang masuk yang stoknya habis
        $barangMasuk = BarangMasuk::with(['barang', 'barang.supplier', 'satuan', 'user', 'barangKeluar'])
            ->get()
            ->filter(function($item) {
                $totalKeluar = $item->barangKeluar->sum('jumlah');
                return ($item->stok_awal - $totalKeluar) <= 0;
            })
            ->filter(function($item) use ($bulan, $tahun) {
                // Filter berdasarkan bulan dan tahun barang keluar terakhir
                $barangKeluarTerakhir = $item->barangKeluar()
                    ->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun)
                    ->first();
                
                // Jika ada barang keluar di bulan/tahun tersebut yang menyebabkan stok habis
                if ($barangKeluarTerakhir) {
                    return true;
                }
                
                // Atau jika barang masuk di bulan/tahun tersebut dan stoknya habis
                return $item->created_at->month == $bulan && $item->created_at->year == $tahun;
            });

        $totalBarang = $barangMasuk->count();
        $totalNilai = $barangMasuk->sum(function($item) {
            $totalKeluar = $item->barangKeluar->sum('jumlah');
            $stokHabis = max(0, $item->stok_awal - $totalKeluar);
            return $stokHabis * $item->harga_persatuan;
        });

        // Ambil data pemilik toko
        $pemilikToko = User::where('role', 'pemilik_toko')->first();
        
        // Jika tidak ada pemilik toko, buat data default
        if (!$pemilikToko) {
            $pemilikToko = (object) [
                'nama' => 'Pemilik Toko',
                'no_wa' => '-'
            ];
        }

        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('pageadmin.laporan.print.print_laporan_stok_habis', compact(
            'barangMasuk', 
            'bulan', 
            'tahun', 
            'bulanList',
            'totalBarang',
            'totalNilai',
            'pemilikToko'
        ));
    }

    public function laporanMendekatiKadaluarsa()
    {
        $hariIni = Carbon::now();
        $satuMingguKedepan = Carbon::now()->addWeek();
        
        // Ambil semua barang masuk yang memiliki tanggal kadaluarsa
        $barangMasuk = BarangMasuk::with(['barang', 'barang.supplier', 'satuan', 'user', 'barangKeluar'])
            ->whereNotNull('tanggal_kadaluarsa')
            ->get()
            ->filter(function($item) use ($hariIni, $satuMingguKedepan) {
                $tanggalKadaluarsa = Carbon::parse($item->tanggal_kadaluarsa);
                
                // Filter barang yang kadaluarsa dalam 1 minggu ke depan atau sudah kadaluarsa
                return $tanggalKadaluarsa->lte($satuMingguKedepan);
            })
            ->map(function($item) {
                // Hitung sisa stok
                $totalKeluar = $item->barangKeluar->sum('jumlah');
                $sisaStok = max(0, $item->stok_awal - $totalKeluar);
                
                // Hitung sisa hari sebelum kadaluarsa
                $tanggalKadaluarsa = Carbon::parse($item->tanggal_kadaluarsa);
                $hariIni = Carbon::now();
                $sisaHari = $hariIni->diffInDays($tanggalKadaluarsa, false);
                
                $item->sisa_stok = $sisaStok;
                $item->sisa_hari = $sisaHari;
                $item->total_nilai = $sisaStok * $item->harga_persatuan;
                
                return $item;
            })
            ->sortBy('sisa_hari'); // Urutkan berdasarkan sisa hari (yang paling dekat kadaluarsa di atas)

        $totalBarang = $barangMasuk->count();
        $totalNilai = $barangMasuk->sum('total_nilai');

        return view('pageadmin.laporan.laporan_mendekati_kadaluarsa', compact(
            'barangMasuk',
            'totalBarang',
            'totalNilai'
        ));
    }

    public function printLaporanMendekatiKadaluarsa()
    {
        $hariIni = Carbon::now();
        $satuMingguKedepan = Carbon::now()->addWeek();
        
        // Ambil semua barang masuk yang memiliki tanggal kadaluarsa
        $barangMasuk = BarangMasuk::with(['barang', 'barang.supplier', 'satuan', 'user', 'barangKeluar'])
            ->whereNotNull('tanggal_kadaluarsa')
            ->get()
            ->filter(function($item) use ($hariIni, $satuMingguKedepan) {
                $tanggalKadaluarsa = Carbon::parse($item->tanggal_kadaluarsa);
                
                // Filter barang yang kadaluarsa dalam 1 minggu ke depan atau sudah kadaluarsa
                return $tanggalKadaluarsa->lte($satuMingguKedepan);
            })
            ->map(function($item) {
                // Hitung sisa stok
                $totalKeluar = $item->barangKeluar->sum('jumlah');
                $sisaStok = max(0, $item->stok_awal - $totalKeluar);
                
                // Hitung sisa hari sebelum kadaluarsa
                $tanggalKadaluarsa = Carbon::parse($item->tanggal_kadaluarsa);
                $hariIni = Carbon::now();
                $sisaHari = $hariIni->diffInDays($tanggalKadaluarsa, false);
                
                $item->sisa_stok = $sisaStok;
                $item->sisa_hari = $sisaHari;
                $item->total_nilai = $sisaStok * $item->harga_persatuan;
                
                return $item;
            })
            ->sortBy('sisa_hari'); // Urutkan berdasarkan sisa hari (yang paling dekat kadaluarsa di atas)

        $totalBarang = $barangMasuk->count();
        $totalNilai = $barangMasuk->sum('total_nilai');

        // Ambil data pemilik toko
        $pemilikToko = User::where('role', 'pemilik_toko')->first();
        
        // Jika tidak ada pemilik toko, buat data default
        if (!$pemilikToko) {
            $pemilikToko = (object) [
                'nama' => 'Pemilik Toko',
                'no_wa' => '-'
            ];
        }

        return view('pageadmin.laporan.print.print_laporan_mendekati_kadaluarsa', compact(
            'barangMasuk',
            'totalBarang',
            'totalNilai',
            'pemilikToko'
        ));
    }
}
