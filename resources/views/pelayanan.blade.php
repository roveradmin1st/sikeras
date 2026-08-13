<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelayanan - GPdI Mahanaim</title>
    
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
                            500: '#003E7E',
                            600: '#00356c',
                            700: '#002a57',
                            750: '#00254c',
                            800: '#001e45',
                            900: '#001938',
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased text-slate-800 bg-white pt-20">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center space-x-3">
                    <img src="{{ asset('images/logo-gpdi.png') }}" alt="Logo GPdI" class="h-10 w-auto">
                    <h1 class="text-2xl font-bold text-primary-600 tracking-tight">GPdI Mahanaim</h1>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('landing') }}" class="text-slate-500 hover:text-slate-800 font-medium px-1 py-2 transition-colors">Beranda</a>
                    <a href="{{ route('profil_gereja') }}" class="text-slate-500 hover:text-slate-800 font-medium px-1 py-2 transition-colors">Profil Gereja</a>
                    <a href="{{ route('pelayanan') }}" class="text-primary-600 font-semibold border-b-2 border-primary-600 px-1 py-2">Pelayanan</a>
                </div>
                <div>
                    <a href="{{ route('login', ['church_slug' => 'mahanaim']) }}" class="bg-primary-900 hover:bg-primary-800 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors shadow-sm">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="bg-primary-50 py-16 border-b border-primary-100">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-primary-900 mb-4">Pelayanan Gereja</h1>
            <p class="text-lg text-slate-500">Struktur organisasi dan bidang-bidang pelayanan di GPdI Mahanaim</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-4xl mx-auto px-4 py-16 space-y-20">
        
        <!-- Struktur Organisasi -->
        <section>
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="network" class="w-5 h-5"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Struktur Organisasi Pelayanan</h2>
            </div>
            <div class="bg-slate-50 p-6 md:p-10 rounded-2xl border border-slate-200 overflow-x-auto text-center">
                <!-- Diagram Sederhana -->
                <div class="inline-flex flex-col items-center">
                    
                    <div class="border-2 border-primary-500 bg-white rounded-lg px-6 py-3 mb-6 min-w-[200px] shadow-sm">
                        <p class="font-bold text-sm text-primary-700">GEMBALA</p>
                        <p class="text-xs text-slate-600 mt-1">Pdt. Elma Br Sihombing S.Th</p>
                    </div>

                    <div class="h-6 border-l-2 border-slate-300"></div>

                    <div class="border-2 border-slate-300 bg-white rounded-lg px-6 py-3 mb-6 min-w-[200px] shadow-sm">
                        <p class="font-bold text-sm text-slate-700">WAKIL GEMBALA</p>
                        <p class="text-xs text-slate-600 mt-1">Pdm. Rahel Lumbantoruan S.Pd</p>
                    </div>

                    <div class="h-6 border-l-2 border-slate-300"></div>
                    <div class="w-full max-w-md border-t-2 border-slate-300"></div>
                    
                    <div class="flex justify-between w-full max-w-md mt-6 gap-4">
                        <div class="border-2 border-slate-300 bg-white rounded-lg px-4 py-3 flex-1 shadow-sm">
                            <p class="font-bold text-[11px] text-slate-700">Bendahara Pembangunan I</p>
                            <p class="text-[11px] text-slate-600 mt-1">Herman Surbakti</p>
                        </div>
                        <div class="border-2 border-slate-300 bg-white rounded-lg px-4 py-3 flex-1 shadow-sm">
                            <p class="font-bold text-[11px] text-slate-700">Bendahara Pembangunan II</p>
                            <p class="text-[11px] text-slate-600 mt-1">Makmur Karo-Karo</p>
                        </div>
                    </div>

                    <div class="w-full max-w-md mt-8 flex justify-center space-x-4">
                        <div class="border border-slate-300 bg-white shadow-sm rounded-lg px-5 py-3 text-sm font-semibold text-slate-700">Ibadah Rayon</div>
                        <div class="border border-slate-300 bg-white shadow-sm rounded-lg px-5 py-3 text-sm font-semibold text-slate-700">Ibadah Pemuda</div>
                        <div class="border border-slate-300 bg-white shadow-sm rounded-lg px-5 py-3 text-sm font-semibold text-slate-700">Ibadah Anak-Anak</div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- Footer -->
    <footer class="bg-[#00142D] text-slate-300 py-16 border-t border-primary-800 mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <div>
                <h3 class="text-xl font-bold text-white mb-4">GPdI Mahanaim</h3>
                <p class="text-sm text-slate-400 max-w-sm leading-relaxed">
                    Menjangkau jiwa, membangun kehidupan, dan memuliakan Tuhan di tengah masyarakat.
                </p>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-5">Tautan Cepat</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-white transition-colors">Kontak Kami</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Lokasi</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Jadwal Ibadah</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Media Sosial</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-5">Hubungi Kami</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li>Jl. Kali Baru Barat Route, Gajah, Kec. Simpang Empat</li>
                    <li>Email: info@gpdimahanaim.org</li>
                    <li>Telp: (021) 1234-5678</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 border-t border-primary-800/50 text-xs text-center text-slate-500">
            &copy; 2024 GPdI Mahanaim. All rights reserved.
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
