<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\TenantManager;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin($church_slug)
    {
        return view('auth.login');
    }

    public function login(Request $request, $church_slug)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $tenantManager = app(TenantManager::class);
        $churchId = $tenantManager->getTenantId();

        // Find user belonging strictly to the current church with status aktif
        $user = User::where('id_church', $churchId)
            ->where('username', $request->username)
            ->where('status', 'aktif')
            ->first();

        // Verify password hash
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $request->filled('remember'));

            // Store tenant details in session
            session(['church_slug' => $church_slug]);
            $request->session()->regenerate();

            return redirect()->route('dashboard', ['church_slug' => $church_slug]);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah atau akun dinonaktifkan.',
        ])->onlyInput('username');
    }

    public function logout(Request $request, $church_slug)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login', ['church_slug' => $church_slug]);
    }
}
