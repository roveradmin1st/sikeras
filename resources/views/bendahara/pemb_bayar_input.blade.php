@extends('layouts.app')

@section('title', 'Input Pembayaran Janji Iman - SIKER')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <!-- Header with Back Button (Matches Gambar IV.44) -->
    <div>
        <a href="{{ route('dashboard', ['church_slug' => request()->route('church_slug')]) }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-primary-600 transition-colors mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
            Kembali Ke Dashboard
        </a>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Input Pembayaran Janji Iman</h1>
        <p class="text-xs text-slate-500 mt-1">Formulir untuk mencatat pembayaran/cicilan janji iman jemaat.</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-500"></i>
            </div>
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

    <!-- Form Input (Matches Gambar IV.44) -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden p-8">
        <form method="POST" action="{{ route('pembangunan.bayar.store', ['church_slug' => request()->route('church_slug')]) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-slate-700">Nama Jemaat</label>
                <select name="id_janji" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-slate-50 focus:bg-white">
                    <option value="">-- Pilih Jemaat yang memiliki Janji Iman Aktif --</option>
                    @foreach($janjiList as $janji)
                        <option value="{{ $janji->id_janji }}">
                            {{ $janji->jemaat->nama_jemaat }} - (Sisa: Rp. {{ number_format($janji->total_janji - $janji->pembayaran()->sum('jumlah_bayar'), 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-slate-700">Tanggal Pembayaran</label>
                <input type="date" name="tanggal_bayar" required value="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-slate-50 focus:bg-white">
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-slate-700">Nominal Pembayaran</label>
                <input type="number" name="jumlah_bayar" min="0" required placeholder="Nominal..."
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-slate-50 focus:bg-white">
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-slate-700">Pilihan Cicilan</label>
                <select name="pilihan_cicilan" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-slate-50 focus:bg-white">
                    <option value="cicilan">Pilih Cicilan</option>
                    <option value="1">Cicilan Ke-1</option>
                    <option value="2">Cicilan Ke-2</option>
                    <option value="3">Cicilan Ke-3</option>
                    <option value="4">Cicilan Ke-4</option>
                    <option value="5">Cicilan Ke-5</option>
                    <option value="lunas">Pelunasan</option>
                </select>
                <p class="text-xs text-slate-400 mt-1">Hanya label tambahan opsional (sesuai field pada Gambar IV.44).</p>
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-slate-700">Upload Bukti Transfer / Kwitansi (Opsional)</label>
                <input type="file" name="bukti_bayar" accept="image/*"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-slate-50 focus:bg-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('dashboard', ['church_slug' => request()->route('church_slug')]) }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-primary-500/30">
                    Simpan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
