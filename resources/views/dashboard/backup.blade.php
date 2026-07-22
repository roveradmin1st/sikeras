@extends('layouts.app')

@section('title', 'Backup & Restorasi - SIKER')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-xl mx-auto text-center">
    <div class="p-4 bg-primary-50 text-primary-600 rounded-full inline-flex mb-4">
        <i data-lucide="database" class="w-10 h-10"></i>
    </div>
    <h2 class="text-xl font-bold text-slate-900">Backup &amp; Restorasi Database</h2>
    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
        Fitur pencadangan dan pemulihan data untuk menjaga keamanan data jemaat dan keuangan gereja. Cadangan database dapat diunduh secara manual.
    </p>
    <div class="mt-6 flex justify-center space-x-3">
        <button class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center space-x-2">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span>Backup Sekarang</span>
        </button>
        <button class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors flex items-center space-x-2">
            <i data-lucide="upload" class="w-4 h-4"></i>
            <span>Restore Database</span>
        </button>
    </div>
</div>
@endsection
