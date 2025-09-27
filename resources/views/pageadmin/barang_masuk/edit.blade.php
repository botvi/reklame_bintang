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
                            <li class="breadcrumb-item active" aria-current="page">Barang Masuk</li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Barang Masuk</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--breadcrumb-->

            <div class="row">
                <div class="col-xl-7 mx-auto">
                    <hr />
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="bx bx-edit me-1 font-22 text-primary"></i></div>
                                <h5 class="mb-0 text-primary">Edit Barang Masuk</h5>
                            </div>
                            <hr>
                            <form action="{{ route('barang_masuk.update', $barang_masuk->id) }}" method="POST" class="row g-3" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="col-md-12">
                                    <label for="barang_id" class="form-label">Barang</label>
                                    <br>
                                    <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm mb-2">Tambah Barang Baru</a>
                                    <select class="form-control" id="barang_id" name="barang_id">
                                        <option value="">Pilih Barang</option>
                                      
                                        @foreach ($barangs as $barang)
                                            <option value="{{ $barang->id }}" {{ $barang_masuk->barang_id == $barang->id ? 'selected' : '' }}>
                                                {{ $barang->nama_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger">
                                        @foreach ($errors->get('barang_id') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                               
                              
                              
                                <div class="col-md-12">
                                    <label for="stok_awal" class="form-label">Stok Awal</label>
                                    <input type="number" step="0.01" class="form-control" id="stok_awal" name="stok_awal" value="{{ $barang_masuk->stok_awal }}" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('stok_awal') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="satuan_id" class="form-label">Satuan</label>
                                    <select class="form-control" id="satuan_id" name="satuan_id">
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan->id }}" 
                                                {{ $barang_masuk->satuan_id == $satuan->id ? 'selected' : '' }}
                                                data-jenis="{{ $satuan->jenis }}" 
                                                data-konversi="{{ $satuan->konversi_ke_dasar }}">
                                                {{ $satuan->nama_satuan }} ({{ $satuan->jenis }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-danger">
                                        @foreach ($errors->get('satuan_id') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="harga_persatuan" class="form-label" id="harga_persatuan_label">Harga Dari Supplier Per</label>
                                    <input type="number" step="0.01" class="form-control" id="harga_persatuan" name="harga_persatuan" value="{{ $barang_masuk->harga_persatuan }}" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('harga_persatuan') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="harga_modal" class="form-label">Harga Modal</label>
                                    <input type="number" step="0.01" class="form-control" id="harga_modal" name="harga_modal" value="{{ $barang_masuk->harga_modal }}" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('harga_modal') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-12">
                                    <label for="harga_jual" class="form-label">Harga Jual</label>
                                    <input type="number" step="0.01" class="form-control" id="harga_jual" name="harga_jual" value="{{ $barang_masuk->harga_jual }}" required>
                                    <small class="text-danger">
                                        @foreach ($errors->get('harga_jual') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h6 class="alert-heading"><i class="bx bx-info-circle"></i> Informasi Diskon</h6>
                                        <p class="mb-0">Atur diskon untuk barang ini. Diskon akan otomatis diterapkan saat pembelian mencapai jumlah minimal yang ditentukan.</p>
                                        <hr>
                                        <small class="text-muted">
                                            <strong>Contoh:</strong> Jika Max Pembelian = 10 dan Diskon = 15%, maka pembelian 10 unit atau lebih akan mendapat diskon 15%.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="max_pembelian_to_diskon" class="form-label">Max Pembelian untuk diskon</label>
                                    <input type="number" step="0.01" class="form-control" id="max_pembelian_to_diskon" name="max_pembelian_to_diskon" value="{{ $barang_masuk->max_pembelian_to_diskon }}" placeholder="Contoh: 10">
                                    <small class="text-muted">Jumlah minimal pembelian untuk mendapatkan diskon (opsional)</small>
                                    <small class="text-danger">
                                        @foreach ($errors->get('max_pembelian_to_diskon') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <label for="diskon" class="form-label">Diskon dalam %</label>
                                    <input type="number" step="0.01" class="form-control" id="diskon" name="diskon" value="{{ $barang_masuk->diskon }}" placeholder="Contoh: 10">
                                    <small class="text-muted">Persentase diskon yang akan diberikan (opsional)</small>
                                    <small class="text-danger">
                                        @foreach ($errors->get('diskon') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </small>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-5">Update</button>
                                    <a href="{{ route('barang_masuk.index') }}" class="btn btn-secondary px-5">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simpan harga awal untuk perbandingan
        let hargaAwal = parseFloat(document.getElementById('harga_persatuan').value) || 0;
        let satuanAwal = document.getElementById('satuan_id').value;
        
        // Fungsi untuk mengubah label harga sesuai satuan
        function updateHargaLabel() {
            const satuanSelect = document.getElementById('satuan_id');
            const hargaLabel = document.getElementById('harga_persatuan_label');
            const selectedOption = satuanSelect.options[satuanSelect.selectedIndex];
            
            if (selectedOption && selectedOption.text) {
                // Ambil nama satuan saja (tanpa jenis dalam kurung)
                const namaSatuan = selectedOption.text.split(' (')[0];
                hargaLabel.textContent = 'Harga Dari Supplier Per ' + namaSatuan;
            } else {
                hargaLabel.textContent = 'Harga Dari Supplier Per';
            }
        }
        
        // Event listener untuk perubahan satuan
        document.getElementById('satuan_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const satuanBaru = this.value;
            const jenisSatuanBaru = selectedOption.getAttribute('data-jenis');
            const konversiSatuanBaru = parseFloat(selectedOption.getAttribute('data-konversi'));
            
            // Update label harga
            updateHargaLabel();
            
            // Jika satuan berubah
            if (satuanBaru !== satuanAwal) {
                // Ambil data satuan lama
                const satuanLamaOption = document.querySelector(`#satuan_id option[value="${satuanAwal}"]`);
                const jenisSatuanLama = satuanLamaOption.getAttribute('data-jenis');
                const konversiSatuanLama = parseFloat(satuanLamaOption.getAttribute('data-konversi'));
                
                // Hitung harga baru berdasarkan konversi
                let hargaBaru = hargaAwal;
                if (konversiSatuanLama > 0 && konversiSatuanBaru > 0) {
                    hargaBaru = hargaAwal * (konversiSatuanBaru / konversiSatuanLama);
                }
                
                // Tampilkan peringatan dengan SweetAlert
                Swal.fire({
                    title: 'Perubahan Satuan',
                    html: `
                        <div class="text-start">
                            <p><strong>Satuan diubah dari:</strong></p>
                            <p>${satuanLamaOption.textContent}</p>
                            <p><strong>Menjadi:</strong></p>
                            <p>${selectedOption.textContent}</p>
                            <p><strong>Harga akan disesuaikan otomatis:</strong></p>
                            <p>Rp ${hargaAwal.toLocaleString('id-ID')} → Rp ${Math.round(hargaBaru).toLocaleString('id-ID')}</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Ya, Sesuaikan Harga',
                    cancelButtonText: 'Batal',
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Update harga
                        document.getElementById('harga_persatuan').value = Math.round(hargaBaru);
                        hargaAwal = Math.round(hargaBaru);
                        satuanAwal = satuanBaru;
                        
                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Harga telah disesuaikan dengan satuan baru',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        // Kembalikan ke satuan sebelumnya
                        this.value = satuanAwal;
                        updateHargaLabel(); // Update label kembali
                    }
                });
            }
        });
        
        // Event listener untuk perubahan barang
        document.getElementById('barang_id').addEventListener('change', function() {
            // Reset harga awal ketika barang berubah
            setTimeout(() => {
                hargaAwal = parseFloat(document.getElementById('harga_persatuan').value) || 0;
                satuanAwal = document.getElementById('satuan_id').value;
            }, 100);
        });
        
        // Update label saat halaman dimuat
        updateHargaLabel();

        // --- Script untuk menghitung harga modal otomatis ---
        const stokAwalInput = document.getElementById('stok_awal');
        const hargaPersatuanInput = document.getElementById('harga_persatuan');
        const hargaModalInput = document.getElementById('harga_modal');

        function updateHargaModal() {
            const stok = parseFloat(stokAwalInput.value) || 0;
            const harga = parseFloat(hargaPersatuanInput.value) || 0;
            hargaModalInput.value = (stok * harga).toFixed(2);
        }

        stokAwalInput.addEventListener('input', updateHargaModal);
        hargaPersatuanInput.addEventListener('input', updateHargaModal);
        // Pastikan harga_modal terupdate jika harga_persatuan diubah oleh script lain
        hargaPersatuanInput.addEventListener('change', updateHargaModal);
        // Inisialisasi saat halaman dimuat
        updateHargaModal();
    });
</script>
@endsection
