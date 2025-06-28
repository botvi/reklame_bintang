<?php

namespace App\Http\Controllers;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\RegPosyandu;
use App\Models\BarangMasuk;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Routing\Controller;

class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        $hariIni = Carbon::now();
        $satuMingguKedepan = Carbon::now()->addWeek();
        
        // Ambil semua barang masuk yang memiliki tanggal kadaluarsa
        $barangMasuk = BarangMasuk::with(['supplier', 'satuan', 'user', 'barangKeluar'])
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

        // Tampilkan alert jika ada barang yang mendekati kadaluarsa
        if ($totalBarang > 0) {
            Alert::warning('Peringatan Kadaluarsa', "Ada {$totalBarang} barang yang mendekati atau sudah kadaluarsa dengan total nilai Rp " . number_format($totalNilai, 0, ',', '.'));
        }

        return view('auth.login', compact('totalBarang', 'totalNilai', 'barangMasuk', 'pemilikToko'));
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|captcha',
        ]);
    
        $credentials = $request->only('username', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role == 'admin') {
                Alert::success('Login Successful', 'Welcome back, Admin!');
                return redirect()->route('dashboard');
            } elseif ($user->role == 'pemilik_toko') {
                Alert::success('Login Successful', 'Welcome back, Pemilik Toko!');
                return redirect()->route('dashboard');
            } else {
                Auth::logout();
                Alert::error('Login Failed', 'You are not authorized to access this area.');
                return redirect('/');
            }
        }
    
        Alert::error('Login Failed', 'The provided credentials do not match our records.');
        return back();
    }
    


    /**
     * Handle logout.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        session()->flush();
        Alert::info('Logged Out', 'You have been logged out.');
        return redirect('/');
    }
}