<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas - Informatika CFI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Global Scrollbar*/
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
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
        html { scroll-behavior: smooth; }
        body { padding-top: 72px; }
        .nav-underline { position: relative; display: inline-block; }
        .nav-underline::after {
            content: ''; position: absolute; left: 0; bottom: -3px; width: 100%; height: 4px;
            background-color: #10b981; border-radius: 9999px; transform: scaleX(0);
            transform-origin: right; transition: transform 0.3s ease-in-out;
        }
        .nav-underline:hover::after { transform: scaleX(1); transform-origin: left; }
    </style>
</head>
<body class="bg-gray-700 min-h-screen flex flex-col">

    <!-- TOP NAVBAR (Fixed) -->
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

    <!-- 📦 MAIN CONTENT -->
    <main class="flex-1 pt-10 pb-16">
        <div class="max-w-5xl mx-auto px-4">
            
            <!-- Header & Filter Toggle -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <h1 class="text-3xl font-bold text-white ml-4 sm:ml-0">Daftar Tugas Publik</h1>
                
                <!-- Toggle Buttons -->
                <div class="flex bg-gray-800 p-1.5 rounded-xl border border-gray-700 shadow-inner gap-1">
                    <a href="{{ route('tasks.public', ['status' => 'active']) }}" 
                       class="px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 
                              {{ $status === 'active' ? 'bg-emerald-600 text-white shadow-md' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                        ✅ Aktif
                    </a>
                    <a href="{{ route('tasks.public', ['status' => 'expired']) }}" 
                       class="px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 
                              {{ $status === 'expired' ? 'bg-red-600 text-white shadow-md' : 'text-gray-400 hover:text-white hover:bg-gray-700' }}">
                        ⏳ Terlewat
                    </a>
                </div>
            </div>

            <!-- Task List -->
            @forelse($tasks as $task)
            <div class="bg-gray-800 rounded-xl border border-gray-700 p-5 mb-4 hover:border-gray-600 transition group">
                
                <!-- Badges: Course & Category -->
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-900/30 text-emerald-400 text-xs font-medium rounded-md border border-emerald-800/30">
                        📚 {{ $task->course_name }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 {{ $task->category === 'kelompok' ? 'bg-blue-900/30 text-blue-400 border-blue-800/30' : 'bg-purple-900/30 text-purple-400 border-purple-800/30' }} text-xs font-medium rounded-md border">
                        {{ $task->category === 'kelompok' ? '👥' : '👤' }} {{ ucfirst($task->category) }}
                    </span>
                </div>

                <!-- Title -->
                <h3 class="text-lg font-semibold text-white mb-2 group-hover:text-emerald-400 transition">
                    {{ $task->title }}
                </h3>

                <!-- Description (Read-only) -->
                <p class="text-gray-400 text-sm mb-4 line-clamp-2 leading-relaxed">
                    {!! Str::limit(strip_tags($task->description), 80) !!}
                </p>

                <!-- External Links (Read-only) -->
                @if($task->material_link || $task->submission_link)
                <div class="mt-3 flex gap-4 mb-3">
                    @if($task->material_link)
                        <a href="{{ $task->material_link }}" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 hover:underline flex items-center gap-1">
                            📖 Lihat Materi
                        </a>
                    @endif
                    @if($task->submission_link)
                        <a href="{{ $task->submission_link }}" target="_blank" class="text-xs text-emerald-400 hover:text-emerald-300 hover:underline flex items-center gap-1">
                            📤 Link Pengumpulan
                        </a>
                    @endif
                </div>
                @endif

                <!-- Footer: User+Role | Deadline | Links -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-gray-700/50 text-xs text-gray-400">
                    
                    <!-- Pembuat & Role -->
                    <div class="flex items-center gap-2">
                        <span>{{ $task->user->name ?? 'Unknown' }}</span>
                        <span class="w-1 h-1 rounded-full bg-gray-600"></span>
                        <span class="text-emerald-400 font-medium">
                            {{ config('roles.list.' . ($task->user->role ?? 'user')) ?? ucfirst($task->user->role ?? 'user') }}
                        </span>
                    </div>

                    <!-- Deadline -->
                    <span class="flex items-center gap-1.5 font-medium {{ $task->is_expired ? 'text-red-400' : 'text-emerald-400' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $task->deadline_at->format('d M Y, H:i') }}
                    </span>
                </div>
            </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-16 bg-gray-800/50 rounded-xl border border-dashed border-gray-700">
                    <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <p class="text-gray-400">Tidak ada tugas {{ $status === 'active' ? 'yang masih aktif' : 'yang terlewat deadline' }}.</p>
                    <a href="{{ route('tasks.public', ['status' => $status === 'active' ? 'expired' : 'active']) }}" class="inline-block mt-3 text-emerald-400 hover:underline text-sm">
                        Lihat {{ $status === 'active' ? 'tugas terlewat' : 'tugas aktif' }} →
                    </a>
                </div>
            @endforelse
        </div>
    </main>

    <!-- 🦶 FOOTER (Sama) -->
    <footer class="bg-gray-900 border-t border-gray-800 py-8 mt-auto">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="flex justify-center items-center gap-2 mb-4">
                <span class="text-emerald-500 font-bold text-xl">❯</span>
                <span class="text-white font-semibold">Informatika CFI</span>
            </div>
            <p class="text-gray-400 text-sm mb-4">Platform manajemen tugas & doksli untuk mahasiswa Informatika.</p>
            <div class="flex justify-center items-center gap-2 mb-6">
                <span class="text-gray-400 text-sm">Dibuat dengan</span>
                <span class="text-red-500 text-lg">❤️</span>
                <span class="text-gray-400 text-sm">oleh Engginer</span>
            </div>
            <p class="text-gray-500 text-xs">&copy; {{ date('Y') }} Informatika CFI. All rights reserved.</p>
        </div>
    </footer>

    <!-- ⚙️ SCRIPTS (Mobile Menu) -->
    <script>
        const mobileMenu = document.getElementById('mobileMenu');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const toggleMenu = () => {
            mobileMenu.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden'); 
        };

        sidebarToggle?.addEventListener('click', toggleMenu);
        sidebarClose?.addEventListener('click', toggleMenu);
        sidebarOverlay?.addEventListener('click', toggleMenu);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                mobileMenu.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    </script>
</body>
</html>