@extends('layouts.app')

@section('title', 'Semua Laporan Transaksi - SIKER')

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Semua Laporan Transaksi</h1>
            <p class="text-xs text-slate-500 mt-1">Laporan keseluruhan transaksi (Kas Umum & Pembangunan) gereja.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-6">
        <form method="GET" action="{{ route('admin.reports', ['church_slug' => request()->route('church_slug')]) }}" class="flex flex-col md:flex-row md:items-end gap-4">
            
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Spesifik (Opsional)</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs text-slate-600">
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bulan (Opsional)</label>
                <select name="bulan" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs text-slate-600">
                    <option value="">Semua Bulan</option>
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ request('bulan') == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tahun (Opsional)</label>
                <input type="number" name="tahun" value="{{ request('tahun') }}" placeholder="Contoh: {{ date('Y') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs text-slate-600">
            </div>

            <div class="flex items-center space-x-2 w-full md:w-1/4">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-sm">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.reports', ['church_slug' => request()->route('church_slug')]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors text-center border border-slate-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Kategori / Jenis Kas</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/20 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ date('d - M - Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-800">{{ $item->keterangan }}</div>
                            @if($item->jemaat)
                                <div class="text-[10px] text-slate-400 mt-0.5">Terkait: {{ $item->jemaat->nama_jemaat }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-slate-150 text-slate-700 font-bold rounded text-[9px] uppercase tracking-wide">
                                {{ $item->kategori ? $item->kategori->nama_kategori : 'Umum' }}
                            </span>
                            <div class="text-[9px] text-slate-400 mt-1 uppercase">{{ str_replace('_', ' ', $item->jenis_kas) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status === 'disetujui')
                                <span class="text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded text-[10px]">Disetujui</span>
                            @elseif($item->status === 'ditolak')
                                <span class="text-rose-600 font-bold bg-rose-50 px-2 py-1 rounded text-[10px]">Ditolak</span>
                            @else
                                <span class="text-amber-600 font-bold bg-amber-50 px-2 py-1 rounded text-[10px]">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            @if($item->debit > 0)
                                <span class="text-emerald-600">+ Rp. {{ number_format($item->debit, 0, ',', '.') }}</span>
                            @else
                                <span class="text-rose-600">- Rp. {{ number_format($item->kredit, 0, ',', '.') }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">Belum ada transaksi ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
