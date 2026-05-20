<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
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
            abort(403, 'Akses ditolak.');
        }

        // 1. Ambil semua key role yang valid dari config
        $allowedRoles = array_keys(config('roles.list')); // ['ketua_humas', 'ketua_acara', ...]
        
        // Jika Admin, tambahkan 'manager' dan 'admin' ke daftar valid
        if (auth()->user()->role === 'admin') {
            $allowedRoles[] = 'manager';
            $allowedRoles[] = 'admin';
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            // 2. Validasi role harus ada di daftar $allowedRoles
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Auto-verify agar bisa langsung login
        ]);

        // Ambil nama label untuk notifikasi
        $roleLabel = config('roles.list.' . $request->role) ?? $request->role;
    
        return back()->with('success', "User berhasil dibuat sebagai {$roleLabel}! 🎉");
    }

    public function updateRole(Request $request, User $user)
    {
        if (!auth()->check()) return redirect()->route('login');

        $requester = auth()->user();
        $allRoles = array_keys(Config::get('roles.list'));
        $basicRoles = Config::get('roles.basic_roles');

        // 1. Cegah ubah role akun sendiri
        if ($user->id === $requester->id) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        // 2. Logika Permission Dinamis
        if ($requester->role === 'admin') {
            // Admin: Full access
        } 
        elseif ($requester->role === 'manager') {
            // Manager: HANYA bisa ubah target yang masuk kategori "Ketua"
            if (!in_array($user->role, $basicRoles)) {
                return back()->with('error', 'Manager hanya dapat mengubah role Ketua.');
            }
            // Manager: Hanya boleh set ke role Ketua, TIDAK boleh ke Admin/Manager
            if (!in_array($request->role, $basicRoles)) {
                return back()->with('error', 'Manager tidak dapat menaikkan role menjadi Admin/Manager.');
            }
        } 
        else {
            abort(403, 'Akses ditolak.');
        }

        // 3. Validasi & Update
        $request->validate([
            'role' => 'required|in:' . implode(',', array_merge($allRoles, ['admin', 'manager']))
        ]);
        
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Role berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        if (!auth()->check()) return redirect()->route('login');

        $requester = auth()->user();
        $basicRoles = Config::get('roles.basic_roles');

        if ($user->id === $requester->id) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Manager TIDAK boleh hapus Admin & Manager
        if ($requester->role === 'manager' && !in_array($user->role, $basicRoles)) {
            return back()->with('error', 'Manager tidak dapat menghapus akun Admin/Manager.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}