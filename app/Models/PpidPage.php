<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PpidPage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'subtitle',
        'meta_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all sections for this page.
     */
    public function sections(): HasMany
    {
        return $this->hasMany(PpidSection::class, 'page_id');
    }

    /**
     * Get only visible sections, ordered by sort_order.
     */
    public function visibleSections(): HasMany
    {
        return $this->sections()
            ->where('is_visible', true)
            ->orderBy('sort_order');
    }

    /**
     * Scope to get only active pages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
