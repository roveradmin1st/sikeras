@extends('layouts.app')

@section('title', 'Daftar Belum Lunas - SIKER')

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Daftar Tunggakan Janji Iman</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar komitmen janji iman jemaat yang belum dilunasi untuk keperluan penagihan dan pengawasan pembangunan.</p>
        </div>
        <div class="flex items-center space-x-2">
            <div class="px-4 py-2 bg-rose-50 text-rose-800 text-xs font-bold rounded-xl border border-rose-100/50">
                Belum Lunas: {{ $items->count() }} Orang
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-6">
        <form method="GET" action="{{ route('pembangunan.belum-lunas.index', ['church_slug' => request()->route('church_slug')]) }}" class="flex flex-col md:flex-row md:items-end gap-4">
            
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Mulai (Opsional)</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs text-slate-600">
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bulan Mulai (Opsional)</label>
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
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tahun Mulai (Opsional)</label>
                <input type="number" name="tahun" value="{{ request('tahun') }}" placeholder="Contoh: {{ date('Y') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs text-slate-600">
            </div>

            <div class="flex items-center space-x-2 w-full md:w-1/4">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-sm">
                    Terapkan Filter
                </button>
                <a href="{{ route('pembangunan.belum-lunas.index', ['church_slug' => request()->route('church_slug')]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors text-center border border-slate-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table (Matches Gambar IV.46) -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Jemaat</th>
                        <th class="px-6 py-4">Tanggal Mulai</th>
                        <th class="px-6 py-4">Total Komitmen</th>
                        <th class="px-6 py-4">Telah Terbayar</th>
                        <th class="px-6 py-4">Sisa Tunggakan</th>
                        <th class="px-6 py-4">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/20 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $item->jemaat ? $item->jemaat->nama_jemaat : 'Jemaat N/A' }}
                        </td>
                        <td class="px-6 py-4">{{ date('d - M - Y', strtotime($item->tanggal_mulai)) }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">Rp. {{ number_format($item->total_janji, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600">Rp. {{ number_format($item->terbayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-rose-600 bg-rose-50/10">Rp. {{ number_format($item->sisa, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('pembangunan.bayar.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-primary-50 hover:bg-primary-100 text-primary-700 font-semibold rounded-lg transition-colors text-[10px]">
                                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                <span>Bayar Cicilan</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">Hebat! Seluruh jemaat telah melunasi komitmen janji imannya.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
