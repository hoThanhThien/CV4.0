<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AiProvider;
use App\Models\User;
use App\Services\AI\AIProviderFactory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

class AIProviderTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_api_key_is_encrypted_in_database()
    {
        $rawKey = 'sk-proj-test1234567890abcdefghijklmnopqrstuvwxyz';

        $provider = AiProvider::create([
            'name' => 'Test Encryption Provider',
            'provider_type' => 'openai',
            'api_key' => $rawKey,
            'model' => 'gpt-4o-mini',
            'temperature' => 0.7,
            'max_tokens' => 4000,
            'timeout' => 60,
            'is_active' => true,
        ]);

        // Verify stored in DB is NOT plain text
        $rawDbValue = \Illuminate\Support\Facades\DB::table('ai_providers')->where('id', $provider->id)->value('api_key');
        $this->assertNotEquals($rawKey, $rawDbValue);

        // Verify decryption retrieves original key
        $this->assertEquals($rawKey, $provider->getDecryptedApiKey());

        // Verify masked key hides sensitive parts
        $masked = $provider->masked_api_key;
        $this->assertStringStartsWith('sk-p', $masked);
        $this->assertStringEndsWith('wxyz', $masked);
        $this->assertStringContainsString('••••••••', $masked);
    }

    public function test_openai_connection_success_with_mock()
    {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'id' => 'chatcmpl-123',
                'choices' => [
                    ['message' => ['content' => 'Hello!']]
                ]
            ], 200),
        ]);

        $provider = AiProvider::create([
            'name' => 'Test OpenAI Provider',
            'provider_type' => 'openai',
            'api_key' => 'sk-valid-key',
            'model' => 'gpt-4o-mini',
        ]);

        $adapter = AIProviderFactory::make($provider);
        $result = $adapter->testConnection($provider);

        $this->assertTrue($result['success']);
        $this->assertEquals('CONNECTED', $result['code']);
    }

    public function test_openai_connection_invalid_key_classified_properly()
    {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'error' => [
                    'message' => 'Incorrect API key provided.',
                    'type' => 'invalid_request_error',
                    'code' => 'invalid_api_key'
                ]
            ], 401),
        ]);

        $provider = AiProvider::create([
            'name' => 'Test OpenAI Invalid',
            'provider_type' => 'openai',
            'api_key' => 'sk-invalid-key',
            'model' => 'gpt-4o-mini',
        ]);

        $adapter = AIProviderFactory::make($provider);
        $result = $adapter->testConnection($provider);

        $this->assertFalse($result['success']);
        $this->assertEquals('INVALID_API_KEY', $result['code']);
        $this->assertStringContainsString('Invalid API Key', $result['message']);
    }

    public function test_gemini_connection_success_with_mock()
    {
        Http::fake([
            'generativelanguage.googleapis.com/v1beta/models*' => Http::response([
                'models' => [
                    [
                        'name' => 'models/gemini-live-flash',
                        'displayName' => 'Gemini Live Flash',
                        'outputTokenLimit' => 65536,
                        'supportedGenerationMethods' => ['generateContent'],
                    ],
                ],
            ], 200),
            'generativelanguage.googleapis.com/v1beta/interactions' => Http::response([
                'id' => 'v1_interaction_test',
                'object' => 'interaction',
                'status' => 'completed',
                'steps' => [
                    ['type' => 'thought', 'signature' => 'sig'],
                    ['type' => 'model_output', 'content' => [['type' => 'text', 'text' => 'OK']]],
                ],
            ], 200),
        ]);

        $provider = AiProvider::create([
            'name' => 'Test Gemini Provider',
            'provider_type' => 'gemini',
            'api_key' => 'AIzaSyFakeKey123',
            'model' => 'gemini',
        ]);

        $adapter = AIProviderFactory::make($provider);
        $result = $adapter->testConnection($provider);

        $this->assertTrue($result['success']);
        $this->assertEquals('CONNECTED', $result['code']);
        $this->assertEquals('gemini-live-flash', $provider->fresh()->model);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://generativelanguage.googleapis.com/v1beta/interactions'
                && $request->hasHeader('x-goog-api-key', 'AIzaSyFakeKey123')
                && !str_contains($request->url(), 'key=')
                && ($request['model'] ?? null) === 'gemini-live-flash';
        });
    }

    public function test_gemini_model_name_normalization()
    {
        $gemini = new \App\Services\AI\Providers\GeminiProvider();

        // Strips prefix/slashes/spaces without hardcoding any model version replacement
        $this->assertEquals('gemini-custom-flash', $gemini->normalizeModel('gemini-custom-flash'));
        $this->assertEquals('gemini-custom-flash', $gemini->normalizeModel('models/gemini-custom-flash'));
        $this->assertEquals('gemini-custom-flash', $gemini->normalizeModel('models/models/gemini-custom-flash'));
        $this->assertEquals('gemini-next-pro', $gemini->normalizeModel(' Gemini Next Pro '));

        // Generic vendor labels normalize to empty string so dynamic API discovery resolves live model
        $this->assertEquals('', $gemini->normalizeModel('gemini'));
        $this->assertEquals('', $gemini->normalizeModel('Google Gemini'));
        $this->assertEquals('', $gemini->normalizeModel(''));
    }

    public function test_gemini_automatic_fallback_when_model_returns_404()
    {
        Http::fake([
            'generativelanguage.googleapis.com/v1beta/models?*' => Http::response([
                'models' => [
                    [
                        'name' => 'models/gemini-discovered-flash',
                        'displayName' => 'Gemini Discovered Flash',
                        'outputTokenLimit' => 32768,
                        'supportedGenerationMethods' => ['generateContent'],
                    ],
                ],
            ], 200),
            'generativelanguage.googleapis.com/v1beta/interactions' => Http::response([
                'error' => [
                    'code' => 'not_found',
                    'message' => 'Model not found on interactions endpoint',
                ],
            ], 404),
            'generativelanguage.googleapis.com/v1beta/models/gemini-experimental-old:generateContent' => Http::response([
                'error' => [
                    'code' => 404,
                    'message' => 'models/gemini-experimental-old is not found for API version v1beta',
                    'status' => 'NOT_FOUND',
                ],
            ], 404),
            'generativelanguage.googleapis.com/v1beta/models/gemini-discovered-flash:generateContent' => Http::response([
                'candidates' => [
                    ['content' => ['parts' => [['text' => 'OK']]]],
                ],
            ], 200),
        ]);

        $provider = AiProvider::create([
            'name' => 'Gemini Fallback Test',
            'provider_type' => 'gemini',
            'api_key' => 'AIzaSyFakeKeyFallback',
            'model' => 'gemini-experimental-old',
        ]);

        $adapter = AIProviderFactory::make($provider);
        $result = $adapter->testConnection($provider);

        $this->assertTrue($result['success']);
        $this->assertEquals('CONNECTED', $result['code']);
        $this->assertEquals('gemini-discovered-flash', $provider->fresh()->model);
    }

    public function test_gemini_fallback_on_503_high_demand_and_vertex_express_fallback()
    {
        Http::fake(function ($request) {
            if (str_starts_with($request->url(), 'https://generativelanguage.googleapis.com/v1beta/models?')) {
                return Http::response([
                    'models' => [
                        [
                            'name' => 'models/gemini-primary-busy',
                            'supportedGenerationMethods' => ['generateContent'],
                        ],
                        [
                            'name' => 'models/gemini-dynamic-backup-flash',
                            'supportedGenerationMethods' => ['generateContent'],
                        ],
                    ],
                ], 200);
            }

            if ($request->url() === 'https://generativelanguage.googleapis.com/v1beta/interactions') {
                if (($request['model'] ?? '') === 'gemini-primary-busy') {
                    return Http::response([
                        'error' => [
                            'code' => 'service_unavailable',
                            'message' => 'Model is currently experiencing high demand.',
                        ],
                    ], 503);
                }

                if (($request['model'] ?? '') === 'gemini-dynamic-backup-flash') {
                    return Http::response([
                        'object' => 'interaction',
                        'status' => 'completed',
                        'steps' => [
                            ['type' => 'model_output', 'content' => [['type' => 'text', 'text' => 'OK']]],
                        ],
                    ], 200);
                }
            }

            return Http::response([], 404);
        });

        $provider = AiProvider::create([
            'name' => 'Gemini 503 Fallback Test',
            'provider_type' => 'gemini',
            'api_key' => 'AQ.FakeStudioKey123',
            'model' => 'gemini-primary-busy',
        ]);

        $adapter = AIProviderFactory::make($provider);
        $result = $adapter->testConnection($provider);

        $this->assertTrue($result['success']);
        // Temporary 503 failover preserves user's configured primary model in DB
        $this->assertEquals('gemini-primary-busy', $provider->fresh()->model);
    }

    public function test_gemini_error_classification_for_unexpected_model_name_format()
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'error' => [
                    'code' => 400,
                    'message' => "* GenerateContentRequest.model: unexpected model name format\n",
                    'status' => 'INVALID_ARGUMENT'
                ]
            ], 400),
        ]);

        $provider = AiProvider::create([
            'name' => 'Gehihi Provider',
            'provider_type' => 'gemini',
            'api_key' => 'AIzaSyValidKeyFormat',
            'model' => 'invalid-model-name',
        ]);

        $adapter = AIProviderFactory::make($provider);
        $result = $adapter->testConnection($provider);

        $this->assertFalse($result['success']);
        $this->assertEquals('INVALID_MODEL_FORMAT', $result['code']);
        $this->assertStringContainsString('Tên Model Gemini', $result['message']);
    }
}
