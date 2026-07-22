<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Jemaat;

class UserController extends Controller
{
    public function index($church_slug)
    {
        $users = User::with('jemaat')->orderBy('role', 'asc')->orderBy('nama', 'asc')->get();
        $jemaats = Jemaat::orderBy('nama_jemaat', 'asc')->get();
        return view('admin.user', compact('users', 'jemaats'));
    }

    public function store(Request $request, $church_slug)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,bendahara_kas,bendahara_pembangunan,pendeta,jemaat',
            'status' => 'required|in:aktif,nonaktif',
            'id_jemaat' => 'nullable|required_if:role,jemaat|exists:jemaat,id_jemaat',
        ], [
            'id_jemaat.required_if' => 'Jemaat wajib dipilih jika role adalah Jemaat.',
        ]);

        $churchId = app(\App\Services\TenantManager::class)->getTenantId();
        $exists = User::where('id_church', $churchId)->where('username', $request->username)->exists();
        if ($exists) {
            return back()->withErrors(['username' => 'Username ini sudah digunakan di gereja ini.'])->withInput();
        }

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'id_jemaat' => $request->role === 'jemaat' ? $request->id_jemaat : null,
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $church_slug, $id_user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,bendahara_kas,bendahara_pembangunan,pendeta,jemaat',
            'status' => 'required|in:aktif,nonaktif',
            'id_jemaat' => 'nullable|required_if:role,jemaat|exists:jemaat,id_jemaat',
        ], [
            'id_jemaat.required_if' => 'Jemaat wajib dipilih jika role adalah Jemaat.',
        ]);

        $user = User::findOrFail($id_user);

        $churchId = app(\App\Services\TenantManager::class)->getTenantId();
        $exists = User::where('id_church', $churchId)
            ->where('username', $request->username)
            ->where('id_user', '!=', $id_user)
            ->exists();
        if ($exists) {
            return back()->withErrors(['username' => 'Username ini sudah digunakan di gereja ini.'])->withInput();
        }

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'role' => $request->role,
            'status' => $request->status,
            'id_jemaat' => $request->role === 'jemaat' ? $request->id_jemaat : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($church_slug, $id_user)
    {
        if (auth()->id() == $id_user) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user = User::findOrFail($id_user);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
