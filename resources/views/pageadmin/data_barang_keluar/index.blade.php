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
                                    <th>Nama Barang</th>
                                    <th>Supplier</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang_keluars as $index => $barang_keluar)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $barang_keluar->barang_masuk->nama_barang }}</td>
                                    <td>{{ $barang_keluar->barang_masuk->supplier->nama_supplier }}</td>    
                                    <td>{{ $barang_keluar->jumlah_beli }} {{ $barang_keluar->satuan->nama_satuan }}</td>
                                    <td>Rp. {{ number_format($barang_keluar->total_harga, 0, ',', '.') ?? '-' }}</td>
        
                                  
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Supplier</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Total Harga</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
