<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogAutomation;
use App\Models\BlogAutomationRun;
use App\Models\AiProvider;

class AIDashboardController extends Controller
{
    public function index()
    {
        $totalAiPosts = BlogPost::where('ai_generated', true)->count();
        $publishedAiPosts = BlogPost::where('ai_generated', true)->where('status', BlogPost::STATUS_PUBLISHED)->count();
        $pendingAiPosts = BlogPost::where('ai_generated', true)->where('status', BlogPost::STATUS_PENDING_REVIEW)->count();
        $scheduledAiPosts = BlogPost::where('ai_generated', true)->where('status', BlogPost::STATUS_SCHEDULED)->count();
        $failedRuns = BlogAutomationRun::where('status', 'failed')->count();
        $activeAutomations = BlogAutomation::active()->count();

        $nextScheduledRun = BlogAutomation::active()
            ->whereNotNull('next_run_at')
            ->orderBy('next_run_at', 'asc')
            ->first();

        $recentPosts = BlogPost::where('ai_generated', true)
            ->latest()
            ->take(6)
            ->get();

        $recentRuns = BlogAutomationRun::latest()
            ->take(6)
            ->get();

        $providers = AiProvider::all();

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
            'providers'
        ));
    }
}
