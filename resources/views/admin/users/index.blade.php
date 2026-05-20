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
            <div class="flex items-center gap-3">
                <a href="{{ route('galeri') }}" class="text-sm text-gray-400 hover:text-emerald-400 transition">← Back</a>
                
                {{-- Tombol Tambah User (Hanya untuk Admin) --}}
                @if(auth()->user()->role === 'admin')
                <button type="button" onclick="openModal()" 
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition flex items-center gap-2 shadow-md hover:shadow-lg ml-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah User
                </button>
                @endif
            </div>
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
                    <thead class="bg-gray text-emerald-300">
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
                                    $basicRoles = config('roles.basic_roles');
                                    $isKetua = in_array($user->role, $basicRoles);

                                    // Izin Edit: Admin (semua kecuali self) ATAU Manager (hanya ke target Ketua & bukan self)
                                    $canEdit = !$isSelf && ($isAdmin || ($isManager && $isKetua));
                                    
                                    // Izin Hapus: Admin (semua kecuali self) ATAU Manager (hanya ke target Ketua & bukan self)
                                    $canDelete = !$isSelf && ($isAdmin || ($isManager && $isKetua));
                                @endphp

                                @if($canEdit)
                                    <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <select name="role" onchange="this.form.submit()"
                                                class="px-3 py-1.5 bg-gray-700 border border-gray-600 rounded-lg text-sm text-white focus:ring-2 focus:ring-emerald-500 outline-none transition">
                                            
                                            @foreach(config('roles.list') as $key => $label)
                                                <option value="{{ $key }}" {{ $user->role === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach

                                            {{-- Opsi Admin/Manager hanya untuk Admin --}}
                                            @if(auth()->user()->role === 'admin')
                                                <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            @endif
                                        </select>
                                    </form>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium border
                                        @if($user->role === 'admin') bg-emerald-900/50 border-emerald-700 text-emerald-300
                                        @elseif($user->role === 'manager') bg-blue-900/50 border-blue-700 text-blue-300
                                        @else bg-purple-900/30 border-purple-700/50 text-purple-300 @endif">
                                        
                                        {{ config('roles.list.' . $user->role) ?? ucfirst($user->role) }}
                                        
                                        @if($isSelf) <span class="text-xs opacity-75">(You)</span>
                                        @elseif($isManager && !$isKetua) <span class="text-xs opacity-75">(Protected)</span>
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

    <!-- modal form -->
    <div id="createUserModal" class="fixed inset-0 z-[60] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>
        
        <!-- Modal Content -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md mx-4">
            <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-2xl overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-white">Tambah User Baru</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Form -->
                <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-4">
                    @csrf
                    
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-300 text-sm mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Email -->
                    <div>
                        <label class="block text-gray-300 text-sm mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label class="block text-gray-300 text-sm mb-1">Password</label>
                        <input type="password" name="password" required minlength="6"
                            class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('password') border-red-500 @enderror">
                        @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-gray-300 text-sm mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>
                    
                    <!-- Role -->
                    <div>
                        <label class="block text-gray-300 text-sm mb-1">Role / Divisi</label>
                        <select name="role" required
                                class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('role') border-red-500 @enderror">
                            
                            <!-- Loop 9 Role Ketua dari Config -->
                            @foreach(config('roles.list') as $key => $label)
                                <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach

                            <!-- Opsi Manager & Admin (Hanya tampil jika login sebagai Admin) -->
                            @if(auth()->user()->role === 'admin')
                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            @endif
                        </select>
                        @error('role') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeModal()" 
                                class="flex-1 px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-lg transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition shadow-md">
                            Buat User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

        //modal logic
        function openModal() {
            document.getElementById('createUserModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // Prevent scroll behind modal
        }

        function closeModal() {
            document.getElementById('createUserModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Close modal when pressing Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });

        // Close modal when clicking outside content
        document.getElementById('createUserModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'createUserModal') closeModal();
        });

        // Auto-open modal if there are validation errors from server
        @if ($errors->any() && request()->is('admin/users'))
            @if (session('success'))
                // Success: show toast only
            @else
                // Errors: open modal to show validation messages
                document.addEventListener('DOMContentLoaded', openModal);
            @endif
        @endif
    </script>
</body>
</html>