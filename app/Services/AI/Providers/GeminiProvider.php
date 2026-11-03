<?php

namespace App\Services\AI\Providers;

use App\Models\AiProvider;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Support\JsonCleaner;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GeminiProvider implements AIProviderInterface
{
    /**
     * In-memory cache of discovered model metadata keyed by model ID.
     *
     * @var array<string, array{id: string, display_name: string, output_token_limit: int}>
     */
    protected array $discoveredModelMeta = [];

    protected function defaultBaseUrl(): string
    {
        return rtrim((string) config('ai.providers.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');
    }

    protected function defaultVertexExpressBaseUrl(): string
    {
        return rtrim((string) config('ai.providers.gemini.vertex_express_base_url', 'https://aiplatform.googleapis.com/v1/publishers/google'), '/');
    }

    /**
     * Normalize user-supplied model identifier syntax without hardcoding any model version names.
     */
    public function normalizeModel(?string $model): string
    {
        $m = trim((string) $model);

        while (str_starts_with(strtolower($m), 'models/')) {
            $m = trim(substr($m, 7));
        }

        $m = ltrim($m, '/');
        $m = strtolower(trim($m));
        $m = preg_replace('/\s+/', '-', $m);

        // Treat generic vendor labels as empty so live discovery resolves an active model dynamically
        if (in_array($m, ['', 'gemini', 'google', 'google-gemini', 'gemini-ai'], true)) {
            return '';
        }

        return $m;
    }

    public function isExplicitVertexUrl(?string $baseUrl): bool
    {
        return !empty($baseUrl) && str_contains(strtolower($baseUrl), 'aiplatform.googleapis.com');
    }

    public function isAqKey(string $apiKey): bool
    {
        return preg_match('/^AQ\./i', trim($apiKey)) === 1;
    }

    /**
     * Determine if this provider should use the Google AI Studio Interactions API (/v1beta/interactions).
     */
    public function canUseInteractionsEndpoint(?string $configuredBaseUrl): bool
    {
        if (!config('ai.providers.gemini.use_interactions_api', true)) {
            return false;
        }

        $cleanBase = rtrim(trim((string) $configuredBaseUrl), '/');
        if ($cleanBase === '') {
            return str_contains(strtolower($this->defaultBaseUrl()), 'generativelanguage.googleapis.com');
        }

        return str_contains(strtolower($cleanBase), 'generativelanguage.googleapis.com');
    }

    public function resolveInteractionsUrl(?string $configuredBaseUrl): string
    {
        $cleanBase = rtrim(trim((string) $configuredBaseUrl), '/');
        $base = $cleanBase !== '' ? $cleanBase : $this->defaultBaseUrl();

        if (str_ends_with(strtolower($base), '/interactions')) {
            return $base;
        }

        return $base . '/interactions';
    }

    public function resolveModelsListUrl(?string $configuredBaseUrl): string
    {
        $cleanBase = rtrim(trim((string) $configuredBaseUrl), '/');
        $base = $cleanBase !== '' ? $cleanBase : $this->defaultBaseUrl();

        if (str_ends_with(strtolower($base), '/interactions')) {
            $base = substr($base, 0, -strlen('/interactions'));
        }

        return $base . '/models';
    }

    /**
     * Resolve model-specific generateContent endpoint (authenticated strictly via x-goog-api-key header).
     */
    public function resolveEndpointUrl(string $apiKey, ?string $configuredBaseUrl, string $model, bool $forceVertexExpress = false): string
    {
        $cleanBase = rtrim(trim((string) $configuredBaseUrl), '/');

        if ($forceVertexExpress || $this->isExplicitVertexUrl($cleanBase)) {
            if ($cleanBase === '' || str_contains(strtolower($cleanBase), 'generativelanguage.googleapis.com')) {
                $base = $this->defaultVertexExpressBaseUrl();
            } elseif (preg_match('#^https?://aiplatform\.googleapis\.com$#i', $cleanBase)) {
                $base = $cleanBase . '/v1/publishers/google';
            } elseif (preg_match('#^https?://aiplatform\.googleapis\.com/v1(beta1)?$#i', $cleanBase)) {
                $base = $cleanBase . '/publishers/google';
            } else {
                $base = $cleanBase;
            }

            return "{$base}/models/{$model}:generateContent";
        }

        $base = $cleanBase !== '' ? $cleanBase : $this->defaultBaseUrl();
        if (str_ends_with(strtolower($base), '/interactions')) {
            $base = substr($base, 0, -strlen('/interactions'));
        }

        return "{$base}/models/{$model}:generateContent";
    }

    protected function httpClient(int $timeout, string $apiKey): PendingRequest
    {
        $client = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-goog-api-key' => $apiKey,
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

    /**
     * Dynamically discover available text-generation Gemini models from Google's official `/models` endpoint.
     * Zero hardcoded model names: version priority and token limits are derived directly from the live API catalog.
     *
     * @return array<int, array{id: string, display_name: string, output_token_limit: int}>
     */
    public function discoverModels(string $apiKey, ?string $configuredBaseUrl = null, bool $forceRefresh = false): array
    {
        $apiKey = trim($apiKey);
        if ($apiKey === '' || $this->isExplicitVertexUrl($configuredBaseUrl)) {
            return [];
        }

        $modelsUrl = $this->resolveModelsListUrl($configuredBaseUrl);
        $cacheKey = 'gemini_live_models_' . substr( hash('sha256', $apiKey . '|' . $modelsUrl), 0, 24 );
        $ttl = max(60, (int) config('ai.providers.gemini.models_cache_ttl', 1800));

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        $fetcher = function () use ($apiKey, $modelsUrl): ?array {
            try {
                $resp = $this->httpClient(15, $apiKey)->get($modelsUrl, ['pageSize' => 100]);
                if (!$resp->successful()) {
                    return null;
                }

                $rawModels = $resp->json('models');
                if (!is_array($rawModels) || empty($rawModels)) {
                    return null;
                }

                $excludedKeywords = [
                    'embedding',
                    'imagen',
                    'veo',
                    'tts',
                    'audio',
                    'robotics',
                    'aqa',
                    'bisheng',
                    'computer-use',
                    'image-generation',
                    'image-preview',
                    'live',
                    'learnlm',
                ];

                $candidates = [];
                foreach ($rawModels as $item) {
                    if (!is_array($item) || empty($item['name'])) {
                        continue;
                    }

                    $methods = $item['supportedGenerationMethods'] ?? null;
                    if (is_array($methods) && !in_array('generateContent', $methods, true)) {
                        continue;
                    }

                    $id = $this->normalizeModel((string) $item['name']);
                    if ($id === '' || !str_starts_with($id, 'gemini-')) {
                        continue;
                    }

                    $skip = false;
                    foreach ($excludedKeywords as $kw) {
                        if (str_contains($id, $kw)) {
                            $skip = true;
                            break;
                        }
                    }
                    if ($skip) {
                        continue;
                    }

                    $candidates[$id] = [
                        'id' => $id,
                        'display_name' => (string) ($item['displayName'] ?? $id),
                        'output_token_limit' => (int) ($item['outputTokenLimit'] ?? 0),
                        '_score' => $this->scoreDiscoveredModel($id),
                    ];
                }

                if (empty($candidates)) {
                    return null;
                }

                $list = array_values($candidates);
                usort($list, fn (array $a, array $b) => $b['_score'] <=> $a['_score']);

                return array_map(function (array $m): array {
                    unset($m['_score']);
                    return $m;
                }, $list);
            } catch (Throwable $e) {
                return null;
            }
        };

        $cached = Cache::get($cacheKey);
        if (is_array($cached) && !empty($cached)) {
            $models = $cached;
        } else {
            $fresh = $fetcher();
            if (is_array($fresh) && !empty($fresh)) {
                Cache::put($cacheKey, $fresh, $ttl);
                $models = $fresh;
            } else {
                $models = [];
            }
        }

        foreach ($models as $meta) {
            if (!empty($meta['id'])) {
                $this->discoveredModelMeta[$meta['id']] = $meta;
            }
        }

        return $models;
    }

    /**
     * Rank discovered models algorithmically by semantic version and stability (no hardcoded model strings).
     */
    protected function scoreDiscoveredModel(string $id): int
    {
        $score = 0;

        if (preg_match('/gemini-(\d+)(?:\.(\d+))?/', $id, $m)) {
            $major = (int) $m[1];
            $minor = isset($m[2]) && $m[2] !== '' ? (int) $m[2] : 0;
            $score += ($major * 100) + ($minor * 10);
        }

        // Prefer fast general-purpose Flash models for reliable blog generation & lower latency
        if (str_contains($id, 'flash')) {
            $score += 35;
        } elseif (str_contains($id, 'pro')) {
            $score += 20;
        }

        // Prefer stable releases over preview/experimental snapshots
        if (!str_contains($id, 'exp') && !str_contains($id, 'preview')) {
            $score += 25;
        }

        // Prefer canonical aliases over dated snapshot suffixes (e.g. -001, -05-20)
        if (!preg_match('/-\d{2,4}(?:-\d{2})?$/', $id)) {
            $score += 8;
        }

        return $score;
    }

    /**
     * JSON Schema for Structured Outputs per Google AI Studio API Reference.
     */
    public function blogPostJsonSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
                'slug' => ['type' => 'string'],
                'excerpt' => ['type' => 'string'],
                'content' => ['type' => 'string'],
                'meta_title' => ['type' => 'string'],
                'meta_description' => ['type' => 'string'],
                'keywords' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'category' => ['type' => 'string'],
                'tags' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'faq' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'question' => ['type' => 'string'],
                            'answer' => ['type' => 'string'],
                        ],
                        'required' => ['question', 'answer'],
                    ],
                ],
                'sources' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'url' => ['type' => 'string'],
                        ],
                        'required' => ['title', 'url'],
                    ],
                ],
                'cover_image_prompt' => ['type' => 'string'],
            ],
            'required' => [
                'title',
                'slug',
                'excerpt',
                'content',
                'meta_title',
                'meta_description',
                'keywords',
                'category',
                'tags',
            ],
        ];
    }

    public function testConnection(AiProvider $provider): array
    {
        if (!$provider->canDecryptApiKey()) {
            return [
                'success' => false,
                'message' => 'Không thể giải mã API Key đã lưu (có thể APP_KEY trên server đã thay đổi). Vui lòng nhấn "Sửa" và nhập lại API Key Gemini.',
                'code' => 'DECRYPT_ERROR',
                'status_code' => 400,
                'model_available' => false,
            ];
        }

        $apiKey = trim($provider->getDecryptedApiKey());
        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'API Key Gemini chưa được cấu hình.',
                'code' => 'MISSING_API_KEY',
                'status_code' => 400,
                'model_available' => false,
            ];
        }

        $requestedModel = $this->normalizeModel($provider->model);
        $isExplicitVertex = $this->isExplicitVertexUrl($provider->base_url);
        $timeout = max(15, (int) ($provider->timeout ?: 30));

        $modelsToTry = $requestedModel !== '' ? [$requestedModel] : [];
        $discoveryAttempted = false;

        if (empty($modelsToTry)) {
            $discoveryAttempted = true;
            foreach ($this->discoverModels($apiKey, $provider->base_url) as $meta) {
                $modelsToTry[] = $meta['id'];
            }
        }

        if (empty($modelsToTry)) {
            return [
                'success' => false,
                'message' => 'Chưa cấu hình tên Model và không thể tự động lấy danh sách Model từ Google AI Studio API.',
                'code' => 'MODEL_NOT_FOUND',
                'status_code' => 404,
                'model_available' => false,
            ];
        }

        try {
            $lastStatusCode = 500;
            $lastJson = null;
            $requestedModelWasNotFound = false;

            for ($idx = 0; $idx < count($modelsToTry); $idx++) {
                $candidateModel = $modelsToTry[$idx];
                $response = null;
                $usedVertexFallback = false;
                $usedInteractions = false;

                // 1. Primary: Google AI Studio Interactions API (/v1beta/interactions)
                if ($this->canUseInteractionsEndpoint($provider->base_url)) {
                    $interactionsUrl = $this->resolveInteractionsUrl($provider->base_url);
                    $interactionsResp = $this->httpClient($timeout, $apiKey)->post($interactionsUrl, [
                        'model' => $candidateModel,
                        'input' => 'Reply with one word: OK',
                        'generation_config' => [
                            'max_output_tokens' => 128,
                        ],
                    ]);

                    $interJson = $interactionsResp->json();
                    if ($interactionsResp->successful() && is_array($interJson) && !empty($interJson)) {
                        $response = $interactionsResp;
                        $usedInteractions = true;
                    } elseif (!in_array($interactionsResp->status(), [200, 404], true)) {
                        $response = $interactionsResp;
                    }
                }

                // 2. Fallback / Custom Base URL: models/{model}:generateContent
                if ($response === null || ($response->status() === 404 && !$usedInteractions)) {
                    $url = $this->resolveEndpointUrl($apiKey, $provider->base_url, $candidateModel);
                    $response = $this->httpClient($timeout, $apiKey)->post($url, [
                        'contents' => [
                            [
                                'role' => 'user',
                                'parts' => [['text' => 'Reply with one word: OK']],
                            ],
                        ],
                        'generationConfig' => [
                            'maxOutputTokens' => 256,
                        ],
                    ]);
                }

                // 3. Optional Vertex AI Express Mode fallback for AQ.* keys if AI Studio rejected auth
                if (
                    !$response->successful()
                    && in_array($response->status(), [400, 401, 403], true)
                    && empty($provider->base_url)
                    && $this->isAqKey($apiKey)
                ) {
                    $vertexUrl = $this->resolveEndpointUrl($apiKey, null, $candidateModel, true);
                    $vertexResp = $this->httpClient($timeout, $apiKey)->post($vertexUrl, [
                        'contents' => [
                            [
                                'role' => 'user',
                                'parts' => [['text' => 'Reply with one word: OK']],
                            ],
                        ],
                        'generationConfig' => [
                            'maxOutputTokens' => 256,
                        ],
                    ]);
                    if ($vertexResp->successful()) {
                        $response = $vertexResp;
                        $usedVertexFallback = true;
                    }
                }

                $statusCode = $response->status();
                $bodyJson = $response->json();

                if ($response->successful()) {
                    $modeLabel = ($isExplicitVertex || $usedVertexFallback)
                        ? 'Google Vertex AI Express Mode'
                        : ($usedInteractions ? 'Google Gemini (Interactions API)' : 'Google Gemini');

                    if ($usedVertexFallback && $provider->exists && empty($provider->base_url)) {
                        $provider->update(['base_url' => $this->defaultVertexExpressBaseUrl()]);
                    }

                    if ($provider->exists && ($requestedModel === '' || ($idx > 0 && $requestedModelWasNotFound))) {
                        $provider->update(['model' => $candidateModel]);
                        return [
                            'success' => true,
                            'message' => "Kết nối {$modeLabel} thành công! Model cũ không còn tồn tại trên API nên hệ thống đã tự động đồng bộ sang '{$candidateModel}'.",
                            'code' => 'CONNECTED',
                            'status_code' => $statusCode,
                            'model_available' => true,
                        ];
                    }

                    if ($idx > 0) {
                        return [
                            'success' => true,
                            'message' => "Kết nối {$modeLabel} thành công qua model khả dụng '{$candidateModel}' (model '{$requestedModel}' đang tạm quá tải).",
                            'code' => 'CONNECTED',
                            'status_code' => $statusCode,
                            'model_available' => true,
                        ];
                    }

                    if ($provider->exists && $provider->model !== $candidateModel) {
                        $provider->update(['model' => $candidateModel]);
                    }

                    return [
                        'success' => true,
                        'message' => "Kết nối {$modeLabel} thành công! Model '{$candidateModel}' phản hồi tốt.",
                        'code' => 'CONNECTED',
                        'status_code' => $statusCode,
                        'model_available' => true,
                    ];
                }

                if ($idx === 0 && $statusCode === 404) {
                    $requestedModelWasNotFound = true;
                }

                $lastStatusCode = $statusCode;
                $lastJson = $bodyJson;

                if (!in_array($statusCode, [404, 429, 503], true)) {
                    break;
                }

                // On 404 / 429 / 503, dynamically discover active models from Google's `/models` API once
                if (!$discoveryAttempted) {
                    $discoveryAttempted = true;
                    foreach (array_slice($this->discoverModels($apiKey, $provider->base_url), 0, 5) as $meta) {
                        if (!in_array($meta['id'], $modelsToTry, true)) {
                            $modelsToTry[] = $meta['id'];
                        }
                    }
                }
            }

            return $this->classifyError($lastStatusCode, $lastJson, $requestedModel ?: 'gemini');
        } catch (ConnectionException $e) {
            return [
                'success' => false,
                'message' => 'Connection timeout: Không thể kết nối tới máy chủ Google Gemini trong thời gian chờ (' . $e->getMessage() . ').',
                'code' => 'TIMEOUT',
                'status_code' => 408,
                'model_available' => false,
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => 'Lỗi kết nối Gemini: ' . $e->getMessage(),
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
                'error' => 'Không thể giải mã API Key Gemini (APP_KEY thay đổi). Vui lòng nhập lại API Key trong mục AI Providers.',
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
                'error' => 'API Key Gemini chưa được cấu hình.',
                'error_code' => 'MISSING_API_KEY',
            ];
        }

        $requestedModel = $this->normalizeModel($modelOverride ?: $provider->model);
        $temperature = (float) ($provider->temperature ?? 0.7);
        $configuredMaxTokens = (int) ($provider->max_tokens ?? 16384);
        $timeout = max(90, (int) ($provider->timeout ?? 120));

        $modelsToTry = $requestedModel !== '' ? [$requestedModel] : [];
        $discoveryAttempted = false;

        if (empty($modelsToTry)) {
            $discoveryAttempted = true;
            foreach (array_slice($this->discoverModels($apiKey, $provider->base_url), 0, 5) as $meta) {
                $modelsToTry[] = $meta['id'];
            }
        }

        if (empty($modelsToTry)) {
            return [
                'success' => false,
                'raw_content' => null,
                'data' => null,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0,
                'error' => 'Chưa cấu hình tên Model và không thể tự động lấy danh sách Model từ Google AI Studio API.',
                'error_code' => 'MODEL_NOT_FOUND',
            ];
        }

        try {
            $lastStatusCode = 500;
            $lastJson = null;
            $lastInvalidJsonFailure = null;
            $jsonRepairRetried = false;
            $requestedModelWasNotFound = false;

            for ($idx = 0; $idx < count($modelsToTry); $idx++) {
                $model = $modelsToTry[$idx];
                $effectiveMaxTokens = $this->resolveMaxOutputTokens($model, $configuredMaxTokens);

                $activeUserPrompt = $lastInvalidJsonFailure !== null
                    ? $userPrompt . "\n\nCRITICAL JSON RULE: Output ONLY a single valid RFC 8259 JSON object matching the schema with non-empty \"title\" and \"content\" HTML strings. Escape all backslashes (\\\\) and double quotes (\\\") properly inside strings."
                    : $userPrompt;

                $attemptResult = $this->dispatchGenerationRequest(
                    $apiKey,
                    $provider->base_url,
                    $model,
                    $systemPrompt,
                    $activeUserPrompt,
                    $temperature,
                    $effectiveMaxTokens,
                    $timeout
                );

                $response = $attemptResult['response'];
                $usedVertexFallback = $attemptResult['used_vertex_fallback'];

                if ($response->successful()) {
                    if ($usedVertexFallback && $provider->exists && empty($provider->base_url)) {
                        $provider->update(['base_url' => $this->defaultVertexExpressBaseUrl()]);
                    }

                    if (empty($modelOverride) && $provider->exists && ($requestedModel === '' || ($idx > 0 && $requestedModelWasNotFound))) {
                        $provider->update(['model' => $model]);
                    }

                    $json = $response->json();
                    $rawContent = $this->extractResponseText($json);
                    $tokens = $this->extractTokenUsage($json);
                    $finishReason = $this->extractFinishReason($json);

                    $decodedData = JsonCleaner::decode($rawContent);

                    if (is_array($decodedData) && !empty($decodedData['title']) && !empty($decodedData['content'])) {
                        return [
                            'success' => true,
                            'raw_content' => $rawContent,
                            'data' => $decodedData,
                            'prompt_tokens' => $tokens['prompt_tokens'],
                            'completion_tokens' => $tokens['completion_tokens'],
                            'total_tokens' => $tokens['total_tokens'],
                            'error' => null,
                            'error_code' => null,
                        ];
                    }

                    Log::warning('Gemini returned unparseable or incomplete blog JSON', [
                        'model' => $model,
                        'finish_reason' => $finishReason,
                        'json_error' => json_last_error_msg(),
                        'raw_length' => strlen($rawContent),
                        'raw_preview' => mb_substr($rawContent, 0, 500),
                    ]);

                    $reasonDetail = match ($finishReason) {
                        'MAX_TOKENS', 'INCOMPLETE' => 'Bài viết bị cắt ngang do vượt giới hạn token đầu ra (MAX_TOKENS). Vui lòng chọn độ dài Ngắn/Tiêu chuẩn hoặc tăng Max Output Tokens trong AI Provider.',
                        'SAFETY', 'BLOCKLIST', 'PROHIBITED_CONTENT' => 'Nội dung yêu cầu bị bộ lọc an toàn của Google Gemini từ chối (' . $finishReason . ').',
                        default => 'Google Gemini trả về dữ liệu không đúng cấu trúc JSON bài viết (finishReason: ' . ($finishReason ?: 'UNKNOWN') . '). Vui lòng bấm tạo thử lại.',
                    };

                    $lastInvalidJsonFailure = [
                        'success' => false,
                        'raw_content' => $rawContent,
                        'data' => null,
                        'prompt_tokens' => $tokens['prompt_tokens'],
                        'completion_tokens' => $tokens['completion_tokens'],
                        'total_tokens' => $tokens['total_tokens'],
                        'error' => $reasonDetail,
                        'error_code' => 'INVALID_JSON_OUTPUT',
                    ];

                    // Self-healing retry once on transient invalid/truncated JSON (excluding safety blocks)
                    if (!$jsonRepairRetried && !in_array($finishReason, ['SAFETY', 'BLOCKLIST', 'PROHIBITED_CONTENT'], true)) {
                        $jsonRepairRetried = true;
                        $effectiveMaxTokens = max($effectiveMaxTokens, 24576);
                        $modelsToTry[] = $model;
                        continue;
                    }

                    return $lastInvalidJsonFailure;
                }

                if ($idx === 0 && $response->status() === 404) {
                    $requestedModelWasNotFound = true;
                }

                $lastStatusCode = $response->status();
                $lastJson = $response->json();

                if (!in_array($lastStatusCode, [404, 429, 503], true)) {
                    break;
                }

                if (!$discoveryAttempted) {
                    $discoveryAttempted = true;
                    foreach (array_slice($this->discoverModels($apiKey, $provider->base_url), 0, 5) as $meta) {
                        if (!in_array($meta['id'], $modelsToTry, true)) {
                            $modelsToTry[] = $meta['id'];
                        }
                    }
                }
            }

            if ($lastInvalidJsonFailure !== null) {
                return $lastInvalidJsonFailure;
            }

            $classification = $this->classifyError($lastStatusCode, $lastJson, $requestedModel ?: 'gemini');
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
                'error' => 'Quá thời gian chờ kết nối tới Google Gemini API (Timeout). Hãy tăng Timeout của Provider lên 120 giây hoặc chọn độ dài bài viết Tiêu chuẩn.',
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
     * Dispatch a generation call via Interactions API primary -> generateContent fallback -> Vertex Express fallback.
     *
     * @return array{response: Response, used_vertex_fallback: bool}
     */
    protected function dispatchGenerationRequest(
        string $apiKey,
        ?string $baseUrl,
        string $model,
        string $systemPrompt,
        string $userPrompt,
        float $temperature,
        int $effectiveMaxTokens,
        int $timeout
    ): array {
        $response = null;
        $usedVertexFallback = false;

        // 1. Primary: Google AI Studio Interactions API (/v1beta/interactions) with Structured JSON Schema
        if ($this->canUseInteractionsEndpoint($baseUrl)) {
            $interactionsUrl = $this->resolveInteractionsUrl($baseUrl);
            $interactionsPayload = [
                'model' => $model,
                'system_instruction' => $systemPrompt,
                'input' => $userPrompt,
                'response_mime_type' => 'application/json',
                'response_format' => $this->blogPostJsonSchema(),
                'generation_config' => [
                    'temperature' => $temperature,
                    'max_output_tokens' => $effectiveMaxTokens,
                ],
            ];

            $interactionsResp = $this->httpClient($timeout, $apiKey)->post($interactionsUrl, $interactionsPayload);
            $interJson = $interactionsResp->json();

            if ($interactionsResp->successful() && is_array($interJson) && !empty($interJson)) {
                $response = $interactionsResp;
            } elseif (!in_array($interactionsResp->status(), [200, 404], true)) {
                $interErr = strtolower((string) ($interJson['error']['message'] ?? ''));
                $isSchemaUnsupported = $interactionsResp->status() === 400
                    && (str_contains($interErr, 'response_format') || str_contains($interErr, 'response_mime_type') || str_contains($interErr, 'schema'));
                if (!$isSchemaUnsupported) {
                    $response = $interactionsResp;
                }
            }
        }

        // 2. Fallback / Custom Base URL: models/{model}:generateContent (with responseJsonSchema + fallback)
        if ($response === null) {
            $response = $this->callGenerateContent($apiKey, $baseUrl, $model, $systemPrompt, $userPrompt, $temperature, $effectiveMaxTokens, $timeout);
        }

        // 3. Optional Vertex AI Express Mode fallback for AQ.* keys
        if (
            !$response->successful()
            && in_array($response->status(), [400, 401, 403], true)
            && empty($baseUrl)
            && $this->isAqKey($apiKey)
        ) {
            $vertexResp = $this->callGenerateContent($apiKey, $this->defaultVertexExpressBaseUrl(), $model, $systemPrompt, $userPrompt, $temperature, $effectiveMaxTokens, $timeout, true);
            if ($vertexResp->successful()) {
                $response = $vertexResp;
                $usedVertexFallback = true;
            }
        }

        return [
            'response' => $response,
            'used_vertex_fallback' => $usedVertexFallback,
        ];
    }

    protected function callGenerateContent(
        string $apiKey,
        ?string $baseUrl,
        string $model,
        string $systemPrompt,
        string $userPrompt,
        float $temperature,
        int $effectiveMaxTokens,
        int $timeout,
        bool $forceVertexExpress = false
    ): Response {
        $url = $this->resolveEndpointUrl($apiKey, $baseUrl, $model, $forceVertexExpress);

        $payload = [
            'systemInstruction' => [
                'parts' => [['text' => $systemPrompt]],
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => $userPrompt]],
                ],
            ],
            'generationConfig' => [
                'temperature' => $temperature,
                'maxOutputTokens' => $effectiveMaxTokens,
                'responseMimeType' => 'application/json',
                'responseJsonSchema' => $this->blogPostJsonSchema(),
            ],
        ];

        $response = $this->httpClient($timeout, $apiKey)->post($url, $payload);

        // Fallback if endpoint/proxy does not accept responseJsonSchema
        if ($response->status() === 400) {
            $errMsg = strtolower((string) ($response->json('error.message') ?? ''));
            if (str_contains($errMsg, 'responsejsonschema') || str_contains($errMsg, 'schema') || str_contains($errMsg, 'unknown name')) {
                unset($payload['generationConfig']['responseJsonSchema']);
                $response = $this->httpClient($timeout, $apiKey)->post($url, $payload);
            }
        }

        return $response;
    }

    /**
     * Resolve effective max output tokens using discovered API metadata (`outputTokenLimit`) and config floor.
     */
    protected function resolveMaxOutputTokens(string $model, int $configured): int
    {
        $minFloor = max(8192, (int) config('ai.providers.gemini.min_blog_output_tokens', 16384));
        $desired = max($configured, $minFloor);

        $discoveredLimit = (int) ($this->discoveredModelMeta[$model]['output_token_limit'] ?? 0);
        if ($discoveredLimit > 0) {
            return min($desired, $discoveredLimit);
        }

        return $desired;
    }

    /**
     * Extract generated text from either Interactions API (outputs/steps) or generateContent (candidates).
     */
    protected function extractResponseText(?array $json): string
    {
        if (!is_array($json)) {
            return '';
        }

        // 1. Interactions API format: 'outputs' or 'steps'
        $blocks = $json['outputs'] ?? $json['steps'] ?? null;
        if (is_array($blocks)) {
            $text = '';
            foreach ($blocks as $block) {
                $blockType = $block['type'] ?? '';
                if ($blockType === 'thought') {
                    continue;
                }

                if (isset($block['text']) && is_string($block['text'])) {
                    $text .= $block['text'];
                }

                if (!empty($block['content']) && is_array($block['content'])) {
                    foreach ($block['content'] as $item) {
                        if (($item['type'] ?? 'text') === 'thought') {
                            continue;
                        }
                        if (isset($item['text']) && is_string($item['text'])) {
                            $text .= $item['text'];
                        }
                    }
                }
            }

            if ($text !== '') {
                return $text;
            }
        }

        // 2. Standard generateContent format: 'candidates[0].content.parts'
        $parts = $json['candidates'][0]['content']['parts'] ?? [];
        if (!is_array($parts)) {
            return '';
        }

        $text = '';
        foreach ($parts as $part) {
            if (!empty($part['thought'])) {
                continue;
            }
            if (isset($part['text']) && is_string($part['text'])) {
                $text .= $part['text'];
            }
        }

        if ($text === '' && isset($parts[0]['text']) && is_string($parts[0]['text'])) {
            return $parts[0]['text'];
        }

        return $text;
    }

    protected function extractTokenUsage(?array $json): array
    {
        if (isset($json['usage']) && is_array($json['usage'])) {
            $u = $json['usage'];
            return [
                'prompt_tokens' => (int) ($u['total_input_tokens'] ?? $u['initial_input_tokens'] ?? 0),
                'completion_tokens' => (int) ($u['total_output_tokens'] ?? $u['output_tokens'] ?? 0),
                'total_tokens' => (int) ($u['total_tokens'] ?? 0),
            ];
        }

        $u = $json['usageMetadata'] ?? [];
        return [
            'prompt_tokens' => (int) ($u['promptTokenCount'] ?? 0),
            'completion_tokens' => (int) ($u['candidatesTokenCount'] ?? 0),
            'total_tokens' => (int) ($u['totalTokenCount'] ?? 0),
        ];
    }

    protected function extractFinishReason(?array $json): string
    {
        if (!empty($json['candidates'][0]['finishReason'])) {
            return strtoupper((string) $json['candidates'][0]['finishReason']);
        }

        if (!empty($json['status'])) {
            return strtoupper((string) $json['status']);
        }

        return '';
    }

    protected function classifyError(int $statusCode, ?array $bodyJson, string $model): array
    {
        $message = (string) ($bodyJson['error']['message'] ?? '');
        $status = strtoupper((string) ($bodyJson['error']['status'] ?? $bodyJson['error']['code'] ?? ''));

        if ($statusCode === 401 || $status === 'UNAUTHENTICATED') {
            return [
                'success' => false,
                'message' => 'Invalid API Key / Unauthenticated: Khóa API Google Gemini không hợp lệ hoặc đã hết hạn. (' . $message . ')',
                'code' => 'INVALID_API_KEY',
                'status_code' => 401,
                'model_available' => false,
            ];
        }

        if ($statusCode === 400 || in_array($status, ['INVALID_ARGUMENT', 'INVALID_REQUEST'], true)) {
            if (str_contains(strtolower($message), 'api_key_invalid') || str_contains(strtolower($message), 'api key not valid')) {
                return [
                    'success' => false,
                    'message' => 'Invalid API Key: Khóa API Google Gemini không hợp lệ. Vui lòng lấy khóa mới tại Google AI Studio (aistudio.google.com).',
                    'code' => 'INVALID_API_KEY',
                    'status_code' => 400,
                    'model_available' => false,
                ];
            }

            if (str_contains(strtolower($message), 'unexpected model name format') || str_contains(strtolower($message), 'model name format')) {
                return [
                    'success' => false,
                    'message' => "Tên Model Gemini '{$model}' không đúng định dạng của Google AI. Vui lòng bấm 'Lấy danh sách Model từ API' trong mục AI Provider để chọn model hợp lệ.",
                    'code' => 'INVALID_MODEL_FORMAT',
                    'status_code' => 400,
                    'model_available' => false,
                ];
            }
        }

        if ($statusCode === 403 || $status === 'PERMISSION_DENIED') {
            return [
                'success' => false,
                'message' => 'Permission Denied: Khóa API không có quyền truy cập hoặc chưa bật Generative Language API trong Google AI Studio.',
                'code' => 'PERMISSION_DENIED',
                'status_code' => 403,
                'model_available' => false,
            ];
        }

        if ($statusCode === 404 || $status === 'NOT_FOUND' || str_contains(strtolower($message), 'not found') || str_contains(strtolower($message), 'no longer available')) {
            return [
                'success' => false,
                'message' => "Model Not Found: Model '{$model}' không tồn tại hoặc đã ngừng hỗ trợ. Vui lòng bấm 'Lấy danh sách Model từ API' để chọn model khả dụng.",
                'code' => 'MODEL_NOT_FOUND',
                'status_code' => 404,
                'model_available' => false,
            ];
        }

        if ($statusCode === 429 || $status === 'RESOURCE_EXHAUSTED' || $status === 'RATE_LIMIT_EXCEEDED') {
            return [
                'success' => false,
                'message' => 'Quota Exceeded: Đã chạm giới hạn gọi API Google Gemini miễn phí (RPM/RPD). Vui lòng đợi 1 phút rồi thử lại.',
                'code' => 'INSUFFICIENT_QUOTA',
                'status_code' => 429,
                'model_available' => true,
            ];
        }

        if ($statusCode === 503 || $status === 'SERVICE_UNAVAILABLE' || $status === 'UNAVAILABLE') {
            return [
                'success' => false,
                'message' => 'High Demand (503): Máy chủ Google Gemini đang có lưu lượng truy cập cao tạm thời. Vui lòng bấm thử lại sau vài giây.',
                'code' => 'PROVIDER_UNAVAILABLE',
                'status_code' => 503,
                'model_available' => true,
            ];
        }

        if ($statusCode >= 500) {
            return [
                'success' => false,
                'message' => 'Provider Unavailable: Máy chủ Google AI đang bảo trì hoặc quá tải (HTTP ' . $statusCode . '). Vui lòng thử lại sau ít phút.',
                'code' => 'PROVIDER_UNAVAILABLE',
                'status_code' => $statusCode,
                'model_available' => false,
            ];
        }

        return [
            'success' => false,
            'message' => 'Lỗi Gemini API: ' . ($message ?: 'Mã lỗi HTTP ' . $statusCode),
            'code' => 'API_ERROR',
            'status_code' => $statusCode,
            'model_available' => false,
        ];
    }
}
