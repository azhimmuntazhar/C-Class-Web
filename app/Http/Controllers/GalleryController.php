<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager; // v3 import

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest()->get();
        return view('gallery.doksli', compact('galleries'));
        return view('galeri', compact('galleries'));
    }

    public function create()
    {
        return view('gallery.createdoksli');
    }

    public function store(Request $request)
    {
    $request->validate([
        'title' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
    ]);

    $file = $request->file('image');
    
    // Generate nama file
    $filename = 'gallery_' . time() . '_' . uniqid() . '.jpg';
    $path = 'galleries/' . $filename;
    $fullPath = storage_path('app/public/' . $path);
    
    // Pastikan direktori ada
    if (!file_exists(dirname($fullPath))) {
        mkdir(dirname($fullPath), 0755, true);
    }
    
    // Compress menggunakan GD native PHP
    $source = imagecreatefromstring(file_get_contents($file->getRealPath()));
    
    // Resize
    $width = imagesx($source);
    $height = imagesy($source);
    $maxSize = 1200;
    
    if ($width > $height) {
        if ($width > $maxSize) {
            $newWidth = $maxSize;
            $newHeight = floor($height * ($maxSize / $width));
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }
    } else {
        if ($height > $maxSize) {
            $newHeight = $maxSize;
            $newWidth = floor($width * ($maxSize / $height));
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }
    }
    
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preserve transparency untuk PNG
    imagealphablending($newImage, false);
    imagesavealpha($newImage, true);
    
    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    // Simpan sebagai JPG dengan quality 85
    imagejpeg($newImage, $fullPath, 85);
    
    // Free memory
    imagedestroy($source);
    imagedestroy($newImage);

    Gallery::create([
        'title' => $request->title,
        'image' => $path,
    ]);

    return redirect()->route('gallery.doksli')->with('success', 'Gambar berhasil diupload!');
    }

    //upload public
    public function storePublic(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        // Simpan gambar (sama seperti store)
        $image = $request->file('image');
        $filename = 'gallery_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('galleries', $filename, 'public');

        Gallery::create([
            'title' => $request->title,
            'image' => $path,
        ]);

        return redirect()->route('galeri')->with('success', 'Gambar berhasil diupload!');
    }

    public function destroy(Gallery $gallery)
    {
        // Hapus file gambar dari storage
        Storage::disk('public')->delete($gallery->image);
        
        // Hapus data dari database
        $gallery->delete();

        return redirect()->route('gallery.doksli')->with('success', 'Gambar berhasil dihapus!');
    }
}