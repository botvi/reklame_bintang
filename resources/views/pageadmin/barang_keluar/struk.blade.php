<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - REKLAME BINTANG</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: "Courier New", Courier, monospace; font-size: 12px; margin: 0; color: #000; }
        .receipt { width: 80mm; margin: 0 auto; padding: 8px 6px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; }
        .small { font-size: 11px; }
        .mt-6 { margin-top: 6px; }
        .actions { margin: 12px 0; text-align: center; }
        .btn { padding: 8px 12px; border: 1px solid #333; background: #fff; cursor: pointer; margin-right: 8px; text-decoration: none; color: #000; }
        .no-print { display: block; }
        @media print {
            @page { size: 80mm auto; margin: 2mm; }
            body { margin: 0; }
            .no-print { display: none !important; }
            .receipt { width: 100%; padding: 0; }
        }
    </style>
    <script>
        function printReceipt() { window.print(); }
    </script>
    </head>
<body>
    <div class="receipt" id="printArea">
        <header class="center">
            <div class="bold" style="font-size: 16px;">REKLAME BINTANG</div>
            <div class="small">Jl. .................................................</div>
            <div class="small">Telp: ..............................</div>
            <div class="line"></div>
            <div class="small">Tanggal: {{ now()->format('d/m/Y') }}  Jam: {{ now()->format('H:i') }}</div>
            <div class="small">No: {{ str_pad($barang_keluar->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="small">Kasir: {{ optional($barang_keluar->user)->nama ?? 'Admin' }}</div>
        </header>

        @if($barang_keluar->pelanggan)
        <div class="small mt-6">
            Pelanggan: {{ $barang_keluar->pelanggan->kode_pelanggan }} - {{ $barang_keluar->pelanggan->nama_pelanggan }}
        </div>
        @endif

        <div class="line"></div>

        <section>
            <div class="bold">{{ $barang_keluar->barang_masuk->barang->nama_barang }}</div>
            <div class="row small">
                <div>{{ $barang_keluar->jumlah_beli }} {{ optional($barang_keluar->satuan)->nama_satuan }} x Rp {{ number_format($barang_keluar->harga_jual, 0, ',', '.') }}</div>
                <div>Rp {{ number_format($barang_keluar->total_harga, 0, ',', '.') }}</div>
            </div>
        </section>

        <div class="line"></div>

        <section>
            @if($barang_keluar->diskon_terpakai > 0)
            <div class="row small">
                <div>Diskon</div>
                <div>-Rp {{ number_format($barang_keluar->diskon_terpakai, 0, ',', '.') }}</div>
            </div>
            @endif
            <div class="row bold">
                <div>TOTAL</div>
                <div>Rp {{ number_format($barang_keluar->diskon_terpakai > 0 ? $barang_keluar->total_harga_setelah_diskon : $barang_keluar->total_harga, 0, ',', '.') }}</div>
            </div>
        </section>

        <div class="line"></div>

        <footer class="center small mt-6">
            Terima kasih telah berbelanja!
        </footer>
    </div>

    <div class="actions no-print">
        <button class="btn" onclick="printReceipt()">Cetak Struk</button>
        <a class="btn" href="{{ route('barang_keluar.index') }}">Transaksi Baru</a>
    </div>
</body>
</html>
