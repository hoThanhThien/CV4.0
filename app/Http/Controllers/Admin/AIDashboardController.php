<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\BlogAutomation;
use App\Models\BlogAutomationRun;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Services\AI\Support\AiSchemaManager;
use Illuminate\Support\Facades\Schema;

class AIDashboardController extends Controller
{
    public function index()
    {
        $hasStatusCol = AiSchemaManager::hasBlogStatusColumn();
        $hasRunsTable = Schema::hasTable('blog_automation_runs');
        $hasAutomationsTable = Schema::hasTable('blog_automations');
        $hasProvidersTable = Schema::hasTable('ai_providers');
        $hasContactsTable = Schema::hasTable('contact_messages');

        $aiPostsQuery = BlogPost::where('ai_generated', true);
        $totalAiPosts = (clone $aiPostsQuery)->count();
        $publishedAiPosts = $hasStatusCol
            ? (clone $aiPostsQuery)->where('status', BlogPost::STATUS_PUBLISHED)->count()
            : (clone $aiPostsQuery)->where('published', true)->count();
        $pendingAiPosts = $hasStatusCol
            ? (clone $aiPostsQuery)->where('status', BlogPost::STATUS_PENDING_REVIEW)->count()
            : 0;
        $scheduledAiPosts = $hasStatusCol
            ? (clone $aiPostsQuery)->where('status', BlogPost::STATUS_SCHEDULED)->count()
            : 0;
        $draftAiPosts = $hasStatusCol
            ? (clone $aiPostsQuery)->where('status', BlogPost::STATUS_DRAFT)->count()
            : (clone $aiPostsQuery)->where('published', false)->count();
        $failedAiPosts = $hasStatusCol
            ? (clone $aiPostsQuery)->whereIn('status', [BlogPost::STATUS_FAILED, BlogPost::STATUS_CANCELLED])->count()
            : 0;

        $totalBlogPosts = BlogPost::count();
        $since7d = now()->subDays(7);
        $since30d = now()->subDays(30);

        $bilingualAiPosts = Schema::hasColumn('blog_posts', 'translations')
            ? (clone $aiPostsQuery)->whereNotNull('translations')->where('translations', '!=', '')->count()
            : 0;

        $aiPostStats = [
            'total' => $totalAiPosts,
            'published' => $publishedAiPosts,
            'pending_review' => $pendingAiPosts,
            'scheduled' => $scheduledAiPosts,
            'draft' => $draftAiPosts,
            'failed' => $failedAiPosts,
            'generated_7d' => (clone $aiPostsQuery)->where('created_at', '>=', $since7d)->count(),
            'generated_30d' => (clone $aiPostsQuery)->where('created_at', '>=', $since30d)->count(),
            'bilingual_ready' => $bilingualAiPosts,
            'total_blog_posts' => $totalBlogPosts,
            'ai_share_pct' => $totalBlogPosts > 0 ? (int) round(($totalAiPosts / $totalBlogPosts) * 100) : 0,
        ];

        $failedRuns = $hasRunsTable ? BlogAutomationRun::where('status', 'failed')->count() : 0;
        $totalRuns = $hasRunsTable ? BlogAutomationRun::count() : 0;
        $completedRuns = $hasRunsTable ? BlogAutomationRun::where('status', 'completed')->count() : 0;

        $runStats = [
            'total_runs' => $totalRuns,
            'completed_runs' => $completedRuns,
            'failed_runs' => $failedRuns,
            'success_rate' => $totalRuns > 0 ? (int) round(($completedRuns / $totalRuns) * 100) : 100,
            'total_tokens_30d' => $hasRunsTable
                ? (int) BlogAutomationRun::where('created_at', '>=', $since30d)->sum('total_tokens')
                : 0,
            'articles_created_30d' => $hasRunsTable
                ? (int) BlogAutomationRun::where('created_at', '>=', $since30d)->sum('articles_created')
                : 0,
            'estimated_cost_30d' => $hasRunsTable
                ? (float) BlogAutomationRun::where('created_at', '>=', $since30d)->sum('estimated_cost')
                : 0.0,
        ];

        // 7-day rolling throughput (DB-agnostic grouping across SQLite & MySQL)
        $windowStart = now()->subDays(6)->startOfDay();
        $recentAiCreatedDates = (clone $aiPostsQuery)
            ->where('created_at', '>=', $windowStart)
            ->get(['created_at'])
            ->countBy(fn ($p) => $p->created_at?->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d'));

        $recentRunDates = $hasRunsTable
            ? BlogAutomationRun::where('created_at', '>=', $windowStart)
                ->get(['created_at'])
                ->countBy(fn ($r) => $r->created_at?->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d'))
            : collect();

        $dailyAiTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->timezone('Asia/Ho_Chi_Minh')->subDays($i);
            $key = $day->format('Y-m-d');
            $dailyAiTrend[] = [
                'date' => $key,
                'label' => $day->format('d/m'),
                'posts' => (int) ($recentAiCreatedDates[$key] ?? 0),
                'runs' => (int) ($recentRunDates[$key] ?? 0),
            ];
        }

        // Contact inbox status summary
        $totalContacts = $hasContactsTable ? ContactMessage::count() : 0;
        $unreadContacts = $hasContactsTable ? ContactMessage::where('is_read', false)->count() : 0;
        $readContacts = max(0, $totalContacts - $unreadContacts);

        $contactStats = [
            'total' => $totalContacts,
            'unread' => $unreadContacts,
            'read' => $readContacts,
            'last_7d' => $hasContactsTable ? ContactMessage::where('created_at', '>=', $since7d)->count() : 0,
            'last_30d' => $hasContactsTable ? ContactMessage::where('created_at', '>=', $since30d)->count() : 0,
            'read_rate' => $totalContacts > 0 ? (int) round(($readContacts / $totalContacts) * 100) : 100,
        ];

        $recentMessages = $hasContactsTable ? ContactMessage::latest()->take(5)->get() : collect();

        $activeAutomations = $hasAutomationsTable ? BlogAutomation::active()->count() : 0;
        $nextScheduledRun = $hasAutomationsTable
            ? BlogAutomation::active()->whereNotNull('next_run_at')->orderBy('next_run_at', 'asc')->first()
            : null;

        $recentPosts = (clone $aiPostsQuery)->latest()->take(6)->get();
        $recentRuns = $hasRunsTable ? BlogAutomationRun::latest()->take(6)->get() : collect();
        $providers = $hasProvidersTable ? AiProvider::all() : collect();

        return view('admin.ai.dashboard', compact(
            'totalAiPosts',
            'publishedAiPosts',
            'pendingAiPosts',
            'scheduledAiPosts',
            'failedRuns',
            'activeAutomations',
            'nextScheduledRun',
            'recentPosts',
            'recentRuns',
            'providers',
            'aiPostStats',
            'runStats',
            'dailyAiTrend',
            'contactStats',
            'recentMessages'
        ));
    }
}
