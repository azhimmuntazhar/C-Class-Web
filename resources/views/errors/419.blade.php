<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Sesi Berakhir | Informatika CFI</title>
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
        
        @keyframes tick {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(360deg); }
        }
        .animate-tick { animation: tick 4s linear infinite; transform-origin: center; }
        
        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 bg-grid z-0"></div>
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-yellow-500/10 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl -z-10"></div>

    <main class="relative z-10 w-full max-w-2xl">
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700 rounded-3xl p-8 md:p-12 shadow-2xl text-center">
            <div class="relative inline-block mb-8 animate-float">
                <div class="relative w-28 h-28 bg-gradient-to-br from-yellow-900/40 to-orange-800/20 rounded-full border border-yellow-700/50 flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-16 h-16 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" stroke-width="1.5"></circle>
                        <line class="animate-tick" x1="12" y1="12" x2="12" y2="6" stroke-width="2" stroke-linecap="round"></line>
                        <line x1="12" y1="12" x2="16" y2="12" stroke-width="2" stroke-linecap="round"></line>
                    </svg>
                </div>
                
                <div class="absolute -top-2 -right-2 w-12 h-12 bg-gray-900 rounded-xl border border-gray-700 flex items-center justify-center shadow-lg">
                    <span class="text-yellow-400 text-xs font-bold font-mono">419</span>
                </div>
            </div>

            <h1 class="text-5xl md:text-6xl font-bold mb-3 tracking-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-500">419</span>
            </h1>
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">
                Sesi Telah Berakhir
            </h2>
            
            <p class="text-gray-400 text-lg mb-8 leading-relaxed max-w-md mx-auto">
                Halaman yang kamu minta telah kedaluwarsa karena alasan keamanan. Silakan refresh halaman dan coba lagi.
            </p>

            <div class="bg-yellow-900/20 border border-yellow-700/50 rounded-2xl p-5 mb-8 text-left">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-yellow-300 text-sm font-medium mb-1">Kenapa ini terjadi?</p>
                        <p class="text-yellow-400/80 text-xs">Token keamanan kamu telah kedaluwarsa. Ini adalah fitur keamanan untuk melindungi website dari serangan.</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-xl transition shadow-lg hover:shadow-yellow-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh Halaman
                </button>
                <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition shadow-lg hover:shadow-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Ke Beranda
                </a>
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