@extends('template-admin.layout')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-primary text-white text-center">
                                <h4 class="mb-0">Menu Laporan</h4>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-3">
                                    <a href="{{ route('laporan.barang_masuk') }}" class="btn btn-primary btn-lg">
                                        <i class="bx bx-download me-2"></i>
                                        Laporan Semua Barang Masuk
                                    </a>
                                    <a href="{{ route('laporan.barang_keluar') }}" class="btn btn-success btn-lg">
                                        <i class="bx bx-upload me-2"></i>
                                        Laporan Semua Barang Keluar
                                    </a>
                                    <a href="{{ route('laporan.barang_masuk_per_bulan') }}" class="btn btn-info btn-lg">
                                        <i class="bx bx-calendar me-2"></i>
                                        Laporan Barang Masuk Per Bulan
                                    </a>
                                    <a href="{{ route('laporan.barang_keluar_per_bulan') }}" class="btn btn-warning btn-lg">
                                        <i class="bx bx-calendar-check me-2"></i>
                                        Laporan Barang Keluar Per Bulan
                                    </a>
                                    <a href="{{ route('laporan.stok_habis') }}" class="btn btn-danger btn-lg">
                                        <i class="bx bx-error-circle me-2"></i>
                                        Laporan Stok Barang Habis
                                    </a>
                                    <a href="{{ route('laporan.stok_sebelum_seminggu') }}" class="btn btn-secondary btn-lg">
                                        <i class="bx bx-time me-2"></i>
                                        Laporan Kadaluarsa Sebelum Seminggu
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
