<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kas - {{ $startDate }} sd {{ $endDate }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; text-transform: uppercase; margin: 0; }
        .header p { margin: 2px 0; font-size: 12px; }
        .logo { width: 60px; height: 60px; position: absolute; top: 0; left: 0; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 15px; text-decoration: underline; }
        .info { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #000; }
        th { background-color: #f2f2f2; padding: 8px; text-align: center; font-size: 11px; }
        td { padding: 6px; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary { width: 40%; float: right; }
        .summary table, .summary th, .summary td { border: none; }
        .summary th { text-align: left; background: none; padding: 4px; }
        .summary td { padding: 4px; text-align: right; }
        .signature { margin-top: 50px; width: 100%; }
        .signature-box { float: right; width: 200px; text-align: center; }
        .signature-box p { margin: 0 0 60px 0; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <div class="header">
        @if($church->path_logo)
            <!-- DomPDF can read from public_path() -->
            <img src="{{ public_path('storage/' . $church->path_logo) }}" class="logo">
        @endif
        <h1>{{ $church->nama_gereja ?? 'NAMA GEREJA / INSTANSI' }}</h1>
        <p>{{ $church->alamat ?? 'Alamat Gereja' }}</p>
        <p>Telp: {{ $church->no_telp ?? '-' }}</p>
    </div>

    <div class="title">
        LAPORAN REKAPITULASI KAS GEREJA
    </div>

    <div class="info">
        <strong>Periode:</strong> {{ date('d/m/Y', strtotime($startDate)) }} s/d {{ date('d/m/Y', strtotime($endDate)) }}<br>
        <strong>Dicetak Tanggal:</strong> {{ date('d/m/Y H:i:s') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="25%">Kategori</th>
                <th width="25%">Keterangan</th>
                <th width="15%">Pemasukan (Rp)</th>
                <th width="15%">Pengeluaran (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td>{{ $item->kategori ? $item->kategori->nama_kategori : '-' }} 
                    ({{ $item->jenis_kas === 'kas_umum' ? 'Umum' : 'Rayon' }})
                </td>
                <td>{{ $item->keterangan }}</td>
                <td class="text-right">{{ $item->debit > 0 ? number_format($item->debit, 0, ',', '.') : '-' }}</td>
                <td class="text-right">{{ $item->kredit > 0 ? number_format($item->kredit, 0, ',', '.') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <th>Total Pemasukan</th>
                <td>Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Pengeluaran</th>
                <td>Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th><strong>Saldo Bersih</strong></th>
                <td><strong>Rp. {{ number_format($saldoAkhir, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="clear"></div>

    <div class="signature">
        <div class="signature-box">
            <p>Mengetahui,<br>Pendeta / Gembala Sidang</p>
            <p>_______________________</p>
        </div>
    </div>

</body>
</html>
