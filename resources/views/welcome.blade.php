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
        /* Loading Screen Styles - Smooth Transition */
        #loadingScreen {
            opacity: 1;
            visibility: visible;
            transition: opacity 0.6s ease-out, visibility 0.6s ease-out;
        }

        #loadingScreen.fade-out {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        /* Progress bar animation */
        @keyframes progress {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        #progressBar {
            animation: progress 2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        /* Main content - start hidden */
        main {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }

        /* Main content - fade in */
        main.ready {
            opacity: 1;
        }

        /* Base animation class */
        .animate-slide-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        /* Trigger animation */
        main.ready .animate-slide-up {
            opacity: 1;
            transform: translateY(0);
        }
        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.2s; }
        .delay-3 { transition-delay: 0.3s; }
        .delay-4 { transition-delay: 0.4s; }
        .delay-5 { transition-delay: 0.5s; }
        .delay-6 { transition-delay: 0.6s; }

        @keyframes logoBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
        }
        #loadingScreen .text-8xl {
            animation: logoBounce 1s infinite;
        }

        /* Global Scrollbar*/
        ::-webkit-scrollbar {
            width: 0;
            height: 0;
        }
        ::-webkit-scrollbar-track {
            background: rgba(31, 41, 55, 0.4); /* Match bg-gray-800/900 */
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.8); /* gray-600 + opacity */
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 114, 128, 1); /* gray-500 */
        }

        /*  Firefox Support (Optional tapi disarankan) */
        html {
            scrollbar-width: thin;
            scrollbar-color: rgba(75, 85, 99, 0.8) rgba(31, 41, 55, 0.4);
        }

        /* Smooth scroll & navbar padding (tetap pertahankan) */
        html { scroll-behavior: smooth; }
        body { padding-top: 72px; }

        /* Animasi Underline (tetap pertahankan) */
        .nav-underline { position: relative; display: inline-block; }
        .nav-underline::after {
            content: ''; position: absolute; left: 0; bottom: -3px; width: 100%; height: 4px;
            background-color: #10b981; border-radius: 9999px; transform: scaleX(0);
            transform-origin: right; transition: transform 0.3s ease-in-out;
        }
        .nav-underline:hover::after { transform: scaleX(1); transform-origin: left; }
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
        /* Custom scrollbar untuk modal */
        #taskModal ::-webkit-scrollbar {
            width: 6px;
        }

        #taskModal ::-webkit-scrollbar-track {
            background: rgba(55, 65, 81, 0.3);
            border-radius: 3px;
        }

        #taskModal ::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.8);
            border-radius: 3px;
        }

        #taskModal ::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 114, 128, 1);
        }

        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden;
        }

        /* iOS smooth scrolling */
        #taskModal .overflow-y-auto {
            -webkit-overflow-scrolling: touch;
        }
    </style>
    
</head>
<body class="bg-gray-800">

    <!-- TOP NAVBAR (Fixed) -->
    <nav class="fixed top-0 left-0 right-0 h-16 bg-gray-800/80 backdrop-blur-md text-white shadow-lg z-50 flex items-center justify-between px-4 md:px-20 transition-all duration-300 border-b border-gray-700/50">
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
            <a href="{{ route('tasks.public') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('tasks.public') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">Task</a>
            <a href="{{ route('galeri') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('galeri') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">Gallery</a>
            <a href="{{ route('about') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">About</a>
        </div>
    </nav>

    <!-- MOBILE MENU -->
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
            <a href="{{ route('tasks.public') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('tasks.public') ? 'bg-emerald-600' : '' }}">Task</a>
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
            <a href="{{ route('tasks.public') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('tasks.public') ? 'bg-emerald-600' : '' }}">
                Task
            </a>
            <a href="{{ route('galeri') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('galeri') ? 'bg-emerald-600' : '' }}">
                Gallery
            </a>            
            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('login') ? 'bg-emerald-600' : '' }}">
                Login
            </a>
        </div>
    </div>

    <!-- LOADING SCREEN -->
    <div id="loadingScreen" class="fixed inset-0 z-[100] bg-gray-900 flex flex-col items-center justify-center">
        <!-- Logo dengan animasi -->
        <div class="relative mb-6">
            <span class="text-emerald-500 text-8xl font-bold block animate-pulse">❯</span>
        </div>
        
        <!-- Loading text -->
        <p class="text-gray-400 text-sm">Informatika CFI</p>
        
        <!-- Progress bar -->
        <div class="w-64 h-1 bg-gray-800 rounded-full mt-8 overflow-hidden">
            <div id="progressBar" class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full" style="width: 0%"></div>
        </div>
    </div>

    <!-- MAIN CONTENT AREA -->
    <main class="min-h-screen bg-gray-800">
        <div class="max-w-6xl mx-auto px-4 py-10 w-full">
            
            <!-- Welcome Section (Delay 1) -->
            <div class="text-center mb-12 mt-20 animate-slide-up delay-1">
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 tracking-tight">
                    Welcome to <span class="text-emerald-500">Class C</span>
                </h1>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    Platform manajemen tugas dan doksli untuk mahasiswa Informatika.
                </p>
            </div>

            <!-- Buttons (Delay 2) -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10 animate-slide-up delay-2">
                <a href="{{ route('tasks.public') }}" 
                class="group flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-emerald-500/20 hover:-translate-y-0.5">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Lihat Daftar Tugas
                </a>
                
                <a href="{{ route('galeri') }}" 
                class="group flex items-center justify-center gap-2 px-6 py-3 bg-gray-700/60 hover:bg-gray-600 text-white font-medium rounded-xl transition-all duration-200 border border-gray-600 hover:border-gray-500 shadow-lg hover:-translate-y-0.5">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Buka Gallery Doksli
                </a>
            </div>

            <!-- Stats Cards (Delay 3) -->
            <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 animate-slide-up delay-3">
                <div onClick="window.location.href='{{ route('tasks.public') }}'" class="bg-gray-700/60 p-6 rounded-2xl border border-gray-600 shadow-sm hover:border-emerald-500/50 transition">
                    <div class="text-emerald-500 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Total Tugas Aktif</h3>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalActiveTasks ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">Dari total {{ \App\Models\Task::count() ?? 0 }} tugas</p>
                </div>
                <div onClick="window.location.href='{{ route('galeri') }}'" class="bg-gray-700/60 p-6 rounded-2xl border border-gray-600 shadow-sm hover:border-emerald-500/50 transition">
                    <div class="text-blue-500 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Doksli Terkumpul</h3>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalDoksli ?? 0 }}</p>
                </div>
                <div class="bg-gray-700/60 p-6 rounded-2xl border border-gray-600 shadow-sm hover:border-emerald-500/50 transition">
                    <div class="text-purple-500 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-white font-semibold text-lg">Member</h3>
                    <p class="text-3xl font-bold text-white mt-1">32</p>
                </div>
            </div>

            <!-- Content Box (Delay 4) -->
            <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-600 p-6 animate-slide-up delay-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-xl font-bold text-white">Aktivitas Terbaru</h2>
                    <a href="{{ route('tasks.public') }}" class="text-sm text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- Task list items dengan staggered animation juga -->
                @if(isset($latestTasks) && $latestTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($latestTasks as $index => $task)
                        <div onclick="openTaskModal({{ $task->id }})" 
                            class="bg-gray-700/60 rounded-xl border border-gray-600 p-5 hover:border-emerald-500/40 hover:bg-gray-800 group cursor-pointer animate-slide-up"
                            style="transition-delay: {{ 0.5 + ($index * 0.1) }}s">
                            
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
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-10 animate-slide-up delay-5">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <p class="text-gray-400 text-sm">Belum ada tugas yang dimasukkan.</p>
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
    <!-- TASK DETAIL MODAL -->
    <div id="taskModal" class="fixed inset-0 z-[70] hidden flex items-end sm:items-center justify-center">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/70 backdrop-blur-md opacity-0 transition-opacity duration-300" onclick="closeTaskModal()"></div>

        <!-- Modal Content -->
        <div class="relative w-full sm:max-w-2xl mx-0 sm:mx-4 bg-gray-800/60 backdrop-blur-md sm:rounded-2xl rounded-t-2xl border-0 sm:border border-gray-700 shadow-2xl flex flex-col overflow-hidden max-h-[90vh] sm:max-h-[85vh] opacity-0 translate-y-full sm:translate-y-4 sm:scale-95 transition-all duration-300 ease-out will-change-transform,opacity">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-700 bg-gray-800 flex-shrink-0">
                <h3 class="text-lg font-bold text-white">Detail Tugas</h3>
                <button onclick="closeTaskModal()" class="text-gray-400 hover:text-white transition p-2 hover:bg-gray-700 rounded-lg -mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Body (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4">
                <!-- Status & Course -->
                <div class="flex flex-wrap gap-2">
                    <span id="modalStatus" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border"></span>
                    <span id="modalCourse" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-900/30 text-emerald-400 text-sm font-medium rounded-lg border border-emerald-800/30"></span>
                </div>

                <!-- Title -->
                <h2 id="modalTitle" class="text-xl font-bold text-white leading-tight"></h2>

                <!-- Category -->
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 text-sm">Kategori:</span>
                    <span id="modalCategory" class="px-2.5 py-1 rounded-md text-xs font-medium border"></span>
                </div>

                <!-- Description -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        Deskripsi Tugas
                    </h4>
                    <div id="modalDescription" class="bg-gray-700/50 rounded-lg p-4 text-gray-300 text-sm leading-relaxed border border-gray-600 max-h-48 overflow-y-auto"></div>
                </div>

                <!-- Timeline -->
                <div class="space-y-3">
                    <div class="bg-gray-700/30 rounded-lg p-3 border border-gray-600">
                        <div class="flex items-center gap-2 text-gray-400 text-xs mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Mulai
                        </div>
                        <p id="modalStarts" class="text-white text-sm font-medium"></p>
                    </div>
                    <div class="bg-gray-700/30 rounded-lg p-3 border border-gray-600">
                        <div class="flex items-center gap-2 text-orange-300 text-xs mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="orange" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Deadline
                        </div>
                        <p id="modalDeadline" class="text-white text-sm font-medium"></p>
                    </div>
                </div>

                <!-- Creator Info -->
                <div class="flex items-center gap-3 p-3 bg-gray-700/30 rounded-lg border border-gray-600">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        <span id="modalCreatorInitial"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-medium truncate" id="modalCreator"></p>
                        <p class="text-gray-400 text-xs truncate" id="modalRole"></p>
                    </div>
                </div>

                <!-- Links -->
                <div id="modalLinks" class="space-y-2">
                    <h4 class="text-sm font-semibold text-gray-300 mb-2">Link Terkait</h4>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-700 bg-gray-800 p-4 flex-shrink-0">
                <button onclick="closeTaskModal()" class="w-full px-5 py-3 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
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
        // 🔹 Task Modal Functions
        const tasksData = @json($latestTasks);

        function openTaskModal(taskId) {
            const task = tasksData.find(t => t.id === taskId);
            if (!task) return;

            // 🔹 Populate Data (sama seperti sebelumnya)
            document.getElementById('modalTitle').textContent = task.title;
            document.getElementById('modalDescription').innerHTML = task.description.replace(/\n/g, '<br>');
            document.getElementById('modalCourse').innerHTML = `📚 ${task.course_name}`;
            document.getElementById('modalStarts').textContent = new Date(task.starts_at).toLocaleString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            document.getElementById('modalDeadline').textContent = new Date(task.deadline_at).toLocaleString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            document.getElementById('modalCreator').textContent = task.user.name || 'Unknown';
            document.getElementById('modalCreatorInitial').textContent = (task.user.name || 'U').charAt(0).toUpperCase();
            document.getElementById('modalRole').textContent = window.configRoles?.[task.user.role] || task.user.role.charAt(0).toUpperCase() + task.user.role.slice(1);

            const isExpired = new Date(task.deadline_at) < new Date();
            const statusEl = document.getElementById('modalStatus');
            statusEl.className = `inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border ${isExpired ? 'bg-red-900/30 text-red-400 border-red-800/30' : 'bg-emerald-900/30 text-emerald-400 border-emerald-800/30'}`;
            statusEl.innerHTML = isExpired ? '⏳ Deadline Lewat' : '✅ Aktif';

            const categoryEl = document.getElementById('modalCategory');
            categoryEl.className = `px-2.5 py-1 rounded-md text-xs font-medium border ${task.category === 'kelompok' ? 'bg-blue-900/30 text-blue-400 border-blue-800/30' : 'bg-purple-900/30 text-purple-400 border-purple-800/30'}`;
            categoryEl.innerHTML = task.category === 'kelompok' ? '👥 Kelompok' : '👤 Individu';

            const linksContainer = document.getElementById('modalLinks');
            linksContainer.innerHTML = '<h4 class="text-sm font-semibold text-gray-300 mb-2">Link Terkait</h4>';
            if (task.material_link) linksContainer.innerHTML += `<a href="${task.material_link}" target="_blank" class="flex items-center gap-2 text-blue-400 hover:text-blue-300 text-sm transition p-2 rounded-lg hover:bg-blue-900/20"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>Materi Pembelajaran</a>`;
            if (task.submission_link) linksContainer.innerHTML += `<a href="${task.submission_link}" target="_blank" class="flex items-center gap-2 text-emerald-400 hover:text-emerald-300 text-sm transition p-2 rounded-lg hover:bg-emerald-900/20"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>Link Pengumpulan</a>`;
            if (!task.material_link && !task.submission_link) linksContainer.innerHTML += '<p class="text-gray-500 text-sm">Tidak ada link tersedia</p>';

            // 🔹 Trigger Animation
            const modal = document.getElementById('taskModal');
            const backdrop = modal.querySelector('.absolute.inset-0');
            const content = modal.querySelector('.relative.w-full');

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Kunci scroll body

            // Force reflow agar transisi berjalan
            void modal.offsetWidth;

            backdrop.classList.remove('opacity-0');
            content.classList.remove('opacity-0', 'translate-y-full', 'sm:translate-y-4', 'sm:scale-95');
            content.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }

        function closeTaskModal() {
            const modal = document.getElementById('taskModal');
            const backdrop = modal.querySelector('.absolute.inset-0');
            const content = modal.querySelector('.relative.w-full');

            // Reverse animation
            backdrop.classList.add('opacity-0');
            content.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            content.classList.add('opacity-0', 'translate-y-full', 'sm:translate-y-4', 'sm:scale-95');

            // Tunggu animasi selesai baru hide element
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeTaskModal();
        });

        // Pass role config to JS
        window.configRoles = @json(config('roles.list', []));

        // Loading Screen Handler dengan Staggered Animation
        document.addEventListener('DOMContentLoaded', function() {
            const loadingScreen = document.getElementById('loadingScreen');
            const mainContent = document.querySelector('main');
            
            // Hide loading screen after 2 seconds
            setTimeout(() => {
                // Fade out loading screen
                if (loadingScreen) {
                    loadingScreen.classList.add('fade-out');
                }
                
                // Show main content
                setTimeout(() => {
                    if (mainContent) {
                        mainContent.classList.add('ready');
                    }
                    // Enable scroll
                    document.body.style.overflow = '';
                }, 300);
                
                // Remove loading screen after transition
                setTimeout(() => {
                    if (loadingScreen) {
                        loadingScreen.style.display = 'none';
                    }
                }, 600);
                
            }, 2000);
        });

        // Prevent scroll while loading
        document.body.style.overflow = 'hidden';
    </script>
</body>
</html>