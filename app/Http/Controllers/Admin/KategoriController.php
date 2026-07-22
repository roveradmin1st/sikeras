<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriTransaksi;

class KategoriController extends Controller
{
    public function index($church_slug)
    {
        $categories = KategoriTransaksi::orderBy('jenis', 'asc')->orderBy('nama_kategori', 'asc')->get();
        return view('admin.kategori', compact('categories'));
    }

    public function store(Request $request, $church_slug)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        KategoriTransaksi::create([
            'nama_kategori' => $request->nama_kategori,
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Kategori transaksi berhasil ditambahkan.');
    }

    public function update(Request $request, $church_slug, $id_kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $category = KategoriTransaksi::findOrFail($id_kategori);
        $category->update([
            'nama_kategori' => $request->nama_kategori,
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Kategori transaksi berhasil diperbarui.');
    }

    public function destroy($church_slug, $id_kategori)
    {
        $category = KategoriTransaksi::findOrFail($id_kategori);
        $category->delete();

        return back()->with('success', 'Kategori transaksi berhasil dihapus.');
    }
}
