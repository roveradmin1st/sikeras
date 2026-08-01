<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kas - {{ $church ? $church->nama_gereja : 'Gereja' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        /* Kop Surat (Header) Styles */
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .kop-surat table {
            width: 100%;
            border: none;
        }
        .kop-surat td {
            vertical-align: middle;
            border: none;
        }
        .logo-container {
            width: 15%;
            text-align: center;
        }
        .logo {
            width: 90px;
            height: auto;
            border: 1px solid #000;
            padding: 2px;
        }
        .header-text-container {
            width: 85%;
            text-align: center;
            line-height: 1.3;
        }
        .header-title-1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .header-title-2 {
            font-size: 14px;
            margin: 0;
            text-transform: uppercase;
        }
        .header-title-3 {
            font-size: 14px;
            margin: 0;
            text-transform: uppercase;
        }
        .header-title-4 {
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            margin: 3px 0;
        }
        .header-desc {
            font-size: 11px;
            margin: 0;
        }
        
        /* Report Title */
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .report-period {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
        }

        /* Summary Cards */
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
            font-weight: bold;
        }
        .summary-table th {
            background-color: #f5f5f5;
            text-align: center;
        }

        /* Main Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 6px;
        }
        .data-table th {
            background-color: #e5eef5;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Signatures */
        .signature-section {
            width: 100%;
            margin-top: 50px;
        }
        .signature-section table {
            width: 100%;
            border: none;
        }
        .signature-section td {
            border: none;
            text-align: center;
            width: 50%;
        }
        .signature-name {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <table>
            <tr>
                <td class="logo-container">
                    <!-- Using public_path for DomPDF to access the file locally -->
                    @if(file_exists(public_path('images/logo-gpdi.png')))
                        <img src="{{ public_path('images/logo-gpdi.png') }}" class="logo" alt="Logo">
                    @endif
                </td>
                <td class="header-text-container">
                    <div class="header-title-1">GEREJA PANTEKOSTA di INDONESIA</div>
                    <div class="header-title-2">JEMAAT "MAHANAIM" SADA PERARIH</div>
                    <div class="header-title-3">DESA PERARIH KEC.MERDEKA KAB KARO</div>
                    <div class="header-title-4">Lembagakeagamaan(GEREJA)</div>
                    <div class="header-desc">Kep.DIRJEN (KRISTEN) PROTESTAN DEPARTEMEN AGAMA RI.NO.30 TH.1988,TGL 3-2-1988</div>
                    <div class="header-desc">(d/hBeslitPemerintah No.33 tgl,3-6-1937,STBL No.368,ket.DEPAG RI.E VII/156/929/73.tgl.2-10-1937</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Title -->
    <div class="report-title">Laporan Keuangan (Kas Umum)</div>
    <div class="report-period">
        Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}
        @if(!empty($rayonFilter))
            <br>Tampilan Khusus: <strong>{{ $rayonFilter }}</strong>
        @endif
    </div>

    <!-- Summary -->
    <table class="summary-table">
        <tr>
            <th>Total Pemasukan (Debit)</th>
            <th>Total Pengeluaran (Kredit)</th>
            <th>Saldo Periode Ini</th>
        </tr>
        <tr>
            <td style="color: green;">Rp. {{ number_format($totalDebit, 0, ',', '.') }}</td>
            <td style="color: red;">Rp. {{ number_format($totalKredit, 0, ',', '.') }}</td>
            <td>Rp. {{ number_format($saldoAkhir, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Data -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Kategori</th>
                <th width="35%">Keterangan</th>
                <th width="15%">Debit (Rp)</th>
                <th width="15%">Kredit (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td>{{ $item->kategori ? $item->kategori->nama_kategori : '-' }}</td>
                <td>
                    {{ $item->keterangan }}
                    @if($item->jemaat && $item->jemaat->rayon)
                    <br><small style="color: gray;">(Rayon: {{ $item->jemaat->rayon->nama_rayon }})</small>
                    @endif
                </td>
                <td class="text-right">{{ $item->debit > 0 ? number_format($item->debit, 0, ',', '.') : '-' }}</td>
                <td class="text-right">{{ $item->kredit > 0 ? number_format($item->kredit, 0, ',', '.') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <table>
            <tr>
                <td>
                    <p>Mengetahui,</p>
                    <p><strong>Gembala Sidang</strong></p>
                    <div class="signature-name">Pdt. _________________</div>
                </td>
                <td>
                    <p>Dibuat Oleh,</p>
                    <p><strong>Bendahara Kas</strong></p>
                    <div class="signature-name">{{ auth()->user()->nama }}</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
