<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    private function isKetua(?string $role): bool
    {
        if (is_null($role)) {
            return false;
        }

        return str_starts_with($role, 'ketua_');
    }

    private function canCreateRole(string $editorRole, string $targetRole): bool
    {
        if ($editorRole === 'admin') {
            return $targetRole === 'manager' || $this->isKetua($targetRole);
        }

        if ($editorRole === 'manager') {
            return $this->isKetua($targetRole);
        }

        return false;
    }

    private function canEditUser(User $editor, User $target): bool
    {
        if ($editor->id === $target->id) {
            return false;
        }

        if ($editor->role === 'admin') {
            if ($target->role === 'admin') {
                return false;
            }

            return $target->role === 'manager' || $this->isKetua($target->role);
        }

        if ($editor->role === 'manager') {
            return $this->isKetua($target->role);
        }

        return false;
    }

    private function canDeleteUser(User $editor, User $target): bool
    {
        if ($editor->id === $target->id) {
            return false;
        }

        if ($editor->role === 'admin') {
            if ($target->role === 'admin') {
                return false;
            }

            return $target->role === 'manager' || $this->isKetua($target->role);
        }

        if ($editor->role === 'manager') {
            return $this->isKetua($target->role);
        }

        return false;
    }

    private function getAssignableRoles(string $editorRole): array
    {
        $allRoles = array_keys(Config::get('roles.list', []));

        if ($editorRole === 'admin') {
            return array_values(array_filter($allRoles, fn($r) =>
                $r === 'manager' || $this->isKetua($r)
            ));
        }

        if ($editorRole === 'manager') {
            return array_values(array_filter($allRoles, fn($r) =>
                $this->isKetua($r)
            ));
        }

        return [];
    }

    public function index()
    {
        $user = auth()->user();

        if (!$user || !in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin & Manager.');
        }

        $users = User::latest()->paginate(10);

        return view('dashboard.users', compact('users'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user || !in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak.');
        }

        $requestedRole = $request->input('role');
        if (!$this->canCreateRole($user->role, $requestedRole)) {
            return back()->withInput()->withErrors([
                'role' => 'Anda tidak memiliki izin untuk membuat user dengan role ini.'
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string',
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

        if (!$editor) {
            return redirect()->route('login');
        }

        if ($target->id === $editor->id) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        if (!$this->canEditUser($editor, $target)) {
            if ($editor->role === 'manager') {
                return back()->with('error', 'Manager hanya dapat mengubah role Ketua.');
            }
            if ($editor->role === 'admin' && $target->role === 'admin') {
                return back()->with('error', 'Admin tidak dapat mengubah role admin lain.');
            }

            return back()->with('error', 'Anda tidak memiliki izin untuk mengedit user ini.');
        }

        $assignableRoles = $this->getAssignableRoles($editor->role);

        $request->validate([
            'role' => 'required|in:' . implode(',', $assignableRoles),
        ]);

        $target->update(['role' => $request->role]);

        $roleLabel = config('roles.list.' . $request->role) ?? ucfirst($request->role);

        return back()->with('success', "Role {$target->name} berhasil diubah menjadi {$roleLabel}! ✨");
    }

    public function destroy(User $target)
    {
        $editor = auth()->user();

        if (!$editor) {
            return redirect()->route('login');
        }

        if ($target->id === $editor->id) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if (!$this->canDeleteUser($editor, $target)) {
            if ($editor->role === 'manager') {
                return back()->with('error', 'Manager hanya dapat menghapus akun Ketua.');
            }
            if ($editor->role === 'admin' && $target->role === 'admin') {
                return back()->with('error', 'Admin tidak dapat menghapus admin lain.');
            }

            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus user ini.');
        }

        $target->delete();

        return back()->with('success', 'User berhasil dihapus! 🗑️');
    }
}