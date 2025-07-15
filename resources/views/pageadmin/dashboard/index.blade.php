@extends('template-admin.layout')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!-- Notifikasi Stok Menipis -->
            @if($stok_menipis->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card radius-10 border-start border-0 border-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="widgets-icons-2 rounded-circle bg-gradient-warning text-white me-3">
                                    <i class='bx bxs-error-alt'></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-warning">Peringatan Stok Menipis</h6>
                                    <p class="mb-0 font-13">Berikut adalah daftar barang dengan stok awal yang menipis (≤ 20):</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-warning">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Supplier</th>
                                            <th>Stok Awal</th>
                                            <th>Satuan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stok_menipis as $index => $barang)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $barang->barang->kode_barang }}</td>
                                            <td>{{ $barang->barang->nama_barang }}</td>
                                            <td>{{ $barang->barang->supplier->nama_supplier }}</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">{{ $barang->stok_awal }}</span>
                                            </td>
                                            <td>{{ $barang->satuan->nama_satuan }}</td>
                                            <td>
                                                @if($barang->stok_awal <= 10)
                                                    <span class="badge bg-danger">KRITIS</span>
                                                @elseif($barang->stok_awal <= 15)
                                                    <span class="badge bg-warning text-dark">MENIPIS</span>
                                                @else
                                                    <span class="badge bg-info">PERHATIAN</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Barang Masuk</p>
                                    <h4 class="my-1 text-info">{{ $barang_masuk }}</h4>
                                    <p class="mb-0 font-13">Jumlah barang masuk</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i
                                        class='bx bxs-cart-add'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Barang Keluar</p>
                                    <h4 class="my-1 text-danger">{{ $barang_keluar }}</h4>
                                    <p class="mb-0 font-13">Jumlah barang keluar</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i
                                        class='bx bxs-cart-download'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Supplier</p>
                                    <h4 class="my-1 text-success">{{ $supplier }}</h4>
                                    <p class="mb-0 font-13">Jumlah supplier</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                        class='bx bxs-user-detail'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Stok Menipis</p>
                                    <h4 class="my-1 text-warning">{{ $stok_menipis->count() }}</h4>
                                    <p class="mb-0 font-13">Barang dengan stok ≤ 20</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-warning text-white ms-auto"><i
                                        class='bx bxs-error-alt'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="row row-cols-1 row-cols-md-3 mt-3">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Harga Modal</p>
                                    <h4 class="my-1 text-primary">Rp {{ number_format($total_harga_modal, 0, ',', '.') }}</h4>
                                    <p class="mb-0 font-13">Akumulasi modal barang masuk</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-white ms-auto"><i class='bx bx-money'></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Harga Jual</p>
                                    <h4 class="my-1 text-success">Rp {{ number_format($total_harga_jual, 0, ',', '.') }}</h4>
                                    <p class="mb-0 font-13">Akumulasi penjualan</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-success text-white ms-auto"><i class='bx bx-cart'></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Keuntungan</p>
                                    <h4 class="my-1 text-warning">Rp {{ number_format($total_keuntungan, 0, ',', '.') }}</h4>
                                    <p class="mb-0 font-13">Akumulasi keuntungan</p>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-warning text-white ms-auto"><i class='bx bx-line-chart'></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // Notifikasi stok menipis saat halaman dimuat
    @if($stok_menipis->count() > 0)
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Peringatan Stok Menipis!',
                html: `
                    <div class="text-start">
                        <p>Ditemukan <strong>{{ $stok_menipis->count() }}</strong> barang dengan stok awal yang menipis (≤ 20).</p>
                        <div class="mt-3">
                            <strong>Daftar barang:</strong>
                            <ul class="text-start mt-2">
                                @foreach($stok_menipis->take(5) as $barang)
                                    <li>{{ $barang->nama_barang }} - {{ $barang->stok_awal }} {{ $barang->satuan->nama_satuan }}</li>
                                @endforeach
                                @if($stok_menipis->count() > 5)
                                    <li>... dan {{ $stok_menipis->count() - 5 }} barang lainnya</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                `,
                icon: 'warning',
                confirmButtonText: 'Lihat Detail',
                showCancelButton: true,
                cancelButtonText: 'Tutup',
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Scroll ke bagian notifikasi stok menipis
                    const notifikasiSection = document.querySelector('.card.border-warning');
                    if (notifikasiSection) {
                        notifikasiSection.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            });
        });
    @endif
</script>
@endsection
