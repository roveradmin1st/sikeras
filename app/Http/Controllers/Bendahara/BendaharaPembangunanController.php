<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JanjiIman;
use App\Models\PembayaranJanji;
use App\Models\TransaksiKas;
use App\Models\KategoriTransaksi;
use App\Models\Jemaat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class BendaharaPembangunanController extends Controller
{
    // ==========================================
    // 1. KOMITMEN JANJI IMAN
    // ==========================================
    public function janjiCreate()
    {
        $jemaatList = Jemaat::where('status', 'aktif')->orderBy('nama_jemaat', 'asc')->get();
        return view('bendahara.pemb_janji_input', compact('jemaatList'));
    }

    public function janjiIndex(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $tanggal = $request->input('tanggal');
        $search = $request->input('search');

        $query = JanjiIman::with('jemaat')
            ->orderBy('tanggal_mulai', 'asc');

        if ($tanggal) {
            $query->whereDate('tanggal_mulai', $tanggal);
        } else {
            if ($bulan) {
                $query->whereMonth('tanggal_mulai', $bulan);
            }
            if ($tahun) {
                $query->whereYear('tanggal_mulai', $tahun);
            }
        }

        if ($search) {
            $query->whereHas('jemaat', function($q) use ($search) {
                $q->where('nama_jemaat', 'like', "%{$search}%");
            });
        }

        $items = $query->get();

        $jemaatList = Jemaat::where('status', 'aktif')->orderBy('nama_jemaat', 'asc')->get();

        return view('bendahara.pemb_janji', compact('items', 'jemaatList', 'bulan', 'tahun', 'tanggal'));
    }

    public function janjiStore(Request $request)
    {
        $request->validate([
            'id_jemaat' => 'required|integer',
            'total_janji' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
        ]);

        JanjiIman::create([
            'id_jemaat' => $request->id_jemaat,
            'total_janji' => $request->total_janji,
            'tanggal_mulai' => $request->tanggal_mulai,
            'status' => 'belum_lunas',
        ]);

        return redirect()->back()->with('success', 'Komitmen Janji Iman baru berhasil didaftarkan.');
    }

    public function janjiUpdate(Request $request, $church_slug, $id)
    {
        $request->validate([
            'id_jemaat' => 'required|integer',
            'total_janji' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'status' => 'required|in:belum_lunas,lunas',
        ]);

        $item = JanjiIman::findOrFail($id);
        $item->update($request->only(['id_jemaat', 'total_janji', 'tanggal_mulai', 'status']));

        return redirect()->back()->with('success', 'Data komitmen Janji Iman berhasil diperbarui.');
    }

    public function janjiDestroy($church_slug, $id)
    {
        $item = JanjiIman::findOrFail($id);
        
        // Prevent deletion if there are already payments made
        if ($item->pembayaran()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Tidak bisa menghapus komitmen Janji Iman yang sudah memiliki riwayat pembayaran cicilan.']);
        }

        $item->delete();

        return redirect()->back()->with('success', 'Data komitmen Janji Iman berhasil dihapus.');
    }

    // ==========================================
    // 2. PEMBAYARAN / CICILAN JANJI IMAN
    // ==========================================
    public function bayarCreate()
    {
        $janjiList = JanjiIman::with('jemaat')
            ->where('status', 'belum_lunas')
            ->get();
        return view('bendahara.pemb_bayar_input', compact('janjiList'));
    }

    public function bayarIndex(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $tanggal = $request->input('tanggal');
        $search = $request->input('search');

        $query = PembayaranJanji::with(['janjiIman.jemaat', 'user'])
            ->orderBy('tanggal_bayar', 'asc');

        if ($tanggal) {
            $query->whereDate('tanggal_bayar', $tanggal);
        } else {
            if ($bulan) {
                $query->whereMonth('tanggal_bayar', $bulan);
            }
            if ($tahun) {
                $query->whereYear('tanggal_bayar', $tahun);
            }
        }

        if ($search) {
            $query->whereHas('janjiIman.jemaat', function($q) use ($search) {
                $q->where('nama_jemaat', 'like', "%{$search}%");
            });
        }

        $items = $query->get();

        // Load active pledges that are not yet fully paid
        $janjiList = JanjiIman::with('jemaat')
            ->where('status', 'belum_lunas')
            ->get();

        return view('bendahara.pemb_bayar', compact('items', 'janjiList', 'bulan', 'tahun', 'tanggal'));
    }

    public function bayarStore(Request $request)
    {
        $request->validate([
            'id_janji' => 'required|integer',
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar' => 'required|numeric|min:0',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $janji = JanjiIman::with('jemaat')->findOrFail($request->id_janji);

        // Fetch "Kas Pembangunan" category
        $kategoriPembangunan = KategoriTransaksi::where('nama_kategori', 'like', '%Pembangunan%')->first();
        $id_kategori = $kategoriPembangunan ? $kategoriPembangunan->id_kategori : null;

        DB::beginTransaction();
        try {
            // 1. Upload proof of payment if exists
            $buktiPath = null;
            if ($request->hasFile('bukti_bayar')) {
                $file = $request->file('bukti_bayar');
                $filename = time() . '_pembangunan_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/bukti'), $filename);
                $buktiPath = 'uploads/bukti/' . $filename;
            }

            // 2. Create the associated TransaksiKas (Building Cash)
            $transaksi = TransaksiKas::create([
                'tanggal' => $request->tanggal_bayar,
                'keterangan' => 'Pembayaran Janji Iman - ' . $janji->jemaat->nama_jemaat,
                'debit' => $request->jumlah_bayar,
                'kredit' => 0,
                'jenis_kas' => 'pembangunan',
                'id_kategori' => $id_kategori,
                'id_user' => Auth::id(),
                'id_jemaat' => $janji->id_jemaat,
                'bukti_transaksi' => $buktiPath,
                'status' => 'disetujui', // Pemasukan sah otomatis
            ]);

            // 3. Create the PembayaranJanji record
            PembayaranJanji::create([
                'id_janji' => $request->id_janji,
                'tanggal_bayar' => $request->tanggal_bayar,
                'jumlah_bayar' => $request->jumlah_bayar,
                'id_transaksi' => $transaksi->id_transaksi,
                'bukti_bayar' => $buktiPath,
                'id_user' => Auth::id(),
            ]);

            // 4. Update Pledge status
            $totalTerbayar = $janji->pembayaran()->sum('jumlah_bayar');
            if ($totalTerbayar >= $janji->total_janji) {
                $janji->update(['status' => 'lunas']);
            } else {
                $janji->update(['status' => 'belum_lunas']);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Cicilan pembayaran Janji Iman berhasil dicatat dan disinkronkan ke Buku Kas Pembangunan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal mencatat pembayaran: ' . $e->getMessage()]);
        }
    }

    public function bayarUpdate(Request $request, $church_slug, $id)
    {
        $request->validate([
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar' => 'required|numeric|min:0',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bayar = PembayaranJanji::findOrFail($id);
        $janji = JanjiIman::findOrFail($bayar->id_janji);
        $transaksi = TransaksiKas::findOrFail($bayar->id_transaksi);

        DB::beginTransaction();
        try {
            $buktiPath = $bayar->bukti_bayar;
            if ($request->hasFile('bukti_bayar')) {
                if ($bayar->bukti_bayar && File::exists(public_path($bayar->bukti_bayar))) {
                    File::delete(public_path($bayar->bukti_bayar));
                }

                $file = $request->file('bukti_bayar');
                $filename = time() . '_pembangunan_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/bukti'), $filename);
                $buktiPath = 'uploads/bukti/' . $filename;
            }

            // Update associated cash transaction
            $transaksi->update([
                'tanggal' => $request->tanggal_bayar,
                'debit' => $request->jumlah_bayar,
                'bukti_transaksi' => $buktiPath,
                'status' => 'disetujui',
                'alasan_penolakan' => null,
            ]);

            // Update payment record
            $bayar->update([
                'tanggal_bayar' => $request->tanggal_bayar,
                'jumlah_bayar' => $request->jumlah_bayar,
                'bukti_bayar' => $buktiPath,
            ]);

            // Recalculate pledge status
            $totalTerbayar = $janji->pembayaran()->sum('jumlah_bayar');
            if ($totalTerbayar >= $janji->total_janji) {
                $janji->update(['status' => 'lunas']);
            } else {
                $janji->update(['status' => 'belum_lunas']);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data cicilan pembayaran berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal mengubah pembayaran: ' . $e->getMessage()]);
        }
    }

    public function bayarDestroy($church_slug, $id)
    {
        $bayar = PembayaranJanji::findOrFail($id);
        $janji = JanjiIman::findOrFail($bayar->id_janji);
        $transaksi = TransaksiKas::findOrFail($bayar->id_transaksi);

        DB::beginTransaction();
        try {
            // Delete files
            if ($bayar->bukti_bayar && File::exists(public_path($bayar->bukti_bayar))) {
                File::delete(public_path($bayar->bukti_bayar));
            }

            // Delete payment & associated transaction
            $bayar->delete();
            $transaksi->delete();

            // Set pledge to unpaid since a payment was deleted
            $janji->update(['status' => 'belum_lunas']);

            DB::commit();
            return redirect()->back()->with('success', 'Cicilan pembayaran berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus pembayaran: ' . $e->getMessage()]);
        }
    }

    // ==========================================
    // 3. DAFTAR JANJI IMAN BELUM LUNAS
    // ==========================================
    public function belumLunasIndex(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $tanggal = $request->input('tanggal');

        // Query pledges that are not fully paid
        $query = JanjiIman::with(['jemaat', 'pembayaran'])
            ->where('status', 'belum_lunas')
            ->orderBy('tanggal_mulai', 'asc');
            
        if ($tanggal) {
            $query->whereDate('tanggal_mulai', $tanggal);
        } else {
            if ($bulan) {
                $query->whereMonth('tanggal_mulai', $bulan);
            }
            if ($tahun) {
                $query->whereYear('tanggal_mulai', $tahun);
            }
        }

        $items = $query->get();

        return view('bendahara.pemb_belum_lunas', compact('items', 'bulan', 'tahun', 'tanggal'));
    }

    public function laporanIndex(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Fetch payments within the date range
        $items = PembayaranJanji::with(['janjiIman.jemaat', 'user'])
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->orderBy('tanggal_bayar', 'asc')
            ->get();

        $totalTerkumpul = $items->sum('jumlah_bayar');

        return view('bendahara.pemb_laporan', compact('items', 'startDate', 'endDate', 'totalTerkumpul'));
    }

    public function laporanCetakPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $items = PembayaranJanji::with(['janjiIman.jemaat', 'user'])
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->orderBy('tanggal_bayar', 'asc')
            ->get();

        $totalTerkumpul = $items->sum('jumlah_bayar');
        
        $church = \App\Models\Church::where('slug', request()->route('church_slug'))->first();

        $pdf = Pdf::loadView('bendahara.pdf_pemb_laporan', compact('items', 'startDate', 'endDate', 'totalTerkumpul', 'church'));
        return $pdf->download('Laporan_Janji_Iman_' . $startDate . '_sd_' . $endDate . '.pdf');
    }

    public function laporanPengeluaranIndex(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Fetch expenditures in Kas Pembangunan
        $items = TransaksiKas::where('jenis_kas', 'pembangunan')
            ->where('kredit', '>', 0)
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPengeluaran = $items->sum('kredit');

        return view('bendahara.pemb_laporan_pengeluaran', compact('items', 'startDate', 'endDate', 'totalPengeluaran'));
    }

    public function laporanPengeluaranCetakPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $items = TransaksiKas::where('jenis_kas', 'pembangunan')
            ->where('kredit', '>', 0)
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPengeluaran = $items->sum('kredit');
        
        $church = \App\Models\Church::where('slug', request()->route('church_slug'))->first();

        $pdf = Pdf::loadView('bendahara.pdf_pemb_laporan_pengeluaran', compact('items', 'startDate', 'endDate', 'totalPengeluaran', 'church'));
        return $pdf->download('Laporan_Pengeluaran_Pembangunan_' . $startDate . '_sd_' . $endDate . '.pdf');
    }
}
