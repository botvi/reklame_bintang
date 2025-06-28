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
                                    <a href="{{ route('laporan.barang_masuk') }}" class="btn btn-outline-primary btn-lg">
                                        <i class="bx bx-package me-2"></i>
                                        Laporan Barang Masuk
                                    </a>
                                    <a href="{{ route('laporan.barang_keluar') }}" class="btn btn-outline-warning btn-lg">
                                        <i class="bx bx-exit me-2"></i>
                                        Laporan Barang Keluar
                                    </a>
                                    <a href="{{ route('laporan.stok_habis') }}" class="btn btn-outline-danger btn-lg">
                                        <i class="bx bx-x-circle me-2"></i>
                                        Laporan Stok Habis
                                    </a>
                                    <a href="{{ route('laporan.mendekati_kadaluarsa') }}" class="btn btn-outline-info btn-lg">
                                        <i class="bx bx-calendar-x me-2"></i>
                                        Laporan Mendekati Kadaluarsa
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
