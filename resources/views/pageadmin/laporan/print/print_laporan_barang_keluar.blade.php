<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Barang Keluar {{ $bulanList[$bulan] }} {{ $tahun }}</title>
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
                <h3 class="fw-bold">LAPORAN BARANG KELUAR {{ strtoupper($bulanList[$bulan]) }}
                    NADYA BANGUNAN</h3>
                <h4>Telp : {{ $pemilikToko->no_wa ?? '-' }}</h4>
                <h4>Aur Duri, Kecamatan Kuantan Mudik</h4>
                <h4>Tahun : {{ $tahun }}</h4>
            </div>
        </div>
        <div style="border-bottom: 3px solid black; margin-top: 10px; margin-bottom: 20px;"></div>
        <div class="info">
            @if(count($result) > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Beli</th>
                            <th>Satuan</th>
                            <th>Harga Modal</th>
                            <th>Harga Jual Persatuan</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($result as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item['kode_barang'] }}</td>
                                <td>{{ $item['nama_barang'] }}</td>
                                <td>{{ number_format($item['jumlah_beli']) }}</td>
                                <td>{{ $item['satuan'] }}</td>
                                <td>Rp. {{ number_format($item['harga_modal'], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($item['harga_jual'], 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                            <tr class="very-bold">
                                <td colspan="7" style="text-align: right;">Total Pendapatan
                                    {{ $bulanList[$bulan] }} {{ $tahun }}:
                                </td>
                                <td>Rp. {{ number_format($totalNilai, 0, ',', '.') }}</td>
                            </tr>
                    </tbody>
                </table>
            @else
                <div style="text-align: center; padding: 50px;">
                    <h4>Tidak ada data barang keluar</h4>
                    <p>Untuk periode {{ $bulanList[$bulan] }} {{ $tahun }}</p>
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