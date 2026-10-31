<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogAutomationRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'automation_id',
        'content_config_id',
        'ai_provider_id',
        'provider_name',
        'model_used',
        'topic_processed',
        'articles_requested',
        'articles_created',
        'articles_failed',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'estimated_cost',
        'status',
        'error_details',
        'created_post_ids',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'articles_requested' => 'integer',
        'articles_created' => 'integer',
        'articles_failed' => 'integer',
        'prompt_tokens' => 'integer',
        'completion_tokens' => 'integer',
        'total_tokens' => 'integer',
        'estimated_cost' => 'float',
        'created_post_ids' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function automation()
    {
        return $this->belongsTo(BlogAutomation::class, 'automation_id');
    }

    public function contentConfig()
    {
        return $this->belongsTo(AiContentConfig::class, 'content_config_id');
    }

    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider_id');
    }

    /**
     * Get posts created in this run
     */
    public function getCreatedPostsAttribute()
    {
        if (empty($this->created_post_ids)) {
            return collect();
        }

        return BlogPost::whereIn('id', (array)$this->created_post_ids)->get();
    }
}
