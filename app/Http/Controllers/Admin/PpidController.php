<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidPage;
use App\Models\PpidSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PpidController extends Controller
{
    /**
     * Display a listing of all PPID pages.
     */
    public function index()
    {
        $pages = DB::table('ppid_pages')
            ->orderBy('slug')
            ->get();

        $title = 'PPID Management - SILATAR Admin';
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'PPID', 'url' => null],
        ];

        return view('admin.ppid.index', compact('pages', 'title', 'breadcrumbs'));
    }

    /**
     * Show the form for editing a PPID page.
     */
    public function edit(string $slug)
    {
        $page = DB::table('ppid_pages')
            ->where('slug', $slug)
            ->first();

        if (!$page) {
            return redirect()->route('admin.ppid.index')
                ->with('error', 'Halaman PPID tidak ditemukan.');
        }

        $sections = DB::table('ppid_sections')
            ->where('page_id', $page->id)
            ->orderBy('sort_order')
            ->get();

        $title = "Edit: {$page->title} - SILATAR Admin";
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'PPID', 'url' => route('admin.ppid.index')],
            ['label' => $page->title, 'url' => null],
        ];

        return view('admin.ppid.edit', compact('page', 'sections', 'title', 'breadcrumbs'));
    }

    /**
     * Update the specified PPID page.
     */
    public function update(Request $request, string $slug)
    {
        $page = DB::table('ppid_pages')
            ->where('slug', $slug)
            ->first();

        if (!$page) {
            return redirect()->route('admin.ppid.index')
                ->with('error', 'Halaman PPID tidak ditemukan.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        DB::table('ppid_pages')
            ->where('id', $page->id)
            ->update([
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'is_active' => isset($validated['is_active']) ? true : false,
                'updated_at' => now(),
            ]);

        // Clear cache for this page
        Cache::forget("ppid_page_{$slug}");

        return redirect()->back()
            ->with('success', 'Halaman PPID berhasil diperbarui.');
    }

    /**
     * Store a new section for a PPID page.
     */
    public function storeSection(Request $request, string $slug): JsonResponse
    {
        $page = DB::table('ppid_pages')
            ->where('slug', $slug)
            ->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Halaman PPID tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'section_key' => 'required|string|max:100',
            'section_type' => 'required|in:text,list,card_grid,timeline,stats,table,form_fields,image',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'metadata' => 'nullable|array',
            'sort_order' => 'integer|min:0',
        ]);

        $sectionId = DB::table('ppid_sections')->insertGetId([
            'page_id' => $page->id,
            'section_key' => $validated['section_key'],
            'section_type' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'] ?? null,
            'metadata' => isset($validated['metadata']) ? json_encode($validated['metadata']) : null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Section berhasil ditambahkan.',
            'section_id' => $sectionId,
        ]);
    }

    /**
     * Update a section's content.
     */
    public function updateSection(Request $request, int $id): JsonResponse
    {
        $section = DB::table('ppid_sections')
            ->where('id', $id)
            ->first();

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'Section tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'metadata' => 'nullable|array',
            'sort_order' => 'integer|min:0',
            'is_visible' => 'boolean',
        ]);

        $updateData = [
            'updated_at' => now(),
        ];

        if (array_key_exists('title', $validated)) {
            $updateData['title'] = $validated['title'];
        }

        if (array_key_exists('content', $validated)) {
            $updateData['content'] = $validated['content'];
        }

        if (array_key_exists('metadata', $validated)) {
            $updateData['metadata'] = json_encode($validated['metadata']);
        }

        if (array_key_exists('sort_order', $validated)) {
            $updateData['sort_order'] = $validated['sort_order'];
        }

        if (array_key_exists('is_visible', $validated)) {
            $updateData['is_visible'] = $validated['is_visible'];
        }

        DB::table('ppid_sections')
            ->where('id', $id)
            ->update($updateData);

        // Clear cache for the page
        $section = DB::table('ppid_sections')->where('id', $id)->first();
        if ($section) {
            $page = DB::table('ppid_pages')->where('id', $section->page_id)->first();
            if ($page) {
                Cache::forget("ppid_page_{$page->slug}");
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Section berhasil diperbarui.',
        ]);
    }

    /**
     * Delete a section.
     */
    public function destroySection(int $id): JsonResponse
    {
        $section = DB::table('ppid_sections')
            ->where('id', $id)
            ->first();

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'Section tidak ditemukan.',
            ], 404);
        }

        DB::table('ppid_sections')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section berhasil dihapus.',
        ]);
    }

    /**
     * Reorder sections.
     */
    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|integer',
            'sections.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['sections'] as $section) {
            DB::table('ppid_sections')
                ->where('id', $section['id'])
                ->update([
                    'sort_order' => $section['sort_order'],
                    'updated_at' => now(),
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan section berhasil diperbarui.',
        ]);
    }

    /**
     * Upload an image for PPID content.
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => 'required|image|max:10240', // 10MB max
        ]);

        $file = $request->file('image');
        $filename = 'ppid/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store the file
        $path = $file->storeAs('public', $filename);

        // Optimize with Intervention Image if available
        try {
            $img = \Intervention\Image\Facades\Image::read(storage_path('app/' . $path));
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->toWebp(85)->save(storage_path('app/' . $path));
        } catch (\Exception $e) {
            // Fallback: keep original file
        }

        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $filename),
            'path' => $filename,
        ]);
    }

    /**
     * Display gallery management page.
     */
    public function gallery(string $slug)
    {
        $page = DB::table('ppid_pages')
            ->where('slug', $slug)
            ->first();

        if (!$page) {
            return redirect()->route('admin.ppid.index')
                ->with('error', 'Halaman PPID tidak ditemukan.');
        }

        $galleryItems = DB::table('ppid_gallery')
            ->where('page_slug', $slug)
            ->orderBy('sort_order')
            ->get();

        $title = "Gallery: {$page->title} - SILATAR Admin";
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'PPID', 'url' => route('admin.ppid.index')],
            ['label' => 'Gallery', 'url' => null],
        ];

        return view('admin.ppid.gallery', compact('page', 'galleryItems', 'title', 'breadcrumbs'));
    }

    /**
     * Upload a gallery image.
     */
    public function uploadGallery(Request $request, string $slug): JsonResponse
    {
        $validated = $request->validate([
            'image' => 'required|image|max:10240', // 10MB max
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('image');
        $filename = 'ppid/gallery/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store the file
        $path = $file->storeAs('public', $filename);

        // Optimize with Intervention Image
        try {
            $img = \Intervention\Image\Facades\Image::read(storage_path('app/' . $path));
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->toWebp(85)->save(storage_path('app/' . $path));
        } catch (\Exception $e) {
            // Fallback: keep original file
        }

        // Get current max sort order
        $maxOrder = DB::table('ppid_gallery')
            ->where('page_slug', $slug)
            ->max('sort_order') ?? 0;

        $galleryId = DB::table('ppid_gallery')->insertGetId([
            'page_slug' => $slug,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image_path' => $filename,
            'sort_order' => $maxOrder + 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil diupload.',
            'gallery_id' => $galleryId,
            'url' => asset('storage/' . $filename),
        ]);
    }

    /**
     * Delete a gallery item.
     */
    public function deleteGallery(int $id): JsonResponse
    {
        $gallery = DB::table('ppid_gallery')
            ->where('id', $id)
            ->first();

        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan.',
            ], 404);
        }

        // Delete file from storage
        if (Storage::exists('public/' . $gallery->image_path)) {
            Storage::delete('public/' . $gallery->image_path);
        }

        DB::table('ppid_gallery')
            ->where('id', $id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus.',
        ]);
    }

    /**
     * Reorder gallery items.
     */
    public function reorderGallery(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['items'] as $item) {
            DB::table('ppid_gallery')
                ->where('id', $item['id'])
                ->update([
                    'sort_order' => $item['sort_order'],
                    'updated_at' => now(),
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan gallery berhasil diperbarui.',
        ]);
    }
}
