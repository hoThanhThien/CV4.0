<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesRenderTest extends TestCase
{
    use RefreshDatabase;

    private Project $project;
    private BlogPost $post;

    protected function setUp(): void
    {
        parent::setUp();

        Skill::create([
            'name' => 'Laravel',
            'details' => 'SSR, Queues, Eloquent ORM',
            'level' => 92,
            'category' => 'Backend',
            'order' => 1,
        ]);

        Experience::create([
            'company' => 'Enmasys',
            'position' => 'Backend & ERP Developer',
            'description' => 'Configured enterprise ERP modules and REST integrations.',
            'start_date' => '2025-06-01',
        ]);

        $this->project = Project::create([
            'title' => 'Personal Portfolio & AI CMS',
            'description' => 'Full-stack Laravel 11 portfolio with automated AI publishing and RAG demo.',
            'featured' => true,
            'order' => 1,
        ]);

        Certificate::create([
            'title' => 'UTH Information Technology Degree',
            'category' => 'degree',
            'issuer' => 'University of Transport Ho Chi Minh City',
            'issue_date' => '2026',
            'description' => 'Bachelor of Information Technology.',
            'file_path' => 'certificates/sample-degree.pdf',
            'file_type' => 'pdf',
            'featured' => true,
            'order' => 1,
        ]);

        $this->post = BlogPost::create([
            'title' => 'Designing Grounded RAG Pipelines',
            'slug' => 'designing-grounded-rag-pipelines',
            'excerpt' => 'How to pair vector retrieval with deterministic fallback guards.',
            'content' => '<p>Structured technical walkthrough of production RAG architecture.</p>',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
            'category' => 'Applied AI',
        ]);
    }

    public function test_all_public_pages_render_pearl_white_layout_in_en_and_vi()
    {
        $routes = [
            '/',
            '/about',
            '/projects',
            '/projects/' . $this->project->id,
            '/certificates',
            '/blog',
            '/blog/' . $this->post->slug,
            '/contact',
        ];

        foreach (['en', 'vi'] as $locale) {
            foreach ($routes as $route) {
                $response = $this->get($route . '?lang=' . $locale);
                $response->assertStatus(200);

                $html = $response->getContent();
                $this->assertNotFalse(mb_check_encoding($html, 'UTF-8'), "Invalid UTF-8 on $route ($locale)");

                foreach (['bg-tech-grid', 'frosted-glass', 'spotlight-card', 'themeToggleBtn', 'lucide'] as $marker) {
                    $this->assertStringContainsString($marker, $html, "Missing Pearl marker '$marker' on $route ($locale)");
                }

                $this->assertDoesNotMatchRegularExpression(
                    '/class="[^"]*\b(fas|fab|far)\s+fa-/i',
                    $html,
                    "Legacy FontAwesome class leaked on $route ($locale)"
                );
                $this->assertDoesNotMatchRegularExpression(
                    '/@section\(|@extends\(|@yield\(/',
                    $html,
                    "Unparsed Blade directive leaked on $route ($locale)"
                );
            }
        }
    }
}
