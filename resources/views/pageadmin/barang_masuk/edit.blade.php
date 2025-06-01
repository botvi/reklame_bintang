@extends('template-admin.layout')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Forms</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Barang Masuk</li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Barang Masuk</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->

            <div class="row">
                <div class="col-xl-7 mx-auto">
                    <hr />
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-edit me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Edit Barang Masuk</h5>
                            </div>
                            <hr>
                            <form action="{{ route('barang_masuk.update', $barang_masuk->id) }}" method="POST" class="row g-3" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="col-md-12">
                                    <label for="kode_barang" class="form-label">Kode Barang</label>
                                    <input type="text" class="form-control" id="kode_barang" name="kode_barang"
                                        value="{{ old('kode_barang', $barang_masuk->kode_barang) }}" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('kode_barang') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="nama_barang" class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                        value="{{ old('nama_barang', $barang_masuk->nama_barang) }}" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('nama_barang') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                             
                                <div class="col-md-12">
                                    <label for="supplier_id" class="form-label">Supplier</label>
                                    <select class="form-control" id="supplier_id" name="supplier_id">
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $barang_masuk->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->nama_supplier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="satuan_id" class="form-label">Satuan</label>
                                    <select class="form-control" id="satuan_id" name="satuan_id">
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->id }}" {{ $barang_masuk->satuan_id == $satuan->id ? 'selected' : '' }}>{{ $satuan->nama_satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="harga_satuan" class="form-label">Harga Satuan</label>
                                    <input type="number" class="form-control" id="harga_satuan" name="harga_satuan" 
                                        value="{{ old('harga_satuan', $barang_masuk->harga_satuan) }}" required oninput="hitungTotal()">
                                    <small class="text-danger">
                                        @foreach ($errors->get('harga_satuan') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="stok_barang" class="form-label">Stok Barang</label>
                                    <input type="number" class="form-control" id="stok_barang" name="stok_barang" 
                                        value="{{ old('stok_barang', $barang_masuk->stok_barang) }}" required oninput="hitungTotal()">
                                    <small class="text-danger">
                                        @foreach ($errors->get('stok_barang') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="total_harga" class="form-label">Total Harga</label>
                                    <input type="number" class="form-control" id="total_harga" name="total_harga" 
                                        value="{{ old('total_harga', $barang_masuk->total_harga) }}" readonly required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('total_harga') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                                    <input type="date" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" 
                                        value="{{ old('tanggal_kadaluarsa', $barang_masuk->tanggal_kadaluarsa) }}" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('tanggal_kadaluarsa') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="gambar" class="form-label">Gambar</label>
                                    @if($barang_masuk->gambar)
                                        <div class="mb-2">
                                            <img src="{{ asset('uploads/barang_masuk/' . $barang_masuk->gambar) }}" alt="Gambar Barang Masuk" class="img-thumbnail" style="max-height: 200px">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="gambar" name="gambar">
                                    <small class="text-danger">
                                        @foreach ($errors->get('gambar') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-5">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('script')
    <script>
        function hitungTotal() {
            const hargaSatuan = document.getElementById('harga_satuan').value;
            const stokBarang = document.getElementById('stok_barang').value;
            const totalHarga = hargaSatuan * stokBarang;
            
            document.getElementById('total_harga').value = totalHarga;
        }
    </script>
    @endsection
@endsection
