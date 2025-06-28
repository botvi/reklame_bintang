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
                            <li class="breadcrumb-item active" aria-current="page">Laporan Stok Habis</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filter Laporan Stok Habis</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.stok_habis') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select class="form-select" id="bulan" name="bulan" required>
                                @foreach($bulanList as $key => $namaBulan)
                                    <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                        {{ $namaBulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select class="form-select" id="tahun" name="tahun" required>
                                @foreach($tahunList as $tahunOption)
                                    <option value="{{ $tahunOption }}" {{ $tahun == $tahunOption ? 'selected' : '' }}>
                                        {{ $tahunOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-search"></i> Tampilkan
                            </button>
                            <a href="{{ route('laporan.stok_habis.print', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
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
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">{{ $totalBarang }}</h4>
                                    <p class="mb-0">Total Barang Stok Habis</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bx bx-x-circle fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h4>
                                    <p class="mb-0">Total Nilai Stok Habis</p>
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
                        Laporan Stok Habis - {{ $bulanList[$bulan] }} {{ $tahun }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($barangMasuk->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Supplier</th>
                                        <th>Stok Awal</th>
                                        <th>Total Keluar</th>
                                        <th>Sisa Stok</th>
                                        <th>Status</th>
                                        <th>Satuan</th>
                                        <th>Harga Satuan</th>
                                        <th>Total Nilai Habis</th>
                                        <th>Tanggal Kadaluarsa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barangMasuk as $index => $item)
                                        @php
                                            $totalKeluar = $item->barangKeluar->sum('jumlah');
                                            $sisaStok = $item->stok_awal - $totalKeluar;
                                            $nilaiHabis = $sisaStok * $item->harga_persatuan;
                                            $status = $sisaStok <= 0 ? 'HABIS' : 'HAMPIR HABIS';
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                            <td>{{ $item->kode_barang }}</td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>{{ $item->supplier->nama_supplier }}</td>
                                            <td>{{ number_format($item->stok_awal) }}</td>
                                            <td>{{ number_format($totalKeluar) }}</td>
                                            <td class="text-danger fw-bold">{{ number_format($sisaStok) }}</td>
                                            <td><span class="badge bg-danger">{{ $status }}</span></td>
                                            <td>{{ $item->satuan->nama_satuan }}</td>
                                            <td>Rp {{ number_format($item->harga_persatuan, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($nilaiHabis, 0, ',', '.') }}</td>
                                            <td>{{ Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th colspan="11" class="text-end">Total Nilai Stok Habis:</th>
                                        <th colspan="2">Rp {{ number_format($totalNilai, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-x-circle fs-1 text-muted"></i>
                            <h5 class="text-muted mt-2">Tidak ada data stok habis</h5>
                            <p class="text-muted">Untuk periode {{ $bulanList[$bulan] }} {{ $tahun }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 