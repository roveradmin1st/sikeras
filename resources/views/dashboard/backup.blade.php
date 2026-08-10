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
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl mb-4 text-left">
        <div class="flex">
            <div class="flex-shrink-0"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i></div>
            <div class="ml-3"><p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p></div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl mb-4 text-left">
        <div class="flex">
            <div class="flex-shrink-0"><i data-lucide="alert-triangle" class="w-5 h-5 text-rose-500"></i></div>
            <div class="ml-3">
                <p class="text-sm font-semibold text-rose-800">Ada kesalahan:</p>
                <ul class="list-disc list-inside text-xs text-rose-700 mt-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="mt-8 flex flex-col md:flex-row justify-center items-center gap-6">
        <!-- Form Backup -->
        <form action="{{ route('admin.backup.do', ['church_slug' => request()->route('church_slug')]) }}" method="POST" class="w-full md:w-auto text-center border-b md:border-b-0 md:border-r border-slate-100 pb-6 md:pb-0 md:pr-6">
            @csrf
            <h3 class="text-sm font-bold text-slate-700 mb-2">Download Data</h3>
            <p class="text-xs text-slate-500 mb-4 px-4 md:px-0">Unduh seluruh database (Format .sql)</p>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors inline-flex items-center space-x-2 w-full md:w-auto justify-center">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span>Backup Sekarang</span>
            </button>
        </form>
        
        <!-- Form Restore -->
        <form action="{{ route('admin.backup.restore', ['church_slug' => request()->route('church_slug')]) }}" method="POST" enctype="multipart/form-data" class="w-full md:w-auto text-center md:pl-6" onsubmit="return confirm('PERINGATAN: Memulihkan database akan menimpa semua data yang ada saat ini. Anda yakin ingin melanjutkan?');">
            @csrf
            <h3 class="text-sm font-bold text-slate-700 mb-2">Upload Data</h3>
            <p class="text-xs text-slate-500 mb-4 px-4 md:px-0">Pulihkan database dari file .sql Anda</p>
            
            <input type="file" name="backup_file" required accept=".sql,.txt" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 transition-all cursor-pointer mb-3">
            
            <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition-colors inline-flex items-center space-x-2 w-full md:w-auto justify-center shadow-sm">
                <i data-lucide="upload" class="w-4 h-4"></i>
                <span>Restore Database</span>
            </button>
        </form>
    </div>
</div>
@endsection
