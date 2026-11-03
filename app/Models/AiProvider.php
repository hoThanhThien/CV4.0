<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AiProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider_type',
        'api_key',
        'model',
        'base_url',
        'temperature',
        'max_tokens',
        'timeout',
        'is_active',
        'is_default',
        'last_tested_at',
        'connection_status',
        'last_error_message',
    ];

    protected $casts = [
        'temperature' => 'float',
        'max_tokens' => 'integer',
        'timeout' => 'integer',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'last_tested_at' => 'datetime',
    ];

    /**
     * Hidden attributes for arrays/JSON serialization to prevent leaking credentials.
     */
    protected $hidden = [
        'api_key',
    ];

    protected $appends = [
        'masked_api_key',
        'has_api_key',
    ];

    /**
     * Encrypt API key when set.
     */
    public function setApiKeyAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['api_key'] = Crypt::encryptString(trim($value));
        }
    }

    /**
     * Check whether stored API key can be decrypted (returns true if empty, false only on DecryptException).
     */
    public function canDecryptApiKey(): bool
    {
        if (empty($this->api_key)) {
            return true;
        }

        try {
            Crypt::decryptString($this->api_key);
            return true;
        } catch (DecryptException $e) {
            return false;
        }
    }

    /**
     * Decrypt API key safely. Returns empty string if failed.
     */
    public function getDecryptedApiKey(): string
    {
        if (empty($this->api_key)) {
            return '';
        }

        try {
            return Crypt::decryptString($this->api_key);
        } catch (DecryptException $e) {
            return '';
        }
    }

    /**
     * Check if provider has a configured API key.
     */
    public function getHasApiKeyAttribute(): bool
    {
        return !empty($this->api_key);
    }

    /**
     * Masked version of API Key for UI display (e.g., sk-...ABCD).
     */
    public function getMaskedApiKeyAttribute(): string
    {
        if (!empty($this->api_key) && !$this->canDecryptApiKey()) {
            return '⚠ Lỗi giải mã (nhập lại Key)';
        }

        $raw = $this->getDecryptedApiKey();
        if (empty($raw)) {
            return '— Chưa cấu hình —';
        }

        $len = strlen($raw);
        if ($len <= 8) {
            return str_repeat('•', 8);
        }

        $prefix = substr($raw, 0, 4);
        $suffix = substr($raw, -4);
        return $prefix . '••••••••' . $suffix;
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Relationships
     */
    public function contentConfigs()
    {
        return $this->hasMany(AiContentConfig::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function automationRuns()
    {
        return $this->hasMany(BlogAutomationRun::class);
    }
}
