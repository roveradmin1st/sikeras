<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jemaat;
use App\Models\TransaksiKas;
use App\Models\JanjiIman;
use App\Models\PembayaranJanji;
use App\Models\Rayon;
use App\Models\KategoriTransaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index($church_slug)
    {
        $user = Auth::user();

        // Load role-specific dashboards
        switch ($user->role) {
            case 'admin':
                // Fetch dynamic stats for Admin Dashboard (matches Gambar IV.16)
                $jumlahJemaat = Jemaat::count();
                $jumlahTransaksi = TransaksiKas::count();
                
                // Calculate actual balances
                $kasUmumDebit = TransaksiKas::where('jenis_kas', 'kas_umum')->where('status', 'disetujui')->sum('debit');
                $kasUmumKredit = TransaksiKas::where('jenis_kas', 'kas_umum')->where('status', 'disetujui')->sum('kredit');
                $totalKasUmum = $kasUmumDebit - $kasUmumKredit;

                $pembangunanDebit = TransaksiKas::where('jenis_kas', 'pembangunan')->where('status', 'disetujui')->sum('debit');
                $pembangunanKredit = TransaksiKas::where('jenis_kas', 'pembangunan')->where('status', 'disetujui')->sum('kredit');
                $kasPembangunan = $pembangunanDebit - $pembangunanKredit;

                // Recent Transactions (Limit 3)
                $recentTransactions = TransaksiKas::orderBy('tanggal', 'desc')->orderBy('id_transaksi', 'desc')->limit(3)->get();

                // Recent Pledges (Limit 3)
                $recentPledges = JanjiIman::with('jemaat')->orderBy('tanggal_mulai', 'desc')->orderBy('id_janji', 'desc')->limit(3)->get();

                return view('dashboard.admin', compact(
                    'jumlahJemaat', 
                    'jumlahTransaksi', 
                    'totalKasUmum', 
                    'kasPembangunan',
                    'recentTransactions',
                    'recentPledges'
                ));

            case 'bendahara_kas':
                // Fetch stats for Bendahara Kas Dashboard (100% Matches Gambar IV.31 description)
                
                // 1. Saldo Kas Aktif & Total Pemasukan (Approved)
                $totalDebit = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])->where('status', 'disetujui')->sum('debit');
                $totalKredit = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])->where('status', 'disetujui')->sum('kredit');
                $saldoKasAktif = $totalDebit - $totalKredit;
                $totalPemasukan = $totalDebit;

                // 2. Persembahan Mingguan, Donasi Umum & Pengeluaran Mingguan
                // Fetch categories first
                $katPersembahan = KategoriTransaksi::where('nama_kategori', 'like', '%Persembahan%')->first();
                $katDonasi = KategoriTransaksi::where('nama_kategori', 'like', '%Donasi%')->first();

                $idKatPersembahan = $katPersembahan ? $katPersembahan->id_kategori : null;
                $idKatDonasi = $katDonasi ? $katDonasi->id_kategori : null;

                // Monthly sums for Persembahan and Donasi
                $firstDayOfMonth = Carbon::now()->startOfMonth()->toDateString();
                $lastDayOfMonth = Carbon::now()->endOfMonth()->toDateString();

                $persembahanMingguan = TransaksiKas::where('id_kategori', $idKatPersembahan)
                    ->where('status', 'disetujui')
                    ->whereBetween('tanggal', [$firstDayOfMonth, $lastDayOfMonth])
                    ->sum('debit');

                $donasiUmum = TransaksiKas::where('id_kategori', $idKatDonasi)
                    ->where('status', 'disetujui')
                    ->whereBetween('tanggal', [$firstDayOfMonth, $lastDayOfMonth])
                    ->sum('debit');

                // Weekly expenses (kredit) for current week
                $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
                $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
                $pengeluaranMingguan = TransaksiKas::where('status', 'disetujui')
                    ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
                    ->sum('kredit');

                // 3. Laporan Kas Rayon (merangkum pendapatan dan pengeluaran pada tingkat rayon)
                $rayons = Rayon::all();
                $rayonStats = [];
                foreach ($rayons as $r) {
                    $jemaatIds = Jemaat::where('id_rayon', $r->id_rayon)->pluck('id_jemaat');
                    
                    $pemasukanRayon = TransaksiKas::where('jenis_kas', 'rayon')
                        ->where('status', 'disetujui')
                        ->whereIn('id_jemaat', $jemaatIds)
                        ->sum('debit');

                    $pengeluaranRayon = TransaksiKas::where('jenis_kas', 'rayon')
                        ->where('status', 'disetujui')
                        ->whereIn('id_jemaat', $jemaatIds)
                        ->sum('kredit');

                    $rayonStats[] = [
                        'nama_rayon' => $r->nama_rayon,
                        'pemasukan' => $pemasukanRayon,
                        'pengeluaran' => $pengeluaranRayon,
                        'saldo' => $pemasukanRayon - $pengeluaranRayon
                    ];
                }

                // 4. Recent transactions (Limit 5)
                $recentTransactions = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])
                    ->with('kategori')
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('id_transaksi', 'desc')
                    ->limit(5)
                    ->get();

                return view('dashboard.bendahara_kas', compact(
                    'saldoKasAktif',
                    'totalPemasukan',
                    'persembahanMingguan',
                    'donasiUmum',
                    'pengeluaranMingguan',
                    'rayonStats',
                    'recentTransactions'
                ));

            case 'bendahara_pembangunan':
                // Fetch stats for Bendahara Pembangunan Dashboard (matches Gambar IV.42)
                $totalJanjiIman = JanjiIman::sum('total_janji');
                
                // Terbayar Janji Iman (total pembayarans)
                $terbayarJanjiIman = PembayaranJanji::sum('jumlah_bayar');

                // Sisa Janji Iman
                $sisaJanjiIman = $totalJanjiIman - $terbayarJanjiIman;
                if ($sisaJanjiIman < 0) $sisaJanjiIman = 0;

                // Count of active pledges
                $activePledgesCount = JanjiIman::where('status', 'belum_lunas')->count();

                // Recent payments (Limit 5)
                $recentPayments = PembayaranJanji::with(['janjiIman.jemaat'])
                    ->orderBy('tanggal_bayar', 'desc')
                    ->orderBy('id_bayar', 'desc')
                    ->limit(5)
                    ->get();

                return view('dashboard.bendahara_pemb', compact(
                    'totalJanjiIman',
                    'terbayarJanjiIman',
                    'sisaJanjiIman',
                    'activePledgesCount',
                    'recentPayments'
                ));

            case 'pendeta':
                $pendingKas = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])->where('status', 'pending')->count();
                $pendingJanji = TransaksiKas::where('jenis_kas', 'pembangunan')->where('status', 'pending')->count();
                return view('dashboard.pendeta', compact('pendingKas', 'pendingJanji'));
            case 'jemaat':
                return view('dashboard.jemaat');
            default:
                Auth::logout();
                return redirect()->route('login', ['church_slug' => $church_slug])
                    ->withErrors(['username' => 'Role tidak dikenal.']);
        }
    }

    public function profile($church_slug)
    {
        return view('dashboard.profile');
    }

    public function backup($church_slug)
    {
        return view('dashboard.backup');
    }

    public function allReports($church_slug)
    {
        return view('dashboard.reports');
    }

    public function settings($church_slug)
    {
        $church = \App\Models\Church::where('slug', $church_slug)->firstOrFail();
        return view('admin.pengaturan', compact('church'));
    }

    public function updateSettings(Request $request, $church_slug)
    {
        $request->validate([
            'nama_gereja' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $church = \App\Models\Church::where('slug', $church_slug)->firstOrFail();
        $church->nama_gereja = $request->nama_gereja;
        $church->alamat = $request->alamat;
        $church->no_telp = $request->no_telp;

        if ($request->hasFile('logo')) {
            // Delete old logo if exists and not default
            if ($church->path_logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($church->path_logo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($church->path_logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $church->path_logo = $path;
        }

        $church->save();

        return redirect()->back()->with('success', 'Pengaturan instansi dan kop surat berhasil diperbarui!');
    }
}
