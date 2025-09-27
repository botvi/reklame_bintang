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
                            <div class="row" id="daftarBarang" style="max-height: 400px; overflow-y: auto;">
                                @foreach($barang_masuks as $barang)
                                <div class="col-md-6 mb-3 barang-card">
                                    <div class="card barang-item" data-id="{{ $barang->id }}" 
                                        data-nama="{{ $barang->barang->nama_barang }}"
                                        data-harga_jual="{{ $barang->harga_jual }}"
                                        data-satuan="{{ $barang->satuan_id }}">
                                        <div class="position-relative">
                                            <img src="{{ asset('uploads/barang/'.$barang->barang->gambar) }}" 
                                                class="card-img-top" alt="{{ $barang->barang->nama_barang }}"
                                                style="height: 150px; object-fit: cover;">
                                        </div>
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $barang->barang->nama_barang }}</h6>
                                            <p class="card-text">STOK : {{ $barang->stok_awal }} {{ $barang->satuan->nama_satuan }}</p>
                                            <p class="card-text">HARGA : Rp {{ number_format($barang->harga_jual, 0, ',', '.') }} / {{ $barang->satuan->nama_satuan }}</p>
                                            @if($barang->max_pembelian_to_diskon && $barang->diskon)
                                                <div class="alert alert-info p-2 mb-0">
                                                    <small>
                                                        <i class="bx bx-gift"></i> 
                                                        Diskon {{ $barang->diskon }}% untuk pembelian minimal {{ $barang->max_pembelian_to_diskon }} {{ $barang->satuan->nama_satuan }}
                                                    </small>
                                                </div>
                                            @endif
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
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                                <div class="mb-3">
                                    <label class="form-label">Pelanggan</label>
                                    <select class="form-control" id="pelanggan_id" name="pelanggan_id" required>
                                        @foreach($pelanggans as $pelanggan)
                                        <option value="">Pilih Pelanggan</option>
                                        <option value="{{ $pelanggan->id }}">{{ $pelanggan->kode_pelanggan }} - {{ $pelanggan->nama_pelanggan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control bg-secondary text-white" id="nama_barang" readonly>
                                    <input type="hidden" id="barang_masuk_id" name="barang_masuk_id">
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" id="harga_jual_label">Harga Jual Per</label>
                                            <input type="number" class="form-control bg-secondary text-white" id="harga_jual" name="harga_jual" min="1" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Jumlah Beli</label>
                                            <input type="number" class="form-control" id="jumlah_beli" name="jumlah_beli" min="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Satuan</label>
                                            <select class="form-control" id="satuan_id" name="satuan_id">
                                                @foreach($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}" data-jenis="{{ $satuan->jenis }}" data-konversi="{{ $satuan->konversi_ke_dasar }}">
                                                        {{ $satuan->nama_satuan }} ({{ $satuan->jenis }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Total Harga</label>
                                    <input type="text" class="form-control bg-secondary text-white" id="total_harga" name="total_harga" readonly>
                                </div>
                                <div class="mb-3" id="diskon_info" style="display: none;">
                                    <label class="form-label">Diskon</label>
                                    <input type="text" class="form-control bg-success text-white" id="diskon_amount" readonly>
                                </div>
                                <div class="mb-3" id="total_setelah_diskon_info" style="display: none;">
                                    <label class="form-label">Total Setelah Diskon</label>
                                    <input type="text" class="form-control bg-success text-white" id="total_setelah_diskon" readonly>
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
            </div>
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
                const harga = card.dataset.harga_jual;
                const satuan = card.dataset.satuan;
                // Isi form dengan data barang yang dipilih
                document.getElementById('barang_masuk_id').value = id;
                document.getElementById('nama_barang').value = nama;
                document.getElementById('harga_jual').value = harga;
                document.getElementById('satuan_id').value = satuan;
                document.getElementById('jumlah_beli').value = 1;
                
                // Ambil jenis satuan dari barang masuk
                const barang = getBarangMasukById(id);
                const satuanBarangMasuk = getSatuanById(barang.satuan_id);
                filterSatuanByJenis(satuanBarangMasuk.jenis);
                // Set default satuan ke satuan barang masuk
                document.getElementById('satuan_id').value = satuanBarangMasuk.id;
                
                // Update label harga sesuai satuan
                updateHargaLabel();
                
                // Hitung total harga awal
                hitungTotal();
                
                // Scroll ke form
                document.getElementById('formBarangKeluar').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            
            // Hitung total saat jumlah berubah
            document.getElementById('jumlah_beli').addEventListener('input', function() {
                hitungTotal();
            });
            
            function hitungTotal() {
                const jumlah = parseInt(document.getElementById('jumlah_beli').value) || 0;
                const harga = parseFloat(document.getElementById('harga_jual').value) || 0;
                const total = jumlah * harga;
                
                // Format total harga dengan pemisah ribuan
                const formattedTotal = 'Rp ' + total.toLocaleString('id-ID');
                document.getElementById('total_harga').value = formattedTotal;
                
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
                        document.getElementById('diskon_amount').value = 'Rp ' + diskonAmount.toLocaleString('id-ID');
                        document.getElementById('total_setelah_diskon').value = 'Rp ' + totalSetelahDiskon.toLocaleString('id-ID');
                        document.getElementById('diskon_info').style.display = 'block';
                        document.getElementById('total_setelah_diskon_info').style.display = 'block';
                    } else {
                        // Sembunyikan informasi diskon
                        document.getElementById('diskon_info').style.display = 'none';
                        document.getElementById('total_setelah_diskon_info').style.display = 'none';
                    }
                }
            }
            
            // Submit form
            document.getElementById('formBarangKeluar').addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!document.getElementById('barang_masuk_id').value) {
                    Swal.fire('Peringatan', 'Pilih barang terlebih dahulu!', 'warning');
                    return;
                }
                
                const jumlah = parseInt(document.getElementById('jumlah_beli').value) || 0;
                if (jumlah <= 0) {
                    Swal.fire('Peringatan', 'Jumlah barang harus lebih dari 0!', 'warning');
                    return;
                }

                // --- VALIDASI STOK TIDAK MENCUKUPI ---
                const barangMasukId = document.getElementById('barang_masuk_id').value;
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
                // --- END VALIDASI ---

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

        /* Custom scrollbar untuk daftar barang */
        #daftarBarang::-webkit-scrollbar {
            width: 8px;
        }

        #daftarBarang::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #daftarBarang::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        #daftarBarang::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Untuk Firefox */
        #daftarBarang {
            scrollbar-width: thin;
            scrollbar-color: #888 #f1f1f1;
        }
    </style>