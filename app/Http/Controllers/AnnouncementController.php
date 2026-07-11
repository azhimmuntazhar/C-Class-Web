<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'semua');

        Announcement::autoArchiveExpired();

        $query = Announcement::with('user');

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        if ($filter === 'baru') {
            $query->where('status', 'baru')->where('expires_at', '>', now());
        } elseif ($filter === 'arsip') {
            $query->where(function ($q) {
                $q->where('status', 'arsip')
                  ->orWhere('expires_at', '<=', now());
            });
        }

        $announcements = $query->latest()->paginate(10);

        $baseQuery = ($user->role === 'admin')
            ? Announcement::query()
            : Announcement::where('user_id', $user->id);

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'aktif' => (clone $baseQuery)->where('status', 'baru')->where('expires_at', '>', now())->count(),
            'arsip' => (clone $baseQuery)->where(function ($q) {
                $q->where('status', 'arsip')->orWhere('expires_at', '<=', now());
            })->count(),
        ];

        return view('dashboard.announcements', compact('announcements', 'stats', 'filter'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string|min:5',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'link'        => 'nullable|url|max:500',
            'duration'    => 'required|in:1w,2w,1m,3m,6m,custom',
            'custom_date' => 'nullable|date|after:now',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->compressAndStore($request->file('image'));
        }

        $expiresAt = $this->calculateExpiresAt($validated['duration'], $validated['custom_date'] ?? null);

        Announcement::create([
            'title'      => $validated['title'],
            'content'    => $validated['content'],
            'image'      => $imagePath,
            'link'       => $validated['link'] ?? null,
            'expires_at' => $expiresAt,
            'user_id'    => Auth::id(),
        ]);

        return back()->with('success', 'Pengumuman berhasil dibuat! 📢');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $announcement->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string|min:5',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'link'        => 'nullable|url|max:500',
            'duration'    => 'required|in:1w,2w,1m,3m,6m,custom',
            'custom_date' => 'nullable|date|after:now',
            'remove_image' => 'nullable|boolean',
        ]);

        $imagePath = $announcement->image;

        if ($request->boolean('remove_image')) {
            if ($announcement->image && Storage::disk('public')->exists($announcement->image)) {
                Storage::disk('public')->delete($announcement->image);
            }
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            if ($announcement->image && Storage::disk('public')->exists($announcement->image)) {
                Storage::disk('public')->delete($announcement->image);
            }
            $imagePath = $this->compressAndStore($request->file('image'));
        }

        $expiresAt = $this->calculateExpiresAt($validated['duration'], $validated['custom_date'] ?? null);

        $announcement->update([
            'title'      => $validated['title'],
            'content'    => $validated['content'],
            'image'      => $imagePath,
            'link'       => $validated['link'] ?? null,
            'expires_at' => $expiresAt,
            'status'     => 'baru',
        ]);

        return back()->with('success', 'Pengumuman berhasil diperbarui! ✨');
    }

    public function archive(Announcement $announcement)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $announcement->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin.');
        }

        $announcement->update(['status' => 'arsip']);

        return back()->with('success', 'Pengumuman berhasil diarsipkan! 📁');
    }

    public function destroy(Announcement $announcement)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $announcement->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin.');
        }

        if ($announcement->image && Storage::disk('public')->exists($announcement->image)) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus! 🗑️');
    }

    public static function getActiveForPublic()
    {
        Announcement::autoArchiveExpired();

        return Announcement::active()->with('user')->take(5)->get();
    }

    private function compressAndStore($image): string
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->read($image->getPathname());

        if ($img->width() > 1200) {
            $img->scaleDown(width: 1200);
        }

        $extension = strtolower($image->getClientOriginalExtension());
        $filename = 'announcement_' . time() . '_' . uniqid() . '.' . $extension;
        $path = 'announcements/' . $filename;

        $compressed = match ($extension) {
            'jpg', 'jpeg' => $img->toJpeg(80),
            'png'         => $img->toPng(),
            'webp'        => $img->toWebp(80),
            default       => $img->toJpeg(80),
        };

        Storage::disk('public')->put($path, (string) $compressed);

        return $path;
    }

    private function calculateExpiresAt(string $duration, ?string $customDate): \Carbon\Carbon
    {
        return match ($duration) {
            '1w'     => now()->addWeek(),
            '2w'     => now()->addWeeks(2),
            '1m'     => now()->addMonth(),
            '3m'     => now()->addMonths(3),
            '6m'     => now()->addMonths(6),
            'custom' => $customDate ? \Carbon\Carbon::parse($customDate) : now()->addWeek(),
        };
    }
}