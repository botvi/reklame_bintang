<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Mendekati Kadaluarsa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section table {
            width: 100%;
        }
        .info-section td {
            padding: 3px 0;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary th, .summary td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .summary th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .main-table th, .main-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        .main-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: black;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 50px;
        }
        .signature table {
            width: 100%;
        }
        .signature td {
            text-align: center;
            padding: 10px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN BARANG MENDEKATI & SUDAH KADALUARSA</h1>
        <p>Toko Nadya</p>
        <p>Periode: {{ \Carbon\Carbon::now()->format('d/m/Y') }} - {{ \Carbon\Carbon::now()->addWeek()->format('d/m/Y') }} (termasuk barang kadaluarsa)</p>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td width="100">Tanggal Cetak</td>
                <td width="10">:</td>
                <td>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Pemilik Toko</td>
                <td>:</td>
                <td>{{ $pemilikToko->nama }}</td>
            </tr>
            <tr>
                <td>No. WhatsApp</td>
                <td>:</td>
                <td>{{ $pemilikToko->no_wa }}</td>
            </tr>
        </table>
    </div>

    <div class="summary">
        <h3>Ringkasan</h3>
        <table>
            <tr>
                <th width="200">Total Barang Mendekati & Sudah Kadaluarsa</th>
                <td width="10">:</td>
                <td>{{ $totalBarang }} item</td>
            </tr>
            <tr>
                <th>Total Nilai Barang</th>
                <td>:</td>
                <td>Rp {{ number_format($totalNilai, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Barang Kritis (≤3 hari)</th>
                <td>:</td>
                <td>{{ $barangMasuk->where('sisa_hari', '<=', 3)->where('sisa_hari', '>=', 0)->count() }} item</td>
            </tr>
            <tr>
                <th>Barang Sudah Kadaluarsa</th>
                <td>:</td>
                <td>{{ $barangMasuk->where('sisa_hari', '<', 0)->count() }} item</td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="80">Kode</th>
                <th width="120">Nama Barang</th>
                <th width="80">Supplier</th>
                <th width="50">Sisa Stok</th>
                <th width="50">Satuan</th>
                <th width="80">Harga Satuan</th>
                <th width="80">Total Nilai</th>
                <th width="80">Tanggal Kadaluarsa</th>
                <th width="60">Sisa Hari</th>
                <th width="60">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangMasuk as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->kode_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                <td class="text-center">{{ $item->sisa_stok }}</td>
                <td class="text-center">{{ $item->satuan->nama_satuan ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_persatuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') }}</td>
                <td class="text-center">
                    @if($item->sisa_hari < 0)
                        <span class="badge badge-danger">KADALUARSA</span>
                    @elseif($item->sisa_hari == 0)
                        <span class="badge badge-danger">HARI INI</span>
                    @elseif($item->sisa_hari <= 3)
                        <span class="badge badge-danger">{{ $item->sisa_hari }} hari</span>
                    @elseif($item->sisa_hari <= 7)
                        <span class="badge badge-warning">{{ $item->sisa_hari }} hari</span>
                    @else
                        <span class="badge badge-info">{{ $item->sisa_hari }} hari</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->sisa_hari < 0)
                        <span class="badge badge-danger">KADALUARSA</span>
                    @elseif($item->sisa_hari == 0)
                        <span class="badge badge-danger">HARI INI</span>
                    @elseif($item->sisa_hari <= 3)
                        <span class="badge badge-danger">KRITIS</span>
                    @elseif($item->sisa_hari <= 7)
                        <span class="badge badge-warning">PERINGATAN</span>
                    @else
                        <span class="badge badge-info">AMAN</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">Tidak ada data barang yang mendekati atau sudah kadaluarsa</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="signature">
        <table>
            <tr>
                <td width="50%"></td>
                <td width="50%">
                    <div class="signature-line">
                        <p>{{ $pemilikToko->nama }}</p>
                        <p>Pemilik Toko</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html> 