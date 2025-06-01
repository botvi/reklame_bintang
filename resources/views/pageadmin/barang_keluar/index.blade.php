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
                            <li class="breadcrumb-item active" aria-current="page">Transaksi Barang Keluar</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->
            <h6 class="mb-0 text-uppercase">Data Transaksi Barang Keluar</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Daftar Barang di Sebelah Kiri -->
                        <div class="col-md-6">
                            <h5>Daftar Barang Tersedia</h5>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="searchBarang" placeholder="Cari nama barang...">
                            </div>
                            <div class="row" id="daftarBarang">
                                @foreach($barang_masuks->take(4) as $barang)
                                <div class="col-md-6 mb-3 barang-card">
                                    <div class="card barang-item" data-id="{{ $barang->id }}" 
                                        data-nama="{{ $barang->nama_barang }}"
                                        data-harga="{{ $barang->harga_satuan }}"
                                        data-stok="{{ $barang->stok_barang }}">
                                        <div class="position-relative">
                                            <img src="{{ asset('uploads/barang_masuk/'.$barang->gambar) }}" 
                                                class="card-img-top" alt="{{ $barang->nama_barang }}"
                                                style="height: 150px; object-fit: cover;">
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $barang->nama_barang }}</h6>
                                            <p class="card-text">
                                                Stok: {{ $barang->stok_barang }}<br>
                                                Harga: Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <script>
                        document.getElementById('searchBarang').addEventListener('keyup', function() {
                            let searchText = this.value.toLowerCase();
                            let cards = document.getElementsByClassName('barang-card');
                            
                            Array.from(cards).forEach(card => {
                                let namaBarang = card.querySelector('.card-title').textContent.toLowerCase();
                                if(namaBarang.includes(searchText)) {
                                    card.style.display = '';
                                } else {
                                    card.style.display = 'none';
                                }
                            });
                        });
                        </script>

                        <!-- Form di Sebelah Kanan -->
                        <div class="col-md-6">
                            <h5>Form Transaksi Barang Keluar</h5>
                            <form action="{{ route('barang_keluar.store') }}" method="POST" id="formBarangKeluar">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" id="nama_barang" readonly>
                                    <input type="hidden" id="barang_masuk_id" name="barang_masuk_id">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Keluar</label>
                                    <input type="number" class="form-control" id="jumlah_keluar" name="jumlah_keluar" min="1">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Total Harga</label>
                                    <input type="text" class="form-control" id="total_harga" name="total_harga" readonly>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang_keluars as $index => $barang_keluar)
                                <tr>
                                    <td>{{ $barang_keluar->barang_masuk->nama_barang }}</td>
                                    <td>{{ $barang_keluar->jumlah_keluar }}</td>
                                    <td>Rp {{ number_format($barang_keluar->total_harga, 0, ',', '.') }}</td>
                                  
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nama Barang</th>
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

    @section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menangani klik pada card menggunakan event delegation
        document.addEventListener('click', function(e) {
            const card = e.target.closest('.barang-item');
            if (!card) return;
            
            // Hapus kelas selected dari semua card
            document.querySelectorAll('.barang-item').forEach(c => c.classList.remove('selected'));
            
            // Tambahkan kelas selected ke card yang diklik
            card.classList.add('selected');
            
            const id = card.dataset.id;
            const nama = card.dataset.nama;
            const harga = card.dataset.harga;
            const stok = card.dataset.stok;
            
            // Isi form dengan data barang yang dipilih
            document.getElementById('barang_masuk_id').value = id;
            document.getElementById('nama_barang').value = nama;
            document.getElementById('jumlah_keluar').max = stok;
            document.getElementById('jumlah_keluar').value = 1;
            
            // Hitung total harga awal
            hitungTotal(1, harga);
            
            // Scroll ke form
            document.getElementById('formBarangKeluar').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
        
        // Hitung total saat jumlah berubah
        document.getElementById('jumlah_keluar').addEventListener('input', function() {
            const selectedCard = document.querySelector('.barang-item.selected');
            if (!selectedCard) return;
            
            const jumlah = parseInt(this.value) || 0;
            const harga = parseFloat(selectedCard.dataset.harga) || 0;
            const stok = parseInt(selectedCard.dataset.stok) || 0;
            
            if (jumlah > stok) {
                this.value = stok;
                Swal.fire('Peringatan', 'Jumlah melebihi stok yang tersedia!', 'warning');
                return;
            }
            
            hitungTotal(jumlah, harga);
        });
        
        function hitungTotal(jumlah, harga) {
            const total = jumlah * harga;
            document.getElementById('total_harga').value = 'Rp ' + total.toLocaleString('id-ID');
        }
        
        // Submit form
        document.getElementById('formBarangKeluar').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!document.getElementById('barang_masuk_id').value) {
                Swal.fire('Peringatan', 'Pilih barang terlebih dahulu!', 'warning');
                return;
            }
            
            const jumlah = parseInt(document.getElementById('jumlah_keluar').value) || 0;
            if (jumlah <= 0) {
                Swal.fire('Peringatan', 'Jumlah barang harus lebih dari 0!', 'warning');
                return;
            }

            // Ambil nilai total harga dan bersihkan format
            const totalHargaText = document.getElementById('total_harga').value;
            const totalHarga = parseInt(totalHargaText.replace(/[^0-9]/g, ''));
            
            // Buat FormData baru
            const formData = new FormData(this);
            formData.set('total_harga', totalHarga);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(() => {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Data berhasil disimpan!',
                    icon: 'success'
                }).then(() => {
                    window.location.reload();
                });
            })
            .catch(error => {
                Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data!', 'error');
                console.error('Error:', error);
            });
        });
    });
    </script>

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

    <style>
        .barang-item {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
        }
        
        .barang-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .barang-item.selected {
            border: 3px solid #0d6efd;
            background-color: #f8f9fa;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
        }
    </style>