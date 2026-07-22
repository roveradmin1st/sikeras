@extends('layouts.app')

@section('title', 'Profil Pengguna - SIKER')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 max-w-xl mx-auto">
    <div class="flex items-center space-x-4 mb-6">
        <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-bold text-2xl">
            {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900">{{ auth()->user()->nama }}</h2>
            <p class="text-sm text-primary-600 uppercase font-semibold tracking-wider">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
        </div>
    </div>
    
    <div class="space-y-4 border-t border-slate-100 pt-6">
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Username</label>
            <p class="text-sm font-medium text-slate-800 mt-1 font-mono">{{ auth()->user()->username }}</p>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Akun</label>
            <p class="text-sm font-semibold text-emerald-600 mt-1">Aktif</p>
        </div>
    </div>
</div>
@endsection
