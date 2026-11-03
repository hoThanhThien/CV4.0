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
    protected string $defaultBaseUrl = 'https://api.openai.com/v1';

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

        $baseUrl = rtrim($provider->base_url ?: $this->defaultBaseUrl, '/');
        $model = trim($provider->model ?: 'gpt-4o-mini');
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

        $baseUrl = rtrim($provider->base_url ?: $this->defaultBaseUrl, '/');
        $model = trim($modelOverride ?: ($provider->model ?: 'gpt-4o-mini'));
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
