<?php

namespace App\Services\AI\Providers;

use App\Models\AiProvider;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Support\JsonCleaner;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Throwable;

class GeminiProvider implements AIProviderInterface
{
    protected string $defaultBaseUrl = 'https://generativelanguage.googleapis.com/v1beta';
    protected string $defaultVertexExpressBaseUrl = 'https://aiplatform.googleapis.com/v1/publishers/google';

    /**
     * Map legacy/retired Gemini model IDs to current supported production models on v1beta.
     */
    protected array $retiredModelMap = [
        'gemini-1.5-flash' => 'gemini-3.5-flash',
        'gemini-1.5-flash-latest' => 'gemini-3.5-flash',
        'gemini-1.5-flash-001' => 'gemini-3.5-flash',
        'gemini-1.5-flash-002' => 'gemini-3.5-flash',
        'gemini-1.5-flash-8b' => 'gemini-3.5-flash',
        'gemini-1.5-flash-8b-latest' => 'gemini-3.5-flash',
        'gemini-2.0-flash' => 'gemini-3.5-flash',
        'gemini-2.0-flash-exp' => 'gemini-3.5-flash',
        'gemini-1.5-pro' => 'gemini-3.5-flash',
        'gemini-1.5-pro-latest' => 'gemini-3.5-flash',
        'gemini-1.5-pro-001' => 'gemini-3.5-flash',
        'gemini-1.5-pro-002' => 'gemini-3.5-flash',
        'gemini-pro' => 'gemini-3.5-flash',
        'gemini-1.0-pro' => 'gemini-3.5-flash',
    ];

    protected array $fallbackModels = [
        'gemini-3.5-flash',
        'gemini-3.8-flash',
        'gemini-3.6-flash',
        'gemini-3.5-flash-lite',
        'gemini-3.1-flash-lite',
        'gemini-2.5-flash',
    ];

    public function normalizeModel(?string $model): string
    {
        $m = trim((string) $model);

        // Strip repeated 'models/' prefixes if present
        while (str_starts_with(strtolower($m), 'models/')) {
            $m = substr($m, 7);
            $m = trim($m);
        }

        $m = ltrim($m, '/');
        $m = strtolower(trim($m));
        // Replace spaces with hyphens (e.g., 'gemini 3.5 flash' -> 'gemini-3.5-flash')
        $m = preg_replace('/\s+/', '-', $m);

        // If empty or common generic keywords, default to recommended model
        if (in_array($m, ['', 'gemini', 'google', 'google-gemini', 'gemini-ai'], true)) {
            return 'gemini-3.5-flash';
        }

        // Automatically upgrade retired Gemini 1.5 / 2.0 models to active 3.5 equivalents
        if (isset($this->retiredModelMap[$m])) {
            return $this->retiredModelMap[$m];
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

    public function resolveEndpointUrl(string $apiKey, ?string $configuredBaseUrl, string $model, bool $forceVertexExpress = false): string
    {
        $cleanBase = rtrim(trim((string) $configuredBaseUrl), '/');

        if ($forceVertexExpress || $this->isExplicitVertexUrl($cleanBase)) {
            if ($cleanBase === '' || str_contains(strtolower($cleanBase), 'generativelanguage.googleapis.com')) {
                $base = $this->defaultVertexExpressBaseUrl;
            } elseif (preg_match('#^https?://aiplatform\.googleapis\.com$#i', $cleanBase)) {
                $base = $cleanBase . '/v1/publishers/google';
            } elseif (preg_match('#^https?://aiplatform\.googleapis\.com/v1(beta1)?$#i', $cleanBase)) {
                $base = $cleanBase . '/publishers/google';
            } else {
                $base = $cleanBase;
            }

            return "{$base}/models/{$model}:generateContent";
        }

        $base = $cleanBase !== '' ? $cleanBase : $this->defaultBaseUrl;
        return "{$base}/models/{$model}:generateContent?key={$apiKey}";
    }

    protected function httpClient(int $timeout, ?string $apiKey = null): PendingRequest
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if (!empty($apiKey)) {
            $headers['x-goog-api-key'] = $apiKey;
        }

        $client = Http::withHeaders($headers)->timeout($timeout);

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

        $modelsToTry = [$requestedModel];
        if (str_starts_with($requestedModel, 'gemini-')) {
            foreach ($this->fallbackModels as $fb) {
                if (!in_array($fb, $modelsToTry, true)) {
                    $modelsToTry[] = $fb;
                }
            }
        }

        try {
            $lastStatusCode = 500;
            $lastJson = null;

            foreach ($modelsToTry as $idx => $candidateModel) {
                $url = $this->resolveEndpointUrl($apiKey, $provider->base_url, $candidateModel);
                $payload = [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [['text' => 'Reply with one word: OK']],
                        ],
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => 256,
                    ],
                ];

                $response = $this->httpClient($timeout, $apiKey)->post($url, $payload);

                // If AQ.* key failed auth on AI Studio and no custom base_url was set, try Vertex AI Express Mode once
                $usedVertexFallback = false;
                if (
                    !$response->successful()
                    && in_array($response->status(), [400, 401, 403], true)
                    && empty($provider->base_url)
                    && $this->isAqKey($apiKey)
                ) {
                    $vertexUrl = $this->resolveEndpointUrl($apiKey, null, $candidateModel, true);
                    $vertexResp = $this->httpClient($timeout, $apiKey)->post($vertexUrl, $payload);
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
                        : 'Google Gemini';

                    if ($usedVertexFallback && $provider->exists && empty($provider->base_url)) {
                        $provider->update(['base_url' => $this->defaultVertexExpressBaseUrl]);
                    }

                    if ($idx > 0 && $provider->exists) {
                        $provider->update(['model' => $candidateModel]);
                        return [
                            'success' => true,
                            'message' => "Kết nối {$modeLabel} thành công! Model cũ không khả dụng hoặc quá tải nên hệ thống đã tự động chuyển sang '{$candidateModel}'.",
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

                $lastStatusCode = $statusCode;
                $lastJson = $bodyJson;

                // Try fallback models on 404 Not Found, 503 High Demand, or 429 Quota
                if (!in_array($statusCode, [404, 429, 503], true)) {
                    break;
                }
            }

            return $this->classifyError($lastStatusCode, $lastJson, $requestedModel);
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
        $configuredMaxTokens = (int) ($provider->max_tokens ?? 8192);
        $timeout = max(90, (int) ($provider->timeout ?? 120));

        $modelsToTry = [$requestedModel];
        if (str_starts_with($requestedModel, 'gemini-')) {
            foreach ($this->fallbackModels as $fb) {
                if (!in_array($fb, $modelsToTry, true)) {
                    $modelsToTry[] = $fb;
                }
            }
        }

        try {
            $lastStatusCode = 500;
            $lastJson = null;

            foreach ($modelsToTry as $idx => $model) {
                $effectiveMaxTokens = $this->resolveMaxOutputTokens($model, $configuredMaxTokens);
                $url = $this->resolveEndpointUrl($apiKey, $provider->base_url, $model);

                $generationConfig = [
                    'temperature' => $temperature,
                    'maxOutputTokens' => $effectiveMaxTokens,
                    'responseMimeType' => 'application/json',
                ];

                if ($model === 'gemini-2.5-flash') {
                    $generationConfig['thinkingConfig'] = [
                        'thinkingBudget' => 1024,
                    ];
                }

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
                    'generationConfig' => $generationConfig,
                ];

                $response = $this->httpClient($timeout, $apiKey)->post($url, $payload);

                // If AQ.* key failed auth on AI Studio and no custom base_url was set, try Vertex AI Express Mode once
                $usedVertexFallback = false;
                if (
                    !$response->successful()
                    && in_array($response->status(), [400, 401, 403], true)
                    && empty($provider->base_url)
                    && $this->isAqKey($apiKey)
                ) {
                    $vertexUrl = $this->resolveEndpointUrl($apiKey, null, $model, true);
                    $vertexResp = $this->httpClient($timeout, $apiKey)->post($vertexUrl, $payload);
                    if ($vertexResp->successful()) {
                        $response = $vertexResp;
                        $usedVertexFallback = true;
                    }
                }

                // If proxy/API version rejects thinkingConfig, retry immediately without it
                if ($response->status() === 400 && isset($payload['generationConfig']['thinkingConfig'])) {
                    $errMsg = strtolower((string) ($response->json('error.message') ?? ''));
                    if (str_contains($errMsg, 'thinking')) {
                        unset($payload['generationConfig']['thinkingConfig']);
                        $targetUrl = $usedVertexFallback
                            ? $this->resolveEndpointUrl($apiKey, null, $model, true)
                            : $url;
                        $response = $this->httpClient($timeout, $apiKey)->post($targetUrl, $payload);
                    }
                }

                if ($response->successful()) {
                    if ($usedVertexFallback && $provider->exists && empty($provider->base_url)) {
                        $provider->update(['base_url' => $this->defaultVertexExpressBaseUrl]);
                    }

                    if ($idx > 0 && empty($modelOverride) && $provider->exists) {
                        $provider->update(['model' => $model]);
                    }

                    $json = $response->json();
                    $rawContent = $this->extractCandidateText($json);
                    $usage = $json['usageMetadata'] ?? [];
                    $finishReason = strtoupper((string) ($json['candidates'][0]['finishReason'] ?? ''));

                    $decodedData = JsonCleaner::decode($rawContent);

                    if (!is_array($decodedData) || empty($decodedData['title']) || empty($decodedData['content'])) {
                        $reasonDetail = match ($finishReason) {
                            'MAX_TOKENS' => 'Bài viết bị cắt ngang do vượt giới hạn token đầu ra (MAX_TOKENS). Vui lòng chọn độ dài Ngắn/Tiêu chuẩn hoặc tăng Max Output Tokens trong AI Provider.',
                            'SAFETY', 'BLOCKLIST', 'PROHIBITED_CONTENT' => 'Nội dung yêu cầu bị bộ lọc an toàn của Google Gemini từ chối (' . $finishReason . ').',
                            default => 'Google Gemini trả về dữ liệu không đúng cấu trúc JSON bài viết (finishReason: ' . ($finishReason ?: 'UNKNOWN') . '). Vui lòng bấm tạo thử lại.',
                        };

                        return [
                            'success' => false,
                            'raw_content' => $rawContent,
                            'data' => null,
                            'prompt_tokens' => (int) ($usage['promptTokenCount'] ?? 0),
                            'completion_tokens' => (int) ($usage['candidatesTokenCount'] ?? 0),
                            'total_tokens' => (int) ($usage['totalTokenCount'] ?? 0),
                            'error' => $reasonDetail,
                            'error_code' => 'INVALID_JSON_OUTPUT',
                        ];
                    }

                    return [
                        'success' => true,
                        'raw_content' => $rawContent,
                        'data' => $decodedData,
                        'prompt_tokens' => (int) ($usage['promptTokenCount'] ?? 0),
                        'completion_tokens' => (int) ($usage['candidatesTokenCount'] ?? 0),
                        'total_tokens' => (int) ($usage['totalTokenCount'] ?? 0),
                        'error' => null,
                        'error_code' => null,
                    ];
                }

                $lastStatusCode = $response->status();
                $lastJson = $response->json();

                // Retry fallback model on 404 Not Found, 503 High Demand, or 429 Quota
                $canFallback = in_array($lastStatusCode, [404, 429, 503], true);

                if (!$canFallback) {
                    break;
                }
            }

            $classification = $this->classifyError($lastStatusCode, $lastJson, $requestedModel);
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

    protected function resolveMaxOutputTokens(string $model, int $configured): int
    {
        if (str_starts_with($model, 'gemini-2.0')) {
            // Gemini 2.0 models enforce a maximum of 8192 output tokens
            return min(max($configured, 8192), 8192);
        }

        if (str_starts_with($model, 'gemini-2.5') || str_starts_with($model, 'gemini-3')) {
            // Gemini 2.5+ thinking models share maxOutputTokens between reasoning and JSON output (up to 65536)
            return min(max($configured, 16384), 65536);
        }

        return max($configured, 8192);
    }

    protected function extractCandidateText(?array $json): string
    {
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

    protected function classifyError(int $statusCode, ?array $bodyJson, string $model): array
    {
        $message = $bodyJson['error']['message'] ?? '';
        $status = $bodyJson['error']['status'] ?? '';

        if ($statusCode === 401 || $status === 'UNAUTHENTICATED') {
            return [
                'success' => false,
                'message' => 'Invalid API Key / Unauthenticated: Khóa API Google Gemini hoặc Vertex AI Express Mode không hợp lệ hoặc đã hết hạn. (' . $message . ')',
                'code' => 'INVALID_API_KEY',
                'status_code' => 401,
                'model_available' => false,
            ];
        }

        if ($statusCode === 400 || $status === 'INVALID_ARGUMENT') {
            if (str_contains(strtolower($message), 'api_key_invalid') || str_contains(strtolower($message), 'api key not valid')) {
                return [
                    'success' => false,
                    'message' => 'Invalid API Key: Khóa API Google Gemini / Vertex AI không hợp lệ. Vui lòng kiểm tra lại key lấy từ Google AI Studio (AIza...) hoặc Vertex AI Express Mode (AQ....).',
                    'code' => 'INVALID_API_KEY',
                    'status_code' => 400,
                    'model_available' => false,
                ];
            }

            if (str_contains(strtolower($message), 'unexpected model name format') || str_contains(strtolower($message), 'model name format')) {
                return [
                    'success' => false,
                    'message' => "Tên Model Gemini '{$model}' không đúng định dạng của Google AI. Vui lòng nhập mã model chính xác (Khuyên dùng: gemini-2.5-flash hoặc gemini-2.5-pro, viết thường, không có khoảng trắng hoặc tiền tố 'models/').",
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

        if ($statusCode === 404 || $status === 'NOT_FOUND' || str_contains(strtolower($message), 'not found') || str_contains(strtolower($message), 'is not found for api version')) {
            return [
                'success' => false,
                'message' => "Model Not Found: Model '{$model}' không tồn tại hoặc đã ngừng hỗ trợ trên Google Gemini API v1beta. Khuyên dùng: 'gemini-2.5-flash' hoặc 'gemini-2.5-pro'.",
                'code' => 'MODEL_NOT_FOUND',
                'status_code' => 404,
                'model_available' => false,
            ];
        }

        if ($statusCode === 429 || $status === 'RESOURCE_EXHAUSTED') {
            return [
                'success' => false,
                'message' => 'Quota Exceeded: Đã chạm giới hạn gọi API Google Gemini miễn phí (RPM/RPD). Vui lòng đợi 1 phút hoặc chuyển sang model gemini-2.5-flash.',
                'code' => 'INSUFFICIENT_QUOTA',
                'status_code' => 429,
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
