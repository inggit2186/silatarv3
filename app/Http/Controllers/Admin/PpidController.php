<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

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

        if (! $page) {
            return redirect()->route('admin.ppid.index')
                ->with('error', 'Halaman PPID tidak ditemukan.');
        }

        $sections = DB::table('ppid_sections')
            ->where('page_id', $page->id)
            ->orderBy('sort_order')
            ->get();

        // Decode metadata for each section
        foreach ($sections as $section) {
            $section->metadata = is_string($section->metadata)
                ? (json_decode($section->metadata, true) ?? [])
                : ($section->metadata ?? []);
        }

        $title = "Edit: {$page->title} - SILATAR Admin";
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'PPID', 'url' => route('admin.ppid.index')],
            ['label' => $page->title, 'url' => null],
        ];

        return view('admin.ppid.edit', compact('page', 'sections', 'title', 'breadcrumbs'));
    }

    /**
     * Update page settings and all sections in a single request.
     */
    public function update(Request $request, string $slug)
    {
        $page = DB::table('ppid_pages')
            ->where('slug', $slug)
            ->first();

        if (! $page) {
            return redirect()->route('admin.ppid.index')
                ->with('error', 'Halaman PPID tidak ditemukan.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
            'sections' => 'nullable|array',
        ]);

        DB::table('ppid_pages')
            ->where('id', $page->id)
            ->update([
                'title' => $validated['title'],
                'subtitle' => $validated['subtitle'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'is_active' => ! empty($validated['is_active']),
                'updated_at' => now(),
            ]);

        $warnings = $this->syncSections($request, $page);

        Cache::forget("ppid_page_{$slug}");

        if ($warnings) {
            return redirect()->route('admin.ppid.edit', $slug)
                ->with('error', implode(' ', $warnings));
        }

        return redirect()->route('admin.ppid.edit', $slug)
            ->with('success', 'Halaman berhasil disimpan.');
    }

    /**
     * Persist all section changes from the page-edit form.
     *
     * @return array<int, string> Pesan singkat bila ada yang gagal disimpan.
     */
    private function syncSections(Request $request, object $page): array
    {
        $warnings = [];
        $sections = $request->input('sections', []);
        if (! is_array($sections)) {
            return $warnings;
        }

        $existing = DB::table('ppid_sections')
            ->where('page_id', $page->id)
            ->get()
            ->keyBy(fn ($row) => (string) $row->id);

        foreach ($sections as $key => $data) {
            if (! is_array($data)) {
                continue;
            }

            $sectionId = (int) ($data['id'] ?? $key);
            $current = $existing->get((string) $sectionId);

            if (! $current) {
                continue;
            }

            $update = [
                'title' => $data['title'] ?? null,
                'sort_order' => (int) ($data['sort_order'] ?? 0),
                'is_visible' => ! empty($data['is_visible']),
                'updated_at' => now(),
            ];

            if (array_key_exists('content', $data)) {
                $update['content'] = $data['content'];
            }

            $metadata = $this->buildMetadata($data, $current);

            // File upload tidak ikut terbawa di $request->input() — ambil dari file bag.
            $file = $request->file("sections.{$sectionId}.metadata.image_file");
            if ($file && $file->isValid()) {
                $path = $this->storeImage($file);

                if ($path) {
                    $metadata = array_merge($metadata ?? [], ['image_path' => $path]);
                } else {
                    $warnings[] = 'Gambar pada section "'.($data['title'] ?: $current->section_key).'" gagal disimpan (format tidak didukung).';
                }
            }

            if ($metadata !== null) {
                $update['metadata'] = json_encode($metadata);
            }

            DB::table('ppid_sections')
                ->where('id', $sectionId)
                ->where('page_id', $page->id)
                ->update($update);
        }

        return $warnings;
    }

    /**
     * Build the metadata array from form data.
     *
     * @param  object|null  $current  Row section sebelum disimpan, dipakai untuk
     *                                mempertahankan gambar lama bila tidak ada upload baru.
     */
    private function buildMetadata(array $data, ?object $current = null): ?array
    {
        if (empty($data['metadata']) || ! is_array($data['metadata'])) {
            return null;
        }

        $metadata = $data['metadata'];
        unset($metadata['image_file']);

        // Mengubah alt text saja tidak boleh menghapus gambar yang sudah terunggah.
        if ($current && $current->section_type === 'image' && empty($metadata['image_path'])) {
            $existing = is_string($current->metadata)
                ? (json_decode($current->metadata, true) ?? [])
                : ($current->metadata ?? []);

            if (! empty($existing['image_path'])) {
                $metadata['image_path'] = $existing['image_path'];
            }
        }

        return $metadata;
    }

    /**
     * Store an uploaded image and return its relative path.
     *
     * Gambar dikonversi ke WebP dan dilebarkan maksimal 1200px. Bila konversi
     * gagal, file asli tetap dipakai.
     */
    private function storeImage($file): ?string
    {
        if (! $file || ! $file->isValid()) {
            return null;
        }

        // Hanya gambar asli yang boleh disimpan; SVG ditolak karena bisa
        // membawa skrip dan tidak bisa dikonversi ke WebP.
        $mime = (string) $file->getMimeType();
        if (! str_starts_with($mime, 'image/') || $mime === 'image/svg+xml') {
            return null;
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $extension = 'jpg';
        }

        $disk = Storage::disk('public');
        $filename = time().'_'.uniqid().'.'.$extension;
        $relativePath = 'ppid/'.$filename;

        // Simpan ke disk "public" agar bisa diakses lewat /storage/...
        $file->storeAs('ppid', $filename, ['disk' => 'public']);

        $webpRelative = 'ppid/'.pathinfo($filename, PATHINFO_FILENAME).'.webp';

        try {
            ImageManager::usingDriver(Driver::class)
                ->decodePath($disk->path($relativePath))
                ->scaleDown(width: 1200)
                ->encode(new WebpEncoder(quality: 85))
                ->save($disk->path($webpRelative));

            $disk->delete($relativePath);

            return $webpRelative;
        } catch (\Throwable $e) {
            // Keep original if optimisation fails.
            return $relativePath;
        }
    }

    /**
     * Add a new section to a page (form POST, redirects back).
     */
    public function storeSection(Request $request, string $slug)
    {
        $page = DB::table('ppid_pages')->where('slug', $slug)->first();

        if (! $page) {
            return redirect()->route('admin.ppid.index')
                ->with('error', 'Halaman tidak ditemukan.');
        }

        $validated = $request->validate([
            'section_key' => 'required|string|max:100|regex:/^[a-z0-9_]+$/',
            'section_type' => 'required|in:'.implode(',', array_keys(config('ppid.section_types'))),
            'title' => 'nullable|string|max:255',
        ], [
            'section_key.regex' => 'Section key hanya boleh huruf kecil, angka, dan underscore.',
        ]);

        if (DB::table('ppid_sections')
            ->where('page_id', $page->id)
            ->where('section_key', $validated['section_key'])
            ->exists()
        ) {
            return redirect()->route('admin.ppid.edit', $slug)
                ->with('error', "Section key '{$validated['section_key']}' sudah digunakan.");
        }

        DB::table('ppid_sections')->insert([
            'page_id' => $page->id,
            'section_key' => $validated['section_key'],
            'section_type' => $validated['section_type'],
            'title' => $validated['title'] ?? null,
            'content' => null,
            'metadata' => null,
            'sort_order' => (DB::table('ppid_sections')->where('page_id', $page->id)->max('sort_order') ?? 0) + 1,
            'is_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Cache::forget("ppid_page_{$slug}");

        return redirect()->route('admin.ppid.edit', $slug)
            ->with('success', 'Section ditambahkan. Isi kontennya di bawah lalu Simpan.');
    }

    /**
     * Remove a section (form DELETE, redirects back).
     */
    public function destroySection(int $id)
    {
        $section = DB::table('ppid_sections')->where('id', $id)->first();

        if (! $section) {
            return redirect()->route('admin.ppid.index')
                ->with('error', 'Section tidak ditemukan.');
        }

        $page = DB::table('ppid_pages')->where('id', $section->page_id)->first();

        DB::table('ppid_sections')->where('id', $id)->delete();

        if ($page) {
            Cache::forget("ppid_page_{$page->slug}");
        }

        return redirect()->route('admin.ppid.edit', $page->slug)
            ->with('success', 'Section dihapus.');
    }

    /**
     * Change a section's type. Resets content and metadata to the new type's empty defaults.
     */
    public function updateType(Request $request, int $id)
    {
        $section = DB::table('ppid_sections')->where('id', $id)->first();

        if (! $section) {
            return redirect()->route('admin.ppid.index')
                ->with('error', 'Section tidak ditemukan.');
        }

        $validated = $request->validate([
            'section_type' => 'required|in:'.implode(',', array_keys(config('ppid.section_types'))),
        ]);

        $newType = $validated['section_type'];

        // If type is the same, just redirect back without change.
        if ($section->section_type === $newType) {
            $page = DB::table('ppid_pages')->where('id', $section->page_id)->first();

            return redirect()->route('admin.ppid.edit', $page->slug)
                ->with('success', 'Tipe section tidak berubah.');
        }

        $metadata = $this->emptyMetadataFor($newType);

        DB::table('ppid_sections')->where('id', $id)->update([
            'section_type' => $newType,
            'content' => null,
            'metadata' => $metadata === null ? null : json_encode($metadata),
            'updated_at' => now(),
        ]);

        $page = DB::table('ppid_pages')->where('id', $section->page_id)->first();
        if ($page) {
            Cache::forget("ppid_page_{$page->slug}");
        }

        $typeLabel = config("ppid.section_types.{$newType}.label", $newType);

        return redirect()->route('admin.ppid.edit', $page->slug)
            ->with('success', "Tipe section diubah ke \"{$typeLabel}\". Konten sebelumnya dikosongkan — silakan isi ulang.");
    }

    /**
     * Return the empty metadata structure for a given section type.
     */
    private function emptyMetadataFor(string $type): ?array
    {
        return match ($type) {
            'text' => null,
            'list' => ['items' => []],
            'card_grid' => ['cards' => []],
            'timeline' => ['steps' => []],
            'stats' => ['stats' => []],
            'table' => ['headers' => [], 'rows' => []],
            'form_fields' => ['fields' => []],
            'image' => ['alt' => '', 'image_path' => ''],
            default => null,
        };
    }

    // -----------------------------------------------------------------
    // AJAX-only endpoints retained for gallery management
    // -----------------------------------------------------------------

    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:10240',
        ]);

        $path = $this->storeImage($validated['image']);

        return response()->json([
            'success' => true,
            'url' => asset('storage/'.$path),
            'path' => $path,
        ]);
    }

    public function gallery(string $slug)
    {
        $page = DB::table('ppid_pages')->where('slug', $slug)->first();

        if (! $page) {
            return redirect()->route('admin.ppid.index')
                ->with('error', 'Halaman tidak ditemukan.');
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

    public function uploadGallery(Request $request, string $slug)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:10240',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $path = $this->storeImage($validated['image']);

        $galleryId = DB::table('ppid_gallery')->insertGetId([
            'page_slug' => $slug,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image_path' => $path,
            'sort_order' => (DB::table('ppid_gallery')->where('page_slug', $slug)->max('sort_order') ?? 0) + 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil diupload.',
            'gallery_id' => $galleryId,
            'url' => asset('storage/'.$path),
        ]);
    }

    public function deleteGallery(int $id)
    {
        $gallery = DB::table('ppid_gallery')->where('id', $id)->first();

        if (! $gallery) {
            return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan.'], 404);
        }

        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        DB::table('ppid_gallery')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Gambar dihapus.']);
    }

    public function reorderGallery(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['items'] as $item) {
            DB::table('ppid_gallery')
                ->where('id', $item['id'])
                ->update(['sort_order' => $item['sort_order'], 'updated_at' => now()]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan gallery diperbarui.']);
    }
}
