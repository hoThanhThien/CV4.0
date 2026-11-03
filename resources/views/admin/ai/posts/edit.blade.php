@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết AI — ' . $post->title)

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-edit" style="color:var(--accent)"></i> Chỉnh sửa bài viết AI</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Bạn có thể chỉnh sửa thủ công nội dung, SEO và trạng thái bài viết trước hoặc sau khi đăng.
        </p>
    </div>
    <div style="display:flex; gap:0.5rem">
        <a href="{{ route('admin.ai.posts.preview', $post->id) }}" class="btn btn-secondary">
            <i class="fas fa-eye"></i> Xem trước
        </a>
        <a href="{{ route('admin.ai.posts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Danh sách bài viết
        </a>
    </div>
</div>

<div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:2rem; max-width:1000px">
    <form method="POST" action="{{ route('admin.ai.posts.update', $post->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="margin-bottom:1.5rem">
            <label for="title" style="display:block; font-weight:600; margin-bottom:0.5rem">Tiêu đề bài viết <span style="color:var(--danger)">*</span></label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $post->title) }}" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="slug" style="display:block; font-weight:600; margin-bottom:0.5rem">Đường dẫn tĩnh (Slug) <span style="color:var(--danger)">*</span></label>
            <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $post->slug) }}" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="excerpt" style="display:block; font-weight:600; margin-bottom:0.5rem">Mô tả tóm tắt (Excerpt)</label>
            <textarea id="excerpt" name="excerpt" rows="3" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="content" style="display:block; font-weight:600; margin-bottom:0.5rem">Nội dung bài viết (HTML) <span style="color:var(--danger)">*</span></label>
            <textarea id="content" name="content" rows="18" class="form-control" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px; font-family:monospace; font-size:0.9rem; line-height:1.5">{{ old('content', $post->content) }}</textarea>
        </div>

        <!-- Thumbnail & OpenGraph Visual Assets -->
        <div style="margin-bottom:1.5rem; padding:1.25rem; background:linear-gradient(135deg, rgba(99,102,241,0.05), rgba(6,182,212,0.05)); border:1px solid rgba(99,102,241,0.22); border-radius:10px">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem">
                <h4 style="font-size:0.95rem; font-weight:800; margin:0; color:var(--accent)">
                    <i class="fas fa-images"></i> Thumbnail bài viết (16:9) & Ảnh OpenGraph (1200×630)
                </h4>
                <button type="button" class="btn btn-primary btn-sm" onclick="submitQuickAiImage()">
                    <i class="fas fa-magic"></i> Tạo mới bằng AI ngay
                </button>
            </div>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:1.25rem; margin-bottom:1rem">
                <!-- Thumbnail 16:9 -->
                <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:10px; padding:0.9rem">
                    <div style="font-weight:700; font-size:0.84rem; margin-bottom:0.5rem">1. Thumbnail bài viết (16:9)</div>
                    @if($post->thumbnail_url)
                        <img src="{{ $post->thumbnail_url }}" alt="Thumbnail" style="width:100%; aspect-ratio:16/9; object-fit:cover; border-radius:8px; border:1px solid var(--border); margin-bottom:0.6rem">
                    @else
                        <div style="width:100%; aspect-ratio:16/9; border-radius:8px; border:1px dashed var(--border); display:flex; align-items:center; justify-content:center; color:var(--text-muted); font-size:0.82rem; margin-bottom:0.6rem">Chưa có Thumbnail</div>
                    @endif
                    <label style="display:block; font-size:0.78rem; color:var(--text-muted); margin-bottom:0.25rem">Tải ảnh thay thế thủ công (tùy chọn):</label>
                    <input type="file" name="image" accept="image/*" class="form-control" style="font-size:0.82rem">
                </div>

                <!-- OG Image 1200x630 -->
                <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:10px; padding:0.9rem">
                    <div style="font-weight:700; font-size:0.84rem; margin-bottom:0.5rem">2. Ảnh OpenGraph mạng xã hội (1200×630)</div>
                    <img src="{{ $post->og_image_url }}" alt="OG Image" style="width:100%; aspect-ratio:1200/630; object-fit:cover; border-radius:8px; border:1px solid var(--border); margin-bottom:0.6rem">
                    <label style="display:block; font-size:0.78rem; color:var(--text-muted); margin-bottom:0.25rem">Tải ảnh OG thay thế thủ công (tùy chọn):</label>
                    <input type="file" name="og_image" accept="image/*" class="form-control" style="font-size:0.82rem">
                </div>
            </div>

            <div>
                <label for="cover_image_prompt" style="display:block; font-weight:600; margin-bottom:0.35rem; font-size:0.85rem">Prompt mô tả hình ảnh cho AI (Cover Image Prompt)</label>
                <textarea id="cover_image_prompt" name="cover_image_prompt" rows="2" class="form-control" placeholder="Mô tả ý tưởng hình ảnh minh họa để AI sinh Thumbnail & Ảnh OG..." style="width:100%; padding:0.6rem; border:1px solid var(--border); border-radius:8px; font-size:0.85rem">{{ old('cover_image_prompt', $post->cover_image_prompt) }}</textarea>
            </div>
        </div>

        <!-- SEO Section -->
        <div style="margin-bottom:1.5rem; padding:1.25rem; background:var(--bg); border:1px solid var(--border); border-radius:10px">
            <h4 style="font-size:0.95rem; font-weight:700; margin-bottom:1rem; color:var(--accent)"><i class="fas fa-search"></i> Tối ưu SEO</h4>
            <div style="margin-bottom:1rem">
                <label for="meta_title" style="display:block; font-weight:600; margin-bottom:0.35rem; font-size:0.85rem">SEO Title</label>
                <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title) }}" style="width:100%; padding:0.6rem; border:1px solid var(--border); border-radius:8px">
            </div>
            <div style="margin-bottom:1rem">
                <label for="meta_description" style="display:block; font-weight:600; margin-bottom:0.35rem; font-size:0.85rem">SEO Meta Description</label>
                <textarea id="meta_description" name="meta_description" rows="2" class="form-control" style="width:100%; padding:0.6rem; border:1px solid var(--border); border-radius:8px">{{ old('meta_description', $post->meta_description) }}</textarea>
            </div>
            <div>
                <label for="keywords" style="display:block; font-weight:600; margin-bottom:0.35rem; font-size:0.85rem">Từ khóa (Keywords)</label>
                <input type="text" id="keywords" name="keywords" class="form-control" value="{{ old('keywords', $post->keywords) }}" style="width:100%; padding:0.6rem; border:1px solid var(--border); border-radius:8px">
            </div>
        </div>

        <!-- Categories & Tags -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem">
            <div>
                <label for="category" style="display:block; font-weight:600; margin-bottom:0.5rem">Danh mục (Category)</label>
                <input type="text" id="category" name="category" class="form-control" value="{{ old('category', $post->category) }}" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
            <div>
                <label for="tags" style="display:block; font-weight:600; margin-bottom:0.5rem">Tags (cách nhau bởi dấu phẩy)</label>
                <input type="text" id="tags" name="tags" class="form-control" value="{{ old('tags', is_array($post->tags) ? implode(', ', $post->tags) : $post->tags) }}" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
        </div>

        <!-- Status & Schedule -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:2rem; padding:1.25rem; background:var(--bg); border:1px solid var(--border); border-radius:10px">
            <div>
                <label for="status" style="display:block; font-weight:600; margin-bottom:0.5rem">Trạng thái bài viết</label>
                <select id="status" name="status" class="form-control" onchange="onStatusChange(this.value)" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Nháp (Draft)</option>
                    <option value="pending_review" {{ old('status', $post->status) === 'pending_review' ? 'selected' : '' }}>Chờ duyệt (Pending Review)</option>
                    <option value="scheduled" {{ old('status', $post->status) === 'scheduled' ? 'selected' : '' }}>Đã lên lịch (Scheduled)</option>
                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Xuất bản công khai (Published)</option>
                </select>
            </div>
            <div id="scheduled_at_box" style="display:{{ old('status', $post->status) === 'scheduled' ? 'block' : 'none' }}">
                <label for="scheduled_at" style="display:block; font-weight:600; margin-bottom:0.5rem">Thời gian hẹn đăng (Giờ Việt Nam)</label>
                <input type="datetime-local" id="scheduled_at" name="scheduled_at" class="form-control"
                    value="{{ $post->published_at ? $post->published_at->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d\TH:i') : now('Asia/Ho_Chi_Minh')->addHours(2)->format('Y-m-d\TH:i') }}"
                    style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
        </div>

        <div style="display:flex; gap:1rem; justify-content:flex-end">
            <a href="{{ route('admin.ai.posts.preview', $post->id) }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu bài viết
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function onStatusChange(status) {
        document.getElementById('scheduled_at_box').style.display = (status === 'scheduled') ? 'block' : 'none';
    }
</script>
@endsection
