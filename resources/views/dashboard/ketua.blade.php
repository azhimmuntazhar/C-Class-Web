@extends('layouts.sidebar')

@section('title', 'Dashboard Ketua')

@section('content')
<div class="p-4 md:p-8">
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-white">Dashboard Ketua</h1>
        <p class="text-gray-400 mt-1">
            Mata Kuliah: <span class="text-emerald-400 font-semibold">{{ $courseName }}</span>
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wide">Total Tugas</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $tasks->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wide">Aktif</p>
                    <p class="text-2xl font-bold text-emerald-400 mt-1">{{ $tasks->filter(fn($t) => !$t->is_expired)->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wide">Expired</p>
                    <p class="text-2xl font-bold text-red-400 mt-1">{{ $tasks->filter(fn($t) => $t->is_expired)->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-red-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-700/60 p-5 rounded-xl border border-gray-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-wide">Terdekat</p>
                    <p class="text-lg font-bold text-orange-400 mt-1">
                        {{ $tasks->filter(fn($t) => !$t->is_expired)->sortBy('deadline_at')->first()?->deadline_at?->diffForHumans(['short' => true]) ?? '-' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-lg bg-orange-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h2 class="text-xl font-bold text-white">Daftar Tugas</h2>
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition shadow-md hover:shadow-lg active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Tugas Baru
        </a>
    </div>

    <div class="bg-gray-700/60 rounded-xl border border-gray-600 overflow-hidden">
        @forelse($tasks as $task)
        <div class="p-4 border-b border-gray-600 last:border-0 hover:bg-gray-700/80 transition group">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="text-white font-medium truncate group-hover:text-emerald-400 transition">{{ $task->title }}</h3>
                        @if($task->is_expired)
                            <span class="px-2 py-0.5 bg-red-900/40 text-red-300 text-xs rounded-full border border-red-700/50">Expired</span>
                        @else
                            <span class="px-2 py-0.5 bg-emerald-900/40 text-emerald-300 text-xs rounded-full border border-emerald-700/50">Active</span>
                        @endif
                    </div>
                    <p class="text-gray-400 text-sm line-clamp-1">{{ Str::limit($task->description, 80) }}</p>
                    <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $task->deadline_at->format('d M Y, H:i') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ ucfirst($task->category) }}
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <button onclick="if(confirm('Hapus tugas ini?')) document.getElementById('delete-form-{{ $task->id }}').submit()" 
                            class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-900/20 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    <form id="delete-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task) }}" method="POST" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            <p class="text-gray-400">Belum ada tugas untuk {{ $courseName }}</p>
            <a href="{{ route('tasks.create') }}" class="inline-block mt-4 text-emerald-400 hover:text-emerald-300 text-sm font-medium">Buat tugas pertama →</a>
        </div>
        @endforelse
    </div>
    
    @if($tasks->hasPages())
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection