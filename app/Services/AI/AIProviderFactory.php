<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Providers\OpenAIProvider;
use App\Services\AI\Providers\GeminiProvider;
use App\Services\AI\Providers\DeepSeekProvider;
use App\Services\AI\Providers\CustomOpenAIProvider;
use InvalidArgumentException;

class AIProviderFactory
{
    /**
     * Resolve provider instance based on model or string type.
     */
    public static function make(AiProvider|string $provider): AIProviderInterface
    {
        $type = is_string($provider) ? strtolower($provider) : strtolower($provider->provider_type);

        return match ($type) {
            'openai' => new OpenAIProvider(),
            'gemini' => new GeminiProvider(),
            'deepseek' => new DeepSeekProvider(),
            'custom' => new CustomOpenAIProvider(),
            default => throw new InvalidArgumentException("Nhà cung cấp AI '{$type}' không được hỗ trợ."),
        };
    }
}
