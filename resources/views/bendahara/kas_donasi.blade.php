@extends('layouts.app')

@section('title', 'Donasi Umum Jemaat - SIKER')

@section('content')
<div class="space-y-6" x-data="{ openAddModal: false, editItem: null }">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Donasi Umum Jemaat</h1>
            <p class="text-xs text-slate-500 mt-1">Pencatatan donasi atau sumbangan sukarela dari jemaat gereja.</p>
        </div>
        <div>
            <button @click="openAddModal = true" class="flex items-center space-x-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-xl transition-all shadow-md shadow-primary-500/25">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Donasi</span>
            </button>
        </div>
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

    <!-- Data Table (Matches Gambar IV.34) -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Donatur (Jemaat)</th>
                        <th class="px-6 py-4">Tanggal Donasi</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Bukti Transaksi</th>
                        <th class="px-6 py-4">Status Approval</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/20 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $item->jemaat ? $item->jemaat->nama_jemaat : 'Hamba Allah (Anonim)' }}
                            @if($item->jemaat && $item->jemaat->rayon)
                            <div class="text-[10px] text-slate-400 font-normal mt-0.5"><i data-lucide="map-pin" class="w-3 h-3 inline-block mr-0.5"></i>{{ $item->jemaat->rayon->nama_rayon }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ date('d - M - Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $item->keterangan ?? '-' }}
                            @if($item->jemaat && $item->jemaat->rayon)
                            <br><span class="text-[10px] text-slate-400">Rayon: {{ $item->jemaat->rayon->nama_rayon }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">Rp. {{ number_format($item->debit, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($item->bukti_transaksi)
                            <a href="{{ asset($item->bukti_transaksi) }}" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-750 font-semibold space-x-1">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>Lihat Bukti</span>
                            </a>
                            @else
                            <span class="text-slate-400">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status === 'disetujui')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Disetujui
                            </span>
                            @elseif($item->status === 'ditolak')
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-100 w-fit">
                                    Ditolak
                                </span>
                                @if($item->alasan_penolakan)
                                <span class="text-[10px] text-rose-500 font-semibold max-w-[120px] truncate" title="{{ $item->alasan_penolakan }}">
                                    Alasan: {{ $item->alasan_penolakan }}
                                </span>
                                @endif
                            </div>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                Pending
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button @click="editItem = {{ json_encode($item) }}" class="p-1 hover:bg-slate-100 rounded-lg text-slate-500 hover:text-primary-600 transition-all inline-block mr-1">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form method="POST" action="{{ route('kas.donasi.destroy', ['church_slug' => request()->route('church_slug'), 'id' => $item->id_transaksi]) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data donasi ini?')">
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
                        <td colspan="8" class="px-6 py-10 text-center text-slate-400">Belum ada catatan donasi umum.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ADD MODAL (Matches Gambar IV.35) -->
    <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div @click.away="openAddModal = false" class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-800">Tambah Donasi Jemaat</h3>
                <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('kas.donasi.store', ['church_slug' => request()->route('church_slug')]) }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id_kategori" value="{{ $id_kategori }}">

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Jemaat Donatur</label>
                    <select name="id_jemaat" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                        <option value="">-- Hamba Allah (Anonim) --</option>
                        @foreach($jemaatList as $jemaat)
                            <option value="{{ $jemaat->id_jemaat }}">{{ $jemaat->nama_jemaat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Donasi</label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nominal Sumbangan (Rp)</label>
                    <input type="number" name="debit" min="0" required placeholder="Contoh: 1000000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan Tambahan</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Donasi sukarela untuk perbaikan pagar"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bukti Fisik Transaksi (Foto/Lampiran)</label>
                    <input type="file" name="bukti_transaksi" accept="image/*"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-md shadow-primary-500/25">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div x-show="editItem" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div @click.away="editItem = null" class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-800">Edit Donasi Jemaat</h3>
                <button @click="editItem = null" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" :action="`{{ url(request()->route('church_slug') . '/kas/donasi') }}/${editItem ? editItem.id_transaksi : ''}`" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Jemaat Donatur</label>
                    <select name="id_jemaat" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                        <option value="" :selected="editItem && !editItem.id_jemaat">-- Hamba Allah (Anonim) --</option>
                        @foreach($jemaatList as $jemaat)
                            <option value="{{ $jemaat->id_jemaat }}" :selected="editItem && editItem.id_jemaat == {{ $jemaat->id_jemaat }}">{{ $jemaat->nama_jemaat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Donasi</label>
                    <input type="date" name="tanggal" required :value="editItem ? editItem.tanggal : ''"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nominal Sumbangan (Rp)</label>
                    <input type="number" name="debit" min="0" required :value="editItem ? editItem.debit : ''"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan Tambahan</label>
                    <input type="text" name="keterangan" :value="editItem ? editItem.keterangan : ''"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bukti Fisik Transaksi (Kosongkan jika tidak diubah)</label>
                    <input type="file" name="bukti_transaksi" accept="image/*"
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
