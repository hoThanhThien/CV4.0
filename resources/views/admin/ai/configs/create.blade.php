@extends('layouts.admin')

@section('title', 'Tạo cấu hình nội dung AI — AI Blog Studio')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-plus-circle" style="color:var(--accent)"></i> Tạo cấu hình nội dung AI mới</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Định nghĩa đề tài, từ khóa, văn phong và chỉ dẫn chuyên biệt cho bài viết AI.
        </p>
    </div>
    <a href="{{ route('admin.ai.configs.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div style="max-width:900px; background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:2rem">
    <form method="POST" action="{{ route('admin.ai.configs.store') }}">
        @csrf

        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem; margin-bottom:1.5rem">
            <div>
                <label for="name" style="display:block; font-weight:600; margin-bottom:0.5rem">Tên cấu hình <span style="color:var(--danger)">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', 'Blog Công nghệ & Kiến trúc phần mềm') }}" placeholder="VD: Blog AI & Cloud Engineering" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
            <div>
                <label for="category" style="display:block; font-weight:600; margin-bottom:0.5rem">Danh mục (Category) <span style="color:var(--danger)">*</span></label>
                <input type="text" id="category" name="category" class="form-control" value="{{ old('category', 'Technology') }}" placeholder="VD: Technology, AI, Web Dev" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem">
            <div>
                <label for="ai_provider_id" style="display:block; font-weight:600; margin-bottom:0.5rem">AI Provider</label>
                <select id="ai_provider_id" name="ai_provider_id" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                    <option value="">— Sử dụng Provider mặc định hệ thống —</option>
                    @foreach($providers as $p)
                        <option value="{{ $p->id }}" {{ old('ai_provider_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->model }}) {{ $p->is_default ? '★ Mặc định' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="model_override" style="display:block; font-weight:600; margin-bottom:0.5rem">Ghi đè Model (tùy chọn)</label>
                <input type="text" id="model_override" name="model_override" class="form-control" value="{{ old('model_override') }}" placeholder="Để trống nếu muốn dùng model mặc định của Provider" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
        </div>

        <!-- Topic Mode -->
        <div style="margin-bottom:1.5rem">
            <label style="display:block; font-weight:600; margin-bottom:0.5rem">Cơ chế lựa chọn chủ đề <span style="color:var(--danger)">*</span></label>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:0.75rem">
                <label style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="topic_mode" value="admin_topic" {{ old('topic_mode', 'admin_topic') === 'admin_topic' ? 'checked' : '' }}>
                    <div>
                        <div style="font-weight:700">Chế độ A: Admin chỉ định</div>
                        <small style="color:var(--text-muted)">AI bám sát chủ đề chính và các từ khóa bạn nhập.</small>
                    </div>
                </label>
                <label style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="topic_mode" value="ai_select" {{ old('topic_mode') === 'ai_select' ? 'checked' : '' }}>
                    <div>
                        <div style="font-weight:700">Chế độ B: AI tự chọn chủ đề</div>
                        <small style="color:var(--text-muted)">AI tự chọn một góc nhìn hoặc nhánh đề tài từ nhóm từ khóa.</small>
                    </div>
                </label>
                <label style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="topic_mode" value="ai_research" {{ old('topic_mode') === 'ai_research' ? 'checked' : '' }}>
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
            <input type="text" id="main_topic" name="main_topic" class="form-control" value="{{ old('main_topic', 'Laravel Architecture & AI Integration Best Practices') }}" placeholder="VD: Hướng dẫn tối ưu hiệu năng Laravel, Microservices hoặc AI Agentic" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <!-- Keywords -->
        <div style="margin-bottom:1.5rem">
            <label for="keywords" style="display:block; font-weight:600; margin-bottom:0.5rem">Danh sách từ khóa SEO (phân tách bằng dấu phẩy)</label>
            <input type="text" id="keywords" name="keywords" class="form-control" value="{{ old('keywords', 'Laravel, AI, Architecture, Scalability, PHP') }}" placeholder="VD: Laravel 10, Performance, AI Automation, REST API" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <!-- Content details grid -->
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1rem; margin-bottom:1.5rem">
            <div>
                <label for="language" style="display:block; font-weight:600; margin-bottom:0.5rem">Ngôn ngữ bài viết</label>
                <select id="language" name="language" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                    <option value="vi" {{ old('language', 'vi') === 'vi' ? 'selected' : '' }}>Tiếng Việt (Vietnamese)</option>
                    <option value="en" {{ old('language') === 'en' ? 'selected' : '' }}>Tiếng Anh (English)</option>
                </select>
            </div>
            <div>
                <label for="writing_tone" style="display:block; font-weight:600; margin-bottom:0.5rem">Văn phong (Writing Tone)</label>
                <select id="writing_tone" name="writing_tone" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                    <option value="professional" {{ old('writing_tone', 'professional') === 'professional' ? 'selected' : '' }}>Chuyên gia, học thuật (Professional)</option>
                    <option value="conversational" {{ old('writing_tone') === 'conversational' ? 'selected' : '' }}>Thân thiện, chia sẻ (Conversational)</option>
                    <option value="enthusiastic" {{ old('writing_tone') === 'enthusiastic' ? 'selected' : '' }}>Truyền cảm hứng (Enthusiastic)</option>
                    <option value="practical" {{ old('writing_tone') === 'practical' ? 'selected' : '' }}>Thực tiễn, cầm tay chỉ việc (Tutorial)</option>
                </select>
            </div>
            <div>
                <label for="article_length" style="display:block; font-weight:600; margin-bottom:0.5rem">Độ dài bài viết</label>
                <select id="article_length" name="article_length" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                    <option value="short" {{ old('article_length') === 'short' ? 'selected' : '' }}>Ngắn (~800 từ)</option>
                    <option value="medium" {{ old('article_length', 'medium') === 'medium' ? 'selected' : '' }}>Tiêu chuẩn (~1500 từ)</option>
                    <option value="long" {{ old('article_length') === 'long' ? 'selected' : '' }}>Chuyên sâu (2500+ từ)</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="target_audience" style="display:block; font-weight:600; margin-bottom:0.5rem">Đối tượng độc giả mục tiêu</label>
            <input type="text" id="target_audience" name="target_audience" class="form-control" value="{{ old('target_audience', 'Software Engineers, Technical Leaders, Recruiters') }}" placeholder="VD: Lập trình viên PHP/Laravel, Tech Leads" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="tags" style="display:block; font-weight:600; margin-bottom:0.5rem">Tags mặc định (cách nhau bởi dấu phẩy)</label>
            <input type="text" id="tags" name="tags" class="form-control" value="{{ old('tags', 'AI, Laravel, Web Development') }}" placeholder="VD: AI, Laravel, Architecture" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="custom_instructions" style="display:block; font-weight:600; margin-bottom:0.5rem">Chỉ dẫn bổ sung đặc thù (Custom Instructions)</label>
            <textarea id="custom_instructions" name="custom_instructions" rows="3" class="form-control" placeholder="VD: Hãy kèm theo đoạn code minh họa chuẩn PSR-12, giải thích ngắn gọn ưu nhược điểm và chia sẻ kinh nghiệm xử lý lỗi trong thực tế." style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">{{ old('custom_instructions') }}</textarea>
        </div>

        <!-- AI Image & OpenGraph Settings -->
        <div style="margin-bottom:1.5rem; padding:1.25rem; background:linear-gradient(135deg, rgba(99,102,241,0.06), rgba(6,182,212,0.06)); border:1px solid rgba(99,102,241,0.22); border-radius:10px">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem">
                <h4 style="font-size:0.95rem; font-weight:800; color:var(--accent); margin:0">
                    <i class="fas fa-image"></i> AI Tạo Thumbnail (16:9) & Ảnh OpenGraph (1200×630)
                </h4>
                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:700; font-size:0.9rem">
                    <input type="checkbox" name="auto_generate_image" value="1" {{ old('auto_generate_image', true) ? 'checked' : '' }}>
                    <span>Tự động sinh Thumbnail & Ảnh OG khi tạo bài</span>
                </label>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem">
                <div>
                    <label for="image_style" style="display:block; font-weight:600; margin-bottom:0.4rem; font-size:0.86rem">Phong cách đồ họa (Visual Style)</label>
                    <select id="image_style" name="image_style" class="form-control" style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:8px">
                        @foreach(\App\Services\AI\BlogImageGenerator::stylePresets() as $styleKey => $styleLabel)
                            <option value="{{ $styleKey }}" {{ old('image_style', 'modern_tech') === $styleKey ? 'selected' : '' }}>{{ $styleLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.4rem; gap:0.5rem">
                        <label for="image_model" style="font-weight:600; font-size:0.86rem; margin:0">Model tạo ảnh (tùy chọn)</label>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="fetchConfigImageModels()" id="btnFetchImgModels" style="padding:0.2rem 0.55rem; font-size:0.75rem">
                            <i class="fas fa-sync-alt"></i> Lấy từ API
                        </button>
                    </div>
                    <input type="text" id="image_model" name="image_model" list="image_model_list" class="form-control" value="{{ old('image_model') }}" placeholder="Để trống = Tự động khám phá từ Provider API" style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:8px">
                    <datalist id="image_model_list"></datalist>
                </div>
            </div>
        </div>

        <!-- Options Checkboxes -->
        <div style="display:flex; gap:2rem; margin-bottom:2rem; padding:1rem; background:var(--bg); border:1px solid var(--border); border-radius:8px; flex-wrap:wrap">
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer">
                <input type="checkbox" name="include_faq" value="1" {{ old('include_faq', true) ? 'checked' : '' }}>
                <span>Tạo câu hỏi thường gặp (FAQ Schema)</span>
            </label>
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer">
                <input type="checkbox" name="include_sources" value="1" {{ old('include_sources') ? 'checked' : '' }}>
                <span>Đính kèm nguồn tham khảo</span>
            </label>
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <span>Kích hoạt cấu hình này</span>
            </label>
        </div>

        <div style="display:flex; gap:1rem; justify-content:flex-end">
            <a href="{{ route('admin.ai.configs.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu cấu hình nội dung
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    async function fetchConfigImageModels() {
        const btn = document.getElementById('btnFetchImgModels');
        const providerSelect = document.getElementById('ai_provider_id');
        const list = document.getElementById('image_model_list');
        const input = document.getElementById('image_model');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';

        try {
            const resp = await fetch(@json(route('admin.ai.providers.fetch-models')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    provider_id: providerSelect.value ? parseInt(providerSelect.value, 10) : null,
                    capability: 'image'
                })
            });

            const data = await resp.json();
            list.innerHTML = '';
            if (data.success && Array.isArray(data.models) && data.models.length > 0) {
                data.models.forEach(m => {
                    const opt = document.createElement('option');
                    opt.value = m.id;
                    opt.label = m.display_name || m.id;
                    list.appendChild(opt);
                });
                if (!input.value) input.value = data.models[0].id;
                window.showAdminToast(data.message, 'success');
            } else {
                window.showAdminToast(data.message || 'Không tìm thấy model ảnh riêng.', 'error');
            }
        } catch (err) {
            window.showAdminToast('Không thể kết nối API lấy danh sách model ảnh.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt"></i> Lấy từ API';
        }
    }
</script>
@endsection
