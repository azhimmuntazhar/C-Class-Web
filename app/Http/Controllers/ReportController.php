<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category' => 'required|in:saran,bug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'reporter_name' => 'nullable|string|max:255',
            'reporter_email' => 'nullable|email|max:255',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image->getPathname());

            $img->scaleDown(width: 1200);

            $filename = 'report_' . time() . '_' . uniqid() . '.webp';
            $path = 'reports/' . $filename;

            Storage::disk('public')->put($path, (string) $img->toWebp(80));
            $validated['image'] = $path;
        }

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
            $validated['reporter_name'] = $validated['reporter_name'] ?? auth()->user()->name;
            $validated['reporter_email'] = $validated['reporter_email'] ?? auth()->user()->email;
        }

        Report::create($validated);

        return back()->with('success', 'Laporan berhasil dikirim! Terima kasih atas masukan Anda. 🎉');
    }

    public function adminIndex(Request $request)
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak.');
        }

        $filter = $request->get('filter', 'semua');
        $category = $request->get('category', 'semua');
        $search = $request->get('search');

        $query = Report::latest();

        if ($filter !== 'semua') {
            $query->where('status', $filter);
        }

        if ($category !== 'semua') {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reporter_name', 'like', "%{$search}%");
            });
        }

        $reports = $query->paginate(10);

        $stats = [
            'total' => Report::count(),
            'baru' => Report::where('status', 'baru')->count(),
            'diproses' => Report::where('status', 'diproses')->count(),
            'selesai' => Report::where('status', 'selesai')->count(),
            'saran' => Report::where('category', 'saran')->count(),
            'bug' => Report::where('category', 'bug')->count(),
        ];

        return view('dashboard.reports', compact('reports', 'stats', 'filter', 'category', 'search'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'status' => 'required|in:baru,diproses,selesai',
        ]);

        $report->update(['status' => $validated['status']]);

        return back()->with('success', 'Status laporan berhasil diperbarui! ✨');
    }

    public function destroy(Report $report)
    {
        $user = auth()->user();
        
        if (!in_array($user->role, ['admin', 'manager'])) {
            abort(403, 'Akses ditolak.');
        }

        if ($report->image && Storage::disk('public')->exists($report->image)) {
            Storage::disk('public')->delete($report->image);
        }

        $report->delete();

        return back()->with('success', 'Laporan berhasil dihapus! 🗑️');
    }
}