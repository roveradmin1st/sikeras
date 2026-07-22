@extends('layouts.app')

@section('title', 'Janji Imanku - SIKER')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    
    <!-- Welcome Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Janji Imanku</h1>
        <p class="text-xs text-slate-500 mt-1">Pantau progres dan riwayat pembayaran Janji Iman serta partisipasi pembangunan Anda secara personal.</p>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-semibold text-rose-800">{{ $errors->first() }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Kolom Kiri: Daftar Komitmen Janji Iman -->
        <div class="space-y-6">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center">
                <i data-lucide="target" class="w-4 h-4 mr-2 text-primary-500"></i> Komitmen Janji Iman
            </h2>
            
            @forelse($janjiImanList as $janji)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden group hover:border-primary-300 transition-all">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full mb-2 inline-block">
                                Mulai: {{ date('M Y', strtotime($janji->tanggal_janji)) }}
                            </span>
                            <h3 class="text-lg font-black text-slate-800">Rp. {{ number_format($janji->nominal_janji, 0, ',', '.') }}</h3>
                        </div>
                        @if($janji->sisa <= 0)
                            <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center" title="Lunas">
                                <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center" title="Berjalan">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-xs mb-1 font-medium">
                            <span class="text-slate-500">Progres Pelunasan</span>
                            <span class="{{ $janji->persentase >= 100 ? 'text-emerald-600 font-bold' : 'text-primary-600 font-bold' }}">{{ $janji->persentase }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $janji->persentase >= 100 ? 'bg-emerald-500' : 'bg-primary-500' }} h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $janji->persentase }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Sudah Dibayar</p>
                            <p class="text-sm font-bold text-emerald-600">Rp. {{ number_format($janji->total_dibayar, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Sisa Tagihan</p>
                            <p class="text-sm font-bold {{ $janji->sisa <= 0 ? 'text-slate-400' : 'text-rose-600' }}">Rp. {{ number_format(max(0, $janji->sisa), 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white border border-dashed border-slate-300 rounded-3xl p-10 text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="shield-question" class="w-8 h-8 text-slate-400"></i>
                </div>
                <h3 class="text-slate-700 font-bold mb-1">Belum Ada Komitmen</h3>
                <p class="text-xs text-slate-500 max-w-[250px]">Anda belum memiliki catatan Janji Iman di sistem kami.</p>
            </div>
            @endforelse
        </div>

        <!-- Kolom Kanan: Riwayat Partisipasi/Setoran -->
        <div class="space-y-6">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center">
                <i data-lucide="history" class="w-4 h-4 mr-2 text-primary-500"></i> Riwayat Partisipasi Pembangunan
            </h2>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Berikut adalah daftar sumbangan pembangunan / setoran janji iman Anda yang telah disetujui oleh gereja.
                    </p>
                </div>
                
                <div class="divide-y divide-slate-100">
                    @forelse($donasiPembangunan as $donasi)
                    <div class="p-5 hover:bg-slate-50 transition-colors flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                                <i data-lucide="check" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $donasi->keterangan ?? 'Setoran Pembangunan' }}</p>
                                <p class="text-xs text-slate-400 mt-0.5 flex items-center">
                                    <i data-lucide="calendar" class="w-3 h-3 mr-1"></i> {{ date('d M Y', strtotime($donasi->tanggal)) }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black text-emerald-600">+ Rp {{ number_format($donasi->debit, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="p-10 text-center">
                        <i data-lucide="inbox" class="w-8 h-8 text-slate-300 mx-auto mb-3"></i>
                        <p class="text-sm font-medium text-slate-500">Belum ada riwayat partisipasi.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
