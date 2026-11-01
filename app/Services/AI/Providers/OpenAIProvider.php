<?php

namespace App\Services\AI\Providers;

use App\Models\AiProvider;
use App\Services\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Throwable;

class OpenAIProvider implements AIProviderInterface
{
    protected string $defaultBaseUrl = 'https://api.openai.com/v1';

    public function testConnection(AiProvider $provider): array
    {
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
        $timeout = $provider->timeout ?: 30;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout($timeout)->post($baseUrl . '/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'user', 'content' => 'Say hello in one word.']
                ],
                'max_tokens' => 5,
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
        $temperature = (float)($provider->temperature ?? 0.7);
        $maxTokens = (int)($provider->max_tokens ?? 4000);
        $timeout = (int)($provider->timeout ?? 90);

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

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout($timeout)->post($baseUrl . '/chat/completions', $payload);

            if ($response->successful()) {
                $json = $response->json();
                $content = $json['choices'][0]['message']['content'] ?? '';
                $usage = $json['usage'] ?? [];

                $decodedData = json_decode($content, true);

                return [
                    'success' => true,
                    'raw_content' => $content,
                    'data' => is_array($decodedData) ? $decodedData : null,
                    'prompt_tokens' => (int)($usage['prompt_tokens'] ?? 0),
                    'completion_tokens' => (int)($usage['completion_tokens'] ?? 0),
                    'total_tokens' => (int)($usage['total_tokens'] ?? 0),
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
        $apiType = $bodyJson['error']['type'] ?? '';
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
