@extends('layouts.sidebar')

@section('title', 'Buat Tugas Baru')

@push('styles')
<style>
    /* Form Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .form-section {
        animation: fadeInUp 0.4s ease-out forwards;
        opacity: 0;
    }
    .form-section:nth-child(1) { animation-delay: 0.1s; }
    .form-section:nth-child(2) { animation-delay: 0.2s; }
    .form-section:nth-child(3) { animation-delay: 0.3s; }
    .form-section:nth-child(4) { animation-delay: 0.4s; }
    .form-section:nth-child(5) { animation-delay: 0.5s; }
    
    /* Input Focus Effect */
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    
    /* Character Counter */
    .char-counter {
        transition: color 0.2s;
    }
    .char-counter.warning { color: #f59e0b; }
    .char-counter.danger { color: #ef4444; }
</style>
@endpush

@section('content')
<div class="p-4 md:p-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 form-section">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                Buat Tugas Baru
            </h1>
            <p class="text-gray-400 mt-1">
                @if(in_array(auth()->user()->role, ['admin', 'manager']))
                    Tambahkan tugas untuk mata kuliah tertentu
                @else
                    Tugas akan otomatis masuk ke divisi Anda
                @endif
            </p>
        </div>
        
        <a href="{{ route('tasks.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition border border-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Info Banner (Untuk Ketua) -->
    @if(!in_array(auth()->user()->role, ['admin', 'manager']))
    <div class="form-section bg-emerald-900/20 border border-emerald-700/50 rounded-xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flex-1">
            <p class="text-emerald-300 text-sm font-medium">Mata kuliah akan otomatis terisi</p>
            <p class="text-emerald-400/80 text-xs mt-1">
                Berdasarkan role Anda sebagai <strong>{{ config('roles.list.' . auth()->user()->role) }}</strong>
            </p>
        </div>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('tasks.store') }}" method="POST" id="taskForm" class="space-y-6">
        @csrf

        <!-- Section 1: Mata Kuliah -->
        <div class="form-section bg-gray-700/60 rounded-xl border border-gray-600 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Mata Kuliah
            </h2>
            
            @if(in_array(auth()->user()->role, ['admin', 'manager']))
                <!-- Admin/Manager: Dropdown untuk pilih course -->
                <select name="course_key" required 
                        class="w-full px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <option value="">Pilih mata kuliah...</option>
                    @foreach(config('roles.courses') as $key => $name)
                        <option value="{{ $key }}" {{ old('course_key') == $key ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-2">Pilih mata kuliah yang akan menerima tugas ini</p>
            @else
                <!-- Ketua: Auto-filled, tidak bisa diubah -->
                @php $courseKey = config("roles.course_mapping." . auth()->user()->role); @endphp
                <input type="hidden" name="course_key" value="{{ $courseKey }}">
                
                <div class="flex items-center gap-3 p-3 bg-gray-800/50 rounded-lg border border-gray-600">
                    <div class="w-10 h-10 rounded-lg bg-emerald-900/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-medium">{{ config('roles.courses.' . $courseKey) }}</p>
                        <p class="text-xs text-gray-400">Otomatis terisi sesuai divisi Anda</p>
                    </div>
                </div>
            @endif
            
            @error('course_key') 
                <p class="text-red-400 text-xs mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Section 2: Informasi Dasar -->
        <div class="form-section bg-gray-700/60 rounded-xl border border-gray-600 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Informasi Tugas
            </h2>
            
            <div class="space-y-4">
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Judul Tugas <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required maxlength="255"
                           class="w-full px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                           placeholder="Contoh: Tugas Implementasi Struktur Data">
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Buat judul yang jelas dan deskriptif</p>
                        <span class="text-xs text-gray-500 char-counter"><span id="titleCount">0</span>/255</span>
                    </div>
                    @error('title') 
                        <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Kategori <span class="text-red-400">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="category" value="individu" {{ old('category', 'individu') == 'individu' ? 'checked' : '' }} class="peer sr-only">
                            <div class="p-4 bg-gray-800 border-2 border-gray-600 rounded-lg peer-checked:border-emerald-500 peer-checked:bg-emerald-900/20 transition hover:border-gray-500">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-purple-900/40 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-medium">Individu</p>
                                        <p class="text-xs text-gray-400">Dikerjakan sendiri</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        
                        <label class="cursor-pointer">
                            <input type="radio" name="category" value="kelompok" {{ old('category') == 'kelompok' ? 'checked' : '' }} class="peer sr-only">
                            <div class="p-4 bg-gray-800 border-2 border-gray-600 rounded-lg peer-checked:border-emerald-500 peer-checked:bg-emerald-900/20 transition hover:border-gray-500">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-900/40 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-medium">Kelompok</p>
                                        <p class="text-xs text-gray-400">Dikerjakan bersama</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('category') 
                        <p class="text-red-400 text-xs mt-2 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 3: Deskripsi -->
        <div class="form-section bg-gray-700/60 rounded-xl border border-gray-600 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Deskripsi Tugas
            </h2>
            
            <div>
                <textarea name="description" rows="6" required 
                          class="w-full px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition resize-none"
                          placeholder="Jelaskan detail tugas, instruksi, dan hal-hal yang perlu diperhatikan...">{{ old('description') }}</textarea>
                <p class="text-xs text-gray-500 mt-2">
                     Tekan Enter untuk membuat paragraf baru. Deskripsi yang jelas akan membantu mahasiswa memahami tugas dengan baik.
                </p>
                @error('description') 
                    <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <!-- Section 4: Link (Opsional) -->
        <div class="form-section bg-gray-700/60 rounded-xl border border-gray-600 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
                Link Tambahan
                <span class="text-xs text-gray-500 font-normal">(Opsional)</span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Link Materi -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Link Materi
                        </span>
                    </label>
                    <input type="url" name="material_link" value="{{ old('material_link') }}" 
                           placeholder="https://drive.google.com/..."
                           class="w-full px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <p class="text-xs text-gray-500 mt-1">Link ke materi pembelajaran (PDF, video, dll)</p>
                    @error('material_link') 
                        <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Link Pengumpulan -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Link Pengumpulan
                        </span>
                    </label>
                    <input type="url" name="submission_link" value="{{ old('submission_link') }}" 
                           placeholder="https://forms.google.com/..."
                           class="w-full px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <p class="text-xs text-gray-500 mt-1">Link untuk pengumpulan tugas (form, drive, dll)</p>
                    @error('submission_link') 
                        <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 5: Timeline -->
        <div class="form-section bg-gray-700/60 rounded-xl border border-gray-600 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Waktu Pelaksanaan
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Mulai -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Mulai Tugas <span class="text-red-400">*</span>
                        </span>
                    </label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" required 
                           class="w-full px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <p class="text-xs text-gray-500 mt-1">Kapan tugas mulai dapat dikerjakan</p>
                    @error('starts_at') 
                        <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Deadline -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Deadline <span class="text-red-400">*</span>
                        </span>
                    </label>
                    <input type="datetime-local" name="deadline_at" value="{{ old('deadline_at') }}" required 
                           class="w-full px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <p class="text-xs text-gray-500 mt-1">Batas akhir pengumpulan tugas</p>
                    @error('deadline_at') 
                        <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            
            <!-- Timeline Preview -->
            <div id="timelinePreview" class="mt-4 p-3 bg-gray-800/50 rounded-lg border border-gray-600 hidden">
                <p class="text-xs text-gray-400 mb-2">Preview Timeline:</p>
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-emerald-400" id="previewStart">-</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                    <span class="text-orange-400" id="previewEnd">-</span>
                </div>
                <p class="text-xs text-gray-500 mt-1" id="previewDuration"></p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-section flex flex-col sm:flex-row gap-3 pt-4">
            <a href="{{ route('tasks.index') }}" 
               class="flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition text-center border border-gray-600">
                Batal
            </a>
            <button type="submit" 
                    class="flex-1 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Tugas
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Character counter for title
    const titleInput = document.querySelector('input[name="title"]');
    const titleCount = document.getElementById('titleCount');
    const charCounter = document.querySelector('.char-counter');
    
    titleInput?.addEventListener('input', function() {
        const count = this.value.length;
        titleCount.textContent = count;
        
        charCounter.classList.remove('warning', 'danger');
        if (count > 230) {
            charCounter.classList.add('danger');
        } else if (count > 200) {
            charCounter.classList.add('warning');
        }
    });
    
    // Timeline preview
    const startsInput = document.querySelector('input[name="starts_at"]');
    const deadlineInput = document.querySelector('input[name="deadline_at"]');
    const timelinePreview = document.getElementById('timelinePreview');
    const previewStart = document.getElementById('previewStart');
    const previewEnd = document.getElementById('previewEnd');
    const previewDuration = document.getElementById('previewDuration');
    
    function updateTimelinePreview() {
        if (startsInput.value && deadlineInput.value) {
            const start = new Date(startsInput.value);
            const end = new Date(deadlineInput.value);
            
            if (start < end) {
                timelinePreview.classList.remove('hidden');
                previewStart.textContent = start.toLocaleString('id-ID', { 
                    day: 'numeric', month: 'short', year: 'numeric', 
                    hour: '2-digit', minute: '2-digit' 
                });
                previewEnd.textContent = end.toLocaleString('id-ID', { 
                    day: 'numeric', month: 'short', year: 'numeric', 
                    hour: '2-digit', minute: '2-digit' 
                });
                
                // Calculate duration
                const diffMs = end - start;
                const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
                const diffHours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                
                let durationText = 'Durasi: ';
                if (diffDays > 0) durationText += `${diffDays} hari `;
                if (diffHours > 0) durationText += `${diffHours} jam`;
                if (diffDays === 0 && diffHours === 0) durationText = 'Durasi: Kurang dari 1 jam';
                
                previewDuration.textContent = durationText;
            } else {
                timelinePreview.classList.add('hidden');
            }
        } else {
            timelinePreview.classList.add('hidden');
        }
    }
    
    startsInput?.addEventListener('change', updateTimelinePreview);
    deadlineInput?.addEventListener('change', updateTimelinePreview);
    
    // Form validation before submit
    document.getElementById('taskForm')?.addEventListener('submit', function(e) {
        const starts = new Date(startsInput.value);
        const deadline = new Date(deadlineInput.value);
        
        if (starts >= deadline) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Waktu Tidak Valid',
                text: 'Deadline harus setelah waktu mulai tugas',
                background: '#1f2937',
                color: '#fff'
            });
            return false;
        }
    });
</script>
@endpush

@endsection