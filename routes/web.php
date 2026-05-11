<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;      // 1. Import TaskController
use App\Http\Controllers\GalleryController;
use App\Models\Gallery;   // Import GalleryController
use Illuminate\Support\Facades\Route;


// === PUBLIC ROUTES (Tidak perlu login) ===
Route::get('/', function () {
    // Hitung total gambar di database
    $totalDoksli = Gallery::count();
    
    return view('welcome', compact('totalDoksli'));
})->name('home');
Route::get('/galeri', function () {
    $galleries = \App\Models\Gallery::latest()->get();
    return view('galeri', compact('galleries'));
})->name('galeri');

Route::get('/galeri/create', function () {
    return view('galericreate');
})->name('galeri.create');

Route::post('/galeri', [GalleryController::class, 'storePublic'])->name('galeri.store');

// Public route untuk About (tanpa login)
Route::get('/about', function () {
    return view('about');
})->name('about');

// UPDATE 1: Redirect dashboard ke tasks.index agar lebih berguna
Route::get('/dashboard', function () {
    return redirect()->route('tasks.index');
})->middleware(['auth', 'verified'])->name('dashboard');


// === PROTECTED ROUTES (WAJIB LOGIN) ===
Route::middleware('auth')->group(function () {

    // 2. ROUTE TASKS (CRUD Lengkap)
    Route::resource('tasks', TaskController::class)->names('tasks');

    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.doksli');
    Route::get('/gallery/create', [GalleryController::class, 'create'])->name('gallery.createdoksli');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');

    // 4. ROUTE PROFILE (Bawaan Breeze - Tetap dipertahankan)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Route ini hanya bisa diakses jika user punya middleware 'isAdmin'
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
});

// Route Auth Breeze (Login, Register, Forgot Password, dll)
require __DIR__.'/auth.php';