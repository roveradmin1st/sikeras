@extends('layouts.app')

@section('title', 'Laporan Kas Mingguan / Bulanan - SIKER')

@section('content')
<div class="space-y-6">
    
    <!-- Header & Filter -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Laporan Kas Gereja</h1>
            <p class="text-xs text-slate-500 mt-1">Laporan rekapitulasi transaksi (Kas Umum & Rayon) yang sudah divalidasi.</p>
        </div>
        
        <form method="GET" action="{{ route('pendeta.laporan.kas', ['church_slug' => request()->route('church_slug')]) }}" class="flex items-center space-x-2">
            <div>
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
            </div>
            <span class="text-slate-400 text-xs">-</span>
            <div>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 text-xs border border-slate-200 rounded-lg focus:ring-primary-500 focus:border-primary-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-50 hover:bg-primary-100 text-primary-700 text-xs font-semibold rounded-lg transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 mb-1">Total Pemasukan (Debit)</p>
            <h3 class="text-2xl font-bold text-emerald-600">Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 mb-1">Total Pengeluaran (Kredit)</p>
            <h3 class="text-2xl font-bold text-rose-600">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-gradient-to-br from-primary-600 to-primary-800 p-5 rounded-2xl border border-primary-500 shadow-md text-white">
            <p class="text-xs font-medium text-primary-100 mb-1">Saldo Bersih Periode Ini</p>
            <h3 class="text-2xl font-bold">Rp. {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <a href="{{ route('pendeta.laporan.kas.cetak', ['church_slug' => request()->route('church_slug'), 'start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-sm font-semibold rounded-xl flex items-center transition-colors border border-rose-200">
            <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Cetak PDF
        </a>
        <a href="{{ route('pendeta.laporan.kas.excel', ['church_slug' => request()->route('church_slug'), 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-xl flex items-center transition-colors border border-emerald-200">
            <i data-lucide="sheet" class="w-4 h-4 mr-2"></i> Export Excel
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Kategori / Keterangan</th>
                        <th class="px-6 py-4">Jenis Kas</th>
                        <th class="px-6 py-4 text-right">Pemasukan</th>
                        <th class="px-6 py-4 text-right">Pengeluaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-700 block">{{ $item->kategori ? $item->kategori->nama_kategori : '-' }}</span>
                            <span class="text-[10px] text-slate-400">{{ $item->keterangan }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->jenis_kas === 'kas_umum')
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-600">Kas Umum</span>
                            @elseif($item->jenis_kas === 'rayon')
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-50 text-purple-600">Kas Rayon</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-600">Kas Pembangunan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-emerald-600">
                            {{ $item->debit > 0 ? number_format($item->debit, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-rose-600">
                            {{ $item->kredit > 0 ? number_format($item->kredit, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                            Tidak ada data transaksi yang disetujui pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
