<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rayon;

class RayonController extends Controller
{
    public function index($church_slug)
    {
        $rayons = Rayon::orderBy('nama_rayon', 'asc')->get();
        return view('admin.rayon', compact('rayons'));
    }

    public function store(Request $request, $church_slug)
    {
        $request->validate([
            'nama_rayon' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Rayon::create([
            'nama_rayon' => $request->nama_rayon,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Rayon berhasil ditambahkan.');
    }

    public function update(Request $request, $church_slug, $id_rayon)
    {
        $request->validate([
            'nama_rayon' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $rayon = Rayon::findOrFail($id_rayon);
        $rayon->update([
            'nama_rayon' => $request->nama_rayon,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Rayon berhasil diperbarui.');
    }

    public function destroy($church_slug, $id_rayon)
    {
        $rayon = Rayon::findOrFail($id_rayon);
        $rayon->delete();

        return back()->with('success', 'Rayon berhasil dihapus.');
    }
}
