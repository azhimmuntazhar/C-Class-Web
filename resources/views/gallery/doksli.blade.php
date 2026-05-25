<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gallery - Informatika CFI</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    </style>
</head>
<body class="bg-gray-700">

    <div class="flex min-h-screen">
        <!-- Sidebar (SAMA PERSIS dengan struktur kamu) -->
        <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-50">
            <div class="p-6">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold">Informatika CFI</h2>                    
                    <button id="sidebarClose" class="md:hidden text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <ul>
                    <li class="mb-2">
                        <a href="{{ route('tasks.index') }}" class="block hover:bg-gray-700 p-2 rounded">
                            Dashboard
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('gallery.doksli') }}" class="block bg-emerald-600 hover:bg-emerald-700 p-2 rounded">
                            Gallery
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Konten -->
        <div class="max-w-7xl mx-auto py-8 w-full">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
                <div>
                    <h1 class="text-4xl font-light text-white tracking-tight">Gallery Doksli</h1>
                    <p class="text-emerald-600 mt-1 text-lg">Kelola koleksi Doksli asli</p>
                </div>
                <a href="{{ route('gallery.createdoksli') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg
                        bg-gray-900 text-white text-sm font-medium
                        hover:bg-gray-800 transition-all duration-200 shadow-sm hover:shadow-md
                        active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Upload Gambar
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-lg flex items-center gap-2 animate-fade-in">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Grid Gallery -->
            @if($galleries->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($galleries as $item)
                        <div class="group bg-white rounded-xl overflow-hidden border border-gray-100 
                                    hover:border-gray-200 hover:shadow-xl transition-all duration-300
                                    hover:-translate-y-1">
                            <!-- Gambar -->
                            <div class="aspect-square overflow-hidden bg-gray-50 relative">
                                <img src="{{ asset('storage/' . $item->image) }}" 
                                    alt="{{ $item->title }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                
                                <!-- Overlay saat hover -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                            </div>
                            
                            <!-- Info -->
                            <div class="p-4">
                                <h4 class="font-medium text-gray-800 truncate text-sm">{{ $item->title }}</h4>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $item->created_at->format('d M Y') }}
                                </p>
                            </div>

                            <!-- Tombol Hapus -->
                            <div class="px-4 pb-4 pt-1">
                                <form action="{{ route('gallery.destroy', $item->id) }}" 
                                    method="POST" 
                                    class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-3 py-2 rounded-lg border border-red-200 text-red-600
                                                text-xs font-medium hover:bg-red-50 transition-colors duration-200
                                                flex items-center justify-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada gambar</h3>
                    <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">Mulai koleksi Anda dengan mengupload gambar pertama</p>
                    <a href="{{ route('gallery.createdoksli') }}" 
                    class="inline-flex items-center text-gray-900 text-sm font-medium hover:text-gray-700 transition-colors">
                        Upload gambar sekarang
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Scripts (SAMA PERSIS dengan struktur kamu) -->
    <script>
        // SweetAlert for delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Gambar akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // SweetAlert for success message
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Sidebar Toggle Script
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const toggleSidebar = () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        };

        sidebarToggle?.addEventListener('click', toggleSidebar);
        sidebarClose?.addEventListener('click', toggleSidebar);
        sidebarOverlay?.addEventListener('click', toggleSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }
        });
    </script>
</body>
</html>