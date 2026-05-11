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
        <div class="mx-auto px-4 py-10 w-full">
            
            <!-- Welcome Section -->
            <div class="px-8">
                <!-- Header Section -->
                    <a href="{{ route('galeri.create') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 mb-4 rounded-lg
                            bg-emerald-600 text-white text-sm font-medium
                            hover:bg-emerald-800 transition-all duration-200 shadow-sm hover:shadow-md
                            active:scale-95">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Upload Gambar
                    </a>
                <!-- Content Box -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-lg flex items-center gap-2 animate-fade-in">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Grid Gallery -->
                @if($galleries->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-5">
                        @foreach($galleries as $item)
                            <div class="group bg-white rounded-xl overflow-hidden border border-gray-100 
                                        hover:border-gray-200 hover:shadow-xl transition-all duration-300
                                        hover:-translate-y-1">
                                <!-- Gambar -->
                                <div class="aspect-square overflow-hidden bg-gray-50 relative">
                                    <img src="{{ asset('storage/' . $item->image) }}" 
                                        alt="{{ $item->title }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    
                                    <!-- Overlay saat hover -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                                </div>
                                
                                <!-- Info -->
                                <div class="p-4">
                                    <h4 class="font-medium text-gray-800 truncate text-sm">{{ $item->title }}</h4>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $item->created_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada gambar</h3>
                        <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">Mulai koleksi Anda dengan mengupload gambar pertama</p>
                        <a href="{{ route('gallery.createdoksli') }}" 
                        class="inline-flex items-center text-gray-900 text-sm font-medium hover:text-gray-700 transition-colors">
                            Upload gambar sekarang
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                @endif                
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
</body>
</html>