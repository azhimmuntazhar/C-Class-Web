<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error | Informatika CFI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(31,41,55,0.4); }
        ::-webkit-scrollbar-thumb { background: rgba(75,85,99,0.8); border-radius: 3px; }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .animate-shake { animation: shake 0.8s ease-in-out; }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.3); }
            50% { box-shadow: 0 0 40px rgba(239, 68, 68, 0.6); }
        }
        .animate-glow { animation: pulse-glow 2s ease-in-out infinite; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
        
        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 bg-grid z-0"></div>
    <div class="absolute top-1/3 left-1/4 w-96 h-96 bg-red-500/10 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-1/3 right-1/4 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl -z-10"></div>

    <main class="relative z-10 w-full max-w-2xl">
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700 rounded-3xl p-8 md:p-12 shadow-2xl text-center">
            <div class="relative inline-block mb-8 animate-float">
                <div class="relative w-28 h-28 bg-gradient-to-br from-red-900/40 to-red-800/20 rounded-full border border-red-700/50 flex items-center justify-center animate-glow">
                    <svg class="w-16 h-16 text-red-400 animate-shake" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                
                <div class="absolute -bottom-2 -right-2 w-12 h-12 bg-gray-900 rounded-xl border border-gray-700 flex items-center justify-center shadow-lg">
                    <span class="text-red-400 text-xs font-bold font-mono">500</span>
                </div>
            </div>

            <h1 class="text-5xl md:text-6xl font-bold mb-3 tracking-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-500">500</span>
            </h1>
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">
                Server Error
            </h2>
            
            <p class="text-gray-400 text-lg mb-8 leading-relaxed max-w-md mx-auto">
                Terjadi kesalahan pada server kami. Tim teknis telah diberitahu dan sedang memperbaiki masalah ini.
            </p>

            <div class="bg-red-900/20 border border-red-700/50 rounded-2xl p-5 mb-8 text-left">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-red-300 text-sm font-medium mb-1">Apa yang bisa kamu lakukan?</p>
                        <ul class="text-red-400/80 text-xs space-y-1">
                            <li>• Coba refresh halaman dalam beberapa saat</li>
                            <li>• Bersihkan cache browser kamu</li>
                            <li>• Jika masalah berlanjut, hubungi admin via WhatsApp</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-xl transition border border-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Refresh
                </button>
                <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition shadow-lg hover:shadow-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Ke Beranda
                </a>
                <a href="https://wa.me/6289506035363" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-xl transition shadow-lg hover:shadow-green-500/20">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
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