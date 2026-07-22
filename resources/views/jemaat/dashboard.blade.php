@extends('layouts.app')

@section('title', 'Transparansi Kas - SIKER')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-black mb-2">Syalom, {{ auth()->user()->nama }}!</h1>
            <p class="text-emerald-100 font-medium">Selamat datang di Portal Transparansi Keuangan Gereja.</p>
            <p class="text-xs text-emerald-200 mt-4 max-w-2xl leading-relaxed">
                "Karena di mana hartamu berada, di situ juga hatimu berada." (Matius 6:21). <br>
                Halaman ini disajikan sebagai bentuk transparansi dan pertanggungjawaban pengelolaan persembahan umat.
            </p>
        </div>
        <i data-lucide="heart-handshake" class="absolute -right-10 -bottom-10 w-64 h-64 text-white opacity-10 rotate-12"></i>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-emerald-100 shadow-sm flex items-center justify-between group hover:border-emerald-300 transition-colors">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Pemasukan</p>
                <h3 class="text-2xl font-black text-emerald-600">Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="trending-up" class="w-6 h-6"></i>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-rose-100 shadow-sm flex items-center justify-between group hover:border-rose-300 transition-colors">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Pengeluaran</p>
                <h3 class="text-2xl font-black text-rose-600">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="trending-down" class="w-6 h-6"></i>
            </div>
        </div>

        <div class="bg-primary-900 p-6 rounded-2xl border border-primary-700 shadow-lg shadow-primary-900/20 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-primary-200 uppercase tracking-wider mb-1">Saldo Kas Gereja</p>
                <h3 class="text-2xl font-black text-white">Rp. {{ number_format($saldoKas, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-white/10 text-white flex items-center justify-center">
                <i data-lucide="wallet" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Riwayat Transaksi Terbaru</h2>
                <p class="text-xs text-slate-500">Mutasi kas umum dan rayon (disetujui).</p>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Kategori / Keterangan</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4 text-right">Pemasukan</th>
                        <th class="px-6 py-4 text-right">Pengeluaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($riwayatTransaksi as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500 text-xs font-medium">
                            <i data-lucide="calendar" class="w-3 h-3 inline-block mr-1 text-slate-400"></i>
                            {{ date('d M Y', strtotime($item->tanggal)) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-800 block mb-0.5">{{ $item->kategori ? $item->kategori->nama_kategori : '-' }}</span>
                            <span class="text-xs text-slate-500 italic">{{ $item->keterangan ?? 'Tidak ada keterangan' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->jenis_kas === 'kas_umum')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">Kas Umum</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-200">Kas Rayon</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($item->debit > 0)
                                <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">+ Rp. {{ number_format($item->debit, 0, ',', '.') }}</span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($item->kredit > 0)
                                <span class="font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-lg">- Rp. {{ number_format($item->kredit, 0, ',', '.') }}</span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                            <i data-lucide="folder-open" class="w-12 h-12 mx-auto text-slate-200 mb-3"></i>
                            <p class="font-medium text-slate-500">Belum ada riwayat transaksi</p>
                            <p class="text-xs mt-1">Data yang ditampilkan hanya yang sudah disetujui (divalidasi).</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
