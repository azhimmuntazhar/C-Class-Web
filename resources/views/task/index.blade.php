@extends('layouts.sidebar')

@section('title', 'Daftar Tugas')

@push('styles')
<style>
    /* Task Card Hover Effect */
    .task-card {
        transition: all 0.2s ease;
    }
    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    /* Staggered Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .task-card {
        animation: fadeInUp 0.4s ease-out forwards;
        opacity: 0;
    }
    .task-card:nth-child(1) { animation-delay: 0.05s; }
    .task-card:nth-child(2) { animation-delay: 0.1s; }
    .task-card:nth-child(3) { animation-delay: 0.15s; }
    .task-card:nth-child(4) { animation-delay: 0.2s; }
    .task-card:nth-child(5) { animation-delay: 0.25s; }
    .task-card:nth-child(n+6) { animation-delay: 0.3s; }
    
    /* Action Button Hover */
    .action-btn {
        transition: all 0.15s ease;
    }
    .action-btn:hover {
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="p-4 md:p-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white">
                @if(auth()->user()->role === 'admin')
                    Semua Tugas
                @elseif(auth()->user()->role === 'manager')
                    Monitoring Tugas
                @else
                    Tugas {{ config('roles.list.' . auth()->user()->role) ?? ucfirst(auth()->user()->role) }}
                @endif
            </h1>
            <p class="text-gray-400 mt-1">
                @if(auth()->user()->role === 'admin')
                    Kelola semua tugas dari seluruh divisi
                @elseif(auth()->user()->role === 'manager')
                    Pantau tugas dari semua Ketua divisi
                @else
                    Daftar tugas untuk mata kuliah Anda
                @endif
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3">
            <!-- Filter Toggle -->
            <div class="flex bg-gray-700/60 p-1 rounded-lg border border-gray-600">
                <a href="{{ route('tasks.index', ['status' => 'active']) }}" 
                   class="px-4 py-2 rounded-md text-sm font-medium transition {{ request('status', 'active') === 'active' ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-white' }}">
                     Aktif
                </a>
                <a href="{{ route('tasks.index', ['status' => 'expired']) }}" 
                   class="px-4 py-2 rounded-md text-sm font-medium transition {{ request('status') === 'expired' ? 'bg-red-600 text-white' : 'text-gray-400 hover:text-white' }}">
                     Terlewat
                </a>
            </div>
            
            <!-- Create Button (Only for Ketua & Admin) -->
            @if(in_array(auth()->user()->role, ['admin']) || str_starts_with(auth()->user()->role, 'ketua_'))
            <a href="{{ route('tasks.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition shadow-md hover:shadow-lg active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tugas Baru
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards (Admin & Manager Only) -->
    @if(in_array(auth()->user()->role, ['admin', 'manager']))
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        
        <!-- Total Tugas (Selalu Netral) -->
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Total Tugas</p>
            <p class="text-2xl font-bold text-white mt-1">{{ $totalAll }}</p>
        </div>
        
        <!-- Tugas Aktif (Berubah Background jika filter 'active' aktif) -->
        <div class="p-4 rounded-xl border transition-all duration-300 {{ $status === 'active' ? 'bg-emerald-900/40 border-emerald-700/50' : 'bg-gray-700/60 border-gray-600' }}">
            <p class="{{ $status === 'active' ? 'text-emerald-200/80' : 'text-gray-400' }} text-xs uppercase transition-colors duration-300">Tugas Aktif</p>
            <p class="text-2xl font-bold text-emerald-400 mt-1">{{ $totalActive }}</p>
        </div>
        
        <!-- Terlewat (Berubah Background jika filter 'expired' aktif) -->
        <div class="p-4 rounded-xl border transition-all duration-300 {{ $status === 'expired' ? 'bg-red-900/40 border-red-700/50' : 'bg-gray-700/60 border-gray-600' }}">
            <p class="{{ $status === 'expired' ? 'text-red-200/80' : 'text-gray-400' }} text-xs uppercase transition-colors duration-300">Terlewat</p>
            <p class="text-2xl font-bold text-red-400 mt-1">{{ $totalExpired }}</p>
        </div>

    </div>
    @endif

    <!-- Search & Filter (Desktop) -->
    <div class="hidden sm:flex flex-wrap items-center gap-4 mb-6 bg-gray-700/60 p-4 rounded-xl border border-gray-600">
        <!-- Search -->
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" id="searchDesktop" placeholder="Cari judul tugas..." 
                   class="w-full px-4 py-2.5 pl-10 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        
        <!-- Filter by Course (Admin/Manager) -->
        @if(in_array(auth()->user()->role, ['admin', 'manager']))
        <select id="filterCourse" class="px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            <option value="">Semua Mata Kuliah</option>
            @foreach(config('roles.courses') as $key => $name)
                <option value="{{ $key }}">{{ $name }}</option>
            @endforeach
        </select>
        @endif
        
        <!-- Reset Button -->
        <button onclick="resetFilters()" 
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-500 text-white text-sm font-medium rounded-lg transition border border-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Reset
        </button>
    </div>

    <!-- Task List -->
    @if($tasks->count() === 0)
        <!-- Empty State -->
        <div class="text-center py-16 bg-gray-700/60 rounded-2xl border border-gray-600">
            <svg class="w-20 h-20 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <h3 class="text-xl font-semibold text-white mb-2">Belum ada tugas</h3>
            <p class="text-gray-400 mb-6">
                @if(request('status') === 'expired')
                    Tidak ada tugas yang terlewat deadline
                @else
                    Belum ada tugas aktif untuk ditampilkan
                @endif
            </p>
            
            @if(in_array(auth()->user()->role, ['admin']) || str_starts_with(auth()->user()->role, 'ketua_'))
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Tugas Pertama
            </a>
            @endif
        </div>
    @else
        <!-- Task Cards -->
        <div class="space-y-4" id="taskList">
            @foreach($tasks as $task)
            @php
                // Permission Check untuk Edit
                $canEdit = false;
                if (in_array(auth()->user()->role, ['admin', 'manager'])) {
                    $canEdit = true;
                } elseif (str_starts_with(auth()->user()->role, 'ketua_')) {
                    $userCourse = config("roles.course_mapping." . auth()->user()->role);
                    $canEdit = ($task->course_key === $userCourse);
                }
                
                // Permission Check untuk Delete
                $canDelete = (auth()->id() === $task->user_id || in_array(auth()->user()->role, ['admin', 'manager']));
            @endphp
            
            <div class="task-card bg-gray-700/60 rounded-xl border border-gray-600 p-5 hover:border-emerald-500/30 transition group"
                 data-course="{{ $task->course_key }}"
                 data-title="{{ strtolower($task->title) }}">
                
                <!-- Header: Course + Status -->
                <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Course Badge -->
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-900/30 text-emerald-400 text-xs font-medium rounded-md border border-emerald-800/30">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            {{ $task->course_name }}
                        </span>
                        
                        <!-- Category Badge -->
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 {{ $task->category === 'kelompok' ? 'bg-blue-900/30 text-blue-400 border-blue-800/30' : 'bg-purple-900/30 text-purple-400 border-purple-800/30' }} text-xs font-medium rounded-md border">
                            @if($task->category === 'kelompok')
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            @else
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            @endif
                            {{ ucfirst($task->category) }}
                        </span>
                    </div>
                    
                    <!-- Status Badge -->
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
                
                <!-- Title & Description -->
                <h3 class="text-lg font-semibold text-white group-hover:text-emerald-400 transition mb-2">
                    {{ $task->title }}
                </h3>
                <p class="text-gray-400 text-sm mb-4 line-clamp-2 leading-relaxed">
                    {{ Str::limit(strip_tags($task->description), 150) }}
                </p>
                
                <!-- External Links -->
                @if($task->material_link || $task->submission_link)
                <div class="flex flex-wrap gap-4 mb-4">
                    @if($task->material_link)
                        <a href="{{ $task->material_link }}" target="_blank" 
                           class="text-xs text-blue-400 hover:text-blue-300 hover:underline flex items-center gap-1 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Materi Pembelajaran
                        </a>
                    @endif
                    @if($task->submission_link)
                        <a href="{{ $task->submission_link }}" target="_blank" 
                           class="text-xs text-emerald-400 hover:text-emerald-300 hover:underline flex items-center gap-1 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Link Pengumpulan
                        </a>
                    @endif
                </div>
                @endif
                
                <!-- Footer: Timeline + Creator + Actions -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pt-4 border-t border-gray-600/50">
                    
                    <!-- Timeline & Creator -->
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-gray-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Mulai: <strong class="text-gray-300">{{ $task->starts_at->format('d M Y, H:i') }}</strong>
                        </span>
                        <span class="flex items-center gap-1.5 {{ $task->is_expired ? 'text-red-400' : 'text-emerald-400' }}">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Deadline: <strong>{{ $task->deadline_at->format('d M Y, H:i') }}</strong>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Oleh: <strong class="text-gray-300">{{ $task->user->name }}</strong>
                        </span>
                    </div>
                    
                    <!-- Actions (Edit & Delete) -->
                    @if($canEdit || $canDelete)
                    <div class="flex items-center gap-2">
                        @if($canEdit)
                        <a href="{{ route('tasks.edit', $task) }}" 
                           class="action-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-400 hover:text-blue-300 hover:bg-blue-900/20 rounded-lg transition border border-blue-800/30">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        @endif
                        
                        @if($canDelete)
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="action-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-lg transition border border-red-800/30">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Search & Filter
    const searchInput = document.getElementById('searchDesktop');
    const filterCourse = document.getElementById('filterCourse');
    const taskCards = document.querySelectorAll('.task-card');
    
    function filterTasks() {
        const query = (searchInput?.value || '').toLowerCase().trim();
        const courseFilter = filterCourse?.value || '';
        
        taskCards.forEach(card => {
            const title = card.dataset.title || '';
            const course = card.dataset.course || '';
            
            const matchesSearch = !query || title.includes(query);
            const matchesCourse = !courseFilter || course === courseFilter;
            
            if (matchesSearch && matchesCourse) {
                card.style.display = '';
                card.style.opacity = '1';
            } else {
                card.style.opacity = '0';
                setTimeout(() => {
                    if (!matchesSearch || !matchesCourse) {
                        card.style.display = 'none';
                    }
                }, 200);
            }
        });
    }
    
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (filterCourse) filterCourse.value = '';
        filterTasks();
    }
    
    searchInput?.addEventListener('input', filterTasks);
    filterCourse?.addEventListener('change', filterTasks);
    
    // Delete Confirmation with SweetAlert
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Hapus tugas ini?',
                text: "Tugas yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

@endsection