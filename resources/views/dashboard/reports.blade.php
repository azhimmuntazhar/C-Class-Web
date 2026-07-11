@extends('layouts.sidebar')

@section('title', 'Kelola Laporan')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeInUp 0.4s ease-out forwards;
        opacity: 0;
    }
    .report-card {
        transition: all 0.2s ease;
    }
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')
<div class="p-4 md:p-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Kelola Laporan
            </h1>
            <p class="text-gray-400 mt-1">Saran dan laporan bug dari pengguna</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4 mb-8 animate-fade-in">
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Total</p>
            <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Baru</p>
            <p class="text-2xl font-bold text-blue-400">{{ $stats['baru'] }}</p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Diproses</p>
            <p class="text-2xl font-bold text-yellow-400">{{ $stats['diproses'] }}</p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Selesai</p>
            <p class="text-2xl font-bold text-emerald-400">{{ $stats['selesai'] }}</p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Saran</p>
            <p class="text-2xl font-bold text-purple-400">{{ $stats['saran'] }}</p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Bug</p>
            <p class="text-2xl font-bold text-red-400">{{ $stats['bug'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('dashboard.reports') }}" class="flex flex-wrap gap-3 mb-6 bg-gray-700/60 p-4 rounded-xl border border-gray-600 animate-fade-in">
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul atau deskripsi..." 
                   class="w-full px-4 py-2.5 pl-10 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        
        <select name="category" class="px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            <option value="semua" {{ $category === 'semua' ? 'selected' : '' }}>Semua Kategori</option>
            <option value="saran" {{ $category === 'saran' ? 'selected' : '' }}>💡 Saran</option>
            <option value="bug" {{ $category === 'bug' ? 'selected' : '' }}>🐛 Bug</option>
        </select>
        
        <select name="filter" class="px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            <option value="semua" {{ $filter === 'semua' ? 'selected' : '' }}>Semua Status</option>
            <option value="baru" {{ $filter === 'baru' ? 'selected' : '' }}>Baru</option>
            <option value="diproses" {{ $filter === 'diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="selesai" {{ $filter === 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        
        <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
            Filter
        </button>
        
        <a href="{{ route('dashboard.reports') }}" class="px-4 py-2.5 bg-gray-600 hover:bg-gray-500 text-white text-sm font-medium rounded-lg transition">
            Reset
        </a>
    </form>

    <!-- Reports List -->
    @if($reports->count() > 0)
        <div class="space-y-4">
            @foreach($reports as $report)
            <div class="report-card bg-gray-700/60 rounded-xl border border-gray-600 p-5 animate-fade-in">
                <div class="flex flex-col md:flex-row gap-4">
                    
                    <!-- Image (if exists) -->
                    @if($report->image)
                    <div class="md:w-48 flex-shrink-0">
                        <img src="{{ asset('storage/' . $report->image) }}" 
                             alt="{{ $report->title }}"
                             class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-80 transition"
                             onclick="window.open('{{ asset('storage/' . $report->image) }}', '_blank')">
                    </div>
                    @endif
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <!-- Header -->
                        <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <!-- Category Badge -->
                                @if($report->isSaran())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-900/40 text-purple-300 text-xs font-medium rounded-md border border-purple-700/50">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                        Saran
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-900/40 text-red-300 text-xs font-medium rounded-md border border-red-700/50">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Bug
                                    </span>
                                @endif
                                
                                <!-- Status Badge -->
                                @php
                                    $statusColors = [
                                        'baru' => 'bg-blue-900/40 text-blue-300 border-blue-700/50',
                                        'diproses' => 'bg-yellow-900/40 text-yellow-300 border-yellow-700/50',
                                        'selesai' => 'bg-emerald-900/40 text-emerald-300 border-emerald-700/50',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-md border {{ $statusColors[$report->status] }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                            
                            <!-- Date -->
                            <span class="text-xs text-gray-500">
                                {{ $report->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-lg font-semibold text-white mb-2">{{ $report->title }}</h3>
                        
                        <!-- Description -->
                        <p class="text-gray-400 text-sm mb-3 whitespace-pre-wrap">{{ $report->description }}</p>
                        
                        <!-- Reporter Info -->
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-3">
                            @if($report->reporter_name)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $report->reporter_name }}
                            </span>
                            @endif
                            @if($report->reporter_email)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ $report->reporter_email }}
                            </span>
                            @endif
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $report->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex flex-wrap gap-2">
                            <!-- Status Update Form -->
                            <form action="{{ route('dashboard.reports.updateStatus', $report) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" 
                                        class="px-3 py-1.5 bg-gray-800 border border-gray-600 rounded-lg text-white text-xs focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                                    <option value="baru" {{ $report->status === 'baru' ? 'selected' : '' }}>🔵 Baru</option>
                                    <option value="diproses" {{ $report->status === 'diproses' ? 'selected' : '' }}>🟡 Diproses</option>
                                    <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>🟢 Selesai</option>
                                </select>
                            </form>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('dashboard.reports.destroy', $report) }}" method="POST" class="delete-form inline">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-lg transition border border-red-800/30">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($reports->hasPages())
        <div class="mt-6">
            {{ $reports->withQueryString()->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-16 bg-gray-700/40 rounded-2xl border border-dashed border-gray-600">
            <svg class="w-20 h-20 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-white mb-2">Belum ada laporan</h3>
            <p class="text-gray-400 text-sm">Laporan dari pengguna akan muncul di sini</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus laporan ini?',
                text: "Laporan akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endpush

@endsection