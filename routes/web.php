<?php

use App\Http\Controllers\ProfileController;
use App\Models\Task;  // Import Task model
use App\Http\Controllers\TaskController;      // Import TaskController
use App\Http\Controllers\GalleryController;
use App\Models\Gallery;   // Import GalleryController
use Illuminate\Support\Facades\Route;


// === PUBLIC ROUTES ===
Route::get('/', function () {
    // Hitung total gambar di database
    $totalDoksli = Gallery::count();
    // Hitung total tugas yang belum expired
    $totalActiveTasks = Task::where('deadline_at', '>', now())->count();
    //5 tugas terbaru
    $latestTasks = Task::with('user')
        ->where('deadline_at', '>', now())
        ->latest()
        ->limit(5)
        ->get();
    
    return view('welcome', compact('totalDoksli', 'totalActiveTasks', 'latestTasks'));
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

//public route untuk tugas
Route::get('/tugas', [\App\Http\Controllers\TaskController::class, 'publicIndex'])->name('tasks.public');

// landingpage role based
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // 🎯 LOGIKA PER ROLE
    if ($user->role === 'admin') {
        // === ADMIN VIEW ===
        // Admin bisa lihat semua tugas + stats user management
        $totalUsers = \App\Models\User::count();
        $totalTasks = \App\Models\Task::count();
        $activeTasks = \App\Models\Task::where('deadline_at', '>', now())->count();
        $latestUsers = \App\Models\User::latest()->limit(5)->get();
        
        return view('dashboard.admin', compact('totalUsers', 'totalTasks', 'activeTasks', 'latestUsers'));
        
    } elseif ($user->role === 'manager') {
        // === MANAGER VIEW ===
        // Manager bisa lihat semua tugas dari basic_roles (Ketua)
        $basicRoles = config('roles.basic_roles');
        
        $tasks = \App\Models\Task::with('user')
            ->whereIn('user_id', function($query) use ($basicRoles) {
                $query->select('id')->from('users')->whereIn('role', $basicRoles);
            })
            ->latest()
            ->paginate(10);
            
        $activeCount = clone $tasks; // Clone query untuk count
        $activeCount = $activeCount->where('deadline_at', '>', now())->count();
        
        return view('dashboard.manager', compact('tasks', 'activeCount'));
        
    } else {
        // === KETUA VIEW (Default) ===
        // Ketua hanya lihat tugas divisinya sendiri
        $courseKey = config("roles.course_mapping.{$user->role}");
        
        $tasks = \App\Models\Task::with('user')
            ->where('course_key', $courseKey)
            ->latest()
            ->paginate(10);
            
        $courseName = config("roles.courses.{$courseKey}") ?? ucfirst(str_replace('_', ' ', $courseKey));
        
        return view('dashboard.ketua', compact('tasks', 'courseName', 'courseKey'));
    }
    
})->middleware(['auth', 'verified'])->name('dashboard');


// === PROTECTED ROUTES (WAJIB LOGIN) ===
Route::middleware('auth')->group(function () {

    // 2. ROUTE TASKS (CRUD Lengkap)
    Route::resource('tasks', \App\Http\Controllers\TaskController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.doksli');
    Route::get('/gallery/create', [GalleryController::class, 'create'])->name('gallery.createdoksli');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');

    Route::get('/dashboard/gallery', function () {
        $user = auth()->user();
        
        // Hanya Admin & Manager yang bisa akses
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin dan Manager.');
        }
        
        $galleries = \App\Models\Gallery::latest()->get();
        return view('dashboard.gallery', compact('galleries'));
    })->name('dashboard.gallery');

    // 4. ROUTE PROFILE (Bawaan Breeze - Tetap dipertahankan)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// Route ini hanya bisa diakses jika user punya middleware 'isAdmin'
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
});
// Admin Routes - Hanya untuk admin
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    // admin add akun
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
});

// Route Auth Breeze (Login, Register, Forgot Password, dll)
require __DIR__.'/auth.php';