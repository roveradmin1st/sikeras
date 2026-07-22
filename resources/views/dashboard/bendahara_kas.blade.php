@extends('layouts.app')

@section('title', 'Dashboard Bendahara Kas - SIKER')

@section('content')
<div class="space-y-6">
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white/65 backdrop-blur-md p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Dashboard Bendahara Kas</h1>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Pemantauan arus kas aktif, persembahan mingguan, donasi umum, dan laporan tingkat rayon.</p>
        </div>
        <div>
            <span class="px-3 py-1.5 bg-primary-50 text-primary-750 text-xs font-semibold rounded-lg border border-primary-100/50">
                Gereja Aktif: GPdI Mahanaim
            </span>
        </div>
    </div>

    <!-- Main Indicators (Matches Gambar IV.31 description: Saldo Kas Aktif & Total Pemasukan) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Saldo Kas Aktif -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Saldo Kas Aktif (Operasional)</p>
                <h3 class="text-xl font-bold text-slate-800">
                    Rp. {{ number_format($saldoKasAktif, 0, ',', '.') }}
                </h3>
            </div>
            <div class="p-3.5 bg-primary-50 text-primary-600 rounded-xl">
                <i data-lucide="wallet" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Total Pemasukan -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Seluruh Pemasukan Kas</p>
                <h3 class="text-xl font-bold text-emerald-600">
                    Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}
                </h3>
            </div>
            <div class="p-3.5 bg-emerald-50 text-emerald-600 rounded-xl">
                <i data-lucide="trending-up" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Secondary Indicators (Matches Gambar IV.31 description: Persembahan, Donasi & Pengeluaran) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Persembahan Mingguan -->
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex items-center space-x-3.5">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <i data-lucide="coins" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Persembahan (Bulan Ini)</p>
                <h4 class="text-sm font-bold text-slate-800 mt-0.5">
                    Rp. {{ number_format($persembahanMingguan, 0, ',', '.') }}
                </h4>
            </div>
        </div>

        <!-- Donasi Umum -->
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex items-center space-x-3.5">
            <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                <i data-lucide="heart-handshake" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Donasi Umum (Bulan Ini)</p>
                <h4 class="text-sm font-bold text-slate-800 mt-0.5">
                    Rp. {{ number_format($donasiUmum, 0, ',', '.') }}
                </h4>
            </div>
        </div>

        <!-- Pengeluaran Mingguan -->
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex items-center space-x-3.5">
            <div class="p-3 bg-slate-100 text-slate-600 rounded-xl">
                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Pengeluaran (Minggu Ini)</p>
                <h4 class="text-sm font-bold text-slate-800 mt-0.5">
                    Rp. {{ number_format($pengeluaranMingguan, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>



    <!-- Laporan Kas Rayon (Matches Gambar IV.31 description: merangkum pemasukan dan pengeluaran rayon) -->
    <div class="bg-white rounded-xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Rekapitulasi Kas Tingkat Rayon</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Rayon</th>
                        <th class="px-6 py-3 text-right">Pemasukan Rayon</th>
                        <th class="px-6 py-3 text-right">Pengeluaran Rayon</th>
                        <th class="px-6 py-3 text-right">Saldo Kas Rayon</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($rayonStats as $stat)
                    <tr class="hover:bg-slate-50/20 transition-colors">
                        <td class="px-6 py-3.5 font-bold text-slate-800">{{ $stat['nama_rayon'] }}</td>
                        <td class="px-6 py-3.5 text-right font-semibold text-emerald-600">
                            Rp. {{ number_format($stat['pemasukan'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 text-right font-semibold text-rose-600">
                            Rp. {{ number_format($stat['pengeluaran'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 text-right font-bold text-slate-900 bg-slate-50/50">
                            Rp. {{ number_format($stat['saldo'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data wilayah rayon terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="bg-white rounded-xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Riwayat Transaksi Kas Terbaru</h3>
            <a href="{{ route('kas.transaksi.index', ['church_slug' => request()->route('church_slug')]) }}"
               class="text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-2.5 py-1.5 rounded-lg transition-colors">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3">Jenis</th>
                        <th class="px-6 py-3">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($recentTransactions as $tx)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-3.5">{{ date('d - M - Y', strtotime($tx->tanggal)) }}</td>
                        <td class="px-6 py-3.5">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-semibold rounded text-[9px] uppercase">
                                {{ $tx->kategori ? $tx->kategori->nama_kategori : 'Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $tx->keterangan }}</td>
                        <td class="px-6 py-3.5">
                            @if($tx->debit > 0)
                            <span class="text-emerald-600 font-semibold">Debit</span>
                            @else
                            <span class="text-rose-600 font-semibold">Kredit</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 font-bold text-slate-900">
                            Rp. {{ number_format($tx->debit > 0 ? $tx->debit : $tx->kredit, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada aktivitas transaksi kas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
