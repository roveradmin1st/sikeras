@extends('layouts.app')

@section('title', 'Laporan Pengeluaran Pembangunan - SIKER')

@section('content')
<div class="space-y-6">
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white/65 backdrop-blur-md p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Laporan Pengeluaran Pembangunan</h1>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Rekapitulasi transaksi pengeluaran (kredit) dari Kas Pembangunan.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('pembangunan.laporan.pengeluaran.cetak', ['church_slug' => request()->route('church_slug'), 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center space-x-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak PDF</span>
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-xl border border-slate-150 shadow-sm p-6">
        <form method="GET" action="{{ route('pembangunan.laporan.pengeluaran', ['church_slug' => request()->route('church_slug')]) }}" class="flex flex-col sm:flex-row items-end gap-4">
            <div>
                <label for="start_date" class="block text-xs font-bold text-slate-600 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label for="end_date" class="block text-xs font-bold text-slate-600 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:ring-primary-500 focus:border-primary-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-lg transition-colors">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pengeluaran (Periode Ini)</p>
                <h3 class="text-2xl font-bold text-rose-600 mt-1">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center">
                <i data-lucide="arrow-down-right" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3 text-right">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-3.5">{{ $index + 1 }}</td>
                        <td class="px-6 py-3.5">{{ date('d M Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-6 py-3.5">{{ $item->kategori ? $item->kategori->nama_kategori : '-' }}</td>
                        <td class="px-6 py-3.5">{{ $item->keterangan }}</td>
                        <td class="px-6 py-3.5 text-right font-semibold text-rose-600">
                            Rp. {{ number_format($item->kredit, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">Tidak ada pengeluaran kas pembangunan pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
