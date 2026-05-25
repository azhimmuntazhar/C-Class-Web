<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Settings - Class C</title>

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
        html { scroll-behavior: smooth; }
        body { padding-top: 72px; } 
        
        /* Rounded Underline Animation */
        .nav-underline { position: relative; display: inline-block; }
        .nav-underline::after {
            content: ''; position: absolute; left: 0; bottom: -4px; width: 100%; height: 4px;
            background-color: #10b981; border-radius: 9999px; transform: scaleX(0);
            transform-origin: right; transition: transform 0.3s ease-in-out;
        }
        .nav-underline:hover::after, .nav-underline.active::after { transform: scaleX(1); transform-origin: left; }
    </style>
</head>
<body class="bg-gray-700 min-h-screen">

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

    <!--  MAIN CONTENT -->
    <main class="min-h-screen bg-gray-700">
        <div class="max-w-3xl mx-auto px-4 py-10 w-full">
            
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-white">Profile Settings</h1>
                <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-emerald-400 transition flex items-center gap-1">
                    ← Back to Home
                </a>
            </div>

            <!-- 1. UPDATE PROFILE INFORMATION -->
            <div class="bg-gray-800 rounded-2xl p-6 mb-6 border border-gray-700 shadow-sm">
                <h2 class="text-xl font-semibold text-white mb-4">Update Profile Information</h2>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2 text-sm font-medium">Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-300 mb-2 text-sm font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition shadow-md hover:shadow-lg active:scale-95">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- 2. UPDATE PASSWORD -->
            <div class="bg-gray-800 rounded-2xl p-6 mb-6 border border-gray-700 shadow-sm">
                <h2 class="text-xl font-semibold text-white mb-4">Update Password</h2>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2 text-sm font-medium">Current Password</label>
                        <input type="password" name="current_password" required autocomplete="current-password"
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('current_password') border-red-500 @enderror">
                        @error('current_password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-300 mb-2 text-sm font-medium">New Password</label>
                        <input type="password" name="password" required autocomplete="new-password"
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition @error('password') border-red-500 @enderror">
                        @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-300 mb-2 text-sm font-medium">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                               class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition shadow-md hover:shadow-lg active:scale-95">
                        Update Password
                    </button>
                </form>
            </div>

            <!--  SESSION & LOGOUT -->
            <div class="bg-gray-800 rounded-2xl p-6 mb-6 border border-gray-700 shadow-sm">
                <h2 class="text-xl font-semibold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Session Aktif
                </h2>
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <p class="text-white font-large">Name : {{ auth()->user()->name }}</p>
                        <p class="text-gray-500 text-md">Username : {{ auth()->user()->email }}</p>
                    </div>
                    
                    <!-- ✅ TOMBOL LOGOUT -->
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                        @csrf
                        <button type="button" onclick="confirmLogout()" 
                                class="px-5 py-2.5 bg-gray-700 hover:bg-yellow-600 text-gray-300 hover:text-white font-medium rounded-lg transition-all duration-200 border border-gray-600 hover:border-yellow-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- 3. DELETE ACCOUNT -->
            <div class="bg-gray-800 rounded-2xl p-6 border border-red-900/50 shadow-sm">
                <h2 class="text-xl font-semibold text-red-400 mb-2">Delete Account</h2>
                <p class="text-gray-400 text-sm mb-2">If you want to delete your account, please contact the admin as the main administrator.</p>
                <p class="text-red-400 text-md mb-6">*Making an account deletion request means that you are considered to have resigned.</p>  
                <a href="https://wa.me/6289506035363" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition shadow-md hover:shadow-lg active:scale-95">
                    Admin
                </a>              
            </div>

        </div>
    </main>

    <!-- ⚙️ SCRIPTS -->
    <script>
        // Mobile Menu Toggle
        const mobileMenu = document.getElementById('mobileMenu');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const toggleMenu = () => {
            mobileMenu.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden'); 
        };

        sidebarToggle?.addEventListener('click', toggleMenu);
        sidebarClose?.addEventListener('click', toggleMenu);
        sidebarOverlay?.addEventListener('click', toggleMenu);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                mobileMenu.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });

        // SweetAlert: Delete Account Confirmation
        function confirmDelete() {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Yes, Delete it!',
                cancelButtonText: 'Cancel',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteAccountForm').submit();
                }
            });
        }

        // SweetAlert: Success Message (Breeze uses session('status'))
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('status') }}',
                timer: 3000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: '#10b981',
                color: '#fff'
            });
        @endif

        // SweetAlert: Logout Confirmation
        function confirmLogout() {
            Swal.fire({
                title: 'Yakin ingin logout?',
                text: "Kamu akan keluar dari sesi saat ini",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#fff',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>
</body>
</html>