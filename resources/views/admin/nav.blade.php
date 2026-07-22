<div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-8">
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('dashboard', ['church_slug' => request()->route('church_slug')]) }}" 
           class="flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/25' : 'text-slate-600 hover:bg-slate-50' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.user.index', ['church_slug' => request()->route('church_slug')]) }}" 
           class="flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.user.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/25' : 'text-slate-600 hover:bg-slate-50' }}">
            <i data-lucide="users" class="w-4 h-4"></i>
            <span>Manajemen User</span>
        </a>
        <a href="{{ route('admin.jemaat.index', ['church_slug' => request()->route('church_slug')]) }}" 
           class="flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.jemaat.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/25' : 'text-slate-600 hover:bg-slate-50' }}">
            <i data-lucide="user-check" class="w-4 h-4"></i>
            <span>Data Jemaat</span>
        </a>
        <a href="{{ route('admin.rayon.index', ['church_slug' => request()->route('church_slug')]) }}" 
           class="flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.rayon.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/25' : 'text-slate-600 hover:bg-slate-50' }}">
            <i data-lucide="map-pin" class="w-4 h-4"></i>
            <span>Data Rayon</span>
        </a>
        <a href="{{ route('admin.kategori.index', ['church_slug' => request()->route('church_slug')]) }}" 
           class="flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.kategori.*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/25' : 'text-slate-600 hover:bg-slate-50' }}">
            <i data-lucide="tags" class="w-4 h-4"></i>
            <span>Kategori Kas</span>
        </a>
    </div>
    <div class="flex items-center px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100 text-xs font-semibold text-slate-500">
        <i data-lucide="shield" class="w-3.5 h-3.5 text-primary-500 mr-2"></i>
        <span>MODUL ADMINISTRATOR</span>
    </div>
</div>
