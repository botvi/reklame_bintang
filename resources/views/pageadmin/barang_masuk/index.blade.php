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
                    </ol>
                </nav>
            </div>
        </div>
        <!--breadcrumb-->
        <h6 class="mb-0 text-uppercase">Data Barang Masuk</h6>
        <hr/>
        <div class="card">
            <div class="card-body">
                <a href="{{ route('barang_masuk.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Supplier</th>
                                <th>Gambar</th>
                                <th>Tanggal Kadaluarsa</th>
                                <th>Stok Awal</th>
                                <th>Harga Persatuan</th>
                                <th>Aksi</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang_masuks as $index => $barang_masuk)
                            <tr>
                                <td>{{ $barang_masuk->kode_barang }}</td>
                                <td>{{ $barang_masuk->nama_barang }}</td>
                                <td>{{ $barang_masuk->supplier->nama_supplier }}</td>
                                <td><img src="{{ asset('uploads/barang_masuk/' . $barang_masuk->gambar) }}" alt="Gambar" style="width: 100px; height: 100px;"></td>
                                <td>{{ $barang_masuk->tanggal_kadaluarsa }}</td>
                                <td>{{ $barang_masuk->stok_awal }}</td>
                                <td>Rp. {{ number_format($barang_masuk->harga_persatuan, 0, ',', '.') }} / {{ $barang_masuk->satuan->nama_satuan ?? 'Tidak Ada Satuan' }}</td>
                                <td>
                                    <a href="{{ route('barang_masuk.edit', $barang_masuk->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('barang_masuk.destroy', $barang_masuk->id) }}" method="POST" style="display:inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Supplier</th>
                                <th>Gambar</th>
                                <th>Tanggal Kadaluarsa</th>
                                <th>Stok Awal</th>
                                <th>Harga Persatuan</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection