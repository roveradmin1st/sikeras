<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIKER - GPdI Mahanaim')</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f4f7fa',
                            100: '#e5eef5',
                            200: '#c5daf0',
                            300: '#92bce3',
                            400: '#5898d2',
                            500: '#003E7E', // GPdI Theme Deep Blue
                            600: '#00356c',
                            700: '#002a56',
                            800: '#002041',
                            900: '#00162d',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js for lightweight reactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @yield('styles')
</head>
<body class="text-slate-800">

    @auth
        <!-- Dynamic Sidebar Layout for Authenticated Users (matches Gambar IV.16 / image24) -->
        <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: window.innerWidth > 768 }">
            
            <!-- Left Sidebar (fixed on desktop, toggleable on mobile) -->
            <aside class="fixed inset-y-0 left-0 z-40 bg-[#003E7E] text-blue-100 flex flex-col justify-between transition-all duration-300 md:static md:translate-x-0 md:inset-0"
                   :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full md:w-0 md:opacity-0 md:overflow-hidden'">
                
                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                    <!-- Sidebar Header (GPdI Logo Image) -->
                    <div class="flex items-center justify-between px-6 mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-1 bg-white rounded-xl shadow-md flex items-center justify-center">
                                <img src="{{ asset('images/logo-gpdi.png') }}" class="w-9 h-9 object-contain" alt="Logo GPdI">
                            </div>
                            <div>
                                <span class="text-xl font-black text-white tracking-wider">SIKER</span>
                                <div class="text-[10px] text-blue-200 font-bold uppercase tracking-wider">GPdI Mahanaim</div>
                            </div>
                        </div>
                        <!-- Close Arrow Button (Visible only on mobile to hide sidebar) -->
                        <button @click="sidebarOpen = false" class="md:hidden p-1.5 rounded-lg hover:bg-black/10 text-blue-200 hover:text-white transition-colors focus:outline-none" title="Sembunyikan Navigasi">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Navigation Links (Dynamic based on Role) -->
                    <nav class="flex-1 px-4 space-y-1">
                        @if(auth()->user()->role === 'admin')
                            <!-- Admin Sidebar Menu (100% Matches Gambar IV.16) -->
                            <a href="{{ route('dashboard', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('admin.user.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.user.*') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="users" class="w-5 h-5"></i>
                                <span>Manajemen User</span>
                            </a>
                            <a href="{{ route('admin.rayon.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.rayon.*') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                                <span>Manajemen Rayon</span>
                            </a>
                            <a href="{{ route('admin.jemaat.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.jemaat.*') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="user-check" class="w-5 h-5"></i>
                                <span>Data Jemaat</span>
                            </a>
                            <a href="{{ route('admin.kategori.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.kategori.*') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="tags" class="w-5 h-5"></i>
                                <span>Kategori Transaksi</span>
                            </a>
                            <a href="{{ route('admin.backup', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.backup') ? 'bg-white/10 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="database" class="w-5 h-5"></i>
                                <span>Backup & Restorasi</span>
                            </a>
                            <a href="{{ route('admin.reports', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.reports') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="files" class="w-5 h-5"></i>
                                <span>Lihat Semua Laporan</span>
                            </a>
                            <a href="{{ route('admin.pengaturan', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('admin.pengaturan') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="settings" class="w-5 h-5"></i>
                                <span>Pengaturan Instansi</span>
                            </a>
                        @elseif(auth()->user()->role === 'bendahara_kas')
                            <!-- Bendahara Kas Sidebar Menu (100% Matches Gambar IV.31 Wireframe) -->
                            <a href="{{ route('dashboard', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('kas.persembahan.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('kas.persembahan.*') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="coins" class="w-5 h-5"></i>
                                <span>Persembahan Mingguan</span>
                            </a>
                            <a href="{{ route('kas.donasi.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('kas.donasi.*') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="heart-handshake" class="w-5 h-5"></i>
                                <span>Donasi Umum</span>
                            </a>
                            <a href="{{ route('kas.buku.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('kas.buku.*') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="book-open" class="w-5 h-5"></i>
                                <span>Buku Kas Gereja</span>
                            </a>
                            <a href="{{ route('kas.transaksi.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('kas.transaksi.*') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="arrow-left-right" class="w-5 h-5"></i>
                                <span>Data Transaksi Kas</span>
                            </a>
                            <a href="{{ route('kas.laporan', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('kas.laporan') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                <span>Laporan Kas</span>
                            </a>
                            <a href="{{ route('kas.laporan-rayon', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('kas.laporan-rayon') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="map" class="w-5 h-5"></i>
                                <span>Laporan Rayon Masuk</span>
                            </a>
                        @elseif(auth()->user()->role === 'bendahara_pembangunan')
                            <!-- Bendahara Pembangunan Sidebar Menu (100% Matches Gambar IV.42 Wireframe) -->
                            <a href="{{ route('dashboard', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('pembangunan.janji.create', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pembangunan.janji.create') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="file-plus" class="w-5 h-5"></i>
                                <span>Input Data Janji Iman</span>
                            </a>
                            <a href="{{ route('pembangunan.bayar.create', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pembangunan.bayar.create') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="credit-card" class="w-5 h-5"></i>
                                <span>Input Pembayaran Janji Iman</span>
                            </a>
                            <a href="{{ route('pembangunan.janji.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pembangunan.janji.index') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="database" class="w-5 h-5"></i>
                                <span>Data Janji Iman</span>
                            </a>
                            <a href="{{ route('pembangunan.belum-lunas.index', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pembangunan.belum-lunas.*') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                                <span>Daftar Janji Iman Belum Lunas</span>
                            </a>
                            <a href="{{ route('pembangunan.laporan', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pembangunan.laporan') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                                <span>Laporan Janji Iman</span>
                            </a>
                        @elseif(auth()->user()->role === 'pendeta')
                            <!-- Pendeta Sidebar Menu -->
                            <a href="{{ route('dashboard', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="{{ route('pendeta.approval.kas', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pendeta.approval.kas') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="check-square" class="w-5 h-5"></i>
                                <span>Approval Laporan Kas</span>
                            </a>
                            <a href="{{ route('pendeta.approval.janji', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pendeta.approval.janji') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                <span>Approval Laporan Janji Iman</span>
                            </a>
                            <a href="{{ route('pendeta.laporan.kas', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pendeta.laporan.kas') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                <span>Laporan Kas Mingguan</span>
                            </a>
                            <a href="{{ route('pendeta.laporan.janji', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pendeta.laporan.janji') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="folder-check" class="w-5 h-5"></i>
                                <span>Laporan Janji Iman</span>
                            </a>
                        @elseif(auth()->user()->role === 'jemaat')
                            <!-- Jemaat (Ummat) Sidebar Menu -->
                            <a href="{{ route('jemaat.transparansi', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('jemaat.transparansi') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                                <span>Transparansi Kas</span>
                            </a>
                            <a href="{{ route('jemaat.janji_imanku', ['church_slug' => request()->route('church_slug')]) }}" 
                               class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('jemaat.janji_imanku') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                                <i data-lucide="wallet" class="w-5 h-5"></i>
                                <span>Janji Imanku</span>
                            </a>
                        @endif

                        <!-- Separator & Profil (Bottom Sidebar) -->
                        <div class="my-4 border-t border-white/10"></div>

                        <a href="{{ route('profile', ['church_slug' => request()->route('church_slug')]) }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('profile') ? 'bg-black/20 text-white shadow-inner' : 'hover:bg-black/10 hover:text-white' }}">
                            <i data-lucide="user" class="w-5 h-5"></i>
                            <span>Profil</span>
                        </a>
                    </nav>
                </div>

                <!-- Logged In User Profile Info (Avatar + Logout button) -->
                <div class="p-4 border-t border-white/10 flex items-center justify-between bg-black/15">
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 flex flex-shrink-0 items-center justify-center font-bold text-white text-xs">
                            {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-xs font-semibold text-white truncate">{{ auth()->user()->nama }}</span>
                            <span class="text-[9px] font-bold text-blue-200 uppercase tracking-wide">{{ str_replace('_', ' ', auth()->user()->role) }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout', ['church_slug' => request()->route('church_slug')]) }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" title="Keluar" class="p-1.5 hover:bg-white/10 text-blue-200 hover:text-red-300 rounded-lg transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Mobile Sidebar overlay -->
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-slate-900/40 backdrop-blur-sm md:hidden"></div>

            <!-- Right Main Content Panel -->
            <div class="flex flex-col flex-1 overflow-y-auto">
                
                <!-- Top Bar Header (Matches Header inside Gambar IV.16) -->
                <header class="flex justify-between items-center h-16 bg-white border-b border-slate-200 px-6 sticky top-0 z-35">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-lg focus:outline-none mr-4 transition-colors">
                            <i data-lucide="menu" class="w-6 h-6"></i>
                        </button>
                        <h2 class="text-base md:text-lg font-bold text-slate-800 tracking-tight">Selamat Datang, {{ auth()->user()->nama }}</h2>
                    </div>

                    <!-- Search Input & Notification icon -->
                    <div class="flex items-center space-x-4">
                        <div class="relative hidden sm:block">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </div>
                            <input type="text" placeholder="Search for anything...." 
                                   class="w-48 bg-slate-50 border border-slate-200 rounded-lg pl-9 pr-4 py-1.5 text-xs text-slate-600 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 transition-all">
                        </div>
                        <button class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors relative">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-primary-600 rounded-full border border-white"></span>
                        </button>
                    </div>
                </header>

                <!-- Page Main Container -->
                <main class="flex-grow p-6">
                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="bg-white border-t border-slate-150 py-4 px-6 text-center text-xs text-slate-400">
                    &copy; {{ date('Y') }} GPdI Mahanaim Sadaperarih. SIKER Keuangan &amp; Janji Iman.
                </footer>
            </div>
        </div>
    @else
        <!-- Simple centered layout for guest routes (login) -->
        <main class="min-h-screen flex items-center justify-center bg-slate-100 px-4 w-full">
            @yield('content')
        </main>
    @endauth

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            lucide.createIcons();
        });
    </script>
    @yield('scripts')
</body>
</html>
