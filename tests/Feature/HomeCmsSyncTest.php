<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeCmsSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_newly_published_blog_posts_appear_on_homepage_without_leaking_drafts()
    {
        $oldPost = BlogPost::create([
            'title' => 'Legacy Systems Overview',
            'slug' => 'legacy-systems-overview',
            'excerpt' => 'Older architecture notes.',
            'content' => '<p>Older content.</p>',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subDays(5),
            'category' => 'Backend',
        ]);

        $freshPost = BlogPost::create([
            'title' => 'Autonomous Hybrid Vector Reranker',
            'slug' => 'autonomous-hybrid-vector-reranker',
            'excerpt' => 'Shipping hybrid BM25 and dense embeddings to production.',
            'content' => '<p>Fresh AI Studio post content.</p>',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subMinutes(5),
            'category' => 'RAG AI',
            'ai_generated' => true,
        ]);

        BlogPost::create([
            'title' => 'Top Secret Unpublished Draft Post',
            'slug' => 'top-secret-unpublished-draft',
            'excerpt' => 'Should never show publicly.',
            'content' => '<p>Draft.</p>',
            'published' => false,
            'status' => BlogPost::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200)
            ->assertSee('Autonomous Hybrid Vector Reranker')
            ->assertSee('Shipping hybrid BM25 and dense embeddings to production.')
            ->assertSee('/blog/' . $freshPost->slug)
            ->assertSee('Legacy Systems Overview')
            ->assertSee('/blog/' . $oldPost->slug)
            ->assertDontSee('Top Secret Unpublished Draft Post');
    }

    public function test_featured_projects_experiences_and_certificates_render_dynamically_from_db()
    {
        $tourest = Project::create([
            'title' => 'Tourest AI Platform',
            'description' => "Fullstack Developer | 01/2026 - 06/2026\nHybrid FAISS + BM25 RAG travel booking platform.",
            'featured' => true,
            'order' => 1,
        ]);

        $laundry = Project::create([
            'title' => 'Laundry Service Hub',
            'description' => "Backend Engineer | 01/2025 - 05/2025\nSpring Boot stateful laundry management.",
            'featured' => true,
            'order' => 2,
        ]);

        $extraFeatured = Project::create([
            'title' => 'Zero-Latency Event Gateway',
            'description' => "Software Engineer | 09/2026 - 11/2026\nRealtime event fan-out service built in Laravel & Redis.",
            'featured' => true,
            'order' => 3,
        ]);

        Experience::create([
            'company' => 'Vanguard Cloud Labs',
            'position' => 'Systems Architect Intern',
            'description' => 'Architected distributed telemetry pipelines in PostgreSQL and Docker.',
            'start_date' => '2026-08-01',
            'current' => true,
            'order' => 1,
        ]);

        Certificate::create([
            'title' => 'Cloud Native Kubernetes Practitioner',
            'category' => 'certificate',
            'issuer' => 'CNCF Training',
            'issue_date' => '2026',
            'file_path' => 'certificates/cncf.pdf',
            'featured' => true,
            'order' => 1,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200)
            ->assertSee('/projects/' . $tourest->id)
            ->assertSee('Tourest AI Platform')
            ->assertSee('/projects/' . $laundry->id)
            ->assertSee('Laundry Service Hub')
            ->assertSee('/projects/' . $extraFeatured->id)
            ->assertSee('Zero-Latency Event Gateway')
            ->assertSee('Vanguard Cloud Labs')
            ->assertSee('Systems Architect Intern')
            ->assertSee('Cloud Native Kubernetes Practitioner')
            ->assertSee('CNCF Training');
    }

    public function test_custom_and_updated_db_skills_sync_into_skill_matrix()
    {
        Skill::create([
            'name' => 'Laravel',
            'details' => 'Custom Event Sourcing & Octane Workers',
            'category' => 'Backend',
            'order' => 1,
        ]);

        Skill::create([
            'name' => 'Kubernetes',
            'details' => 'Multi-node Helm rollouts & HPA autoscaling',
            'category' => 'DevOps & Cloud',
            'order' => 2,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200)
            ->assertSee('Custom Event Sourcing \\u0026 Octane Workers', false)
            ->assertSee('Kubernetes', false)
            ->assertSee('Multi-node Helm rollouts \\u0026 HPA autoscaling', false);

        $categories = $response->viewData('skillCategories');
        $flattened = collect($categories)->flatMap(fn ($cat) => $cat['items']);
        $this->assertTrue($flattened->contains(fn ($item) => $item['name'] === 'Kubernetes'));
        $this->assertTrue($flattened->contains(fn ($item) => $item['desc']['en'] === 'Custom Event Sourcing & Octane Workers'));
    }
}
