<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\AiProvider;
use App\Models\AiContentConfig;
use App\Services\AI\BlogContentGenerator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Throwable;

class AIPostController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        $query = BlogPost::where('ai_generated', true)->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $posts = $query->with(['provider', 'contentConfig'])->paginate(12)->withQueryString();

        $counts = [
            'all' => BlogPost::where('ai_generated', true)->count(),
            'pending_review' => BlogPost::where('ai_generated', true)->where('status', BlogPost::STATUS_PENDING_REVIEW)->count(),
            'scheduled' => BlogPost::where('ai_generated', true)->where('status', BlogPost::STATUS_SCHEDULED)->count(),
            'draft' => BlogPost::where('ai_generated', true)->where('status', BlogPost::STATUS_DRAFT)->count(),
            'published' => BlogPost::where('ai_generated', true)->where('status', BlogPost::STATUS_PUBLISHED)->count(),
        ];

        return view('admin.ai.posts.index', compact('posts', 'status', 'counts'));
    }

    public function preview($id)
    {
        $post = BlogPost::findOrFail($id);
        return view('admin.ai.posts.preview', compact('post'));
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        return view('admin.ai.posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $post->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'keywords' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|string',
            'status' => 'required|in:draft,pending_review,scheduled,published',
            'scheduled_at' => 'nullable|date',
        ]);

        if (!empty($validated['tags'])) {
            $validated['tags'] = array_values(array_filter(array_map('trim', explode(',', $validated['tags']))));
        } else {
            $validated['tags'] = [];
        }

        $validated['last_edited_by_admin'] = true;

        if ($validated['status'] === 'published') {
            $validated['published'] = true;
            $validated['published_at'] = $post->published_at ?: now();
        } elseif ($validated['status'] === 'scheduled') {
            $validated['published'] = false;
            $validated['published_at'] = !empty($validated['scheduled_at']) ? Carbon::parse($validated['scheduled_at']) : now()->addHours(3);
            $validated['scheduled_at'] = $validated['published_at'];
        } else {
            $validated['published'] = false;
            $validated['published_at'] = null;
        }

        $post->update($validated);

        return redirect()->route('admin.ai.posts.preview', $post->id)->with('success', 'Đã lưu thay đổi bài viết thành công!');
    }

    public function approve($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->update([
            'status' => BlogPost::STATUS_DRAFT,
        ]);

        return back()->with('success', "Đã phê duyệt bài viết '#{$post->id}: {$post->title}'! Bài viết hiện ở trạng thái Nháp (Draft), sẵn sàng xuất bản hoặc lên lịch.");
    }

    public function publishNow($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->update([
            'status' => BlogPost::STATUS_PUBLISHED,
            'published' => true,
            'published_at' => now(),
            'scheduled_at' => null,
        ]);

        return back()->with('success', "Đã xuất bản bài viết '#{$post->title}' thành công ra trang công khai!");
    }

    public function schedule(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $request->validate([
            'scheduled_at' => 'required|date|after:now',
        ]);

        $scheduledTime = Carbon::parse($request->input('scheduled_at'));

        $post->update([
            'status' => BlogPost::STATUS_SCHEDULED,
            'published' => false,
            'published_at' => $scheduledTime,
            'scheduled_at' => $scheduledTime,
        ]);

        return back()->with('success', "Đã lên lịch xuất bản bài viết vào lúc: " . $scheduledTime->format('d/m/Y H:i'));
    }

    public function cancelSchedule($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->update([
            'status' => BlogPost::STATUS_DRAFT,
            'published' => false,
            'scheduled_at' => null,
        ]);

        return back()->with('success', "Đã hủy lịch xuất bản bài viết. Đã chuyển về bản Nháp.");
    }

    public function regenerate(Request $request, $id, BlogContentGenerator $generator)
    {
        $post = BlogPost::findOrFail($id);

        $config = $post->contentConfig;
        if (!$config) {
            $config = AiContentConfig::active()->first();
        }

        if (!$config) {
            return back()->with('error', 'Không tìm thấy cấu hình nội dung AI phù hợp để sinh lại bài viết.');
        }

        // Safeguard: Check if admin has made edits
        if ($post->last_edited_by_admin && !$request->boolean('confirm_overwrite')) {
            return back()->with('error', 'Bài viết này đã được Admin chỉnh sửa thủ công. Vui lòng xác nhận ghi đè trước khi tạo lại!');
        }

        try {
            $provider = $config->getEffectiveProvider();
            if (!$provider) {
                return back()->with('error', 'AI Provider không khả dụng.');
            }

            $adapter = \App\Services\AI\AIProviderFactory::make($provider);
            $prompts = $generator->buildPrompts($config, [$post->title]);
            $response = $adapter->generateBlogContent($provider, $prompts['system'], $prompts['user'], $config->model_override);

            if (!$response['success'] || empty($response['data'])) {
                return back()->with('error', 'AI không thể tạo lại nội dung: ' . ($response['error'] ?? 'Lỗi không xác định'));
            }

            $data = $response['data'];
            $sanitized = $generator->sanitizeHtml($data['content'] ?? '');

            $post->update([
                'title' => trim($data['title']),
                'content' => $sanitized,
                'excerpt' => $data['excerpt'] ?? $post->excerpt,
                'meta_title' => $data['meta_title'] ?? $post->meta_title,
                'meta_description' => $data['meta_description'] ?? $post->meta_description,
                'category' => $data['category'] ?? $post->category,
                'tags' => $data['tags'] ?? $post->tags,
                'faq' => $data['faq'] ?? $post->faq,
                'sources' => $data['sources'] ?? $post->sources,
                'cover_image_prompt' => $data['cover_image_prompt'] ?? $post->cover_image_prompt,
                'last_edited_by_admin' => false,
            ]);

            return back()->with('success', "Đã tạo lại nội dung bài viết thành công!");
        } catch (Throwable $e) {
            return back()->with('error', 'Lỗi ngoại lệ khi tạo lại: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.ai.posts.index')->with('success', 'Đã xóa bài viết AI thành công!');
    }
}
