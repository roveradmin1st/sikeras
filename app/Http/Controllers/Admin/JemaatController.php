<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jemaat;
use App\Models\Rayon;
use App\Models\JanjiIman;
use App\Models\TransaksiKas;

class JemaatController extends Controller
{
    public function index($church_slug)
    {
        $jemaats = Jemaat::with('rayon')->orderBy('nama_jemaat', 'asc')->get();
        $rayons = Rayon::orderBy('nama_rayon', 'asc')->get();

        // Calculations for the 4 summary cards mentioned in thesis Gambar IV.19 / Line 989
        $totalJemaat = $jemaats->count();
        $jumlahRayon = $rayons->count();
        $totalJanjiIman = JanjiIman::count();
        
        $totalPersembahanVal = TransaksiKas::where('status', 'disetujui')
            ->whereHas('kategori', function ($q) {
                $q->where('nama_kategori', 'like', '%persembahan%');
            })
            ->sum('debit');
        
        $totalPersembahan = 'Rp ' . number_format($totalPersembahanVal, 0, ',', '.');

        return view('admin.jemaat', compact(
            'jemaats',
            'rayons',
            'totalJemaat',
            'jumlahRayon',
            'totalJanjiIman',
            'totalPersembahan'
        ));
    }

    public function store(Request $request, $church_slug)
    {
        $request->validate([
            'nama_jemaat' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'id_rayon' => 'required|exists:rayon,id_rayon',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Jemaat::create([
            'nama_jemaat' => $request->nama_jemaat,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_rayon' => $request->id_rayon,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Jemaat berhasil ditambahkan.');
    }

    public function update(Request $request, $church_slug, $id_jemaat)
    {
        $request->validate([
            'nama_jemaat' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'id_rayon' => 'required|exists:rayon,id_rayon',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jemaat = Jemaat::findOrFail($id_jemaat);
        $jemaat->update([
            'nama_jemaat' => $request->nama_jemaat,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_rayon' => $request->id_rayon,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Jemaat berhasil diperbarui.');
    }

    public function buatAkun(Request $request, $church_slug, $id_jemaat)
    {
        $jemaat = Jemaat::findOrFail($id_jemaat);
        $church = \App\Models\Church::where('slug', $church_slug)->firstOrFail();

        // Check if user already exists
        $exists = \App\Models\User::where('id_jemaat', $id_jemaat)->first();
        if ($exists) {
            return back()->withErrors(['error' => 'Jemaat ini sudah memiliki akun login (Username: ' . $exists->username . ')']);
        }

        // Generate username (lowercase, remove spaces)
        $username = strtolower(str_replace(' ', '', $jemaat->nama_jemaat)) . rand(10, 99);
        
        $user = \App\Models\User::create([
            'id_church' => $church->id_church,
            'nama' => $jemaat->nama_jemaat,
            'username' => $username,
            'password' => bcrypt('jemaat123'), // Default password
            'role' => 'jemaat',
            'status' => 'aktif',
            'id_jemaat' => $jemaat->id_jemaat,
        ]);

        return back()->with('success', 'Akun berhasil dibuat! Username: ' . $user->username . ' | Password default: jemaat123');
    }

    public function destroy($church_slug, $id_jemaat)
    {
        $jemaat = Jemaat::findOrFail($id_jemaat);
        $jemaat->delete();

        return back()->with('success', 'Jemaat berhasil dihapus.');
    }
}
