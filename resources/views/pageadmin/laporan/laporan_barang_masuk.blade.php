@extends('template-admin.layout')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Laporan</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('laporan') }}">Menu Laporan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Laporan Barang Masuk</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filter Laporan Barang Masuk</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.barang_masuk') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-select" id="bulan" name="bulan">
                                <option value="">Pilih Bulan</option>
                                @foreach($bulanList as $key => $namaBulan)
                                    <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                        {{ $namaBulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <option value="">Pilih Tahun</option>
                                @foreach($tahunList as $tahunOption)
                                    <option value="{{ $tahunOption }}" {{ $tahun == $tahunOption ? 'selected' : '' }}>
                                        {{ $tahunOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" 
                                   value="{{ $tanggal_awal ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" 
                                   value="{{ $tanggal_akhir ?? '' }}">
                        </div>
                        <div class="col-12 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-search"></i> Tampilkan
                            </button>
                            <a href="{{ route('laporan.barang_masuk.print', ['bulan' => $bulan, 'tahun' => $tahun, 'tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir]) }}" 
                               class="btn btn-success" target="_blank">
                                <i class="bx bx-printer"></i> Cetak
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">{{ $totalBarang }}</h4>
                                    <p class="mb-0">Total Barang Masuk</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bx bx-package fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h4>
                                    <p class="mb-0">Total Nilai Barang</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bx bx-money fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        @if($tanggal_awal && $tanggal_akhir)
                            Laporan Barang Masuk - {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}
                        @else
                            Laporan Barang Masuk - {{ $bulanList[$bulan] ?? '' }} {{ $tahun ?? '' }}
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($barangMasuk->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Supplier</th>
                                        <th>Stok</th>
                                        <th>Satuan</th>
                                        <th>Harga Persatuan Dari Supplier</th>
                                        <th>Harga Modal</th>
                                        <th>Harga Jual</th>
                                        <th>Tanggal Kadaluarsa</th>
                                        <th>Penginput</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barangMasuk as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                            <td>{{ $item->barang->kode_barang }}</td>
                                            <td>{{ $item->barang->nama_barang }}</td>
                                            <td>{{ $item->barang->supplier->nama_supplier }}</td>
                                            <td>{{ number_format($item->stok_awal) }}</td>
                                            <td>{{ $item->satuan->nama_satuan }}</td>
                                            <td>Rp {{ number_format($item->harga_persatuan, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->harga_modal, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                            <td>{{ Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') }}</td>
                                            <td>{{ $item->user->nama }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th colspan="11" class="text-end">Total Nilai:</th>
                                        <th>Rp {{ number_format($totalNilai, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-package fs-1 text-muted"></i>
                            <h5 class="text-muted mt-2">Tidak ada data barang masuk</h5>
                            <p class="text-muted">Untuk periode {{ $bulanList[$bulan] }} {{ $tahun }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection