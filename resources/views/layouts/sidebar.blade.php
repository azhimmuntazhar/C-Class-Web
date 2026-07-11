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
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(31,41,55,0.4); border-radius: 3px; }
        ::-webkit-scrollbar-thumb { background: rgba(75,85,99,0.8); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(107,114,128,1); }
        html { scrollbar-width: thin; scrollbar-color: rgba(75,85,99,0.8) rgba(31,41,55,0.4); }

        html { scroll-behavior: smooth; }

        .sidebar { transition: transform 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }

        .nav-item.active {
            position: relative;
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            background-color: #10b981;
            border-radius: 9999px;
        }

        .content-with-sidebar {
            margin-left: 0;
            padding-top: 0;
            transition: margin-left 0.3s ease-in-out, padding-top 0.3s ease-in-out;
        }

        @media (min-width: 768px) {
            .content-with-sidebar {
                margin-left: 16rem;
            }
        }

        @media (max-width: 767px) {
            .content-with-sidebar {
                padding-top: 4rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-800 text-white">

<nav class="md:hidden fixed top-0 left-0 right-0 h-16 bg-gray-900/95 backdrop-blur-md border-b border-gray-700 z-40 flex items-center px-4">
    <div class="flex items-center gap-3">
        <button id="sidebarToggle" class="p-2 text-gray-300 hover:text-white focus:outline-none -ml-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <a href="{{ route('home') }}" class="flex items-center gap-1.5 text-lg font-bold text-white">
            <span class="text-emerald-500">❯</span>
            <span class="truncate">Informatika CFI</span>
        </a>
    </div>
    <div class="flex-1"></div>
</nav>

<aside id="sidebar" class="sidebar sidebar-hidden fixed inset-y-0 left-0 w-64 bg-gray-900 border-r border-gray-700 z-50 flex flex-col">
    
    <div class="h-16 flex items-center px-6 border-b border-gray-700">
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold">
            <span class="text-emerald-500 text-2xl">❯</span>
            <span class="truncate">Informatika CFI</span>
        </a>
    </div>
    
    <a href="{{ route('profile.edit') }}" class="block hover:bg-gray-800/80 transition">
        <div class="p-4 border-b border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 capitalize">{{ config('roles.list.' . auth()->user()->role) ?? auth()->user()->role }}</p>
                </div>
            </div>
        </div>
    </a>
    
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @php
            $currentRoute = request()->route() ? request()->route()->getName() : '';
        @endphp
        
        <a href="{{ route('dashboard') }}" 
           class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ $currentRoute === 'dashboard' ? 'bg-gray-800 text-white active' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>
        
        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
            <a href="{{ route('tasks.index') }}" 
               class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ str_contains($currentRoute, 'tasks.') ? 'bg-gray-800 text-white active' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Semua Tugas
            </a>
        @else
            <a href="{{ route('tasks.index') }}" 
               class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ str_contains($currentRoute, 'tasks.') ? 'bg-gray-800 text-white active' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Tugas Saya
            </a>
        @endif
        
        @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <a href="{{ route('dashboard.gallery') }}" 
               class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ str_contains($currentRoute, 'dashboard.gallery') ? 'bg-gray-800 text-white active' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Kelola Gallery
            </a>
        @endif

        <a href="{{ route('dashboard.announcements') }}" 
            class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ str_contains($currentRoute, 'dashboard.announcements') ? 'bg-gray-800 text-white active' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            Pengumuman
            @php
                $_annCount = auth()->user()->role === 'admin' 
                    ? \App\Models\Announcement::where('status', 'baru')->where('expires_at', '>', now())->count()
                    : \App\Models\Announcement::where('user_id', auth()->id())->where('status', 'baru')->where('expires_at', '>', now())->count();
            @endphp
            @if($_annCount > 0)
                <span class="ml-auto px-2 py-0.5 bg-orange-500 text-white text-xs font-bold rounded-full">
                    {{ $_annCount }}
                </span>
            @endif
        </a>
        
        @if(auth()->user()->role === 'admin')
            <div class="pt-4 mt-4 border-t border-gray-700">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Admin</p>
                <a href="{{ route('dashboard.users') }}" 
                class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ str_contains($currentRoute, 'dashboard.users') ? 'bg-gray-800 text-white active' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Kelola User
                </a>
                <a href="{{ route('dashboard.reports') }}" 
                class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ str_contains($currentRoute, 'dashboard.reports') ? 'bg-gray-800 text-white active' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Laporan
                    @php
                        $newReportsCount = \App\Models\Report::where('status', 'baru')->count();
                    @endphp
                    @if($newReportsCount > 0)
                        <span class="ml-auto px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">
                            {{ $newReportsCount }}
                        </span>
                    @endif
                </a>
            </div>
        @endif

        @if(auth()->user()->role === 'manager')
            <div class="pt-4 mt-4 border-t border-gray-700">
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Manager</p>
                <a href="{{ route('dashboard.users') }}" 
                class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ str_contains($currentRoute, 'dashboard.users') ? 'bg-gray-800 text-white active' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Kelola User
                </a>
                <a href="{{ route('dashboard.reports') }}" 
                class="nav-item flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ str_contains($currentRoute, 'dashboard.reports') ? 'bg-gray-800 text-white active' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Laporan
                    @php
                        $newReportsCount = \App\Models\Report::where('status', 'baru')->count();
                    @endphp
                    @if($newReportsCount > 0)
                        <span class="ml-auto px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full">
                            {{ $newReportsCount }}
                        </span>
                    @endif
                </a>
            </div>
        @endif
        
    </nav>
    
    <div class="p-4 border-t border-gray-700 space-y-2">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-400 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Home
        </a>
        
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:text-red-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden backdrop-blur-sm"></div>

<main class="content-with-sidebar min-h-screen">
    @yield('content')
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        const toggleSidebar = () => {
            sidebar.classList.toggle('sidebar-hidden');
            sidebarOverlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        };
        
        sidebarToggle?.addEventListener('click', toggleSidebar);
        sidebarOverlay?.addEventListener('click', toggleSidebar);
        
        function handleResponsive() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-hidden');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            } else {
                sidebar.classList.add('sidebar-hidden');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
        
        handleResponsive();
        
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(handleResponsive, 100);
        });
        
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