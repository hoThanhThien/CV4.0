<?php

namespace App\Services\AI\Providers;

use App\Models\AiProvider;

class DeepSeekProvider extends OpenAIProvider
{
    protected function defaultBaseUrl(): string
    {
        return rtrim((string) config('ai.providers.deepseek.base_url', 'https://api.deepseek.com'), '/');
    }

    public function testConnection(AiProvider $provider): array
    {
        $providerCopy = clone $provider;
        if (empty($providerCopy->base_url)) {
            $providerCopy->base_url = $this->defaultBaseUrl();
        }

        $result = parent::testConnection($providerCopy);

        if ($result['success']) {
            $result['message'] = "Kết nối DeepSeek thành công! Model '{$providerCopy->model}' khả dụng.";
        } else {
            $result['message'] = str_replace('OpenAI', 'DeepSeek', $result['message']);
        }

        return $result;
    }

    public function generateBlogContent(
        AiProvider $provider,
        string $systemPrompt,
        string $userPrompt,
        ?string $modelOverride = null
    ): array {
        $providerCopy = clone $provider;
        if (empty($providerCopy->base_url)) {
            $providerCopy->base_url = $this->defaultBaseUrl();
        }

        return parent::generateBlogContent($providerCopy, $systemPrompt, $userPrompt, $modelOverride);
    }
}
