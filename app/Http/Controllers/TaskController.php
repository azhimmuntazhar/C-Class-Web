<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource (Protected - Login Required)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'active'); // ✅ TAMBAHKAN: Default 'active'
        
        $query = Task::with('user');

        // 1. Filter berdasarkan role
        if ($user->role === 'admin' || $user->role === 'manager') {
            // Admin & Manager: lihat semua tugas
        } else {
            // Ketua: hanya lihat tugas divisinya
            $courseKey = config("roles.course_mapping.{$user->role}");
            $query->where('course_key', $courseKey);
        }

        // 2. ✅ TAMBAHKAN: Filter berdasarkan status deadline
        if ($status === 'expired') {
            $query->where('deadline_at', '<=', now());
        } else {
            $query->where('deadline_at', '>', now());
        }

        $tasks = $query->latest()->get();

        return view('task.index', compact('tasks'));
    }

    /**
     * Public task listing (No Login Required)
     */
    public function publicIndex(Request $request)
    {
        $status = $request->query('status', 'active');
        $query = Task::with('user');

        // Filter berdasarkan status deadline
        if ($status === 'expired') {
            $query->where('deadline_at', '<=', now());
        } else {
            $query->where('deadline_at', '>', now());
        }

        $tasks = $query->latest()->get();
        return view('tasks.public', compact('tasks', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $courses = config('roles.courses');
        $allowedCourseKey = null;

        if ($user->role === 'admin' || $user->role === 'manager') {
            // Admin/Manager bisa pilih semua course
        } else {
            // Ketua hanya bisa input untuk course bagiannya
            $allowedCourseKey = config("roles.course_mapping.{$user->role}");
        }

        return view('task.create', compact('courses', 'allowedCourseKey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $user = auth()->user();
        
        // 🔐 Permission Check
        $canEdit = false;
        
        if (in_array($user->role, ['admin', 'manager'])) {
            $canEdit = true;
        } elseif (str_starts_with($user->role, 'ketua_')) {
            $userCourse = config("roles.course_mapping.{$user->role}");
            $canEdit = ($task->course_key === $userCourse);
        }
        
        if (!$canEdit) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit tugas ini.');
        }
        
        $courses = config('roles.courses');
        $allowedCourseKey = null;
        
        // Ketua tidak bisa ganti course
        if (!in_array($user->role, ['admin', 'manager'])) {
            $allowedCourseKey = config("roles.course_mapping.{$user->role}");
        }
        
        return view('task.edit', compact('task', 'courses', 'allowedCourseKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $user = auth()->user();
        
        // 🔐 Permission Check
        $canEdit = false;
        
        if (in_array($user->role, ['admin', 'manager'])) {
            $canEdit = true;
        } elseif (str_starts_with($user->role, 'ketua_')) {
            $userCourse = config("roles.course_mapping.{$user->role}");
            $canEdit = ($task->course_key === $userCourse);
        }
        
        if (!$canEdit) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit tugas ini.');
        }
        
        // 🔐 Validasi: Ketua tidak boleh ubah course_key
        $courseKey = $task->course_key; // Default: tetap sama
        
        if (in_array($user->role, ['admin', 'manager'])) {
            // Admin/Manager boleh ganti course
            $courseKey = $request->course_key;
        } else {
            // Ketua: course_key harus sama dengan task asli
            if ($request->course_key !== $task->course_key) {
                return back()->withInput()->withErrors([
                    'course_key' => 'Anda tidak dapat mengubah mata kuliah tugas ini.'
                ]);
            }
        }
        
        $validated = $request->validate([
            'course_key'      => 'required|string',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'category'        => 'required|in:individu,kelompok',
            'material_link'   => 'nullable|url',
            'submission_link' => 'nullable|url',
            'starts_at'       => 'required|date|before:deadline_at',
            'deadline_at'     => 'required|date|after:starts_at',
        ]);
        
        // Update task
        $task->update([
            'course_key'      => $courseKey,
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'category'        => $validated['category'],
            'material_link'   => $validated['material_link'] ?? null,
            'submission_link' => $validated['submission_link'] ?? null,
            'starts_at'       => $validated['starts_at'],
            'deadline_at'     => $validated['deadline_at'],
        ]);
        
        $courseName = config("roles.courses.{$courseKey}") ?? $courseKey;
        return redirect()->route('tasks.index')
            ->with('success', "Tugas '{$validated['title']}' berhasil diperbarui! ✨");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $courseKey = $request->course_key;

        // VALIDASI PERMISSION: Ketua tidak boleh input course lain
        if (!in_array($user->role, ['admin', 'manager'])) {
            $allowedCourse = config("roles.course_mapping.{$user->role}");
            if ($courseKey !== $allowedCourse) {
                return back()->withInput()->withErrors([
                    'course_key' => 'Anda tidak memiliki akses untuk mata kuliah ini.'
                ]);
            }
        }

        $validated = $request->validate([
            'course_key'      => 'required|string',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'category'        => 'required|in:individu,kelompok',
            'material_link'   => 'nullable|url',
            'submission_link' => 'nullable|url',
            'starts_at'       => 'required|date|before:deadline_at',
            'deadline_at'     => 'required|date|after:starts_at',
        ]);

        $validated['user_id'] = $user->id;
        Task::create($validated);

        $courseName = config("roles.courses.{$courseKey}") ?? $courseKey;
        return redirect()->route('tasks.index')->with('success', "Tugas {$courseName} berhasil dibuat! 🎉");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        // Permission check: owner, admin, atau manager
        if ($task->user_id !== $user->id && !in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak.');
        }
        
        $task->delete();
        return back()->with('success', 'Tugas berhasil dihapus! 🗑️');
    }
}