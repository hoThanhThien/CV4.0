@extends('layouts.admin')

@section('title', 'Edit Blog Post')

@section('content')
<div class="page-header">
    <h1>Edit Blog Post</h1>
    <div style="display:flex; gap:0.5rem">
        @if($post->published)
        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-eye"></i> Preview
        </a>
        @endif
        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.blog.update', $post->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div style="display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start">
        <!-- Main -->
        <div>
            <div class="form-card">
                <div class="form-group">
                    <label class="form-label" for="title">Post Title *</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                    @error('title')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>
                <div style="margin-bottom:1.25rem; padding:0.75rem 1rem; border-radius:10px; background:rgba(255,255,255,0.03); border:1px solid var(--border)">
                    <div style="font-size:0.78rem; color:var(--text-muted)">Slug: <code style="color:var(--accent-light)">/{{ $post->slug }}</code></div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="excerpt">Excerpt / Summary</label>
                    <textarea id="excerpt" name="excerpt" class="form-control" style="min-height:80px">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="content">Content *</label>
                    <textarea id="content" name="content" class="form-control" style="min-height:400px" required>{{ old('content', $post->content) }}</textarea>
                    @error('content')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="form-card" style="margin-bottom:1.25rem">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; margin-bottom:0.75rem">
                    <label class="form-label" style="margin:0">Thumbnail (16:9) & OG (1200×630)</label>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="triggerBlogAiImage()" style="color:var(--accent); font-weight:700; padding:0.25rem 0.6rem; font-size:0.76rem">
                        <i class="fas fa-magic"></i> Tạo bằng AI
                    </button>
                </div>

                <div class="form-group">
                    <div style="font-size:0.78rem; color:var(--text-muted); margin-bottom:0.35rem">Cover Thumbnail (16:9)</div>
                    @if($post->thumbnail_url)
                        <img src="{{ $post->thumbnail_url }}" style="width:100%; aspect-ratio:16/9; object-fit:cover; border-radius:10px; border:1px solid var(--border); margin-bottom:0.65rem">
                    @else
                        <div style="background:rgba(255,255,255,0.04); border-radius:10px; height:100px; display:flex; align-items:center; justify-content:center; color:var(--text-muted); margin-bottom:0.65rem; border:1px solid var(--border); font-size:0.88rem">
                            No thumbnail
                        </div>
                    @endif
                    <label class="form-label" for="image" style="font-size:0.8rem">Replace Thumbnail</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                    <div id="image-preview" style="margin-top:0.75rem; display:none">
                        <img id="preview-img" style="width:100%; border-radius:10px; border:1px solid var(--border)">
                    </div>
                </div>

                <div class="form-group" style="padding-top:0.75rem; border-top:1px solid var(--border)">
                    <div style="font-size:0.78rem; color:var(--text-muted); margin-bottom:0.35rem">OpenGraph Social Image (1200×630)</div>
                    <img src="{{ $post->og_image_url }}" style="width:100%; aspect-ratio:1200/630; object-fit:cover; border-radius:10px; border:1px solid var(--border); margin-bottom:0.65rem">
                    <label class="form-label" for="og_image" style="font-size:0.8rem">Replace OG Image (optional)</label>
                    <input type="file" id="og_image" name="og_image" class="form-control" accept="image/*">
                </div>

                <div class="form-group" style="padding-top:0.75rem; border-top:1px solid var(--border)">
                    <label class="form-label" for="cover_image_prompt" style="font-size:0.8rem">AI Image Prompt (optional)</label>
                    <textarea id="cover_image_prompt" name="cover_image_prompt" rows="2" class="form-control" style="min-height:60px; font-size:0.82rem" placeholder="Gợi ý ý tưởng hình ảnh cho AI...">{{ old('cover_image_prompt', $post->cover_image_prompt) }}</textarea>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="published" name="published" value="1" {{ old('published', $post->published) ? 'checked' : '' }}>
                    <label for="published">Published</label>
                </div>
                @if($post->published_at)
                <div style="margin-top:0.75rem; font-size:0.8rem; color:var(--text-muted)">
                    <i class="fas fa-calendar"></i> Published {{ $post->published_at->format('M d, Y') }}
                </div>
                @endif
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </div>
</form>

<form id="blog-ai-image-form" method="POST" action="{{ route('admin.blog.generate-image', $post->id) }}" style="display:none"
    data-confirm-type="ai"
    data-confirm-icon="fa-image"
    data-confirm-title="Tạo Thumbnail & Ảnh OG bằng AI?"
    data-confirm="AI sẽ tự động sinh bộ Thumbnail (16:9) và Ảnh OpenGraph (1200×630) cho bài viết này."
    data-confirm-btn="<i class='fas fa-magic'></i> Tạo ngay"
    data-loading-title="AI đang thiết kế Thumbnail & Ảnh OG..."
    data-loading-desc="Hệ thống đang tạo ảnh minh họa 16:9 và ảnh chia sẻ mạng xã hội OpenGraph 1200×630...">
    @csrf
    <input type="hidden" name="custom_prompt" id="blog_ai_custom_prompt">
</form>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function triggerBlogAiImage() {
        const promptEl = document.getElementById('cover_image_prompt');
        const hiddenEl = document.getElementById('blog_ai_custom_prompt');
        const form = document.getElementById('blog-ai-image-form');
        if (promptEl && hiddenEl) {
            hiddenEl.value = promptEl.value;
        }
        window.openAdminConfirmForm(form);
    }
</script>
@endsection
