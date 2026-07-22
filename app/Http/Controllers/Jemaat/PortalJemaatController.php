<?php

namespace App\Http\Controllers\Jemaat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiKas;
use App\Models\JanjiIman;
use App\Models\PembayaranJanji;
use Illuminate\Support\Facades\Auth;

class PortalJemaatController extends Controller
{
    public function dashboard($church_slug)
    {
        // Fitur Transparansi Keuangan (Umum)
        // Jemaat bisa melihat total pemasukan, pengeluaran, saldo, dan riwayat mutasi

        // Hitung Saldo Kas Umum (Kas + Rayon) yang sudah disetujui
        $transaksiUmum = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('status', 'disetujui')
            ->get();
            
        $totalPemasukan = $transaksiUmum->sum('debit');
        $totalPengeluaran = $transaksiUmum->sum('kredit');
        $saldoKas = $totalPemasukan - $totalPengeluaran;

        // Ambil riwayat mutasi terakhir (misal 50 transaksi terbaru)
        $riwayatTransaksi = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('status', 'disetujui')
            ->with(['kategori'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_transaksi', 'desc')
            ->take(50)
            ->get();

        return view('jemaat.dashboard', compact('saldoKas', 'totalPemasukan', 'totalPengeluaran', 'riwayatTransaksi'));
    }

    public function janjiImanKu($church_slug)
    {
        // Fitur Pantau Janji Iman Pribadi
        $user = Auth::user();
        
        // Pastikan user memiliki id_jemaat yang terhubung
        if (!$user->id_jemaat) {
            return redirect()->back()->withErrors(['error' => 'Akun Anda belum ditautkan dengan data Jemaat. Silakan hubungi Admin.']);
        }

        // Ambil data janji iman (komitmen) milik jemaat ini
        $janjiImanList = JanjiIman::where('id_jemaat', $user->id_jemaat)
            ->with(['pembayaran' => function($q) {
                $q->join('transaksi_kas', 'pembayaran_janji.id_transaksi', '=', 'transaksi_kas.id_transaksi')
                  ->where('transaksi_kas.status', 'disetujui')
                  ->orderBy('tanggal_bayar', 'desc');
            }])
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Hitung persentase untuk masing-masing janji iman
        foreach ($janjiImanList as $janji) {
            $totalDibayar = $janji->pembayaran->sum('jumlah_bayar');
            $janji->total_dibayar = $totalDibayar;
            $janji->sisa = $janji->total_janji - $totalDibayar;
            $janji->persentase = $janji->total_janji > 0 ? min(100, round(($totalDibayar / $janji->total_janji) * 100)) : 0;
        }

        // Riwayat Transaksi Pembangunan (Donasi Pembangunan dari jemaat ini)
        $donasiPembangunan = TransaksiKas::where('jenis_kas', 'pembangunan')
            ->where('id_jemaat', $user->id_jemaat)
            ->where('status', 'disetujui')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('jemaat.janji_imanku', compact('janjiImanList', 'donasiPembangunan'));
    }
}
