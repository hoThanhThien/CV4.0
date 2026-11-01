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
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    ['content' => ['parts' => [['text' => 'pong']]]]
                ]
            ], 200),
        ]);

        $provider = AiProvider::create([
            'name' => 'Test Gemini Provider',
            'provider_type' => 'gemini',
            'api_key' => 'AIzaSyFakeKey123',
            'model' => 'gemini-1.5-flash',
        ]);

        $adapter = AIProviderFactory::make($provider);
        $result = $adapter->testConnection($provider);

        $this->assertTrue($result['success']);
        $this->assertEquals('CONNECTED', $result['code']);
    }

    public function test_gemini_model_name_normalization()
    {
        $gemini = new \App\Services\AI\Providers\GeminiProvider();

        $this->assertEquals('gemini-1.5-flash', $gemini->normalizeModel('gemini-1.5-flash'));
        $this->assertEquals('gemini-1.5-flash', $gemini->normalizeModel('models/gemini-1.5-flash'));
        $this->assertEquals('gemini-1.5-flash', $gemini->normalizeModel('models/models/gemini-1.5-flash'));
        $this->assertEquals('gemini-1.5-flash', $gemini->normalizeModel('Gemini 1.5 Flash'));
        $this->assertEquals('gemini-1.5-flash', $gemini->normalizeModel(' gemini-1.5-flash '));
        $this->assertEquals('gemini-1.5-pro', $gemini->normalizeModel('models/gemini-1.5-pro'));
        $this->assertEquals('gemini-1.5-pro', $gemini->normalizeModel('GEMINI 1.5 PRO'));
        $this->assertEquals('gemini-1.5-flash', $gemini->normalizeModel('gemini'));
        $this->assertEquals('gemini-1.5-flash', $gemini->normalizeModel('Google Gemini'));
        $this->assertEquals('gemini-1.5-flash', $gemini->normalizeModel(''));
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
