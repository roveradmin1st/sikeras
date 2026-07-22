@extends('layouts.app')

@section('title', 'Buku Kas Umum - SIKER')

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Buku Kas Umum (Jurnal)</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar buku kas besar operasional yang disetujui Pendeta dengan saldo berjalan real-time.</p>
        </div>
        <div class="flex items-center space-x-2">
            <!-- Quick indicator showing overall cash balance -->
            @php
                $firstItem = $items->first();
                $currentBalance = $firstItem ? $firstItem->running_saldo : 0;
            @endphp
            <div class="px-4 py-2 bg-emerald-50 text-emerald-800 text-xs font-bold rounded-xl border border-emerald-100/50">
                Saldo Saat Ini: Rp. {{ number_format($currentBalance, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Ledger Table (Matches Gambar IV.36) -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Debit (Masuk)</th>
                        <th class="px-6 py-4">Kredit (Keluar)</th>
                        <th class="px-6 py-4">Saldo Berjalan</th>
                        <th class="px-6 py-4">Sumber Kas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/20 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-400">{{ $items->count() - $index }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ date('d - M - Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-800">{{ $item->keterangan }}</div>
                            @if($item->jemaat)
                            <div class="text-[10px] text-slate-400 mt-0.5 font-semibold">Terkait: {{ $item->jemaat->nama_jemaat }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-slate-150 text-slate-700 font-bold rounded text-[9px] uppercase tracking-wide">
                                {{ $item->kategori ? $item->kategori->nama_kategori : 'Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-emerald-600">
                            {{ $item->debit > 0 ? 'Rp. ' . number_format($item->debit, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 font-bold text-rose-600">
                            {{ $item->kredit > 0 ? 'Rp. ' . number_format($item->kredit, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4 font-black text-slate-800 bg-slate-50/50">
                            Rp. {{ number_format($item->running_saldo, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 capitalize font-semibold text-slate-500">
                            {{ str_replace('_', ' ', $item->jenis_kas) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-slate-400">Belum ada catatan buku kas umum yang disetujui.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
