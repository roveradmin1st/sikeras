@extends('layouts.app')

@section('title', 'Laporan Janji Iman - SIKER')

@section('content')
<div class="space-y-6">
    
    <!-- Header & Filter -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Laporan Janji Iman</h1>
            <p class="text-xs text-slate-500 mt-1">Laporan rekapitulasi setoran cicilan Janji Iman yang sudah divalidasi.</p>
        </div>
        
        <form method="GET" action="{{ route('pendeta.laporan.janji', ['church_slug' => request()->route('church_slug')]) }}" class="flex items-center space-x-2">
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
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 p-5 rounded-2xl border border-emerald-500 shadow-md text-white">
        <p class="text-xs font-medium text-emerald-100 mb-1">Total Setoran Terkumpul (Periode Ini)</p>
        <h3 class="text-3xl font-bold">Rp. {{ number_format($totalTerkumpul, 0, ',', '.') }}</h3>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        <a href="{{ route('pendeta.laporan.janji.cetak', ['church_slug' => request()->route('church_slug'), 'start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-sm font-semibold rounded-xl flex items-center transition-colors border border-rose-200">
            <i data-lucide="file-text" class="w-4 h-4 mr-2"></i> Cetak PDF
        </a>
        <a href="{{ route('pendeta.laporan.janji.excel', ['church_slug' => request()->route('church_slug'), 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-xl flex items-center transition-colors border border-emerald-200">
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
                        <th class="px-6 py-4">Tanggal Setor</th>
                        <th class="px-6 py-4">Nama Jemaat</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-right">Nominal Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $item->jemaat ? $item->jemaat->nama_jemaat : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] text-slate-500">{{ $item->keterangan }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-emerald-600">
                            {{ $item->debit > 0 ? 'Rp. ' . number_format($item->debit, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                            Tidak ada data setoran janji iman yang disetujui pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
