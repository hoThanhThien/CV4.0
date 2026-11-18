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

    /**
     * Generate a cover/thumbnail raster image from a visual prompt using dynamically discovered image models.
     *
     * @param AiProvider $provider
     * @param string $prompt
     * @param string|null $modelOverride
     * @param string $aspectRatio
     * @return array [
     *     'success' => bool,
     *     'binary' => string|null,
     *     'mime_type' => string|null,
     *     'model_used' => string|null,
     *     'error' => string|null,
     *     'error_code' => string|null
     * ]
     */
    public function generateImage(
        AiProvider $provider,
        string $prompt,
        ?string $modelOverride = null,
        string $aspectRatio = '16:9'
    ): array;

    /**
     * Generate a grounded natural-language chat completion for RAG/Copilot responses.
     *
     * @return array{
     *     success: bool,
     *     text: string|null,
     *     model_used: string|null,
     *     prompt_tokens: int,
     *     completion_tokens: int,
     *     total_tokens: int,
     *     error: string|null,
     *     error_code: string|null
     * }
     */
    public function generateChatReply(
        AiProvider $provider,
        string $systemPrompt,
        string $userPrompt,
        ?string $modelOverride = null,
        int $maxTokens = 800,
        float $temperature = 0.35
    ): array;

    /**
     * Generate dense embedding vectors for an array of strings, or null when unsupported/unavailable.
     *
     * @param  array<int, string>  $texts
     * @return array<int, array<int, float>>|null
     */
    public function embedTexts(AiProvider $provider, array $texts): ?array;
}
