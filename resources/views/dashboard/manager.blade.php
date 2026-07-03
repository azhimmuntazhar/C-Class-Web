@extends('layouts.sidebar')

@section('title', 'Dashboard Manager')

@push('styles')
<style>
    /* Card Hover Effect */
    .stat-card {
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    /* Staggered Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
    }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }
    .delay-6 { animation-delay: 0.6s; }
    
    /* Photo Hover Zoom */
    .photo-card:hover .photo-img {
        transform: scale(1.1);
    }
    .photo-img {
        transition: transform 0.4s ease;
    }
    
    /* Deadline Progress Bar */
    .deadline-progress {
        transition: width 0.5s ease;
    }
    
    /* Pulse Animation for Urgent */
    @keyframes urgent-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    .urgent-badge {
        animation: urgent-pulse 2s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div class="p-4 md:p-8">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                Dashboard Manager
            </h1>
            <p class="text-gray-400 mt-1">Ringkasan aktivitas dan monitoring seluruh divisi</p>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('tasks.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition border border-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Semua Tugas
            </a>
            <a href="{{ route('dashboard.gallery') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Kelola Gallery
            </a>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        
        <!-- Total Tugas -->
        <div class="stat-card bg-gray-700/60 p-5 rounded-xl border border-gray-600 animate-fade-in delay-1">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <span class="text-xs text-gray-500">Total</span>
            </div>
            <p class="text-3xl font-bold text-white">{{ $totalTasks }}</p>
            <p class="text-xs text-gray-400 mt-1">Total Tugas</p>
        </div>
        
        <!-- Tugas Aktif -->
        <div class="stat-card bg-gray-700/60 p-5 rounded-xl border border-gray-600 animate-fade-in delay-2">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="text-xs text-emerald-400 font-medium">
                    {{ $totalTasks > 0 ? round(($activeTasks / $totalTasks) * 100) : 0 }}%
                </span>
            </div>
            <p class="text-3xl font-bold text-emerald-400">{{ $activeTasks }}</p>
            <p class="text-xs text-gray-400 mt-1">Tugas Aktif</p>
        </div>
        
        <!-- Tugas Terlewat -->
        <div class="stat-card bg-gray-700/60 p-5 rounded-xl border border-gray-600 animate-fade-in delay-3">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-red-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs text-red-400 font-medium">
                    {{ $totalTasks > 0 ? round(($expiredTasks / $totalTasks) * 100) : 0 }}%
                </span>
            </div>
            <p class="text-3xl font-bold text-red-400">{{ $expiredTasks }}</p>
            <p class="text-xs text-gray-400 mt-1">Tugas Terlewat</p>
        </div>
        
        <!-- Jumlah Ketua -->
        <div class="stat-card bg-gray-700/60 p-5 rounded-xl border border-gray-600 animate-fade-in delay-4">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-purple-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-xs text-gray-500">Divisi</span>
            </div>
            <p class="text-3xl font-bold text-purple-400">{{ $totalKetua }}</p>
            <p class="text-xs text-gray-400 mt-1">Ketua Divisi</p>
        </div>
    </div>

    <!-- Mendekati Deadline (FULL WIDTH) -->
    <div class="bg-gray-700/60 rounded-xl border border-gray-600 overflow-hidden mb-8 animate-fade-in delay-3">
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-gray-600">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Mendekati Deadline
            </h2>
            <a href="{{ route('tasks.index', ['status' => 'active']) }}" 
               class="text-xs text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1">
                Lihat Semua
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <!-- Content -->
        <div class="divide-y divide-gray-600">
            @forelse($upcomingDeadlines as $task)
            @php
                $hoursLeft = now()->diffInHours($task->deadline_at, false);
                $daysLeft = floor($hoursLeft / 24);
                $isUrgent = $hoursLeft < 48;
                $isCritical = $hoursLeft < 24;
            @endphp
            <div class="p-4 hover:bg-gray-700/80 transition group">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-white font-medium truncate group-hover:text-emerald-400 transition">
                                {{ $task->title }}
                            </h3>
                            @if($isUrgent)
                            <span class="urgent-badge px-2 py-0.5 bg-red-900/40 text-red-400 text-[10px] rounded-full border border-red-700/50">
                                URGENT
                            </span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                {{ $task->course_name }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ $task->user->name }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Countdown -->
                    <div class="text-right flex-shrink-0">
                        <div class="text-sm font-bold {{ $isCritical ? 'text-red-500' : ($isUrgent ? 'text-orange-400' : 'text-blue-400') }}">
                            @if($isCritical)
                                ️ {{ $hoursLeft }} jam
                            @elseif($daysLeft === 1)
                                Besok
                            @elseif($daysLeft <= 7)
                                {{ $daysLeft }} hari
                            @else
                                {{ $task->deadline_at->diffForHumans(['parts' => 1]) }}
                            @endif
                        </div>
                        <div class="text-[10px] text-gray-500">
                            {{ $task->deadline_at->format('d M, H:i') }}
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                @php
                    $totalDuration = $task->starts_at->diffInHours($task->deadline_at);
                    $elapsed = $task->starts_at->diffInHours(now());
                    $progress = $totalDuration > 0 ? min(100, max(0, ($elapsed / $totalDuration) * 100)) : 0;
                @endphp
                <div class="mt-3 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                    <div class="deadline-progress h-full rounded-full {{ $isUrgent ? 'bg-red-500' : 'bg-orange-500' }}" 
                         style="width: {{ $progress }}%"></div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-400 text-sm">Tidak ada tugas mendekati deadline</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Tugas Terbaru -->
    <div class="bg-gray-700/60 rounded-xl border border-gray-600 overflow-hidden mb-8 animate-fade-in delay-4">
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-gray-600">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Tugas Terbaru
            </h2>
            <a href="{{ route('tasks.index') }}" 
               class="text-xs text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1">
                Lihat Semua
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <!-- Task Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800/80 text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Tugas</th>
                        <th class="px-4 py-3 text-left font-medium hidden md:table-cell">Mata Kuliah</th>
                        <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">Ketua</th>
                        <th class="px-4 py-3 text-left font-medium">Deadline</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-600">
                    @forelse($latestTasks as $task)
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
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white text-[10px] font-bold">
                                    {{ strtoupper(substr($task->user->name, 0, 1)) }}
                                </div>
                                {{ $task->user->name }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-300 whitespace-nowrap">
                            {{ $task->deadline_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($task->is_expired)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-900/40 text-red-300 text-xs rounded-full border border-red-700/50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Expired
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-900/40 text-emerald-300 text-xs rounded-full border border-emerald-700/50">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Active
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                            Belum ada tugas yang ditambahkan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 animate-fade-in delay-5">
        
        <!-- Completion Rate -->
        <div class="bg-gradient-to-br from-emerald-900/40 to-gray-800 p-5 rounded-xl border border-emerald-700/30">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-white font-semibold">Tingkat Penyelesaian</h3>
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            @php
                $completionRate = $totalTasks > 0 ? round(($expiredTasks / $totalTasks) * 100) : 0;
            @endphp
            <div class="flex items-end gap-2">
                <span class="text-3xl font-bold text-emerald-400">{{ $completionRate }}%</span>
                <span class="text-xs text-gray-400 mb-1">selesai</span>
            </div>
            <div class="mt-3 h-2 bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full transition-all duration-500" 
                     style="width: {{ $completionRate }}%"></div>
            </div>
        </div>
        
        <!-- Active Divisions -->
        <div class="bg-gradient-to-br from-purple-900/40 to-gray-800 p-5 rounded-xl border border-purple-700/30">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-white font-semibold">Divisi Aktif</h3>
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-bold text-purple-400">{{ $totalKetua }}</span>
                <span class="text-xs text-gray-400 mb-1">divisi</span>
            </div>
            <p class="text-xs text-gray-400 mt-3">
                {{ count(config('roles.courses')) }} mata kuliah terdaftar
            </p>
        </div>
        
        <!-- Gallery Stats -->
        <div class="bg-gradient-to-br from-blue-900/40 to-gray-800 p-5 rounded-xl border border-blue-700/30">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-white font-semibold">Gallery</h3>
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="flex items-end gap-2">
                <span class="text-3xl font-bold text-blue-400">{{ \App\Models\Gallery::count() }}</span>
                <span class="text-xs text-gray-400 mb-1">total foto</span>
            </div>
            <p class="text-xs text-gray-400 mt-3">
                {{ $latestPhotos->count() }} foto terbaru ditambahkan
            </p>
        </div>
    </div>

    <!-- ✅ FOTO TERBARU (DIPINDAH KE PALING BAWAH) -->
    <div class="bg-gray-700/60 rounded-xl border border-gray-600 overflow-hidden animate-fade-in delay-6">
        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b border-gray-600">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Foto Terbaru
            </h2>
            <a href="{{ route('dashboard.gallery') }}" 
               class="text-xs text-emerald-400 hover:text-emerald-300 transition flex items-center gap-1">
                Kelola Gallery
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <!-- Photo Grid -->
        <div class="p-5">
            @if($latestPhotos->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                    @foreach($latestPhotos as $photo)
                    <div class="photo-card group relative aspect-square rounded-lg overflow-hidden bg-gray-800 cursor-pointer"
                         onclick="window.location='{{ route('dashboard.gallery') }}'">
                        <img src="{{ asset('storage/' . $photo->image) }}" 
                             alt="{{ $photo->title }}"
                             loading="lazy"
                             class="photo-img w-full h-full object-cover">
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                            <p class="text-white text-xs font-medium truncate w-full">{{ $photo->title }}</p>
                        </div>
                        
                        <!-- Date Badge -->
                        <span class="absolute top-2 right-2 px-2 py-0.5 bg-black/50 text-white text-[10px] rounded-md backdrop-blur-sm">
                            {{ $photo->created_at->format('d M') }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-white font-semibold mb-1">Belum ada foto</h3>
                    <p class="text-gray-400 text-sm mb-4">Mulai koleksi dokumentasi dengan mengupload foto pertama</p>
                    <a href="{{ route('dashboard.gallery') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Upload Foto
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection