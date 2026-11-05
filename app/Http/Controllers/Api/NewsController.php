<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsController extends BaseApiController
{
    /**
     * Get latest published news
     * GET /api/news
     *
     * Query params:
     * - limit (default 5, max 20)
     * - category (optional)
     */
    public function index(Request $request)
    {
        $limit = (int) $request->input('limit', 5);
        $limit = max(1, min($limit, 20));
        $category = $request->input('category');

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
            ->orderByDesc('publish_date');

        if ($category) {
            $query->where('category', $category);
        }

        $news = $query->limit($limit)->get()->map(function ($item) {
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
        });

        return $this->success($news, 'Daftar berita terbaru');
    }

    /**
     * Get single news detail
     * GET /api/news/{slug}
     */
    public function show(string $slug)
    {
        $news = DB::table('news')
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->first();

        if (! $news || $news->status !== 'published') {
            return $this->notFound('Berita tidak ditemukan');
        }

        return $this->success([
            'id' => $news->id,
            'title' => $news->title,
            'slug' => $news->slug,
            'category' => $news->category,
            'excerpt' => $news->excerpt,
            'content' => $news->content,
            'image' => $news->image,
            'image_url' => $news->image
                ? asset('storage/'.$news->image)
                : null,
            'publish_date' => $news->publish_date,
            'writer' => $news->writer ?? null,
            'editor' => $news->editor ?? null,
            'photographer' => $news->photographer ?? null,
        ], 'Detail berita');
    }
}
