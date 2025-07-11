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
        ]);
        Barang::create($request->all());
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
        ]);
        $barang = Barang::find($id);
        $barang->update($request->all());
        Alert::toast('Success', 'Barang berhasil diubah' )->position('top-end');
        return redirect()->route('barang.index');
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);
        $barang->delete();
        Alert::toast('Success', 'Barang berhasil dihapus' )->position('top-end');
        return redirect()->route('barang.index');
    }
}
