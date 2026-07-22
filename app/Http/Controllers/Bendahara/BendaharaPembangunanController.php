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

    public function janjiIndex()
    {
        $items = JanjiIman::with('jemaat')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $jemaatList = Jemaat::where('status', 'aktif')->orderBy('nama_jemaat', 'asc')->get();

        return view('bendahara.pemb_janji', compact('items', 'jemaatList'));
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

    public function janjiUpdate(Request $request, $id)
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

    public function janjiDestroy($id)
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

    public function bayarIndex()
    {
        $items = PembayaranJanji::with(['janjiIman.jemaat', 'user'])
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        // Load active pledges that are not yet fully paid
        $janjiList = JanjiIman::with('jemaat')
            ->where('status', 'belum_lunas')
            ->get();

        return view('bendahara.pemb_bayar', compact('items', 'janjiList'));
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
                'status' => 'pending',
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

            // 4. Update Pledge status to lunas if payments cover the total commitment
            $totalTerbayar = $janji->pembayaran()->sum('jumlah_bayar') + $request->jumlah_bayar;
            if ($totalTerbayar >= $janji->total_janji) {
                $janji->update(['status' => 'lunas']);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Cicilan pembayaran Janji Iman berhasil dicatat dan disinkronkan ke Buku Kas Pembangunan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal mencatat pembayaran: ' . $e->getMessage()]);
        }
    }

    public function bayarUpdate(Request $request, $id)
    {
        $request->validate([
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar' => 'required|numeric|min:0',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $bayar = PembayaranJanji::findOrFail($id);
        $janji = JanjiIman::findOrFail($bayar->id_janji);
        $transaksi = TransaksiKas::findOrFail($bayar->id_transaksi);

        // Prevent modification if already approved by Pendeta
        if ($transaksi->status === 'disetujui') {
            return redirect()->back()->withErrors(['error' => 'Pembayaran yang transaksinya sudah disetujui Pendeta tidak dapat diubah.']);
        }

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

    public function bayarDestroy($id)
    {
        $bayar = PembayaranJanji::findOrFail($id);
        $janji = JanjiIman::findOrFail($bayar->id_janji);
        $transaksi = TransaksiKas::findOrFail($bayar->id_transaksi);

        if ($transaksi->status === 'disetujui') {
            return redirect()->back()->withErrors(['error' => 'Pembayaran yang transaksinya sudah disetujui Pendeta tidak dapat dihapus.']);
        }

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
    public function belumLunasIndex()
    {
        // Query pledges that are not fully paid
        $items = JanjiIman::with(['jemaat', 'pembayaran'])
            ->where('status', 'belum_lunas')
            ->get();

        return view('bendahara.pemb_belum_lunas', compact('items'));
    }

    public function laporanIndex()
    {
        return view('bendahara.pemb_laporan');
    }
}
