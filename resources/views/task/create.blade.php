<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tugas Baru</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>
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
                        <a href="{{ route('tasks.create') }}" class="block bg-emerald-600 hover:bg-emerald-700 p-2 rounded">Add New Task</a>
                    </li>
                </ul>
            </div>
        </div>

<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-white">Buat Tugas Baru</h1>
        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-400 hover:text-emerald-400 transition">← Kembali</a>
    </div>

    <form action="{{ route('tasks.store') }}" method="POST" class="bg-gray-800 p-6 rounded-2xl border border-gray-700 space-y-5">
        @csrf

        <!-- Mata Kuliah -->
        <div>
            <label class="block text-gray-300 mb-1 text-sm font-medium">Mata Kuliah</label>
            @if(in_array(auth()->user()->role, ['admin', 'manager']))
                <select name="course_key" required class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    @foreach(config('roles.courses') as $key => $name)
                        <option value="{{ $key }}" {{ old('course_key') == $key ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            @else
                @php $courseKey = config("roles.course_mapping." . auth()->user()->role); @endphp
                <input type="hidden" name="course_key" value="{{ $courseKey }}">
                <input type="text" value="{{ config('roles.courses.' . $courseKey) }}" disabled 
                       class="w-full px-4 py-2.5 bg-gray-700/50 border border-gray-600 rounded-lg text-gray-300 cursor-not-allowed">
                <p class="text-xs text-gray-500 mt-1">*Otomatis terisi sesuai divisi Anda</p>
            @endif
            @error('course_key') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Judul & Kategori -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 mb-1 text-sm font-medium">Judul Tugas</label>
                <input type="text" name="title" value="{{ old('title') }}" required 
                       class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-gray-300 mb-1 text-sm font-medium">Kategori</label>
                <select name="category" required class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="individu" {{ old('category') == 'individu' ? 'selected' : '' }}>Individu</option>
                    <option value="kelompok" {{ old('category') == 'kelompok' ? 'selected' : '' }}>Kelompok</option>
                </select>
                @error('category') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-gray-300 mb-1 text-sm font-medium">Deskripsi Tugas</label>
            <textarea 
                name="description" 
                rows="5" 
                required 
                class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                placeholder="Masukkan deskripsi tugas di sini...&#10;&#10;">{{ old('description') }}</textarea>
            @error('description') 
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p> 
            @enderror
            <p class="text-xs text-gray-500 mt-1">Tekan Enter untuk membuat paragraf baru</p>
        </div>

        <!-- Link -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 mb-1 text-sm font-medium">Link Materi (Opsional)</label>
                <input type="url" name="material_link" value="{{ old('material_link') }}" placeholder="https://..." 
                       class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                @error('material_link') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-gray-300 mb-1 text-sm font-medium">Link Pengumpulan (Opsional)</label>
                <input type="url" name="submission_link" value="{{ old('submission_link') }}" placeholder="https://..." 
                       class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                @error('submission_link') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Waktu -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-300 mb-1 text-sm font-medium">Mulai Tugas</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" required 
                       class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                @error('starts_at') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-gray-300 mb-1 text-sm font-medium">Deadline</label>
                <input type="datetime-local" name="deadline_at" value="{{ old('deadline_at') }}" required 
                       class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                @error('deadline_at') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ route('tasks.index') }}" class="flex-1 px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg transition text-center font-medium">Batal</a>
            <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition shadow-md hover:shadow-lg">Simpan Tugas</button>
        </div>
    </form>
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


