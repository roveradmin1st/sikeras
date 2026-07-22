@extends('layouts.app')

@section('title', 'Pengaturan Kop Surat & Instansi - SIKER')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Pengaturan Instansi & Kop Surat</h1>
        <p class="text-xs text-slate-500 mt-1">Sesuaikan informasi instansi Anda. Data ini akan ditampilkan otomatis pada Kop Surat dokumen cetak resmi (Laporan PDF).</p>
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

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Form Settings -->
        <div class="md:col-span-2 bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden">
            <form action="{{ route('admin.pengaturan.update', ['church_slug' => request()->route('church_slug')]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Instansi / Gereja</label>
                    <input type="text" name="nama_gereja" value="{{ old('nama_gereja', $church->nama_gereja ?? '') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all font-medium text-slate-800">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" placeholder="Contoh: Jl. Diponegoro No. 1, Kota XYZ"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm text-slate-800">{{ old('alamat', $church->alamat ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nomor Telepon / Kontak</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $church->no_telp ?? '') }}" placeholder="Contoh: (021) 1234567"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm text-slate-800">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Logo Kop Surat</label>
                    <input type="file" name="logo" accept="image/png, image/jpeg, image/jpg"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm text-slate-600 bg-slate-50 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1.5">* Format yang didukung: JPG, PNG. Biarkan kosong jika tidak ingin mengubah logo saat ini.</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-all shadow-md shadow-primary-500/30">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Kop Surat -->
        <div class="bg-white rounded-2xl border border-slate-150 shadow-sm p-6 flex flex-col justify-center items-center relative overflow-hidden">
            <div class="absolute inset-0 bg-slate-50/50 -z-10"></div>
            
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Preview Kop Surat</h3>

            <div class="w-full bg-white p-4 rounded-lg shadow-sm border border-slate-200 flex items-center justify-center space-x-4">
                <div class="w-16 h-16 bg-slate-100 rounded-md flex items-center justify-center overflow-hidden border border-slate-200 shrink-0">
                    @if(isset($church) && $church->path_logo)
                        <img src="{{ asset('storage/' . $church->path_logo) }}" alt="Logo" class="w-full h-full object-contain">
                    @else
                        <i data-lucide="image" class="w-6 h-6 text-slate-300"></i>
                    @endif
                </div>
                <div class="text-center flex-1 pr-4">
                    <h2 class="text-sm font-black uppercase text-slate-900 leading-tight">{{ $church->nama_gereja ?? 'NAMA INSTANSI' }}</h2>
                    <p class="text-[10px] text-slate-600 mt-0.5 leading-snug">{{ $church->alamat ?? 'Alamat instansi akan tampil di sini' }}</p>
                    <p class="text-[10px] text-slate-500">Telp: {{ $church->no_telp ?? '-' }}</p>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-slate-400 max-w-[200px] mx-auto">Kop surat ini akan otomatis digunakan pada setiap dokumen cetak PDF.</p>
            </div>
        </div>

    </div>
</div>
@endsection
