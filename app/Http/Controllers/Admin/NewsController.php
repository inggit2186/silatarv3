<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class NewsController extends Controller
{
    /**
     * Check if user has admin access from hak_akses table.
     */
    private function userHasAccess($user): bool
    {
        return $this->checkAccess($user, ['admin', 'superadmin']);
    }

    /**
     * Check if user has news access (admin, superadmin, or humas).
     */
    private function userHasNewsAccess($user): bool
    {
        return $this->checkAccess($user, ['admin', 'superadmin', 'humas']);
    }

    /**
     * Check if user has specific access from hak_akses table.
     */
    private function checkAccess($user, array $requiredAccess): bool
    {
        $hakAkses = DB::table('hak_akses')
            ->where('user_id', $user->id)
            ->first();

        if (!$hakAkses) {
            return false;
        }

        $access = json_decode($hakAkses->akses, true);
        if (!is_array($access)) {
            return false;
        }

        foreach ($requiredAccess as $accessType) {
            if (in_array($accessType, $access)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Display news list.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$this->userHasNewsAccess($user)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $canManage = $this->userHasAccess($user);

        $query = DB::table('news')
            ->orderByDesc('created_at');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $news = $query->paginate(12);

        // Categories for filter
        $categories = [
            'featured' => 'Featured',
            'pengumuman' => 'Pengumuman',
            'kegiatan' => 'Kegiatan',
            'layanan' => 'Layanan',
            'info' => 'Informasi',
        ];

        // Stats
        $stats = [
            'total' => DB::table('news')->count(),
            'published' => DB::table('news')->where('status', 'published')->count(),
            'draft' => DB::table('news')->where('status', 'draft')->count(),
            'archived' => DB::table('news')->where('status', 'archived')->count(),
        ];

        return view('admin.news.index', [
            'title' => 'Manajemen Berita - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Berita', 'url' => null],
            ],
            'news' => $news,
            'categories' => $categories,
            'stats' => $stats,
            'canManage' => $this->userHasAccess($user),
        ]);
    }

    /**
     * Show create news form.
     */
    public function create(Request $request)
    {
        $user = $request->user();
        if (!$this->userHasNewsAccess($user)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $categories = [
            'featured' => 'Featured',
            'pengumuman' => 'Pengumuman',
            'kegiatan' => 'Kegiatan',
            'layanan' => 'Layanan',
            'info' => 'Informasi',
        ];

        return view('admin.news.create', [
            'title' => 'Tambah Berita - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Berita', 'url' => route('admin.news.index')],
                ['label' => 'Tambah', 'url' => null],
            ],
            'categories' => $categories,
            'currentUser' => $user->name,
        ]);
    }

    /**
     * Store new news.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$this->userHasNewsAccess($user)) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'in:featured,pengumuman,kegiatan,layanan,info'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_featured' => ['nullable', 'boolean'],
            'is_slideshow' => ['nullable', 'boolean'],
            'writer' => ['nullable', 'string', 'max:255'],
            'editor' => ['nullable', 'string', 'max:255'],
            'photographer' => ['nullable', 'string', 'max:255'],
            'publish_date' => ['nullable', 'date'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'news_' . Str::slug($request->title) . '_' . time();

            try {
                // Create image manager with GD driver
                $manager = new ImageManager(new Driver());
                $img = $manager->decode($image->getPathname());

                // Resize if too large (max 1200px width for news thumbnail)
                if ($img->width() > 1200) {
                    $img->resize(1200, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // Save as WebP for better compression
                $outputPath = $filename . '.webp';
                $encoded = $img->encodeUsingFormat(Format::WEBP, quality: 85);
                Storage::disk('public')->put('news/' . $outputPath, $encoded->toString());
                $imagePath = 'news/' . $outputPath;
            } catch (\Exception $e) {
                // Fallback to original file
                $ext = $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('news', $filename . '.' . $ext, 'public');
            }
        }

        $newsId = DB::table('news')->insertGetId([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . time(),
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'category' => $validated['category'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'image' => $imagePath,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
            'is_slideshow' => $request->boolean('is_slideshow'),
            'writer' => $validated['writer'] ?? null,
            'editor' => $validated['editor'] ?? null,
            'photographer' => $validated['photographer'] ?? null,
            'publish_date' => $validated['publish_date'] ?? now(),
            'author_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log activity
        $this->logActivity($user->id, 'create_news', "Membuat berita: {$validated['title']}", $newsId);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dibuat.');
    }

    /**
     * Show edit news form.
     */
    public function edit(Request $request, $id)
    {
        $user = $request->user();
        if (!$this->userHasNewsAccess($user)) {
            abort(403);
        }

        $news = DB::table('news')->where('id', $id)->first();

        if (!$news) {
            abort(404, 'Berita tidak ditemukan.');
        }

        $categories = [
            'featured' => 'Featured',
            'pengumuman' => 'Pengumuman',
            'kegiatan' => 'Kegiatan',
            'layanan' => 'Layanan',
            'info' => 'Informasi',
        ];

        return view('admin.news.edit', [
            'title' => 'Edit Berita - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Berita', 'url' => route('admin.news.index')],
                ['label' => 'Edit', 'url' => null],
            ],
            'news' => $news,
            'categories' => $categories,
        ]);
    }

    /**
     * Update news.
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        if (!$this->userHasNewsAccess($user)) {
            abort(403);
        }

        $news = DB::table('news')->where('id', $id)->first();

        if (!$news) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'in:featured,pengumuman,kegiatan,layanan,info'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_featured' => ['nullable', 'boolean'],
            'is_slideshow' => ['nullable', 'boolean'],
            'writer' => ['nullable', 'string', 'max:255'],
            'editor' => ['nullable', 'string', 'max:255'],
            'photographer' => ['nullable', 'string', 'max:255'],
            'publish_date' => ['nullable', 'date'],
        ]);

        $imagePath = $news->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $image = $request->file('image');
            $filename = 'news_' . Str::slug($request->title) . '_' . time();

            try {
                // Create image manager with GD driver
                $manager = new ImageManager(new Driver());
                $img = $manager->decode($image->getPathname());

                // Resize if too large (max 1200px width for news thumbnail)
                if ($img->width() > 1200) {
                    $img->resize(1200, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // Save as WebP for better compression
                $outputPath = $filename . '.webp';
                $encoded = $img->encodeUsingFormat(Format::WEBP, quality: 85);
                Storage::disk('public')->put('news/' . $outputPath, $encoded->toString());
                $imagePath = 'news/' . $outputPath;
            } catch (\Exception $e) {
                // Fallback to original file
                $ext = $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('news', $filename . '.' . $ext, 'public');
            }
        }

        DB::table('news')->where('id', $id)->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . time(),
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
            'category' => $validated['category'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'image' => $imagePath,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
            'is_slideshow' => $request->boolean('is_slideshow'),
            'writer' => $validated['writer'] ?? null,
            'editor' => $validated['editor'] ?? null,
            'photographer' => $validated['photographer'] ?? null,
            'publish_date' => $validated['publish_date'] ?? $news->publish_date,
            'updated_at' => now(),
        ]);

        $this->logActivity($user->id, 'update_news', "Mengupdate berita: {$validated['title']}", $id);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diupdate.');
    }

    /**
     * Delete news.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$this->userHasNewsAccess($user)) {
            abort(403);
        }

        $news = DB::table('news')->where('id', $id)->first();

        if (!$news) {
            abort(404);
        }

        // Delete image
        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        DB::table('news')->where('id', $id)->delete();

        $this->logActivity($user->id, 'delete_news', "Menghapus berita: {$news->title}", $id);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Upload image for content editor with compression.
     */
    public function uploadImage(Request $request)
    {
        $user = $request->user();
        if (!$this->userHasNewsAccess($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'image' => ['required', 'image', 'max:10240'], // 10MB max input, will compress
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = strtolower($image->getClientOriginalExtension());

            // Generate unique filename
            $filename = 'content_' . Str::random(12) . '_' . time();

            try {
                // Create image manager with GD driver
                $manager = new ImageManager(new Driver());

                // Read and process image
                $img = $manager->decode($image->getPathname());

                // Get original dimensions
                $originalWidth = $img->width();
                $originalHeight = $img->height();

                // Configurable max dimensions (balance between quality and size)
                $maxWidth = 1920;
                $maxHeight = 1080;

                // Resize if larger than max dimensions (maintain aspect ratio)
                if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
                    $img->resize($maxWidth, $maxHeight, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }

                // Quality settings
                $quality = 82; // Good quality without being too large

                // Determine output format - convert to WebP for better compression
                $outputFilename = $filename . '.webp';
                $encoded = $img->encodeUsingFormat(Format::WEBP, quality: $quality);

                // Save to storage
                $path = 'news/content/' . $outputFilename;
                Storage::disk('public')->put($path, $encoded->toString());

                // Get file size for logging
                $fileSize = strlen($encoded->toString());
                $originalSize = $image->getSize();
                $savedPercent = $originalSize > 0 ? round((1 - ($fileSize / $originalSize)) * 100, 1) : 0;

                return response()->json([
                    'success' => true,
                    'url' => asset('storage/' . $path),
                    'filename' => $outputFilename,
                    'stats' => [
                        'original_size' => $this->formatBytes($originalSize),
                        'compressed_size' => $this->formatBytes($fileSize),
                        'saved_percent' => $savedPercent > 0 ? $savedPercent . '%' : '0%',
                        'dimensions' => $img->width() . 'x' . $img->height(),
                    ]
                ]);
            } catch (\Exception $e) {
                // Fallback: save original file if compression fails
                $fallbackFilename = $filename . '.' . $extension;
                $path = $image->storeAs('news/content', $fallbackFilename, 'public');

                return response()->json([
                    'success' => true,
                    'url' => asset('storage/' . $path),
                    'filename' => $fallbackFilename,
                    'warning' => 'Image saved without compression'
                ]);
            }
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }

    /**
     * Format bytes to human readable.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) :0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Log activity.
     */
    private function logActivity($userId, $action, $description, $refId = null)
    {
        DB::table('news_logs')->insert([
            'news_id' => $refId,
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
