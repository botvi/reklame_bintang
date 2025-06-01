<?php

namespace App\Http\Controllers\admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('pageadmin.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('pageadmin.supplier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat_supplier' => 'required|string|max:255',
            'no_hp_supplier' => 'required|string|max:255',
        ]);
        Supplier::create($request->all());
        Alert::toast('Success', 'Supplier berhasil ditambahkan' )->position('top-end');
        return redirect()->route('supplier.index');
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return view('pageadmin.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'alamat_supplier' => 'required|string|max:255',
            'no_hp_supplier' => 'required|string|max:255',
        ]);
        $supplier = Supplier::find($id);
        $supplier->update($request->all());
        Alert::toast('Success', 'Supplier berhasil diubah' )->position('top-end');
        return redirect()->route('supplier.index');
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        Alert::toast('Success', 'Supplier berhasil dihapus' )->position('top-end');
        return redirect()->route('supplier.index');
    }
}
