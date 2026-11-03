<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\BlogPost;
use App\Models\BlogAutomation;
use App\Models\AiContentConfig;
use App\Models\AiProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class ScheduledPublishingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_website_does_not_leak_draft_or_scheduled_posts()
    {
        // 1. Published post (Live)
        $livePost = BlogPost::create([
            'title' => 'Live Published Post',
            'slug' => 'live-published-post',
            'content' => '<p>Live content</p>',
            'published' => true,
            'status' => BlogPost::STATUS_PUBLISHED,
            'published_at' => now()->subHours(2),
        ]);

        // 2. Draft post
        $draftPost = BlogPost::create([
            'title' => 'Secret Draft Post',
            'slug' => 'secret-draft-post',
            'content' => '<p>Draft content</p>',
            'published' => false,
            'status' => BlogPost::STATUS_DRAFT,
            'published_at' => null,
        ]);

        // 3. Pending Review post
        $pendingPost = BlogPost::create([
            'title' => 'Pending Review Post',
            'slug' => 'pending-review-post',
            'content' => '<p>Pending content</p>',
            'published' => false,
            'status' => BlogPost::STATUS_PENDING_REVIEW,
            'published_at' => null,
        ]);

        // 4. Future Scheduled post (Must remain hidden until scheduled time)
        $futurePost = BlogPost::create([
            'title' => 'Future Scheduled Post',
            'slug' => 'future-scheduled-post',
            'content' => '<p>Future content</p>',
            'published' => false,
            'status' => BlogPost::STATUS_SCHEDULED,
            'published_at' => now()->addHours(5),
            'scheduled_at' => now()->addHours(5),
        ]);

        // Test Public Listing /blog
        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertSee('Live Published Post');
        $response->assertDontSee('Secret Draft Post');
        $response->assertDontSee('Pending Review Post');
        $response->assertDontSee('Future Scheduled Post');

        // Test Individual Detail URLs
        $this->get('/blog/live-published-post')->assertStatus(200);
        $this->get('/blog/secret-draft-post')->assertStatus(404);
        $this->get('/blog/pending-review-post')->assertStatus(404);
        $this->get('/blog/future-scheduled-post')->assertStatus(404);

        // Test Sitemap
        $sitemapResponse = $this->get('/sitemap.xml');
        $sitemapResponse->assertStatus(200);
        $sitemapResponse->assertSee('/blog/live-published-post');
        $sitemapResponse->assertDontSee('/blog/future-scheduled-post');
        $sitemapResponse->assertDontSee('/blog/secret-draft-post');
    }

    public function test_scheduled_command_publishes_posts_when_due()
    {
        $duePost = BlogPost::create([
            'title' => 'Due Scheduled Post',
            'slug' => 'due-scheduled-post',
            'content' => '<p>Content now ready to be read</p>',
            'published' => false,
            'status' => BlogPost::STATUS_SCHEDULED,
            'published_at' => now()->subMinutes(2),
            'scheduled_at' => now()->subMinutes(2),
        ]);

        // Before command runs: should be 404
        $this->get('/blog/due-scheduled-post')->assertStatus(404);

        // Run artisan command
        Artisan::call('blog:publish-scheduled');

        // Refresh post
        $duePost->refresh();
        $this->assertEquals(BlogPost::STATUS_PUBLISHED, $duePost->status);
        $this->assertTrue($duePost->published);

        // Now accessible to the public
        $this->get('/blog/due-scheduled-post')->assertStatus(200);
        $this->get('/blog')->assertSee('Due Scheduled Post');
    }

    public function test_automation_command_runs_due_automations()
    {
        $provider = AiProvider::create([
            'name' => 'Auto Provider',
            'provider_type' => 'openai',
            'api_key' => 'sk-auto-key',
            'model' => 'gpt-4o-mini',
            'is_active' => true,
            'is_default' => true,
        ]);

        $config = AiContentConfig::create([
            'name' => 'Tech Automation Config',
            'ai_provider_id' => $provider->id,
            'topic_mode' => 'admin_topic',
            'main_topic' => 'Automated AI Blog',
            'keywords' => 'Laravel, Automation',
            'language' => 'vi',
            'writing_tone' => 'professional',
            'target_audience' => 'Engineers',
            'article_length' => 'medium',
            'category' => 'Technology',
            'is_active' => true,
        ]);

        $automation = BlogAutomation::create([
            'name' => 'Daily Tech Automation',
            'content_config_id' => $config->id,
            'frequency' => 'daily',
            'execution_time' => '08:00:00',
            'timezone' => 'Asia/Ho_Chi_Minh',
            'articles_per_run' => 1,
            'publishing_mode' => 'auto_publish',
            'interval_minutes' => 60,
            'next_run_at' => now()->subMinutes(1), // Due for run!
            'is_active' => true,
        ]);

        $mockJson = [
            'title' => 'Automated Post Created by Scheduler',
            'slug' => 'automated-post-created-by-scheduler',
            'content' => '<p>Generated by cronjob</p>',
        ];

        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [
                    ['message' => ['content' => json_encode($mockJson)]]
                ],
            ], 200),
        ]);

        Artisan::call('blog:run-automations');

        // Verify post was generated and auto published
        $post = BlogPost::where('slug', 'automated-post-created-by-scheduler')->first();
        $this->assertNotNull($post);
        $this->assertEquals(BlogPost::STATUS_PUBLISHED, $post->status);
        $this->assertTrue($post->published);

        // Verify automation next_run_at was updated into the future
        $automation->refresh();
        $this->assertNotNull($automation->next_run_at);
        $this->assertTrue($automation->next_run_at->isFuture());
    }

    public function test_web_cron_endpoint_triggers_due_scheduled_posts()
    {
        $duePost = BlogPost::create([
            'title' => 'Cron Endpoint Published Post',
            'slug' => 'cron-endpoint-published-post',
            'content' => '<p>Published via web cron endpoint</p>',
            'published' => false,
            'status' => BlogPost::STATUS_SCHEDULED,
            'published_at' => now()->subMinute(),
            'scheduled_at' => now()->subMinute(),
        ]);

        $res = $this->get('/cron/schedule-run');
        $res->assertStatus(200)->assertJson(['status' => 'ok']);

        $duePost->refresh();
        $this->assertEquals(BlogPost::STATUS_PUBLISHED, $duePost->status);
        $this->assertTrue($duePost->published);
    }
}
