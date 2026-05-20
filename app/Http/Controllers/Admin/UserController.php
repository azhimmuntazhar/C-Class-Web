<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Hanya admin yang bisa akses
        if (!auth()->check() || !auth()->user()->hasAccess(['admin', 'manager'])) {
            abort(403, 'Akses ditolak. Khusus Admin & Manager.');
        }

        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // Hanya Admin yang boleh create user
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Khusus Admin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:user,manager,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Auto-verify agar bisa langsung login
        ]);

        return back()->with('success', 'User baru berhasil dibuat! 🎉');
    }

    public function updateRole(Request $request, User $user)
    {
        if (!auth()->check()) return redirect()->route('login');

        $requester = auth()->user();
        
        // Cegah ubah role akun sendiri
        if ($user->id === $requester->id) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        // Aturan Permission Role
        if ($requester->role === 'admin') {
            // Admin: Full access (boleh ubah apa saja)
        } 
        elseif ($requester->role === 'manager') {
            // Manager: HANYA bisa mengubah target yang saat ini bertipe 'user'
            if ($user->role !== 'user') {
                return back()->with('error', 'Manager hanya dapat mengubah role User.');
            }
            // Manager: Hanya boleh set ke 'user' atau 'manager', TIDAK boleh ke 'admin'
            if (!in_array($request->role, ['user', 'manager'])) {
                return back()->with('error', 'Manager tidak dapat menaikkan role menjadi Admin.');
            }
        } 
        else {
            abort(403, 'Akses ditolak.');
        }

        // Validasi & Update
        $request->validate(['role' => 'required|in:user,manager,admin']);
        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role user berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if (!auth()->check()) return redirect()->route('login');

        $requester = auth()->user();

        // 🛡️ 1. Cegah hapus akun sendiri
        if ($user->id === $requester->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // 🛡️ 2. Logika Permission Hapus
        if ($requester->role === 'manager') {
            // Manager TIDAK boleh hapus Admin & Manager
            if (in_array($user->role, ['admin', 'manager'])) {
                return back()->with('error', 'Manager tidak dapat menghapus akun Admin atau Manager.');
            }
        }

        // ✅ 3. Eksekusi Hapus
        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}