<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Informatika CFI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(31,41,55,0.4); }
        ::-webkit-scrollbar-thumb { background: rgba(75,85,99,0.8); border-radius: 3px; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
        
        @keyframes lock-shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }
        .animate-lock { animation: lock-shake 2s ease-in-out infinite; }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.5; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        .pulse-ring { animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        
        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 bg-grid z-0"></div>
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-red-500/10 rounded-full blur-3xl -z-10"></div>

    <main class="relative z-10 w-full max-w-2xl">
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700 rounded-3xl p-8 md:p-12 shadow-2xl text-center">
            <div class="relative inline-block mb-8 animate-float">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-28 h-28 rounded-full border-2 border-orange-500/30 pulse-ring"></div>
                </div>
                
                <div class="relative w-28 h-28 bg-gradient-to-br from-orange-900/40 to-red-800/20 rounded-2xl border border-orange-700/50 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-14 h-14 text-orange-400 animate-lock" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-gray-900 rounded-full border-2 border-gray-700 flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-5xl md:text-6xl font-bold mb-3 tracking-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500">403</span>
            </h1>
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">
                Akses Ditolak
            </h2>
            
            <p class="text-gray-400 text-lg mb-8 leading-relaxed max-w-md mx-auto">
                Maaf, kamu tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika kamu merasa ini adalah kesalahan.
            </p>

            <div class="bg-orange-900/20 border border-orange-700/50 rounded-2xl p-5 mb-8 text-left">
                <h3 class="text-white font-semibold mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Mengapa ini terjadi?
                </h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-start gap-2">
                        <span class="text-orange-400">•</span>
                        <span>Kamu mencoba mengakses halaman yang memerlukan role <strong class="text-white">Admin</strong> atau <strong class="text-white">Manager</strong>.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-orange-400">•</span>
                        <span>Session login kamu mungkin telah kedaluwarsa.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-orange-400">•</span>
                        <span>URL yang diakses tidak valid atau telah diubah.</span>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url()->previous() }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-xl transition border border-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition shadow-lg hover:shadow-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Ke Beranda
                </a>
                @auth
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-700/60 hover:bg-gray-700 text-white font-medium rounded-xl transition border border-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                @endauth
            </div>
        </div>

        <footer class="mt-8 text-center">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} <span class="text-emerald-500 font-bold">❯</span> Informatika CFI
            </p>
        </footer>
    </main>
</body>
</html>