<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiKas;
use App\Models\KategoriTransaksi;
use App\Models\Jemaat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class BendaharaKasController extends Controller
{
    // ==========================================
    // 1. PERSEMBAHAN MINGGUAN
    // ==========================================
    public function persembahanIndex()
    {
        $kategori = KategoriTransaksi::where('nama_kategori', 'like', '%Persembahan%')->first();
        $id_kategori = $kategori ? $kategori->id_kategori : null;

        $items = TransaksiKas::where('id_kategori', $id_kategori)
            ->with(['jemaat.rayon', 'user'])
            ->orderBy('tanggal', 'asc')
            ->get();

        $jemaatList = Jemaat::where('status', 'aktif')->orderBy('nama_jemaat', 'asc')->get();

        return view('bendahara.kas_persembahan', compact('items', 'id_kategori', 'jemaatList'));
    }

    public function persembahanStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_kategori' => 'required|integer',
            'persembahan' => 'required|array',
            'persembahan.*.id_jemaat' => 'nullable|integer',
            'persembahan.*.keterangan' => 'nullable|string',
            'persembahan.*.nominal' => 'required|numeric|min:1',
        ]);

        $totalNominal = 0;
        $rincianArr = [];

        // Ambil nama jemaat untuk rincian
        foreach ($request->persembahan as $item) {
            $totalNominal += $item['nominal'];
            
            $namaJemaat = "Anonim";
            if (!empty($item['id_jemaat'])) {
                $jemaat = \App\Models\Jemaat::find($item['id_jemaat']);
                if ($jemaat) {
                    $namaJemaat = $jemaat->nama_jemaat;
                }
            }

            $ket = $item['keterangan'] ?? 'Persembahan';
            $rincianArr[] = $namaJemaat . " (" . $ket . ": Rp" . number_format($item['nominal'], 0, ',', '.') . ")";
        }

        $rincianText = implode(', ', $rincianArr);
        
        $keteranganGabungan = "Total Persembahan Ibadah. Rincian: " . $rincianText;

        TransaksiKas::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $keteranganGabungan,
            'debit' => $totalNominal,
            'kredit' => 0,
            'jenis_kas' => 'kas_umum',
            'id_kategori' => $request->id_kategori,
            'id_user' => Auth::id(),
            'id_jemaat' => null, // Karena digabung, id_jemaat jadi null
            'bukti_transaksi' => null,
            'status' => 'disetujui',
        ]);

        return redirect()->back()->with('success', 'Data persembahan berhasil dijumlahkan dan dicatat sebagai 1 transaksi.');
    }

    public function persembahanUpdate(Request $request, $church_slug, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'debit' => 'required|numeric|min:1',
            'id_jemaat' => 'nullable|integer',
            'keterangan' => 'nullable|string',
        ]);

        $item = TransaksiKas::findOrFail($id);
        
        $data = $request->only(['tanggal', 'debit', 'id_jemaat', 'keterangan']);
        $data['status'] = 'disetujui';

        $item->update($data);

        return redirect()->back()->with('success', 'Data persembahan berhasil diperbarui.');
    }

    public function persembahanDestroy($church_slug, $id)
    {
        $item = TransaksiKas::findOrFail($id);

        if ($item->bukti_transaksi && File::exists(public_path($item->bukti_transaksi))) {
            File::delete(public_path($item->bukti_transaksi));
        }

        $item->delete();

        return redirect()->back()->with('success', 'Data persembahan mingguan berhasil dihapus.');
    }

    // ==========================================
    // 2. DONASI UMUM
    // ==========================================
    public function donasiIndex()
    {
        $kategori = KategoriTransaksi::where('nama_kategori', 'like', '%Donasi%')->first();
        $id_kategori = $kategori ? $kategori->id_kategori : null;

        $items = TransaksiKas::where('id_kategori', $id_kategori)
            ->with('jemaat.rayon')
            ->orderBy('tanggal', 'asc')
            ->get();

        $jemaatList = Jemaat::where('status', 'aktif')->orderBy('nama_jemaat', 'asc')->get();

        return view('bendahara.kas_donasi', compact('items', 'id_kategori', 'jemaatList'));
    }

    public function donasiStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'debit' => 'required|numeric|min:0',
            'id_jemaat' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_kategori' => 'required|integer',
        ]);

        $data = $request->only(['tanggal', 'debit', 'id_jemaat', 'keterangan', 'id_kategori']);
        $data['kredit'] = 0;
        $data['jenis_kas'] = 'kas_umum';
        $data['id_user'] = Auth::id();
        $data['status'] = 'disetujui'; // Pemasukan sah otomatis

        if ($request->hasFile('bukti_transaksi')) {
            $file = $request->file('bukti_transaksi');
            $filename = time() . '_donasi_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $data['bukti_transaksi'] = 'uploads/bukti/' . $filename;
        }

        TransaksiKas::create($data);

        return redirect()->back()->with('success', 'Data donasi berhasil dicatat dan langsung Sah/Disetujui.');
    }

    public function donasiUpdate(Request $request, $church_slug, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'debit' => 'required|numeric|min:0',
            'id_jemaat' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = TransaksiKas::findOrFail($id);

        $data = $request->only(['tanggal', 'debit', 'id_jemaat', 'keterangan']);
        $data['status'] = 'disetujui';

        if ($request->hasFile('bukti_transaksi')) {
            if ($item->bukti_transaksi && File::exists(public_path($item->bukti_transaksi))) {
                File::delete(public_path($item->bukti_transaksi));
            }

            $file = $request->file('bukti_transaksi');
            $filename = time() . '_donasi_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $data['bukti_transaksi'] = 'uploads/bukti/' . $filename;
        }

        $item->update($data);

        return redirect()->back()->with('success', 'Data donasi jemaat berhasil diperbarui.');
    }

    public function donasiDestroy($church_slug, $id)
    {
        $item = TransaksiKas::findOrFail($id);

        if ($item->bukti_transaksi && File::exists(public_path($item->bukti_transaksi))) {
            File::delete(public_path($item->bukti_transaksi));
        }

        $item->delete();

        return redirect()->back()->with('success', 'Data donasi jemaat berhasil dihapus.');
    }

    // ==========================================
    // 3. TRANSAKSI KAS UMUM & RAYON (DEBIT/KREDIT)
    // ==========================================
    public function transaksiIndex()
    {
        // Exclude Persembahan and Donasi from this list to keep menus focused
        $kategoriPersembahan = KategoriTransaksi::where('nama_kategori', 'like', '%Persembahan%')->first();
        $kategoriDonasi = KategoriTransaksi::where('nama_kategori', 'like', '%Donasi%')->first();
        
        $excludedIds = array_filter([
            $kategoriPersembahan ? $kategoriPersembahan->id_kategori : null,
            $kategoriDonasi ? $kategoriDonasi->id_kategori : null
        ]);

        $items = TransaksiKas::whereNotIn('id_kategori', $excludedIds)
            ->where(function($query) {
                $query->whereIn('jenis_kas', ['kas_umum', 'rayon'])
                      ->orWhere(function($q) {
                          $q->where('jenis_kas', 'pembangunan')
                            ->where('kredit', '>', 0);
                      });
            })
            ->with(['kategori', 'jemaat.rayon'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('id_transaksi', 'asc')
            ->get();

        $kategoriList = KategoriTransaksi::where('status', 'aktif')->orderBy('nama_kategori', 'asc')->get();
        $jemaatList = Jemaat::where('status', 'aktif')->orderBy('nama_jemaat', 'asc')->get();

        return view('bendahara.kas_transaksi', compact('items', 'kategoriList', 'jemaatList'));
    }

    public function transaksiStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric|min:0',
            'id_kategori' => 'required|integer',
            'jenis_kas' => 'required|in:kas_umum,rayon,pembangunan',
            'id_jemaat' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['tanggal', 'id_kategori', 'jenis_kas', 'id_jemaat', 'keterangan']);
        
        if ($request->tipe === 'masuk') {
            $data['debit'] = $request->jumlah;
            $data['kredit'] = 0;
            $data['status'] = 'disetujui'; // Pemasukan sah otomatis
        } else {
            $data['debit'] = 0;
            $data['kredit'] = $request->jumlah;
            $data['status'] = 'pending'; // Pengeluaran butuh persetujuan
        }

        $data['id_user'] = Auth::id();

        if ($request->hasFile('bukti_transaksi')) {
            $file = $request->file('bukti_transaksi');
            $filename = time() . '_kas_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $data['bukti_transaksi'] = 'uploads/bukti/' . $filename;
        }

        TransaksiKas::create($data);

        $msg = $request->tipe === 'masuk' 
            ? 'Catatan transaksi kas (Pemasukan) berhasil disimpan dan langsung Sah/Disetujui.'
            : 'Catatan transaksi kas (Pengeluaran) berhasil disimpan. Menunggu persetujuan Pendeta.';

        return redirect()->back()->with('success', $msg);
    }

    public function transaksiUpdate(Request $request, $church_slug, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric|min:0',
            'id_kategori' => 'required|integer',
            'jenis_kas' => 'required|in:kas_umum,rayon,pembangunan',
            'id_jemaat' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = TransaksiKas::findOrFail($id);

        if ($item->kredit > 0 && $item->status === 'disetujui') {
            return redirect()->back()->withErrors(['error' => 'Transaksi pengeluaran yang sudah disetujui Pendeta tidak dapat diubah.']);
        }

        $data = $request->only(['tanggal', 'id_kategori', 'jenis_kas', 'id_jemaat', 'keterangan']);
        $data['alasan_penolakan'] = null;
        if ($request->tipe === 'masuk') {
            $data['debit'] = $request->jumlah;
            $data['kredit'] = 0;
            $data['status'] = 'disetujui'; // Pemasukan sah otomatis
        } else {
            $data['debit'] = 0;
            $data['kredit'] = $request->jumlah;
            $data['status'] = 'pending'; // Pengeluaran butuh persetujuan
        }

        if ($request->hasFile('bukti_transaksi')) {
            if ($item->bukti_transaksi && File::exists(public_path($item->bukti_transaksi))) {
                File::delete(public_path($item->bukti_transaksi));
            }

            $file = $request->file('bukti_transaksi');
            $filename = time() . '_kas_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $data['bukti_transaksi'] = 'uploads/bukti/' . $filename;
        }

        $item->update($data);

        return redirect()->back()->with('success', 'Catatan transaksi kas berhasil diperbarui.');
    }

    public function transaksiDestroy($church_slug, $id)
    {
        $item = TransaksiKas::findOrFail($id);

        if ($item->kredit > 0 && $item->status === 'disetujui') {
            return redirect()->back()->withErrors(['error' => 'Transaksi pengeluaran yang sudah disetujui Pendeta tidak dapat dihapus.']);
        }

        if ($item->bukti_transaksi && File::exists(public_path($item->bukti_transaksi))) {
            File::delete(public_path($item->bukti_transaksi));
        }

        $item->delete();

        return redirect()->back()->with('success', 'Catatan transaksi kas berhasil dihapus.');
    }

    // ==========================================
    // 4. BUKU KAS UMUM (LEDGER REAL-TIME)
    // ==========================================
    public function bukuIndex(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $tanggal = $request->input('tanggal');

        // Fetch all approved transactions (Debit/Kredit) to show running balance
        $items = TransaksiKas::where('status', 'disetujui')
            ->whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->with(['kategori', 'jemaat.rayon'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('id_transaksi', 'asc')
            ->get();

        // Calculate running balance dynamically
        $runningBalance = 0;
        foreach ($items as $item) {
            $runningBalance += ($item->debit - $item->kredit);
            $item->running_saldo = $runningBalance;
        }

        // Apply filters on the collection
        if ($tanggal) {
            $items = $items->where('tanggal', $tanggal);
        } else {
            if ($bulan) {
                $items = $items->filter(function ($item) use ($bulan) {
                    return \Carbon\Carbon::parse($item->tanggal)->format('m') == str_pad($bulan, 2, '0', STR_PAD_LEFT);
                });
            }
            if ($tahun) {
                $items = $items->filter(function ($item) use ($tahun) {
                    return \Carbon\Carbon::parse($item->tanggal)->format('Y') == $tahun;
                });
            }
        }

        // Show oldest first (asc) as requested by user
        $items = $items->values();

        return view('bendahara.kas_buku', compact('items', 'bulan', 'tahun', 'tanggal'));
    }

    public function laporanKasIndex(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $idRayon = $request->input('id_rayon');

        $query = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['kategori', 'jemaat.rayon']);

        if ($idRayon) {
            $query->whereHas('jemaat', function($q) use ($idRayon) {
                $q->where('id_rayon', $idRayon);
            });
        }

        $items = $query->orderBy('tanggal', 'asc')->get();

        $totalDebit = $items->sum('debit');
        $totalKredit = $items->sum('kredit');

        // Calculate Saldo Awal (before start date)
        $queryAwal = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('status', 'disetujui')
            ->where('tanggal', '<', $startDate);
        if ($idRayon) {
            $queryAwal->whereHas('jemaat', function($q) use ($idRayon) {
                $q->where('id_rayon', $idRayon);
            });
        }
        $saldoAwal = $queryAwal->sum('debit') - $queryAwal->sum('kredit');

        $saldoAkhir = $saldoAwal + $totalDebit - $totalKredit;

        $rayonList = \App\Models\Rayon::orderBy('nama_rayon', 'asc')->get();

        return view('bendahara.laporan_kas', compact('items', 'startDate', 'endDate', 'idRayon', 'rayonList', 'totalDebit', 'totalKredit', 'saldoAwal', 'saldoAkhir'));
    }

    public function laporanKasCetakPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $idRayon = $request->input('id_rayon');

        $query = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->with(['kategori', 'jemaat.rayon']);

        if ($idRayon) {
            $query->whereHas('jemaat', function($q) use ($idRayon) {
                $q->where('id_rayon', $idRayon);
            });
        }

        $items = $query->orderBy('tanggal', 'asc')->get();

        $totalDebit = $items->sum('debit');
        $totalKredit = $items->sum('kredit');

        // Calculate Saldo Awal (before start date)
        $queryAwal = TransaksiKas::whereIn('jenis_kas', ['kas_umum', 'rayon'])
            ->where('status', 'disetujui')
            ->where('tanggal', '<', $startDate);
        if ($idRayon) {
            $queryAwal->whereHas('jemaat', function($q) use ($idRayon) {
                $q->where('id_rayon', $idRayon);
            });
        }
        $saldoAwal = $queryAwal->sum('debit') - $queryAwal->sum('kredit');

        $saldoAkhir = $saldoAwal + $totalDebit - $totalKredit;
        
        $church = \App\Models\Church::where('slug', request()->route('church_slug'))->first();

        $rayonFilter = null;
        if ($idRayon) {
            $rayonObj = \App\Models\Rayon::find($idRayon);
            $rayonFilter = $rayonObj ? $rayonObj->nama_rayon : null;
        }

        $pdf = Pdf::loadView('bendahara.pdf_laporan_kas', compact('items', 'startDate', 'endDate', 'totalDebit', 'totalKredit', 'saldoAwal', 'saldoAkhir', 'church', 'rayonFilter'));
        return $pdf->download('Laporan_Kas_' . $startDate . '_sd_' . $endDate . '.pdf');
    }

    // ==========================================
    // 6. LAPORAN PERSEMBAHAN MINGGUAN
    // ==========================================
    public function laporanPersembahanIndex(Request $request)
    {
        $startDate = $request->input('start_date', \Carbon\Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', \Carbon\Carbon::now()->endOfMonth()->toDateString());

        $kategori = \App\Models\KategoriTransaksi::where('nama_kategori', 'like', '%Persembahan%')->first();
        $id_kategori = $kategori ? $kategori->id_kategori : null;

        $query = TransaksiKas::where('id_kategori', $id_kategori)
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        $items = $query->orderBy('tanggal', 'asc')->get();
        $totalPersembahan = $items->sum('debit');

        return view('bendahara.laporan_persembahan', compact('items', 'startDate', 'endDate', 'totalPersembahan'));
    }

    public function laporanPersembahanCetakPdf(Request $request)
    {
        $startDate = $request->input('start_date', \Carbon\Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', \Carbon\Carbon::now()->endOfMonth()->toDateString());

        $kategori = \App\Models\KategoriTransaksi::where('nama_kategori', 'like', '%Persembahan%')->first();
        $id_kategori = $kategori ? $kategori->id_kategori : null;

        $query = TransaksiKas::where('id_kategori', $id_kategori)
            ->where('status', 'disetujui')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        $items = $query->orderBy('tanggal', 'asc')->get();
        $totalPersembahan = $items->sum('debit');

        $church = \App\Models\Church::where('slug', request()->route('church_slug'))->first();

        $pdf = Pdf::loadView('bendahara.pdf_laporan_persembahan', compact('items', 'startDate', 'endDate', 'totalPersembahan', 'church'));
        return $pdf->download('Laporan_Persembahan_' . $startDate . '_sd_' . $endDate . '.pdf');
    }
}
