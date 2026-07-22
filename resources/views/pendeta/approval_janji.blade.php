@extends('layouts.app')

@section('title', 'Validasi Janji Iman - SIKER')

@section('content')
<div class="space-y-6" x-data="{ rejectItem: null }">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Validasi Pembayaran Janji Iman</h1>
            <p class="text-xs text-slate-500 mt-1">Tinjau dan sahkan setoran cicilan Janji Iman jemaat dari Bendahara Pembangunan.</p>
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

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Tanggal Setor</th>
                        <th class="px-6 py-4">Nama Jemaat</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Nominal Pembayaran</th>
                        <th class="px-6 py-4">Bukti</th>
                        <th class="px-6 py-4 text-center">Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50/20 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ date('d - M - Y', strtotime($item->tanggal)) }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $item->jemaat ? $item->jemaat->nama_jemaat : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] text-slate-500">{{ $item->keterangan }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-emerald-600">
                            {{ $item->debit > 0 ? 'Rp. ' . number_format($item->debit, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($item->bukti_transaksi)
                            <a href="{{ asset($item->bukti_transaksi) }}" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-750 font-semibold space-x-1">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>Lihat</span>
                            </a>
                            @else
                            <span class="text-slate-400">Tidak ada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <form method="POST" action="{{ route('pendeta.approval.janji.approve', ['church_slug' => request()->route('church_slug'), 'id' => $item->id_transaksi]) }}" class="inline-block" onsubmit="return confirm('Sahkan setoran ini? Dana akan langsung masuk ke buku kas pembangunan.')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold rounded-lg border border-emerald-200 transition-colors">
                                        Setujui
                                    </button>
                                </form>
                                <button type="button" @click="rejectItem = {{ json_encode($item) }}" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg border border-rose-200 transition-colors">
                                    Tolak
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                            <i data-lucide="check-circle" class="w-8 h-8 mx-auto text-slate-300 mb-2"></i>
                            Tidak ada setoran Janji Iman yang menunggu validasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- REJECT MODAL -->
    <div x-show="rejectItem" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
        <div @click.away="rejectItem = null" class="bg-white rounded-2xl shadow-xl border border-slate-100 max-w-md w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-800">Tolak Setoran Janji Iman</h3>
                <button @click="rejectItem = null" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" :action="`{{ url(request()->route('church_slug') . '/pendeta/approval-janji') }}/${rejectItem ? rejectItem.id_transaksi : ''}/reject`" class="p-6 space-y-4">
                @csrf
                
                <p class="text-xs text-slate-600">Silakan masukkan alasan penolakan agar Bendahara mengetahui masalahnya.</p>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" rows="3" required placeholder="Contoh: Bukti transfer buram..."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-xs"></textarea>
                </div>

                <div class="pt-2 flex justify-end space-x-2">
                    <button type="button" @click="rejectItem = null" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl transition-colors shadow-md shadow-rose-500/25">
                        Tolak Setoran
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
