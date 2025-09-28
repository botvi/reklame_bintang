@extends('template-admin.layout')

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Kasir</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Point of Sale (POS)</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--breadcrumb-->
        
        <!-- Header Kasir -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h3 class="mb-1"><i class="bx bx-store me-2"></i>Point of Sale System</h3>
                                <p class="mb-0 opacity-75">Sistem kasir modern untuk transaksi barang keluar</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="me-3">
                                        <small class="d-block opacity-75">Tanggal</small>
                                        <strong id="currentDate"></strong>
                                    </div>
                                    <div>
                                        <small class="d-block opacity-75">Waktu</small>
                                        <strong id="currentTime"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Daftar Barang di Sebelah Kiri -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bx bx-package me-2 text-primary"></i>Daftar Produk</h5>
                            <div class="position-relative" style="width: 300px;">
                                <input type="text" class="form-control form-control-lg" id="searchBarang" 
                                       placeholder="🔍 Cari produk...">
                                <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                    <i class="bx bx-search text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row" id="daftarBarang" style="max-height: 500px; overflow-y: auto;">
                            @foreach($barang_masuks as $barang)
                            <div class="col-lg-4 col-md-6 mb-3 barang-card">
                                <div class="card barang-item h-100 border-0 shadow-sm" data-id="{{ $barang->id }}" 
                                    data-nama="{{ $barang->barang->nama_barang }}"
                                    data-harga_jual="{{ $barang->harga_jual }}"
                                    data-satuan="{{ $barang->satuan_id }}">
                                    <div class="position-relative overflow-hidden">
                                        <img src="{{ asset('uploads/barang/'.$barang->barang->gambar) }}" 
                                            class="card-img-top" alt="{{ $barang->barang->nama_barang }}"
                                            style="height: 180px; object-fit: cover; transition: transform 0.3s ease;">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-success rounded-pill">
                                                <i class="bx bx-check-circle me-1"></i>Stok: {{ $barang->stok_awal }}
                                            </span>
                                        </div>
                                        @if($barang->max_pembelian_to_diskon && $barang->diskon)
                                            <div class="position-absolute top-0 start-0 m-2">
                                                <span class="badge bg-warning rounded-pill">
                                                    <i class="bx bx-gift me-1"></i>{{ $barang->diskon }}% OFF
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2 text-truncate" title="{{ $barang->barang->nama_barang }}">
                                            {{ $barang->barang->nama_barang }}
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small">{{ $barang->satuan->nama_satuan }}</span>
                                            <span class="fw-bold text-primary">
                                                Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        @if($barang->max_pembelian_to_diskon && $barang->diskon)
                                            <div class="alert alert-info p-2 mb-0 small">
                                                <i class="bx bx-info-circle me-1"></i>
                                                Min. {{ $barang->max_pembelian_to_diskon }} {{ $barang->satuan->nama_satuan }} untuk diskon
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
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

            <!-- Form Kasir di Sebelah Kanan -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-gradient-success text-white border-0">
                        <h5 class="mb-0"><i class="bx bx-shopping-cart me-2"></i>Keranjang Belanja</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('barang_keluar.store') }}" method="POST" id="formBarangKeluar">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                            <!-- Pelanggan -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-user me-1"></i>Pelanggan
                                </label>
                                <select class="form-select form-select-lg" id="pelanggan_id" name="pelanggan_id" required>
                                    @foreach($pelanggans as $pelanggan)
                                    <option value="">👤 Pilih Pelanggan</option>
                                    <option value="{{ $pelanggan->id }}">{{ $pelanggan->kode_pelanggan }} - {{ $pelanggan->nama_pelanggan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Produk Terpilih -->
                            <div class="mb-4" id="selectedProduct" style="display: none;">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-package me-1"></i>Produk Terpilih
                                </label>
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <img id="selectedProductImage" src="" alt="" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" id="selectedProductName">-</h6>
                                                <small class="text-muted" id="selectedProductPrice">-</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="barang_masuk_id" name="barang_masuk_id">
                            </div>

                            <!-- Detail Transaksi -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <label class="form-label fw-bold" id="harga_jual_label">
                                        <i class="bx bx-dollar me-1"></i>Harga Jual Per
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-primary text-white">Rp</span>
                                        <input type="number" class="form-control form-control-lg" id="harga_jual" name="harga_jual" min="1" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">
                                        <i class="bx bx-hash me-1"></i>Jumlah
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <button class="btn btn-outline-secondary" type="button" id="decreaseQty">
                                            <i class="bx bx-minus"></i>
                                        </button>
                                        <input type="number" class="form-control text-center" id="jumlah_beli" name="jumlah_beli" min="1" value="1">
                                        <button class="btn btn-outline-secondary" type="button" id="increaseQty">
                                            <i class="bx bx-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">
                                        <i class="bx bx-ruler me-1"></i>Satuan
                                    </label>
                                    <select class="form-select form-select-lg" id="satuan_id" name="satuan_id">
                                        @foreach($satuans as $satuan)
                                            <option value="{{ $satuan->id }}" data-jenis="{{ $satuan->jenis }}" data-konversi="{{ $satuan->konversi_ke_dasar }}">
                                                {{ $satuan->nama_satuan }} ({{ $satuan->jenis }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Ringkasan Pembayaran -->
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold mb-3">
                                        <i class="bx bx-receipt me-1"></i>Ringkasan Pembayaran
                                    </h6>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Subtotal:</span>
                                        <span class="fw-bold" id="subtotal_display">Rp 0</span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-2" id="diskon_display" style="display: none;">
                                        <span class="text-success">
                                            <i class="bx bx-gift me-1"></i>Diskon:
                                        </span>
                                        <span class="fw-bold text-success" id="diskon_amount_display">-Rp 0</span>
                                    </div>
                                    
                                    <hr class="my-2">
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold fs-5">Total:</span>
                                        <span class="fw-bold fs-4 text-primary" id="total_display">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Keranjang Belanja -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bx bx-cart me-1"></i>Daftar Item (Keranjang)
                                </label>
                                <div class="card border-0">
                                    <div class="card-body p-0">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th class="text-end">Qty</th>
                                                    <th class="text-end">Harga</th>
                                                    <th class="text-end">Subtotal</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="cartItems"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="cartHiddenInputs"></div>
                            </div>

                            <!-- Hidden inputs legacy (tidak dipakai di backend multi-item, tapi dibiarkan) -->
                            <input type="hidden" id="total_harga" name="total_harga">
                            <input type="hidden" id="diskon_amount" name="diskon_amount">
                            <input type="hidden" id="total_setelah_diskon" name="total_setelah_diskon">

                            <!-- Tombol Aksi -->
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary btn-lg" id="addToCart">
                                    <i class="bx bx-plus-circle me-2"></i>Tambah ke Keranjang
                                </button>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bx bx-check-circle me-2"></i>Proses Transaksi
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="clearForm">
                                    <i class="bx bx-refresh me-2"></i>Reset Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                    </div>
                </div>
            </div>

            {{-- <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Beli</th>
                                    <th>Harga Jual Persatuan</th>
                                    <th>Total Harga</th>
                                    <th>Diskon</th>
                                    <th>Total Setelah Diskon</th>
                                    <th>Penginput</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang_keluars as $index => $barang_keluar)
                                <tr>
                                    <td>{{ $barang_keluar->pelanggan->kode_pelanggan }} - {{ $barang_keluar->pelanggan->nama_pelanggan }}</td>
                                    <td>{{ $barang_keluar->barang_masuk->barang->nama_barang }}</td>
                                    <td>{{ $barang_keluar->jumlah_beli }}</td>
                                    <td>Rp {{ number_format($barang_keluar->harga_jual, 0, ',', '.') }} / {{ $barang_keluar->satuan->nama_satuan ?? 'Tidak Ada Satuan' }}</td>
                                    <td>Rp {{ number_format($barang_keluar->total_harga, 0, ',', '.') }}</td>
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
                                    <th>Pelanggan</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Beli</th>
                                    <th>Harga Jual Persatuan</th>
                                    <th>Total Harga</th>
                                    <th>Diskon</th>
                                    <th>Total Setelah Diskon</th>
                                    <th>Penginput</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    @endsection

    @section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Data satuan dan barang masuk untuk JS
        const satuans = @json($satuans);
        const barangMasuks = @json($barang_masuks);

        function filterSatuanByJenis(jenis) {
            const satuanSelect = document.getElementById('satuan_id');
            satuanSelect.innerHTML = '';
            satuans.forEach(satuan => {
                if (satuan.jenis === jenis) {
                    const option = document.createElement('option');
                    option.value = satuan.id;
                    option.text = satuan.nama_satuan + ' (' + satuan.jenis + ')';
                    option.setAttribute('data-jenis', satuan.jenis);
                    option.setAttribute('data-konversi', satuan.konversi_ke_dasar);
                    satuanSelect.appendChild(option);
                }
            });
        }

        function getBarangMasukById(id) {
            return barangMasuks.find(b => b.id == id);
        }
        function getSatuanById(id) {
            return satuans.find(s => s.id == id);
        }

        // Fungsi untuk mengubah label harga sesuai satuan
        function updateHargaLabel() {
            const satuanSelect = document.getElementById('satuan_id');
            const hargaLabel = document.getElementById('harga_jual_label');
            const selectedOption = satuanSelect.options[satuanSelect.selectedIndex];
            
            if (selectedOption && selectedOption.text) {
                // Ambil nama satuan saja (tanpa jenis dalam kurung)
                const namaSatuan = selectedOption.text.split(' (')[0];
                hargaLabel.textContent = 'Harga Jual Per ' + namaSatuan;
            } else {
                hargaLabel.textContent = 'Harga Jual Per';
            }
        }

        // Update waktu real-time
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            const timeOptions = { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            };
            
            document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', dateOptions);
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', timeOptions);
        }

        // Update waktu setiap detik
        setInterval(updateDateTime, 1000);
        updateDateTime(); // Panggil sekali saat halaman dimuat

        document.addEventListener('DOMContentLoaded', function() {
            const cart = [];
            function renderCart() {
                const tbody = document.getElementById('cartItems');
                const hidden = document.getElementById('cartHiddenInputs');
                tbody.innerHTML = '';
                hidden.innerHTML = '';
                let subtotal = 0;
                let totalDiskon = 0;
                cart.forEach((it, idx) => {
                    const row = document.createElement('tr');
                    const subtotalItem = it.jumlah * it.harga;
                    subtotal += subtotalItem;
                    const barang = getBarangMasukById(it.barang_masuk_id);
                    const satuan = getSatuanById(it.satuan_id);
                    row.innerHTML = `
                        <td>${barang.barang.nama_barang}</td>
                        <td class="text-end">${it.jumlah} ${satuan.nama_satuan}</td>
                        <td class="text-end">Rp ${parseInt(it.harga).toLocaleString('id-ID')}</td>
                        <td class="text-end">Rp ${subtotalItem.toLocaleString('id-ID')}</td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-link text-danger d-flex align-items-center justify-content-center" data-rm="${idx}" style="min-width:32px;min-height:32px;">
                                <i class="bx bx-trash fs-5"></i>
                                <span class="d-none d-md-inline ms-1">Hapus</span>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);

                    // diskon per item (sama seperti di backend)
                    if (barang.max_pembelian_to_diskon && barang.diskon && it.jumlah >= barang.max_pembelian_to_diskon && it.satuan_id === barang.satuan_id) {
                        totalDiskon += (subtotalItem * barang.diskon) / 100;
                    }

                    // hidden inputs untuk backend array
                    hidden.insertAdjacentHTML('beforeend', `
                        <input type="hidden" name="items[${idx}][barang_masuk_id]" value="${it.barang_masuk_id}">
                        <input type="hidden" name="items[${idx}][jumlah_beli]" value="${it.jumlah}">
                        <input type="hidden" name="items[${idx}][harga_jual]" value="${it.harga}">
                        <input type="hidden" name="items[${idx}][satuan_id]" value="${it.satuan_id}">
                    `);
                });

                const totalBayar = subtotal - totalDiskon;
                document.getElementById('subtotal_display').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                if (totalDiskon > 0) {
                    document.getElementById('diskon_display').style.display = 'flex';
                    document.getElementById('diskon_amount_display').textContent = '-Rp ' + totalDiskon.toLocaleString('id-ID');
                } else {
                    document.getElementById('diskon_display').style.display = 'none';
                }
                document.getElementById('total_display').textContent = 'Rp ' + totalBayar.toLocaleString('id-ID');

                // juga set legacy hidden total
                document.getElementById('total_harga').value = subtotal;
                document.getElementById('diskon_amount').value = totalDiskon || '';
                document.getElementById('total_setelah_diskon').value = totalBayar || '';

                // bind remove
                tbody.querySelectorAll('[data-rm]').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const i = parseInt(this.getAttribute('data-rm'));
                        cart.splice(i, 1);
                        renderCart();
                    });
                });
            }
            // Menangani klik pada card menggunakan event delegation
            document.addEventListener('click', function(e) {
                const card = e.target.closest('.barang-item');
                if (!card) return;
                
                // Hapus kelas selected dari semua card
                document.querySelectorAll('.barang-item').forEach(c => c.classList.remove('selected'));
                
                // Tambahkan kelas selected ke card yang diklik dengan animasi
                card.classList.add('selected');
                
                const id = card.dataset.id;
                const nama = card.dataset.nama;
                const harga = card.dataset.harga_jual;
                const satuan = card.dataset.satuan;
                
                // Ambil data barang lengkap
                const barang = getBarangMasukById(id);
                const satuanBarangMasuk = getSatuanById(barang.satuan_id);
                
                // Update tampilan produk terpilih
                document.getElementById('selectedProduct').style.display = 'block';
                document.getElementById('selectedProductImage').src = card.querySelector('img').src;
                document.getElementById('selectedProductName').textContent = nama;
                document.getElementById('selectedProductPrice').textContent = `Rp ${parseInt(harga).toLocaleString('id-ID')} / ${satuanBarangMasuk.nama_satuan}`;
                
                // Isi form dengan data barang yang dipilih
                document.getElementById('barang_masuk_id').value = id;
                document.getElementById('harga_jual').value = harga;
                document.getElementById('satuan_id').value = satuan;
                document.getElementById('jumlah_beli').value = 1;
                
                // Filter satuan berdasarkan jenis
                filterSatuanByJenis(satuanBarangMasuk.jenis);
                // Set default satuan ke satuan barang masuk
                document.getElementById('satuan_id').value = satuanBarangMasuk.id;
                
                // Update label harga sesuai satuan
                updateHargaLabel();
                
                // Hitung total harga awal
                hitungTotal();
                
                // Animasi scroll ke form
                document.getElementById('formBarangKeluar').scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            });

            // Tambah ke keranjang
            document.getElementById('addToCart').addEventListener('click', function() {
                const barangMasukId = document.getElementById('barang_masuk_id').value;
                if (!barangMasukId) {
                    Swal.fire('Peringatan', 'Pilih barang terlebih dahulu!', 'warning');
                    return;
                }
                const jumlah = parseInt(document.getElementById('jumlah_beli').value) || 0;
                if (jumlah <= 0) {
                    Swal.fire('Peringatan', 'Jumlah barang harus lebih dari 0!', 'warning');
                    return;
                }
                const satuanKeluarId = parseInt(document.getElementById('satuan_id').value);
                const barang = getBarangMasukById(barangMasukId);
                const satuanMasuk = getSatuanById(barang.satuan_id);
                const satuanKeluar = getSatuanById(satuanKeluarId);
                if (barang && satuanMasuk && satuanKeluar) {
                    const stok_dasar = barang.stok_awal * satuanMasuk.konversi_ke_dasar;
                    const keluar_dasar = jumlah * satuanKeluar.konversi_ke_dasar;
                    if (stok_dasar < keluar_dasar) {
                        Swal.fire('Peringatan', 'Stok tidak mencukupi!', 'warning');
                        return;
                    }
                }
                const harga = parseFloat(document.getElementById('harga_jual').value) || 0;

                // Gabungkan item yang sama (barang_masuk_id + satuan_id) menjadi satu baris
                const existingIndex = cart.findIndex(it => it.barang_masuk_id == barangMasukId && it.satuan_id == satuanKeluarId && it.harga == harga);
                if (existingIndex >= 0) {
                    cart[existingIndex].jumlah += jumlah;
                } else {
                    cart.push({
                        barang_masuk_id: parseInt(barangMasukId),
                        jumlah: jumlah,
                        harga: Math.round(harga),
                        satuan_id: satuanKeluarId
                    });
                }
                renderCart();

                // reset pilihan jumlah saja
                document.getElementById('jumlah_beli').value = 1;
                hitungTotal();
            });
            
            // Tombol increase/decrease quantity
            document.getElementById('increaseQty').addEventListener('click', function() {
                const qtyInput = document.getElementById('jumlah_beli');
                const currentQty = parseInt(qtyInput.value) || 0;
                qtyInput.value = currentQty + 1;
                hitungTotal();
            });

            document.getElementById('decreaseQty').addEventListener('click', function() {
                const qtyInput = document.getElementById('jumlah_beli');
                const currentQty = parseInt(qtyInput.value) || 0;
                if (currentQty > 1) {
                    qtyInput.value = currentQty - 1;
                    hitungTotal();
                }
            });

            // Hitung total saat jumlah berubah
            document.getElementById('jumlah_beli').addEventListener('input', function() {
                hitungTotal();
            });

            // Tombol reset form
            document.getElementById('clearForm').addEventListener('click', function() {
                // Reset form
                document.getElementById('formBarangKeluar').reset();
                document.getElementById('selectedProduct').style.display = 'none';
                document.querySelectorAll('.barang-item').forEach(c => c.classList.remove('selected'));
                
                // Reset display
                document.getElementById('subtotal_display').textContent = 'Rp 0';
                document.getElementById('diskon_display').style.display = 'none';
                document.getElementById('total_display').textContent = 'Rp 0';
                
                // Reset hidden inputs
                document.getElementById('total_harga').value = '';
                document.getElementById('diskon_amount').value = '';
                document.getElementById('total_setelah_diskon').value = '';
            });
            
            function hitungTotal() {
                const jumlah = parseInt(document.getElementById('jumlah_beli').value) || 0;
                const harga = parseFloat(document.getElementById('harga_jual').value) || 0;
                const total = jumlah * harga;
                
                // Update display
                document.getElementById('subtotal_display').textContent = 'Rp ' + total.toLocaleString('id-ID');
                document.getElementById('total_display').textContent = 'Rp ' + total.toLocaleString('id-ID');
                
                // Format total harga dengan pemisah ribuan untuk backend
                const formattedTotal = 'Rp ' + total.toLocaleString('id-ID');
                document.getElementById('total_harga').value = total; // Kirim angka murni ke backend
                
                // Cek diskon
                const barangMasukId = document.getElementById('barang_masuk_id').value;
                const satuanKeluarId = parseInt(document.getElementById('satuan_id').value);
                if (barangMasukId) {
                    const barang = getBarangMasukById(barangMasukId);
                    // Diskon hanya berlaku jika satuan_id sama dengan satuan barang masuk
                    if (barang && barang.max_pembelian_to_diskon && barang.diskon && 
                        jumlah >= barang.max_pembelian_to_diskon && 
                        satuanKeluarId === barang.satuan_id) {
                        // Hitung diskon
                        const diskonAmount = (total * barang.diskon) / 100;
                        const totalSetelahDiskon = total - diskonAmount;
                        
                        // Tampilkan informasi diskon
                        document.getElementById('diskon_display').style.display = 'flex';
                        document.getElementById('diskon_amount_display').textContent = '-Rp ' + diskonAmount.toLocaleString('id-ID');
                        document.getElementById('total_display').textContent = 'Rp ' + totalSetelahDiskon.toLocaleString('id-ID');
                        
                        // Set hidden inputs untuk backend
                        document.getElementById('diskon_amount').value = diskonAmount;
                        document.getElementById('total_setelah_diskon').value = totalSetelahDiskon;
                    } else {
                        // Sembunyikan informasi diskon
                        document.getElementById('diskon_display').style.display = 'none';
                        document.getElementById('total_display').textContent = 'Rp ' + total.toLocaleString('id-ID');
                        
                        // Reset hidden inputs
                        document.getElementById('diskon_amount').value = '';
                        document.getElementById('total_setelah_diskon').value = '';
                    }
                }
            }
            
            // Submit form multi-item
            document.getElementById('formBarangKeluar').addEventListener('submit', function(e) {
                e.preventDefault();
                if (cart.length === 0) {
                    Swal.fire('Peringatan', 'Keranjang masih kosong. Tambahkan item dahulu.', 'warning');
                    return;
                }
                // kirim form normal (hidden inputs items[] sudah dirender)
                this.submit();
            });

            // Otomatisasi harga jual berdasarkan konversi
            document.getElementById('satuan_id').addEventListener('change', function() {
                const barangMasukId = document.getElementById('barang_masuk_id').value;
                const barang = getBarangMasukById(barangMasukId);
                if (!barang) return;
                const satuanBarangMasuk = getSatuanById(barang.satuan_id);
                const satuanKeluar = getSatuanById(parseInt(this.value));
                if (!satuanBarangMasuk || !satuanKeluar) return;
                // Rumus konversi harga jual
                let hargaBaru = barang.harga_jual * (satuanKeluar.konversi_ke_dasar / satuanBarangMasuk.konversi_ke_dasar);
                document.getElementById('harga_jual').value = Math.round(hargaBaru);
                
                // Update label harga sesuai satuan
                updateHargaLabel();
                
                hitungTotal();
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
                
                // Update label harga saat halaman dimuat
                updateHargaLabel();
            });
        });
    </script>
    @endsection

    <style>
        /* Animasi dan efek visual untuk kasir */
        .barang-item {
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .barang-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .barang-item:hover::before {
            left: 100%;
        }
        
        .barang-item:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        
        .barang-item.selected {
            border: 3px solid #28a745;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            box-shadow: 0 0 20px rgba(40, 167, 69, 0.3);
            transform: translateY(-5px);
        }

        .barang-item.selected .card-img-top {
            transform: scale(1.1);
        }

        /* Gradient backgrounds */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        /* Custom scrollbar untuk daftar barang */
        #daftarBarang::-webkit-scrollbar {
            width: 8px;
        }

        #daftarBarang::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #daftarBarang::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        #daftarBarang::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        /* Untuk Firefox */
        #daftarBarang {
            scrollbar-width: thin;
            scrollbar-color: #667eea #f1f1f1;
        }

        /* Animasi untuk form */
        .form-control:focus, .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            transform: scale(1.02);
            transition: all 0.3s ease;
        }

        /* Animasi untuk tombol */
        .btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:active::before {
            width: 300px;
            height: 300px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Animasi untuk card */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        /* Loading animation */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .loading {
            animation: pulse 1.5s ease-in-out infinite;
        }

        /* Badge animations */
        .badge {
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.1);
        }

        /* Input group animations */
        .input-group .btn {
            transition: all 0.3s ease;
        }

        .input-group .btn:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .barang-item:hover {
                transform: translateY(-4px) scale(1.01);
            }
            
            .btn:hover {
                transform: translateY(-1px);
            }
        }

        /* Custom animations untuk selected product */
        #selectedProduct {
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Animasi untuk ringkasan pembayaran */
        .card.bg-light {
            transition: all 0.3s ease;
        }

        .card.bg-light:hover {
            background-color: #f8f9fa !important;
            transform: scale(1.02);
        }

        /* Pulse effect untuk total */
        #total_display {
            transition: all 0.3s ease;
        }

        #total_display.updated {
            animation: pulse 0.6s ease-in-out;
        }
    </style>