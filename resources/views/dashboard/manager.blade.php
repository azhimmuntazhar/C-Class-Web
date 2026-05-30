@extends('layouts.sidebar')

@section('title', 'Dashboard Manager')

@section('content')
<div class="p-4 md:p-8">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-white">Dashboard Manager</h1>
        <p class="text-gray-400 mt-1">Memantau tugas dari semua divisi Ketua</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-gray-700/60 rounded-xl border border-gray-600 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" placeholder="Cari judul tugas..." 
                       class="w-full px-4 py-2.5 pl-10 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <!-- Filter by Course -->
            <select class="px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                <option value="">Semua Mata Kuliah</option>
                @foreach(config('roles.courses') as $key => $name)
                    <option value="{{ $key }}">{{ $name }}</option>
                @endforeach
            </select>
            
            <!-- Filter by Status -->
            <select class="px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="expired">Expired</option>
            </select>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Total Tugas</p>
            <p class="text-2xl font-bold text-white">{{ $tasks->total() }}</p>
        </div>
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Aktif</p>
            <p class="text-2xl font-bold text-emerald-400">{{ $tasks->filter(fn($t) => !$t->is_expired)->count() }}</p>
        </div>
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Expired</p>
            <p class="text-2xl font-bold text-red-400">{{ $tasks->filter(fn($t) => $t->is_expired)->count() }}</p>
        </div>
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Divisi</p>
            <p class="text-2xl font-bold text-purple-400">{{ count(config('roles.courses')) }}</p>
        </div>
    </div>

    <!-- Task Table -->
    <div class="bg-gray-700/60 rounded-xl border border-gray-600 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800/80 text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Tugas</th>
                        <th class="px-4 py-3 text-left font-medium hidden md:table-cell">Mata Kuliah</th>
                        <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">Ketua</th>
                        <th class="px-4 py-3 text-left font-medium">Deadline</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-600">
                    @forelse($tasks as $task)
                    <tr class="hover:bg-gray-700/80 transition">
                        <td class="px-4 py-3">
                            <div class="font-medium text-white">{{ $task->title }}</div>
                            <div class="text-gray-500 text-xs md:hidden">{{ $task->course_name }}</div>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="inline-flex items-center px-2 py-1 bg-emerald-900/30 text-emerald-300 text-xs rounded-md">
                                {{ $task->course_name }}
                            </span>
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell text-gray-300">
                            {{ $task->user->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-300">
                            {{ $task->deadline_at->format('d M H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($task->is_expired)
                                <span class="inline-flex items-center px-2 py-1 bg-red-900/40 text-red-300 text-xs rounded-full">Expired</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 bg-emerald-900/40 text-emerald-300 text-xs rounded-full">Active</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button class="text-gray-400 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                            Tidak ada tugas untuk ditampilkan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($tasks->hasPages())
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection