@extends('layouts.app')

@section('title', 'Dashboard Bendahara Pembangunan - SIKER')

@section('content')
<div class="space-y-6">
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white/60 backdrop-blur-md p-6 rounded-2xl border border-white/60 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Dashboard Bendahara Pembangunan</h1>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Kelola komitmen janji iman jemaat dan catatan cicilan dana pembangunan gereja.</p>
        </div>
        <div>
            <span class="px-3 py-1.5 bg-primary-50 text-primary-750 text-xs font-semibold rounded-lg border border-primary-100/50">
                Gereja Aktif: GPdI Mahanaim
            </span>
        </div>
    </div>

    <!-- Quick Stats (Matches Gambar IV.42 layout) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Komitmen Janji Iman -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Total Janji Iman</p>
            <h3 class="text-base font-bold text-slate-800 text-center mt-2.5">
                Rp. {{ number_format($totalJanjiIman, 0, ',', '.') }}
            </h3>
        </div>

        <!-- Terbayar / Terkumpul (Dikurangi Pengeluaran) -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm bg-emerald-50/20 border-emerald-100/30">
            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider text-center">Saldo Dana Pembangunan</p>
            <h3 class="text-base font-bold text-emerald-700 text-center mt-2.5">
                Rp. {{ number_format($saldoPembangunan, 0, ',', '.') }}
            </h3>
        </div>

        <!-- Sisa Piutang Janji Iman -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm bg-rose-50/20 border-rose-100/30">
            <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wider text-center">Sisa Piutang Janji</p>
            <h3 class="text-base font-bold text-rose-700 text-center mt-2.5">
                Rp. {{ number_format($sisaJanjiIman, 0, ',', '.') }}
            </h3>
        </div>

        <!-- Aktif Belum Lunas -->
        <div class="bg-white rounded-xl p-6 border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider text-center">Belum Lunas</p>
            <h3 class="text-base font-bold text-slate-800 text-center mt-2.5">
                {{ $activePledgesCount }} Orang
            </h3>
        </div>
    </div>

    <!-- Recent Payments Table (Matches Gambar IV.42 Description) -->
    <div class="bg-white rounded-xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tabel Pembayaran Janji Iman Terbaru</h3>
            <a href="{{ route('pembangunan.bayar.index', ['church_slug' => request()->route('church_slug')]) }}"
               class="text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-2.5 py-1.5 rounded-lg transition-colors">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-3">Nama Jemaat</th>
                        <th class="px-6 py-3">Tanggal Pembayaran</th>
                        <th class="px-6 py-3">Nominal</th>
                        <th class="px-6 py-3">Status Cicilan</th>
                        <th class="px-6 py-3">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($recentPayments as $payment)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-6 py-3.5 font-semibold text-slate-800">
                            {{ $payment->janjiIman && $payment->janjiIman->jemaat ? $payment->janjiIman->jemaat->nama_jemaat : 'Jemaat N/A' }}
                        </td>
                        <td class="px-6 py-3.5">{{ date('d - M - Y', strtotime($payment->tanggal_bayar)) }}</td>
                        <td class="px-6 py-3.5 font-bold text-emerald-600">
                            Rp. {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 font-medium">
                            @if($payment->transaksi)
                                @if($payment->transaksi->status === 'disetujui')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    Disetujui
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                    Pending
                                </span>
                                @endif
                            @else
                            <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-3.5">
                            @if($payment->bukti_bayar)
                            <a href="{{ asset($payment->bukti_bayar) }}" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-750 font-semibold space-x-1">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>Lihat Bukti</span>
                            </a>
                            @else
                            <span class="text-slate-400 font-medium">Tidak Ada</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada cicilan pembayaran janji iman.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
