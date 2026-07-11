@extends('layouts.sidebar')

@section('title', 'Kelola Gallery')

@push('styles')
<style>
    /* Gallery Card Hover */
    .gallery-item {
        transition: all 0.3s ease;
    }
    .gallery-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px -5px rgba(0, 0, 0, 0.4);
    }
    
    /* Image Zoom */
    .gallery-item:hover .gallery-img {
        transform: scale(1.08);
    }
    .gallery-img {
        transition: transform 0.5s ease;
        will-change: transform;
    }
    
    /* Staggered Animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .gallery-item {
        animation: fadeInUp 0.4s ease-out forwards;
        opacity: 0;
    }
    
    /* Lightbox */
    #lightbox {
        transition: opacity 0.3s ease;
    }
    #lightbox.hidden {
        opacity: 0;
        pointer-events: none;
    }
    #lightbox:not(.hidden) {
        opacity: 1;
        pointer-events: auto;
    }
    
    /* Upload Drag & Drop */
    .upload-zone {
        transition: all 0.3s ease;
    }
    .upload-zone.drag-over {
        border-color: #10b981;
        background-color: rgba(16, 185, 129, 0.1);
    }
    
    /* Skeleton Loader */
    .skeleton {
        background: linear-gradient(90deg, rgba(55,65,81,1) 25%, rgba(75,85,99,0.5) 50%, rgba(55,65,81,1) 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
@endpush

@section('content')
<div class="p-4 md:p-8">
    
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-900/40 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                Kelola Gallery
            </h1>
            <p class="text-gray-400 mt-1">Moderasi dan kelola koleksi dokumentasi doksli</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3">
            <!-- Search -->
            <div class="relative">
                <input type="text" id="searchGallery" placeholder="Cari judul..." 
                       class="w-48 md:w-64 px-4 py-2.5 pl-10 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <!-- Upload Button -->
            <button onclick="openUploadModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition shadow-md hover:shadow-lg active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Upload Gambar
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Total Gambar</p>
            <p class="text-2xl font-bold text-white">{{ $galleries->count() }}</p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Ditambahkan Bulan Ini</p>
            <p class="text-2xl font-bold text-blue-400">
                {{ $galleries->filter(fn($g) => $g->created_at->isCurrentMonth())->count() }}
            </p>
        </div>
        <div class="bg-gray-700/60 p-4 rounded-xl border border-gray-600">
            <p class="text-gray-400 text-xs uppercase">Upload Terbaru</p>
            <p class="text-lg font-bold text-emerald-400">
                {{ $galleries->first()?->created_at?->diffForHumans() ?? '-' }}
            </p>
        </div>
    </div>

    <!-- Gallery Grid -->
    @if($galleries->count() > 0)
        <div id="galleryGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($galleries as $index => $item)
            <div class="gallery-item group bg-gray-700/60 rounded-xl overflow-hidden border border-gray-600 hover:border-emerald-500/50 cursor-pointer"
                 data-title="{{ strtolower($item->title) }}"
                 style="animation-delay: {{ $index * 0.05 }}s"
                 onclick="openLightbox({{ $item->id }}, '{{ asset('storage/' . $item->image) }}', '{{ addslashes($item->title) }}', '{{ $item->created_at->format('d M Y, H:i') }}')">
                
                <!-- Image -->
                <div class="aspect-square overflow-hidden bg-gray-800 relative">
                    <div class="absolute inset-0 skeleton"></div>
                    <img src="{{ asset('storage/' . $item->image) }}" 
                         alt="{{ $item->title }}"
                         loading="lazy"
                         class="gallery-img w-full h-full object-cover"
                         onload="this.parentElement.querySelector('.skeleton').style.display='none'">
                    
                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-3">
                        <p class="text-white font-medium text-sm truncate">{{ $item->title }}</p>
                        <p class="text-gray-300 text-xs">{{ $item->created_at->format('d M Y') }}</p>
                        <div class="flex gap-2 mt-2">
                            <button class="flex-1 px-2 py-1.5 bg-white/20 hover:bg-white/30 text-white text-xs rounded-lg backdrop-blur-sm transition"
                                    onclick="event.stopPropagation(); openLightbox({{ $item->id }}, '{{ asset('storage/' . $item->image) }}', '{{ addslashes($item->title) }}', '{{ $item->created_at->format('d M Y, H:i') }}')">
                                 Lihat
                            </button>
                            <form action="{{ route('gallery.destroy', $item->id) }}" method="POST" class="delete-form" onsubmit="event.stopPropagation()">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex-1 px-2 py-1.5 bg-red-500/20 hover:bg-red-500/40 text-red-300 text-xs rounded-lg backdrop-blur-sm transition">
                                     Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Badge -->
                    <span class="absolute top-2 right-2 px-2 py-0.5 bg-black/50 text-white text-[10px] rounded-md backdrop-blur-sm">
                        #{{ $loop->iteration }}
                    </span>
                </div>
                
                <!-- Info (Mobile) -->
                <div class="p-3 md:hidden">
                    <h4 class="font-medium text-white text-sm truncate">{{ $item->title }}</h4>
                    <p class="text-gray-500 text-xs">{{ $item->created_at->format('d M Y') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-20 bg-gray-700/40 rounded-2xl border border-dashed border-gray-600">
            <div class="w-24 h-24 mx-auto mb-6 bg-gray-700 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">Gallery masih kosong</h3>
            <p class="text-gray-400 text-sm mb-6 max-w-sm mx-auto">Mulai koleksi dokumentasi dengan mengupload gambar pertama</p>
            <button onclick="openUploadModal()" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Upload Gambar Pertama
            </button>
        </div>
    @endif
</div>

<!-- 📤 UPLOAD MODAL -->
<div id="uploadModal" class="fixed inset-0 z-[80] hidden flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
    <div class="bg-gray-800 rounded-2xl border border-gray-700 w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Upload Gambar
                </h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-white transition p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form id="uploadForm" action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Judul Gambar</label>
                    <input type="text" name="title" required 
                           class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500 outline-none transition"
                           placeholder="Contoh: Dokumentasi Workshop AI 2026">
                </div>
                
                <!-- Upload Zone -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Pilih Gambar</label>
                    <div id="uploadZone" class="upload-zone border-2 border-dashed border-gray-600 rounded-xl p-8 text-center cursor-pointer hover:border-emerald-500/50 transition"
                         onclick="document.getElementById('imageInput').click()">
                        
                        <!-- Default State -->
                        <div id="uploadDefault">
                            <svg class="w-12 h-12 mx-auto text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-400 text-sm mb-1">Klik atau drag & drop gambar di sini</p>
                            <p class="text-gray-500 text-xs">PNG, JPG, WEBP (Maks. 5MB)</p>
                        </div>
                        
                        <!-- Preview State -->
                        <div id="uploadPreview" class="hidden">
                            <img id="imagePreview" class="max-h-48 mx-auto rounded-lg mb-3 object-contain" src="" alt="Preview" loading="lazy">
                            <p id="fileName" class="text-gray-300 text-sm font-medium"></p>
                            <p id="fileSize" class="text-gray-500 text-xs mt-1"></p>
                        </div>
                    </div>
                    <input type="file" name="image" id="imageInput" accept="image/*" required class="hidden">
                    @error('image') 
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Actions -->
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeUploadModal()" 
                            class="flex-1 px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" id="uploadBtn"
                            class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 🔦 LIGHTBOX MODAL -->
<div id="lightbox" class="fixed inset-0 z-[80] hidden flex items-center justify-center bg-black/90 backdrop-blur-md p-4">
    <!-- Close -->
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white/70 hover:text-white p-2 rounded-lg hover:bg-white/10 transition z-10">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
    
    <!-- Image -->
    <div class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center">
        <img id="lightboxImg" src="" alt="" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl" loading="lazy">
        
        <!-- Caption -->
        <div class="mt-4 text-center">
            <h3 id="lightboxTitle" class="text-white font-semibold text-lg"></h3>
            <p id="lightboxDate" class="text-gray-400 text-sm mt-1"></p>
        </div>
        
        <!-- Actions -->
        <div class="flex gap-3 mt-4">
            <a id="lightboxDownload" href="" download 
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm rounded-lg transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download
            </a>
        </div>
    </div>
    
    <!-- Navigation Arrows -->
    <button id="prevBtn" onclick="navigateLightbox(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white p-3 rounded-full hover:bg-white/10 transition hidden md:block">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </button>
    <button id="nextBtn" onclick="navigateLightbox(1)" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white p-3 rounded-full hover:bg-white/10 transition hidden md:block">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    </button>
</div>

@push('scripts')
<script>
    @php
        $galleryJson = $galleries->map(fn($g) => [
            'id' => $g->id,
            'title' => $g->title,
            'image' => asset('storage/' . $g->image),
            'created_at' => $g->created_at->format('d M Y, H:i')
        ])->values();
    @endphp
    
    const galleryData = @json($galleryJson);
    
    let currentLightboxIndex = 0;
    
    // =====================
    // UPLOAD MODAL
    // =====================
    function openUploadModal() {
        document.getElementById('uploadModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('uploadForm')?.reset();
        document.getElementById('uploadDefault').classList.remove('hidden');
        document.getElementById('uploadPreview').classList.add('hidden');
    }
    
    // Image Preview
    const imageInput = document.getElementById('imageInput');
    const uploadZone = document.getElementById('uploadZone');
    
    imageInput?.addEventListener('change', function() {
        const file = this.files[0];
        if (file) showPreview(file);
    });
    
    function showPreview(file) {
        if (file.size > 5 * 1024 * 1024) {
            Swal.fire({ 
                icon: 'error', 
                title: 'File terlalu besar', 
                text: 'Maksimal 5MB', 
                background: '#1f2937', 
                color: '#fff' 
            });
            imageInput.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            document.getElementById('uploadDefault').classList.add('hidden');
            document.getElementById('uploadPreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
    
    // Drag & Drop
    uploadZone?.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('drag-over');
    });
    
    uploadZone?.addEventListener('dragleave', () => {
        uploadZone.classList.remove('drag-over');
    });
    
    uploadZone?.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            imageInput.files = e.dataTransfer.files;
            showPreview(file);
        }
    });
    
    // =====================
    // LIGHTBOX
    // =====================
    function openLightbox(id, src, title, date) {
        currentLightboxIndex = galleryData.findIndex(item => item.id === id);
        
        document.getElementById('lightboxImg').src = src;
        document.getElementById('lightboxTitle').textContent = title;
        document.getElementById('lightboxDate').textContent = date;
        document.getElementById('lightboxDownload').href = src;
        
        document.getElementById('lightbox').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        updateNavButtons();
    }
    
    function closeLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    function navigateLightbox(direction) {
        const newIndex = currentLightboxIndex + direction;
        if (newIndex >= 0 && newIndex < galleryData.length) {
            const item = galleryData[newIndex];
            openLightbox(item.id, item.image, item.title, item.created_at);
        }
    }
    
    function updateNavButtons() {
        document.getElementById('prevBtn').style.display = currentLightboxIndex > 0 ? 'block' : 'none';
        document.getElementById('nextBtn').style.display = currentLightboxIndex < galleryData.length - 1 ? 'block' : 'none';
    }
    
    // =====================
    // SEARCH
    // =====================
    const searchInput = document.getElementById('searchGallery');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    searchInput?.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        galleryItems.forEach(item => {
            const title = item.dataset.title || '';
            if (!query || title.includes(query)) {
                item.style.display = '';
                item.style.opacity = '1';
            } else {
                item.style.opacity = '0';
                setTimeout(() => { item.style.display = 'none'; }, 200);
            }
        });
    });
    
    // =====================
    // DELETE CONFIRMATION
    // =====================
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus gambar ini?',
                text: 'Gambar akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
    
    // =====================
    // KEYBOARD SHORTCUTS
    // =====================
    document.addEventListener('keydown', (e) => {
        const lightbox = document.getElementById('lightbox');
        if (!lightbox.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') navigateLightbox(-1);
            if (e.key === 'ArrowRight') navigateLightbox(1);
        }
        
        if (e.key === 'Escape') {
            closeUploadModal();
        }
    });
    
    // Close modals on backdrop click
    document.getElementById('lightbox')?.addEventListener('click', (e) => {
        if (e.target.id === 'lightbox') closeLightbox();
    });
    document.getElementById('uploadModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'uploadModal') closeUploadModal();
    });
</script>
@endpush

@endsection