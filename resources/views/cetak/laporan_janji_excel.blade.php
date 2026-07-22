<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Janji_Iman_" . $startDate . "_sd_" . $endDate . ".xls");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table border="1">
        <tr>
            <th colspan="5" style="text-align: center; font-size: 16px; font-weight: bold;">
                {{ $church->nama_gereja ?? 'NAMA GEREJA / INSTANSI' }}<br>
                LAPORAN REKAPITULASI JANJI IMAN<br>
                Periode: {{ date('d/m/Y', strtotime($startDate)) }} s/d {{ date('d/m/Y', strtotime($endDate)) }}
            </th>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
        <tr>
            <th style="background-color: #f2f2f2;">No</th>
            <th style="background-color: #f2f2f2;">Tanggal Setor</th>
            <th style="background-color: #f2f2f2;">Nama Jemaat</th>
            <th style="background-color: #f2f2f2;">Keterangan</th>
            <th style="background-color: #f2f2f2;">Nominal (Rp)</th>
        </tr>
        @forelse($items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
            <td>{{ $item->jemaat ? $item->jemaat->nama_jemaat : '-' }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>{{ $item->debit }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5">Tidak ada data setoran.</td>
        </tr>
        @endforelse
        <tr>
            <th colspan="4" style="text-align: right;">Total Setoran Terkumpul</th>
            <th style="text-align: left;">{{ $totalTerkumpul }}</th>
        </tr>
    </table>
</body>
</html>
