<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | Informatika CFI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(31,41,55,0.4); }
        ::-webkit-scrollbar-thumb { background: rgba(75,85,99,0.8); border-radius: 3px; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(-5deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .animate-blink { animation: blink 1.5s ease-in-out infinite; }
        
        @keyframes dash {
            to { stroke-dashoffset: 0; }
        }
        .animate-dash {
            stroke-dasharray: 1000;
            stroke-dashoffset: 1000;
            animation: dash 3s ease-in-out forwards;
        }
        
        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 bg-grid z-0"></div>
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-500/10 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl -z-10"></div>

    <main class="relative z-10 w-full max-w-2xl">
        <div class="bg-gray-800/60 backdrop-blur-xl border border-gray-700 rounded-3xl p-8 md:p-12 shadow-2xl text-center">
            <div class="relative inline-block mb-8 animate-float">
                <div class="relative w-32 h-32 bg-gradient-to-br from-gray-800 to-gray-900 rounded-full border border-gray-700 flex items-center justify-center shadow-inner">
                    <svg class="w-20 h-20 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path class="animate-dash" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                
                <div class="absolute -top-2 -right-2 px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-lg animate-blink">
                    404
                </div>
            </div>

            <h1 class="text-5xl md:text-6xl font-bold mb-3 tracking-tight">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-red-600">404</span>
            </h1>
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">
                Halaman Tidak Ditemukan
            </h2>
            
            <p class="text-gray-400 text-lg mb-8 leading-relaxed max-w-md mx-auto">
                Oops! Halaman yang kamu cari mungkin telah dipindahkan, dihapus, atau tidak pernah ada.
            </p>

            <div class="bg-gray-900/80 border border-gray-700 rounded-2xl p-5 mb-8">
                <p class="text-sm text-gray-400 mb-3">Mungkin kamu sedang mencari:</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <a href="{{ route('about') }}" class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs rounded-lg transition border border-gray-700">About</a>
                    <a href="{{ route('tasks.public') }}" class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs rounded-lg transition border border-gray-700"> Tugas</a>
                    <a href="{{ route('galeri') }}" class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs rounded-lg transition border border-gray-700"> Gallery</a>
                </div>
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