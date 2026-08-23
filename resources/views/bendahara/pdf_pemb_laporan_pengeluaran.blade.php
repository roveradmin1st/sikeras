<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran Pembangunan - {{ $church ? $church->nama_gereja : 'Gereja' }}</title>
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
            text-align: center;
            font-weight: bold;
        }
        .summary-table th {
            background-color: #f5f5f5;
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
        .text-left { text-align: left; }
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
    <div class="report-title">Laporan Pengeluaran Kas Pembangunan</div>
    <div class="report-period">
        Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}
    </div>

    <!-- Summary -->
    <table class="summary-table">
        <tr>
            <th>Total Pengeluaran (Periode Ini)</th>
        </tr>
        <tr>
            <td style="color: red; font-size: 14px;">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Data -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Kategori</th>
                <th width="40%">Keterangan</th>
                <th width="20%">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                <td class="text-center">{{ $item->kategori ? $item->kategori->nama_kategori : '-' }}</td>
                <td class="text-left">{{ $item->keterangan }}</td>
                <td class="text-right">{{ number_format($item->kredit, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada pengeluaran kas pembangunan pada periode ini.</td>
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
                    @php
                        $pendeta = \App\Models\User::where('role', 'pendeta')->first();
                        $namaPendeta = $pendeta ? $pendeta->nama : '_________________';
                    @endphp
                    <div class="signature-name">{{ $namaPendeta }}</div>
                </td>
                <td>
                    <p>Dibuat Oleh,</p>
                    <p><strong>Bendahara Pembangunan</strong></p>
                    <div class="signature-name">{{ auth()->user()->nama }}</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
