<?php

namespace App\Services\AI\Providers;

use App\Models\AiProvider;
use App\Services\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Throwable;

class GeminiProvider implements AIProviderInterface
{
    protected string $defaultBaseUrl = 'https://generativelanguage.googleapis.com/v1beta';

    public function testConnection(AiProvider $provider): array
    {
        $apiKey = $provider->getDecryptedApiKey();
        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'API Key Gemini chưa được cấu hình.',
                'code' => 'MISSING_API_KEY',
                'status_code' => 400,
                'model_available' => false,
            ];
        }

        $model = $provider->model ?: 'gemini-1.5-flash';
        $baseUrl = rtrim($provider->base_url ?: $this->defaultBaseUrl, '/');
        $timeout = $provider->timeout ?: 30;

        $url = "{$baseUrl}/models/{$model}:generateContent?key={$apiKey}";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout($timeout)->post($url, [
                'contents' => [
                    ['parts' => [['text' => 'ping']]]
                ],
                'generationConfig' => [
                    'maxOutputTokens' => 5,
                ]
            ]);

            $statusCode = $response->status();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => "Kết nối Google Gemini thành công! Model '{$model}' phản hồi tốt.",
                    'code' => 'CONNECTED',
                    'status_code' => $statusCode,
                    'model_available' => true,
                ];
            }

            return $this->classifyError($statusCode, $response->json(), $model);
        } catch (ConnectionException $e) {
            return [
                'success' => false,
                'message' => 'Connection timeout: Không thể kết nối tới máy chủ Google Gemini trong thời gian chờ.',
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
        $apiKey = $provider->getDecryptedApiKey();
        if (empty($apiKey)) {
            return [
                'success' => false,
                'raw_content' => null,
                'data' => null,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0,
                'error' => 'API Key Gemini is missing or empty.',
                'error_code' => 'MISSING_API_KEY',
            ];
        }

        $model = $modelOverride ?: ($provider->model ?: 'gemini-1.5-flash');
        $baseUrl = rtrim($provider->base_url ?: $this->defaultBaseUrl, '/');
        $temperature = (float)($provider->temperature ?? 0.7);
        $maxTokens = (int)($provider->max_tokens ?? 4000);
        $timeout = (int)($provider->timeout ?? 90);

        $url = "{$baseUrl}/models/{$model}:generateContent?key={$apiKey}";

        try {
            $fullPrompt = "SYSTEM INSTRUCTIONS:\n{$systemPrompt}\n\nUSER REQUEST:\n{$userPrompt}";

            $payload = [
                'contents' => [
                    ['parts' => [['text' => $fullPrompt]]]
                ],
                'generationConfig' => [
                    'temperature' => $temperature,
                    'maxOutputTokens' => $maxTokens,
                    'responseMimeType' => 'application/json',
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout($timeout)->post($url, $payload);

            if ($response->successful()) {
                $json = $response->json();
                $rawContent = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $usage = $json['usageMetadata'] ?? [];

                // Clean markdown codeblocks if returned
                $cleanedContent = trim($rawContent);
                if (str_starts_with($cleanedContent, '```json')) {
                    $cleanedContent = preg_replace('/^```json\s*/', '', $cleanedContent);
                    $cleanedContent = preg_replace('/\s*```$/', '', $cleanedContent);
                } elseif (str_starts_with($cleanedContent, '```')) {
                    $cleanedContent = preg_replace('/^```\s*/', '', $cleanedContent);
                    $cleanedContent = preg_replace('/\s*```$/', '', $cleanedContent);
                }

                $decodedData = json_decode($cleanedContent, true);

                return [
                    'success' => true,
                    'raw_content' => $rawContent,
                    'data' => is_array($decodedData) ? $decodedData : null,
                    'prompt_tokens' => (int)($usage['promptTokenCount'] ?? 0),
                    'completion_tokens' => (int)($usage['candidatesTokenCount'] ?? 0),
                    'total_tokens' => (int)($usage['totalTokenCount'] ?? 0),
                    'error' => null,
                    'error_code' => null,
                ];
            }

            $classification = $this->classifyError($response->status(), $response->json(), $model);
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
                'error' => 'Connection timeout while generating content via Gemini.',
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

    protected function classifyError(int $statusCode, ?array $bodyJson, string $model): array
    {
        $message = $bodyJson['error']['message'] ?? '';
        $status = $bodyJson['error']['status'] ?? '';

        if ($statusCode === 400 || $status === 'INVALID_ARGUMENT') {
            if (str_contains(strtolower($message), 'api_key_invalid') || str_contains(strtolower($message), 'api key not valid')) {
                return [
                    'success' => false,
                    'message' => 'Invalid API Key: Khóa API Google Gemini không hợp lệ.',
                    'code' => 'INVALID_API_KEY',
                    'status_code' => 400,
                    'model_available' => false,
                ];
            }
        }

        if ($statusCode === 403 || $status === 'PERMISSION_DENIED') {
            return [
                'success' => false,
                'message' => 'Permission Denied: Khóa API không có quyền truy cập hoặc bị giới hạn địa lý.',
                'code' => 'PERMISSION_DENIED',
                'status_code' => 403,
                'model_available' => false,
            ];
        }

        if ($statusCode === 404 || $status === 'NOT_FOUND') {
            return [
                'success' => false,
                'message' => "Model Not Found: Model '{$model}' không tồn tại trong Google AI API.",
                'code' => 'MODEL_NOT_FOUND',
                'status_code' => 404,
                'model_available' => false,
            ];
        }

        if ($statusCode === 429 || $status === 'RESOURCE_EXHAUSTED') {
            return [
                'success' => false,
                'message' => 'Quota Exceeded: Đã hết hạn mức gọi API Google Gemini miễn phí hoặc vượt quá giới hạn RPM.',
                'code' => 'INSUFFICIENT_QUOTA',
                'status_code' => 429,
                'model_available' => true,
            ];
        }

        if ($statusCode >= 500) {
            return [
                'success' => false,
                'message' => 'Provider Unavailable: Máy chủ Google AI đang bảo trì hoặc quá tải.',
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
