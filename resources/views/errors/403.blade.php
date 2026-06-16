<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Informatika CFI</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Global Scrollbar - Sama dengan tema */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(31,41,55,0.4); border-radius: 3px; }
        ::-webkit-scrollbar-thumb { background: rgba(75,85,99,0.8); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(107,114,128,1); }
        html { scrollbar-width: thin; scrollbar-color: rgba(75,85,99,0.8) rgba(31,41,55,0.4); }
        
        html { scroll-behavior: smooth; }
        
        /* Animasi Float untuk Icon */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Animasi Pulse untuk Shield */
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.5; }
            100% { transform: scale(1.2); opacity: 0; }
        }
        .pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-gray-800 text-white max-h-screen flex flex-col">

    <!-- Mobile Menu (Simple) -->
    <div id="mobileMenu" class="fixed inset-0 z-40 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleMobileMenu()"></div>
        <div class="absolute right-0 top-0 h-full w-64 bg-gray-900 border-l border-gray-700 p-6 flex flex-col gap-4">
            <div class="flex justify-end">
                <button onclick="toggleMobileMenu()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <a href="{{ url('/') }}" class="text-gray-300 hover:text-white py-2 border-b border-gray-700">Home</a>
            @auth
                <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white py-2 border-b border-gray-700">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 py-2 font-medium">Login</a>
            @endauth
        </div>
    </div>

    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-red-500/5 rounded-full blur-3xl"></div>
    </div>

    <!-- 403 ERROR CONTENT -->
    <main class="flex-1 flex items-center justify-center px-4 pt-24 pb-12">
        <div class="max-w-lg w-full text-center">
            
            <!-- Animated Shield Icon -->
            <div class="relative inline-block mb-8 animate-float">
                <!-- Pulse Ring Effect -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-24 h-24 rounded-full border-2 border-red-500/30 pulse-ring"></div>
                </div>
                
                <!-- Main Shield Icon -->
                <div class="relative w-24 h-24 bg-gradient-to-br from-red-900/40 to-red-800/20 rounded-2xl border border-red-700/50 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <!-- Lock Badge -->
                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gray-800 rounded-full border-2 border-gray-700 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>

            <!-- Error Code & Message -->
            <h1 class="text-6xl md:text-7xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-red-600 mb-4">
                403
            </h1>
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">
                Akses Ditolak
            </h2>
            <p class="text-gray-400 text-lg mb-8 leading-relaxed">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. 
                <br class="hidden sm:block">
                Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                <a href="{{ url()->previous() }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-xl transition border border-gray-600 hover:border-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                
                <a href="{{ route('home') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition shadow-lg hover:shadow-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Beranda
                </a>
                
                @auth
                <a href="{{ route('dashboard') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-700/60 hover:bg-gray-700 text-white font-medium rounded-xl transition border border-gray-600">
                    Dashboard
                </a>
                @endauth
            </div>

            <!-- Help Section -->
            <div class="bg-gray-700/40 rounded-xl border border-gray-600 p-5 text-left">
                <h3 class="text-white font-semibold mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Mengapa ini terjadi?
                </h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-start gap-2">
                        <span class="text-red-400 mr-2">•</span>
                        <span>Anda mencoba mengakses halaman terbatas.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-red-400 mr-2">•</span>
                        <span>Session login Anda mungkin telah kedaluwarsa.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-red-400 mr-2">•</span>
                        <span>URL yang diakses tidak valid atau telah diubah.</span>
                    </li>
                </ul>
                
                @guest
                <div class="mt-4 pt-4 border-t border-gray-600">
                    <p class="text-sm text-gray-400 mb-3">Belum login? Silakan masuk untuk mengakses fitur lengkap.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-emerald-400 hover:text-emerald-300 text-sm font-medium transition">
                        Login sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
                @endguest
            </div>

        </div>
    </main>


    <!-- ⚙️ SCRIPTS -->
    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }
        
        document.getElementById('mobileMenuBtn')?.addEventListener('click', toggleMobileMenu);
        
        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.getElementById('mobileMenu')?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
        
        // Auto-hide mobile menu on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                document.getElementById('mobileMenu')?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    </script>
</body>
</html>