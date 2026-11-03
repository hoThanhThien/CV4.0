<?php

namespace App\Services\AI\Providers;

use App\Models\AiProvider;

class CustomOpenAIProvider extends OpenAIProvider
{
    public function testConnection(AiProvider $provider): array
    {
        if (empty($provider->base_url)) {
            return [
                'success' => false,
                'message' => 'Custom Provider bắt buộc phải có Base URL (ví dụ: https://api.groq.com/openai/v1 hoặc https://openrouter.ai/api/v1).',
                'code' => 'MISSING_BASE_URL',
                'status_code' => 400,
                'model_available' => false,
            ];
        }

        if (!$this->isSafeUrl($provider->base_url)) {
            return [
                'success' => false,
                'message' => 'Base URL không an toàn hoặc không hợp lệ. Chỉ chấp nhận giao thức http:// hoặc https://.',
                'code' => 'UNSAFE_BASE_URL',
                'status_code' => 400,
                'model_available' => false,
            ];
        }

        $result = parent::testConnection($provider);

        if ($result['success']) {
            $result['message'] = "Kết nối Custom Provider ({$provider->base_url}) thành công! Model '{$provider->model}' phản hồi tốt.";
        }

        return $result;
    }

    public function generateBlogContent(
        AiProvider $provider,
        string $systemPrompt,
        string $userPrompt,
        ?string $modelOverride = null
    ): array {
        if (empty($provider->base_url) || !$this->isSafeUrl($provider->base_url)) {
            return [
                'success' => false,
                'raw_content' => null,
                'data' => null,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0,
                'error' => 'Base URL không an toàn hoặc không được cung cấp.',
                'error_code' => 'UNSAFE_BASE_URL',
            ];
        }

        return parent::generateBlogContent($provider, $systemPrompt, $userPrompt, $modelOverride);
    }

    public function generateImage(
        AiProvider $provider,
        string $prompt,
        ?string $modelOverride = null,
        string $aspectRatio = '16:9'
    ): array {
        if (empty($provider->base_url) || !$this->isSafeUrl($provider->base_url)) {
            return [
                'success' => false,
                'binary' => null,
                'mime_type' => null,
                'model_used' => null,
                'error' => 'Base URL không an toàn hoặc chưa được cấu hình.',
                'error_code' => 'UNSAFE_BASE_URL',
            ];
        }

        return parent::generateImage($provider, $prompt, $modelOverride, $aspectRatio);
    }

    public function generateVisualDesignSpec(AiProvider $provider, array $articleContext, ?string $modelOverride = null): ?array
    {
        if (empty($provider->base_url) || !$this->isSafeUrl($provider->base_url)) {
            return null;
        }

        return parent::generateVisualDesignSpec($provider, $articleContext, $modelOverride);
    }

    /**
     * Validate against basic SSRF vulnerabilities
     */
    protected function isSafeUrl(string $url): bool
    {
        $parsed = parse_url($url);
        if (!$parsed || !isset($parsed['scheme']) || !isset($parsed['host'])) {
            return false;
        }

        $scheme = strtolower($parsed['scheme']);
        if (!in_array($scheme, ['http', 'https'], true)) {
            return false;
        }

        $host = strtolower($parsed['host']);

        // In production, block loopback and cloud metadata endpoints unless explicitly developing
        if (app()->environment('production')) {
            if (
                $host === '169.254.169.254' || // AWS/cloud metadata
                $host === 'metadata.google.internal' ||
                str_starts_with($host, '127.') ||
                $host === 'localhost' ||
                $host === '::1'
            ) {
                return false;
            }
        }

        return true;
    }
}
