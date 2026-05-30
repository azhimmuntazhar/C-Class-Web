<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Tugas - Informatika CFI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Modal Animation - Sama dengan welcome.blade.php */
        #taskModal {
            transition: opacity 0.3s ease;
        }
        #taskModal.hidden {
            opacity: 0;
            pointer-events: none;
        }

        /* Custom scrollbar untuk modal */
        #taskModal ::-webkit-scrollbar {
            width: 6px;
        }
        #taskModal ::-webkit-scrollbar-track {
            background: rgba(55, 65, 81, 0.3);
            border-radius: 3px;
        }
        #taskModal ::-webkit-scrollbar-thumb {
            background: rgba(75, 85, 99, 0.8);
            border-radius: 3px;
        }
        #taskModal ::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 114, 128, 1);
        }

        /* Prevent body scroll when modal is open */
        body.modal-open {
            overflow: hidden;
        }

        /* iOS smooth scrolling */
        #taskModal .overflow-y-auto {
            -webkit-overflow-scrolling: touch;
        }
        /* Global Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(31,41,55,0.4); border-radius: 3px; }
        ::-webkit-scrollbar-thumb { background: rgba(75,85,99,0.8); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(107,114,128,1); }
        html { scrollbar-width: thin; scrollbar-color: rgba(75,85,99,0.8) rgba(31,41,55,0.4); }
        
        html { scroll-behavior: smooth; }
        body { padding-top: 72px; }
        
        .nav-underline { position: relative; display: inline-block; }
        .nav-underline::after {
            content: ''; position: absolute; left: 0; bottom: -3px; width: 100%; height: 4px;
            background-color: #10b981; border-radius: 9999px; transform: scaleX(0);
            transform-origin: right; transition: transform 0.3s ease-in-out;
        }
        .nav-underline:hover::after { transform: scaleX(1); transform-origin: left; }
        
        /* Calendar Styles */
        .calendar-day {
            min-height: 100px;
            transition: all 0.2s;
        }
        .calendar-day:hover {
            background-color: rgba(55, 65, 81, 0.5);
        }
        .calendar-day.today {
            background-color: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .calendar-day.other-month {
            opacity: 0.4;
        }
        
        /* Task Badge Animation */
        .task-badge {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .calendar-day { min-height: 60px; }
            .task-badge-mobile { font-size: 0.65rem; padding: 0.1rem 0.3rem; }
        }
    </style>
</head>
<body class="bg-gray-800 min-h-screen flex flex-col">

    <!-- TOP NAVBAR (Fixed) -->
    <nav class="fixed top-0 left-0 right-0 h-16 bg-gray-800/80 backdrop-blur-md text-white shadow-lg z-50 flex items-center justify-between px-4 md:px-20 transition-all duration-300 border-b border-gray-700/50">
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
            <a href="{{ route('tasks.public') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('tasks.public') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">Task</a>
            <a href="{{ route('galeri') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('galeri') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">Gallery</a>
            <a href="{{ route('about') }}" class="nav-underline px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('about') ? 'text-white active' : 'text-gray-300 hover:text-white' }}">About</a>
        </div>
    </nav>

    <!-- MOBILE MENU -->
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
            <a href="{{ route('tasks.public') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('tasks.public') ? 'bg-emerald-600' : '' }}">Task</a>
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
            <a href="{{ route('tasks.public') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('tasks.public') ? 'bg-emerald-600' : '' }}">
                Task
            </a>
            <a href="{{ route('galeri') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('galeri') ? 'bg-emerald-600' : '' }}">
                Gallery
            </a>            
            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700 transition {{ request()->routeIs('login') ? 'bg-emerald-600' : '' }}">
                Login
            </a>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-4 md:p-6">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">Kalender Tugas</h1>
                    <p class="text-gray-400 text-sm mt-1">Kelola dan lihat jadwal tugas</p>
                </div>
                
                <!-- Filter Toggle -->
                <div class="flex bg-gray-700/60 p-1 rounded-lg border border-gray-600">
                    <a href="{{ route('tasks.public', ['status' => 'active']) }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium transition {{ $status === 'active' ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-white' }}">
                        Aktif
                    </a>
                    <a href="{{ route('tasks.public', ['status' => 'expired']) }}" 
                       class="px-4 py-2 rounded-md text-sm font-medium transition {{ $status === 'expired' ? 'bg-red-600 text-white' : 'text-gray-400 hover:text-white' }}">
                        Terlewat
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                
                <!-- SIDEBAR -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Mini Calendar -->
                    <div class="hidden lg:block bg-gray-700/60 rounded-xl border border-gray-600 p-4">
                        <div class="flex items-center justify-between mb-4">
                            <button onclick="changeMonth(-1)" class="p-1 hover:bg-gray-700 rounded text-gray-400 hover:text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <h3 class="font-semibold text-white" id="miniCalendarMonth">April 2026</h3>
                            <button onclick="changeMonth(1)" class="p-1 hover:bg-gray-700 rounded text-gray-400 hover:text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-xs mb-2">
                            <span class="text-gray-500">Min</span>
                            <span class="text-gray-500">Sen</span>
                            <span class="text-gray-500">Sel</span>
                            <span class="text-gray-500">Rab</span>
                            <span class="text-gray-500">Kam</span>
                            <span class="text-gray-500">Jum</span>
                            <span class="text-gray-500">Sab</span>
                        </div>
                        <div class="grid grid-cols-7 gap-1" id="miniCalendarDays">
                            <!-- Generated by JS -->
                        </div>
                    </div>

                    <!--TUGAS AKTIF (Replacing "Tugas Hari Ini") -->
                    <div class="bg-gray-700/60 rounded-xl border border-gray-600 p-3">
                        <h3 class="font-semibold text-white mb-3 flex items-center gap-2">
                            @if($status === 'active')
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Tugas Aktif
                            @else
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Tugas Terlewat
                            @endif
                        </h3>
                        
                        <div class="space-y-2 max-h-96 overflow-y-auto">
                            {{-- $tasks sudah difilter otomatis oleh controller sesuai $status --}}
                            @forelse($tasks->take(5) as $task)
                            <div class="p-2 bg-gray-800 rounded-lg border border-gray-600 hover:border-emerald-500/50 transition cursor-pointer" onclick="openTaskModal({{ $task->id }})">
                                <p class="text-white text-sm font-medium truncate">{{ $task->title }}</p>
                                <p class="text-gray-400 text-xs">{{ $task->course_name }}</p>
                                <div class="flex items-center gap-1 mt-1 text-xs {{ $status === 'active' ? 'text-emerald-400' : 'text-red-400' }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @if($status === 'active')
                                        {{ $task->deadline_at->diffForHumans() }}
                                    @else
                                        Lewat {{ $task->deadline_at->diffForHumans() }}
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-sm text-center py-4">
                                Tidak ada tugas {{ $status === 'active' ? 'aktif' : 'terlewat' }}
                            </p>
                            @endforelse
                            
                            @if($tasks->count() > 5)
                            <p class="text-gray-400 text-xs text-center mt-2">+{{ $tasks->count() - 5 }} lainnya</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- MAIN CALENDAR -->
                <div class="lg:col-span-3">
                    <div class="bg-gray-700/60 rounded-xl border border-gray-600 p-4 md:p-6">
                        <!-- Calendar Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-white" id="calendarTitle">April 2026</h2>
                            <div class="flex gap-2">
                                <button onclick="changeMonth(-1)" class="p-2 hover:bg-gray-700 rounded-lg text-gray-400 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <button onclick="changeMonth(1)" class="p-2 hover:bg-gray-700 rounded-lg text-gray-400 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="grid grid-cols-7 gap-px bg-gray-700 border border-gray-600 rounded-lg overflow-hidden">
                            <!-- Day Headers -->
                            <div class="bg-gray-800 p-2 text-center text-xs font-medium text-gray-400">Minggu</div>
                            <div class="bg-gray-800 p-2 text-center text-xs font-medium text-gray-400">Senin</div>
                            <div class="bg-gray-800 p-2 text-center text-xs font-medium text-gray-400">Selasa</div>
                            <div class="bg-gray-800 p-2 text-center text-xs font-medium text-gray-400">Rabu</div>
                            <div class="bg-gray-800 p-2 text-center text-xs font-medium text-gray-400">Kamis</div>
                            <div class="bg-gray-800 p-2 text-center text-xs font-medium text-gray-400">Jumat</div>
                            <div class="bg-gray-800 p-2 text-center text-xs font-medium text-gray-400">Sabtu</div>

                            <!-- Calendar Days -->
                            @php
                                // Pastikan kita menggunakan timezone yang konsisten (sesuaikan dengan config/app.php)
                                $appTimezone = config('app.timezone', 'Asia/Jakarta');
                                
                                $currentDate = now($appTimezone);
                                $daysInMonth = $currentDate->daysInMonth;
                                $firstDayOfMonth = $currentDate->copy()->startOfMonth();
                                $startingDay = $firstDayOfMonth->dayOfWeek;
                                
                                $prevMonthDays = $firstDayOfMonth->copy()->subDays($startingDay);
                            @endphp

                            @for ($i = 0; $i < 42; $i++)
                                @php
                                    $dayDate = $prevMonthDays->copy()->addDays($i);
                                    $dayNum = $dayDate->day;
                                    $isToday = $dayDate->isToday();
                                    $isCurrentMonth = $dayDate->month == $currentDate->month;
                                    
                                    // FIX: Bandingkan tanggal dengan timezone yang sama
                                    $dayTasks = $tasks->filter(function($task) use ($dayDate, $appTimezone) {
                                        $taskDate = $task->deadline_at->copy()->setTimezone($appTimezone)->toDateString();
                                        $calendarDate = $dayDate->copy()->setTimezone($appTimezone)->toDateString();
                                        return $taskDate === $calendarDate;
                                    });
                                @endphp
                                
                                <div class="calendar-day bg-gray-800 p-2 {{ $isToday ? 'today' : '' }} {{ !$isCurrentMonth ? 'other-month' : '' }} min-h-[100px] md:min-h-[120px]">
                                    <span class="text-sm font-medium {{ $isToday ? 'text-emerald-400' : ($isCurrentMonth ? 'text-white' : 'text-gray-500') }}">
                                        {{ $dayNum }}
                                    </span>
                                    
                                    <!-- Task Badges -->
                                    <div class="mt-1 space-y-1">
                                        @foreach($dayTasks->take(3) as $task)
                                        <div class="task-badge p-1 rounded text-[10px] md:text-xs truncate cursor-pointer hover:opacity-80 transition {{ $task->is_expired ? 'bg-red-900/40 text-red-300 border border-red-700/50' : 'bg-emerald-900/40 text-emerald-300 border border-emerald-700/50' }}" onclick="openTaskModal({{ $task->id }})">
                                            {{ Str::limit($task->title, 20) }}
                                        </div>
                                        @endforeach
                                        @if($dayTasks->count() > 3)
                                        <div class="text-gray-500 text-[10px] pl-1">+{{ $dayTasks->count() - 3 }} lainnya</div>
                                        @endif
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- TASK DETAIL MODAL -->
    <div id="taskModal" class="fixed inset-0 z-[80] hidden flex items-end sm:items-center justify-center">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/70 backdrop-blur-md opacity-0 transition-opacity duration-300" onclick="closeTaskModal()"></div>

        <!-- Modal Content -->
        <div class="relative w-full sm:max-w-2xl mx-0 sm:mx-4 bg-gray-800/60 backdrop-blur-md sm:rounded-2xl rounded-t-2xl border-0 sm:border border-gray-700 shadow-2xl flex flex-col overflow-hidden max-h-[90vh] sm:max-h-[85vh] opacity-0 translate-y-full sm:translate-y-4 sm:scale-95 transition-all duration-300 ease-out will-change-transform,opacity">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-700 bg-gray-800 flex-shrink-0">
                <h3 class="text-lg font-bold text-white">Detail Tugas</h3>
                <button onclick="closeTaskModal()" class="text-gray-400 hover:text-white transition p-2 hover:bg-gray-700 rounded-lg -mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Body (Scrollable) -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4">
                <!-- Status & Course -->
                <div class="flex flex-wrap gap-2">
                    <span id="modalStatus" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border"></span>
                    <span id="modalCourse" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-900/30 text-emerald-400 text-sm font-medium rounded-lg border border-emerald-800/30"></span>
                </div>

                <!-- Title -->
                <h2 id="modalTitle" class="text-xl font-bold text-white leading-tight"></h2>

                <!-- Category -->
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 text-sm">Kategori:</span>
                    <span id="modalCategory" class="px-2.5 py-1 rounded-md text-xs font-medium border"></span>
                </div>

                <!-- Description -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        Deskripsi Tugas
                    </h4>
                    <div id="modalDescription" class="bg-gray-700/50 rounded-lg p-4 text-gray-300 text-sm leading-relaxed border border-gray-600 max-h-48 overflow-y-auto"></div>
                </div>

                <!-- Timeline -->
                <div class="space-y-3">
                    <div class="bg-gray-700/30 rounded-lg p-3 border border-gray-600">
                        <div class="flex items-center gap-2 text-gray-400 text-xs mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Mulai
                        </div>
                        <p id="modalStarts" class="text-white text-sm font-medium"></p>
                    </div>
                    <div class="bg-gray-700/30 rounded-lg p-3 border border-gray-600">
                        <div class="flex items-center gap-2 text-orange-300 text-xs mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="orange" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Deadline
                        </div>
                        <p id="modalDeadline" class="text-white text-sm font-medium"></p>
                    </div>
                </div>

                <!-- Creator Info -->
                <div class="flex items-center gap-3 p-3 bg-gray-700/30 rounded-lg border border-gray-600">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        <span id="modalCreatorInitial"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-medium truncate" id="modalCreator"></p>
                        <p class="text-gray-400 text-xs truncate" id="modalRole"></p>
                    </div>
                </div>

                <!-- Links -->
                <div id="modalLinks" class="space-y-2">
                    <h4 class="text-sm font-semibold text-gray-300 mb-2">Link Terkait</h4>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-700 bg-gray-800 p-4 flex-shrink-0">
                <button onclick="closeTaskModal()" class="w-full px-5 py-3 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-gray-900 border-t border-gray-800 py-8 mt-auto">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="flex justify-center items-center gap-2 mb-4">
                <span class="text-emerald-500 font-bold text-xl">❯</span>
                <span class="text-white font-semibold">Informatika CFI</span>
            </div>
            <p class="text-gray-400 text-sm mb-4">
                Platform manajemen tugas & doksli untuk mahasiswa Informatika.
            </p>
            <div class="flex justify-center item-center gap-2 mb-6">
                <a class="text-gray-400 hover:text-emerald-400 text-sm transition">Dibuat dengan</a>
                <a class="text-red-500 text-lg leading-none hover:scale-110 transition flex items-center">❤️</a>
                <a class="text-gray-400 hover:text-emerald-400 text-sm transition">
                    oleh Engginer</a>
            </div>
            <p class="text-gray-500 text-xs">
                &copy; {{ date('Y') }} Informatika CFI. All rights reserved.
            </p>
        </div>
    </footer>

    <script>
        // Task Data
        const tasksData = @json($tasks);
        let currentDate = new Date();

        // Initialize Mini Calendar
        function initMiniCalendar() {
            const daysContainer = document.getElementById('miniCalendarDays');
            const monthLabel = document.getElementById('miniCalendarMonth');
            
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            monthLabel.textContent = new Date(year, month).toLocaleString('id-ID', { month: 'long', year: 'numeric' });
            
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            daysContainer.innerHTML = '';
            
            for (let i = 0; i < firstDay; i++) {
                daysContainer.innerHTML += '<div></div>';
            }
            
            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = new Date().toDateString() === new Date(year, month, day).toDateString();
                const hasTasks = tasksData.some(t => {
                    const taskDate = new Date(t.deadline_at);
                    return taskDate.getDate() === day && taskDate.getMonth() === month;
                });
                
                daysContainer.innerHTML += `
                    <div class="p-1 text-center text-sm rounded ${isToday ? 'bg-emerald-600 text-white' : 'text-gray-300 hover:bg-gray-700'} cursor-pointer relative">
                        ${day}
                        ${hasTasks ? '<span class="absolute bottom-0.5 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-emerald-400 rounded-full"></span>' : ''}
                    </div>
                `;
            }
        }

        function changeMonth(delta) {
            currentDate.setMonth(currentDate.getMonth() + delta);
            initMiniCalendar();
            updateCalendarTitle();
        }

        function updateCalendarTitle() {
            const title = document.getElementById('calendarTitle');
            title.textContent = currentDate.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
        }

        function openTaskModal(taskId) {
            const task = tasksData.find(t => t.id === taskId);
            if (!task) return;

            // Populate Data
            document.getElementById('modalTitle').textContent = task.title;
            document.getElementById('modalDescription').innerHTML = task.description.replace(/\n/g, '<br>');
            document.getElementById('modalCourse').innerHTML = `📚 ${task.course_name}`;
            document.getElementById('modalStarts').textContent = new Date(task.starts_at).toLocaleString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            document.getElementById('modalDeadline').textContent = new Date(task.deadline_at).toLocaleString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
            document.getElementById('modalCreator').textContent = task.user.name || 'Unknown';
            document.getElementById('modalCreatorInitial').textContent = (task.user.name || 'U').charAt(0).toUpperCase();
            document.getElementById('modalRole').textContent = window.configRoles?.[task.user.role] || task.user.role.charAt(0).toUpperCase() + task.user.role.slice(1);

            // Status Badge
            const isExpired = new Date(task.deadline_at) < new Date();
            const statusEl = document.getElementById('modalStatus');
            statusEl.className = `inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border ${isExpired ? 'bg-red-900/30 text-red-400 border-red-800/30' : 'bg-emerald-900/30 text-emerald-400 border-emerald-800/30'}`;
            statusEl.innerHTML = isExpired ? '⏳ Deadline Lewat' : '✅ Aktif';

            // Category Badge
            const categoryEl = document.getElementById('modalCategory');
            categoryEl.className = `px-2.5 py-1 rounded-md text-xs font-medium border ${task.category === 'kelompok' ? 'bg-blue-900/30 text-blue-400 border-blue-800/30' : 'bg-purple-900/30 text-purple-400 border-purple-800/30'}`;
            categoryEl.innerHTML = task.category === 'kelompok' ? '👥 Kelompok' : '👤 Individu';

            // Links
            const linksContainer = document.getElementById('modalLinks');
            linksContainer.innerHTML = '<h4 class="text-sm font-semibold text-gray-300 mb-2">Link Terkait</h4>';
            if (task.material_link) {
                linksContainer.innerHTML += `<a href="${task.material_link}" target="_blank" class="flex items-center gap-2 text-blue-400 hover:text-blue-300 text-sm transition p-2 rounded-lg hover:bg-blue-900/20"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>Materi Pembelajaran</a>`;
            }
            if (task.submission_link) {
                linksContainer.innerHTML += `<a href="${task.submission_link}" target="_blank" class="flex items-center gap-2 text-emerald-400 hover:text-emerald-300 text-sm transition p-2 rounded-lg hover:bg-emerald-900/20"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>Link Pengumpulan</a>`;
            }
            if (!task.material_link && !task.submission_link) {
                linksContainer.innerHTML += '<p class="text-gray-500 text-sm">Tidak ada link tersedia</p>';
            }

            // Trigger Animation
            const modal = document.getElementById('taskModal');
            const backdrop = modal.querySelector('.absolute.inset-0');
            const content = modal.querySelector('.relative.w-full');

            modal.classList.remove('hidden');
            document.body.classList.add('modal-open');

            // Force reflow
            void modal.offsetWidth;

            backdrop.classList.remove('opacity-0');
            content.classList.remove('opacity-0', 'translate-y-full', 'sm:translate-y-4', 'sm:scale-95');
            content.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }

        function closeTaskModal() {
            const modal = document.getElementById('taskModal');
            const backdrop = modal.querySelector('.absolute.inset-0');
            const content = modal.querySelector('.relative.w-full');

            // Reverse animation
            backdrop.classList.add('opacity-0');
            content.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            content.classList.add('opacity-0', 'translate-y-full', 'sm:translate-y-4', 'sm:scale-95');

            // Wait for transition
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }, 300);
        }
        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeTaskModal();
        });

        // Pass role config to JS (jika belum ada)
        window.configRoles = @json(config('roles.list', []));

        // Mobile Menu
        const mobileMenu = document.getElementById('mobileMenu');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const toggleMenu = () => {
            mobileMenu.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        };

        sidebarToggle?.addEventListener('click', toggleMenu);
        sidebarClose?.addEventListener('click', toggleMenu);
        sidebarOverlay?.addEventListener('click', toggleMenu);

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            initMiniCalendar();
            updateCalendarTitle();
        });

        document.getElementById('taskModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'taskModal') closeModal();
        });
    </script>
</body>
</html>