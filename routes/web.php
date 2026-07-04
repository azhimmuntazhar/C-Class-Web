<?php

use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Models\Gallery;
use App\Models\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $totalDoksli = Gallery::count();
    $totalActiveTasks = Task::where('deadline_at', '>', now())->count();
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

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/tugas', [TaskController::class, 'publicIndex'])->name('tasks.public');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        $totalUsers = \App\Models\User::count();
        $totalTasks = \App\Models\Task::count();
        $activeTasks = \App\Models\Task::where('deadline_at', '>', now())->count();
        $latestUsers = \App\Models\User::latest()->limit(5)->get();

        return view('dashboard.admin', compact('totalUsers', 'totalTasks', 'activeTasks', 'latestUsers'));
    } elseif ($user->role === 'manager') {
        $basicRoles = config('roles.basic_roles');
        $allTasksQuery = \App\Models\Task::with('user');

        $totalTasks = (clone $allTasksQuery)->count();
        $activeTasks = (clone $allTasksQuery)->where('deadline_at', '>', now())->count();
        $expiredTasks = (clone $allTasksQuery)->where('deadline_at', '<=', now())->count();
        $latestTasks = (clone $allTasksQuery)->latest()->take(5)->get();
        $upcomingDeadlines = (clone $allTasksQuery)
            ->where('deadline_at', '>', now())
            ->orderBy('deadline_at', 'asc')
            ->take(5)
            ->get();
        $totalKetua = \App\Models\User::whereIn('role', $basicRoles)->count();
        $latestPhotos = \App\Models\Gallery::latest()->take(5)->get();
        $tasks = (clone $allTasksQuery)->latest()->paginate(10);

        return view('dashboard.manager', compact(
            'totalTasks', 'activeTasks', 'expiredTasks',
            'latestTasks', 'upcomingDeadlines', 'totalKetua',
            'latestPhotos', 'tasks'
        ));
    }

    $courseKey = config("roles.course_mapping.{$user->role}");
    $tasks = \App\Models\Task::with('user')
        ->where('course_key', $courseKey)
        ->latest()
        ->paginate(10);
    $courseName = config("roles.courses.{$courseKey}") ?? ucfirst(str_replace('_', ' ', $courseKey));

    return view('dashboard.ketua', compact('tasks', 'courseName', 'courseKey'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('tasks', TaskController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.doksli');
    Route::get('/gallery/create', [GalleryController::class, 'create'])->name('gallery.createdoksli');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');

    Route::get('/dashboard/gallery', function () {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin dan Manager.');
        }

        $galleries = \App\Models\Gallery::latest()->get();

        return view('dashboard.gallery', compact('galleries'));
    })->name('dashboard.gallery');

    Route::get('/dashboard/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('dashboard.users');
    Route::post('/dashboard/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('dashboard.users.store');
    Route::patch('/dashboard/users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('dashboard.users.updateRole');
    Route::delete('/dashboard/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('dashboard.users.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
});

require __DIR__.'/auth.php';