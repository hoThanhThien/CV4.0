<?php

namespace Tests\Feature;

use App\Models\AiProvider;
use App\Models\BlogPost;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PortfolioRagTest extends TestCase
{
    use RefreshDatabase;

    public function test_copilot_retrieves_live_db_chunks_using_hybrid_vector_and_calls_gemini_llm()
    {
        AiProvider::create([
            'name' => 'Google Gemini',
            'provider_type' => 'gemini',
            'api_key' => 'AIzaSyTestFakeKeyForRagTesting123456',
            'model' => 'gemini-3.6-flash',
            'is_active' => true,
            'is_default' => true,
        ]);

        $project = Project::create([
            'title' => 'Nexus Quantum Scheduler',
            'description' => "Backend Engineer | 08/2026 - 11/2026\nBuilt distributed Rust and Laravel webhook dispatcher with sub-20ms tail latency.",
            'featured' => true,
            'order' => 1,
        ]);

        BlogPost::create([
            'title' => 'Draft Secret Unreleased Architecture',
            'slug' => 'draft-secret-unreleased',
            'content' => '<p>Top secret draft note.</p>',
            'published' => false,
            'status' => BlogPost::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $capturedInputPrompt = '';

        Http::fake([
            'generativelanguage.googleapis.com/v1beta/models?*' => Http::response([
                'models' => [
                    [
                        'name' => 'models/gemini-embedding-001',
                        'supportedGenerationMethods' => ['embedContent'],
                    ],
                ],
            ], 200),

            'generativelanguage.googleapis.com/v1beta/models/gemini-embedding-001:batchEmbedContents' => function ($request) {
                $reqs = $request['requests'] ?? [];
                $embeddings = [];
                foreach ($reqs as $idx => $item) {
                    $txt = strtolower((string) ($item['content']['parts'][0]['text'] ?? ''));
                    if (str_contains($txt, 'nexus') || str_contains($txt, 'webhook') || $idx === 0) {
                        $embeddings[] = ['values' => [0.95, 0.25, 0.10]];
                    } else {
                        $embeddings[] = ['values' => [0.10, 0.85, 0.45]];
                    }
                }
                return Http::response(['embeddings' => $embeddings], 200);
            },

            'generativelanguage.googleapis.com/v1beta/interactions' => function ($request) use (&$capturedInputPrompt) {
                $capturedInputPrompt = (string) ($request['input'] ?? '');
                return Http::response([
                    'outputs' => [
                        [
                            'type' => 'message',
                            'content' => [
                                ['type' => 'text', 'text' => 'Ho Thanh Thien built **Nexus Quantum Scheduler** achieving sub-20ms tail latency [S1].'],
                            ],
                        ],
                    ],
                    'usage' => [
                        'total_input_tokens' => 320,
                        'total_output_tokens' => 28,
                        'total_tokens' => 348,
                    ],
                ], 200);
            },
        ]);

        $response = $this->postJson('/api/v1/copilot/ask', [
            'query' => 'Tell me about Nexus Quantum Scheduler latency',
            'locale' => 'en',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('mode', 'llm-rag')
            ->assertJsonPath('retrieval_method', 'hybrid-vector')
            ->assertJsonPath('model', 'gemini-3.6-flash')
            ->assertJsonPath('tokens.total', 348);

        $this->assertStringContainsString('Nexus Quantum Scheduler', $response->json('answer'));
        $this->assertStringContainsString('Nexus Quantum Scheduler', $capturedInputPrompt);
        $this->assertStringNotContainsString('Draft Secret Unreleased Architecture', $capturedInputPrompt);

        $urls = collect($response->json('sources'))->pluck('url')->all();
        $this->assertContains('/projects/' . $project->id, $urls);
    }

    public function test_copilot_falls_back_honestly_when_no_provider_or_quota_exceeded()
    {
        Project::create([
            'title' => 'Tourest',
            'description' => "Fullstack Developer | 01/2026 - 06/2026\nFAISS + BM25 hybrid RAG travel assistant in Python and FastAPI.",
            'featured' => true,
        ]);

        // Case A: No active AI provider configured
        $noProviderResp = $this->postJson('/api/v1/copilot/ask', [
            'query' => 'Tourest FAISS FastAPI',
            'locale' => 'vi',
        ]);

        $noProviderResp->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('mode', 'retrieval-fallback')
            ->assertJsonPath('retrieval_method', 'bm25-lexical')
            ->assertJsonPath('fallback_reason', 'NO_ACTIVE_PROVIDER')
            ->assertJsonPath('model', null);

        $this->assertStringContainsString('Tourest', $noProviderResp->json('answer'));

        // Case B: Active OpenAI provider returns 429 quota exceeded -> clean fallback without 500
        AiProvider::create([
            'name' => 'OpenAI Backup',
            'provider_type' => 'openai',
            'api_key' => 'sk-test-fake-key',
            'model' => 'gpt-4o-mini',
            'is_active' => true,
            'is_default' => true,
        ]);

        Http::fake([
            'api.openai.com/v1/embeddings' => Http::response(['error' => ['message' => 'Quota exceeded']], 429),
            'api.openai.com/v1/chat/completions' => Http::response([
                'error' => ['message' => 'You exceeded your current quota.', 'code' => 'insufficient_quota'],
            ], 429),
        ]);

        $quotaResp = $this->postJson('/api/v1/copilot/ask', [
            'query' => 'Tourest FastAPI',
            'locale' => 'en',
        ]);

        $quotaResp->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('mode', 'retrieval-fallback')
            ->assertJsonPath('fallback_reason', 'INSUFFICIENT_QUOTA');

        // Case C: Nonsense query with 0 lexical overlap returns clear guidance
        $nonsenseResp = $this->postJson('/api/v1/copilot/ask', [
            'query' => 'fghjfgh',
            'locale' => 'vi',
        ]);

        $nonsenseResp->assertStatus(200)
            ->assertJsonPath('mode', 'retrieval-fallback')
            ->assertJsonPath('match_score', 0);

        $this->assertStringContainsString('fghjfgh', $nonsenseResp->json('answer'));
    }

    public function test_no_closure_routes_exist_so_ci_route_cache_never_fails()
    {
        foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
            $action = $route->getAction();
            $this->assertIsString($action['uses'] ?? null, 'Closure route breaks deploy route:cache on URI: ' . $route->uri());
        }

        $this->get('/sitemap.xml')->assertStatus(200)->assertHeader('Content-Type', 'text/xml; charset=utf-8');
        $this->get('/robots.txt')->assertStatus(200);
    }

    public function test_copilot_validates_input_and_enforces_rate_limiter_outside_testing()
    {
        $this->postJson('/api/v1/copilot/ask', ['query' => 'a'])->assertStatus(422);
        $this->postJson('/api/v1/copilot/ask', ['query' => 'Valid question', 'locale' => 'fr'])->assertStatus(422);

        $previousEnv = $this->app['env'];
        $this->app['env'] = 'production';

        try {
            $clientIp = ['REMOTE_ADDR' => '198.51.100.77'];
            for ($i = 0; $i < 15; $i++) {
                $this->withServerVariables($clientIp)
                     ->postJson('/api/v1/copilot/ask', ['query' => 'Tell me GPA UTH ' . $i])
                     ->assertStatus(200);
            }

            $this->withServerVariables($clientIp)
                 ->postJson('/api/v1/copilot/ask', ['query' => 'Over limit query'])
                 ->assertStatus(429);
        } finally {
            $this->app['env'] = $previousEnv;
        }
    }

    public function test_gemini_model_fallback_cache_skips_dead_model_on_subsequent_turns()
    {
        $provider = AiProvider::create([
            'name' => 'Google Gemini Cached',
            'provider_type' => 'gemini',
            'api_key' => 'AIzaSyTestFakeKeyForCacheTest123456',
            'model' => 'gemini-deprecated-model',
            'is_active' => true,
            'is_default' => true,
        ]);

        Project::create([
            'title' => 'Tourest',
            'description' => 'Hybrid RAG AI tour booking platform.',
            'featured' => true,
        ]);

        $calledModels = [];

        Http::fake([
            'generativelanguage.googleapis.com/v1beta/interactions' => function ($request) use (&$calledModels) {
                $model = (string) ($request['model'] ?? '');
                $calledModels[] = $model;

                if ($model === 'gemini-deprecated-model') {
                    return Http::response(['error' => ['message' => 'Model not found', 'status' => 'NOT_FOUND']], 404);
                }

                return Http::response([
                    'outputs' => [[
                        'type' => 'message',
                        'content' => [['type' => 'text', 'text' => 'Tourest uses Hybrid RAG [S1].']],
                    ]],
                ], 200);
            },

            'generativelanguage.googleapis.com/v1beta/models/gemini-deprecated-model:generateContent' => Http::response([
                'error' => ['message' => 'Model not found', 'status' => 'NOT_FOUND'],
            ], 404),

            'generativelanguage.googleapis.com/v1beta/models?*' => Http::response([
                'models' => [
                    [
                        'name' => 'models/gemini-fast-live',
                        'supportedGenerationMethods' => ['generateContent'],
                        'outputTokenLimit' => 8192,
                    ],
                ],
            ], 200),
        ]);

        $first = $this->postJson('/api/v1/copilot/ask', ['query' => 'Tourest RAG']);
        $first->assertStatus(200)
            ->assertJsonPath('mode', 'llm-rag')
            ->assertJsonPath('model', 'gemini-fast-live');

        $calledModels = [];

        $second = $this->postJson('/api/v1/copilot/ask', ['query' => 'Tell me more about Tourest']);
        $second->assertStatus(200)
            ->assertJsonPath('mode', 'llm-rag')
            ->assertJsonPath('model', 'gemini-fast-live');

        $this->assertSame(['gemini-fast-live'], $calledModels);
        $this->assertSame('gemini-fast-live', \Illuminate\Support\Facades\Cache::get('gemini_chat_working_model_' . $provider->id));
    }

    public function test_openapi_spec_documents_copilot_rag_endpoint()
    {
        $response = $this->get('/api/docs/openapi.json');
        $response->assertStatus(200);
        $paths = $response->json('paths') ?? [];
        $this->assertArrayHasKey('/api/v1/copilot/ask', $paths);
    }
}
