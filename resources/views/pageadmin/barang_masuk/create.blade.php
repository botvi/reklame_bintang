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
                            <li class="breadcrumb-item active" aria-current="page">Tambah Barang Masuk</li>
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
                                <div><i class="bx bx-plus-circle me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Tambah Barang Masuk</h5>
                            </div>
                            <hr>
                            <form action="{{ route('barang_masuk.store') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <label for="barang_id" class="form-label">Barang</label>
                                    <br>
                                    <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm mb-2">Tambah Barang Baru</a>
                                    <select class="form-control" id="barang_id" name="barang_id">
                                        <option value="">Pilih Barang</option>
                                        @foreach ($barangs as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger">
                                        @foreach ($errors->get('barang_id') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                
                                
                                <div class="col-md-12">
                                    <label for="tanggal_kadaluarsa" class="form-label">Tanggal Kadaluarsa</label>
                                    <p class="text-muted small">Kosongkan jika tidak ada tanggal kadaluarsa</p>
                                    <input type="date" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah tanggal kadaluarsa" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa">
                                    <small class="text-danger">
                                        @foreach ($errors->get('tanggal_kadaluarsa') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="stok_awal" class="form-label">Stok Awal</label>
                                    <input type="number" step="0.01" class="form-control" id="stok_awal" name="stok_awal" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('stok_awal') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="satuan_id" class="form-label">Satuan</label>
                                    <select class="form-control" id="satuan_id" name="satuan_id">
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->id }}">{{ $satuan->nama_satuan }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger">
                                        @foreach ($errors->get('satuan_id') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="harga_persatuan" class="form-label" id="harga_persatuan_label">Harga Per</label>
                                    <input type="number" step="0.01" class="form-control" id="harga_persatuan" name="harga_persatuan" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('harga_persatuan') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-5">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const satuanSelect = document.getElementById('satuan_id');
    const hargaLabel = document.getElementById('harga_persatuan_label');
    
    // Fungsi untuk mengubah label
    function updateHargaLabel() {
        const selectedOption = satuanSelect.options[satuanSelect.selectedIndex];
        if (selectedOption && selectedOption.text) {
            hargaLabel.textContent = 'Harga Per ' + selectedOption.text;
        } else {
            hargaLabel.textContent = 'Harga Per';
        }
    }
    
    // Event listener untuk perubahan pada select satuan
    satuanSelect.addEventListener('change', updateHargaLabel);
    
    // Update label saat halaman dimuat
    updateHargaLabel();
});
</script>
@endsection
