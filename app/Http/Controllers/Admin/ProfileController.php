<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Models\BlogAutomation;
use App\Models\BlogAutomationRun;
use App\Models\BlogPost;
use App\Models\Certificate;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Services\AI\Support\AiSchemaManager;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $errors = session('errors');
        $defaultTab = ($errors && ($errors->has('current_password') || $errors->has('password')))
            ? 'security'
            : session('profile_tab', 'overview');

        $activeTab = $request->query('tab', $defaultTab);
        if (!in_array($activeTab, ['overview', 'security', 'activity', 'preferences'], true)) {
            $activeTab = 'overview';
        }

        // Real Sanctum API tokens belonging to this user
        $tokens = $user->tokens()
            ->orderByDesc('created_at')
            ->get();

        // Parse current browser/session telemetry truthfully from the live HTTP request
        $ua = (string) $request->userAgent();
        $browser = $this->detectBrowser($ua);
        $os = $this->detectOs($ua);

        $currentSession = [
            'session_id_prefix' => substr((string) $request->session()->getId(), 0, 12),
            'driver' => config('session.driver', 'file'),
            'lifetime_minutes' => (int) config('session.lifetime', 120),
            'ip_address' => $request->ip() ?: '127.0.0.1',
            'browser' => $browser,
            'os' => $os,
            'locale' => app()->getLocale(),
            'is_secure' => $request->isSecure(),
        ];

        $aiReady = false;
        try {
            $aiReady = AiSchemaManager::isReady();
        } catch (\Throwable $e) {
            $aiReady = false;
        }

        $cmsSummary = [
            'projects' => Project::count(),
            'blog_posts' => BlogPost::count(),
            'published_posts' => BlogPost::where('published', true)->count(),
            'certificates' => Certificate::count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'pending_ai_posts' => $aiReady && AiSchemaManager::hasBlogStatusColumn()
                ? BlogPost::where('ai_generated', true)->where('status', 'pending_review')->count()
                : 0,
            'ai_providers' => $aiReady ? AiProvider::where('is_active', true)->count() : 0,
            'active_automations' => $aiReady ? BlogAutomation::where('is_active', true)->count() : 0,
        ];

        $activities = $this->buildRecentActivityFeed($user, $tokens, $aiReady);

        return view('admin.profile.index', compact(
            'user',
            'activeTab',
            'tokens',
            'currentSession',
            'cmsSummary',
            'activities'
        ));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        $emailChanged = $validated['email'] !== $user->email;
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()
            ->route('admin.profile.index', ['tab' => 'overview'])
            ->with('success', 'Đã cập nhật thông tin hồ sơ quản trị viên.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()
                ->route('admin.profile.index', ['tab' => 'security'])
                ->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        $request->session()->regenerate();

        return redirect()
            ->route('admin.profile.index', ['tab' => 'security'])
            ->with('success', 'Đã cập nhật mật khẩu đăng nhập thành công.');
    }

    public function destroyToken(Request $request, int $tokenId)
    {
        $deleted = $request->user()
            ->tokens()
            ->where('id', $tokenId)
            ->delete();

        if (!$deleted) {
            return redirect()
                ->route('admin.profile.index', ['tab' => 'security'])
                ->with('error', 'Không tìm thấy API Token thuộc tài khoản hiện tại.');
        }

        return redirect()
            ->route('admin.profile.index', ['tab' => 'security'])
            ->with('success', 'Đã thu hồi Sanctum API Token.');
    }

    /**
     * Build unified real-time activity feed directly from existing CMS records (no fabricated entries).
     */
    private function buildRecentActivityFeed($user, Collection $tokens, bool $aiReady): Collection
    {
        $items = collect();

        if ($user->updated_at && (!$user->created_at || !$user->updated_at->equalTo($user->created_at))) {
            $items->push([
                'category' => 'account',
                'icon' => 'fa-user-edit',
                'tone' => 'cobalt',
                'title' => 'Cập nhật hồ sơ / bảo mật tài khoản',
                'detail' => $user->email,
                'timestamp' => $user->updated_at,
                'url' => null,
            ]);
        }

        if ($user->created_at) {
            $items->push([
                'category' => 'account',
                'icon' => 'fa-user-check',
                'tone' => 'success',
                'title' => 'Khởi tạo tài khoản quản trị viên',
                'detail' => 'ID #' . $user->id . ' — ' . $user->email,
                'timestamp' => $user->created_at,
                'url' => null,
            ]);
        }

        foreach ($tokens->take(4) as $token) {
            $ts = $token->last_used_at ?? $token->created_at;
            if ($ts) {
                $items->push([
                    'category' => 'account',
                    'icon' => 'fa-key',
                    'tone' => 'cobalt',
                    'title' => $token->last_used_at
                        ? 'Sử dụng API Token: ' . $token->name
                        : 'Khởi tạo API Token: ' . $token->name,
                    'detail' => 'Sanctum Personal Access Token #' . $token->id,
                    'timestamp' => $ts,
                    'url' => null,
                ]);
            }
        }

        foreach (BlogPost::latest('updated_at')->take(4)->get() as $post) {
            $items->push([
                'category' => 'content',
                'icon' => $post->ai_generated ? 'fa-robot' : 'fa-newspaper',
                'tone' => $post->published ? 'success' : 'neutral',
                'title' => ($post->ai_generated ? 'Bài viết AI: ' : 'Bài viết Blog: ') . Str::limit($post->title, 58),
                'detail' => $post->published ? 'Đã xuất bản' : 'Bản nháp / Chờ duyệt',
                'timestamp' => $post->updated_at ?? $post->created_at,
                'url' => route('admin.blog.edit', $post->id),
            ]);
        }

        foreach (Project::latest('updated_at')->take(3)->get() as $project) {
            $items->push([
                'category' => 'content',
                'icon' => 'fa-code',
                'tone' => 'neutral',
                'title' => 'Dự án: ' . Str::limit($project->title, 58),
                'detail' => $project->featured ? 'Dự án nổi bật' : 'Portfolio item',
                'timestamp' => $project->updated_at ?? $project->created_at,
                'url' => route('admin.projects.edit', $project->id),
            ]);
        }

        foreach (Certificate::latest('updated_at')->take(2)->get() as $cert) {
            $items->push([
                'category' => 'content',
                'icon' => 'fa-certificate',
                'tone' => 'neutral',
                'title' => 'Chứng chỉ / Hồ sơ: ' . Str::limit($cert->title, 54),
                'detail' => $cert->issuer ?: 'Portfolio credentials',
                'timestamp' => $cert->updated_at ?? $cert->created_at,
                'url' => route('admin.certificates.edit', $cert->id),
            ]);
        }

        if ($aiReady) {
            foreach (BlogAutomationRun::latest('created_at')->take(4)->get() as $run) {
                $isOk = $run->status === 'success';
                $items->push([
                    'category' => 'ai',
                    'icon' => $isOk ? 'fa-bolt' : 'fa-exclamation-triangle',
                    'tone' => $isOk ? 'cobalt' : 'danger',
                    'title' => 'AI Automation (' . ($run->provider_name ?: 'Gemini') . ')',
                    'detail' => ($run->topic_processed ?: $run->model_used ?: 'Scheduled run') . ' — ' . ucfirst((string) $run->status),
                    'timestamp' => $run->completed_at ?? $run->started_at ?? $run->created_at,
                    'url' => route('admin.ai.logs.show', $run->id),
                ]);
            }
        }

        return $items
            ->filter(fn ($item) => !empty($item['timestamp']))
            ->sortByDesc('timestamp')
            ->take(14)
            ->values();
    }

    private function detectBrowser(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Edg/') => 'Microsoft Edge',
            str_contains($ua, 'OPR/') || str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'Chrome/') && !str_contains($ua, 'Edg/') => 'Google Chrome',
            str_contains($ua, 'Firefox/') => 'Mozilla Firefox',
            str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome/') => 'Safari',
            $ua === '' => 'CLI / Test Client',
            default => 'Trình duyệt web',
        };
    }

    private function detectOs(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Mac OS X') || str_contains($ua, 'Macintosh') => 'macOS',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iOS / iPadOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Không xác định',
        };
    }
}
