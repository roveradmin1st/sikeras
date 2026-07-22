@extends('layouts.app')

@section('title', 'Komitmen Janji Iman - SIKER')

@section('content')
<div class="space-y-6" x-data="{ editItem: null }">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Data Janji Iman Jemaat</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola data komitmen awal janji iman jemaat untuk dana pembangunan gereja.</p>
        </div>
        <!-- Tombol Tambah dihapus sesuai rancangan halaman murni tabel di Gambar IV.45 -->
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

    <!-- Data Table (Matches Gambar IV.45) -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Jemaat</th>
                        <th class="px-6 py-4">Tanggal Mulai</th>
                        <th class="px-6 py-4">Komitmen Awal</th>
                        <th class="px-6 py-4">Telah Terbayar</th>
                        <th class="px-6 py-4">Sisa Piutang</th>
                        <th class="px-6 py-4">Status Kelunasan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/20 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $item->jemaat ? $item->jemaat->nama_jemaat : 'Jemaat N/A' }}
                        </td>
                        <td class="px-6 py-4">{{ date('d - M - Y', strtotime($item->tanggal_mulai)) }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">Rp. {{ number_format($item->total_janji, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600">Rp. {{ number_format($item->terbayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-rose-600">Rp. {{ number_format($item->sisa, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($item->status === 'lunas')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Lunas
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                Belum Lunas
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button @click="editItem = {{ json_encode($item) }}" class="p-1 hover:bg-slate-100 rounded-lg text-slate-500 hover:text-primary-600 transition-all inline-block mr-1">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <!-- Delete only allowed if no payments have been registered (controlled in Controller) -->
                            <form method="POST" action="{{ route('pembangunan.janji.destroy', ['church_slug' => request()->route('church_slug'), 'id' => $item->id_janji]) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data janji iman ini?')">
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
                        <td colspan="8" class="px-6 py-10 text-center text-slate-400">Belum ada data komitmen janji iman jemaat.</td>
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
                <h3 class="text-sm font-bold text-slate-800">Edit Komitmen Janji Iman</h3>
                <button @click="editItem = null" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" :action="`{{ url(request()->route('church_slug') . '/pembangunan/janji') }}/${editItem ? editItem.id_janji : ''}`" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Anggota Jemaat</label>
                    <select name="id_jemaat" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                        @foreach($jemaatList as $jemaat)
                            <option value="{{ $jemaat->id_jemaat }}" :selected="editItem && editItem.id_jemaat == {{ $jemaat->id_jemaat }}">{{ $jemaat->nama_jemaat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Komitmen Mulai</label>
                    <input type="date" name="tanggal_mulai" required :value="editItem ? editItem.tanggal_mulai : ''"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Total Nominal Komitmen Janji Iman (Rp)</label>
                    <input type="number" name="total_janji" min="0" required :value="editItem ? editItem.total_janji : ''"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status Kelunasan</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                        <option value="belum_lunas" :selected="editItem && editItem.status === 'belum_lunas'">Belum Lunas</option>
                        <option value="lunas" :selected="editItem && editItem.status === 'lunas'">Lunas</option>
                    </select>
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
