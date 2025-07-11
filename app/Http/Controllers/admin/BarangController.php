<?php

namespace App\Http\Controllers\admin;

use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('supplier')->get();
        return view('pageadmin.barang.index', compact('barangs'));
    }

 
    public function create()
    {
        $suppliers = Supplier::all();
        return view('pageadmin.barang.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'nama_barang' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaFile = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move('uploads/barang', $namaFile);
        }
        Barang::create([
            'kode_barang' => $request->kode_barang,
            'supplier_id' => $request->supplier_id,
            'nama_barang' => $request->nama_barang,
            'gambar' => $namaFile,
        ]);
        Alert::toast('Success', 'Barang berhasil ditambahkan' )->position('top-end');
        return redirect()->route('barang.index');
    }

    public function edit($id)
    {
        $barang = Barang::find($id);
        $suppliers = Supplier::all();
        return view('pageadmin.barang.edit', compact('barang', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'nama_barang' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $barang = Barang::find($id);

        $data = [
            'kode_barang' => $request->kode_barang,
            'supplier_id' => $request->supplier_id,
            'nama_barang' => $request->nama_barang,
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($barang->gambar && file_exists('uploads/barang/' . $barang->gambar)) {
                unlink('uploads/barang/' . $barang->gambar);
            }
            $gambar = $request->file('gambar');
            $namaFile = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move('uploads/barang', $namaFile);
            $data['gambar'] = $namaFile;
        }

        $barang->update($data);

        Alert::toast('Success', 'Barang berhasil diubah')->position('top-end');
        return redirect()->route('barang.index');
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);
        if ($barang->gambar) {
            unlink('uploads/barang/' . $barang->gambar);
        }
        $barang->delete();
        Alert::toast('Success', 'Barang berhasil dihapus' )->position('top-end');
        return redirect()->route('barang.index');
    }
}
