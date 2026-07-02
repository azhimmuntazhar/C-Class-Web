<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Helper: Cek apakah role termasuk "basic" (Ketua divisi)
     */
    private function isBasicRole(string $role): bool
    {
        $basicRoles = Config::get('roles.basic_roles', []);
        return in_array($role, $basicRoles) || str_starts_with($role, 'ketua_');
    }

    /**
     * Helper: Get roles yang boleh di-assign oleh $editor
     * 
     * @param string $editorRole Role dari user yang sedang login
     * @param string|null $targetRole Role dari user target (optional untuk create)
     */
    private function getAssignableRoles(string $editorRole, ?string $targetRole = null): array
    {
        $allRoles = array_keys(Config::get('roles.list', []));
        
        if ($editorRole === 'admin') {
            // Admin: bisa assign manager + basic roles (tapi bukan admin)
            return array_values(array_filter($allRoles, fn($r) => 
                $r === 'manager' || $this->isBasicRole($r)
            ));
        }
        
        if ($editorRole === 'manager') {
            // Manager: hanya bisa assign basic roles (bukan admin/manager)
            return array_values(array_filter($allRoles, fn($r) => $this->isBasicRole($r)));
        }
        
        return [];
    }

    public function index()
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak. Khusus Admin & Manager.');
        }

        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Dapatkan role yang boleh di-assign (untuk create, targetRole = null)
        $allowedRoles = $this->getAssignableRoles($user->role, null);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        $roleLabel = config('roles.list.' . $request->role) ?? ucfirst($request->role);
        return back()->with('success', "User berhasil dibuat sebagai {$roleLabel}! 🎉");
    }

    public function updateRole(Request $request, User $target)
    {
        $editor = auth()->user();
        
        if (!$editor) return redirect()->route('login');

        // 1. Cegah edit diri sendiri
        if ($target->id === $editor->id) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        // 2. Permission Check
        if ($editor->role === 'admin') {
            // Admin TIDAK boleh edit admin lain
            if ($target->role === 'admin') {
                return back()->with('error', 'Admin tidak dapat mengubah role admin lain.');
            }
        } 
        elseif ($editor->role === 'manager') {
            // Manager HANYA boleh edit basic roles (Ketua)
            if (!$this->isBasicRole($target->role)) {
                return back()->with('error', 'Manager hanya dapat mengubah role Ketua.');
            }
        } 
        else {
            abort(403, 'Akses ditolak.');
        }

        // 3. VALIDASI AMAN: Gunakan target->role yang sudah ada
        $assignableRoles = $this->getAssignableRoles($editor->role, $target->role);
        
        $request->validate([
            'role' => 'required|in:' . implode(',', $assignableRoles),
        ]);
        
        $target->update(['role' => $request->role]);
        
        $roleLabel = config('roles.list.' . $request->role) ?? ucfirst($request->role);
        return back()->with('success', "Role berhasil diubah menjadi {$roleLabel}! ✨");
    }

    public function destroy(User $target)
    {
        $editor = auth()->user();
        
        if (!$editor) return redirect()->route('login');

        if ($target->id === $editor->id) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Manager TIDAK boleh hapus Admin/Manager
        if ($editor->role === 'manager' && !$this->isBasicRole($target->role)) {
            return back()->with('error', 'Manager tidak dapat menghapus akun Admin/Manager.');
        }

        $target->delete();
        return back()->with('success', 'User berhasil dihapus! 🗑️');
    }
}