<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - Class C</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Smooth scroll untuk anchor links */
        html { scroll-behavior: smooth; }
        /* Mencegah konten tertutup navbar fixed */
        body { padding-top: 72px; } 

        /* Animasi Underline Sliding */
        @keyframes underline-slide {
            from { transform: scaleX(0); transform-origin: left; }
            to { transform: scaleX(1); transform-origin: left; }
        }
        
        .nav-underline {
            position: relative;
            display: inline-block;
        }
        
        .nav-underline::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -3px; /* Sesuaikan jarak underline dari teks */
            width: 100%;
            height: 4px; /* Ketebalan underline */
            border-radius: 9999px;
            background-color: #10b981; /* Warna emerald-500 */
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease-in-out;
        }
        
        /* Hover effect: underline slide dari kiri */
        .nav-underline:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }
        
        /* Optional: animasi marquee tetap dipertahankan */
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 25s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="bg-gray-700">

    <!-- 🔝 TOP NAVBAR (Fixed) -->
    <nav class="fixed top-0 left-0 right-0 h-16 bg-gray-800 text-white shadow-lg z-50 flex items-center justify-between px-4 md:px-20 transition-all duration-300">
        <div class="flex items-center gap-4">
            <button id="sidebarToggle" class="md:hidden text-gray-300 hover:text-white focus:outline-none p-1">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-wide flex items-center gap-2">
                <span class="text-emerald-500">❯</span> Informatika CFI
            </a>
        </div>
        <div class="hidden md:flex items-center gap-2">
            <a href="{{ route('home') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">Home</a>
            <a href="{{ route('galeri') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('galeri') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">Gallery</a>
            <a href="{{ route('about') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">About</a>
        </div>
    </nav>

    <!-- 📱 MOBILE MENU -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>
    <div id="mobileMenu" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transform -translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden flex flex-col">
        <div class="p-6 border-b border-gray-700 flex items-center justify-between">
            <span class="text-lg font-bold">Menu</span>
            <button id="sidebarClose" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 flex flex-col gap-2">
            <a href="{{ route('home') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('home') ? 'bg-emerald-600' : '' }}">Home</a>
            <a href="{{ route('galeri') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('galeri') ? 'bg-emerald-600' : '' }}">Gallery</a>
            <a href="{{ route('about') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('about') ? 'bg-emerald-600' : '' }}">About</a>
        </div>
    </div>

    <!-- 📦 MAIN CONTENT AREA -->
    <main class="min-h-screen bg-gray-700">
        <div class="mx-auto py-10 w-full">
            
            <!-- Main Content -->
            <div class="flex-1 flex flex-col">
                <div id="sidebarOverlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden md:hidden"></div>

                <div class="max-w-2xl mx-auto px-4 py-10 w-full">
                    <div class="text-center text-white">
                        <h3 class="text-4xl font-bold mb-7 mt-7">Upload Doksli Temanmu 🤫</h3>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <!-- Form Upload -->
                        <form action="{{ route('galeri.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Judul -->
                            <div class="mb-5">
                                <label class="block text-gray-700 font-medium mb-2">Judul Gambar</label>
                                <input type="text" name="title" value="{{ old('title') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                                    placeholder="Contoh: Dava lagi turu"
                                    required>
                                @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Upload Gambar -->
                            <div class="mb-6">
                                <label class="block text-gray-700 font-medium mb-2">Pilih Gambar</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-emerald-500 transition cursor-pointer" id="dropZone">
                                    <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" required>
                                    <label for="imageInput" class="cursor-pointer">
                                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Klik atau import gambar di sini</p>
                                        <p class="text-xs text-gray-400">PNG, JPG, GIF • Maksimal 2MB</p>
                                    </label>
                                </div>
                                <!-- Preview -->
                                <div id="previewContainer" class="mt-4 hidden">
                                    <img id="imagePreview" class="w-32 h-32 object-cover rounded-lg border border-gray-200 mx-auto">
                                </div>
                                @error('image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tombol -->
                            <div class="flex gap-3">
                                <a href="{{ route('galeri') }}"
                                class="flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition text-center">
                                    Batal
                                </a>
                                <button type="submit"
                                        class="flex-1 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                                    Upload Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Container Running Text -->
            <div class="relative overflow-hidden bg-gray-800 border-y border-gray-700 py-3 mt-10">
                <div class="flex whitespace-nowrap animate-marquee">
                    
                    <!-- 🔹 SET 1 (Teks Asli) -->
                    <span class="text-gray-300 text-lg md:text-base px-4 flex items-center gap-2">
                        Hunting Doksli Asli 😹
                    </span>
                    <span class="text-emerald-400 text-lg md:text-base px-4 flex items-center gap-2">
                        Upload yang lucu-lucu aja 😭
                    </span>
                    <span class="text-gray-300 text-lg md:text-base px-4 flex items-center gap-2">
                        Edit dulu baru upload, jangan langsung upload yang asli 😎
                    </span>
                    <span class="text-emerald-400 text-lg md:text-base px-4 flex items-center gap-2">
                        Privasi Kalian Aman 🔒
                    </span>

                    <!-- SET 3 (Duplikat Persis untuk Loop Seamless) -->
                    <span class="text-gray-300 text-lg md:text-base px-4 flex items-center gap-2">
                        Hunting Doksli Asli 😹
                    </span>
                    <span class="text-emerald-400 text-lg md:text-base px-4 flex items-center gap-2">
                        Upload yang lucu-lucu aja 😭
                    </span>
                    <span class="text-gray-300 text-lg md:text-base px-4 flex items-center gap-2">
                        Edit dulu baru upload, jangan langsung upload yang asli 😎
                    </span>
                    <span class="text-emerald-400 text-lg md:text-base px-4 flex items-center gap-2">
                        Privasi Kalian Aman 🔒
                    </span>
                    <!-- SET 2 (Duplikat Persis untuk Loop Seamless) -->
                    <span class="text-gray-300 text-lg md:text-base px-4 flex items-center gap-2">
                        Hunting Doksli Asli 😹
                    </span>
                    <span class="text-emerald-400 text-lg md:text-base px-4 flex items-center gap-2">
                        Upload yang lucu-lucu aja 😭
                    </span>
                    <span class="text-gray-300 text-lg md:text-base px-4 flex items-center gap-2">
                        Edit dulu baru upload, jangan langsung upload yang asli 😎
                    </span>
                    <span class="text-emerald-400 text-lg md:text-base px-4 flex items-center gap-2">
                        Privasi Kalian Aman 🔒
                    </span>
                </div>
            </div>
        </div>
    </main>
    <!--  FOOTER -->
    <footer class="bg-gray-900 border-t border-gray-800 py-8 mt-auto">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="flex justify-center items-center gap-2 mb-4">
                <span class="text-emerald-500 font-bold text-xl">❯</span>
                <span class="text-white font-semibold">Informatika CFI</span>
            </div>
            <p class="text-gray-400 text-sm mb-4">
                Platform manajemen tugas & doksli untuk mahasiswa Informatika.
            </p>
            <div class="flex justify-center item-center gap-2 mb-6">
                <a class="text-gray-400 hover:text-emerald-400 text-sm transition">Dibuat dengan</a>
                <a class="text-red-500 text-lg leading-none hover:scale-110 transition flex items-center">❤️</a>
                <a class="text-gray-400 hover:text-emerald-400 text-sm transition">
                    oleh Engginer</a>
            </div>
            <p class="text-gray-500 text-xs">
                &copy; {{ date('Y') }} Informatika CFI. All rights reserved.
            </p>
        </div>
    </footer>
    <!-- ⚙️ SCRIPTS -->
    <script>
        // Mobile Menu Toggle Logic
        const mobileMenu = document.getElementById('mobileMenu');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const toggleMenu = () => {
            mobileMenu.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
            // Mencegah scroll body saat menu mobile terbuka
            document.body.classList.toggle('overflow-hidden'); 
        };

        sidebarToggle?.addEventListener('click', toggleMenu);
        sidebarClose?.addEventListener('click', toggleMenu);
        sidebarOverlay?.addEventListener('click', toggleMenu);

        // Close menu when resizing to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                mobileMenu.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });

        // SweetAlert for success message (Global)
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: '#1f2937',
                color: '#fff'
            });
        @endif
    </script>
        <!-- CSS Animation -->
    <style>
        @keyframes marquee {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 25s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused; /* Berhenti saat di-hover */
        }
    </style>
</body>
</html>