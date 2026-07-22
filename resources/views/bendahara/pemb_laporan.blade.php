@extends('layouts.app')

@section('title', 'Laporan Janji Iman - SIKER')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-xl mx-auto text-center">
    <div class="p-4 bg-primary-50 text-primary-600 rounded-full inline-flex mb-4">
        <i data-lucide="file-spreadsheet" class="w-10 h-10"></i>
    </div>
    <h2 class="text-xl font-bold text-slate-900">Laporan Janji Iman</h2>
    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
        Halaman rekapitulasi, pengajuan persetujuan ke Pendeta, dan ekspor Laporan Komitmen Janji Iman Jemaat.
    </p>
    <div class="mt-6 flex justify-center space-x-3">
        <button class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center space-x-2">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Cetak PDF</span>
        </button>
    </div>
</div>
@endsection
