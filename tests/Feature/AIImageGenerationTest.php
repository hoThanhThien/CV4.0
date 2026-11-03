<?php

namespace Tests\Feature;

use App\Models\AiContentConfig;
use App\Models\AiProvider;
use App\Models\BlogPost;
use App\Models\User;
use App\Services\AI\BlogContentGenerator;
use App\Services\AI\BlogImageGenerator;
use App\Services\AI\Support\VisualAssetRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AIImageGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_gemini_dynamic_image_model_discovery_and_interactions_image_generation()
    {
        $renderer = new VisualAssetRenderer();
        $samplePng = $renderer->renderAiDesignedAssets([
            'title' => 'Sample Raster Source',
            'category' => 'AI',
        ])['og']['bytes'];

        Http::fake(function ($request) use ($samplePng) {
            if (str_starts_with($request->url(), 'https://generativelanguage.googleapis.com/v1beta/models')) {
                return Http::response([
                    'models' => [
                        [
                            'name' => 'models/gemini-dynamic-text-flash',
                            'displayName' => 'Gemini Dynamic Text Flash',
                            'supportedGenerationMethods' => ['generateContent'],
                        ],
                        [
                            'name' => 'models/gemini-dynamic-banana-image',
                            'displayName' => 'Dynamic Nano Banana Image',
                            'supportedGenerationMethods' => ['generateContent'],
                        ],
                    ],
                ], 200);
            }

            if ($request->url() === 'https://generativelanguage.googleapis.com/v1beta/interactions') {
                return Http::response([
                    'id' => 'inter_img_01',
                    'status' => 'completed',
                    'steps' => [
                        [
                            'type' => 'model_output',
                            'content' => [
                                [
                                    'type' => 'image',
                                    'mime_type' => 'image/png',
                                    'data' => base64_encode($samplePng),
                                ],
                            ],
                        ],
                    ],
                ], 200);
            }

            return Http::response([], 404);
        });

        $provider = AiProvider::create([
            'name' => 'Gemini Dynamic Image Provider',
            'provider_type' => 'gemini',
            'api_key' => 'AQ.TestDynamicImageKey123',
            'model' => 'gemini-dynamic-text-flash',
            'is_active' => true,
            'is_default' => true,
        ]);

        $post = BlogPost::create([
            'title' => 'Tối ưu kiến trúc AI Agentic với Laravel',
            'slug' => 'toi-uu-kien-truc-ai-agentic-voi-laravel',
            'content' => '<p>Nội dung chuyên sâu về AI Agentic.</p>',
            'category' => 'AI Engineering',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now(),
            'ai_generated' => true,
            'ai_provider_id' => $provider->id,
        ]);

        $generator = new BlogImageGenerator();
        $result = $generator->generateForPost($post, $provider, null, '3d_isometric');

        $this->assertTrue($result['success']);
        $this->assertEquals('native_ai', $result['mode']);
        $this->assertEquals('gemini-dynamic-banana-image', $result['model_used']);

        $post->refresh();
        $this->assertNotEmpty($post->image);
        $this->assertNotEmpty($post->og_image);
        Storage::disk('public')->assertExists($post->image);
        Storage::disk('public')->assertExists($post->og_image);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://generativelanguage.googleapis.com/v1beta/interactions'
                && $request->hasHeader('x-goog-api-key', 'AQ.TestDynamicImageKey123')
                && ($request['model'] ?? null) === 'gemini-dynamic-banana-image'
                && ($request['response_format']['type'] ?? null) === 'image'
                && ($request['response_format']['aspect_ratio'] ?? null) === '16:9';
        });
    }

    public function test_free_tier_zero_quota_gracefully_designs_thumbnail_and_valid_1200x630_og_png()
    {
        Http::fake(function ($request) {
            if (str_starts_with($request->url(), 'https://generativelanguage.googleapis.com/v1beta/models')) {
                return Http::response([
                    'models' => [
                        [
                            'name' => 'models/gemini-free-text-flash',
                            'displayName' => 'Gemini Free Text Flash',
                            'supportedGenerationMethods' => ['generateContent'],
                        ],
                        [
                            'name' => 'models/gemini-paid-image-model',
                            'displayName' => 'Paid Image Model',
                            'supportedGenerationMethods' => ['generateContent'],
                        ],
                    ],
                ], 200);
            }

            if ($request->url() === 'https://generativelanguage.googleapis.com/v1beta/interactions') {
                // Paid image model returns Free Tier limit: 0
                if (($request['response_format']['type'] ?? '') === 'image') {
                    return Http::response([
                        'error' => [
                            'code' => 'too_many_requests',
                            'message' => 'Rate limit exceeded for model gemini-paid-image-model (limit: 0 input tokens per minute on Free Tier).',
                        ],
                    ], 429);
                }

                // Text LLM returns bespoke visual design JSON (with malicious <script> to verify sanitizer)
                return Http::response([
                    'status' => 'completed',
                    'steps' => [
                        [
                            'type' => 'model_output',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => json_encode([
                                        'bg_from' => '#0b1120',
                                        'bg_to' => '#1e1b4b',
                                        'accent_primary' => '#6366f1',
                                        'accent_secondary' => '#06b6d4',
                                        'badge' => 'CLOUD & AI',
                                        'headline' => 'Kiến trúc Hệ thống Phân tán',
                                        'subtitle' => 'High Availability Architecture',
                                        'motif' => 'microservices',
                                        'svg_elements' => '<circle cx="210" cy="210" r="64" fill="#6366f1" fill-opacity="0.35"/><script>alert("xss")</script>',
                                    ], JSON_UNESCAPED_UNICODE),
                                ],
                            ],
                        ],
                    ],
                ], 200);
            }

            return Http::response([], 404);
        });

        $provider = AiProvider::create([
            'name' => 'Gemini Free Tier Provider',
            'provider_type' => 'gemini',
            'api_key' => 'AQ.FreeTierKey123',
            'model' => 'gemini-free-text-flash',
            'is_active' => true,
            'is_default' => true,
        ]);

        $post = BlogPost::create([
            'title' => 'Kiến trúc Hệ thống Phân tán trong Laravel',
            'slug' => 'kien-truc-he-thong-phan-tan-trong-laravel',
            'content' => '<p>Phân tích chi tiết hệ thống phân tán.</p>',
            'category' => 'Cloud & AI',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now(),
            'ai_generated' => true,
            'ai_provider_id' => $provider->id,
        ]);

        $generator = new BlogImageGenerator();
        $result = $generator->generateForPost($post, $provider);

        $this->assertTrue($result['success']);
        $this->assertEquals('ai_studio_design', $result['mode']);

        $post->refresh();
        Storage::disk('public')->assertExists($post->image);
        Storage::disk('public')->assertExists($post->og_image);

        // Verify SVG thumbnail is clean and XSS-free
        $svgContent = Storage::disk('public')->get($post->image);
        $this->assertStringContainsString('<svg', $svgContent);
        $this->assertStringContainsString('Kiến trúc Hệ thống', $svgContent);
        $this->assertStringContainsString('Laravel', $svgContent);
        $this->assertStringNotContainsString('<script', $svgContent);
        $this->assertStringNotContainsString('alert(', $svgContent);

        // Verify OG image is a real 1200x630 raster PNG
        $ogBytes = Storage::disk('public')->get($post->og_image);
        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $ogBytes);
        $imgInfo = getimagesizefromstring($ogBytes);
        $this->assertIsArray($imgInfo);
        $this->assertEquals(1200, $imgInfo[0]);
        $this->assertEquals(630, $imgInfo[1]);
        $this->assertEquals(IMAGETYPE_PNG, $imgInfo[2]);
    }

    public function test_auto_generate_image_during_blog_post_creation_renders_public_og_meta()
    {
        $provider = AiProvider::create([
            'name' => 'OpenAI Auto Image Test',
            'provider_type' => 'openai',
            'api_key' => 'sk-test-auto-image',
            'model' => 'gpt-4o-mini',
            'is_active' => true,
            'is_default' => true,
        ]);

        $config = AiContentConfig::create([
            'name' => 'Auto Image Config',
            'ai_provider_id' => $provider->id,
            'topic_mode' => 'admin_topic',
            'main_topic' => 'Bảo mật API với Laravel Sanctum',
            'language' => 'vi',
            'writing_tone' => 'professional',
            'target_audience' => 'Developers',
            'article_length' => 'short',
            'category' => 'Security',
            'auto_generate_image' => true,
            'image_style' => 'modern_tech',
            'is_active' => true,
        ]);

        Http::fake([
            'api.openai.com/v1/models' => Http::response(['data' => [['id' => 'gpt-4o-mini']]], 200),
            'api.openai.com/v1/chat/completions' => Http::sequence()
                ->push([
                    'choices' => [[
                        'message' => [
                            'content' => json_encode([
                                'title' => 'Bảo mật API thực chiến với Laravel Sanctum',
                                'slug' => 'bao-mat-api-thuc-chien-voi-laravel-sanctum',
                                'excerpt' => 'Hướng dẫn chi tiết bảo mật REST API bằng Laravel Sanctum.',
                                'content' => '<h2>1. Giới thiệu Sanctum</h2><p>Bảo mật token hiệu quả.</p>',
                                'meta_title' => 'Bảo mật API Laravel Sanctum',
                                'meta_description' => 'Hướng dẫn bảo mật REST API Laravel.',
                                'keywords' => ['Laravel', 'Sanctum', 'Security'],
                                'category' => 'Security',
                                'tags' => ['Laravel', 'Security'],
                                'cover_image_prompt' => 'Cybersecurity shield protecting cloud API endpoints',
                            ], JSON_UNESCAPED_UNICODE),
                        ],
                    ]],
                ], 200)
                ->push([
                    'choices' => [[
                        'message' => [
                            'content' => json_encode([
                                'bg_from' => '#0f172a',
                                'bg_to' => '#1e1b4b',
                                'accent_primary' => '#10b981',
                                'accent_secondary' => '#06b6d4',
                                'badge' => 'SECURITY',
                                'headline' => 'Bảo mật API Laravel Sanctum',
                                'subtitle' => 'API Authentication',
                                'motif' => 'shield_security',
                                'svg_elements' => '<circle cx="210" cy="210" r="50" fill="#10b981" fill-opacity="0.3"/>',
                            ], JSON_UNESCAPED_UNICODE),
                        ],
                    ]],
                ], 200),
        ]);

        $contentGenerator = new BlogContentGenerator();
        $res = $contentGenerator->generatePost($config, 'auto_publish');

        $this->assertTrue($res['success']);
        $post = $res['post'];
        $this->assertNotNull($post->image);
        $this->assertNotNull($post->og_image);
        Storage::disk('public')->assertExists($post->image);
        Storage::disk('public')->assertExists($post->og_image);

        // Public blog detail page must expose the dedicated 1200x630 raster OG image URL in meta tags
        $page = $this->get(route('blog.show', $post->slug));
        $page->assertStatus(200);
        $page->assertSee($post->og_image_url, false);
        $page->assertSee($post->thumbnail_url, false);
    }

    public function test_admin_can_fetch_image_models_and_generate_images_for_existing_post()
    {
        $admin = User::factory()->create();

        $provider = AiProvider::create([
            'name' => 'Gemini Admin Image Test',
            'provider_type' => 'gemini',
            'api_key' => 'AQ.AdminTestKey999',
            'model' => 'gemini-text-flash',
            'is_active' => true,
            'is_default' => true,
        ]);

        $post = BlogPost::create([
            'title' => 'Bài viết kiểm thử tạo ảnh Admin',
            'slug' => 'bai-viet-kiem-thu-tao-anh-admin',
            'content' => '<p>Nội dung bài viết.</p>',
            'published' => false,
            'status' => BlogPost::STATUS_DRAFT,
            'ai_generated' => true,
            'ai_provider_id' => $provider->id,
        ]);

        Http::fake([
            'generativelanguage.googleapis.com/v1beta/models*' => Http::response([
                'models' => [
                    [
                        'name' => 'models/gemini-live-banana-image',
                        'displayName' => 'Gemini Live Banana Image',
                        'supportedGenerationMethods' => ['generateContent'],
                    ],
                ],
            ], 200),
            'generativelanguage.googleapis.com/v1beta/interactions' => Http::response([
                'error' => [
                    'code' => 'too_many_requests',
                    'message' => 'Rate limit exceeded (limit: 0 on Free Tier).',
                ],
            ], 429),
        ]);

        // 1. Fetch image models dynamically via Admin endpoint
        $modelsResp = $this->actingAs($admin)->postJson(route('admin.ai.providers.fetch-models'), [
            'provider_id' => $provider->id,
            'capability' => 'image',
        ]);
        $modelsResp->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('models.0.id', 'gemini-live-banana-image');

        // 2. Trigger AI Thumbnail + OG generation on the post
        $genResp = $this->actingAs($admin)->post(route('admin.ai.posts.generate-image', $post->id), [
            'image_style' => 'cyberpunk_neon',
            'custom_prompt' => 'Glowing futuristic microservices mesh',
        ]);
        $genResp->assertRedirect();

        $post->refresh();
        $this->assertNotNull($post->image);
        $this->assertNotNull($post->og_image);
        $this->assertEquals('Glowing futuristic microservices mesh', $post->cover_image_prompt);
        Storage::disk('public')->assertExists($post->image);
        Storage::disk('public')->assertExists($post->og_image);
    }
}
