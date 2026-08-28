@extends('layouts.app')

@section('title', 'Persembahan Mingguan - SIKER')

@section('content')
<div class="space-y-6" x-data="{
            openAddModal: false,
            editItem: null,
            receiptItem: null,
            receiptItem: null,
            entries: [{ id_jemaat: '', nominal: '', keterangan: '' }],
            get total() {
                return this.entries.reduce((a, b) => a + (Number(b.nominal) || 0), 0)
            },
            addRow() {
                this.entries.push({id_jemaat: '', nominal: '', keterangan: ''})
            },
            removeRow(index) {
                if(this.entries.length > 1) {
                    this.entries.splice(index, 1)
                }
            }
        }">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Persembahan Mingguan</h1>
            <p class="text-xs text-slate-500 mt-1">Pencatatan pemasukan dana persembahan ibadah raya minggu gereja.</p>
        </div>
        <div>
            <button @click="openAddModal = true" class="flex items-center space-x-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-xl transition-all shadow-md shadow-primary-500/25">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Persembahan</span>
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

    <!-- Data Table (Matches Gambar IV.32) -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal Ibadah</th>
                        <th class="px-6 py-4">Nama Jemaat / Sumber</th>
                        <th class="px-6 py-4">Keterangan / Minggu Ke-</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Bukti Transaksi</th>
                        <th class="px-6 py-4">Status Validasi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/20 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ date('d - M - Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $item->jemaat ? $item->jemaat->nama_jemaat : 'Kolekte Umum (Anonim)' }}
                            @if($item->jemaat && $item->jemaat->rayon)
                            <div class="text-[10px] text-slate-400 font-normal mt-0.5"><i data-lucide="map-pin" class="w-3 h-3 inline-block mr-0.5"></i>{{ $item->jemaat->rayon->nama_rayon }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $item->keterangan ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">Rp. {{ number_format($item->debit, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <button @click="receiptItem = {{ json_encode($item) }}; receiptItem.user = {{ json_encode($item->user) }}; receiptItem.jemaat = {{ json_encode($item->jemaat) }}" class="inline-flex items-center text-primary-600 hover:text-primary-750 font-semibold space-x-1 bg-primary-50 hover:bg-primary-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                                <span>Lihat Kuitansi</span>
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status === 'disetujui')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Sah (Valid)
                            </span>
                            @elseif($item->status === 'ditolak')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                Ditolak
                            </span>
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
                            <form method="POST" action="{{ route('kas.persembahan.destroy', ['church_slug' => request()->route('church_slug'), 'id' => $item->id_transaksi]) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data persembahan ini?')">
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
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">Belum ada catatan persembahan mingguan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ADD MODAL (Smart Multi-entry Auto Calc) -->
    <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div @click.away="openAddModal = false" class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 sticky top-0 z-10">
                <h3 class="text-sm font-bold text-slate-800">Pencatatan Persembahan Mingguan (Auto-Calc)</h3>
                <button type="button" @click="openAddModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('kas.persembahan.store', ['church_slug' => request()->route('church_slug')]) }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="id_kategori" value="{{ $id_kategori }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Ibadah</label>
                        <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                    </div>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-700">Rincian Persembahan / Jemaat</span>
                        <button type="button" @click="addRow()" class="text-[10px] bg-white border border-slate-200 px-2 py-1 rounded-md hover:bg-slate-100 font-semibold text-slate-600 shadow-sm flex items-center">
                            <i data-lucide="plus" class="w-3 h-3 mr-1"></i> Tambah Baris
                        </button>
                    </div>
                    <div class="p-4 bg-slate-50/30 space-y-3">
                        <template x-for="(entry, index) in entries" :key="index">
                            <div class="flex flex-col md:flex-row gap-3 items-end bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
                                <div class="w-full md:w-1/3">
                                    <label class="block text-[10px] font-semibold text-slate-500 mb-1">Nama Jemaat</label>
                                    <select :name="'persembahan['+index+'][id_jemaat]'" x-model="entry.id_jemaat" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:border-primary-500 transition-all text-xs">
                                        <option value="">-- Kolekte Umum / Anonim --</option>
                                        @foreach($jemaatList as $j)
                                            <option value="{{ $j->id_jemaat }}">{{ $j->nama_jemaat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-full md:w-1/3">
                                    <label class="block text-[10px] font-semibold text-slate-500 mb-1">Keterangan / Minggu Ke-</label>
                                    <input type="text" :name="'persembahan['+index+'][keterangan]'" x-model="entry.keterangan" placeholder="Contoh: Perpuluhan / Ibadah Raya"
                                           class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:border-primary-500 transition-all text-xs">
                                </div>
                                <div class="w-full md:w-1/3">
                                    <label class="block text-[10px] font-semibold text-slate-500 mb-1">Nominal (Rp)</label>
                                    <div class="flex items-center gap-2">
                                        <input type="number" :name="'persembahan['+index+'][nominal]'" x-model="entry.nominal" min="1" required placeholder="0"
                                            class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:border-primary-500 transition-all text-xs">
                                        <button type="button" @click="removeRow(index)" x-show="entries.length > 1" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="bg-emerald-50 px-4 py-3 border-t border-emerald-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-emerald-800">Total Persembahan:</span>
                        <span class="text-lg font-black text-emerald-700">Rp. <span x-text="new Intl.NumberFormat('id-ID').format(total)"></span></span>
                    </div>
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-md shadow-primary-500/25">
                        Simpan Data Persembahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div x-show="editItem" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div @click.away="editItem = null" class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-800">Edit Persembahan Mingguan</h3>
                <button @click="editItem = null" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" :action="`{{ url(request()->route('church_slug') . '/kas/persembahan') }}/${editItem ? editItem.id_transaksi : ''}`" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Jemaat / Sumber</label>
                    <select name="id_jemaat" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                        <option value="" :selected="editItem && !editItem.id_jemaat">-- Kolekte Umum / Anonim --</option>
                        @foreach($jemaatList as $j)
                            <option value="{{ $j->id_jemaat }}" :selected="editItem && editItem.id_jemaat == {{ $j->id_jemaat }}">{{ $j->nama_jemaat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Ibadah</label>
                    <input type="date" name="tanggal" required :value="editItem ? editItem.tanggal : ''"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nominal Persembahan (Rp)</label>
                    <input type="number" name="debit" min="0" required :value="editItem ? editItem.debit : ''"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan / Keterangan Minggu Ke-</label>
                    <input type="text" name="keterangan" :value="editItem ? editItem.keterangan : ''"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs">
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

    <!-- RECEIPT MODAL -->
    <div x-show="receiptItem" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div @click.away="receiptItem = null" class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-sm w-full overflow-hidden">
            <!-- Header Kuitansi -->
            <div class="px-6 py-5 border-b border-slate-100 flex flex-col items-center bg-slate-50/80">
                <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-3">
                    <i data-lucide="receipt" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Bukti Persembahan</h3>
                <p class="text-xs font-semibold text-slate-500 mt-0.5" x-text="receiptItem ? new Date(receiptItem.tanggal).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : ''"></p>
            </div>
            <!-- Body Kuitansi -->
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="text-xs font-semibold text-slate-500">Rincian / Keterangan</span>
                    <span class="text-xs font-bold text-slate-800 text-right" x-text="receiptItem ? receiptItem.keterangan : ''"></span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="text-xs font-semibold text-slate-500">Dikumpulkan Oleh</span>
                    <span class="text-xs font-bold text-slate-800 text-right" x-text="receiptItem && receiptItem.user ? receiptItem.user.nama : 'Bendahara Kas'"></span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="text-xs font-semibold text-slate-500">Sumber / Jemaat</span>
                    <span class="text-xs font-bold text-slate-800 text-right" x-text="receiptItem && receiptItem.jemaat ? receiptItem.jemaat.nama_jemaat : 'Kolekte Umum / Anonim'"></span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm font-bold text-slate-800">Jumlah Terkumpul</span>
                    <span class="text-lg font-black text-emerald-600" x-text="receiptItem ? 'Rp. ' + new Intl.NumberFormat('id-ID').format(receiptItem.debit) : ''"></span>
                </div>
            </div>
            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                <button type="button" @click="receiptItem = null" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>


    <!-- RECEIPT MODAL -->
    <div x-show="receiptItem" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div @click.away="receiptItem = null" class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full overflow-hidden">
            <!-- Header Kuitansi -->
            <div class="px-6 py-5 border-b border-slate-100 flex flex-col items-center bg-slate-50/80">
                <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-3">
                    <i data-lucide="receipt" class="w-6 h-6"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Bukti Persembahan</h3>
                <p class="text-xs font-semibold text-slate-500 mt-0.5" x-text="receiptItem ? new Date(receiptItem.tanggal).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : ''"></p>
            </div>
            <!-- Body Kuitansi -->
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="text-xs font-semibold text-slate-500">Rincian</span>
                    <span class="text-xs font-bold text-slate-800 text-right w-2/3" style="line-height: 1.5;" x-text="receiptItem ? receiptItem.keterangan : ''"></span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                    <span class="text-xs font-semibold text-slate-500">Diinput Oleh</span>
                    <span class="text-xs font-bold text-slate-800 text-right" x-text="receiptItem && receiptItem.user ? receiptItem.user.nama : 'Bendahara'"></span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <span class="text-sm font-bold text-slate-800">Jumlah Terkumpul</span>
                    <span class="text-lg font-black text-emerald-600" x-text="receiptItem ? 'Rp. ' + new Intl.NumberFormat('id-ID').format(receiptItem.debit) : ''"></span>
                </div>
            </div>
            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                <button type="button" @click="receiptItem = null" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
