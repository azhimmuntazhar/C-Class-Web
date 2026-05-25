<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload Gallery - Informatika CFI</title>

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
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm p-4 flex items-center justify-between md:hidden">
                <button id="sidebarToggle" class="text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-lg font-semibold">Upload Gallery</h1>
            </header>

            <div id="sidebarOverlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden md:hidden"></div>

            <div class="max-w-2xl mx-auto px-4 py-10 w-full">
                <div class="text-center text-white">
                    <h3 class="text-4xl font-bold mb-7 mt-7">Upload Gambar</h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <!-- Form Upload -->
                    <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Judul -->
                        <div class="mb-5">
                            <label class="block text-gray-700 font-medium mb-2">Judul Gambar</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                                   placeholder="Contoh: Dava lagi turu"
                                   required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Gambar -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Pilih Gambar</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-emerald-500 transition cursor-pointer" id="dropZone">
                                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" required>
                                <label for="imageInput" class="cursor-pointer">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">Klik atau import gambar di sini</p>
                                    <p class="text-xs text-gray-400">PNG, JPG, GIF • Maksimal 2MB</p>
                                </label>
                            </div>
                            <!-- Preview -->
                            <div id="previewContainer" class="mt-4 hidden">
                                <img id="imagePreview" class="w-32 h-32 object-cover rounded-lg border border-gray-200 mx-auto">
                            </div>
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol -->
                        <div class="flex gap-3">
                            <a href="{{ route('gallery.doksli') }}"
                               class="flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition text-center">
                                Batal
                            </a>
                            <button type="submit"
                                    class="flex-1 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                                Upload Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Preview gambar sebelum upload
        const imageInput = document.getElementById('imageInput');
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // SweetAlert for validation errors
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '{!! implode("<br>", $errors->all()) !!}',
                confirmButtonColor: '#dc2626'
            });
        @endif

        // Sidebar Toggle Script (sama seperti view lain)
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