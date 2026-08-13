<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Keuangan GPdI Mahanaim</title>
    
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
<body class="font-sans antialiased text-slate-800 bg-white">

    <!-- Navbar -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center space-x-3">
                    <img src="{{ asset('images/logo-gpdi.png') }}" alt="Logo GPdI" class="h-10 w-auto">
                    <h1 class="text-2xl font-bold text-primary-600 tracking-tight">GPdI Mahanaim</h1>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('landing') }}" class="text-primary-600 font-semibold border-b-2 border-primary-600 px-1 py-2">Beranda</a>
                    <a href="{{ route('profil_gereja') }}" class="text-slate-500 hover:text-slate-800 font-medium px-1 py-2 transition-colors">Profil Gereja</a>
                    <a href="{{ route('pelayanan') }}" class="text-slate-500 hover:text-slate-800 font-medium px-1 py-2 transition-colors">Pelayanan</a>
                </div>
                <div>
                    <a href="{{ route('login', ['church_slug' => 'mahanaim']) }}" class="bg-primary-900 hover:bg-primary-800 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors shadow-sm">
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/gereja_gpdi.png') }}" alt="Gereja GPdI Mahanaim" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-slate-800/50 backdrop-blur-[2px]"></div>
        </div>
        
        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-tight mb-6">
                Sistem Informasi Keuangan<br>GPdI Mahanaim
            </h1>
            <p class="text-lg md:text-xl text-slate-200 mb-10 max-w-2xl mx-auto font-light">
                Platform terintegrasi untuk pengelolaan pemasukan dan pengeluaran keuangan gereja yang transparan dan akuntabel.
            </p>
            <a href="{{ route('login', ['church_slug' => 'mahanaim']) }}" class="inline-block bg-white text-primary-700 hover:bg-slate-50 font-bold py-3.5 px-8 rounded-xl transition-all shadow-lg transform hover:-translate-y-1">
                Masuk ke Sistem
            </a>
        </div>
    </section>

    <!-- Manfaat Sistem -->
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-primary-900 mb-4">Manfaat Sistem Kami</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Sistem kami dirancang untuk memastikan pengelolaan dana gereja yang efisien, transparan, dan aman.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-primary-50 text-primary-900 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="eye" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary-900 mb-3">Transparansi Dana</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Semua transaksi dicatat dengan jelas dan dapat diakses oleh pihak yang berwenang, memastikan akuntabilitas penuh.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-primary-50 text-primary-900 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="bar-chart-2" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary-900 mb-3">Laporan Real-time</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Akses laporan keuangan terkini kapan saja dengan dashboard interaktif kami.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-primary-50 text-primary-900 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="shield-check" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold text-primary-900 mb-3">Keamanan Data</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Data keuangan Anda dilindungi dengan standar keamanan tinggi untuk mencegah akses yang tidak sah.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-[#001e45] text-center px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Mari Bergabung Bersama Kami</h2>
            <p class="text-primary-100 mb-10 text-lg font-light leading-relaxed">
                Kami rindu untuk menyambut Anda dalam keluarga besar GPdI Mahanaim.<br>Temukan tempat Anda untuk bertumbuh dan melayani bersama.
            </p>
            <a href="#" class="inline-block bg-white text-primary-900 hover:bg-slate-100 font-bold py-3 px-8 rounded-lg transition-colors">
                Hubungi Kami
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#00142D] text-slate-300 py-16 border-t border-primary-800">
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
                    <li>Jl. Contoh Alamat No. 123, Kota</li>
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
