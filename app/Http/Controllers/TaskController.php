<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Task::with('user')->latest();

        // Filter berdasarkan role
        if ($user->role === 'admin' || $user->role === 'manager') {
            $tasks = $query->paginate(10);
        } else {
            // Ketua hanya lihat tugas divisinya
            $courseKey = config("roles.course_mapping.{$user->role}");
            $tasks = $query->where('course_key', $courseKey)->paginate(10);
        }

        return view('task.index', compact('tasks'));
    }

    public function publicIndex(Request $request)
    {
        $status = $request->query('status', 'active'); // Default: aktif
        $query = Task::with('user')->latest();

        // Filter berdasarkan status deadline
        if ($status === 'expired') {
            $query->where('deadline_at', '<=', now());
        } else {
            $query->where('deadline_at', '>', now());
        }

        $tasks = $query->get();
        return view('tasks.public', compact('tasks', 'status'));
    }

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

    // Optional: destroy method jika perlu hapus tugas
    public function destroy(Task $task)
    {
        $user = Auth::user();
        
        // Hanya owner atau admin/manager yang bisa hapus
        if ($task->user_id !== $user->id && !in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak.');
        }
        
        $task->delete();
        return back()->with('success', 'Tugas berhasil dihapus!');
    }
}