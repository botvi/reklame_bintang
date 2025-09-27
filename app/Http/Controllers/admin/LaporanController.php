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
        $tanggal_awal = $request->get('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggal_akhir = $request->get('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $query = BarangMasuk::with(['barang', 'satuan', 'user']);
        
        // Filter berdasarkan tanggal jika ada
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59']);
        } else {
            // Fallback ke filter bulan dan tahun
            $query->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun);
        }
        
        $barangMasuk = $query->orderBy('created_at', 'desc')->get();

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
            'totalNilai',
            'tanggal_awal',
            'tanggal_akhir'
        ));
    }

    public function printLaporanBarangMasuk(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $tanggal_awal = $request->get('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggal_akhir = $request->get('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $query = BarangMasuk::with(['barang', 'barang.supplier', 'satuan', 'user']);
        
        // Filter berdasarkan tanggal jika ada
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59']);
        } else {
            // Fallback ke filter bulan dan tahun
            $query->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun);
        }
        
        $barangMasuk = $query->orderBy('created_at', 'desc')->get();

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
            'pemilikToko',
            'tanggal_awal',
            'tanggal_akhir'
        ));
    }

    public function laporanBarangKeluar(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $tanggal_awal = $request->get('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggal_akhir = $request->get('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $query = BarangKeluar::with(['barang_masuk.barang', 'satuan', 'user', 'barang_masuk', 'pelanggan']);
        
        // Filter berdasarkan tanggal jika ada
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59']);
        } else {
            // Fallback ke filter bulan dan tahun
            $query->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun);
        }
        
        $barangKeluar = $query->orderBy('created_at', 'desc')->get();

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
                'pelanggan' => $first->pelanggan,
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
            'totalNilai',
            'tanggal_awal',
            'tanggal_akhir'
        ));
    }

    public function printLaporanBarangKeluar(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $tanggal_awal = $request->get('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggal_akhir = $request->get('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $query = BarangKeluar::with(['barang_masuk.barang', 'satuan', 'user', 'barang_masuk', 'pelanggan']);
        
        // Filter berdasarkan tanggal jika ada
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59']);
        } else {
            // Fallback ke filter bulan dan tahun
            $query->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun);
        }
        
        $barangKeluar = $query->orderBy('created_at', 'desc')->get();

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
                'pelanggan' => $first->pelanggan,
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
            'pemilikToko',
            'tanggal_awal',
            'tanggal_akhir'
        ));
    }

    public function laporanStokHabis(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $tanggal_awal = $request->get('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggal_akhir = $request->get('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Ambil semua barang masuk yang stoknya habis
        $barangMasuk = BarangMasuk::with(['barang', 'barang.supplier', 'satuan', 'user', 'barangKeluar'])
            ->get()
            ->filter(function($item) {
                // Hitung total barang keluar untuk item ini
                $totalKeluar = $item->barangKeluar->sum('jumlah');
                // Stok habis jika stok_awal - total_keluar <= 0
                return ($item->stok_awal - $totalKeluar) <= 0;
            })
            ->filter(function($item) use ($bulan, $tahun, $tanggal_awal, $tanggal_akhir) {
                // Filter berdasarkan tanggal jika ada
                if ($tanggal_awal && $tanggal_akhir) {
                    $tanggalItem = Carbon::parse($item->created_at);
                    $tanggalAwal = Carbon::parse($tanggal_awal);
                    $tanggalAkhir = Carbon::parse($tanggal_akhir);
                    
                    // Cek apakah barang masuk dalam rentang tanggal
                    if ($tanggalItem->between($tanggalAwal, $tanggalAkhir)) {
                        return true;
                    }
                    
                    // Cek apakah ada barang keluar dalam rentang tanggal yang menyebabkan stok habis
                    $barangKeluarDalamRentang = $item->barangKeluar()
                        ->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59'])
                        ->first();
                    
                    return $barangKeluarDalamRentang ? true : false;
                } else {
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
                }
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
            'totalNilai',
            'tanggal_awal',
            'tanggal_akhir'
        ));
    }

    public function printLaporanStokHabis(Request $request)
    {
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $tanggal_awal = $request->get('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggal_akhir = $request->get('tanggal_akhir', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Ambil semua barang masuk yang stoknya habis
        $barangMasuk = BarangMasuk::with(['barang', 'barang.supplier', 'satuan', 'user', 'barangKeluar'])
            ->get()
            ->filter(function($item) {
                $totalKeluar = $item->barangKeluar->sum('jumlah');
                return ($item->stok_awal - $totalKeluar) <= 0;
            })
            ->filter(function($item) use ($bulan, $tahun, $tanggal_awal, $tanggal_akhir) {
                // Filter berdasarkan tanggal jika ada
                if ($tanggal_awal && $tanggal_akhir) {
                    $tanggalItem = Carbon::parse($item->created_at);
                    $tanggalAwal = Carbon::parse($tanggal_awal);
                    $tanggalAkhir = Carbon::parse($tanggal_akhir);
                    
                    // Cek apakah barang masuk dalam rentang tanggal
                    if ($tanggalItem->between($tanggalAwal, $tanggalAkhir)) {
                        return true;
                    }
                    
                    // Cek apakah ada barang keluar dalam rentang tanggal yang menyebabkan stok habis
                    $barangKeluarDalamRentang = $item->barangKeluar()
                        ->whereBetween('created_at', [$tanggal_awal . ' 00:00:00', $tanggal_akhir . ' 23:59:59'])
                        ->first();
                    
                    return $barangKeluarDalamRentang ? true : false;
                } else {
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
                }
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
            'pemilikToko',
            'tanggal_awal',
            'tanggal_akhir'
        ));
    }
}
