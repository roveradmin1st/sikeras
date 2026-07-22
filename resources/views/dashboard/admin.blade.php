@extends('layouts.app')

@section('title', 'Dashboard Admin - SIKER')

@section('content')
<div class="space-y-6">
    
    <!-- Top Dashboard Header Actions (Matches Image 24 [Laporan] button placement) -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Dashboard Overview</h1>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Ringkasan kondisi dan aktivitas keuangan jemaat secara real-time.</p>
        </div>
        <div>
            <a href="{{ route('admin.reports', ['church_slug' => request()->route('church_slug')]) }}"
               class="flex items-center space-x-2 px-4 py-2 bg-slate-100 border border-slate-200 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                <i data-lucide="file-text" class="w-4 h-4 text-slate-400"></i>
                <span>Laporan</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards (100% Matches Gambar IV.16 / Image 24) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Kas Umum -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Total Kas Umum</p>
            <h3 class="text-base font-bold text-slate-800 text-center mt-2.5">
                Rp. {{ number_format($totalKasUmum, 0, ',', '.') }}
            </h3>
        </div>
        <!-- Card 2: Kas Pembangunan -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Kas Pembangunan</p>
            <h3 class="text-base font-bold text-slate-800 text-center mt-2.5">
                Rp. {{ number_format($kasPembangunan, 0, ',', '.') }}
            </h3>
        </div>
        <!-- Card 3: Jumlah Jemaat -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Jumlah Jemaat</p>
            <h3 class="text-base font-bold text-slate-800 text-center mt-2.5">
                {{ $jumlahJemaat }} Orang
            </h3>
        </div>
        <!-- Card 4: Jumlah Transaksi -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Jumlah Transaksi</p>
            <h3 class="text-base font-bold text-slate-800 text-center mt-2.5">
                {{ $jumlahTransaksi }} Transaksi
            </h3>
        </div>
    </div>

    <!-- Pemasukan & Pengeluaran Terbaru (Matches Gambar IV.16) -->
    <div class="bg-white rounded-xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pemasukan &amp; Pengeluaran Terbaru</h3>
            <a href="{{ route('admin.reports', ['church_slug' => request()->route('church_slug')]) }}"
               class="text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-2.5 py-1.5 rounded-lg transition-colors">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3">Jenis</th>
                        <th class="px-6 py-3">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($recentTransactions as $tx)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-3.5">{{ date('d - M - Y', strtotime($tx->tanggal)) }}</td>
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
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada transaksi terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Janji Iman Terbaru (Matches Gambar IV.16) -->
    <div class="bg-white rounded-xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/55">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Janji Iman Terbaru</h3>
            <a href="{{ route('admin.reports', ['church_slug' => request()->route('church_slug')]) }}"
               class="text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-2.5 py-1.5 rounded-lg transition-colors">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Nama Jemaat</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($recentPledges as $pledge)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-3.5">{{ date('d - M - Y', strtotime($pledge->tanggal_mulai)) }}</td>
                        <td class="px-6 py-3.5 font-medium text-slate-800">{{ $pledge->jemaat->nama_jemaat }}</td>
                        <td class="px-6 py-3.5">
                            @if($pledge->status === 'lunas')
                            <span class="text-emerald-600 font-semibold">Lunas</span>
                            @else
                            <span class="text-amber-600 font-semibold">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 font-bold text-slate-900">
                            Rp. {{ number_format($pledge->total_janji, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data komitmen janji iman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
