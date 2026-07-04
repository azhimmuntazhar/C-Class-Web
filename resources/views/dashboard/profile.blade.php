@extends('layouts.sidebar')

@section('title', 'Profile Settings')

@push('styles')
<style>
    .profile-section {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    .profile-section:nth-child(1) { animation-delay: 0.1s; }
    .profile-section:nth-child(2) { animation-delay: 0.2s; }
    .profile-section:nth-child(3) { animation-delay: 0.3s; }
    .profile-section:nth-child(4) { animation-delay: 0.4s; }
    .profile-section:nth-child(5) { animation-delay: 0.5s; }
    
    @keyframes fadeInUp {
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    
    .profile-card {
        transition: all 0.3s ease;
    }
    .profile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    .form-input {
        transition: all 0.2s ease;
    }
    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        border-color: #10b981;
    }
    
    .password-toggle {
        transition: all 0.2s ease;
    }
    .password-toggle:hover {
        color: #10b981;
    }
    
    .strength-bar {
        transition: all 0.3s ease;
    }
    
    @keyframes pulse-ring {
        0% { transform: scale(0.95); opacity: 0.7; }
        50% { transform: scale(1); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.7; }
    }
    .avatar-pulse {
        animation: pulse-ring 3s ease-in-out infinite;
    }
    
    .btn-lift {
        transition: all 0.2s ease;
    }
    .btn-lift:hover {
        transform: translateY(-1px);
    }
    .btn-lift:active {
        transform: translateY(0);
    }
    
    .danger-border {
        position: relative;
        overflow: hidden;
    }
    .danger-border::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #ef4444, transparent);
        animation: slide 3s linear infinite;
    }
    @keyframes slide {
        to { left: 100%; }
    }
    
    .session-card {
        background: linear-gradient(135deg, rgba(31, 41, 55, 0.6) 0%, rgba(17, 24, 39, 0.8) 100%);
    }
</style>
@endpush

@section('content')
<div class="p-4 md:p-8 max-w-5xl mx-auto">
    
    
    <div class="profile-section mb-8">
        <div class="bg-gradient-to-r from-gray-700/60 to-gray-800/60 rounded-2xl border border-gray-600 p-6 md:p-8 profile-card">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                
                <div class="relative">
                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-full bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 flex items-center justify-center text-white font-bold text-3xl md:text-4xl shadow-xl avatar-pulse">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-gray-900 rounded-full border-2 border-emerald-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">
                        {{ auth()->user()->name }}
                    </h1>
                    <p class="text-gray-400 mb-3 flex items-center justify-center md:justify-start gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ auth()->user()->email }}
                    </p>
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium
                            {{ auth()->user()->role === 'admin' ? 'bg-red-900/40 text-red-300 border border-red-700/50' : 
                               (auth()->user()->role === 'manager' ? 'bg-purple-900/40 text-purple-300 border border-purple-700/50' : 
                               'bg-emerald-900/40 text-emerald-300 border border-emerald-700/50') }}">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ config('roles.list.' . auth()->user()->role) ?? ucfirst(auth()->user()->role) }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-700/60 text-gray-300 border border-gray-600">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Bergabung {{ auth()->user()->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="profile-section profile-card bg-gray-700/60 rounded-2xl border border-gray-600 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-emerald-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-white">Informasi Profil</h2>
                    <p class="text-xs text-gray-400">Update nama dan email Anda</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </span>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                               class="form-input w-full pl-11 pr-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 outline-none @error('name') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap">
                    </div>
                    @error('name') 
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                               class="form-input w-full pl-11 pr-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 outline-none @error('email') border-red-500 @enderror"
                               placeholder="email@example.com">
                    </div>
                    @error('email') 
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>

                
                <div class="pt-2">
                    <button type="submit" 
                            class="btn-lift w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition shadow-md hover:shadow-emerald-500/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        
        <div class="profile-section profile-card bg-gray-700/60 rounded-2xl border border-gray-600 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-blue-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-white">Ubah Password</h2>
                    <p class="text-xs text-gray-400">Pastikan password kuat dan aman</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Password Saat Ini <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                        <input type="password" name="current_password" required autocomplete="current-password"
                               id="current_password"
                               class="form-input w-full pl-11 pr-11 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 outline-none @error('current_password', 'updatePassword') border-red-500 @enderror"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword('current_password', this)" 
                                class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    @error('current_password', 'updatePassword') 
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Password Baru <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </span>
                        <input type="password" name="password" required autocomplete="new-password"
                               id="new_password" oninput="checkPasswordStrength(this.value)"
                               class="form-input w-full pl-11 pr-11 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 outline-none @error('password', 'updatePassword') border-red-500 @enderror"
                               placeholder="Minimal 8 karakter">
                        <button type="button" onclick="togglePassword('new_password', this)" 
                                class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    
                    
                    <div class="mt-2 space-y-1.5">
                        <div class="flex gap-1">
                            <div id="strength-1" class="h-1 flex-1 bg-gray-700 rounded-full strength-bar"></div>
                            <div id="strength-2" class="h-1 flex-1 bg-gray-700 rounded-full strength-bar"></div>
                            <div id="strength-3" class="h-1 flex-1 bg-gray-700 rounded-full strength-bar"></div>
                            <div id="strength-4" class="h-1 flex-1 bg-gray-700 rounded-full strength-bar"></div>
                        </div>
                        <p id="strength-text" class="text-xs text-gray-500">Masukkan password untuk melihat kekuatan</p>
                    </div>
                    
                    @error('password', 'updatePassword') 
                        <p class="text-red-400 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Konfirmasi Password Baru <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </span>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                               id="confirm_password"
                               class="form-input w-full pl-11 pr-11 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 outline-none"
                               placeholder="Ulangi password baru">
                        <button type="button" onclick="togglePassword('confirm_password', this)" 
                                class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                            <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                
                <div class="pt-2">
                    <button type="submit" 
                            class="btn-lift w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition shadow-md hover:shadow-blue-500/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    
    <div class="profile-section profile-card session-card rounded-2xl border border-gray-600 p-6 mt-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-purple-900/40 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-white">Sesi Aktif & Keamanan</h2>
                <p class="text-xs text-gray-400">Informasi sesi login dan logout akun</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            
            <div class="bg-gray-800/40 rounded-xl p-4 border border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Perangkat</p>
                        <p class="text-sm text-white font-medium">Browser Aktif</p>
                    </div>
                </div>
            </div>
            
            
            <div class="bg-gray-800/40 rounded-xl p-4 border border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Login Terakhir</p>
                        <p class="text-sm text-white font-medium">{{ now()->format('H:i') }} WIB</p>
                    </div>
                </div>
            </div>
            
            
            <div class="bg-gray-800/40 rounded-xl p-4 border border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-purple-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Lokasi</p>
                        <p class="text-sm text-white font-medium">Indonesia</p>
                    </div>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="button" onclick="confirmLogout()" 
                    class="btn-lift w-full sm:w-auto px-5 py-2.5 bg-yellow-600/90 hover:bg-yellow-600 text-white font-medium rounded-lg transition border border-yellow-500/50 hover:border-yellow-500 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Logout dari Akun
            </button>
        </form>
    </div>

    
    <div class="profile-section danger-border bg-red-900/10 rounded-2xl border border-red-700/50 p-6 mt-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-red-400 mb-2">Zona Berbahaya</h2>
                <p class="text-gray-400 text-sm mb-3 leading-relaxed">
                    Jika Anda ingin menghapus akun, silakan hubungi admin sebagai administrator utama. 
                    Tindakan ini tidak dapat dibatalkan dan semua data Anda akan hilang permanen.
                </p>
                <p class="text-red-400 text-xs mb-4 font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Pengajuan penghapusan akun berarti Anda dianggap telah mengundurkan diri.
                </p>
                
                     <a href="https://wa.me/6289506035363" target="_blank"
                   class="btn-lift inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition shadow-md hover:shadow-red-500/20">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Hubungi Admin via WhatsApp
                </a>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const eyeOpen = button.querySelector('.eye-open');
        const eyeClosed = button.querySelector('.eye-closed');
        
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }
    
    function checkPasswordStrength(password) {
        let strength = 0;
        let message = '';
        let color = '';
        
        if (password.length === 0) {
            updateStrengthIndicator(0, 'Masukkan password untuk melihat kekuatan', 'gray');
            return;
        }
        
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        if (strength <= 2) {
            message = 'Lemah - Tambahkan huruf besar, angka, dan simbol';
            color = 'red';
        } else if (strength <= 3) {
            message = 'Sedang - Bisa lebih kuat';
            color = 'yellow';
        } else if (strength <= 4) {
            message = 'Baik - Password cukup kuat';
            color = 'blue';
        } else {
            message = 'Kuat - Password sangat aman!';
            color = 'green';
        }
        
        updateStrengthIndicator(Math.min(strength, 4), message, color);
    }
    
    function updateStrengthIndicator(level, message, color) {
        const colors = {
            red: 'bg-red-500',
            yellow: 'bg-yellow-500',
            blue: 'bg-blue-500',
            green: 'bg-emerald-500',
            gray: 'bg-gray-700'
        };
        
        const textColors = {
            red: 'text-red-400',
            yellow: 'text-yellow-400',
            blue: 'text-blue-400',
            green: 'text-emerald-400',
            gray: 'text-gray-500'
        };
        
        for (let i = 1; i <= 4; i++) {
            const bar = document.getElementById(`strength-${i}`);
            bar.className = 'h-1 flex-1 rounded-full strength-bar ' + 
                (i <= level ? colors[color] : 'bg-gray-700');
        }
        
        const strengthText = document.getElementById('strength-text');
        strengthText.textContent = message;
        strengthText.className = 'text-xs mt-1 ' + textColors[color];
    }
    
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
            reverseButtons: true,
            customClass: {
                popup: 'rounded-xl border border-gray-700',
                confirmButton: 'rounded-lg',
                cancelButton: 'rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    }

    @if (session('status'))
        @php
            $statusMessage = session('status') === 'password-updated' 
                ? 'Password berhasil diupdate!' 
                : 'Profile berhasil diupdate!';
            $statusIcon = session('status') === 'password-updated' ? 'lock' : 'user';
        @endphp
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ $statusMessage }}',
            timer: 3000,
            showConfirmButton: false,
            position: 'top-end',
            toast: true,
            background: '#1f2937',
            color: '#fff',
            iconColor: '#10b981',
            customClass: {
                popup: 'rounded-xl border border-gray-700'
            }
        });
    @endif
</script>
@endpush

@endsection