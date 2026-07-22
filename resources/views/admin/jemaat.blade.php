@extends('layouts.app')

@section('title', 'Data Jemaat - GPdI Mahanaim')

@section('content')
<div class="space-y-8" x-data="{ openAddModal: false, openEditModal: false, editData: { id_jemaat: '', nama_jemaat: '', alamat: '', no_hp: '', id_rayon: '', status: 'aktif' } }">
    
    <!-- Header & Navigation -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white/60 backdrop-blur-md p-6 rounded-2xl border border-white/60">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Data Jemaat</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar seluruh jemaat GPdI Mahanaim yang terintegrasi dalam sistem.</p>
        </div>
        <div>
            <button @click="openAddModal = true" class="flex items-center space-x-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Jemaat</span>
            </button>
        </div>
    </div>

    @include('admin.nav')

    <!-- Summary Cards (Line 989 / Gambar IV.19) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Jemaat</p>
                <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $totalJemaat }} Orang</h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                <i data-lucide="heart" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Janji Iman</p>
                <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $totalJanjiIman }} Komitmen</h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <i data-lucide="wallet" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Persembahan</p>
                <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $totalPersembahan }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <i data-lucide="map-pin" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Jumlah Rayon</p>
                <h3 class="text-xl font-bold text-slate-800 mt-0.5">{{ $jumlahRayon }} Wilayah</h3>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl flex items-center space-x-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
        <span class="text-sm font-medium text-emerald-800">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
        <ul class="list-disc list-inside text-sm text-red-800">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Jemaat</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4">No HP</th>
                        <th class="px-6 py-4">Rayon</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($jemaats as $idx => $jemaat)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-400">{{ $idx + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $jemaat->nama_jemaat }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $jemaat->alamat }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $jemaat->no_hp }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-purple-50 text-purple-700 text-xs font-semibold rounded-lg border border-purple-100">
                                {{ $jemaat->rayon->nama_rayon }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($jemaat->status === 'aktif')
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg border border-emerald-100">Aktif</span>
                            @else
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-lg border border-slate-200">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <form method="POST" action="{{ route('admin.jemaat.buat-akun', ['church_slug' => request()->route('church_slug'), 'id_jemaat' => $jemaat->id_jemaat]) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Buat akun otomatis untuk Jemaat ini? Username akan dibuat dari nama jemaat, dan password default adalah jemaat123.')"
                                        class="inline-flex items-center space-x-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 text-xs font-semibold rounded-lg transition-colors">
                                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                                    <span>Buat Akun</span>
                                </button>
                            </form>
                            <button @click="editData = { id_jemaat: '{{ $jemaat->id_jemaat }}', nama_jemaat: '{{ $jemaat->nama_jemaat }}', alamat: '{{ $jemaat->alamat }}', no_hp: '{{ $jemaat->no_hp }}', id_rayon: '{{ $jemaat->id_rayon }}', status: '{{ $jemaat->status }}' }; openEditModal = true"
                                    class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-primary-50 text-slate-600 hover:text-primary-600 text-xs font-semibold rounded-lg transition-colors">
                                <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                <span>Edit</span>
                            </button>
                            <form method="POST" action="{{ route('admin.jemaat.destroy', ['church_slug' => request()->route('church_slug'), 'id_jemaat' => $jemaat->id_jemaat]) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus jemaat ini?')"
                                        class="inline-flex items-center space-x-1 px-3 py-1.5 bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 text-xs font-semibold rounded-lg transition-colors">
                                    <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-slate-300"></i>
                            <span class="text-sm">Belum ada data jemaat.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Tambah Jemaat -->
    <div x-show="openAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-md overflow-hidden" @click.away="openAddModal = false">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/55">
                <h3 class="text-base font-bold text-slate-900">Tambah Jemaat Baru</h3>
                <button @click="openAddModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.jemaat.store', ['church_slug' => request()->route('church_slug')]) }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap Jemaat</label>
                    <input type="text" name="nama_jemaat" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm" placeholder="Masukkan nama lengkap jemaat">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Tempat Tinggal</label>
                    <input type="text" name="alamat" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm" placeholder="Contoh: Jl. Sadaperarih No.12">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Handphone (WhatsApp)</label>
                    <input type="text" name="no_hp" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm" placeholder="Contoh: 081234567890">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Rayon Pelayanan</label>
                    <select name="id_rayon" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-white">
                        <option value="">-- Pilih Rayon --</option>
                        @foreach($rayons as $rayon)
                            <option value="{{ $rayon->id_rayon }}">{{ $rayon->nama_rayon }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Keanggotaan</label>
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

    <!-- Modal: Edit Jemaat -->
    <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-full max-w-md overflow-hidden" @click.away="openEditModal = false">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/55">
                <h3 class="text-base font-bold text-slate-900">Ubah Data Jemaat</h3>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form method="POST" :action="`{{ url('/') }}/${'{{ request()->route('church_slug') }}'}/admin/jemaat/${editData.id_jemaat}`" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap Jemaat</label>
                    <input type="text" name="nama_jemaat" x-model="editData.nama_jemaat" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Tempat Tinggal</label>
                    <input type="text" name="alamat" x-model="editData.alamat" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Handphone (WhatsApp)</label>
                    <input type="text" name="no_hp" x-model="editData.no_hp" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Rayon Pelayanan</label>
                    <select name="id_rayon" x-model="editData.id_rayon" required class="block w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm bg-white">
                        @foreach($rayons as $rayon)
                            <option value="{{ $rayon->id_rayon }}">{{ $rayon->nama_rayon }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Keanggotaan</label>
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
