<?php

namespace App\Services\AI;

use App\Models\AiContentConfig;
use App\Models\AiProvider;
use App\Models\BlogPost;
use App\Models\BlogAutomationRun;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Throwable;

class BlogContentGenerator
{
    /**
     * Generate and save a blog post from configuration.
     *
     * @param AiContentConfig $config
     * @param string $publishingMode 'manual_approval', 'auto_schedule', 'auto_publish'
     * @param Carbon|null $scheduledAt Target publish time if auto_schedule
     * @param BlogAutomationRun|null $runLog Optional run log tracking
     * @return array ['success' => bool, 'post' => ?BlogPost, 'error' => ?string]
     */
    public function generatePost(
        AiContentConfig $config,
        string $publishingMode = 'manual_approval',
        ?Carbon $scheduledAt = null,
        ?BlogAutomationRun $runLog = null
    ): array {
        @set_time_limit(300);

        $provider = $config->getEffectiveProvider();
        if (!$provider || !$provider->is_active) {
            $err = 'Không tìm thấy AI Provider nào đang hoạt động cho cấu hình này.';
            $this->updateRunLogFailure($runLog, $err);
            return ['success' => false, 'post' => null, 'error' => $err];
        }

        $adapter = AIProviderFactory::make($provider);
        $model = $config->getEffectiveModel();

        // 1. Build prompt
        $recentPosts = BlogPost::latest()->take(10)->pluck('title')->toArray();
        $prompts = $this->buildPrompts($config, $recentPosts);

        // 2. Call AI API
        $response = $adapter->generateBlogContent(
            $provider,
            $prompts['system'],
            $prompts['user'],
            $config->model_override
        );

        if (!$response['success']) {
            $err = $response['error'] ?: 'AI Provider không thể sinh nội dung.';
            $this->updateRunLogFailure($runLog, $err);
            return ['success' => false, 'post' => null, 'error' => $err];
        }

        $data = $response['data'];
        if (!is_array($data) || empty($data['title']) || empty($data['content'])) {
            $err = 'AI trả về dữ liệu không đúng định dạng JSON hoặc thiếu title/content.';
            $this->updateRunLogFailure($runLog, $err);
            return ['success' => false, 'post' => null, 'error' => $err];
        }

        // 3. Deduplication check
        $dupCheck = $this->checkDuplication($data['title']);
        if ($dupCheck['is_duplicate']) {
            $err = "Trùng lặp nội dung: {$dupCheck['reason']}";
            $this->updateRunLogFailure($runLog, $err);
            return ['success' => false, 'post' => null, 'error' => $err];
        }

        // 4. Sanitize HTML content
        $sanitizedContent = $this->sanitizeHtml($data['content']);

        // 5. Slug generation
        $slug = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $uniqueSlug = BlogPost::generateSlug($slug);

        // 6. Determine publication status
        $status = BlogPost::STATUS_DRAFT;
        $isPublished = false;
        $publishedAt = null;

        if ($publishingMode === 'auto_publish') {
            $status = BlogPost::STATUS_PUBLISHED;
            $isPublished = true;
            $publishedAt = now();
        } elseif ($publishingMode === 'auto_schedule') {
            $status = BlogPost::STATUS_SCHEDULED;
            $isPublished = false;
            $publishedAt = $scheduledAt ?: now()->addHours(2);
        } else { // manual_approval
            $status = BlogPost::STATUS_PENDING_REVIEW;
            $isPublished = false;
            $publishedAt = null;
        }

        // 7. Save BlogPost to Database
        try {
            $post = BlogPost::create([
                'title' => trim($data['title']),
                'slug' => $uniqueSlug,
                'content' => $sanitizedContent,
                'excerpt' => $data['excerpt'] ?? Str::limit(strip_tags($sanitizedContent), 220),
                'published' => $isPublished,
                'published_at' => $publishedAt,
                'scheduled_at' => ($status === BlogPost::STATUS_SCHEDULED) ? $publishedAt : null,
                'status' => $status,
                'ai_generated' => true,
                'ai_provider_id' => $provider->id,
                'ai_config_id' => $config->id,
                'meta_title' => $data['meta_title'] ?? $data['title'],
                'meta_description' => $data['meta_description'] ?? ($data['excerpt'] ?? null),
                'keywords' => is_array($data['keywords'] ?? null) ? implode(', ', $data['keywords']) : ($data['keywords'] ?? null),
                'category' => $data['category'] ?? $config->category,
                'tags' => $data['tags'] ?? $config->tags,
                'faq' => $data['faq'] ?? null,
                'sources' => $data['sources'] ?? null,
                'cover_image_prompt' => $data['cover_image_prompt'] ?? null,
            ]);

            // Update run log
            if ($runLog) {
                $createdIds = (array)($runLog->created_post_ids ?: []);
                $createdIds[] = $post->id;

                $runLog->update([
                    'articles_created' => $runLog->articles_created + 1,
                    'prompt_tokens' => $runLog->prompt_tokens + ($response['prompt_tokens'] ?? 0),
                    'completion_tokens' => $runLog->completion_tokens + ($response['completion_tokens'] ?? 0),
                    'total_tokens' => $runLog->total_tokens + ($response['total_tokens'] ?? 0),
                    'created_post_ids' => $createdIds,
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }

            return ['success' => true, 'post' => $post, 'error' => null];
        } catch (Throwable $e) {
            $err = 'Lỗi lưu bài viết vào database: ' . $e->getMessage();
            $this->updateRunLogFailure($runLog, $err);
            return ['success' => false, 'post' => null, 'error' => $err];
        }
    }

    /**
     * Check duplication against recent posts.
     */
    public function checkDuplication(string $title): array
    {
        $normalizedTitle = mb_strtolower(trim($title), 'UTF-8');

        // 1. Exact or near title match
        $existing = BlogPost::whereRaw('LOWER(title) = ?', [$normalizedTitle])->first();
        if ($existing) {
            return [
                'is_duplicate' => true,
                'reason' => "Tiêu đề '{$title}' đã tồn tại (Bài #{$existing->id}).",
            ];
        }

        // 2. Similarity check on recent 20 posts
        $recentPosts = BlogPost::latest()->take(20)->get(['id', 'title']);
        foreach ($recentPosts as $p) {
            similar_text(mb_strtolower($p->title, 'UTF-8'), $normalizedTitle, $percent);
            if ($percent > 85.0) {
                return [
                    'is_duplicate' => true,
                    'reason' => "Tiêu đề có độ tương đồng {$percent}% với bài viết '#{$p->id}: {$p->title}'.",
                ];
            }
        }

        return ['is_duplicate' => false, 'reason' => null];
    }

    /**
     * Build strict system and user prompt for JSON structured generation.
     */
    public function buildPrompts(AiContentConfig $config, array $recentTitles = []): array
    {
        $langName = match ($config->language) {
            'vi' => 'Vietnamese (Tiếng Việt chuẩn, tự nhiên, văn phong công nghệ hiện đại)',
            'en' => 'English (US Professional)',
            default => 'Vietnamese',
        };

        $lengthDesc = match ($config->article_length) {
            'short' => 'Khoảng 700 - 1000 từ, súc tích, đi thẳng vào trọng tâm',
            'long' => 'Khoảng 2000 - 3000 từ, chuyên sâu, phân tích kỹ thuật toàn diện, ví dụ thực tế',
            default => 'Khoảng 1200 - 1700 từ, cân bằng lý thuyết và thực tiễn, có code demo hoặc hướng dẫn cụ thể',
        };

        $recentAvoid = !empty($recentTitles)
            ? "Tuyệt đối KHÔNG viết trùng tiêu đề hoặc chủ đề của các bài viết gần đây sau:\n- " . implode("\n- ", $recentTitles)
            : '';

        $keywordsStr = implode(', ', $config->keywords_array);
        $authorName = trim((string) config('ai.author_name', config('app.name', 'Tech Blog')));
        $siteUrl = trim((string) config('ai.site_url', config('app.url', '')));
        $blogIdentity = $siteUrl !== '' ? "{$authorName} ({$siteUrl})" : $authorName;

        $systemPrompt = <<<PROMPT
You are a Senior Technical Writer, Software Architect, and SEO Specialist for the personal portfolio & tech blog of {$blogIdentity}.

Your mission:
Write an authoritative, engaging, and in-depth tech blog post based on the configuration provided.

CRITICAL INSTRUCTIONS:
1. You MUST output ONLY a single valid RFC 8259 JSON object without any markdown code fences (never wrap in ```json, and never use ``` inside the HTML `content` field).
2. The language of the article MUST be {$langName}.
3. Tone of voice: {$config->writing_tone}.
4. Target audience: {$config->target_audience}.
5. Content length: {$lengthDesc}.
6. Content structure:
   - Must use clean semantic HTML for the `content` field.
   - Inside HTML attributes in `content`, prefer single quotes (e.g. `<a href='https://...'>`, `<code class='language-php'>`) and properly escape any backslashes (`\\\\`) or double quotes (`\\\"`) so the JSON string is 100% valid.
   - Use `<h2>` for main sections, `<h3>` for subsections.
   - Use `<p>` for paragraphs.
   - Use `<ul>` or `<ol>` with `<li>` for key takeaways and lists.
   - Use `<pre><code>` for code snippets (specify clean code examples; HTML-escape `<` and `>` inside `<code>` when needed).
   - Use `<blockquote>` for important notes or quotes.
   - DO NOT include `<h1>`, `<html>`, `<head>`, `<body>`, `<script>`, or `<iframe>`.
7. SEO requirements:
   - `meta_title`: Under 60 characters, compelling, click-worthy.
   - `meta_description`: 140-160 characters, summarising benefits.
   - Naturally integrate keywords without keyword stuffing.
8. {$recentAvoid}
9. If custom instructions are provided, follow them strictly.

Output JSON Schema:
{
  "title": "Compelling Title",
  "slug": "url-friendly-slug-without-accents",
  "excerpt": "Engaging summary paragraph (150-200 chars)",
  "content": "<h2>...</h2><p>...</p>",
  "meta_title": "SEO Title",
  "meta_description": "SEO Meta Description",
  "keywords": ["keyword 1", "keyword 2"],
  "category": "{$config->category}",
  "tags": ["Tag1", "Tag2"],
  "faq": [
    {"question": "Common Question 1?", "answer": "Answer 1"},
    {"question": "Common Question 2?", "answer": "Answer 2"}
  ],
  "sources": [
    {"title": "Documentation / Authoritative Source", "url": "https://..."}
  ],
  "cover_image_prompt": "Photorealistic or modern 3D tech illustration prompt for DALL-E / Midjourney representing the topic"
}
PROMPT;

        $topicModeDesc = '';
        if ($config->topic_mode === 'admin_topic') {
            $topicModeDesc = "Chủ đề chính do Admin chỉ định: '{$config->main_topic}'";
        } elseif ($config->topic_mode === 'ai_select') {
            $topicModeDesc = "Hãy tự động chọn một chủ đề nóng hổi, thực tiễn và hấp dẫn nhất xoay quanh lĩnh vực: '{$config->main_topic}' và nhóm từ khóa: '{$keywordsStr}'.";
        } else { // ai_research
            $topicModeDesc = "Hãy nghiên cứu và đề xuất một chủ đề đột phá mới nhất, giải quyết bài toán kỹ thuật thực tế liên quan đến: '{$config->main_topic}'.";
        }

        $userPrompt = <<<USER
Yêu cầu tạo bài viết:
- {$topicModeDesc}
- Từ khóa cần khai thác: {$keywordsStr}
- Danh mục: {$config->category}
- Hướng dẫn đặc thù của Admin: {$config->custom_instructions}
- Bao gồm FAQ: {$config->include_faq}
- Bao gồm Nguồn tham khảo: {$config->include_sources}

Hãy tạo toàn bộ bài viết theo cấu trúc JSON được quy định!
USER;

        return [
            'system' => $systemPrompt,
            'user' => $userPrompt,
        ];
    }

    /**
     * Sanitize HTML content to prevent XSS / dangerous elements.
     */
    public function sanitizeHtml(string $html): string
    {
        // Remove script tags and contents
        $clean = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        // Remove iframe tags
        $clean = preg_replace('#<iframe(.*?)>(.*?)</iframe>#is', '', $clean);
        // Remove style tags
        $clean = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $clean);
        // Remove object / embed
        $clean = preg_replace('#<object(.*?)>(.*?)</object>#is', '', $clean);
        $clean = preg_replace('#<embed(.*?)>(.*?)</embed>#is', '', $clean);
        // Remove javascript: links
        $clean = preg_replace('#href=["\']javascript:(.*?)["\']#is', 'href="#"', $clean);
        // Remove inline event handlers (onclick, onload, onerror, etc.)
        $clean = preg_replace('#\son[a-zA-Z]+=["\'](.*?)["\']#is', '', $clean);

        // Strip non-allowed tags
        $allowedTags = '<h2><h3><h4><h5><h6><p><br><hr><ul><ol><li><strong><em><b><i><u><code><pre><blockquote><a><table><thead><tbody><tr><th><td><span><div><img>';
        return strip_tags($clean, $allowedTags);
    }

    protected function updateRunLogFailure(?BlogAutomationRun $runLog, string $error): void
    {
        if ($runLog) {
            $runLog->update([
                'articles_failed' => $runLog->articles_failed + 1,
                'status' => 'failed',
                'error_details' => $error,
                'completed_at' => now(),
            ]);
        }
    }
}
