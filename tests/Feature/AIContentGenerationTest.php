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
}
