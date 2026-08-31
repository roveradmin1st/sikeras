@extends('layouts.app')

@section('title', 'Pembayaran Janji Iman - SIKER')

@section('content')
<div class="space-y-6" x-data="{ editItem: null }">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Setoran Cicilan Janji Iman</h1>
            <p class="text-xs text-slate-500 mt-1">Pencatatan pembayaran cicilan jemaat untuk dana pembangunan dan sinkronisasi otomatis kas pembangunan.</p>
        </div>
        <!-- Tombol tambah dihapus untuk diselaraskan dengan rancangan halaman murni tabel (Gambar IV.45/46) -->
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

    <!-- Filter Section -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-6">
        <form method="GET" action="{{ route('pembangunan.bayar.index', ['church_slug' => request()->route('church_slug')]) }}" class="flex flex-col md:flex-row md:items-end gap-4">
            
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Jemaat</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama jemaat..." class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs text-slate-600">
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Spesifik</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs text-slate-600">
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bulan</label>
                <select name="bulan" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs text-slate-600">
                    <option value="">Semua Bulan</option>
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ request('bulan') == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tahun (Opsional)</label>
                <input type="number" name="tahun" value="{{ request('tahun') }}" placeholder="Contoh: {{ date('Y') }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs text-slate-600">
            </div>

            <div class="flex items-center space-x-2 w-full md:w-1/4">
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-sm">
                    Terapkan Filter
                </button>
                <a href="{{ route('pembangunan.bayar.index', ['church_slug' => request()->route('church_slug')]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors text-center border border-slate-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Jemaat</th>
                        <th class="px-6 py-4">Tanggal Bayar</th>
                        <th class="px-6 py-4">Nominal Setoran</th>
                        <th class="px-6 py-4">Petugas Pencatat</th>
                        <th class="px-6 py-4">Bukti Pembayaran</th>
                        <th class="px-6 py-4">Status Kas</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/20 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $item->janjiIman && $item->janjiIman->jemaat ? $item->janjiIman->jemaat->nama_jemaat : 'Jemaat N/A' }}
                        </td>
                        <td class="px-6 py-4">{{ date('d - M - Y', strtotime($item->tanggal_bayar)) }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600">Rp. {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $item->user ? $item->user->nama : '-' }}</td>
                        <td class="px-6 py-4">
                            @if($item->bukti_bayar)
                            <a href="{{ asset($item->bukti_bayar) }}" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-750 font-semibold space-x-1">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>Lihat Bukti</span>
                            </a>
                            @else
                            <span class="text-slate-400">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item->transaksi)
                                @if($item->transaksi->status === 'disetujui')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    Disetujui
                                </span>
                                @elseif($item->transaksi->status === 'ditolak')
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-100 w-fit">
                                        Ditolak
                                    </span>
                                    @if($item->transaksi->alasan_penolakan)
                                    <span class="text-[10px] text-rose-500 font-semibold max-w-[120px] truncate" title="{{ $item->transaksi->alasan_penolakan }}">
                                        Alasan: {{ $item->transaksi->alasan_penolakan }}
                                    </span>
                                    @endif
                                </div>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                    Pending Approval
                                </span>
                                @endif
                            @else
                            <span class="text-slate-450">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button @click="editItem = {{ json_encode($item) }}" class="p-1 hover:bg-slate-100 rounded-lg text-slate-500 hover:text-primary-600 transition-all inline-block mr-1">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form method="POST" action="{{ route('pembangunan.bayar.destroy', ['church_slug' => request()->route('church_slug'), 'id' => $item->id_bayar]) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data setoran cicilan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 hover:bg-slate-100 rounded-lg text-slate-500 hover:text-rose-600 transition-all">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center text-slate-400">Belum ada catatan cicilan pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div x-show="editItem" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div @click.away="editItem = null" class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-800">Edit Cicilan Pembayaran</h3>
                <button @click="editItem = null" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" :action="`{{ url(request()->route('church_slug') . '/pembangunan/bayar') }}/${editItem ? editItem.id_bayar : ''}`" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Setoran</label>
                    <input type="date" name="tanggal_bayar" required :value="editItem ? editItem.tanggal_bayar : ''"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jumlah Setoran Bayar (Rp)</label>
                    <input type="number" name="jumlah_bayar" min="0" required :value="editItem ? editItem.jumlah_bayar : ''"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bukti Fisik Pembayaran (Kosongkan jika tidak diubah)</label>
                    <input type="file" name="bukti_bayar" accept="image/*"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="editItem = null" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-md shadow-primary-500/25">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
