<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiKas;
use Illuminate\Support\Facades\DB;

class PendetaController extends Controller
{
    // ==========================================
    // 1. APPROVAL KAS (Bendahara Kas)
    // ==========================================
    public function approvalKasIndex()
    {
        // Fetch pending transactions specifically for general cash and rayon
        $pendingItems = TransaksiKas::with(['user', 'jemaat', 'kategori'])
            ->whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('kredit', '>', 0)
            ->where('status', 'pending')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Fetch rejected transactions
        $rejectedItems = TransaksiKas::with(['user', 'jemaat', 'kategori'])
            ->whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('kredit', '>', 0)
            ->where('status', 'ditolak')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('pendeta.approval_kas', compact('pendingItems', 'rejectedItems'));
    }

    public function approveKas(Request $request, $church_slug, $id)
    {
        $transaksi = TransaksiKas::findOrFail($id);
        $transaksi->update(['status' => 'disetujui']);

        return redirect()->back()->with('success', 'Transaksi Kas berhasil disetujui.');
    }

    public function rejectKas(Request $request, $church_slug, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255'
        ]);

        $transaksi = TransaksiKas::findOrFail($id);
        
        $transaksi->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return redirect()->back()->with('success', 'Transaksi Kas telah ditolak.');
    }

    // ==========================================
    // 2. APPROVAL JANJI IMAN (Bendahara Pembangunan)
    // ==========================================
    public function approvalJanjiIndex()
    {
        // Fetch pending building cash transactions
        $pendingItems = TransaksiKas::with(['user', 'jemaat', 'kategori'])
            ->where('jenis_kas', 'pembangunan')
            ->where('kredit', '>', 0)
            ->where('status', 'pending')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Fetch rejected building cash transactions
        $rejectedItems = TransaksiKas::with(['user', 'jemaat', 'kategori'])
            ->where('jenis_kas', 'pembangunan')
            ->where('kredit', '>', 0)
            ->where('status', 'ditolak')
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('pendeta.approval_janji', compact('pendingItems', 'rejectedItems'));
    }

    public function approveJanji(Request $request, $church_slug, $id)
    {
        $transaksi = TransaksiKas::findOrFail($id);
        $transaksi->update(['status' => 'disetujui']);

        return redirect()->back()->with('success', 'Pengeluaran Kas Pembangunan berhasil disetujui.');
    }

    public function rejectJanji(Request $request, $church_slug, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255'
        ]);

        $transaksi = TransaksiKas::findOrFail($id);
        $transaksi->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return redirect()->back()->with('success', 'Setoran Janji Iman telah ditolak.');
    }

    // ==========================================
    // 3. LAPORAN-LAPORAN
    // ==========================================
    public function laporanKasIndex(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        // Ambil transaksi kas_umum dan rayon yang disetujui dalam rentang waktu
        $items = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['kategori', 'jemaat'])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPemasukan = $items->sum('debit');
        $totalPengeluaran = $items->sum('kredit');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return view('pendeta.laporan_kas', compact('items', 'startDate', 'endDate', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
    }

    public function laporanJanjiIndex(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        // Laporan Janji Iman biasanya rekap per jemaat
        // Kita ambil janji iman yang dibuat dalam rentang, atau pembayarannya dalam rentang.
        // Untuk sederhana, kita ambil semua transaksi pembangunan yang disetujui di rentang ini.
        $items = TransaksiKas::where('jenis_kas', 'pembangunan')
            ->has('pembayaranJanji')
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['jemaat'])
            ->orderBy('tanggal', 'asc')
            ->get();
            
        $totalTerkumpul = $items->sum('debit');

        return view('pendeta.laporan_janji', compact('items', 'startDate', 'endDate', 'totalTerkumpul'));
    }

    public function cetakLaporanKas(Request $request, $church_slug)
    {
        $church = \App\Models\Church::where('slug', $church_slug)->firstOrFail();
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        $items = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['kategori', 'jemaat'])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPemasukan = $items->sum('debit');
        $totalPengeluaran = $items->sum('kredit');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cetak.laporan_kas_pdf', compact('items', 'church', 'startDate', 'endDate', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
        
        return $pdf->download('Laporan_Kas_' . $startDate . '_sd_' . $endDate . '.pdf');
    }

    public function excelLaporanKas(Request $request, $church_slug)
    {
        // For simplicity and since we don't have a dedicated Export class setup yet, 
        // we can use a simpler view-based excel export if Maatwebsite allows it,
        // or just return a CSV. Let's return a basic CSV/Excel HTML download for now,
        // or use the HTML table trick for simple Excel.
        
        $church = \App\Models\Church::where('slug', $church_slug)->firstOrFail();
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        $items = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['kategori', 'jemaat'])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPemasukan = $items->sum('debit');
        $totalPengeluaran = $items->sum('kredit');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return view('cetak.laporan_kas_excel', compact('items', 'church', 'startDate', 'endDate', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
    }

    public function cetakLaporanJanji(Request $request, $church_slug)
    {
        $church = \App\Models\Church::where('slug', $church_slug)->firstOrFail();
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        $items = TransaksiKas::where('jenis_kas', 'pembangunan')
            ->has('pembayaranJanji')
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['jemaat'])
            ->orderBy('tanggal', 'asc')
            ->get();
            
        $totalTerkumpul = $items->sum('debit');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cetak.laporan_janji_pdf', compact('items', 'church', 'startDate', 'endDate', 'totalTerkumpul'));
        
        return $pdf->download('Laporan_Janji_Iman_' . $startDate . '_sd_' . $endDate . '.pdf');
    }

    public function excelLaporanJanji(Request $request, $church_slug)
    {
        $church = \App\Models\Church::where('slug', $church_slug)->firstOrFail();
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        $items = TransaksiKas::where('jenis_kas', 'pembangunan')
            ->has('pembayaranJanji')
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['jemaat'])
            ->orderBy('tanggal', 'asc')
            ->get();
            
        $totalTerkumpul = $items->sum('debit');

        return view('cetak.laporan_janji_excel', compact('items', 'church', 'startDate', 'endDate', 'totalTerkumpul'));
    }
}
