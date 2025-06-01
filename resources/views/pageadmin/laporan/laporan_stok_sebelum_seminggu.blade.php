<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Kadaluarsa Sebelum Seminggu</title>
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
                <h3 class="fw-bold">LAPORAN STOK BARANG KADALUARSA SEBELUM SEMINGGU</h3>
                <h4>Telp : {{ $data->no_wa ?? '-' }}</h4>
                <h4>Aur Duri, Kecamatan Kuantan Mudik</h4>
                <h4>Tahun : {{ \Carbon\Carbon::parse($laporan->first()->created_at)->locale('id')->isoFormat('Y') }}</h4>
                <hr>
            </div>
        </div>
        <div class="info">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Supplier</th>
                        <th>Stok Barang</th>
                        <th>Tanggal Kadaluarsa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->satuan->nama_satuan }}</td>
                            <td>{{ $item->supplier->nama_supplier }}</td>
                            <td>{{ $item->stok_barang }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->locale('id')->isoFormat('D MMMM Y') ?? '-' }}
                            </td>


                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
        <hr>
        <div style="text-align: right; margin-top: 50px;">
            <p>Aur Duri, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
            <br><br>
            {{-- <img src="{{ asset('env/ttd.png') }}" alt="Tanda Tangan" style="width: 150px; height: auto; margin-bottom: -30px; margin-top: -50px;"> --}}
            <br><br>
            <p style="text-decoration: underline;">{{ $data->nama ?? '-' }}</p>
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
