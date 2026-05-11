<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Tugas</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CKEditor (opsional, untuk rich text description) -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
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
            <!-- Header for mobile -->
            <header class="bg-white shadow-sm p-4 flex items-center justify-between md:hidden">
                <button id="sidebarToggle" class="text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-lg font-semibold">Edit Tugas</h1>
            </header>

            <!-- Overlay for mobile -->
            <div id="sidebarOverlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden md:hidden"></div>

            <div class="max-w-6xl mx-auto px-4 py-10 w-full">
                <div class="text-center text-white">
                    <h3 class="text-5xl font-bold mb-7 mt-7">Edit Tugas Kelas</h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                    <div class="p-6">

                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-lg font-semibold text-gray-900">Edit Tugas</h4>
                            <a href="{{ route('tasks.index') }}"
                               class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition">
                                BACK
                            </a>
                        </div>

                        <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">IMAGE</label>

                                <div class="mb-3">
                                    <img
                                        src="{{ asset('/storage/tasks/'.$task->image) }}"
                                        class="w-32 h-32 object-cover rounded-lg border border-gray-200"
                                        alt="{{ $task->title }}"
                                    >
                                </div>

                                <input type="file" name="image"
                                       class="block w-full text-sm
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-lg file:border-0
                                              file:bg-gray-900 file:text-white
                                              hover:file:bg-gray-800
                                              border border-gray-200 rounded-lg bg-white">
                                @error('image')
                                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">TITLE</label>
                                <input type="text" name="title" value="{{ old('title', $task->title) }}"
                                       placeholder="Masukkan Judul Tugas"
                                       class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
                                @error('title')
                                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">DESCRIPTION</label>
                                <textarea name="description" id="description" rows="6"
                                          class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200"
                                          placeholder="Masukkan Description Tugas">{{ old('description', $task->description) }}</textarea>
                                @error('description')
                                    <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                                               bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                                    UPDATE
                                </button>

                                <button type="reset"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                                               bg-amber-500 text-white text-sm font-semibold hover:bg-amber-600 transition">
                                    RESET
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        CKEDITOR.replace('description');

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
