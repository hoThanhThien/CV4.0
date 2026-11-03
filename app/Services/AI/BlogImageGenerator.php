<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use App\Models\BlogPost;
use App\Services\AI\Support\VisualAssetRenderer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class BlogImageGenerator
{
    public function __construct(
        protected VisualAssetRenderer $renderer = new VisualAssetRenderer()
    ) {
    }

    /**
     * Available visual style presets for Admin UI & prompt art direction.
     *
     * @return array<string, string>
     */
    public static function stylePresets(): array
    {
        return [
            'modern_tech' => 'Modern Tech Glassmorphism (Hiện đại, công nghệ cao)',
            '3d_isometric' => '3D Isometric Render (Khối 3D trực quan, ánh sáng studio)',
            'minimalist_gradient' => 'Minimalist Dark Gradient (Tối giản, sang trọng)',
            'editorial_illustration' => 'Editorial Digital Art (Minh họa tạp chí công nghệ)',
            'cyberpunk_neon' => 'Futuristic Neon Glow (Tương lai, tương phản mạnh)',
        ];
    }

    /**
     * Generate & store both Cover Thumbnail (16:9) and OpenGraph Social Image (1200x630) for a BlogPost.
     *
     * @return array{
     *     success: bool,
     *     mode: string|null,
     *     model_used: string|null,
     *     thumbnail_path: string|null,
     *     og_path: string|null,
     *     message: string,
     *     error: string|null
     * }
     */
    public function generateForPost(
        BlogPost $post,
        ?AiProvider $provider = null,
        ?string $imageModelOverride = null,
        ?string $style = null,
        ?string $customPrompt = null
    ): array {
        @set_time_limit(240);

        $effectiveProvider = $this->resolveProvider($post, $provider);
        if (!$effectiveProvider || !$effectiveProvider->is_active) {
            return [
                'success' => false,
                'mode' => null,
                'model_used' => null,
                'thumbnail_path' => null,
                'og_path' => null,
                'message' => 'Không tìm thấy AI Provider nào đang hoạt động để tạo ảnh.',
                'error' => 'Không tìm thấy AI Provider nào đang hoạt động để tạo ảnh.',
            ];
        }

        $effectiveStyle = $style ?: ($post->contentConfig?->image_style ?: 'modern_tech');
        $effectiveImageModel = $imageModelOverride !== null
            ? trim($imageModelOverride)
            : trim((string) ($post->contentConfig?->image_model ?? ''));

        $promptToSave = trim((string) ($customPrompt !== null && trim($customPrompt) !== '' ? $customPrompt : ($post->cover_image_prompt ?? '')));
        if ($promptToSave === '') {
            $promptToSave = $this->synthesizeBasePrompt($post);
        }

        $fullPrompt = $this->buildArtDirectedPrompt($post, $promptToSave, $effectiveStyle);

        $disk = (string) config('ai.image.disk', 'public');
        $thumbDir = trim((string) config('ai.image.thumbnail_dir', 'blog/thumbnails'), '/');
        $ogDir = trim((string) config('ai.image.og_dir', 'blog/og'), '/');
        $aspectRatio = (string) config('ai.image.aspect_ratio', '16:9');
        $thumbW = max(640, (int) config('ai.image.thumbnail_width', 1280));
        $thumbH = max(360, (int) config('ai.image.thumbnail_height', 720));
        $ogW = max(600, (int) config('ai.image.og_width', 1200));
        $ogH = max(315, (int) config('ai.image.og_height', 630));

        try {
            $adapter = AIProviderFactory::make($effectiveProvider);
            $nativeResult = $adapter->generateImage(
                $effectiveProvider,
                $fullPrompt,
                $effectiveImageModel !== '' ? $effectiveImageModel : null,
                $aspectRatio
            );

            if ($nativeResult['success'] && !empty($nativeResult['binary'])) {
                $processed = $this->renderer->processNativeRasterImage(
                    $nativeResult['binary'],
                    (string) ($nativeResult['mime_type'] ?? 'image/png'),
                    $thumbW,
                    $thumbH,
                    $ogW,
                    $ogH
                );

                $stored = $this->persistGeneratedPair($post, $disk, $thumbDir, $ogDir, $processed['thumbnail'], $processed['og'], $promptToSave);
                $modelLabel = $nativeResult['model_used'] ?: $effectiveProvider->name;

                return [
                    'success' => true,
                    'mode' => 'native_ai',
                    'model_used' => $modelLabel,
                    'thumbnail_path' => $stored['thumbnail_path'],
                    'og_path' => $stored['og_path'],
                    'message' => "Đã tạo Thumbnail (16:9) & Ảnh OG ({$ogW}×{$ogH}) bằng AI ({$modelLabel})!",
                    'error' => null,
                ];
            }

            $errorCode = (string) ($nativeResult['error_code'] ?? '');
            $allowSmartFallback = (bool) config('ai.image.enable_smart_fallback', true)
                && in_array($errorCode, [
                    'FREE_TIER_IMAGE_RESTRICTED',
                    'IMAGE_MODEL_UNAVAILABLE',
                    'INSUFFICIENT_QUOTA',
                    'MODEL_NOT_FOUND',
                ], true);

            if ($allowSmartFallback) {
                $aiSpec = null;
                if (method_exists($adapter, 'generateVisualDesignSpec')) {
                    $aiSpec = $adapter->generateVisualDesignSpec($effectiveProvider, [
                        'title' => $post->title,
                        'category' => $post->category ?: 'Technology',
                        'style' => $effectiveStyle,
                        'prompt' => $promptToSave,
                    ]);
                }

                $mergedSpec = array_merge(
                    $aiSpec ?? [],
                    [
                        'title' => $post->title,
                        'category' => $post->category ?: 'Technology',
                        'style' => $effectiveStyle,
                        'author' => (string) config('ai.author_name', config('app.name', 'Tech Blog')),
                    ]
                );

                $designed = $this->renderer->renderAiDesignedAssets($mergedSpec, $thumbW, $thumbH, $ogW, $ogH);
                $stored = $this->persistGeneratedPair($post, $disk, $thumbDir, $ogDir, $designed['thumbnail'], $designed['og'], $promptToSave);

                $designerLabel = $aiSpec !== null
                    ? ($effectiveProvider->model ?: $effectiveProvider->name) . ' Visual Studio'
                    : 'AI Visual Studio';

                $reasonNote = $errorCode === 'FREE_TIER_IMAGE_RESTRICTED'
                    ? ' (API Key đang ở Free Tier giới hạn model ảnh gốc nên AI đã thiết kế bộ đồ họa Vector & OG PNG 1200×630 chuyên biệt)'
                    : ' (Tự động thiết kế bằng AI Visual Studio)';

                return [
                    'success' => true,
                    'mode' => 'ai_studio_design',
                    'model_used' => $designerLabel,
                    'thumbnail_path' => $stored['thumbnail_path'],
                    'og_path' => $stored['og_path'],
                    'message' => "Đã tạo Thumbnail & Ảnh OG ({$ogW}×{$ogH}) cho bài viết thành công{$reasonNote}!",
                    'error' => null,
                ];
            }

            $errMsg = $nativeResult['error'] ?: 'Không thể tạo ảnh từ AI Provider.';
            return [
                'success' => false,
                'mode' => null,
                'model_used' => $nativeResult['model_used'] ?? null,
                'thumbnail_path' => null,
                'og_path' => null,
                'message' => $errMsg,
                'error' => $errMsg,
            ];
        } catch (Throwable $e) {
            Log::warning('BlogImageGenerator failed', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'mode' => null,
                'model_used' => null,
                'thumbnail_path' => null,
                'og_path' => null,
                'message' => 'Lỗi tạo ảnh AI: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Save both generated files to disk, remove superseded generated files, and update BlogPost columns.
     *
     * @param array{bytes: string, extension: string, mime_type: string} $thumbAsset
     * @param array{bytes: string, extension: string, mime_type: string} $ogAsset
     * @return array{thumbnail_path: string, og_path: string}
     */
    protected function persistGeneratedPair(
        BlogPost $post,
        string $disk,
        string $thumbDir,
        string $ogDir,
        array $thumbAsset,
        array $ogAsset,
        string $promptToSave
    ): array {
        $slugBase = Str::limit(Str::slug($post->slug ?: $post->title) ?: ('post-' . $post->id), 60, '');
        $uniqueSuffix = now()->format('YmdHis') . '-' . strtolower(Str::random(5));

        $thumbPath = "{$thumbDir}/{$slugBase}-{$uniqueSuffix}.{$thumbAsset['extension']}";
        $ogPath = "{$ogDir}/{$slugBase}-og-{$uniqueSuffix}.{$ogAsset['extension']}";

        $oldImage = $post->image;
        $oldOgImage = $post->og_image;

        Storage::disk($disk)->put($thumbPath, $thumbAsset['bytes']);
        Storage::disk($disk)->put($ogPath, $ogAsset['bytes']);

        if (!empty($oldImage) && $oldImage !== $thumbPath && !str_starts_with($oldImage, 'http')) {
            Storage::disk($disk)->delete($oldImage);
        }
        if (!empty($oldOgImage) && $oldOgImage !== $ogPath && $oldOgImage !== $thumbPath && !str_starts_with($oldOgImage, 'http')) {
            Storage::disk($disk)->delete($oldOgImage);
        }

        $post->update([
            'image' => $thumbPath,
            'og_image' => $ogPath,
            'cover_image_prompt' => $promptToSave,
        ]);

        return [
            'thumbnail_path' => $thumbPath,
            'og_path' => $ogPath,
        ];
    }

    protected function resolveProvider(BlogPost $post, ?AiProvider $explicitProvider = null): ?AiProvider
    {
        if ($explicitProvider && $explicitProvider->is_active) {
            return $explicitProvider;
        }

        if ($post->ai_provider_id && $post->provider && $post->provider->is_active) {
            return $post->provider;
        }

        if ($post->contentConfig) {
            $cfgProvider = $post->contentConfig->getEffectiveProvider();
            if ($cfgProvider && $cfgProvider->is_active) {
                return $cfgProvider;
            }
        }

        return AiProvider::active()->default()->first() ?? AiProvider::active()->first();
    }

    public function synthesizeBasePrompt(BlogPost $post): string
    {
        $category = trim((string) ($post->category ?: 'Software Engineering & Technology'));
        $title = trim((string) $post->title);
        $excerpt = Str::limit(trim(strip_tags((string) ($post->excerpt ?: $post->content))), 160);

        return "Editorial hero illustration representing '{$title}' in the domain of {$category}. Context: {$excerpt}";
    }

    public function buildArtDirectedPrompt(BlogPost $post, string $basePrompt, string $style): string
    {
        $styleDirective = match ($style) {
            '3d_isometric' => '3D isometric architectural tech render, smooth Octane-style studio lighting, subtle glass and metallic materials, rich depth of field',
            'minimalist_gradient' => 'Minimalist sleek abstract geometric composition, dark slate background with refined luminous indigo and cyan gradients, Apple/Linear inspired aesthetic',
            'editorial_illustration' => 'Modern editorial digital illustration for a premier software engineering publication, clean vector-inspired forms, balanced composition',
            'cyberpunk_neon' => 'Futuristic high-tech visualization, deep midnight backdrop with crisp electric cyan and magenta data light trails, cinematic contrast',
            default => 'Modern high-tech glassmorphism & clean 3D geometric illustration, deep slate-indigo atmosphere with vibrant cyan and violet accents, studio rim lighting',
        };

        $category = trim((string) ($post->category ?: 'Technology'));

        return "{$basePrompt}. Visual style: {$styleDirective}. Topic category: {$category}. "
            . '16:9 widescreen hero composition with the primary visual subject centered so both 16:9 thumbnail and 1.91:1 OpenGraph crops frame cleanly. '
            . 'No watermarks, no cluttered or garbled text, ultra-clean professional tech blog cover.';
    }
}
