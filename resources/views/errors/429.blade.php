<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Terlalu Banyak Permintaan | Informatika CFI</title>
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
        
        @keyframes countdown {
            from { stroke-dashoffset: 0; }
            to { stroke-dashoffset: 283; }
        }
        .animate-countdown { animation: countdown 60s linear forwards; }
        
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
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-pink-500/10 rounded-full blur-3xl -z-10"></div>

    <main class="relative z-10 w-full max-w-2xl">
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700 rounded-3xl p-8 md:p-12 shadow-2xl text-center">
            <div class="relative inline-block mb-8 animate-float">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-28 h-28 rounded-full border-2 border-purple-500/30 pulse-ring"></div>
                </div>
                
                <div class="relative w-28 h-28 bg-gradient-to-br from-purple-900/40 to-pink-800/20 rounded-full border border-purple-700/50 flex items-center justify-center backdrop-blur-sm">
                    <svg class="absolute w-full h-full -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" stroke="rgba(168, 85, 247, 0.2)" stroke-width="4" fill="none"></circle>
                        <circle class="animate-countdown" cx="50" cy="50" r="45" stroke="#a855f7" stroke-width="4" fill="none" stroke-linecap="round"></circle>
                    </svg>
                    
                    <svg class="w-12 h-12 text-purple-400 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                
                <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-gray-900 rounded-xl border border-gray-700 flex items-center justify-center shadow-lg">
                    <span class="text-purple-400 text-xs font-bold font-mono">429</span>
                </div>
            </div>

            <h1 class="text-5xl md:text-6xl font-bold mb-3 tracking-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-500">429</span>
            </h1>
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">
                Terlalu Banyak Permintaan
            </h2>
            
            <p class="text-gray-400 text-lg mb-8 leading-relaxed max-w-md mx-auto">
                Kamu telah melakukan terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat sebelum mencoba lagi.
            </p>

            <div class="bg-purple-900/20 border border-purple-700/50 rounded-2xl p-5 mb-8">
                <div class="flex items-center justify-center gap-3">
                    <div class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-purple-500"></span>
                    </div>
                    <span class="text-purple-300 text-sm font-medium">Cooldown aktif. Coba lagi dalam beberapa saat...</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button onclick="setTimeout(() => window.location.reload(), 5000)" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl transition shadow-lg hover:shadow-purple-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Coba Lagi (5 detik)
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