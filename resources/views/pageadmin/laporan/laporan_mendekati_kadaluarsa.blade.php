@extends('template-admin.layout')

@section('title', 'Laporan Barang Mendekati & Sudah Kadaluarsa')

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
                            <li class="breadcrumb-item active" aria-current="page">Laporan Barang Mendekati & Sudah Kadaluarsa</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filter Laporan Barang Mendekati & Sudah Kadaluarsa</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.mendekati_kadaluarsa') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" 
                                   value="{{ $tanggal_awal ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" 
                                   value="{{ $tanggal_akhir ?? '' }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-search"></i> Tampilkan
                            </button>
                            <a href="{{ route('laporan.mendekati_kadaluarsa.print', ['tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir]) }}" 
                               class="btn btn-success" target="_blank">
                                <i class="bx bx-printer"></i> Cetak
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Header Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        @if($tanggal_awal && $tanggal_akhir)
                            Laporan Barang Mendekati & Sudah Kadaluarsa - {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}
                        @else
                            Laporan Barang Mendekati & Sudah Kadaluarsa
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-0">
                                @if($tanggal_awal && $tanggal_akhir)
                                    Menampilkan barang yang kadaluarsa dalam rentang tanggal yang dipilih
                                @else
                                    Menampilkan barang yang akan kadaluarsa dalam 7 hari ke depan dan barang yang sudah kadaluarsa
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('laporan.mendekati_kadaluarsa.print', ['tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir]) }}" 
                               target="_blank" 
                               class="btn btn-success">
                                <i class="bx bx-printer"></i> Cetak Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <!-- Total Barang Card -->
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">{{ $totalBarang }}</h4>
                                    <p class="mb-0">Total Barang</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bx bx-package fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Nilai Card -->
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h4>
                                    <p class="mb-0">Total Nilai</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bx bx-money fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kritis Card -->
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">{{ $barangMasuk->where('sisa_hari', '<=', 3)->where('sisa_hari', '>=', 0)->count() }}</h4>
                                    <p class="mb-0">Kritis (≤3 hari)</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bx bx-error-circle fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kadaluarsa Card -->
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h4 class="mb-0">{{ $barangMasuk->where('sisa_hari', '<', 0)->count() }}</h4>
                                    <p class="mb-0">Sudah Kadaluarsa</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="bx bx-time fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data Barang Mendekati & Sudah Kadaluarsa</h5>
                </div>
                <div class="card-body">
                    @if($barangMasuk->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="datatable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Supplier</th>
                                        <th>Sisa Stok</th>
                                        <th>Satuan</th>
                                     
                                        <th>Tanggal Kadaluarsa</th>
                                        <th>Sisa Hari</th>
                                        <th>Status</th>
                                        <th>Penginput</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barangMasuk as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->barang->kode_barang }}</td>
                                            <td>{{ $item->barang->nama_barang }}</td>
                                            <td>{{ $item->barang->supplier->nama_supplier ?? '-' }}</td>
                                            <td>{{ number_format($item->sisa_stok) }}</td>
                                            <td>{{ $item->satuan->nama_satuan ?? '-' }}</td>
                                           
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($item->sisa_hari < 0)
                                                    <span class="badge bg-danger">SUDAH KADALUARSA</span>
                                                @elseif($item->sisa_hari == 0)
                                                    <span class="badge bg-danger">HARI INI KADALUARSA</span>
                                                @elseif($item->sisa_hari <= 3)
                                                    <span class="badge bg-danger">{{ $item->sisa_hari }} hari</span>
                                                @elseif($item->sisa_hari <= 7)
                                                    <span class="badge bg-warning">{{ $item->sisa_hari }} hari</span>
                                                @else
                                                    <span class="badge bg-info">{{ $item->sisa_hari }} hari</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->sisa_hari < 0)
                                                    <span class="badge bg-danger">KADALUARSA</span>
                                                @elseif($item->sisa_hari == 0)
                                                    <span class="badge bg-danger">HARI INI</span>
                                                @elseif($item->sisa_hari <= 3)
                                                    <span class="badge bg-danger">KRITIS</span>
                                                @elseif($item->sisa_hari <= 7)
                                                    <span class="badge bg-warning">PERINGATAN</span>
                                                @else
                                                    <span class="badge bg-info">AMAN</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->user->nama }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th colspan="10" class="text-end">Total Nilai:</th>
                                        <th colspan="2">Rp {{ number_format($totalNilai, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-package fs-1 text-muted"></i>
                            <h5 class="text-muted mt-2">Tidak ada data barang yang mendekati atau sudah kadaluarsa</h5>
                            <p class="text-muted">Semua barang masih dalam kondisi aman</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('vendor.sweetalert.alert')
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "pageLength": 25,
            "order": [[9, "asc"]], // Urutkan berdasarkan sisa hari
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "responsive": true
        });
    });
</script>
@endpush 