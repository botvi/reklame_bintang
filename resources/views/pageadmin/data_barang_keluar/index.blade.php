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
                            <li class="breadcrumb-item active" aria-current="page">Data Barang Keluar</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Data Barang Keluar</h6>
            <hr/>


            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pelanggan</th>
                                    <th>Nama Barang</th>
                                    <th>Supplier</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Total Harga</th>
                                    <th>Diskon</th>
                                    <th>Total Setelah Diskon</th>
                                    <th>Penginput</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang_keluars as $index => $barang_keluar)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $barang_keluar->pelanggan->kode_pelanggan }} - {{ $barang_keluar->pelanggan->nama_pelanggan }}</td>
                                    <td>{{ $barang_keluar->barang_masuk->barang->nama_barang ?? 'N/A' }}</td>
                                    <td>{{ $barang_keluar->barang_masuk->barang->supplier->nama_supplier ?? 'N/A' }}</td>    
                                    <td>{{ $barang_keluar->jumlah_beli }} {{ $barang_keluar->satuan->nama_satuan ?? 'Tidak Ada Satuan' }}</td>
                                    <td>Rp. {{ number_format($barang_keluar->total_harga, 0, ',', '.') ?? '-' }}</td>
                                    <td>
                                        @if($barang_keluar->diskon_terpakai > 0)
                                            <span class="badge bg-success">Rp. {{ number_format($barang_keluar->diskon_terpakai, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($barang_keluar->diskon_terpakai > 0)
                                            <strong class="text-success">Rp. {{ number_format($barang_keluar->total_harga_setelah_diskon, 0, ',', '.') }}</strong>
                                        @else
                                            Rp. {{ number_format($barang_keluar->total_harga, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td>{{ $barang_keluar->user->nama }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Pelanggan</th>
                                    <th>Nama Barang</th>
                                    <th>Supplier</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Total Harga</th>
                                    <th>Diskon</th>
                                    <th>Total Setelah Diskon</th>
                                    <th>Penginput</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
