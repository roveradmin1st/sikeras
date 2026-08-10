<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kas - {{ $startDate }} sd {{ $endDate }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 8px; margin-bottom: 2px; position: relative; }
        .header-bottom-line { border-bottom: 1px solid #000; margin-bottom: 20px; }
        .header h1 { font-size: 16px; margin: 0; line-height: 1.2; }
        .header h2 { font-size: 18px; margin: 0; line-height: 1.2; font-weight: bold;}
        .header h3 { font-size: 14px; margin: 0; line-height: 1.2; }
        .header p { margin: 2px 0; font-size: 10px; }
        .header .underline-text { text-decoration: underline; font-size: 12px; font-weight: bold; margin-top: 5px; margin-bottom: 2px;}
        .logo { width: 70px; height: 85px; position: absolute; top: 0; left: 0; border: 1px solid #000; padding: 3px; object-fit: contain; }
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
        <div style="padding-left: 80px;">
            <h1>GEREJA PANTEKOSTA di INDONESIA</h1>
            <h2>JEMAAT "MAHANAIM" SADA PERARIH</h2>
            <h3>DESA PERARIH KEC.MERDEKA KAB KARO</h3>
            <div class="underline-text">Lembagakeagamaan(GEREJA)</div>
            <p>Kep.DIRJEN (KRISTEN) PROTESTAN DEPARTEMEN AGAMA RI.NO.30 TH.1988,TGL 3-2-1988</p>
            <p>(d/hBeslitPemerintah No.33 tgl,3-6-1937,STBL No.368,ket.DEPAG RI.E VII/156/929/73.tgl.2-10-1937</p>
        </div>
    </div>
    <div class="header-bottom-line"></div>

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
