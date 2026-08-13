<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Gereja - GPdI Mahanaim</title>
    
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
                    <a href="{{ route('profil_gereja') }}" class="text-primary-600 font-semibold border-b-2 border-primary-600 px-1 py-2">Profil Gereja</a>
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

    <!-- Page Header -->
    <div class="bg-primary-50 py-16 border-b border-primary-100">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-primary-900 mb-4">Profil & Sejarah Gereja</h1>
            <p class="text-lg text-slate-500">Mengenal lebih dekat perjalanan pelayanan dan visi misi GPdI Mahanaim</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="max-w-4xl mx-auto px-4 py-16 space-y-20">
        
        <!-- Sejarah -->
        <section>
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Sejarah Gereja GPdI Mahanaim</h2>
            </div>
            
            <div class="prose prose-slate max-w-none text-slate-600 space-y-4 leading-relaxed text-justify">
                <p>
                    Gereja Pantekosta di Indonesia (GPdI) berasal dari gerakan pentakostal yang muncul di Amerika Serikat pada awal abad ke-20, khususnya dari kebangunan rohani di Azusa Street tahun 1906. Gerakan ini masuk ke Indonesia pada tahun 1921, dibawa oleh dua misionaris Belanda, yaitu Cornelius Groesbeek dan Dirk van Klaveren. Pelayanan pertama dimulai di Bali, lalu berkembang ke Surabaya, Cepu, dan Batavia (Jakarta). Pada tahun 1923 dibentuk organisasi resmi bernama <em>pinkster Gemeente</em> di Hindia Belanda. Setelah beberapa kali perubahan nama, pada tahun 1942 resmi menggunakan nama Gereja Pantekosta di Indonesia (GPdI).
                </p>
                <p>
                    Pelayanan pekerjaan Tuhan GPdI Mahanaim di desa Sadaperarih dirintis oleh ibu <strong>Pdt. Maria Br Ginting</strong>. Setelah beberapa waktu maka pelayanan diteruskan oleh bapak Pdt. Barus dan keluarga. Namun tidak lama kemudian bapak Pdt. Barus dan keluarga pindah ke desa Bunuraya dan membuka pelayanan disana, dan jiwa-jiwa yang dilayanai bertambah. Oleh sebab itu pelayanan di desa Sadaperarih dilanjutkan oleh bapak Pdt. Sembiring dan Pdt. Salam Br Tarigan. Seiring berjalannya waktu dan dikarenakan belum semua masyarakat mengenal Tuhan maka sempat terjadi gesekan dengan tetangga pada waktu itu.
                </p>
                <p>
                    Pada tahun 1986 <strong>Pdt. Elma Br Sihombing</strong> ada di desa Sadaperarih ini untuk meneruskan pelayanan pekerjaan Tuhan sampai sekarang. Di dalamnya banyak proses yang terjadi. Pada waktu itu ada satu keluarga yaitu Bapak Bantal Surbakti yang memberikan tanahnya dengan ukuran 20x30 m untuk dibeli menjadi pertapakan gereja. Ada juga keluarga Reken Sembiring dari Jakarta menyerahkan satu pertapakan dan dijual seharga Rp. 800.000,- dan hasil penjualan tapak dipakai untuk pembangunan gereja awal. Semua jemaat yang ada juga turut ambil bagian menurut kemampuan masing-masing berbentuk materi dan juga doa-doa mereka.
                </p>
                <p>
                    Gereja pertama dibangun dengan ukuran 8x12 m. Ada jemaat yang memberi bantuan berupa kayu untuk broti. Juga ada keluarga dari Medan yaitu Bp. Ipel Purba yang turut ambil bagian sebagai donatur. Ada juga membantu dalam gotong royong dan seluruh jemaat sangat antusias. Seiring berjalan waktu jemaat pun mulai bertambah, maka pembangunan dilakukan lagi karena tempat ibadah tidak memadai lagi. Gereja direnovasi menjadi 10x25 m dengan pastori 9x10 m. Pelayanan gereja masih berjalan sampai dengan saat ini.
                </p>
            </div>
        </section>

        <!-- Visi Misi -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-primary-50 rounded-2xl p-8 border border-primary-100">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-white text-primary-600 rounded-lg shadow-sm flex items-center justify-center">
                        <i data-lucide="eye" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-bold text-primary-900">Visi</h2>
                </div>
                <p class="text-slate-600 leading-relaxed italic">
                    "Menjadi gereja yang bersatu dengan didasari kasih, kerendahan hati dan kepentingan bersama untuk mencapai sukacita dalam persekutuan serta meneladani Kristus."
                </p>
            </div>
            
            <div class="bg-primary-50 rounded-2xl p-8 border border-primary-100">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-white text-primary-600 rounded-lg shadow-sm flex items-center justify-center">
                        <i data-lucide="target" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-xl font-bold text-primary-900">Misi</h2>
                </div>
                <p class="text-slate-600 leading-relaxed font-medium">
                    Bersekutu, Bertumbuh, Berbuah Melayani dengan hati, dari Tuhan dan untuk Tuhan bagi Kemuliaan Tuhan menuju kepada Gereja yang sempurna.
                </p>
            </div>
        </section>



        <!-- Lokasi & Peta -->
        <section>
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Lokasi Gereja</h2>
            </div>
            <div class="bg-slate-50 p-2 rounded-2xl border border-slate-200">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15933.159368560124!2d98.4907106!3d3.149206!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30310065e10bd9b1%3A0xb3cf565ecbe86e35!2sGPDI%20MAHANAIM%20SADAPERARIH!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                    width="100%" 
                    height="400" 
                    style="border:0; border-radius: 12px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <div class="p-4 text-center">
                    <p class="text-sm font-semibold text-slate-700">GPdI Mahanaim Sadaperarih</p>
                    <p class="text-xs text-slate-500 mt-1">Jl. Kali Baru Barat Route, Gajah, Kec. Simpang Empat, Kabupaten Karo, Sumatera Utara 22153</p>
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
