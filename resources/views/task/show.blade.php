<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show Tasks</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
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
                        <a href="{{ route('tasks.index') }}" class="block hover:bg-gray-700 p-2 rounded">Dashboard</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('tasks.create') }}" class="block hover:bg-gray-700 p-2 rounded">Add New Task</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex-1 flex flex-col">
            <!-- Header/Navbar for mobile toggle -->
            <header class="bg-white shadow-sm p-4 flex items-center justify-between md:hidden">
                <button id="sidebarToggle" class="text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-lg font-semibold">Detail Tugas</h1>
            </header>

            <!-- Overlay for mobile -->
            <div id="sidebarOverlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden md:hidden"></div>

            <div class="max-w-6xl mx-auto px-4 py-10 w-full">
                <div class="text-center text-white">
                    <h3 class="text-5xl font-bold mb-7 mt-7">Detail Tugas</h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                    <div class="p-6">

                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-lg font-semibold text-gray-900">Detail Tugas</h4>
                            <a href="{{ route('tasks.index') }}"
                               class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition">
                                KEMBALI
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                            <div class="md:col-span-4">
                                <div class="border border-gray-200 rounded-2xl overflow-hidden">
                                    <img
                                        src="{{ asset('/storage/tasks/'.$task->image) }}"
                                        class="w-full"
                                        alt="{{ $task->title }}"
                                    >
                                </div>
                            </div>

                            <div class="md:col-span-8">
                                <div class="border border-gray-200 rounded-2xl p-6">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $task->title }}</h3>
                                    <hr class="my-4 border-gray-200">

                                    <div class="mt-4">
                                        <div class="text-sm font-semibold text-gray-700 mb-2">DESKRIPSI</div>
                                        <div class="prose max-w-none text-gray-700">
                                            {!! $task->description !!}
                                        </div>
                                    </div>

                                    <hr class="my-4 border-gray-200">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const toggleSidebar = () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        };

        if(sidebarToggle) sidebarToggle.addEventListener('click', toggleSidebar);
        if(sidebarClose) sidebarClose.addEventListener('click', toggleSidebar);
        if(sidebarOverlay) sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>

</body>
</html>
