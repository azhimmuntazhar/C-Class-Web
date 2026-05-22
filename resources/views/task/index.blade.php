<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Tugas - Class C</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-700">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-50">
            <div class="p-6">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold">Informatika CFI</h2>                    
                    <!-- Close Button for Mobile -->
                    <button id="sidebarClose" class="md:hidden text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <ul>
                    <li class="mb-2">
                        <a href="{{ route('tasks.index') }}" class="block bg-emerald-600 hover:bg-emerald-700 p-2 rounded">
                            Dashboard
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('gallery.doksli') }}" class="block hover:bg-gray-700 p-2 rounded">
                            Gallery
                        </a>
                    </li>
                    {{-- Tambahkan lebih banyak link sidebar di sini --}}
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="max-w-6xl mx-auto px-4 py-10">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-white">Daftar Tugas</h1>
                <a href="{{ route('tasks.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tugas Baru
                </a>
            </div>

            @if($tasks->isEmpty())
                <div class="text-center py-12 bg-gray-800 rounded-2xl border border-gray-700">
                    <p class="text-gray-400">Belum ada tugas untuk ditampilkan.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($tasks as $task)
                    <div class="bg-gray-800 rounded-xl border border-gray-700 p-5 hover:border-emerald-500/30 transition group">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="inline-block px-2.5 py-1 bg-purple-900/40 text-purple-300 text-xs rounded-md font-medium mb-2">
                                    {{ $task->course_name }}
                                </span>
                                <h3 class="text-lg font-semibold text-white group-hover:text-emerald-400 transition">{{ $task->title }}</h3>
                                <p class="text-gray-400 text-sm mt-1">{{ Str::limit($task->description, 120) }}</p>
                            </div>
                            
                            {{-- 🔥 AUTO STATUS BADGE --}}
                            @if($task->is_expired)
                                <span class="px-3 py-1.5 bg-red-900/30 text-red-400 text-xs font-medium rounded-full border border-red-800/50 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Deadline Lewat
                                </span>
                            @else
                                <span class="px-3 py-1.5 bg-emerald-900/30 text-emerald-400 text-xs font-medium rounded-full border border-emerald-800/50 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Aktif
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-gray-400 mt-4 pt-4 border-t border-gray-700">
                            <span>📅 Mulai: <strong class="text-gray-300">{{ $task->starts_at->format('d M Y, H:i') }}</strong></span>
                            <span>⏰ Deadline: <strong class="text-gray-300">{{ $task->deadline_at->format('d M Y, H:i') }}</strong></span>
                            <span>👤 Oleh: <strong class="text-gray-300">{{ $task->user->name }}</strong></span>
                            <span>📋 {{ ucfirst($task->category) }}</span>
                        </div>

                        @if($task->material_link || $task->submission_link)
                        <div class="mt-4 flex flex-wrap gap-4">
                            @if($task->material_link)
                                <a href="{{ $task->material_link }}" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 hover:underline flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    Materi
                                </a>
                            @endif
                            @if($task->submission_link)
                                <a href="{{ $task->submission_link }}" target="_blank" class="text-xs text-emerald-400 hover:text-emerald-300 hover:underline flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Kumpul
                                </a>
                            @endif
                        </div>
                        @endif

                        {{-- Tombol Hapus (Owner/Admin/Manager) --}}
                        @if(auth()->id() === $task->user_id || in_array(auth()->user()->role, ['admin', 'manager']))
                        <div class="mt-4 pt-3 border-t border-gray-700">
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-300 transition">🗑️ Hapus</button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>

    <!-- SweetAlert Script -->
    <script>
        // SweetAlert for delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
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

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarClose.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Memastikan overlay hilang jika layar di-resize ke desktop saat sidebar terbuka
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) { // md breakpoint
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
