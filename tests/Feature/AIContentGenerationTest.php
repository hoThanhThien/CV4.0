<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AiProvider;
use App\Models\AiContentConfig;
use App\Models\BlogPost;
use App\Services\AI\BlogContentGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class AIContentGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected AiProvider $provider;
    protected AiContentConfig $config;

    protected function setUp(): void
    {
        parent::setUp();

        \Illuminate\Support\Facades\Storage::fake('public');

        $this->provider = AiProvider::create([
            'name' => 'OpenAI Test',
            'provider_type' => 'openai',
            'api_key' => 'sk-test-key-1234567890',
            'model' => 'gpt-4o-mini',
            'is_active' => true,
            'is_default' => true,
        ]);

        $this->config = AiContentConfig::create([
            'name' => 'Laravel Architecture Config',
            'ai_provider_id' => $this->provider->id,
            'topic_mode' => 'admin_topic',
            'main_topic' => 'Xây dựng Clean Architecture trong Laravel',
            'keywords' => 'Laravel, Clean Architecture, Repository Pattern',
            'language' => 'vi',
            'writing_tone' => 'professional',
            'target_audience' => 'Software Engineers',
            'article_length' => 'medium',
            'category' => 'Technology',
            'tags' => ['Laravel', 'Architecture'],
            'include_faq' => true,
            'is_active' => true,
        ]);
    }

    public function test_generate_post_success_with_json_mock()
    {
        $mockJson = [
            'title' => 'Xây dựng Clean Architecture trong Laravel: Hướng dẫn thực chiến',
            'slug' => 'xay-dung-clean-architecture-trong-laravel',
            'excerpt' => 'Tìm hiểu các nguyên lý cốt lõi của Clean Architecture và cách áp dụng vào dự án Laravel quy mô lớn.',
            'content' => '<h2>1. Clean Architecture là gì?</h2><p>Clean Architecture giúp tách biệt business logic khỏi framework.</p><script>alert("hack");</script><iframe src="evil.com"></iframe>',
            'meta_title' => 'Clean Architecture trong Laravel | Hồ Thành Thiện',
            'meta_description' => 'Hướng dẫn kiến trúc Clean Architecture chuẩn cho Laravel Developers.',
            'keywords' => ['Laravel', 'Clean Architecture', 'Design Pattern'],
            'category' => 'Technology',
            'tags' => ['Laravel', 'Architecture'],
            'faq' => [
                ['question' => 'Khi nào nên dùng Clean Architecture?', 'answer' => 'Khi dự án có nghiệp vụ phức tạp và cần khả năng test cao.']
            ],
            'sources' => [
                ['title' => 'Martin Fowler Clean Architecture', 'url' => 'https://martinfowler.com']
            ],
            'cover_image_prompt' => 'A modern 3D abstract visualization of modular clean architecture software design.'
        ];

        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    ['message' => ['content' => json_encode($mockJson)]]
                ],
                'usage' => [
                    'prompt_tokens' => 350,
                    'completion_tokens' => 850,
                    'total_tokens' => 1200,
                ]
            ], 200),
        ]);

        $generator = new BlogContentGenerator();
        $result = $generator->generatePost($this->config, 'manual_approval');

        $this->assertTrue($result['success']);
        $this->assertNotNull($result['post']);

        $post = $result['post'];
        $this->assertEquals($mockJson['title'], $post->title);
        $this->assertEquals($mockJson['slug'], $post->slug);
        $this->assertEquals(BlogPost::STATUS_PENDING_REVIEW, $post->status);
        $this->assertFalse($post->published);
        $this->assertTrue($post->ai_generated);

        // Verify HTML sanitization: <script> and <iframe> must be stripped!
        $this->assertStringNotContainsString('<script>', $post->content);
        $this->assertStringNotContainsString('alert(', $post->content);
        $this->assertStringNotContainsString('<iframe>', $post->content);
        $this->assertStringContainsString('<h2>1. Clean Architecture là gì?</h2>', $post->content);

        // Verify FAQ and Sources
        $this->assertCount(1, $post->faq);
        $this->assertEquals('Khi nào nên dùng Clean Architecture?', $post->faq[0]['question']);
    }

    public function test_deduplication_prevents_duplicate_post_creation()
    {
        BlogPost::create([
            'title' => 'Xây dựng Clean Architecture trong Laravel',
            'slug' => 'xay-dung-clean-architecture-trong-laravel',
            'content' => '<p>Existing post content</p>',
            'published' => true,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $mockJson = [
            'title' => 'Xây dựng Clean Architecture trong Laravel',
            'slug' => 'xay-dung-clean-architecture-trong-laravel-duplicate',
            'content' => '<p>Duplicate content attempt</p>',
        ];

        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    ['message' => ['content' => json_encode($mockJson)]]
                ],
            ], 200),
        ]);

        $generator = new BlogContentGenerator();
        $result = $generator->generatePost($this->config, 'manual_approval');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Trùng lặp nội dung', $result['error']);
    }

    public function test_publishing_modes()
    {
        $mockJson = [
            'title' => 'Test Publishing Modes Post',
            'slug' => 'test-publishing-modes-post',
            'content' => '<p>Content</p>',
        ];

        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    ['message' => ['content' => json_encode($mockJson)]]
                ],
            ], 200),
        ]);

        $generator = new BlogContentGenerator();

        // 1. Auto publish mode
        $resPub = $generator->generatePost($this->config, 'auto_publish');
        $this->assertTrue($resPub['success']);
        $this->assertEquals(BlogPost::STATUS_PUBLISHED, $resPub['post']->status);
        $this->assertTrue($resPub['post']->published);
        $this->assertNotNull($resPub['post']->published_at);
    }

    public function test_gemini_thinking_parts_and_raw_newlines_in_json_are_parsed_cleanly()
    {
        $geminiProvider = AiProvider::create([
            'name' => 'Gemini 2.5 Test',
            'provider_type' => 'gemini',
            'api_key' => 'AIzaSyFakeGeminiKey123456',
            'model' => 'gemini-2.5-flash',
            'is_active' => true,
        ]);

        $this->config->update(['ai_provider_id' => $geminiProvider->id]);

        // Simulate Gemini 2.5 response containing a thought part AND markdown-fenced JSON with raw newlines inside string
        $rawMalformedJson = "```json\n{\n  \"title\": \"Tối ưu hiệu năng Laravel với Gemini 2.5\",\n  \"slug\": \"toi-uu-hieu-nang-laravel-gemini\",\n  \"excerpt\": \"Hướng dẫn chi tiết tối ưu Laravel.\",\n  \"content\": \"<h2>Giới thiệu</h2>\n<pre><code>php artisan optimize</code></pre>\",\n}\n```";

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'finishReason' => 'STOP',
                        'content' => [
                            'parts' => [
                                ['thought' => true, 'text' => 'Thinking about Laravel performance...'],
                                ['text' => $rawMalformedJson],
                            ],
                        ],
                    ],
                ],
                'usageMetadata' => [
                    'promptTokenCount' => 400,
                    'candidatesTokenCount' => 900,
                    'totalTokenCount' => 1300,
                ],
            ], 200),
        ]);

        $generator = new BlogContentGenerator();
        $result = $generator->generatePost($this->config->fresh(), 'manual_approval');

        $this->assertTrue($result['success']);
        $this->assertEquals('Tối ưu hiệu năng Laravel với Gemini 2.5', $result['post']->title);
        $this->assertStringContainsString('php artisan optimize', $result['post']->content);
    }

    public function test_gemini_interactions_api_structured_output_and_header_auth()
    {
        $geminiProvider = AiProvider::create([
            'name' => 'Gemini 3.5 Interactions Test',
            'provider_type' => 'gemini',
            'api_key' => 'AQ.FakeStudioInteractionsKey999',
            'model' => 'gemini-3.5-flash',
            'is_active' => true,
        ]);

        $this->config->update(['ai_provider_id' => $geminiProvider->id]);

        $structuredArticle = [
            'title' => 'Kiến trúc Microservices hiện đại với Gemini Interactions API',
            'slug' => 'kien-truc-microservices-gemini-interactions-api',
            'excerpt' => 'Phân tích chuyên sâu cách tích hợp Gemini Interactions API chuẩn JSON Schema vào hệ thống Laravel.',
            'content' => '<h2>1. Tổng quan Interactions API</h2><p>Chuẩn kết nối mới sử dụng x-goog-api-key và JSON Schema.</p>',
            'meta_title' => 'Gemini Interactions API với Laravel | Hồ Thành Thiện',
            'meta_description' => 'Hướng dẫn tích hợp chuẩn Google AI Studio Interactions API vào Laravel.',
            'keywords' => ['Gemini', 'Interactions API', 'Laravel'],
            'category' => 'Technology',
            'tags' => ['Gemini', 'Laravel'],
            'faq' => [
                ['question' => 'Interactions API dùng header nào?', 'answer' => 'Sử dụng header x-goog-api-key.'],
            ],
            'sources' => [
                ['title' => 'Google AI Studio API Reference', 'url' => 'https://aistudio.google.com/api-reference'],
            ],
            'cover_image_prompt' => 'Modern cloud API architecture diagram in 3D isometric style',
        ];

        Http::fake([
            'generativelanguage.googleapis.com/v1beta/interactions' => Http::response([
                'id' => 'v1_interaction_blog_001',
                'object' => 'interaction',
                'model' => 'gemini-3.5-flash',
                'status' => 'completed',
                'usage' => [
                    'total_input_tokens' => 420,
                    'total_output_tokens' => 980,
                    'total_tokens' => 1400,
                ],
                'steps' => [
                    ['type' => 'thought', 'signature' => 'sig_abc'],
                    [
                        'type' => 'model_output',
                        'content' => [
                            ['type' => 'text', 'text' => json_encode($structuredArticle, JSON_UNESCAPED_UNICODE)],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $runLog = \App\Models\BlogAutomationRun::create([
            'provider_used' => 'Google Gemini',
            'model_used' => 'gemini-3.5-flash',
            'trigger_type' => 'manual',
            'started_at' => now(),
            'status' => 'running',
        ]);

        $generator = new BlogContentGenerator();
        $result = $generator->generatePost($this->config->fresh(), 'manual_approval', null, $runLog);

        $this->assertTrue($result['success']);
        $this->assertNotNull($result['post']);
        $this->assertEquals('Kiến trúc Microservices hiện đại với Gemini Interactions API', $result['post']->title);
        $this->assertEquals(420, $runLog->fresh()->prompt_tokens);
        $this->assertEquals(980, $runLog->fresh()->completion_tokens);
        $this->assertEquals(1400, $runLog->fresh()->total_tokens);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://generativelanguage.googleapis.com/v1beta/interactions'
                && $request->hasHeader('x-goog-api-key', 'AQ.FakeStudioInteractionsKey999')
                && !str_contains($request->url(), 'key=')
                && ($request['response_mime_type'] ?? null) === 'application/json'
                && ($request['response_format']['type'] ?? null) === 'object';
        });
    }

    public function test_json_cleaner_repairs_php_namespaces_inner_code_fences_and_unescaped_html_quotes()
    {
        // Reproduces exact failure mode on long PHP/Laravel articles (finishReason: STOP):
        // - Unescaped PHP backslashes (\A, \S, \U in App\Services\Users, \$service, \d+)
        // - Inner ```php code fences inside content (must NOT truncate outer JSON!)
        // - Unescaped HTML attribute double quotes (<h2 class="section">)
        $malformedStopJson = <<<'RAW'
{
  "title": "Kiến trúc Laravel thực chiến với AI Service Layer",
  "slug": "kien-truc-laravel-thuc-chien-ai-service",
  "excerpt": "Hướng dẫn tổ chức Service Layer trong Laravel.",
  "content": "<h2 class="intro">1. Cấu trúc Namespace</h2><p>Ví dụ:</p><pre><code>```php
namespace App\Services\Users;
use Illuminate\Support\Facades\Http;
$pattern = '/\d+/';
```</code></pre>",
  "meta_title": "Kiến trúc Laravel AI",
  "meta_description": "Hướng dẫn thực chiến Laravel Service Layer.",
  "keywords": ["Laravel", "Architecture"],
  "category": "Technology",
  "tags": ["Laravel", "PHP"],
}
RAW;

        $decoded = \App\Services\AI\Support\JsonCleaner::decode($malformedStopJson);

        $this->assertIsArray($decoded);
        $this->assertEquals('Kiến trúc Laravel thực chiến với AI Service Layer', $decoded['title']);
        $this->assertStringContainsString('App\Services\Users', $decoded['content']);
        $this->assertStringContainsString('Illuminate\Support\Facades\Http', $decoded['content']);
    }
}
