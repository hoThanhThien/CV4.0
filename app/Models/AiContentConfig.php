<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiContentConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ai_provider_id',
        'model_override',
        'topic_mode',
        'main_topic',
        'keywords',
        'language',
        'writing_tone',
        'target_audience',
        'article_length',
        'category',
        'tags',
        'custom_instructions',
        'include_faq',
        'include_sources',
        'is_active',
    ];

    protected $casts = [
        'include_faq' => 'boolean',
        'include_sources' => 'boolean',
        'is_active' => 'boolean',
        'tags' => 'array',
    ];

    /**
     * Helper to get effective provider (assigned provider or system default).
     */
    public function getEffectiveProvider(): ?AiProvider
    {
        if ($this->ai_provider_id && $this->provider && $this->provider->is_active) {
            return $this->provider;
        }

        return AiProvider::active()->default()->first() ?? AiProvider::active()->first();
    }

    /**
     * Helper to get effective model.
     */
    public function getEffectiveModel(): string
    {
        if (!empty($this->model_override)) {
            return $this->model_override;
        }

        $provider = $this->getEffectiveProvider();
        return $provider ? $provider->model : 'default';
    }

    /**
     * Parse keywords as clean array.
     */
    public function getKeywordsArrayAttribute(): array
    {
        if (empty($this->keywords)) {
            return [];
        }

        if (str_starts_with(trim($this->keywords), '[') && str_ends_with(trim($this->keywords), ']')) {
            $decoded = json_decode($this->keywords, true);
            if (is_array($decoded)) {
                return array_map('trim', $decoded);
            }
        }

        return array_filter(array_map('trim', explode(',', $this->keywords)));
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relationships
     */
    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    public function automations()
    {
        return $this->hasMany(BlogAutomation::class, 'content_config_id');
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'ai_config_id');
    }
}
