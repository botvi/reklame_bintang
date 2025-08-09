<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Stok Habis {{ $bulanList[$bulan] }} {{ $tahun }}</title>
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
                <h3 class="fw-bold">LAPORAN STOK HABIS 
                    @if($tanggal_awal && $tanggal_akhir)
                        {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}
                    @else
                        {{ strtoupper($bulanList[$bulan] ?? '') }} {{ $tahun ?? '' }}
                    @endif
                    NADYA BANGUNAN</h3>
                <h4>Telp : {{ $pemilikToko->no_wa ?? '-' }}</h4>
                <h4>Aur Duri, Kecamatan Kuantan Mudik</h4>
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
                            <th>Supplier</th>
                            <th>Status</th>
                            <th>Tanggal Kadaluarsa</th>
                            <th>Penginput</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangMasuk as $key => $item)
                            @php
                                $totalKeluar = $item->barangKeluar->sum('jumlah');
                                $sisaStok = $item->stok_awal - $totalKeluar;
                                $nilaiHabis = $sisaStok * $item->harga_persatuan;
                                $status = $sisaStok <= 0 ? 'HABIS' : 'HAMPIR HABIS';
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->barang->kode_barang }}</td>
                                <td>{{ $item->barang->nama_barang }}</td>
                                <td>{{ $item->barang->supplier->nama_supplier }}</td>
                                <td style="color: red; font-weight: bold;">{{ $status }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->locale('id')->isoFormat('D MMMM Y') ?? '-' }}
                                </td>
                                <td>{{ $item->user->nama }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 50px;">
                    <h4>Tidak ada data stok habis</h4>
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
            <p>Pemilik Nadya Bangunan</p>
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html> 