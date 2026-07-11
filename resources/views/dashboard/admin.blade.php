@extends('layouts.sidebar')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-4 md:p-8">
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-white">Admin Dashboard</h1>
        <p class="text-gray-400 mt-1">Kelola sistem, user, dan monitoring global</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase">Total Users</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $totalUsers }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase">Total Tugas</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $totalTasks }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase">Tugas Aktif</p>
                    <p class="text-2xl font-bold text-emerald-400 mt-1">{{ $activeTasks }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase">Gallery</p>
                    <p class="text-2xl font-bold text-blue-400 mt-1">{{ \App\Models\Gallery::count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-bold text-white mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        
        <a href="{{ route('dashboard.users') }}" class="group bg-gray-700/60 p-5 rounded-xl border border-gray-600 hover:border-emerald-500/50 hover:bg-gray-700 transition">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg bg-purple-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold group-hover:text-emerald-400 transition">Kelola User</h3>
                    <p class="text-gray-400 text-sm mt-1">Tambah, edit, atau hapus akun user dan atur role mereka</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('tasks.index') }}" class="group bg-gray-700/60 p-5 rounded-xl border border-gray-600 hover:border-emerald-500/50 hover:bg-gray-700 transition">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold group-hover:text-emerald-400 transition">Semua Tugas</h3>
                    <p class="text-gray-400 text-sm mt-1">Lihat dan kelola semua tugas di sistem dari semua ketua</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('gallery.doksli') }}" class="group bg-gray-700/60 p-5 rounded-xl border border-gray-600 hover:border-emerald-500/50 hover:bg-gray-700 transition">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold group-hover:text-emerald-400 transition">Kelola Gallery</h3>
                    <p class="text-gray-400 text-sm mt-1">Moderasi dan kelola koleksi dokumentasi doksli</p>
                </div>
            </div>
        </a>
    </div>

    <h2 class="text-xl font-bold text-white mb-4">User Terbaru</h2>
    <div class="bg-gray-700/60 rounded-xl border border-gray-600 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800/80 text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">User</th>
                        <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">Role</th>
                        <th class="px-4 py-3 text-left font-medium">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-600">
                    @forelse($latestUsers as $user)
                    <tr class="hover:bg-gray-700/80 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $user->name }}</div>
                                    <div class="text-gray-500 text-xs">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            <span class="inline-flex items-center px-2 py-1 bg-gray-800 text-gray-300 text-xs rounded-md">
                                {{ config('roles.list.' . $user->role) ?? ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                            Tidak ada user untuk ditampilkan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection