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
    <!-- Mobile Menu Content -->
    <div id="mobileMenu" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transform -translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden flex flex-col">
        <div class="p-6 border-b border-gray-700 flex items-center justify-between">
            <span class="text-lg font-bold">Menu</span>
            <button id="sidebarClose" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 flex flex-col gap-2">
            <a href="{{ route('home') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('home') ? 'bg-emerald-600' : '' }}">
                Home
            </a>
            <a href="{{ route('galeri') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('galeri') ? 'bg-emerald-600' : '' }}">
                Gallery
            </a>            
            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('login') ? 'bg-emerald-600' : '' }}">
                Login
            </a>
        </div>
    </div>

    <!-- 📦 MAIN CONTENT AREA -->
    <main class="min-h-screen bg-gray-700">
        <div class="max-w-6xl mx-auto px-4 py-10 w-full">
            
            <!-- Welcome Section -->
            <div class="text-center mb-12 mt-20">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 tracking-tight">
                    Welcome to <span class="text-emerald-500">Class C</span>
                </h1>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    Platform manajemen tugas dan doksli untuk mahasiswa Informatika.
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-sm hover:border-emerald-500/50 transition">
                    <div class="text-emerald-500 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Total Tugas Aktif</h3>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalActiveTasks ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Dari total {{ \App\Models\Task::count() ?? 0 }} tugas</p>
                </div>
                <div onClick="window.location.href='{{ route('galeri') }}'" class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-sm hover:border-emerald-500/50 transition">
                    <div class="text-blue-500 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Doksli Terkumpul</h3>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalDoksli ?? 0 }}</p>
                </div>
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-sm hover:border-emerald-500/50 transition">
                    <div class="text-purple-500 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Member</h3>
                    <p class="text-3xl font-bold text-white mt-1">32</p>
                </div>
            </div>

            <!-- Content Box -->
            <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">Aktivitas Terbaru</h2>
                    <a href="{{ route('tasks.public') }}" class="text-sm text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                @if(isset($latestTasks) && $latestTasks->count() > 0)
                    <div class="space-y-4">
                        @forelse($latestTasks as $task)
                        <div class="bg-gray-800/60 rounded-xl border border-gray-700 p-5 hover:border-emerald-500/40 hover:bg-gray-800 transition group">
                            
                            <!-- Paling Atas -->
                            <div class="flex items-center gap-2 mb-3 flex-wrap">
                                <!-- MATA KULIAH -->
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-900/30 text-emerald-400 text-xs font-medium rounded-md border border-emerald-800/30">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    {{ $task->course_name }}
                                </span>

                                <!-- KATEGORI TUGAS (Sebelah Kanan) -->
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 {{ $task->category === 'kelompok' ? 'bg-blue-900/30 text-blue-400 border-blue-800/30' : 'bg-purple-900/30 text-purple-400 border-purple-800/30' }} text-xs font-medium rounded-md border">
                                    @if($task->category === 'kelompok')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    @endif
                                    {{ ucfirst($task->category) }}
                                </span>
                            </div>

                            <!-- JUDUL TUGAS -->
                            <h4 class="text-white font-semibold text-lg mb-2 group-hover:text-emerald-400 transition-colors">
                                {{ $task->title }}
                            </h4>

                            <!-- DESKRIPSI TUGAS -->
                            <p class="text-gray-400 text-sm mb-4 line-clamp-2 leading-relaxed">
                                    {!! Str::limit(strip_tags($task->description), 80) !!}
                            </p>

                            <!-- FOOTER: Pembuat (Kiri) & Deadline (Kanan) -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-700/50">
                                
                                <!-- PEMBUAT TUGAS (Sebelah Kiri) -->
                                <span class="flex items-center gap-2 text-xs text-gray-400">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    
                                    <span>{{ $task->user->name ?? 'Unknown' }}</span>
                                    
                                    <!-- Separator -->
                                    <span class="w-1 h-1 rounded-full bg-gray-600"></span>
                                    
                                    <!-- Role Name (Kanan) -->
                                    <span class="text-emerald-400/90 font-medium">
                                        {{ config('roles.list.' . ($task->user->role ?? 'user')) ?? ucfirst($task->user->role ?? 'user') }}
                                    </span>
                                </span>

                                <!--  DEADLINE (Paling Kanan) -->
                                <span class="flex items-center gap-2 text-xs font-medium {{ $task->is_expired ? 'text-red-400' : 'text-emerald-400' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $task->deadline_at->format('l, d M H:i') }}
                                </span>
                            </div>
                        </div>
                        @empty
                            <!-- Empty State -->
                            <div class="text-center py-12 bg-gray-800/30 rounded-xl border border-dashed border-gray-700">
                                <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                <p class="text-gray-500 text-sm">Belum ada tugas yang dimasukkan.</p>
                            </div>
                        @endforelse
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-10">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <p class="text-gray-400 text-sm">Belum ada tugas yang dimasukkan.</p>
                        @auth
                            <a href="{{ route('tasks.create') }}" class="inline-block mt-3 text-emerald-400 hover:text-emerald-300 text-sm font-medium transition">
                                + Tambah tugas pertama →
                            </a>
                        @endauth
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

        document.addEventListener('DOMContentLoaded', function() {
            const targetEl = document.querySelector('.bg-gray-800 .text-3xl'); // Sesuaikan selector
            if (targetEl) {
                const finalValue = {{ $totalActiveTasks ?? 0 }};
                let current = 0;
                const increment = Math.ceil(finalValue / 20);
                
                const animate = () => {
                    current += increment;
                    if (current >= finalValue) {
                        targetEl.textContent = finalValue;
                    } else {
                        targetEl.textContent = current;
                        requestAnimationFrame(animate);
                    }
                };
                
                if (finalValue > 0) animate();
            }
        });
    </script>
</body>
</html>