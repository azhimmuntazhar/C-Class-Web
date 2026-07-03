<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GalleryController extends Controller
{
    private const MAX_WIDTH = 1920;
    private const MAX_HEIGHT = 1080;
    private const JPEG_QUALITY = 80;
    private const WEBP_QUALITY = 80;

    private const ALLOWED_IMAGE_MIMES = ['jpeg', 'png', 'jpg', 'webp'];
    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];

    private function getImageManager(): ImageManager
    {
        return new ImageManager(new Driver());
    }

    public function index()
    {
        $galleries = Gallery::latest()->get();

        return view('gallery.doksli', compact('galleries'));
    }

    public function create()
    {
        return view('gallery.createdoksli');
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => [
                'required',
                'file',
                'mimes:' . implode(',', self::ALLOWED_IMAGE_EXTENSIONS),
                'mimetypes:image/jpeg,image/png,image/jpg,image/webp',
                'max:5120',
            ],
        ], [
            'image.mimes' => 'Hanya file gambar (JPG, PNG, WebP) yang diperbolehkan.',
            'image.mimetypes' => 'Format file tidak valid. Hanya gambar yang diterima.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $file = $request->file('image');
        if ($this->isVideoFile($file)) {
            return back()->withInput()->withErrors([
                'image' => 'Upload video tidak diperbolehkan. Hanya gambar yang diterima.'
            ]);
        }

        $path = $this->compressAndStore($file);

        Gallery::create([
            'title' => $validated['title'],
            'image' => $path,
        ]);

        return back()->with('success', 'Gambar berhasil diupload & dikompresi! 🎉');
    }

    public function storePublic(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => [
                'required',
                'file',
                'mimes:' . implode(',', self::ALLOWED_IMAGE_EXTENSIONS),
                'mimetypes:image/jpeg,image/png,image/jpg,image/webp',
                'max:5120',
            ],
        ], [
            'image.mimes' => 'Hanya file gambar (JPG, PNG, WebP) yang diperbolehkan.',
            'image.mimetypes' => 'Format file tidak valid.',
            'image.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        $file = $request->file('image');

        if ($this->isVideoFile($file)) {
            return redirect()->back()->withInput()->withErrors([
                'image' => 'Upload video tidak diperbolehkan.'
            ]);
        }

        $path = $this->compressAndStore($file);

        Gallery::create([
            'title' => $validated['title'],
            'image' => $path,
        ]);

        return redirect()->route('galeri')->with('success', 'Gambar berhasil diupload! ');
    }

    public function destroy(Gallery $gallery)
    {
        if (!in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak.');
        }

        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return redirect()->route('dashboard.gallery')->with('success', 'Gambar berhasil dihapus! 🗑️');
    }

    private function isVideoFile($file): bool
    {
        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        $videoMimeTypes = [
            'video/mp4',
            'video/webm',
            'video/ogg',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-flv',
            'video/3gpp',
            'video/x-matroska',
        ];

        $videoExtensions = ['mp4', 'webm', 'avi', 'mov', 'mkv', 'flv', 'wmv', '3gp', 'm4v'];

        return in_array($mimeType, $videoMimeTypes) || in_array($extension, $videoExtensions);
    }

    private function compressAndStore($image): string
    {
        $manager = $this->getImageManager();
        $img = $manager->read($image->getPathname());

        $originalWidth = $img->width();
        $originalHeight = $img->height();
        $extension = strtolower($image->getClientOriginalExtension());

        if ($originalWidth > self::MAX_WIDTH || $originalHeight > self::MAX_HEIGHT) {
            $img->scaleDown(self::MAX_WIDTH, self::MAX_HEIGHT);
        }

        $filename = 'gallery_' . time() . '_' . uniqid() . '.' . $extension;
        $path = 'galleries/' . $filename;

        $compressed = match($extension) {
            'jpg', 'jpeg' => $img->toJpeg(self::JPEG_QUALITY),
            'png' => $img->toPng(),
            'webp' => $img->toWebp(self::WEBP_QUALITY),
            default => $img->toJpeg(self::JPEG_QUALITY),
        };

        Storage::disk('public')->put($path, (string) $compressed);

        return $path;
    }
}