<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class MasterAkunPemilikController extends Controller
{
    public function index()
    {
        $data = User::where('role', 'pemilik_toko')->get();
        return view('pageadmin.master_akun_pemilik.index', compact('data'));
    }

    public function create()
    {
        return view('pageadmin.master_akun_pemilik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'nama' => 'required',
            'no_wa' => 'required',
            'profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'alamat' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->no_wa = $request->no_wa;
        $user->role = 'pemilik_toko';
        $user->alamat = $request->alamat;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->hasFile('profil')) {
            $file = $request->file('profil');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profil'), $fileName);
            $user->profil = $fileName;
            $user->save();
        }

        Alert::success('Success', 'Akun pemilik toko berhasil ditambahkan');
        return redirect()->route('master_akun_pemilik.index');
    }

    public function edit($id)
    {
        $pemilik = User::find($id);
        return view('pageadmin.master_akun_pemilik.edit', compact('pemilik'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'nama' => 'required',
            'no_wa' => 'required',
            'profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'alamat' => 'required',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = User::find($id);
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->no_wa = $request->no_wa;
        $user->role = 'pemilik_toko';
        $user->alamat = $request->alamat;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Update profil hanya jika ada file baru
        if ($request->hasFile('profil')) {
            // Hapus foto lama jika ada
            if ($user->profil && file_exists(public_path('profil/' . $user->profil))) {
                unlink(public_path('profil/' . $user->profil));
            }
            
            $file = $request->file('profil');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profil'), $fileName);
            $user->profil = $fileName;
        }

        $user->save();

        Alert::success('Success', 'Akun pemilik toko berhasil diubah');
        return redirect()->route('master_akun_pemilik.index');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        Alert::success('Success', 'Akun pemilik toko berhasil dihapus');
        return redirect()->route('master_akun_pemilik.index');
    }
}
