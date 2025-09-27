<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Barang Masuk @if($tanggal_awal && $tanggal_akhir){{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}@else{{ $bulanList[$bulan] ?? '' }} {{ $tahun ?? '' }}@endif</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: "Times New Roman", Times, serif;
        }

        .very-bold {
            font-weight: 1000;
        }

        @page {
            margin: 2.54cm;
            box-sizing: border-box;
        }

        /* Gaya untuk memastikan gambar di kiri dan teks di tengah */
        .header {
            display: flex;
            /* Menggunakan flexbox untuk tata letak */
            align-items: center;
            /* Mengatur agar elemen sejajar secara vertikal */
            justify-content: flex-start;
            /* Mengatur konten ke kiri */
            margin-bottom: 20px;
            /* Jarak bawah untuk pemisah */
        }

        .header img {
            max-width: 100px;
            /* Atur ukuran maksimum gambar */
            margin-right: 20px;
            /* Jarak antara gambar dan teks */
        }

        .header div {
            flex-grow: 1;
            /* Mengizinkan div teks untuk mengambil ruang yang tersisa */
            text-align: center;
            /* Mengatur teks di tengah */
        }

        .header h3,
        .header p {
            margin: 0;
            /* Menghapus margin default */
        }

        .info {
            margin-bottom: 20px;
            /* Jarak bawah untuk informasi */
        }

        .info p {
            margin: 0;
            /* Menghapus margin default */
        }

        .info span {
            display: inline-block;
            /* Menjaga span pada baris yang sama */
            width: 150px;
            /* Lebar span untuk penataan */
        }

        .table-container {
            margin-bottom: 20px;
            /* Jarak bawah untuk tabel */
        }

        .table-container table {
            width: 100%;
            /* Memastikan tabel mengambil lebar penuh */
            border-collapse: collapse;
            /* Menghapus jarak antara border tabel */
        }

        .table-container th,
        .table-container td {
            border: 1px solid black;
            /* Border untuk sel tabel */
            padding: 8px;
            /* Jarak dalam sel */
            text-align: left;
            /* Teks rata kiri */
        }

        .table-container th {
            background-color: #f2f2f2;
            /* Warna latar belakang untuk header tabel */
        }

        .line {
            border-left: 2px solid black;
            display: inline-block;
            margin: 0 10px;
        }

        .line-short {
            height: 500px;
        }

        .line-long {
            height: 700px;
        }

        .page-break {
            page-break-after: always;
            /* Ensures the content after this class starts on a new page */
        }
    </style>
</head>

<body>
    <div class="pages">
        <div class="header">
            <div>
                <h3 class="fw-bold">LAPORAN BARANG MASUK 
                    @if($tanggal_awal && $tanggal_akhir)
                        {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}
                    @else
                        {{ strtoupper($bulanList[$bulan] ?? '') }} {{ $tahun ?? '' }}
                    @endif
                <h3 class="fw-bold">BINTANG REKLAME</h3>
                </h3>
                <br>
                <h4>Telp : 0853-7456-9178</h4>
                <h4>Jl. Perintis Kemerdekaan, Simpang Tiga, Kec. Kuantan Tengah, Kabupaten Kuantan Singingi, Riau 29516</h4>
                <h4>
                    @if($tanggal_awal && $tanggal_akhir)
                        Periode : {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}
                    @else
                        Tahun : {{ $tahun ?? '' }}
                    @endif
                </h4>
            </div>
        </div>
        <div style="border-bottom: 3px solid black; margin-top: 10px; margin-bottom: 20px;"></div>
        <div class="info">
            @if($barangMasuk->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Supplier</th>
                            <th>Stok Barang</th>
                            <th>Harga Persatuan Dari Supplier</th>
                            <th>Harga Modal</th>
                            <th>Penginput</th>
                            <th>Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangMasuk as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->barang->kode_barang }}</td>
                                <td>{{ $item->barang->nama_barang }}</td>
                                <td>{{ $item->satuan->nama_satuan }}</td>
                                <td>{{ $item->barang->supplier->nama_supplier }}</td>
                                <td>{{ $item->stok_awal }} {{ $item->satuan->nama_satuan }}</td>
                                <td>Rp. {{ number_format($item->harga_persatuan, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($item->harga_modal, 0, ',', '.') }}</td>
                                <td>{{ $item->user->nama }}</td>
                                <td>Rp. {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                            <tr class="very-bold">
                                <td colspan="7" style="text-align: right;">Total
                                    @if($tanggal_awal && $tanggal_akhir)
                                        {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}:
                                    @else
                                        {{ $bulanList[$bulan] ?? '' }} {{ $tahun ?? '' }}:
                                    @endif
                                </td>
                                <td>Rp. {{ number_format($totalNilai, 0, ',', '.') }}</td>
                            </tr>
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 50px;">
                    <h4>Tidak ada data barang masuk</h4>
                    <p>Untuk periode 
                        @if($tanggal_awal && $tanggal_akhir)
                            {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}
                        @else
                            {{ $bulanList[$bulan] ?? '' }} {{ $tahun ?? '' }}
                        @endif
                    </p>
                </div>
            @endif
        </div>
        <hr>
        <div style="text-align: right; margin-top: 50px;">
            <p>Aur Duri, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
            <br><br>
            {{-- <img src="{{ asset('env/ttd.png') }}" alt="Tanda Tangan" style="width: 150px; height: auto; margin-bottom: -30px; margin-top: -50px;"> --}}
            <br><br>
            <p style="text-decoration: underline;">{{ $pemilikToko->nama ?? '-' }}</p>
            <p>PIMPINAN</p>
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
