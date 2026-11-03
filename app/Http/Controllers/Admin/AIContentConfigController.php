<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiContentConfig;
use App\Models\AiProvider;
use App\Services\AI\BlogContentGenerator;
use Illuminate\Http\Request;
use Throwable;

class AIContentConfigController extends Controller
{
    public function index()
    {
        $configs = AiContentConfig::with('provider')->latest()->get();
        return view('admin.ai.configs.index', compact('configs'));
    }

    public function create()
    {
        $providers = AiProvider::active()->get();
        return view('admin.ai.configs.create', compact('providers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'ai_provider_id' => 'nullable|exists:ai_providers,id',
            'model_override' => 'nullable|string|max:100',
            'topic_mode' => 'required|in:admin_topic,ai_select,ai_research',
            'main_topic' => 'required|string|max:255',
            'keywords' => 'nullable|string',
            'language' => 'required|string|max:20',
            'writing_tone' => 'required|string|max:50',
            'target_audience' => 'required|string|max:150',
            'article_length' => 'required|in:short,medium,long',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string',
            'custom_instructions' => 'nullable|string',
            'include_faq' => 'boolean',
            'include_sources' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['include_faq'] = $request->boolean('include_faq', true);
        $validated['include_sources'] = $request->boolean('include_sources', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Convert tags from comma-separated string to array
        if (!empty($validated['tags'])) {
            $validated['tags'] = array_values(array_filter(array_map('trim', explode(',', $validated['tags']))));
        } else {
            $validated['tags'] = [];
        }

        AiContentConfig::create($validated);

        return redirect()->route('admin.ai.configs.index')->with('success', 'Đã lưu cấu hình nội dung AI!');
    }

    public function edit($id)
    {
        $config = AiContentConfig::findOrFail($id);
        $providers = AiProvider::active()->get();
        return view('admin.ai.configs.edit', compact('config', 'providers'));
    }

    public function update(Request $request, $id)
    {
        $config = AiContentConfig::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'ai_provider_id' => 'nullable|exists:ai_providers,id',
            'model_override' => 'nullable|string|max:100',
            'topic_mode' => 'required|in:admin_topic,ai_select,ai_research',
            'main_topic' => 'required|string|max:255',
            'keywords' => 'nullable|string',
            'language' => 'required|string|max:20',
            'writing_tone' => 'required|string|max:50',
            'target_audience' => 'required|string|max:150',
            'article_length' => 'required|in:short,medium,long',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string',
            'custom_instructions' => 'nullable|string',
            'include_faq' => 'boolean',
            'include_sources' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['include_faq'] = $request->boolean('include_faq', true);
        $validated['include_sources'] = $request->boolean('include_sources', false);
        $validated['is_active'] = $request->boolean('is_active', true);

        if (!empty($validated['tags'])) {
            $validated['tags'] = array_values(array_filter(array_map('trim', explode(',', $validated['tags']))));
        } else {
            $validated['tags'] = [];
        }

        $config->update($validated);

        return redirect()->route('admin.ai.configs.index')->with('success', 'Đã cập nhật cấu hình nội dung!');
    }

    public function destroy($id)
    {
        $config = AiContentConfig::findOrFail($id);
        $config->delete();

        return redirect()->route('admin.ai.configs.index')->with('success', 'Đã xóa cấu hình nội dung!');
    }

    /**
     * Trigger immediate single post generation for testing.
     */
    public function generateNow($id, BlogContentGenerator $generator)
    {
        @set_time_limit(300);

        $config = AiContentConfig::findOrFail($id);
        $provider = $config->getEffectiveProvider();

        $runLog = \App\Models\BlogAutomationRun::create([
            'automation_id' => null,
            'content_config_id' => $config->id,
            'ai_provider_id' => $provider ? $provider->id : null,
            'provider_name' => $provider ? $provider->name : 'Unknown',
            'model_used' => $config->getEffectiveModel(),
            'topic_processed' => $config->main_topic ?: 'Manual Test Topic',
            'articles_requested' => 1,
            'articles_created' => 0,
            'articles_failed' => 0,
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $result = $generator->generatePost($config, 'manual_approval', null, $runLog);

            if ($result['success']) {
                $post = $result['post'];
                return redirect()->route('admin.ai.posts.preview', $post->id)
                    ->with('success', "AI đã tạo thành công bài viết: '{$post->title}'! Bạn có thể xem trước và duyệt bài.");
            }

            return back()->with('error', 'Tạo bài viết thất bại: ' . $result['error']);
        } catch (Throwable $e) {
            $runLog->update([
                'articles_failed' => 1,
                'status' => 'failed',
                'error_details' => $e->getMessage(),
                'completed_at' => now(),
            ]);
            return back()->with('error', 'Lỗi ngoại lệ khi tạo bài viết: ' . $e->getMessage());
        }
    }
}
