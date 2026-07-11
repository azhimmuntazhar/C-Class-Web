@extends('layouts.sidebar')

@section('title', 'Kelola User')

@push('styles')
<style>
    .modal { transition: opacity 0.2s ease; }
    .modal.hidden { opacity: 0; pointer-events: none; }
    .modal:not(.hidden) { opacity: 1; pointer-events: auto; }
    
    .user-row { transition: background-color 0.2s; }
    .user-row:hover { background-color: rgba(55, 65, 81, 0.8); }
    
    .role-admin { background-color: rgba(127, 29, 29, 0.4); color: rgb(248, 113, 113); border-color: rgba(185, 28, 28, 0.5); }
    .role-manager { background-color: rgba(88, 28, 135, 0.4); color: rgb(192, 132, 252); border-color: rgba(126, 34, 206, 0.5); }
    .role-ketua { background-color: rgba(6, 78, 59, 0.4); color: rgb(110, 231, 183); border-color: rgba(4, 120, 87, 0.5); }
</style>
@endpush

@php
    $currentUser = auth()->user();

    $canEdit = function($targetUser) use ($currentUser) {
        if ($targetUser->id === $currentUser->id) return false;
        if ($currentUser->role === 'admin') {
            return $targetUser->role !== 'admin';
        }
        if ($currentUser->role === 'manager') {
            return str_starts_with($targetUser->role, 'ketua_');
        }
        return false;
    };

    $canDelete = function($targetUser) use ($currentUser) {
        if ($targetUser->id === $currentUser->id) return false;
        if ($currentUser->role === 'admin') {
            return $targetUser->role !== 'admin';
        }
        if ($currentUser->role === 'manager') {
            return str_starts_with($targetUser->role, 'ketua_');
        }
        return false;
    };

    $getAssignableRoles = function() use ($currentUser) {
        $allRoles = config('roles.list', []);
        if ($currentUser->role === 'admin') {
            return array_filter($allRoles, fn($key) => $key === 'manager' || str_starts_with($key, 'ketua_'), ARRAY_FILTER_USE_KEY);
        }
        if ($currentUser->role === 'manager') {
            return array_filter($allRoles, fn($key) => str_starts_with($key, 'ketua_'), ARRAY_FILTER_USE_KEY);
        }
        return [];
    };
@endphp

@section('content')
<div class="p-4 md:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                Kelola User
            </h1>
            <p class="text-gray-400 mt-1">
                @if($currentUser->role === 'admin')
                    Kelola semua user (Manager & Ketua)
                @else
                    Kelola akun Ketua divisi
                @endif
            </p>
        </div>
        
        <button onclick="openAddUserModal()" 
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition shadow-md hover:shadow-lg active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah User
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Total User</p>
            <p class="text-2xl font-bold text-white">{{ $users->total() }}</p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Manager</p>
            <p class="text-2xl font-bold text-purple-400">{{ $users->filter(fn($u) => $u->role === 'manager')->count() }}</p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Ketua</p>
            <p class="text-2xl font-bold text-emerald-400">
                {{ $users->filter(fn($u) => $u->role && str_starts_with($u->role, 'ketua_'))->count() }}
            </p>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-4 mb-6 bg-gray-700/60 p-4 rounded-xl border border-gray-600">
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" id="searchInput" placeholder="Cari nama atau email..." 
                   class="w-full px-4 py-2.5 pl-10 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        
        <select id="filterRole" class="px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-lg text-white text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            <option value="">Semua Role</option>
            @foreach(config('roles.list') as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
        
        <button onclick="resetFilters()" 
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-500 text-white text-sm font-medium rounded-lg transition border border-gray-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Reset
        </button>
    </div>

    <div class="bg-gray-700/60 rounded-xl border border-gray-600 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800/80 text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">User</th>
                        <th class="px-4 py-3 text-left font-medium hidden md:table-cell">Role</th>
                        <th class="px-4 py-3 text-left font-medium hidden lg:table-cell">Email</th>
                        <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">Joined</th>
                        <th class="px-4 py-3 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-600" id="userTableBody">
                    @forelse($users as $user)
                    <tr class="user-row" data-role="{{ $user->role ?? '' }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-medium text-white truncate">
                                        {{ $user->name }}
                                        @if($user->id === $currentUser->id)
                                            <span class="text-xs text-emerald-400">(Anda)</span>
                                        @endif
                                    </div>
                                    <div class="text-gray-500 text-xs md:hidden truncate">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            @php
                                $roleClass = 'bg-gray-800 text-gray-300 border-gray-600';
                                if ($user->role === 'admin') $roleClass = 'role-admin';
                                elseif ($user->role === 'manager') $roleClass = 'role-manager';
                                elseif ($user->role && str_starts_with($user->role, 'ketua_')) $roleClass = 'role-ketua';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 text-xs rounded-md border {{ $roleClass }}">
                                {{ config('roles.list.' . $user->role) ?? ucfirst($user->role ?? 'No Role') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 hidden lg:table-cell text-gray-300 truncate max-w-[200px]">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-3 text-gray-300 whitespace-nowrap hidden sm:table-cell">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                @if($canEdit($user))
                                <button onclick="openEditRoleModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->role }}')" 
                                        class="p-2 text-gray-400 hover:text-emerald-400 hover:bg-emerald-900/20 rounded-lg transition" 
                                        title="Edit Role">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                @else
                                <span class="p-2 text-gray-600 cursor-not-allowed" 
                                      title="{{ $user->id === $currentUser->id ? 'Tidak dapat mengedit akun sendiri' : ($currentUser->role === 'manager' ? 'Manager hanya dapat mengedit Ketua' : 'Tidak dapat mengedit Admin') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </span>
                                @endif

                                @if($canDelete($user))
                                <button onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')" 
                                        class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-900/20 rounded-lg transition" 
                                        title="Hapus User">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                                @else
                                <span class="p-2 text-gray-600 cursor-not-allowed" 
                                      title="{{ $user->id === $currentUser->id ? 'Tidak dapat menghapus akun sendiri' : ($currentUser->role === 'manager' ? 'Manager hanya dapat menghapus Ketua' : 'Tidak dapat menghapus Admin') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">Belum ada user</p>
                            <button onclick="openAddUserModal()" class="mt-4 text-emerald-400 hover:text-emerald-300 text-sm font-medium">
                                + Tambah User Pertama
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-4 py-3 border-t border-gray-600">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

<div id="addUserModal" class="modal fixed inset-0 z-[80] hidden flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-gray-800 rounded-2xl border border-gray-700 w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold text-white">Tambah User Baru</h3>
                <button onclick="closeAddUserModal()" class="text-gray-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="addUserForm" action="{{ route('dashboard.users.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required 
                           class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                           placeholder="Masukkan nama user">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" required 
                           class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                           placeholder="user@example.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition"
                           placeholder="Minimal 8 karakter">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Role</label>
                    <select name="role" required 
                            class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        <option value="">Pilih role...</option>
                        @foreach($getAssignableRoles() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($currentUser->role === 'admin')
                        <p class="text-xs text-gray-500 mt-1">💡 Anda dapat membuat user dengan role Manager atau Ketua</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1">💡 Sebagai Manager, Anda hanya dapat membuat akun Ketua</p>
                    @endif
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAddUserModal()" 
                            class="flex-1 px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
                        Tambah User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editRoleModal" class="modal fixed inset-0 z-[80] hidden flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-gray-800 rounded-2xl border border-gray-700 w-full max-w-md">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold text-white">Edit Role User</h3>
                <button onclick="closeEditRoleModal()" class="text-gray-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="editRoleForm" method="POST" class="space-y-4">
                @csrf @method('PATCH')
                
                <div class="p-4 bg-gray-700/50 rounded-lg border border-gray-600">
                    <p class="text-sm text-gray-400">User</p>
                    <p id="editUserName" class="text-white font-medium"></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Role Baru</label>
                    <select name="role" id="editRoleSelect" required 
                            class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        @foreach($getAssignableRoles() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($currentUser->role === 'admin')
                        <p class="text-xs text-gray-500 mt-1">💡 Anda dapat mengubah role menjadi Manager atau Ketua</p>
                    @else
                        <p class="text-xs text-gray-500 mt-1">💡 Sebagai Manager, Anda hanya dapat mengatur role Ketua</p>
                    @endif
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditRoleModal()" 
                            class="flex-1 px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openAddUserModal() {
        document.getElementById('addUserModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeAddUserModal() {
        document.getElementById('addUserModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('addUserForm')?.reset();
    }
    
    function openEditRoleModal(userId, userName, currentRole) {
        document.getElementById('editUserName').textContent = userName;
        document.getElementById('editRoleSelect').value = currentRole;
        document.getElementById('editRoleForm').action = `/dashboard/users/${userId}/role`;
        
        document.getElementById('editRoleModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeEditRoleModal() {
        document.getElementById('editRoleModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Hapus user ini?',
            text: `User "${userName}" akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#4b5563',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            background: '#1f2937',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/dashboard/users/${userId}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    const searchInput = document.getElementById('searchInput');
    const filterRole = document.getElementById('filterRole');
    const userRows = document.querySelectorAll('#userTableBody tr[data-role]');
    
    function filterUsers() {
        const query = (searchInput?.value || '').toLowerCase().trim();
        const roleFilter = filterRole?.value || '';
        
        userRows.forEach(row => {
            const name = row.querySelector('.font-medium.text-white')?.textContent.toLowerCase() || '';
            const email = row.querySelector('.text-gray-500')?.textContent.toLowerCase() || '';
            const role = row.dataset.role;
            
            const matchesSearch = !query || name.includes(query) || email.includes(query);
            const matchesRole = !roleFilter || role === roleFilter;
            
            row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
        });
    }
    
    function resetFilters() {
        if (searchInput) searchInput.value = '';
        if (filterRole) filterRole.value = '';
        filterUsers();
    }
    
    searchInput?.addEventListener('input', filterUsers);
    filterRole?.addEventListener('change', filterUsers);

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeAddUserModal();
            closeEditRoleModal();
        }
    });
</script>
@endpush