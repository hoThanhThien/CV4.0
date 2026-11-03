@extends('layouts.admin')

@section('title', 'Sửa cấu hình nội dung — ' . $config->name)

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-edit" style="color:var(--accent)"></i> Sửa cấu hình nội dung</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Cập nhật đề tài, từ khóa, văn phong và chỉ dẫn chuyên biệt cho bài viết AI.
        </p>
    </div>
    <a href="{{ route('admin.ai.configs.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div style="max-width:900px; background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:2rem">
    <form method="POST" action="{{ route('admin.ai.configs.update', $config->id) }}">
        @csrf
        @method('PUT')

        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem; margin-bottom:1.5rem">
            <div>
                <label for="name" style="display:block; font-weight:600; margin-bottom:0.5rem">Tên cấu hình <span style="color:var(--danger)">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $config->name) }}" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
            <div>
                <label for="category" style="display:block; font-weight:600; margin-bottom:0.5rem">Danh mục (Category) <span style="color:var(--danger)">*</span></label>
                <input type="text" id="category" name="category" class="form-control" value="{{ old('category', $config->category) }}" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem">
            <div>
                <label for="ai_provider_id" style="display:block; font-weight:600; margin-bottom:0.5rem">AI Provider</label>
                <select id="ai_provider_id" name="ai_provider_id" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                    <option value="">— Sử dụng Provider mặc định hệ thống —</option>
                    @foreach($providers as $p)
                        <option value="{{ $p->id }}" {{ old('ai_provider_id', $config->ai_provider_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->model }}) {{ $p->is_default ? '★ Mặc định' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="model_override" style="display:block; font-weight:600; margin-bottom:0.5rem">Ghi đè Model (tùy chọn)</label>
                <input type="text" id="model_override" name="model_override" class="form-control" value="{{ old('model_override', $config->model_override) }}" placeholder="Để trống nếu muốn dùng model mặc định của Provider" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
        </div>

        <!-- Topic Mode -->
        <div style="margin-bottom:1.5rem">
            <label style="display:block; font-weight:600; margin-bottom:0.5rem">Cơ chế lựa chọn chủ đề <span style="color:var(--danger)">*</span></label>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:0.75rem">
                <label style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="topic_mode" value="admin_topic" {{ old('topic_mode', $config->topic_mode) === 'admin_topic' ? 'checked' : '' }}>
                    <div>
                        <div style="font-weight:700">Chế độ A: Admin chỉ định</div>
                        <small style="color:var(--text-muted)">AI bám sát chủ đề chính và các từ khóa bạn nhập.</small>
                    </div>
                </label>
                <label style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="topic_mode" value="ai_select" {{ old('topic_mode', $config->topic_mode) === 'ai_select' ? 'checked' : '' }}>
                    <div>
                        <div style="font-weight:700">Chế độ B: AI tự chọn chủ đề</div>
                        <small style="color:var(--text-muted)">AI tự chọn một góc nhìn hoặc nhánh đề tài từ nhóm từ khóa.</small>
                    </div>
                </label>
                <label style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="topic_mode" value="ai_research" {{ old('topic_mode', $config->topic_mode) === 'ai_research' ? 'checked' : '' }}>
                    <div>
                        <div style="font-weight:700">Chế độ C: AI nghiên cứu xu hướng</div>
                        <small style="color:var(--text-muted)">AI chủ động đề xuất góc nhìn kỹ thuật mang tính đột phá.</small>
                    </div>
                </label>
            </div>
        </div>

        <!-- Main Topic -->
        <div style="margin-bottom:1.5rem">
            <label for="main_topic" style="display:block; font-weight:600; margin-bottom:0.5rem">Chủ đề chính (Main Topic) <span style="color:var(--danger)">*</span></label>
            <input type="text" id="main_topic" name="main_topic" class="form-control" value="{{ old('main_topic', $config->main_topic) }}" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <!-- Keywords -->
        <div style="margin-bottom:1.5rem">
            <label for="keywords" style="display:block; font-weight:600; margin-bottom:0.5rem">Danh sách từ khóa SEO (phân tách bằng dấu phẩy)</label>
            <input type="text" id="keywords" name="keywords" class="form-control" value="{{ old('keywords', $config->keywords) }}" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <!-- Content details grid -->
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1rem; margin-bottom:1.5rem">
            <div>
                <label for="language" style="display:block; font-weight:600; margin-bottom:0.5rem">Ngôn ngữ bài viết</label>
                <select id="language" name="language" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                    <option value="vi" {{ old('language', $config->language) === 'vi' ? 'selected' : '' }}>Tiếng Việt (Vietnamese)</option>
                    <option value="en" {{ old('language', $config->language) === 'en' ? 'selected' : '' }}>Tiếng Anh (English)</option>
                </select>
            </div>
            <div>
                <label for="writing_tone" style="display:block; font-weight:600; margin-bottom:0.5rem">Văn phong (Writing Tone)</label>
                <select id="writing_tone" name="writing_tone" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                    <option value="professional" {{ old('writing_tone', $config->writing_tone) === 'professional' ? 'selected' : '' }}>Chuyên gia, học thuật (Professional)</option>
                    <option value="conversational" {{ old('writing_tone', $config->writing_tone) === 'conversational' ? 'selected' : '' }}>Thân thiện, chia sẻ (Conversational)</option>
                    <option value="enthusiastic" {{ old('writing_tone', $config->writing_tone) === 'enthusiastic' ? 'selected' : '' }}>Truyền cảm hứng (Enthusiastic)</option>
                    <option value="practical" {{ old('writing_tone', $config->writing_tone) === 'practical' ? 'selected' : '' }}>Thực tiễn, cầm tay chỉ việc (Tutorial)</option>
                </select>
            </div>
            <div>
                <label for="article_length" style="display:block; font-weight:600; margin-bottom:0.5rem">Độ dài bài viết</label>
                <select id="article_length" name="article_length" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                    <option value="short" {{ old('article_length', $config->article_length) === 'short' ? 'selected' : '' }}>Ngắn (~800 từ)</option>
                    <option value="medium" {{ old('article_length', $config->article_length) === 'medium' ? 'selected' : '' }}>Tiêu chuẩn (~1500 từ)</option>
                    <option value="long" {{ old('article_length', $config->article_length) === 'long' ? 'selected' : '' }}>Chuyên sâu (2500+ từ)</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="target_audience" style="display:block; font-weight:600; margin-bottom:0.5rem">Đối tượng độc giả mục tiêu</label>
            <input type="text" id="target_audience" name="target_audience" class="form-control" value="{{ old('target_audience', $config->target_audience) }}" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="tags" style="display:block; font-weight:600; margin-bottom:0.5rem">Tags mặc định (cách nhau bởi dấu phẩy)</label>
            <input type="text" id="tags" name="tags" class="form-control" value="{{ old('tags', is_array($config->tags) ? implode(', ', $config->tags) : $config->tags) }}" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="custom_instructions" style="display:block; font-weight:600; margin-bottom:0.5rem">Chỉ dẫn bổ sung đặc thù (Custom Instructions)</label>
            <textarea id="custom_instructions" name="custom_instructions" rows="3" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">{{ old('custom_instructions', $config->custom_instructions) }}</textarea>
        </div>

        <!-- Options Checkboxes -->
        <div style="display:flex; gap:2rem; margin-bottom:2rem; padding:1rem; background:var(--bg); border:1px solid var(--border); border-radius:8px; flex-wrap:wrap">
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer">
                <input type="checkbox" name="include_faq" value="1" {{ old('include_faq', $config->include_faq) ? 'checked' : '' }}>
                <span>Tạo câu hỏi thường gặp (FAQ Schema)</span>
            </label>
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer">
                <input type="checkbox" name="include_sources" value="1" {{ old('include_sources', $config->include_sources) ? 'checked' : '' }}>
                <span>Đính kèm nguồn tham khảo</span>
            </label>
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $config->is_active) ? 'checked' : '' }}>
                <span>Kích hoạt cấu hình này</span>
            </label>
        </div>

        <div style="display:flex; gap:1rem; justify-content:flex-end">
            <a href="{{ route('admin.ai.configs.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật cấu hình
            </button>
        </div>
    </form>
</div>
@endsection
