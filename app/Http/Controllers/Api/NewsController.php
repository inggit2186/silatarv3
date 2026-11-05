<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends BaseApiController
{
    /**
     * Get published news (list atau paginated)
     * GET /api/news
     *
     * Query params:
     * - limit (default 5, max 200) — mode list (slideshow / fallback tanpa paginasi)
     * - page (optional) — bila dikirim, response memakai format paginasi
     * - per_page (default 50, max 200) — hanya untuk mode paginasi
     * - category (optional)
     * - q (optional) — cari pada judul & ringkasan
     */
    public function index(Request $request)
    {
        $category = $request->input('category');
        $search = trim((string) $request->input('q', ''));

        $query = DB::table('news')
            ->select([
                'id',
                'title',
                'slug',
                'category',
                'excerpt',
                'image',
                'publish_date',
                'is_featured',
                'is_slideshow',
            ])
            ->where('status', 'published')
            ->orderByDesc('publish_date')
            ->orderByDesc('id');

        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($search !== '') {
            $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', '%'.$search.'%')
                    ->orWhere('excerpt', 'like', '%'.$search.'%');
            });
        }

        // Mode paginasi (halaman daftar berita)
        if ($request->has('page') || $request->boolean('paginate')) {
            $perPage = (int) $request->input('per_page', 50);
            $perPage = max(1, min($perPage, 200));

            $paginator = $query->paginate($perPage);
            $paginator->getCollection()->transform(fn ($item) => $this->formatItem($item));

            return $this->successPaginated($paginator, 'Daftar berita');
        }

        // Mode list ringkas (slideshow dashboard / fallback klien lama)
        $limit = (int) $request->input('limit', 5);
        $limit = max(1, min($limit, 200));

        $news = $query->limit($limit)->get()->map(fn ($item) => $this->formatItem($item));

        return $this->success($news, 'Daftar berita terbaru');
    }

    /**
     * Get daftar kategori berita beserta jumlahnya
     * GET /api/news/categories
     */
    public function categories()
    {
        $categories = DB::table('news')
            ->select('category', DB::raw('COUNT(*) as total'))
            ->where('status', 'published')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'category' => $row->category,
                'total' => (int) $row->total,
            ]);

        return $this->success($categories, 'Daftar kategori berita');
    }

    /**
     * Format satu baris berita untuk response JSON.
     */
    private function formatItem($item): array
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'category' => $item->category,
            'excerpt' => $item->excerpt,
            'image' => $item->image,
            'image_url' => $item->image
                ? asset('storage/'.$item->image)
                : null,
            'publish_date' => $item->publish_date,
            'is_featured' => (bool) $item->is_featured,
            'is_slideshow' => (bool) $item->is_slideshow,
        ];
    }

    /**
     * Get single news detail
     * GET /api/news/{slug}
     */
    public function show(Request $request, string $slug)
    {
        $news = DB::table('news')
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->first();

        if (! $news || $news->status !== 'published') {
            return $this->notFound('Berita tidak ditemukan');
        }

        // Catat kunjungan (total + unik per IP/hari) lalu ambil angka terbaru
        $this->trackView($news->id, $request);

        $stats = DB::table('news')
            ->where('id', $news->id)
            ->first(['view_count', 'unique_view_count']);

        // Nama penulis dari tabel users (bila writer kosong)
        $authorName = null;
        if (! empty($news->author_id)) {
            $authorName = DB::table('users')
                ->where('id', $news->author_id)
                ->value('name');
        }

        return $this->success([
            'id' => $news->id,
            'title' => $news->title,
            'slug' => $news->slug,
            'category' => $news->category,
            'excerpt' => $news->excerpt,
            'content' => $this->normalizeContent($news->content),
            'image' => $news->image,
            'image_url' => $news->image
                ? asset('storage/'.$news->image)
                : null,
            'publish_date' => $news->publish_date,
            'writer' => $this->cleanText($news->writer ?? null) ?? $this->cleanText($authorName),
            'editor' => $this->cleanText($news->editor ?? null),
            'photographer' => $this->cleanText($news->photographer ?? null),
            'view_count' => (int) ($stats->view_count ?? 0),
            'unique_view_count' => (int) ($stats->unique_view_count ?? 0),
        ], 'Detail berita');
    }

    /**
     * Catat kunjungan berita: view_count selalu naik, unique_view_count
     * hanya sekali per IP per hari (mengikuti perilaku versi web).
     */
    private function trackView(int $newsId, Request $request): void
    {
        try {
            $ipAddress = $request->ip();
            $today = now()->toDateString();

            $alreadyViewed = DB::table('news_views')
                ->where('news_id', $newsId)
                ->where('ip_address', $ipAddress)
                ->whereDate('viewed_at', $today)
                ->exists();

            DB::table('news')->where('id', $newsId)->increment('view_count');

            if (! $alreadyViewed) {
                DB::table('news')->where('id', $newsId)->increment('unique_view_count');

                DB::table('news_views')->insert([
                    'news_id' => $newsId,
                    'ip_address' => $ipAddress,
                    'session_id' => null,
                    'user_agent' => substr((string) $request->userAgent(), 0, 500),
                    'viewed_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Kegagalan pencatatan tidak boleh menggagalkan pembacaan berita
            \Log::warning('[NewsView] gagal mencatat kunjungan: '.$e->getMessage());
        }
    }

    /**
     * Bersihkan teks metadata (trim + null bila kosong).
     */
    private function cleanText(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /**
     * Normalisasi HTML Quill agar dapat dirender dengan baik di aplikasi mobile.
     *
     * Quill menyimpan list sebagai <ol><li data-list="bullet|ordered"> sehingga
     * flutter_html tidak mengenalinya. Method ini mengonversinya menjadi
     * <ul>/<ol> standar dan membersihkan atribut internal editor.
     */
    private function normalizeContent(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Bersihkan atribut internal Quill (contenteditable, ql-ui)
        $html = preg_replace('/\s*contenteditable="(true|false)"/i', '', $html);
        $html = preg_replace('/<span[^>]*class="[^"]*ql-ui[^"]*"[^>]*>.*?<\/span>/is', '', $html);

        // Ubah src relatif (/storage/...) menjadi URL absolut agar tampil di app
        $html = preg_replace_callback(
            '/src="(\/storage\/[^"]+)"/i',
            fn ($m) => 'src="'.asset(ltrim($m[1], '/')).'"',
            $html
        );

        // Konversi <li data-list="bullet"> menjadi <ul><li>...</li></ul>
        $html = preg_replace_callback(
            '/<ol[^>]*>(.*?)<\/ol>/is',
            function ($matches) {
                $inner = $matches[1];

                // Pecah per <li>
                preg_match_all('/<li([^>]*)>(.*?)<\/li>/is', $inner, $items, PREG_SET_ORDER);

                if (empty($items)) {
                    return $matches[0];
                }

                $bulletItems = [];
                $orderedItems = [];
                $plainItems = [];

                foreach ($items as $item) {
                    $attrs = $item[1];
                    $body = $item[2];

                    if (preg_match('/data-list="bullet"/i', $attrs)) {
                        $bulletItems[] = $body;
                    } elseif (preg_match('/data-list="ordered"/i', $attrs)) {
                        $orderedItems[] = $body;
                    } else {
                        $plainItems[] = $body;
                    }
                }

                $out = '';
                if (! empty($bulletItems)) {
                    $out .= '<ul>'.implode('', array_map(
                        fn ($b) => '<li>'.$b.'</li>',
                        $bulletItems
                    )).'</ul>';
                }
                if (! empty($orderedItems) || ! empty($plainItems)) {
                    $out .= '<ol>'.implode('', array_map(
                        fn ($b) => '<li>'.$b.'</li>',
                        array_merge($orderedItems, $plainItems)
                    )).'</ol>';
                }

                return $out;
            },
            $html
        );

        return $html;
    }
}
