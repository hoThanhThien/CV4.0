<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use App\Support\PortfolioCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheAndLayoutSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_cache_populates_and_observers_invalidate_on_crud_mutations(): void
    {
        Project::create([
            'title' => 'Cached Initial System',
            'description' => "Fullstack Developer | 01/2026\nInitial cached project.",
            'featured' => true,
            'order' => 1,
        ]);

        Skill::create([
            'name' => 'Laravel 11',
            'details' => 'SSR & Observers',
            'level' => 92,
            'category' => 'Backend',
            'order' => 1,
        ]);

        Experience::create([
            'company' => 'Enmasys',
            'position' => 'Odoo ERP Developer',
            'description' => 'ERP integration modules.',
            'start_date' => '2026-01-01',
        ]);

        $this->get('/?lang=en')
            ->assertStatus(200)
            ->assertSee('Cached Initial System');

        $this->assertTrue(Cache::has(PortfolioCache::KEY_HOME_FEATURED_PROJECTS));
        $this->assertTrue(Cache::has(PortfolioCache::KEY_HOME_SKILLS));
        $this->assertTrue(Cache::has(PortfolioCache::KEY_HOME_SKILL_CATEGORIES));
        $this->assertTrue(Cache::has(PortfolioCache::KEY_HOME_EXPERIENCES));
        $this->assertTrue(Cache::has(PortfolioCache::KEY_HOME_RECENT_POSTS));
        $this->assertTrue(Cache::has(PortfolioCache::KEY_HOME_FEATURED_CERTIFICATES));

        // Eloquent create/update/delete automatically clears caches via PortfolioContentObserver
        Project::create([
            'title' => 'Observer Fresh Flagship System',
            'description' => "Software Engineer | 06/2026\nAppears right after observer flush.",
            'featured' => true,
            'order' => 0,
        ]);

        $this->assertFalse(Cache::has(PortfolioCache::KEY_HOME_FEATURED_PROJECTS));
        $this->assertFalse(Cache::has(PortfolioCache::KEY_HOME_RECENT_POSTS));

        $this->get('/?lang=en')
            ->assertStatus(200)
            ->assertSee('Observer Fresh Flagship System');

        // Published BlogPost mutation invalidates cache as well
        BlogPost::create([
            'title' => 'Zero Stale Cache With Observers',
            'slug' => 'zero-stale-cache-with-observers',
            'excerpt' => 'How observers invalidate response fragments.',
            'content' => '<p>Observer architecture details.</p>',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        $this->assertFalse(Cache::has(PortfolioCache::KEY_HOME_RECENT_POSTS));

        $this->get('/?lang=en')
            ->assertStatus(200)
            ->assertSee('Zero Stale Cache With Observers');
    }

    public function test_certificate_counts_project_meta_and_sitemap_cache_invalidation(): void
    {
        $project = Project::create([
            'title' => 'Distributed Gateway Alpha',
            'description' => "Backend | 2026\nAPI Gateway project.",
            'featured' => true,
            'order' => 1,
        ]);

        $skill = Skill::create([
            'name' => 'Rust',
            'details' => 'High-throughput services',
            'level' => 85,
            'category' => 'Backend',
            'order' => 2,
        ]);

        Certificate::create([
            'title' => 'UTH Degree',
            'category' => 'degree',
            'issuer' => 'UTH',
            'issue_date' => '2026',
            'file_path' => 'certificates/degree.pdf',
            'file_type' => 'pdf',
            'featured' => true,
            'order' => 1,
        ]);

        // 1. Certificates page caches category counts
        $this->get('/certificates?lang=en')->assertStatus(200);
        $this->assertTrue(Cache::has(PortfolioCache::KEY_CERTIFICATE_COUNTS));
        $this->assertSame(1, PortfolioCache::certificateCategoryCounts()['degree']);
        $this->assertSame(1, PortfolioCache::certificateCategoryCounts()['all']);

        Certificate::create([
            'title' => 'Design Award',
            'category' => 'award',
            'issuer' => 'UTH',
            'issue_date' => '2026',
            'file_path' => 'certificates/award.pdf',
            'file_type' => 'pdf',
            'featured' => true,
            'order' => 2,
        ]);

        $this->assertFalse(Cache::has(PortfolioCache::KEY_CERTIFICATE_COUNTS));
        $this->assertSame(1, PortfolioCache::certificateCategoryCounts()['award']);
        $this->assertSame(2, PortfolioCache::certificateCategoryCounts()['all']);

        // 2. Projects archive meta caches & invalidates on pivot sync via Admin CRUD
        $this->get('/projects?lang=en')->assertStatus(200);
        $this->assertTrue(Cache::has(PortfolioCache::KEY_PROJECT_ARCHIVE_META));
        $this->assertNotContains('Rust', PortfolioCache::projectArchiveMeta()['allTechNames']);

        $admin = User::factory()->create();
        $this->actingAs($admin);

        $this->put(route('admin.projects.update', $project->id), [
            'title' => 'Distributed Gateway Alpha',
            'description' => "Backend | 2026\nAPI Gateway project.",
            'featured' => 1,
            'order' => 1,
            'skills' => [$skill->id],
        ])->assertRedirect(route('admin.projects.index'));

        $this->assertFalse(Cache::has(PortfolioCache::KEY_PROJECT_ARCHIVE_META));
        $this->assertContains('Rust', PortfolioCache::projectArchiveMeta()['allTechNames']);

        // 3. Sitemap XML caches & busts when a published blog post is added (never includes drafts)
        $sitemapKey = PortfolioCache::KEY_SITEMAP_XML . '.' . md5(rtrim(url('/'), '/'));
        $this->get('/sitemap.xml')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'text/xml; charset=utf-8')
            ->assertSee('/projects/' . $project->id);

        $this->assertTrue(Cache::has($sitemapKey));

        BlogPost::create([
            'title' => 'Fresh Sitemap Article',
            'slug' => 'fresh-sitemap-article',
            'content' => '<p>Body.</p>',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subMinute(),
        ]);

        BlogPost::create([
            'title' => 'Unpublished Sitemap Secret',
            'slug' => 'unpublished-sitemap-secret',
            'content' => '<p>Draft.</p>',
            'published' => false,
            'status' => BlogPost::STATUS_DRAFT,
            'published_at' => null,
        ]);

        $this->assertFalse(Cache::has($sitemapKey));

        $this->get('/sitemap.xml')
            ->assertStatus(200)
            ->assertSee('/blog/fresh-sitemap-article')
            ->assertDontSee('unpublished-sitemap-secret');
    }

    public function test_refactored_pages_share_pearl_layout_and_floating_widgets_slot(): void
    {
        $homeHtml = $this->get('/?lang=en')
            ->assertStatus(200)
            ->assertSee('id="ragCopilotPanel"', false)
            ->assertSee('window.PAGE_I18N', false)
            ->assertDontSee('@section', false)
            ->assertDontSee('@yield', false)
            ->getContent();

        $this->assertSame(1, substr_count($homeHtml, 'let currentLang ='));
        $this->assertSame(1, substr_count($homeHtml, 'function setLanguage('));

        $projectsHtml = $this->get('/projects?lang=vi')
            ->assertStatus(200)
            ->assertSee('window.PAGE_I18N', false)
            ->assertDontSee('id="ragCopilotPanel"', false)
            ->assertDontSee('@section', false)
            ->getContent();

        $this->assertSame(1, substr_count($projectsHtml, 'let currentLang ='));
        $this->assertSame(1, substr_count($projectsHtml, 'function setLanguage('));

        $contactHtml = $this->get('/contact?lang=vi')
            ->assertStatus(200)
            ->assertSee('window.PAGE_I18N', false)
            ->assertSee('aria-current="page"', false)
            ->assertDontSee('id="ragCopilotPanel"', false)
            ->assertDontSee('@section', false)
            ->getContent();

        $this->assertSame(1, substr_count($contactHtml, 'let currentLang ='));
        $this->assertSame(1, substr_count($contactHtml, 'function setLanguage('));
    }
}
