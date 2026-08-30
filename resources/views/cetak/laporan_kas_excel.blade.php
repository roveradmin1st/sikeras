<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Kas_" . $startDate . "_sd_" . $endDate . ".xls");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table border="1">
        <tr>
            <th colspan="6" style="text-align: center; font-size: 16px; font-weight: bold;">
                {{ $church->nama_gereja ?? 'NAMA GEREJA / INSTANSI' }}<br>
                LAPORAN REKAPITULASI KAS GEREJA<br>
                Periode: {{ date('d/m/Y', strtotime($startDate)) }} s/d {{ date('d/m/Y', strtotime($endDate)) }}
            </th>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2;">No</th>
            <th style="background-color: #f2f2f2;">Tanggal</th>
            <th style="background-color: #f2f2f2;">Kategori</th>
            <th style="background-color: #f2f2f2;">Keterangan</th>
            <th style="background-color: #f2f2f2;">Pemasukan (Rp)</th>
            <th style="background-color: #f2f2f2;">Pengeluaran (Rp)</th>
        </tr>
        @forelse($items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
            <td>{{ $item->kategori ? $item->kategori->nama_kategori : '-' }} ({{ $item->jenis_kas === 'kas_umum' ? 'Umum' : ($item->jenis_kas === 'rayon' ? 'Rayon' : 'Pembangunan') }})</td>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->debit }}</td>
            <td>{{ $item->kredit }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Tidak ada data transaksi.</td>
        </tr>
        @endforelse
        <tr>
            <th colspan="4" style="text-align: right;">Total Pemasukan</th>
            <th colspan="2" style="text-align: left;">{{ $totalPemasukan }}</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: right;">Total Pengeluaran</th>
            <th colspan="2" style="text-align: left;">{{ $totalPengeluaran }}</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: right;">Saldo Bersih</th>
            <th colspan="2" style="text-align: left;">{{ $saldoAkhir }}</th>
        </tr>
    </table>
</body>
</html>
