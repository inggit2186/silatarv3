<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublikasiController extends Controller
{
    private array $statuses = [
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ];

    private array $categories = [
        'laporan' => 'Laporan',
        'panduan' => 'Panduan',
        'regulasi' => 'Regulasi',
        'pengumuman' => 'Pengumuman',
        'lainnya' => 'Lainnya',
    ];

    public function index(Request $request)
    {
        $this->authorizeAccess($request);

        $query = DB::table('publikasi as p')
            ->leftJoin('users as u', 'u.id', '=', 'p.uploaded_by')
            ->select('p.*', 'u.name as uploader_name')
            ->orderByDesc('p.created_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('p.title', 'like', "%{$search}%")
                    ->orWhere('p.description', 'like', "%{$search}%")
                    ->orWhere('p.original_filename', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('p.status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('p.category', $request->category);
        }

        $publikasi = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => DB::table('publikasi')->count(),
            'published' => DB::table('publikasi')->where('status', 'published')->count(),
            'draft' => DB::table('publikasi')->where('status', 'draft')->count(),
            'archived' => DB::table('publikasi')->where('status', 'archived')->count(),
            'downloads' => DB::table('publikasi')->sum('download_count') ?? 0,
        ];

        return view('admin.publikasi.index', [
            'title' => 'Manajemen Publikasi - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Publikasi', 'url' => null],
            ],
            'publikasi' => $publikasi,
            'statuses' => $this->statuses,
            'categories' => $this->categories,
            'stats' => $stats,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorizeAccess($request);

        return view('admin.publikasi.create', [
            'title' => 'Tambah Publikasi - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Publikasi', 'url' => route('admin.publikasi.index')],
                ['label' => 'Tambah', 'url' => null],
            ],
            'statuses' => $this->statuses,
            'categories' => $this->categories,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAccess($request);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $request->file('file');
        $slug = $this->uniqueSlug($validated['title']);
        $filename = $slug.'-'.time().'.'.$file->getClientOriginalExtension();
        $filePath = $file->storeAs('publikasi', $filename, 'public');

        DB::table('publikasi')->insert([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'category' => $validated['category'] ?: null,
            'file_path' => $filePath,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? ($validated['status'] === 'published' ? now() : null),
            'download_count' => 0,
            'uploaded_by' => $request->user()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.publikasi.index')
            ->with('success', 'Publikasi berhasil ditambahkan.');
    }

    public function edit(Request $request, int $id)
    {
        $this->authorizeAccess($request);

        $publikasi = DB::table('publikasi')->where('id', $id)->first();
        abort_unless($publikasi, 404);

        return view('admin.publikasi.edit', [
            'title' => 'Edit Publikasi - SILATAR Admin',
            'breadcrumbs' => [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['label' => 'Publikasi', 'url' => route('admin.publikasi.index')],
                ['label' => 'Edit', 'url' => null],
            ],
            'publikasi' => $publikasi,
            'statuses' => $this->statuses,
            'categories' => $this->categories,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $this->authorizeAccess($request);

        $publikasi = DB::table('publikasi')->where('id', $id)->first();
        abort_unless($publikasi, 404);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'file' => ['nullable', 'file', 'max:10240'],
        ]);

        $fileData = [
            'file_path' => $publikasi->file_path,
            'original_filename' => $publikasi->original_filename,
            'mime_type' => $publikasi->mime_type,
            'file_size' => $publikasi->file_size,
        ];

        if ($request->hasFile('file')) {
            if ($publikasi->file_path && Storage::disk('public')->exists($publikasi->file_path)) {
                Storage::disk('public')->delete($publikasi->file_path);
            }

            $file = $request->file('file');
            $filename = $publikasi->slug.'-'.time().'.'.$file->getClientOriginalExtension();
            $fileData = [
                'file_path' => $file->storeAs('publikasi', $filename, 'public'),
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ];
        }

        DB::table('publikasi')->where('id', $id)->update(array_merge([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'] ?: null,
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? ($validated['status'] === 'published' && ! $publikasi->published_at ? now() : $publikasi->published_at),
            'updated_at' => now(),
        ], $fileData));

        return redirect()->route('admin.publikasi.index')
            ->with('success', 'Publikasi berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id)
    {
        $this->authorizeAccess($request);

        $publikasi = DB::table('publikasi')->where('id', $id)->first();
        abort_unless($publikasi, 404);

        if ($publikasi->file_path && Storage::disk('public')->exists($publikasi->file_path)) {
            Storage::disk('public')->delete($publikasi->file_path);
        }

        DB::table('publikasi')->where('id', $id)->delete();

        return redirect()->route('admin.publikasi.index')
            ->with('success', 'Publikasi berhasil dihapus.');
    }

    private function authorizeAccess(Request $request): void
    {
        $role = strtolower($request->user()->role ?? '');
        abort_unless(in_array($role, ['admin', 'superadmin', 'petugas']), 403);
    }

    private function uniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        if (strlen($baseSlug) > 60) {
            $baseSlug = rtrim(substr($baseSlug, 0, 60), '-');
        }

        $slug = $baseSlug ?: Str::random(12);
        $counter = 1;

        while (DB::table('publikasi')->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
