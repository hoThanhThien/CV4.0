<?php

namespace App\Services\AI\Providers;

use App\Models\AiProvider;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Support\JsonCleaner;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Throwable;

class OpenAIProvider implements AIProviderInterface
{
    protected function defaultBaseUrl(): string
    {
        return rtrim((string) config('ai.providers.openai.base_url', 'https://api.openai.com/v1'), '/');
    }

    /**
     * Dynamically fetch available models from an OpenAI-compatible `/models` endpoint.
     *
     * @return array<int, array{id: string, display_name: string}>
     */
    public function discoverModels(string $apiKey, ?string $configuredBaseUrl = null): array
    {
        $apiKey = trim($apiKey);
        if ($apiKey === '') {
            return [];
        }

        $baseUrl = rtrim($configuredBaseUrl ?: $this->defaultBaseUrl(), '/');

        try {
            $resp = $this->httpClient($apiKey, 15)->get($baseUrl . '/models');
            if (!$resp->successful()) {
                return [];
            }

            $items = $resp->json('data');
            if (!is_array($items)) {
                return [];
            }

            $models = [];
            foreach ($items as $item) {
                $id = trim((string) ($item['id'] ?? ''));
                if ($id === '' || str_contains($id, 'embedding') || str_contains($id, 'whisper') || str_contains($id, 'tts') || str_contains($id, 'dall-e') || str_contains($id, 'image')) {
                    continue;
                }
                $models[] = [
                    'id' => $id,
                    'display_name' => $id,
                ];
            }

            return $models;
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Dynamically discover image generation models from an OpenAI-compatible `/models` endpoint.
     *
     * @return array<int, array{id: string, display_name: string}>
     */
    public function discoverImageModels(string $apiKey, ?string $configuredBaseUrl = null): array
    {
        $apiKey = trim($apiKey);
        if ($apiKey === '') {
            return [];
        }

        $baseUrl = rtrim($configuredBaseUrl ?: $this->defaultBaseUrl(), '/');

        try {
            $resp = $this->httpClient($apiKey, 15)->get($baseUrl . '/models');
            if (!$resp->successful()) {
                return [];
            }

            $items = $resp->json('data');
            if (!is_array($items)) {
                return [];
            }

            $models = [];
            foreach ($items as $item) {
                $id = trim((string) ($item['id'] ?? ''));
                $lower = strtolower($id);
                if (
                    $id !== ''
                    && (
                        str_contains($lower, 'image')
                        || str_contains($lower, 'dall-e')
                        || str_contains($lower, 'flux')
                        || str_contains($lower, 'sdxl')
                        || str_contains($lower, 'stable-diffusion')
                    )
                ) {
                    $models[] = [
                        'id' => $id,
                        'display_name' => $id,
                    ];
                }
            }

            return $models;
        } catch (Throwable $e) {
            return [];
        }
    }

    protected function httpClient(string $apiKey, int $timeout): PendingRequest
    {
        $client = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->timeout($timeout);

        if (defined('CURL_IPRESOLVE_V4') && defined('CURLOPT_IPRESOLVE')) {
            $client = $client->withOptions([
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
            ]);
        }

        return $client;
    }

    public function testConnection(AiProvider $provider): array
    {
        if (!$provider->canDecryptApiKey()) {
            return [
                'success' => false,
                'message' => 'Không thể giải mã API Key đã lưu (có thể APP_KEY trên server đã thay đổi). Vui lòng nhấn "Sửa" và nhập lại API Key.',
                'code' => 'DECRYPT_ERROR',
                'status_code' => 400,
                'model_available' => false,
            ];
        }

        $apiKey = trim($provider->getDecryptedApiKey());
        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'API Key chưa được cấu hình.',
                'code' => 'MISSING_API_KEY',
                'status_code' => 400,
                'model_available' => false,
            ];
        }

        $baseUrl = rtrim($provider->base_url ?: $this->defaultBaseUrl(), '/');
        $model = trim((string) $provider->model);
        if ($model === '') {
            $discovered = $this->discoverModels($apiKey, $baseUrl);
            $model = $discovered[0]['id'] ?? '';
        }
        $timeout = max(15, (int) ($provider->timeout ?: 30));

        try {
            $response = $this->httpClient($apiKey, $timeout)->post($baseUrl . '/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => 'Say hello in one word.'],
                ],
                'max_tokens' => 16,
            ]);

            $statusCode = $response->status();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => "Kết nối OpenAI thành công! Model '{$model}' khả dụng.",
                    'code' => 'CONNECTED',
                    'status_code' => $statusCode,
                    'model_available' => true,
                ];
            }

            return $this->classifyError($statusCode, $response->json(), $response->body(), $model);
        } catch (ConnectionException $e) {
            return [
                'success' => false,
                'message' => 'Connection timeout: Không thể kết nối tới máy chủ OpenAI trong thời gian chờ.',
                'code' => 'TIMEOUT',
                'status_code' => 408,
                'model_available' => false,
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => 'Lỗi kết nối: ' . $e->getMessage(),
                'code' => 'CONNECTION_ERROR',
                'status_code' => 500,
                'model_available' => false,
            ];
        }
    }

    public function generateBlogContent(
        AiProvider $provider,
        string $systemPrompt,
        string $userPrompt,
        ?string $modelOverride = null
    ): array {
        @set_time_limit(300);

        if (!$provider->canDecryptApiKey()) {
            return [
                'success' => false,
                'raw_content' => null,
                'data' => null,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0,
                'error' => 'Không thể giải mã API Key (APP_KEY thay đổi). Vui lòng nhập lại API Key.',
                'error_code' => 'DECRYPT_ERROR',
            ];
        }

        $apiKey = trim($provider->getDecryptedApiKey());
        if (empty($apiKey)) {
            return [
                'success' => false,
                'raw_content' => null,
                'data' => null,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0,
                'error' => 'API Key is missing or invalid.',
                'error_code' => 'MISSING_API_KEY',
            ];
        }

        $baseUrl = rtrim($provider->base_url ?: $this->defaultBaseUrl(), '/');
        $model = trim((string) ($modelOverride ?: $provider->model));
        if ($model === '') {
            $discovered = $this->discoverModels($apiKey, $baseUrl);
            $model = $discovered[0]['id'] ?? '';
        }
        $temperature = (float) ($provider->temperature ?? 0.7);
        $maxTokens = max(2000, (int) ($provider->max_tokens ?? 4096));
        $timeout = max(90, (int) ($provider->timeout ?? 120));

        try {
            $payload = [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
                'response_format' => ['type' => 'json_object'],
            ];

            $response = $this->httpClient($apiKey, $timeout)->post($baseUrl . '/chat/completions', $payload);

            // Fallback if custom OpenAI-compatible endpoint does not support response_format
            if ($response->status() === 400 && str_contains(strtolower((string) $response->body()), 'response_format')) {
                unset($payload['response_format']);
                $response = $this->httpClient($apiKey, $timeout)->post($baseUrl . '/chat/completions', $payload);
            }

            if ($response->successful()) {
                $json = $response->json();
                $content = $json['choices'][0]['message']['content'] ?? '';
                $finishReason = strtolower((string) ($json['choices'][0]['finish_reason'] ?? ''));
                $usage = $json['usage'] ?? [];

                $decodedData = JsonCleaner::decode($content);

                if (!is_array($decodedData) || empty($decodedData['title']) || empty($decodedData['content'])) {
                    $errMsg = $finishReason === 'length'
                        ? 'Bài viết bị cắt ngang do vượt giới hạn Max Output Tokens. Vui lòng tăng Max Output Tokens lên 8000.'
                        : 'AI Provider trả về dữ liệu không đúng cấu trúc JSON bài viết.';

                    return [
                        'success' => false,
                        'raw_content' => $content,
                        'data' => null,
                        'prompt_tokens' => (int) ($usage['prompt_tokens'] ?? 0),
                        'completion_tokens' => (int) ($usage['completion_tokens'] ?? 0),
                        'total_tokens' => (int) ($usage['total_tokens'] ?? 0),
                        'error' => $errMsg,
                        'error_code' => 'INVALID_JSON_OUTPUT',
                    ];
                }

                return [
                    'success' => true,
                    'raw_content' => $content,
                    'data' => $decodedData,
                    'prompt_tokens' => (int) ($usage['prompt_tokens'] ?? 0),
                    'completion_tokens' => (int) ($usage['completion_tokens'] ?? 0),
                    'total_tokens' => (int) ($usage['total_tokens'] ?? 0),
                    'error' => null,
                    'error_code' => null,
                ];
            }

            $classification = $this->classifyError($response->status(), $response->json(), $response->body(), $model);
            return [
                'success' => false,
                'raw_content' => null,
                'data' => null,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0,
                'error' => $classification['message'],
                'error_code' => $classification['code'],
            ];
        } catch (ConnectionException $e) {
            return [
                'success' => false,
                'raw_content' => null,
                'data' => null,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0,
                'error' => 'Connection timeout while generating content.',
                'error_code' => 'TIMEOUT',
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'raw_content' => null,
                'data' => null,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0,
                'error' => $e->getMessage(),
                'error_code' => 'RUNTIME_ERROR',
            ];
        }
    }

    /**
     * Generate a cover/thumbnail raster image via an OpenAI-compatible `/images/generations` endpoint.
     * Dynamically discovers active image models from `/models` without hardcoding model IDs.
     */
    public function generateImage(
        AiProvider $provider,
        string $prompt,
        ?string $modelOverride = null,
        string $aspectRatio = '16:9'
    ): array {
        @set_time_limit(180);

        if (!$provider->canDecryptApiKey()) {
            return [
                'success' => false,
                'binary' => null,
                'mime_type' => null,
                'model_used' => null,
                'error' => 'Không thể giải mã API Key.',
                'error_code' => 'DECRYPT_ERROR',
            ];
        }

        $apiKey = trim($provider->getDecryptedApiKey());
        if ($apiKey === '') {
            return [
                'success' => false,
                'binary' => null,
                'mime_type' => null,
                'model_used' => null,
                'error' => 'API Key chưa được cấu hình.',
                'error_code' => 'MISSING_API_KEY',
            ];
        }

        $baseUrl = rtrim($provider->base_url ?: $this->defaultBaseUrl(), '/');
        $imageModel = trim((string) $modelOverride);
        if ($imageModel === '') {
            $discovered = $this->discoverImageModels($apiKey, $baseUrl);
            $imageModel = $discovered[0]['id'] ?? '';
        }

        if ($imageModel === '') {
            return [
                'success' => false,
                'binary' => null,
                'mime_type' => null,
                'model_used' => null,
                'error' => 'Nhà cung cấp hiện tại không có model tạo ảnh khả dụng trên endpoint /models.',
                'error_code' => 'IMAGE_MODEL_UNAVAILABLE',
            ];
        }

        $timeout = max(45, (int) ($provider->timeout ?? 90));
        $preferredSize = $aspectRatio === '16:9' ? '1792x1024' : '1024x1024';

        try {
            $payload = [
                'model' => $imageModel,
                'prompt' => $prompt,
                'n' => 1,
                'size' => $preferredSize,
                'response_format' => 'b64_json',
            ];

            $response = $this->httpClient($apiKey, $timeout)->post($baseUrl . '/images/generations', $payload);

            // Retry with more universal parameters if the specific image model uses standard size or omits response_format
            if ($response->status() === 400) {
                $bodyLower = strtolower((string) $response->body());
                if (str_contains($bodyLower, 'response_format')) {
                    unset($payload['response_format']);
                }
                if (str_contains($bodyLower, 'size') || str_contains($bodyLower, '1792x1024')) {
                    $payload['size'] = $aspectRatio === '16:9' ? '1536x1024' : '1024x1024';
                }
                $response = $this->httpClient($apiKey, $timeout)->post($baseUrl . '/images/generations', $payload);

                if ($response->status() === 400 && ($payload['size'] ?? '') !== '1024x1024') {
                    $payload['size'] = '1024x1024';
                    $response = $this->httpClient($apiKey, $timeout)->post($baseUrl . '/images/generations', $payload);
                }
            }

            if ($response->successful()) {
                $item = $response->json('data.0');
                if (is_array($item)) {
                    if (!empty($item['b64_json']) && is_string($item['b64_json'])) {
                        $binary = base64_decode($item['b64_json'], true);
                        if (is_string($binary) && strlen($binary) > 64) {
                            return [
                                'success' => true,
                                'binary' => $binary,
                                'mime_type' => 'image/png',
                                'model_used' => $imageModel,
                                'error' => null,
                                'error_code' => null,
                            ];
                        }
                    }

                    if (!empty($item['url']) && is_string($item['url']) && str_starts_with($item['url'], 'https://')) {
                        $dl = Http::timeout(30)->get($item['url']);
                        if ($dl->successful() && strlen($dl->body()) > 64) {
                            return [
                                'success' => true,
                                'binary' => $dl->body(),
                                'mime_type' => (string) ($dl->header('Content-Type') ?: 'image/png'),
                                'model_used' => $imageModel,
                                'error' => null,
                                'error_code' => null,
                            ];
                        }
                    }
                }
            }

            $classified = $this->classifyError($response->status(), $response->json(), $response->body(), $imageModel);

            return [
                'success' => false,
                'binary' => null,
                'mime_type' => null,
                'model_used' => $imageModel,
                'error' => $classified['message'],
                'error_code' => $classified['code'],
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'binary' => null,
                'mime_type' => null,
                'model_used' => $imageModel,
                'error' => 'Lỗi gọi API tạo ảnh: ' . $e->getMessage(),
                'error_code' => 'RUNTIME_ERROR',
            ];
        }
    }

    /**
     * Ask an OpenAI-compatible LLM to art-direct a bespoke visual composition & safe SVG vector scene.
     */
    public function generateVisualDesignSpec(AiProvider $provider, array $articleContext, ?string $modelOverride = null): ?array
    {
        if (!$provider->canDecryptApiKey()) {
            return null;
        }

        $apiKey = trim($provider->getDecryptedApiKey());
        if ($apiKey === '') {
            return null;
        }

        $baseUrl = rtrim($provider->base_url ?: $this->defaultBaseUrl(), '/');
        $model = trim((string) ($modelOverride ?: $provider->model));
        if ($model === '') {
            $discovered = $this->discoverModels($apiKey, $baseUrl);
            $model = $discovered[0]['id'] ?? '';
        }
        if ($model === '') {
            return null;
        }

        $title = (string) ($articleContext['title'] ?? '');
        $category = (string) ($articleContext['category'] ?? 'Technology');
        $style = (string) ($articleContext['style'] ?? 'modern_tech');
        $promptHint = (string) ($articleContext['prompt'] ?? '');

        $sys = 'You are a Principal Brand & Tech Editorial Illustrator. Return ONLY a JSON object with keys: '
            . 'bg_from (#RRGGBB dark color), bg_to (#RRGGBB dark color), accent_primary (#RRGGBB vibrant color), accent_secondary (#RRGGBB vibrant color), '
            . 'badge (short uppercase tag), headline (concise visual headline), subtitle (short takeaway), '
            . 'motif (one of: cloud_nodes, neural_net, code_matrix, shield_security, microservices, database_cluster), '
            . 'svg_elements (clean SVG primitives <circle>, <rect>, <path>, <line>, <polygon>, <g> inside a 420x420 box without <svg> or <script>).';

        $usr = "Article Title: {$title}\nCategory: {$category}\nVisual Style: {$style}\nVisual Concept: {$promptHint}";

        try {
            $payload = [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $sys],
                    ['role' => 'user', 'content' => $usr],
                ],
                'temperature' => 0.7,
                'max_tokens' => 1200,
                'response_format' => ['type' => 'json_object'],
            ];

            $resp = $this->httpClient($apiKey, 25)->post($baseUrl . '/chat/completions', $payload);
            if ($resp->status() === 400 && str_contains(strtolower((string) $resp->body()), 'response_format')) {
                unset($payload['response_format']);
                $resp = $this->httpClient($apiKey, 25)->post($baseUrl . '/chat/completions', $payload);
            }

            if ($resp->successful()) {
                $content = (string) ($resp->json('choices.0.message.content') ?? '');
                $parsed = JsonCleaner::decode($content);
                if (is_array($parsed) && !empty($parsed['accent_primary']) && !isset($parsed['content'])) {
                    return $parsed;
                }
            }
        } catch (Throwable $e) {
            return null;
        }

        return null;
    }

    protected function classifyError(int $statusCode, ?array $bodyJson, string $rawBody, string $model): array
    {
        $apiMessage = $bodyJson['error']['message'] ?? '';
        $apiCode = $bodyJson['error']['code'] ?? '';

        if ($statusCode === 401) {
            return [
                'success' => false,
                'message' => 'Invalid API Key: Khóa API OpenAI không chính xác hoặc đã hết hạn.',
                'code' => 'INVALID_API_KEY',
                'status_code' => 401,
                'model_available' => false,
            ];
        }

        if ($statusCode === 429) {
            if (str_contains(strtolower($apiMessage), 'quota') || $apiCode === 'insufficient_quota') {
                return [
                    'success' => false,
                    'message' => 'Insufficient Quota: Tài khoản OpenAI đã hết hạn mức (credits/quota). Vui lòng nạp thêm tín dụng.',
                    'code' => 'INSUFFICIENT_QUOTA',
                    'status_code' => 429,
                    'model_available' => false,
                ];
            }
            return [
                'success' => false,
                'message' => 'Rate Limit Exceeded: Đã vượt quá số lượng request cho phép mỗi phút của OpenAI.',
                'code' => 'RATE_LIMIT_EXCEEDED',
                'status_code' => 429,
                'model_available' => true,
            ];
        }

        if ($statusCode === 404 || str_contains(strtolower($apiMessage), 'model')) {
            return [
                'success' => false,
                'message' => "Model Not Found: Model '{$model}' không tồn tại hoặc tài khoản chưa được cấp quyền truy cập.",
                'code' => 'MODEL_NOT_FOUND',
                'status_code' => 404,
                'model_available' => false,
            ];
        }

        if ($statusCode >= 500) {
            return [
                'success' => false,
                'message' => 'Provider Unavailable: Máy chủ OpenAI đang gặp sự cố hoặc bảo trì (Mã lỗi ' . $statusCode . ').',
                'code' => 'PROVIDER_UNAVAILABLE',
                'status_code' => $statusCode,
                'model_available' => false,
            ];
        }

        return [
            'success' => false,
            'message' => 'Lỗi API OpenAI: ' . ($apiMessage ?: 'Mã HTTP ' . $statusCode),
            'code' => 'API_ERROR',
            'status_code' => $statusCode,
            'model_available' => false,
        ];
    }
}
