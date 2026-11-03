<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    // Status Constants
    public const STATUS_GENERATING = 'generating';
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'og_image',
        'published',
        'published_at',
        'status',
        'ai_generated',
        'ai_provider_id',
        'ai_config_id',
        'meta_title',
        'meta_description',
        'keywords',
        'category',
        'tags',
        'faq',
        'sources',
        'cover_image_prompt',
        'scheduled_at',
        'last_edited_by_admin',
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'ai_generated' => 'boolean',
        'last_edited_by_admin' => 'boolean',
        'tags' => 'array',
        'faq' => 'array',
        'sources' => 'array',
    ];

    /**
     * Scope for strictly published articles accessible to the public website.
     * Prevents any draft, pending, or future-scheduled articles from leaking.
     */
    public function scopePublished($query)
    {
        $query->where('published', true);

        if (\App\Services\AI\Support\AiSchemaManager::hasBlogStatusColumn()) {
            $query->where('status', self::STATUS_PUBLISHED);
        }

        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now())
                     ->orderBy('published_at', 'desc');
    }

    /**
     * Scope for scheduled articles that have reached their publication time.
     */
    public function scopeDueForPublishing($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED)
                     ->where(function ($q) {
                         $q->where(function ($sub) {
                             $sub->whereNotNull('published_at')->where('published_at', '<=', now());
                         })->orWhere(function ($sub) {
                             $sub->whereNotNull('scheduled_at')->where('scheduled_at', '<=', now());
                         });
                     });
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', self::STATUS_PENDING_REVIEW);
    }

    public function scopeAiGenerated($query)
    {
        return $query->where('ai_generated', true);
    }

    public static function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Status check helpers
     */
    public function isPublished(): bool
    {
        return $this->published && $this->status === self::STATUS_PUBLISHED && $this->published_at && $this->published_at->lte(now());
    }

    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPendingReview(): bool
    {
        return $this->status === self::STATUS_PENDING_REVIEW;
    }

    /**
     * Public URL for the article's cover thumbnail image.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('storage/' . ltrim($this->image, '/'));
    }

    /**
     * Public URL for the dedicated 1200x630 OpenGraph social preview image (always raster-safe).
     */
    public function getOgImageUrlAttribute(): string
    {
        if (!empty($this->og_image)) {
            if (str_starts_with($this->og_image, 'http://') || str_starts_with($this->og_image, 'https://')) {
                return $this->og_image;
            }

            return asset('storage/' . ltrim($this->og_image, '/'));
        }

        if (!empty($this->image) && !str_ends_with(strtolower($this->image), '.svg')) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }

            return asset('storage/' . ltrim($this->image, '/'));
        }

        return asset('images/og-image.webp');
    }

    /**
     * Relationships
     */
    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    public function contentConfig()
    {
        return $this->belongsTo(AiContentConfig::class, 'ai_config_id');
    }
}
