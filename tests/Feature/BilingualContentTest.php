<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Certificate;
use App\Models\Project;
use App\Models\User;
use App\Services\RAG\PortfolioCorpusBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BilingualContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_crud_persists_bilingual_translations_and_renders_forms(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create();
        $this->actingAs($admin);

        // 1. Project Create Form & Store
        $this->get(route('admin.projects.create'))
            ->assertStatus(200)
            ->assertSee('translations[vi][title]', false)
            ->assertSee('translations[en][title]', false);

        $this->post(route('admin.projects.store'), [
            'title' => 'Telemetry Mesh Platform',
            'description' => "Lead Engineer | 06/2026\nHigh-throughput metrics collector.",
            'featured' => 1,
            'order' => 1,
            'translations' => [
                'en' => [
                    'title' => 'Telemetry Mesh Platform EN',
                    'description' => "Lead Engineer | 06/2026\nEnglish high-throughput metrics collector.",
                ],
                'vi' => [
                    'title' => 'Nen tang Giam sat Telemetry VI',
                    'description' => "Ky su Truong | 06/2026\nHe thong thu thap chi so hieu nang cao.",
                ],
            ],
        ])->assertRedirect(route('admin.projects.index'));

        $project = Project::firstOrFail();
        $this->assertSame('Telemetry Mesh Platform EN', $project->translationValue('title', 'en'));
        $this->assertSame('Nen tang Giam sat Telemetry VI', $project->translationValue('title', 'vi'));

        // Empty whitespace-only update strips clean rather than overwriting fallback with blanks
        $this->put(route('admin.projects.update', $project->id), [
            'title' => 'Telemetry Mesh Platform',
            'description' => "Lead Engineer | 06/2026\nHigh-throughput metrics collector.",
            'featured' => 1,
            'order' => 1,
            'translations' => [
                'en' => ['title' => 'Telemetry Mesh Platform EN', 'description' => "Lead Engineer | 06/2026\nEnglish high-throughput metrics collector."],
                'vi' => ['title' => '   ', 'description' => ''],
            ],
        ])->assertRedirect(route('admin.projects.index'));

        $project->refresh();
        $this->assertNull($project->translationValue('title', 'vi'));
        $this->assertSame('Telemetry Mesh Platform EN', $project->translationValue('title', 'en'));

        // 2. Certificate Create Form & Store
        $this->get(route('admin.certificates.create'))
            ->assertStatus(200)
            ->assertSee('translations[vi][issuer]', false);

        $this->post(route('admin.certificates.store'), [
            'title' => 'UTH Distinction Honor',
            'category' => 'degree',
            'issuer' => 'University of Transport HCMC',
            'issue_date' => '2026',
            'description' => 'Graduated with Distinction.',
            'file' => UploadedFile::fake()->create('degree.pdf', 120, 'application/pdf'),
            'featured' => 1,
            'order' => 1,
            'translations' => [
                'en' => [
                    'title' => 'UTH Distinction Honor EN',
                    'issuer' => 'UTH International Program',
                    'description' => 'Bachelor of Engineering with Distinction.',
                ],
                'vi' => [
                    'title' => 'Bang Khen Xuat Sac UTH VI',
                    'issuer' => 'Dai hoc Giao thong Van tai TPHCM',
                    'description' => 'Tot nghiep Loai Xuat sac nganh CNTT.',
                ],
            ],
        ])->assertRedirect(route('admin.certificates.index'));

        $cert = Certificate::firstOrFail();
        $this->assertSame('Bang Khen Xuat Sac UTH VI', $cert->translationValue('title', 'vi'));
        $this->assertSame('Dai hoc Giao thong Van tai TPHCM', $cert->translationValue('issuer', 'vi'));

        // 3. Blog Create Form & Store
        $this->get(route('admin.blog.create'))
            ->assertStatus(200)
            ->assertSee('translations[vi][excerpt]', false);

        $this->post(route('admin.blog.store'), [
            'title' => 'Hybrid RAG Indexing Notes',
            'excerpt' => 'Canonical summary.',
            'content' => '<p>Canonical body paragraph.</p>',
            'published' => 1,
            'translations' => [
                'en' => [
                    'title' => 'Hybrid RAG Indexing Notes EN',
                    'excerpt' => 'English concise excerpt.',
                    'content' => '<p>English full article content.</p>',
                ],
                'vi' => [
                    'title' => 'Ghi chu Lap chi muc Hybrid RAG VI',
                    'excerpt' => 'Tom tat tieng Viet ngan gon.',
                    'content' => '<p>Noi dung bai viet day du bang tieng Viet.</p>',
                ],
            ],
        ])->assertRedirect(route('admin.blog.index'));

        $post = BlogPost::firstOrFail();
        $this->assertSame('Ghi chu Lap chi muc Hybrid RAG VI', $post->translationValue('title', 'vi'));
        $this->assertSame('English concise excerpt.', $post->translationValue('excerpt', 'en'));
    }

    public function test_ai_post_update_preserves_or_updates_translations_cleanly(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $post = BlogPost::create([
            'title' => 'AI Systems Architecture',
            'slug' => 'ai-systems-architecture',
            'excerpt' => 'Base AI excerpt.',
            'content' => '<p>Base AI content.</p>',
            'status' => BlogPost::STATUS_DRAFT,
            'published' => false,
            'ai_generated' => true,
            'translations' => [
                'vi' => [
                    'title' => 'Kien truc He thong AI Ban dau',
                    'excerpt' => 'Tom tat AI ban dau.',
                    'content' => '<p>Noi dung AI ban dau.</p>',
                ],
            ],
        ]);

        $this->get(route('admin.ai.posts.edit', $post->id))
            ->assertStatus(200)
            ->assertSee('Kien truc He thong AI Ban dau');

        // Updating post fields without sending translations array preserves existing translations
        $this->put(route('admin.ai.posts.update', $post->id), [
            'title' => 'AI Systems Architecture Updated',
            'slug' => 'ai-systems-architecture',
            'excerpt' => 'Base AI excerpt.',
            'content' => '<p>Base AI content updated.</p>',
            'status' => BlogPost::STATUS_PUBLISHED,
        ])->assertRedirect(route('admin.ai.posts.preview', $post->id));

        $post->refresh();
        $this->assertSame('Kien truc He thong AI Ban dau', $post->translationValue('title', 'vi'));

        // Updating with translations saves new bilingual payload
        $this->put(route('admin.ai.posts.update', $post->id), [
            'title' => 'AI Systems Architecture Updated',
            'slug' => 'ai-systems-architecture',
            'excerpt' => 'Base AI excerpt.',
            'content' => '<p>Base AI content updated.</p>',
            'status' => BlogPost::STATUS_PUBLISHED,
            'translations' => [
                'en' => ['title' => 'AI Systems Architecture EN', 'excerpt' => 'EN excerpt.', 'content' => '<p>EN body.</p>'],
                'vi' => ['title' => 'Kien truc He thong AI Cap nhat', 'excerpt' => 'Tom tat AI cap nhat.', 'content' => '<p>Noi dung AI cap nhat.</p>'],
            ],
        ])->assertRedirect(route('admin.ai.posts.preview', $post->id));

        $post->refresh();
        $this->assertSame('Kien truc He thong AI Cap nhat', $post->translationValue('title', 'vi'));
        $this->assertSame('AI Systems Architecture EN', $post->translationValue('title', 'en'));
    }

    public function test_public_routes_render_localized_db_content_for_vi_en_and_fallback(): void
    {
        Storage::fake('public');

        $translatedProject = Project::create([
            'title' => 'Canonical Mesh Project',
            'description' => "Full-Stack Engineer | 01/2026\nCanonical description text.",
            'featured' => true,
            'order' => 1,
            'translations' => [
                'en' => [
                    'title' => 'Mesh Gateway Platform EN',
                    'description' => "Software Engineer | 01/2026 - 06/2026\nEnglish architecture summary for gateway.\n+ Built multi-region gateway failover.",
                ],
                'vi' => [
                    'title' => 'Nen tang Cong ket noi Mesh VI',
                    'description' => "Kiến trúc sư Hệ thống VI | 01/2026 - 06/2026\nTom tat kien truc bang tieng Viet chi tiet.\n+ Toi uu hoa tim kiem vector hybrid.",
                ],
            ],
        ]);

        $fallbackProject = Project::create([
            'title' => 'Untranslated Legacy Fallback System',
            'description' => "Backend Dev | 05/2025\nCanonical summary shown when locale translation is absent.",
            'featured' => true,
            'order' => 2,
        ]);

        Certificate::create([
            'title' => 'Canonical Honors Award',
            'category' => 'award',
            'issuer' => 'Canonical UTH',
            'issue_date' => '2026',
            'description' => 'Canonical award detail.',
            'file_path' => 'certificates/honor.pdf',
            'file_type' => 'pdf',
            'featured' => true,
            'order' => 1,
            'translations' => [
                'en' => [
                    'title' => 'First Class Honor EN',
                    'issuer' => 'UTH Engineering Faculty EN',
                    'description' => 'English description of distinction award.',
                ],
                'vi' => [
                    'title' => 'Giai thuong Xuat sac VI',
                    'issuer' => 'Khoa CNTT Dai hoc UTH VI',
                    'description' => 'Mo ta giai thuong bang tieng Viet.',
                ],
            ],
        ]);

        $post = BlogPost::create([
            'title' => 'Canonical Blog Title',
            'slug' => 'bilingual-architecture-guide',
            'excerpt' => 'Canonical blog excerpt.',
            'content' => '<p>Canonical body copy.</p>',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
            'translations' => [
                'en' => [
                    'title' => 'Vector Search Scaling EN',
                    'excerpt' => 'English scaling teaser copy.',
                    'content' => '<p>Detailed English paragraph about cosine scoring.</p>',
                ],
                'vi' => [
                    'title' => 'Mo rong Tim kiem Vector VI',
                    'excerpt' => 'Doan tom tat mo rong vector tieng Viet.',
                    'content' => '<p>Noi dung chuyen sau ve tinh diem cosine bang tieng Viet.</p>',
                ],
            ],
        ]);

        // Vietnamese views (?lang=vi) render VI translations + fallback cleanly
        $this->get('/?lang=vi')
            ->assertStatus(200)
            ->assertSee('Nen tang Cong ket noi Mesh VI')
            ->assertSee('Untranslated Legacy Fallback System')
            ->assertSee('Giai thuong Xuat sac VI')
            ->assertSee('Khoa CNTT Dai hoc UTH VI')
            ->assertSee('Mo rong Tim kiem Vector VI')
            ->assertDontSee('Mesh Gateway Platform EN')
            ->assertDontSee('Vector Search Scaling EN');

        $this->get('/projects?lang=vi')
            ->assertStatus(200)
            ->assertSee('Nen tang Cong ket noi Mesh VI')
            ->assertSee('Tom tat kien truc bang tieng Viet chi tiet.')
            ->assertSee('Untranslated Legacy Fallback System')
            ->assertDontSee('Mesh Gateway Platform EN');

        $this->get('/projects/' . $translatedProject->id . '?lang=vi')
            ->assertStatus(200)
            ->assertSee('Nen tang Cong ket noi Mesh VI')
            ->assertSee('Kien truc su Truong VI')
            ->assertSee('Tom tat kien truc bang tieng Viet chi tiet.')
            ->assertDontSee('Mesh Gateway Platform EN');

        $this->get('/projects/' . $fallbackProject->id . '?lang=vi')
            ->assertStatus(200)
            ->assertSee('Untranslated Legacy Fallback System')
            ->assertSee('Canonical summary shown when locale translation is absent.');

        $this->get('/certificates?lang=vi')
            ->assertStatus(200)
            ->assertSee('Giai thuong Xuat sac VI')
            ->assertSee('Khoa CNTT Dai hoc UTH VI')
            ->assertSee('Mo ta giai thuong bang tieng Viet.')
            ->assertDontSee('First Class Honor EN');

        $this->get('/blog?lang=vi')
            ->assertStatus(200)
            ->assertSee('Mo rong Tim kiem Vector VI')
            ->assertSee('Doan tom tat mo rong vector tieng Viet.')
            ->assertDontSee('Vector Search Scaling EN');

        $this->get('/blog/' . $post->slug . '?lang=vi')
            ->assertStatus(200)
            ->assertSee('Mo rong Tim kiem Vector VI')
            ->assertSee('Noi dung chuyen sau ve tinh diem cosine bang tieng Viet.')
            ->assertDontSee('Vector Search Scaling EN');

        // English views (?lang=en) switch away from session VI cleanly and render EN translations
        $this->get('/?lang=en')
            ->assertStatus(200)
            ->assertSee('Mesh Gateway Platform EN')
            ->assertSee('First Class Honor EN')
            ->assertSee('Vector Search Scaling EN')
            ->assertDontSee('Nen tang Cong ket noi Mesh VI')
            ->assertDontSee('Mo rong Tim kiem Vector VI');

        $this->get('/projects/' . $translatedProject->id . '?lang=en')
            ->assertStatus(200)
            ->assertSee('Mesh Gateway Platform EN')
            ->assertSee('English architecture summary for gateway.')
            ->assertDontSee('Nen tang Cong ket noi Mesh VI');

        $this->get('/blog/' . $post->slug . '?lang=en')
            ->assertStatus(200)
            ->assertSee('Vector Search Scaling EN')
            ->assertSee('Detailed English paragraph about cosine scoring.')
            ->assertDontSee('Mo rong Tim kiem Vector VI');
    }

    public function test_rag_corpus_builder_indexes_both_en_and_vi_translations(): void
    {
        $project = Project::create([
            'title' => 'Canonical Quantum Node',
            'description' => "Engineer | 2026\nBase summary.",
            'translations' => [
                'en' => ['title' => 'Quantum Edge Node EN', 'description' => "Engineer | 2026\nEnglish edge computing detail."],
                'vi' => ['title' => 'Nut Tinh Toan Bien Quantum VI', 'description' => "Ky su | 2026\nChi tiet dien toan bien bang tieng Viet."],
            ],
        ]);

        $cert = Certificate::create([
            'title' => 'Cloud Architecture Badge',
            'category' => 'certificate',
            'issuer' => 'Global Cloud Institute',
            'file_path' => 'certificates/badge.pdf',
            'file_type' => 'pdf',
            'translations' => [
                'vi' => ['title' => 'Chung chi Kien truc Dam may VI', 'issuer' => 'Hoc vien Dam may Quoc te'],
            ],
        ]);

        $post = BlogPost::create([
            'title' => 'Zero Downtime Migrations',
            'slug' => 'zero-downtime-migrations',
            'excerpt' => 'Base migration guide.',
            'content' => '<p>Base guide content.</p>',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subHour(),
            'translations' => [
                'vi' => [
                    'title' => 'Cap nhat CSDL Khong Gian Doan VI',
                    'excerpt' => 'Huong dan migrate an toan.',
                    'content' => '<p>Noi dung huong dan migrate tieng Viet.</p>',
                ],
            ],
        ]);

        $chunks = collect(PortfolioCorpusBuilder::buildChunks());

        $projChunk = $chunks->firstWhere('id', 'project:' . $project->id);
        $this->assertNotNull($projChunk);
        $this->assertStringContainsString('Quantum Edge Node EN', $projChunk['content']);
        $this->assertStringContainsString('Nut Tinh Toan Bien Quantum VI', $projChunk['content']);
        $this->assertStringContainsString('Chi tiet dien toan bien bang tieng Viet.', $projChunk['content']);

        $certChunk = $chunks->firstWhere('id', 'cert:' . $cert->id);
        $this->assertNotNull($certChunk);
        $this->assertStringContainsString('Chung chi Kien truc Dam may VI', $certChunk['content']);

        $blogChunk = $chunks->firstWhere('id', 'blog:' . $post->id);
        $this->assertNotNull($blogChunk);
        $this->assertStringContainsString('Cap nhat CSDL Khong Gian Doan VI', $blogChunk['content']);
        $this->assertStringContainsString('Noi dung huong dan migrate tieng Viet.', $blogChunk['content']);
    }
}
