<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Informatika CFI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html { scroll-behavior: smooth; }
        body { padding-top: 72px; }
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

    <!-- 🔝 TOP NAVBAR (Fixed) -->
    <nav class="fixed top-0 left-0 right-0 h-16 bg-gray-800 text-white shadow-lg z-50 flex items-center justify-between px-4 md:px-20 transition-all duration-300">
        <div class="flex items-center gap-4">
            <button id="sidebarToggle" class="md:hidden text-gray-300 hover:text-white focus:outline-none p-1">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <a href="{{ route('home') }}" class="text-xl font-bold tracking-wide flex items-center gap-2">
                <span class="text-emerald-500">❯</span> Informatika CFI
            </a>
        </div>
        <div class="hidden md:flex items-center gap-2">
            <a href="{{ route('home') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">Home</a>
            <a href="{{ route('galeri') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('galeri') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">Gallery</a>
            <a href="{{ route('about') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">About</a>
        </div>
    </nav>

    <!-- 📱 MOBILE MENU -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>
    <div id="mobileMenu" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transform -translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden flex flex-col">
        <div class="p-6 border-b border-gray-700 flex items-center justify-between">
            <span class="text-lg font-bold">Menu</span>
            <button id="sidebarClose" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 flex flex-col gap-2">
            <a href="{{ route('home') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('home') ? 'bg-emerald-600' : '' }}">Home</a>
            <a href="{{ route('galeri') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('galeri') ? 'bg-emerald-600' : '' }}">Gallery</a>
            <a href="{{ route('about') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('about') ? 'bg-emerald-600' : '' }}">About</a>
        </div>
    </div>    
    <!-- Mobile Menu Content -->
    <div id="mobileMenu" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white transform -translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden flex flex-col">
        <div class="p-6 border-b border-gray-700 flex items-center justify-between">
            <span class="text-lg font-bold">Menu</span>
            <button id="sidebarClose" class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 flex flex-col gap-2">
            <a href="{{ route('home') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('home') ? 'bg-emerald-600' : '' }}">
                Home
            </a>
            <a href="{{ route('galeri') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('galeri') ? 'bg-emerald-600' : '' }}">
                Gallery
            </a>            
            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('login') ? 'bg-emerald-600' : '' }}">
                Login
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 py-10 w-full">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-white">User Management</h1>
            <a href="{{ route('galeri') }}" class="text-sm text-gray-400 hover:text-emerald-400 transition">← Back</a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-900/50 border border-emerald-700 text-emerald-300 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-900/50 border border-red-700 text-red-300 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-700 text-gray-300">
                        <tr>
                            <th class="px-6 py-4 text-left font-medium">ID</th>
                            <th class="px-6 py-4 text-left font-medium">Name</th>
                            <th class="px-6 py-4 text-left font-medium">Email</th>
                            <th class="px-6 py-4 text-left font-medium">Role</th>
                            <th class="px-6 py-4 text-left font-medium">Joined</th>
                            <th class="px-6 py-4 text-left font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 text-gray-400">{{ $user->id }}</td>
                            <td class="px-6 py-4 text-white font-medium">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $isSelf = $user->id === auth()->id();
                                    $isAdmin = auth()->user()->role === 'admin';
                                    $isManager = auth()->user()->role === 'manager';
                                    $targetIsUser = $user->role === 'user';

                                    // Izin Edit: Admin (semua kecuali self) ATAU Manager (hanya ke target User & bukan self)
                                    $canEdit = !$isSelf && ($isAdmin || ($isManager && $targetIsUser));
                                    // Izin Hapus: Admin (semua kecuali self) ATAU Manager (hanya ke target User & bukan self)
                                    $canDelete = !$isSelf && ($isAdmin || ($isManager && $targetIsUser));
                                @endphp

                                @if($canEdit)
                                    <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <select name="role" onchange="this.form.submit()"
                                                class="px-3 py-1.5 bg-gray-700 border border-gray-600 rounded-lg text-sm text-white focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                            <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                            @if($isAdmin)
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            @endif
                                        </select>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium border
                                        @if($user->role === 'admin') bg-emerald-900/50 border-emerald-700 text-emerald-300
                                        @elseif($user->role === 'manager') bg-blue-900/50 border-blue-700 text-blue-300
                                        @else bg-gray-700 border-gray-600 text-gray-300 @endif">
                                        {{ ucfirst($user->role) }}
                                        @if($isSelf)
                                            <span class="text-xs opacity-75">(You)</span>
                                        @elseif($isManager && !$targetIsUser)
                                            <span class="text-xs opacity-75">(Protected)</span>
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                            <!-- td action -->
                            <td class="px-6 py-4">
                                @if($canDelete)
                                    {{-- FORM HAPUS DENGAN ID UNIK --}}
                                    <form id="delete-form-{{ $user->id }}" 
                                        method="POST" 
                                        action="{{ route('admin.users.destroy', $user) }}" 
                                        class="inline">
                                        @csrf
                                        @method('DELETE') {{-- WAJIB AGAR ROUTING BENAR --}}
                                        <button type="button" 
                                                onclick="confirmDelete({{ $user->id }})"
                                                class="px-3 py-1.5 bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white rounded-lg text-xs font-medium transition">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500 text-xs">
                                        @if($isSelf) (You) @else (Protected) @endif
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $users->links() }}
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Hapus user ini?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Ya, Hapus!',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ PAKAI ID SPESIFIK, bukan selector ambigu
                    const form = document.getElementById(`delete-form-${userId}`);
                    if (form) {
                        form.submit();
                    } else {
                        Swal.fire('Error', 'Form tidak ditemukan', 'error');
                    }
                }
            });
        }

        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('success') }}', timer: 3000, showConfirmButton: false, background: '#1f2937', color: '#fff', position: 'top-end', toast: true });
        @endif

        // Mobile Menu Toggle Logic
        const mobileMenu = document.getElementById('mobileMenu');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const toggleMenu = () => {
            mobileMenu.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
            // Mencegah scroll body saat menu mobile terbuka
            document.body.classList.toggle('overflow-hidden'); 
        };

        sidebarToggle?.addEventListener('click', toggleMenu);
        sidebarClose?.addEventListener('click', toggleMenu);
        sidebarOverlay?.addEventListener('click', toggleMenu);

        // Close menu when resizing to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                mobileMenu.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });

        // SweetAlert for success message (Global)
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: '#1f2937',
                color: '#fff'
            });
        @endif
    </script>
</body>
</html>