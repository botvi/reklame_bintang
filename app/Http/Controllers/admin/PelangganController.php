<?php

namespace App\Http\Controllers\admin;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::all();
        return view('pageadmin.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pageadmin.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat_pelanggan' => 'required|string|max:255',
            'no_hp_pelanggan' => 'required|string|max:255',
        ]);

        // Generate kode_pelanggan random
        $kode_pelanggan = 'PLG' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        
        // Pastikan kode unik
        while (Pelanggan::where('kode_pelanggan', $kode_pelanggan)->exists()) {
            $kode_pelanggan = 'PLG' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        }

        $data = $request->all();
        $data['kode_pelanggan'] = $kode_pelanggan;
        
        Pelanggan::create($data);
        Alert::toast('Success', 'Pelanggan berhasil ditambahkan')->position('top-end');
        return redirect()->route('pelanggan.index');
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::find($id);
        return view('pageadmin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat_pelanggan' => 'required|string|max:255',
            'no_hp_pelanggan' => 'required|string|max:255',
        ]);
        
        $pelanggan = Pelanggan::find($id);
        $pelanggan->update($request->all());
        Alert::toast('Success', 'Pelanggan berhasil diubah')->position('top-end');
        return redirect()->route('pelanggan.index');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::find($id);
        $pelanggan->delete();
        Alert::toast('Success', 'Pelanggan berhasil dihapus')->position('top-end');
        return redirect()->route('pelanggan.index');
    }
}
