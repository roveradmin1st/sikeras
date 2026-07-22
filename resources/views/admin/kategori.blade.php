@extends('layouts.app')

@section('title', 'Kategori Kas - GPdI Mahanaim')

@section('content')
<div class="space-y-8" x-data="{ openAddModal: false, openEditModal: false, editData: { id_kategori: '', nama_kategori: '', jenis: 'pemasukan', keterangan: '', status: 'aktif' } }">
    
    <!-- Header & Navigation -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white/60 backdrop-blur-md p-6 rounded-2xl border border-white/60">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kategori Buku Kas</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar kategori untuk klasifikasi pencatatan pemasukan dan pengeluaran kas gereja.</p>
        </div>
        <div>
            <button @click="openAddModal = true" class="flex items-center space-x-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Kategori</span>
            </button>
        </div>
    </div>

    @include('admin.nav')

    <!-- Notifications -->
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl flex items-center space-x-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
        <span class="text-sm font-medium text-emerald-800">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Jenis Aliran</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($categories as $idx => $cat)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $cat->nama_kategori }}</td>
                        <td class="px-6 py-4">
                            @if($cat->jenis === 'pemasukan')
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-100 flex items-center w-fit">
                                <i data-lucide="arrow-down-left" class="w-3.5 h-3.5 mr-1"></i>
                                Pemasukan
                            </span>
                            @else
                            <span class="px-2.5 py-1 bg-rose-50 text-rose-700 text-xs font-semibold rounded-lg border border-rose-100 flex items-center w-fit">
                                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 mr-1"></i>
                                Pengeluaran
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $cat->keterangan ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($cat->status === 'aktif')
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-100">Aktif</span>
                            @else
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-lg border border-slate-200">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="editData = { id_kategori: '{{ $cat->id_kategori }}', nama_kategori: '{{ $cat->nama_kategori }}', jenis: '{{ $cat->jenis }}', keterangan: '{{ $cat->keterangan }}', status: '{{ $cat->status }}' }; openEditModal = true"
                                    class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-primary-50 text-slate-600 hover:text-primary-600 text-xs font-semibold rounded-lg transition-colors">
                                <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                <span>Edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.kategori.destroy', ['church_slug' => request()->route('church_slug'), 'id_kategori' => $cat->id_kategori]) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                        class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 text-xs font-semibold rounded-lg transition-colors">
                                    <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-slate-300"></i>
                            <span class="text-sm">Belum ada data kategori transaksi.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Tambah Kategori -->
    <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-md overflow-hidden" @click.away="openAddModal = false">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/55">
                <h3 class="text-base font-bold text-slate-900">Tambah Kategori Baru</h3>
                <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.kategori.store', ['church_slug' => request()->route('church_slug')]) }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kategori</label>
                    <input type="text" name="nama_kategori" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm" placeholder="Contoh: Persepuluhan, Pembelian Semen">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Aliran Kas</label>
                    <select name="jenis" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-white">
                        <option value="pemasukan">Pemasukan (Uang Masuk)</option>
                        <option value="pengeluaran">Pengeluaran (Uang Keluar)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm" placeholder="Deskripsi peruntukan kategori ini"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Aktifasi</label>
                    <select name="status" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-white">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-primary-500/20">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Kategori -->
    <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-md overflow-hidden" @click.away="openEditModal = false">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/55">
                <h3 class="text-base font-bold text-slate-900">Ubah Data Kategori</h3>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form method="POST" :action="`{{ url('/') }}/${'{{ request()->route('church_slug') }}'}/admin/kategori/${editData.id_kategori}`" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Kategori</label>
                    <input type="text" name="nama_kategori" x-model="editData.nama_kategori" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Aliran Kas</label>
                    <select name="jenis" x-model="editData.jenis" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-white">
                        <option value="pemasukan">Pemasukan (Uang Masuk)</option>
                        <option value="pengeluaran">Pengeluaran (Uang Keluar)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="3" x-model="editData.keterangan" class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Aktifasi</label>
                    <select name="status" x-model="editData.status" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-white">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" @click="openEditModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-primary-500/20">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
