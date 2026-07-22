@extends('layouts.app')

@section('title', 'Masuk - GPdI Mahanaim')

@section('content')
<div class="flex items-center justify-center min-h-[70vh] w-full">
    <div class="w-full max-w-md">
        <!-- Glassmorphic Login Card -->
        <div class="bg-white/85 backdrop-blur-md rounded-2xl shadow-xl border border-white/60 p-8">
            <div class="text-center mb-6">
                <!-- Inserted GPdI Logo Image directly into Login Card without double borders -->
                <div class="inline-flex mb-4">
                    <img src="{{ asset('images/logo-gpdi.png') }}" class="w-24 h-auto object-contain" alt="Logo GPdI">
                </div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang</h2>
                <p class="text-sm text-slate-500 mt-1">Silakan masuk ke akun pengurus keuangan Anda</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-red-800">Gagal masuk:</p>
                        <ul class="mt-1 list-disc list-inside text-xs text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post', ['church_slug' => request()->route('church_slug')]) }}" class="space-y-6">
                @csrf
                
                <!-- Username field -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                            class="block w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-white/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm"
                            placeholder="Masukkan username Anda">
                    </div>
                </div>

                <!-- Password field -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="key-round" class="w-5 h-5"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" id="password" required
                            class="block w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 bg-white/50 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-sm"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <i x-show="!show" data-lucide="eye" class="w-5 h-5" x-cloak></i>
                            <i x-show="show" data-lucide="eye-off" class="w-5 h-5" x-cloak></i>
                        </button>
                    </div>
                </div>

                <!-- Remember me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember"
                        class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500/20 transition-all">
                    <label for="remember" class="ml-2.5 block text-sm font-medium text-slate-600">Ingat saya di perangkat ini</label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:ring-offset-2 transition-all duration-150 shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30">
                    Masuk ke Sistem
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
