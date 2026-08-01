@extends('layouts.app')

@section('title', 'Dashboard Pendeta - SIKER')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="bg-gradient-to-br from-primary-900 to-slate-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-primary-900/20">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-primary-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500 rounded-full blur-3xl opacity-20"></div>
        
        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold tracking-tight mb-2">Syalom, {{ Auth::user()->nama }}!</h1>
            <p class="text-primary-100 max-w-xl text-sm leading-relaxed mb-6">
                Ini adalah ringkasan sistem informasi keuangan jemaat. Anda memiliki otoritas tertinggi untuk memvalidasi seluruh transaksi dari Bendahara Kas dan Bendahara Pembangunan sebelum data disahkan ke laporan.
            </p>
            
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-medium backdrop-blur-sm">
                    <i data-lucide="shield-check" class="w-4 h-4 mr-1.5 text-primary-300"></i>
                    Akses Validator Aktif
                </span>
                <span class="text-xs text-primary-200">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Validations Pending Section -->
    <div>
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
            <i data-lucide="bell-ring" class="w-5 h-5 mr-2 text-amber-500"></i>
            Tugas Validasi Anda
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- Kas Umum Pending -->
            <div class="bg-white rounded-2xl p-6 border border-amber-200 shadow-sm shadow-amber-100 flex items-start justify-between group hover:border-amber-300 transition-colors">
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Transaksi Kas Umum & Rayon</p>
                    <div class="flex items-end space-x-3">
                        <h3 class="text-3xl font-extrabold text-amber-600">{{ $pendingKas }}</h3>
                        <span class="text-xs font-medium text-amber-600/70 mb-1 pb-0.5">Menunggu Validasi</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center">
                    <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                </div>
                <a href="{{ route('pendeta.approval.kas', ['church_slug' => request()->route('church_slug')]) }}" class="absolute inset-0 z-10"></a>
            </div>

            <!-- Pembangunan Pending -->
            <div class="bg-white rounded-2xl p-6 border border-emerald-200 shadow-sm shadow-emerald-100 flex items-start justify-between group hover:border-emerald-300 transition-colors relative">
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Setoran Janji Iman</p>
                    <div class="flex items-end space-x-3">
                        <h3 class="text-3xl font-extrabold text-emerald-600">{{ $pendingJanji }}</h3>
                        <span class="text-xs font-medium text-emerald-600/70 mb-1 pb-0.5">Menunggu Validasi</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <i data-lucide="home" class="w-6 h-6"></i>
                </div>
                <a href="{{ route('pendeta.approval.janji', ['church_slug' => request()->route('church_slug')]) }}" class="absolute inset-0 z-10"></a>
            </div>

        </div>
    </div>

    <!-- Quick Access Laporan -->
    <div>
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
            <i data-lucide="file-bar-chart-2" class="w-5 h-5 mr-2 text-primary-600"></i>
            Akses Laporan Final
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('pendeta.laporan.kas', ['church_slug' => request()->route('church_slug')]) }}" class="bg-white p-5 rounded-2xl border border-slate-150 hover:border-primary-300 hover:shadow-md transition-all flex items-center justify-between group">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Laporan Kas Mingguan</h4>
                        <p class="text-xs text-slate-500">Lihat rekapitulasi persembahan & pengeluaran</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300 group-hover:text-primary-500 transition-colors"></i>
            </a>

            <a href="{{ route('pendeta.laporan.janji', ['church_slug' => request()->route('church_slug')]) }}" class="bg-white p-5 rounded-2xl border border-slate-150 hover:border-primary-300 hover:shadow-md transition-all flex items-center justify-between group">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Laporan Janji Iman</h4>
                        <p class="text-xs text-slate-500">Pantau progres komitmen pembangunan</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-slate-300 group-hover:text-primary-500 transition-colors"></i>
            </a>
        </div>
    </div>

</div>
@endsection
