<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Informatika CFI</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Global Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(31,41,55,0.4); border-radius: 3px; }
        ::-webkit-scrollbar-thumb { background: rgba(75,85,99,0.8); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(107,114,128,1); }
        html { scrollbar-width: thin; scrollbar-color: rgba(75,85,99,0.8) rgba(31,41,55,0.4); }
        
        html { scroll-behavior: smooth; }
        
        /* Sidebar Transitions */
        .sidebar { transition: transform 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        
        /* Content padding for sidebar */
        .content-with-sidebar { 
            margin-left: 0;
            padding-top: 0;
            transition: margin-left 0.3s ease-in-out, padding-top 0.3s ease-in-out;
        }
        
        /* Desktop: sidebar margin */
        @media (min-width: 768px) {
            .content-with-sidebar { 
                margin-left: 16rem;
            }
        }
        
        /* Mobile: navbar padding */
        @media (max-width: 767px) {
            .content-with-sidebar { 
                padding-top: 4rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-800 text-white">

<!-- 📱 MOBILE TOP NAVBAR (Hanya muncul di mobile) -->
<nav class="md:hidden fixed top-0 left-0 right-0 h-16 bg-gray-900/95 backdrop-blur-md border-b border-gray-700 z-40 flex items-center px-4">
    
    <!-- Kiri: Hamburger + Logo (Bersebelahan) -->
    <div class="flex items-center gap-3">
        <!-- Hamburger Button -->
        <button id="sidebarToggle" class="p-2 text-gray-300 hover:text-white focus:outline-none -ml-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <!-- Logo/Title -->
        <a href="{{ route('home') }}" class="flex items-center gap-1.5 text-lg font-bold text-white">
            <span class="text-emerald-500">❯</span>
            <span class="truncate">Informatika CFI</span>
        </a>
    </div>
    
    <!-- Kanan: Kosong (atau bisa diisi notifikasi/user menu nanti) -->
    <div class="flex-1"></div>
</nav>

    <!-- 📦 SIDEBAR (Fixed Left) -->
    <aside id="sidebar" class="sidebar sidebar-hidden fixed inset-y-0 left-0 w-64 bg-gray-900 border-r border-gray-700 z-50 flex flex-col">
        
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-gray-700">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold">
                <span class="text-emerald-500 text-2xl">❯</span>
                <span class="truncate">Informatika CFI</span>
            </a>
        </div>
        
        <!-- User Profile -->
        <a href="{{ route('profile.edit') }}" class="block hover:bg-gray-800/80 transition">
            <div class="p-4 border-b border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
        </a>
        
        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @php
                $currentRoute = request()->route()->getName();
            @endphp
            
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ $currentRoute === 'dashboard' ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            
            <!-- Tasks -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                <a href="{{ route('tasks.index') }}" 
                   class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ str_contains($currentRoute, 'tasks.') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Semua Tugas
                </a>
            @else
                <a href="{{ route('tasks.create') }}" 
                   class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ $currentRoute === 'tasks.create' ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Tugas
                </a>
            @endif

            <!-- Gallery -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                <a href="{{ route('galeri') }}" 
                class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ $currentRoute === 'galeri' ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Gallery
                </a>
            @endif
            
            <!-- Admin Only -->
            @if(auth()->user()->role === 'admin')
                <div class="pt-4 mt-4 border-t border-gray-700">
                    <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Admin</p>
                    <a href="{{ route('admin.users.index') }}" 
                       class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ str_contains($currentRoute, 'admin.users') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Kelola User
                    </a>
                </div>
            @endif
        </nav>
        
        <!-- Bottom Actions -->
        <div class="p-4 border-t border-gray-700 space-y-2">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Home
            </a>
            
            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:text-red-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Mobile Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden backdrop-blur-sm"></div>

    <!-- 📦 MAIN CONTENT -->
    <main class="content-with-sidebar min-h-screen">
        @yield('content')
    </main>

    <!-- ⚙️ SCRIPTS (SINGLE, CLEAN) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Toggle function
            const toggleSidebar = () => {
                sidebar.classList.toggle('sidebar-hidden');
                sidebarOverlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            };
            
            // Event listeners
            sidebarToggle?.addEventListener('click', toggleSidebar);
            sidebarOverlay?.addEventListener('click', toggleSidebar);
            
            // Initialize based on screen size
            function handleResponsive() {
                if (window.innerWidth >= 768) {
                    // Desktop: show sidebar
                    sidebar.classList.remove('sidebar-hidden');
                    sidebarOverlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    // Mobile: hide sidebar
                    sidebar.classList.add('sidebar-hidden');
                    sidebarOverlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }
            
            // Run on load
            handleResponsive();
            
            // Run on resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(handleResponsive, 100);
            });
            
            // SweetAlert for session messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    background: '#1f2937',
                    color: '#fff',
                    position: 'top-end',
                    toast: true
                });
            @endif
            
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    background: '#1f2937',
                    color: '#fff'
                });
            @endif
        });
    </script>
    
    @stack('scripts')
</body>
</html>