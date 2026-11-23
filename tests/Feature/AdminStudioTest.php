<?php

namespace Tests\Feature;

use App\Models\AiContentConfig;
use App\Models\AiProvider;
use App\Models\BlogAutomation;
use App\Models\BlogAutomationRun;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminStudioTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Thien Ho Studio',
            'email' => 'studio-admin@portfolio.test',
            'password' => Hash::make('StudioPass123!'),
        ]);
    }

    public function test_guest_cannot_access_admin_dashboards(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/ai')->assertRedirect('/admin/login');
    }

    public function test_admin_login_and_studio_layout_use_pearl_white_dark_mode_and_lucide(): void
    {
        $this->get('/admin/login')
            ->assertStatus(200)
            ->assertSee('lucide-admin.js')
            ->assertSee('thien_theme')
            ->assertSee('loginThemeToggle');

        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertStatus(200)
            ->assertSee('lucide-admin.js')
            ->assertSee('thien_theme')
            ->assertSee('adminThemeToggle')
            ->assertSee('Pearl White 4.0');
    }

    public function test_ai_dashboard_reports_ai_post_and_contact_analytics(): void
    {
        $provider = AiProvider::create([
            'name' => 'Google Gemini Test',
            'provider_type' => 'gemini',
            'model' => 'gemini-2.5-flash',
            'api_key' => 'fake-test-key',
            'is_active' => true,
            'is_default' => true,
            'connection_status' => 'connected',
        ]);

        $config = AiContentConfig::create([
            'name' => 'RAG Studio Config',
            'ai_provider_id' => $provider->id,
            'main_topic' => 'Applied RAG Architecture',
        ]);

        BlogPost::create([
            'title' => 'AI Published Post',
            'slug' => 'ai-published-post',
            'content' => '<p>Body</p>',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now(),
            'ai_generated' => true,
            'ai_provider_id' => $provider->id,
            'ai_config_id' => $config->id,
            'translations' => json_encode(['en' => ['title' => 'AI Published Post'], 'vi' => ['title' => 'Bài AI đã đăng']]),
        ]);

        BlogPost::create([
            'title' => 'AI Pending Review Post',
            'slug' => 'ai-pending-review-post',
            'content' => '<p>Draft body</p>',
            'published' => false,
            'status' => BlogPost::STATUS_PENDING_REVIEW,
            'ai_generated' => true,
            'ai_provider_id' => $provider->id,
            'ai_config_id' => $config->id,
        ]);

        BlogPost::create([
            'title' => 'Manual Editorial Post',
            'slug' => 'manual-editorial-post',
            'content' => '<p>Manual</p>',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now(),
            'ai_generated' => false,
        ]);

        $automation = BlogAutomation::create([
            'name' => 'Weekly RAG Digest',
            'content_config_id' => $config->id,
            'frequency' => 'weekly',
            'publishing_mode' => 'manual_approval',
            'is_active' => true,
            'next_run_at' => now()->addDay(),
        ]);

        BlogAutomationRun::create([
            'automation_id' => $automation->id,
            'content_config_id' => $config->id,
            'ai_provider_id' => $provider->id,
            'provider_name' => 'Google Gemini',
            'model_used' => 'gemini-2.5-flash',
            'topic_processed' => 'RAG Architecture',
            'articles_requested' => 1,
            'articles_created' => 1,
            'articles_failed' => 0,
            'total_tokens' => 4200,
            'status' => 'completed',
        ]);

        ContactMessage::create([
            'name' => 'Recruiter One',
            'email' => 'hr1@example.com',
            'subject' => 'Backend Engineer Role',
            'message' => 'Let us schedule an interview.',
            'is_read' => false,
        ]);

        ContactMessage::create([
            'name' => 'Recruiter Two',
            'email' => 'hr2@example.com',
            'subject' => 'Odoo Project',
            'message' => 'Follow up note.',
            'is_read' => true,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/ai');

        $response->assertStatus(200)
            ->assertViewHas('aiPostStats', function (array $stats) {
                return $stats['total'] === 2
                    && $stats['published'] === 1
                    && $stats['pending_review'] === 1
                    && $stats['bilingual_ready'] === 1
                    && $stats['generated_7d'] === 2
                    && $stats['total_blog_posts'] === 3
                    && $stats['ai_share_pct'] === 67;
            })
            ->assertViewHas('runStats', function (array $stats) {
                return $stats['total_runs'] === 1
                    && $stats['completed_runs'] === 1
                    && $stats['success_rate'] === 100
                    && $stats['total_tokens_30d'] === 4200;
            })
            ->assertViewHas('contactStats', function (array $stats) {
                return $stats['total'] === 2
                    && $stats['unread'] === 1
                    && $stats['read'] === 1
                    && $stats['read_rate'] === 50;
            })
            ->assertViewHas('dailyAiTrend', fn (array $trend) => count($trend) === 7)
            ->assertSee('4,200')
            ->assertSee('Recruiter One')
            ->assertSee('Weekly RAG Digest')
            ->assertSee('adminThemeToggle');
    }
}
