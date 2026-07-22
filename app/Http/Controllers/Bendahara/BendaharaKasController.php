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
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('bendahara.kas_persembahan', compact('items', 'id_kategori'));
    }

    public function persembahanStore(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'debit' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_kategori' => 'required|integer',
        ]);

        $data = $request->only(['tanggal', 'debit', 'keterangan', 'id_kategori']);
        $data['kredit'] = 0;
        $data['jenis_kas'] = 'kas_umum';
        $data['id_user'] = Auth::id();
        $data['status'] = 'pending'; // Default status is pending (approval by Pendeta)

        if ($request->hasFile('bukti_transaksi')) {
            $file = $request->file('bukti_transaksi');
            $filename = time() . '_persembahan_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $data['bukti_transaksi'] = 'uploads/bukti/' . $filename;
        }

        TransaksiKas::create($data);

        return redirect()->back()->with('success', 'Data persembahan mingguan berhasil dicatat. Menunggu persetujuan Pendeta.');
    }

    public function persembahanUpdate(Request $request, $church_slug, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'debit' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = TransaksiKas::findOrFail($id);
        
        // Prevent editing if already approved (standard auditing)
        if ($item->status === 'disetujui') {
            return redirect()->back()->withErrors(['error' => 'Transaksi yang sudah disetujui Pendeta tidak dapat diubah.']);
        }

        $data = $request->only(['tanggal', 'debit', 'keterangan']);

        if ($request->hasFile('bukti_transaksi')) {
            // Delete old file
            if ($item->bukti_transaksi && File::exists(public_path($item->bukti_transaksi))) {
                File::delete(public_path($item->bukti_transaksi));
            }

            $file = $request->file('bukti_transaksi');
            $filename = time() . '_persembahan_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $data['bukti_transaksi'] = 'uploads/bukti/' . $filename;
        }

        $item->update($data);

        return redirect()->back()->with('success', 'Data persembahan mingguan berhasil diperbarui.');
    }

    public function persembahanDestroy($church_slug, $id)
    {
        $item = TransaksiKas::findOrFail($id);

        if ($item->status === 'disetujui') {
            return redirect()->back()->withErrors(['error' => 'Transaksi yang sudah disetujui Pendeta tidak dapat dihapus.']);
        }

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
            ->orderBy('tanggal', 'desc')
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
        $data['status'] = 'pending';

        if ($request->hasFile('bukti_transaksi')) {
            $file = $request->file('bukti_transaksi');
            $filename = time() . '_donasi_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $data['bukti_transaksi'] = 'uploads/bukti/' . $filename;
        }

        TransaksiKas::create($data);

        return redirect()->back()->with('success', 'Data donasi jemaat berhasil dicatat. Menunggu persetujuan Pendeta.');
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

        if ($item->status === 'disetujui') {
            return redirect()->back()->withErrors(['error' => 'Transaksi yang sudah disetujui Pendeta tidak dapat diubah.']);
        }

        $data = $request->only(['tanggal', 'debit', 'id_jemaat', 'keterangan']);

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

        if ($item->status === 'disetujui') {
            return redirect()->back()->withErrors(['error' => 'Transaksi yang sudah disetujui Pendeta tidak dapat dihapus.']);
        }

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
            ->with(['kategori', 'jemaat'])
            ->orderBy('tanggal', 'desc')
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
            'jenis_kas' => 'required|in:kas_umum,rayon',
            'id_jemaat' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['tanggal', 'id_kategori', 'jenis_kas', 'id_jemaat', 'keterangan']);
        
        if ($request->tipe === 'masuk') {
            $data['debit'] = $request->jumlah;
            $data['kredit'] = 0;
        } else {
            $data['debit'] = 0;
            $data['kredit'] = $request->jumlah;
        }

        $data['id_user'] = Auth::id();
        $data['status'] = 'pending';

        if ($request->hasFile('bukti_transaksi')) {
            $file = $request->file('bukti_transaksi');
            $filename = time() . '_kas_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/bukti'), $filename);
            $data['bukti_transaksi'] = 'uploads/bukti/' . $filename;
        }

        TransaksiKas::create($data);

        return redirect()->back()->with('success', 'Catatan transaksi kas berhasil disimpan. Menunggu persetujuan Pendeta.');
    }

    public function transaksiUpdate(Request $request, $church_slug, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric|min:0',
            'id_kategori' => 'required|integer',
            'jenis_kas' => 'required|in:kas_umum,rayon',
            'id_jemaat' => 'nullable|integer',
            'keterangan' => 'nullable|string',
            'bukti_transaksi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = TransaksiKas::findOrFail($id);

        if ($item->status === 'disetujui') {
            return redirect()->back()->withErrors(['error' => 'Transaksi yang sudah disetujui Pendeta tidak dapat diubah.']);
        }

        $data = $request->only(['tanggal', 'id_kategori', 'jenis_kas', 'id_jemaat', 'keterangan']);

        if ($request->tipe === 'masuk') {
            $data['debit'] = $request->jumlah;
            $data['kredit'] = 0;
        } else {
            $data['debit'] = 0;
            $data['kredit'] = $request->jumlah;
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

        if ($item->status === 'disetujui') {
            return redirect()->back()->withErrors(['error' => 'Transaksi yang sudah disetujui Pendeta tidak dapat dihapus.']);
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
    public function bukuIndex()
    {
        // Fetch all approved transactions (Debit/Kredit) to show running balance
        $items = TransaksiKas::where('status', 'disetujui')
            ->with(['kategori', 'jemaat'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('id_transaksi', 'asc')
            ->get();

        // Calculate running balance dynamically
        $runningBalance = 0;
        foreach ($items as $item) {
            $runningBalance += ($item->debit - $item->kredit);
            $item->running_saldo = $runningBalance;
        }

        // Reverse to show newest first in table
        $items = $items->reverse();

        return view('bendahara.kas_buku', compact('items'));
    }

    public function laporanKasIndex()
    {
        return view('bendahara.laporan_kas');
    }

    public function laporanRayonIndex()
    {
        return view('bendahara.laporan_rayon');
    }
}
