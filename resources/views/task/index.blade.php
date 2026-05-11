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
        <div class="flex-1 flex flex-col">
            <!-- Header/Navbar untuk tombol toggle (hanya terlihat di layar kecil) -->
            <header class="bg-white shadow-sm p-4 flex items-center justify-between md:hidden">
                <button id="sidebarToggle" class="text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-lg font-semibold">Dashboard Tugas</h1>
            </header>

            <!-- Overlay untuk mobile (saat sidebar terbuka) -->
            <div id="sidebarOverlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden md:hidden"></div>

            <!-- Konten Halaman Sebenarnya -->
            <div class="max-w-6xl mx-auto px-4 py-10 w-full">
                <div class="text-center text-white">
                    <h3 class="text-5xl font-bold mb-7 mt-7">Dashboard Tugas</h3>
                </div>


                <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                    <div class="p-6">

                        <a href="{{ route('tasks.create') }}"
                           class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                                  bg-emerald-600 text-white text-sm font-semibold
                                  hover:bg-emerald-700 transition mb-4">
                            ADD TASK
                        </a>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                                <thead class="bg-gray-50 text-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold border-b">IMAGE</th>
                                        <th class="px-4 py-3 text-left font-semibold border-b">TITLE</th>
                                        <th class="px-4 py-3 text-left font-semibold border-b">DESCRIPTION</th>
                                        <th class="px-4 py-3 text-left font-semibold border-b w-[220px]">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tasks as $task)
                                        <tr class="border-b last:border-b-0">
                                            <td class="px-4 py-3">
                                                <div class="flex justify-center">
                                                    <img
                                                        src="{{ asset('/storage/tasks/'.$task->image) }}"
                                                        class="w-32 h-32 object-cover rounded-lg border border-gray-200"
                                                        alt="{{ $task->title }}"
                                                    >
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 font-medium text-gray-900">
                                                {{ $task->title }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ Str::limit(strip_tags($task->description), 40) }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <form
                                                    action="{{ route('tasks.destroy', $task->id) }}"
                                                    method="POST"
                                                    class="delete-form flex items-center gap-2"
                                                >
                                                    <a href="{{ route('tasks.show', $task->id) }}"
                                                       class="px-3 py-2 rounded-lg bg-gray-900 text-white
                                                              text-xs font-semibold hover:bg-gray-800 transition">
                                                        SHOW
                                                    </a>

                                                    <a href="{{ route('tasks.edit', $task->id) }}"
                                                       class="px-3 py-2 rounded-lg bg-blue-600 text-white
                                                              text-xs font-semibold hover:bg-blue-700 transition">
                                                        EDIT
                                                    </a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="px-3 py-2 rounded-lg bg-red-600 text-white
                                                               text-xs font-semibold hover:bg-red-700 transition">
                                                        HAPUS
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-6">
                                                <div class="bg-red-50 border border-red-200 text-red-700
                                                            rounded-lg px-4 py-3">
                                                    Data Tugas belum ada.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $tasks->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
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
