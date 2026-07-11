@extends('layouts.sidebar')

@section('title', 'Kelola Pengumuman')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeInUp 0.4s ease-out forwards;
        opacity: 0;
    }
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    }
    /* Image preview in upload */
    .upload-zone.drag-over {
        border-color: #f97316;
        background-color: rgba(249, 115, 22, 0.05);
    }
</style>
@endpush

@section('content')
<div class="p-4 md:p-8">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 animate-fade-in">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                </div>
                Kelola Pengumuman
            </h1>
            <p class="text-gray-400 mt-1">
                @if(auth()->user()->role === 'admin')
                    Kelola semua pengumuman dari seluruh divisi
                @else
                    Kelola pengumuman Anda
                @endif
            </p>
        </div>
        
        <button onclick="openCreateModal()" 
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition shadow-md hover:shadow-lg active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Pengumuman
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 animate-fade-in">
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Total</p>
            <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Aktif</p>
            <p class="text-2xl font-bold text-orange-400">{{ $stats['aktif'] }}</p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Arsip</p>
            <p class="text-2xl font-bold text-gray-400">{{ $stats['arsip'] }}</p>
        </div>
    </div>

    <div class="flex gap-2 mb-6 animate-fade-in overflow-x-auto pb-1">
        @foreach(['semua' => 'Semua', 'baru' => 'Aktif', 'arsip' => 'Arsip'] as $key => $label)
        <a href="{{ route('dashboard.announcements', ['filter' => $key]) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition whitespace-nowrap {{ $filter === $key ? 'bg-orange-600 text-white' : 'bg-gray-700/60 text-gray-400 hover:text-white' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    @if($announcements->count() > 0)
        <div class="space-y-4">
            @foreach($announcements as $announcement)
            @php
                $isArchived = $announcement->isArchived();
                $canEdit = auth()->user()->role === 'admin' || $announcement->user_id === auth()->id();
            @endphp
            <div class="card-hover bg-gray-700/60 rounded-xl border border-gray-600 overflow-hidden animate-fade-in {{ $isArchived ? 'opacity-60' : '' }}">
                <div class="p-5">
                    
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                @if($isArchived)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-800 text-gray-400 text-xs font-medium rounded-md border border-gray-700">📁 Arsip</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-900/40 text-orange-300 text-xs font-medium rounded-md border border-orange-700/50">📢 Aktif</span>
                                @endif
                                <span class="text-xs text-gray-500">{{ $announcement->remaining_time }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-white break-words">{{ $announcement->title }}</h3>
                            @if(auth()->user()->role === 'admin')
                            <p class="text-xs text-gray-500 mt-1">
                                Oleh: {{ $announcement->user->name }} 
                                ({{ config('roles.list.' . $announcement->user->role) ?? ucfirst($announcement->user->role) }})
                            </p>
                            @endif
                        </div>
                    </div>

                    @if($announcement->hasImage())
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $announcement->image) }}" 
                             alt="{{ $announcement->title }}"
                             class="w-full max-h-64 object-cover rounded-lg cursor-pointer hover:opacity-90 transition"
                             onclick="window.open('{{ asset('storage/' . $announcement->image) }}', '_blank')">
                    </div>
                    @endif

                    <p class="text-gray-400 text-sm mb-3 whitespace-pre-wrap line-clamp-3">{{ $announcement->content }}</p>
                    @if($announcement->hasLink())
                    <a href="{{ $announcement->link }}" target="_blank" 
                       class="inline-flex items-center gap-2 text-blue-400 hover:text-blue-300 text-sm mb-3 transition hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        {{ Str::limit($announcement->link, 50) }}
                    </a>
                    @endif

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-600">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $announcement->created_at->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1 {{ $isArchived ? 'text-red-400' : 'text-orange-400' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Exp: {{ $announcement->expires_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        @if($canEdit)
                        <div class="flex gap-2">
                            <button onclick='openEditModal(@json($announcement))' 
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-blue-400 hover:text-blue-300 hover:bg-blue-900/20 rounded-lg transition border border-blue-800/30">
                                ✏️ Edit
                            </button>
                            
                            @if(!$isArchived)
                            <form action="{{ route('dashboard.announcements.archive', $announcement) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-gray-400 hover:text-gray-300 hover:bg-gray-700/50 rounded-lg transition border border-gray-600">
                                    📁 Arsip
                                </button>
                            </form>
                            @endif
                            
                            <form action="{{ route('dashboard.announcements.destroy', $announcement) }}" method="POST" class="delete-form inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded-lg transition border border-red-800/30">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($announcements->hasPages())
        <div class="mt-6">
            {{ $announcements->withQueryString()->links() }}
        </div>
        @endif
    @else
        <div class="text-center py-16 bg-gray-700/40 rounded-2xl border border-dashed border-gray-600 animate-fade-in">
            <svg class="w-20 h-20 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-white mb-2">Belum ada pengumuman</h3>
            <p class="text-gray-400 text-sm mb-4">Buat pengumuman pertama Anda</p>
            <button onclick="openCreateModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition">
                + Buat Pengumuman
            </button>
        </div>
    @endif
</div>

<div id="announcementModal" class="fixed inset-0 z-[80] hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-gray-800 rounded-2xl border border-gray-700 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 id="modalTitle" class="text-xl font-bold text-white">Buat Pengumuman</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="announcementForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                <input type="hidden" name="remove_image" id="removeImageInput" value="0">
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Judul <span class="text-red-400">*</span></label>
                    <input type="text" name="title" id="inputTitle" required maxlength="255"
                           class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-orange-500 outline-none transition"
                           placeholder="Contoh: Libur Nasional Hari Raya">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Isi Pengumuman <span class="text-red-400">*</span></label>
                    <textarea name="content" id="inputContent" rows="4" required minlength="5"
                              class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-orange-500 outline-none transition resize-none"
                              placeholder="Tulis isi pengumuman..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Gambar <span class="text-gray-500 text-xs">(Opsional, max 3MB)</span>
                    </label>
                    <div id="uploadZone" class="upload-zone border-2 border-dashed border-gray-600 rounded-xl p-5 text-center cursor-pointer hover:border-orange-500/50 transition"
                         onclick="document.getElementById('imageInput').click()">
                        <div id="uploadDefault">
                            <svg class="w-8 h-8 mx-auto text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-400 text-sm">Klik untuk upload gambar</p>
                            <p class="text-gray-500 text-xs mt-1">JPG, PNG, WEBP</p>
                        </div>
                        <div id="uploadPreview" class="hidden">
                            <img id="previewImg" class="max-h-40 mx-auto rounded-lg mb-2 object-contain" src="" alt="Preview">
                            <p id="previewName" class="text-gray-300 text-sm font-medium"></p>
                            <button type="button" onclick="event.stopPropagation(); removeImage()" class="mt-2 text-xs text-red-400 hover:text-red-300">✕ Hapus gambar</button>
                        </div>
                    </div>
                    <input type="file" name="image" id="imageInput" accept="image/*" class="hidden" onchange="previewUploadedImage(this)">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Link <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <input type="url" name="link" id="inputLink" maxlength="500"
                           class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-orange-500 outline-none transition"
                           placeholder="https://example.com/info-selengkapnya">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Durasi Tampil <span class="text-red-400">*</span></label>
                    <select name="duration" id="inputDuration" required 
                            class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-orange-500 outline-none transition"
                            onchange="toggleCustomDate()">
                        <option value="1w">1 Minggu (Default)</option>
                        <option value="2w">2 Minggu</option>
                        <option value="1m">1 Bulan</option>
                        <option value="3m">3 Bulan</option>
                        <option value="6m">6 Bulan</option>
                        <option value="custom">Tanggal Kustom</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pengumuman otomatis diarsipkan setelah durasi berakhir</p>
                </div>
                
                <div id="customDateWrapper" class="hidden">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Berakhir <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="custom_date" id="inputCustomDate"
                           class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-orange-500 outline-none transition">
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span id="submitBtnText">Buat Pengumuman</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('modalTitle').textContent = 'Buat Pengumuman';
        document.getElementById('submitBtnText').textContent = 'Buat Pengumuman';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('announcementForm').action = "{{ route('dashboard.announcements.store') }}";
        document.getElementById('announcementForm').reset();
        document.getElementById('removeImageInput').value = '0';
        document.getElementById('uploadDefault').classList.remove('hidden');
        document.getElementById('uploadPreview').classList.add('hidden');
        document.getElementById('customDateWrapper').classList.add('hidden');
        showModal();
    }

    function openEditModal(a) {
        document.getElementById('modalTitle').textContent = 'Edit Pengumuman';
        document.getElementById('submitBtnText').textContent = 'Simpan Perubahan';
        document.getElementById('formMethod').value = 'PATCH';
        document.getElementById('announcementForm').action = '/dashboard/announcements/' + a.id;
        document.getElementById('inputTitle').value = a.title;
        document.getElementById('inputContent').value = a.content;
        document.getElementById('inputLink').value = a.link || '';
        document.getElementById('inputDuration').value = '1w';
        document.getElementById('customDateWrapper').classList.add('hidden');
        document.getElementById('removeImageInput').value = '0';

        // Show existing image
        if (a.image) {
            document.getElementById('previewImg').src = '/storage/' + a.image;
            document.getElementById('previewName').textContent = 'Gambar saat ini';
            document.getElementById('uploadDefault').classList.add('hidden');
            document.getElementById('uploadPreview').classList.remove('hidden');
        } else {
            document.getElementById('uploadDefault').classList.remove('hidden');
            document.getElementById('uploadPreview').classList.add('hidden');
        }
        showModal();
    }

    function showModal() {
        const m = document.getElementById('announcementModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        const m = document.getElementById('announcementModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function toggleCustomDate() {
        const d = document.getElementById('inputDuration').value;
        const w = document.getElementById('customDateWrapper');
        if (d === 'custom') { w.classList.remove('hidden'); document.getElementById('inputCustomDate').required = true; }
        else { w.classList.add('hidden'); document.getElementById('inputCustomDate').required = false; }
    }

    function previewUploadedImage(input) {
        const file = input.files[0];
        if (!file) return;
        if (file.size > 3 * 1024 * 1024) {
            Swal.fire({ icon: 'error', title: 'File terlalu besar', text: 'Maksimal 3MB', background: '#1f2937', color: '#fff' });
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewName').textContent = file.name;
            document.getElementById('uploadDefault').classList.add('hidden');
            document.getElementById('uploadPreview').classList.remove('hidden');
            document.getElementById('removeImageInput').value = '0';
        };
        reader.readAsDataURL(file);
    }

    function removeImage() {
        document.getElementById('imageInput').value = '';
        document.getElementById('uploadDefault').classList.remove('hidden');
        document.getElementById('uploadPreview').classList.add('hidden');
        document.getElementById('removeImageInput').value = '1';
    }

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus pengumuman?', text: 'Aksi ini tidak dapat dibatalkan!',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#dc2626', cancelButtonColor: '#4b5563',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                background: '#1f2937', color: '#fff'
            }).then((r) => { if (r.isConfirmed) form.submit(); });
        });
    });

    document.getElementById('announcementModal')?.addEventListener('click', (e) => { if (e.target.id === 'announcementModal') closeModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

    const zone = document.getElementById('uploadZone');
    zone?.addEventListener('dragover', (e) => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone?.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone?.addEventListener('drop', (e) => {
        e.preventDefault(); zone.classList.remove('drag-over');
        const f = e.dataTransfer.files[0];
        if (f && f.type.startsWith('image/')) {
            document.getElementById('imageInput').files = e.dataTransfer.files;
            previewUploadedImage(document.getElementById('imageInput'));
        }
    });
</script>
@endpush

@endsection