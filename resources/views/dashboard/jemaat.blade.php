@extends('layouts.app')

@section('title', 'Dashboard Jemaat - GPdI Mahanaim')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white/60 backdrop-blur-md p-6 rounded-2xl border border-white/60">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Portal Layanan Jemaat</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau perkembangan kas keuangan gereja secara transparan dan cek status komitmen Janji Iman Anda.</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-3.5 py-1.5 bg-primary-50 text-primary-700 text-xs font-semibold rounded-lg border border-primary-100">
                Gereja Aktif: {{ $currentChurch->nama_gereja }}
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Kas Umum Infobox -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl">
                <i data-lucide="line-chart" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Saldo Kas Umum Gereja (Terverifikasi)</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($saldoKas, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-400 mt-1">Status: Terbuka &amp; Transparan</p>
            </div>
        </div>

        <!-- Personal Janji Iman Infobox -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="p-4 bg-primary-50 text-primary-600 rounded-2xl">
                <i data-lucide="heart" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Komitmen Janji Iman Anda</p>
                @if($janji)
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($janji->total_janji, 0, ',', '.') }}</h3>
                    <p class="text-xs text-slate-400 mt-1">Status: Belum Lunas. Terus semangat menyicil!</p>
                @else
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">Belum Ada Komitmen Aktif</h3>
                    <p class="text-xs text-slate-400 mt-1">Hubungi Bendahara Pembangunan untuk mendaftarkan komitmen baru Anda.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
