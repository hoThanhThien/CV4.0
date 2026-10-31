<?php

namespace App\Services\AI\Contracts;

use App\Models\AiProvider;

interface AIProviderInterface
{
    /**
     * Test connection to provider API with given key and model.
     *
     * @param AiProvider $provider
     * @return array [
     *     'success' => bool,
     *     'message' => string,
     *     'code' => string,
     *     'status_code' => int|null,
     *     'model_available' => bool
     * ]
     */
    public function testConnection(AiProvider $provider): array;

    /**
     * Send prompt to generate structured JSON blog post.
     *
     * @param AiProvider $provider
     * @param string $systemPrompt
     * @param string $userPrompt
     * @param string|null $modelOverride
     * @return array [
     *     'success' => bool,
     *     'raw_content' => string|null,
     *     'data' => array|null,
     *     'prompt_tokens' => int,
     *     'completion_tokens' => int,
     *     'total_tokens' => int,
     *     'error' => string|null,
     *     'error_code' => string|null
     * ]
     */
    public function generateBlogContent(
        AiProvider $provider,
        string $systemPrompt,
        string $userPrompt,
        ?string $modelOverride = null
    ): array;
}
