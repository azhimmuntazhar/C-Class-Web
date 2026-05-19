<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <!-- Logo / Brand -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white">
                <span class="text-emerald-500">❯</span> Informatika CFI
            </h1>
        </div>

        <!-- Card -->
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-emerald-400 transition flex items-center">
                    ← Back to Login
                </a>
            </div>
        <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-8">
            <h1 class="text-2xl font-bold text-white mb-4 text-center">Forgot Your Password?</h1>
            <p class="text-gray-400">No problem. Just contact the admin as the main administrator.</p>
                <a href="https://wa.me/6289506035363" class="mt-4 w-full flex items-center justify-center px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition shadow-md hover:shadow-lg active:scale-95">
                    Admin
                </a>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-500 text-sm mt-6">
            &copy; {{ date('Y') }} Informatika CFI. All rights reserved.
        </p>
    </div>
</body>
</html>


