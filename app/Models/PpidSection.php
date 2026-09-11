<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpidSection extends Model
{
    protected $fillable = [
        'page_id',
        'section_key',
        'section_type',
        'title',
        'content',
        'metadata',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the page that owns the section.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(PpidPage::class, 'page_id');
    }

    /**
     * Get metadata value for a specific key.
     */
    public function getMetadataValue(string $key, mixed $default = null): mixed
    {
        return data_get($this->metadata, $key, $default);
    }

    /**
     * Get stats from metadata (for stats section type).
     */
    public function getStats(): array
    {
        return $this->getMetadataValue('stats', []);
    }

    /**
     * Get cards from metadata (for card_grid section type).
     */
    public function getCards(): array
    {
        return $this->getMetadataValue('cards', []);
    }

    /**
     * Get steps from metadata (for timeline section type).
     */
    public function getSteps(): array
    {
        return $this->getMetadataValue('steps', []);
    }

    /**
     * Get table headers and rows from metadata (for table section type).
     */
    public function getTableHeaders(): array
    {
        return $this->getMetadataValue('headers', []);
    }

    public function getTableRows(): array
    {
        return $this->getMetadataValue('rows', []);
    }

    /**
     * Get form fields from metadata (for form_fields section type).
     */
    public function getFormFields(): array
    {
        return $this->getMetadataValue('fields', []);
    }

    /**
     * Get list items from metadata (for list section type).
     */
    public function getListItems(): array
    {
        return $this->getMetadataValue('items', []);
    }
}
