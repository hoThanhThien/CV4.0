@extends('layouts.admin')

@section('title', 'Xem trước bài viết: ' . $post->title)

@section('content')
<!-- Admin Action Bar -->
<div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:1.25rem; margin-bottom:2rem; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05)">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem">
        <div style="display:flex; align-items:center; gap:0.75rem">
            <a href="{{ route('admin.ai.posts.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <span style="font-size:0.95rem; font-weight:700">Trạng thái hiện tại:</span>
            @if($post->status === 'published')
                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Đã xuất bản</span>
            @elseif($post->status === 'scheduled')
                <span class="badge badge-info" style="background:rgba(6,182,212,0.15); color:#0891b2">
                    <i class="fas fa-clock"></i> Lên lịch: {{ $post->published_at ? $post->published_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : '' }}
                </span>
            @elseif($post->status === 'pending_review')
                <span class="badge badge-warning"><i class="fas fa-user-clock"></i> Chờ duyệt (Pending Review)</span>
            @else
                <span class="badge badge-secondary">Bản nháp (Draft)</span>
            @endif

            @if($post->last_edited_by_admin)
                <span class="badge" style="background:rgba(245,158,11,0.15); color:var(--warning)">Đã sửa tay bởi Admin</span>
            @endif
        </div>

        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap">
            @if($post->status === 'pending_review')
                <form method="POST" action="{{ route('admin.ai.posts.approve', $post->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-check"></i> Phê duyệt bài viết
                    </button>
                </form>
            @endif

            @if($post->status !== 'published')
                <form method="POST" action="{{ route('admin.ai.posts.publish-now', $post->id) }}"
                    data-confirm-type="publish"
                    data-confirm-title="Xuất bản bài viết ngay?"
                    data-confirm="Bài viết này sẽ được hiển thị công khai trên trang Blog ngay lập tức.">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm" style="background:var(--success); border-color:var(--success)">
                        <i class="fas fa-globe"></i> Xuất bản ngay
                    </button>
                </form>

                <button type="button" class="btn btn-secondary btn-sm" onclick="openScheduleModal()">
                    <i class="fas fa-calendar-alt"></i> Lên lịch đăng
                </button>
            @else
                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-external-link-alt"></i> Xem trang công khai
                </a>
            @endif

            @if($post->status === 'scheduled')
                <form method="POST" action="{{ route('admin.ai.posts.cancel-schedule', $post->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--danger)">
                        <i class="fas fa-times"></i> Hủy lịch
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.ai.posts.edit', $post->id) }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>

            <!-- Nút Tạo Thumbnail & Ảnh OG bằng AI -->
            <button type="button" class="btn btn-secondary btn-sm" onclick="openAiImageModal()" style="color:#0891b2; border-color:rgba(6,182,212,0.35); background:rgba(6,182,212,0.08); font-weight:700">
                <i class="fas fa-image"></i> {{ $post->image ? 'Tạo lại Thumbnail & OG (AI)' : 'Tạo Thumbnail & OG bằng AI' }}
            </button>

            <!-- Nút Regenerate -->
            <button type="button" class="btn btn-secondary btn-sm" onclick="openRegenerateModal()" style="color:var(--accent)">
                <i class="fas fa-sync-alt"></i> Tạo lại bằng AI
            </button>
        </div>
    </div>
</div>

<!-- Article Preview Box (Styling exact match with public blog) -->
<div style="max-width:960px; margin:0 auto; background:var(--bg-card); border:1px solid var(--border); border-radius:16px; padding:3rem 2.5rem; box-shadow:0 10px 15px -3px rgba(0,0,0,0.05)">
    <!-- Metadata header -->
    <div style="display:flex; align-items:center; gap:0.5rem; color:var(--text-muted); font-size:0.85rem; margin-bottom:1rem">
        <span><i class="fas fa-folder" style="color:var(--accent)"></i> {{ $post->category ?? 'Technology' }}</span>
        <span>•</span>
        <span><i class="fas fa-robot"></i> {{ $post->provider->name ?? 'AI Generated' }} ({{ $post->provider->model ?? 'Default' }})</span>
        @if($post->published_at)
            <span>•</span>
            <span><i class="fas fa-calendar"></i> {{ $post->published_at->timezone('Asia/Ho_Chi_Minh')->format('F d, Y H:i') }}</span>
        @endif
    </div>

    <!-- Title -->
    <h1 style="font-size:2.2rem; font-weight:900; line-height:1.3; margin-bottom:1.5rem; color:var(--text)">
        {{ $post->title }}
    </h1>

    <!-- Excerpt / Lead -->
    @if($post->excerpt)
        <div style="font-size:1.15rem; color:var(--text-muted); line-height:1.7; margin-bottom:2rem; padding-left:1rem; border-left:4px solid var(--accent); font-style:italic">
            {{ $post->excerpt }}
        </div>
    @endif

    <!-- Cover Thumbnail Preview -->
    @if($post->thumbnail_url)
        <div style="margin-bottom:2rem; border-radius:14px; overflow:hidden; border:1px solid var(--border); box-shadow:0 8px 20px -6px rgba(0,0,0,0.12); position:relative">
            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" style="width:100%; height:auto; display:block; object-fit:cover">
            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem; padding:0.65rem 1rem; background:var(--bg); border-top:1px solid var(--border); font-size:0.78rem; color:var(--text-muted)">
                <span><i class="fas fa-check-circle" style="color:var(--success)"></i> <strong>Thumbnail bài viết (16:9)</strong> — Đang sử dụng trên danh sách & đầu bài Blog</span>
                <div style="display:flex; gap:0.5rem">
                    <a href="{{ $post->thumbnail_url }}" target="_blank" rel="noopener" style="color:var(--accent); font-weight:600; text-decoration:none"><i class="fas fa-external-link-alt"></i> Mở ảnh gốc</a>
                    <button type="button" onclick="openAiImageModal()" style="background:none; border:none; color:#0891b2; font-weight:700; cursor:pointer; padding:0"><i class="fas fa-magic"></i> Tạo lại bằng AI</button>
                </div>
            </div>
        </div>
    @else
        <div style="margin-bottom:2rem; padding:1.35rem; background:linear-gradient(135deg, rgba(99,102,241,0.06), rgba(6,182,212,0.06)); border:1px dashed var(--accent); border-radius:12px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem">
            <div>
                <div style="font-weight:800; color:var(--text); font-size:0.95rem"><i class="fas fa-image" style="color:var(--accent)"></i> Bài viết chưa có Thumbnail & Ảnh OG riêng</div>
                <div style="font-size:0.83rem; color:var(--text-muted); margin-top:0.2rem">Bấm nút bên cạnh để AI tự động thiết kế bộ Thumbnail (16:9) và Ảnh OpenGraph (1200×630) theo đúng chủ đề bài viết.</div>
            </div>
            <button type="button" class="btn btn-primary btn-sm" onclick="openAiImageModal()">
                <i class="fas fa-magic"></i> Tạo Thumbnail & Ảnh OG bằng AI
            </button>
        </div>
    @endif

    <!-- Cover image prompt if available -->
    @if($post->cover_image_prompt)
        <div style="margin-bottom:2rem; padding:1rem; background:rgba(99,102,241,0.05); border:1px dashed var(--accent); border-radius:10px; font-size:0.85rem">
            <strong><i class="fas fa-paint-brush" style="color:var(--accent)"></i> Prompt ý tưởng hình ảnh AI (Image Prompt):</strong>
            <p style="margin-top:0.35rem; margin-bottom:0; color:var(--text-muted); font-family:monospace">{{ $post->cover_image_prompt }}</p>
        </div>
    @endif

    <!-- Rich HTML Content Body -->
    <div class="post-preview-content" style="color:var(--text); font-size:1.05rem; line-height:1.85">
        {!! $post->content !!}
    </div>

    <!-- FAQ Section if present -->
    @if(!empty($post->faq) && is_array($post->faq))
        <div style="margin-top:3rem; padding-top:2rem; border-top:1px solid var(--border)">
            <h3 style="font-size:1.4rem; font-weight:800; margin-bottom:1.25rem"><i class="fas fa-question-circle" style="color:var(--accent)"></i> Câu hỏi thường gặp (FAQ)</h3>
            <div style="display:flex; flex-direction:column; gap:1rem">
                @foreach($post->faq as $faqItem)
                    <div style="background:var(--bg); border:1px solid var(--border); border-radius:10px; padding:1.25rem">
                        <h4 style="font-size:1rem; font-weight:700; color:var(--text); margin-bottom:0.5rem">{{ $faqItem['question'] ?? '' }}</h4>
                        <p style="color:var(--text-muted); font-size:0.95rem; margin:0; line-height:1.6">{{ $faqItem['answer'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Sources Section if present -->
    @if(!empty($post->sources) && is_array($post->sources))
        <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--border)">
            <h4 style="font-size:1rem; font-weight:700; margin-bottom:0.75rem; color:var(--text-muted)"><i class="fas fa-link"></i> Nguồn tham khảo</h4>
            <ul style="margin-left:1.5rem; color:var(--accent); font-size:0.9rem">
                @foreach($post->sources as $source)
                    <li>
                        <a href="{{ $source['url'] ?? '#' }}" target="_blank" rel="noopener" style="color:var(--accent)">{{ $source['title'] ?? ($source['url'] ?? '') }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- SEO & OpenGraph Social Card Preview -->
    <div style="margin-top:3rem; padding:1.5rem; background:var(--bg); border:1px solid var(--border); border-radius:12px; font-size:0.85rem">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem; margin-bottom:1rem">
            <h4 style="font-size:0.9rem; font-weight:800; margin:0; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-muted)">
                <i class="fas fa-share-alt" style="color:var(--accent)"></i> SEO Meta & OpenGraph Social Card (1200×630)
            </h4>
            @if($post->og_image)
                <a href="{{ $post->og_image_url }}" target="_blank" rel="noopener" class="badge badge-success" style="text-decoration:none">
                    <i class="fas fa-check-circle"></i> Xem file OG PNG/WebP 1200×630
                </a>
            @endif
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1.5rem; align-items:start">
            <div>
                <div style="margin-bottom:0.5rem"><strong>SEO Title:</strong> {{ $post->meta_title ?? $post->title }}</div>
                <div style="margin-bottom:0.5rem"><strong>SEO Description:</strong> {{ $post->meta_description ?? $post->excerpt }}</div>
                @if($post->keywords)
                    <div style="margin-bottom:0.5rem"><strong>Keywords:</strong> {{ $post->keywords }}</div>
                @endif
                <div style="font-size:0.78rem; color:var(--text-muted); margin-top:0.75rem">
                    Thẻ <code>og:image</code> và <code>twitter:image</code> tự động trỏ tới file ảnh chuẩn tỷ lệ <strong>1200×630</strong> giúp hiển thị sắc nét khi chia sẻ lên Facebook, Zalo, LinkedIn, Discord và X.
                </div>
            </div>

            <!-- Simulated Facebook / LinkedIn Link Share Card -->
            <div style="border:1px solid var(--border); border-radius:12px; overflow:hidden; background:var(--bg-card); box-shadow:0 4px 12px rgba(0,0,0,0.06)">
                <div style="aspect-ratio: 1200 / 630; width:100%; overflow:hidden; background:#0f172a">
                    <img src="{{ $post->og_image_url }}" alt="OG Preview" style="width:100%; height:100%; object-fit:cover; display:block">
                </div>
                <div style="padding:0.85rem 1rem; border-top:1px solid var(--border)">
                    <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); letter-spacing:0.04em">{{ parse_url(config('app.url'), PHP_URL_HOST) ?: 'hothanhthien.io.vn' }}</div>
                    <div style="font-weight:800; font-size:0.92rem; color:var(--text); margin-top:0.2rem; line-height:1.35">{{ Str::limit($post->meta_title ?? $post->title, 68) }}</div>
                    <div style="font-size:0.78rem; color:var(--text-muted); margin-top:0.2rem">{{ Str::limit($post->meta_description ?? $post->excerpt, 95) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal AI Image Studio (Tạo Thumbnail & Ảnh OG bằng AI) -->
<div id="ai-image-modal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.58); backdrop-filter:blur(5px); z-index:9997; align-items:center; justify-content:center; padding:1.25rem">
    <div style="background:var(--bg-card); max-width:560px; width:100%; border-radius:16px; border:1px solid var(--border); box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); overflow:hidden">
        <form method="POST" action="{{ route('admin.ai.posts.generate-image', $post->id) }}"
            data-confirm-type="ai"
            data-confirm-icon="fa-image"
            data-confirm-title="Bắt đầu tạo Thumbnail & Ảnh OG?"
            data-confirm="AI sẽ sinh mới đồng thời ảnh Thumbnail (16:9) cho trang Blog và ảnh OpenGraph (1200×630) cho chia sẻ mạng xã hội."
            data-confirm-btn="<i class='fas fa-magic'></i> Tạo ngay"
            data-loading-title="AI đang thiết kế Thumbnail & Ảnh OG..."
            data-loading-desc="Hệ thống đang tạo ảnh minh họa 16:9 và xuất bản chuẩn OpenGraph 1200×630...">
            @csrf
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between">
                <h3 style="font-size:1.1rem; font-weight:800; margin:0; color:var(--text)">
                    <i class="fas fa-magic" style="color:var(--accent)"></i> AI Studio — Tạo Thumbnail & Ảnh OG
                </h3>
                <button type="button" onclick="closeAiImageModal()" style="background:none; border:none; font-size:1.3rem; cursor:pointer; color:var(--text-muted)">&times;</button>
            </div>
            <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1rem">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:0.85rem">
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.35rem">AI Provider</label>
                        <select id="modal_ai_provider_id" name="ai_provider_id" class="form-control" style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:8px">
                            <option value="">— Theo cấu hình bài viết —</option>
                            @foreach($providers ?? [] as $p)
                                <option value="{{ $p->id }}" {{ ($post->ai_provider_id == $p->id) ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.35rem">Phong cách đồ họa</label>
                        <select name="image_style" class="form-control" style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:8px">
                            @foreach(\App\Services\AI\BlogImageGenerator::stylePresets() as $sKey => $sLabel)
                                <option value="{{ $sKey }}" {{ ($post->contentConfig?->image_style ?? 'modern_tech') === $sKey ? 'selected' : '' }}>{{ $sLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.35rem">
                        <label style="font-weight:600; font-size:0.85rem; margin:0">Model tạo ảnh (tùy chọn)</label>
                        <button type="button" class="btn btn-secondary btn-sm" id="btnModalFetchImgModels" onclick="fetchModalImageModels()" style="padding:0.18rem 0.55rem; font-size:0.74rem">
                            <i class="fas fa-sync-alt"></i> Lấy danh sách từ API
                        </button>
                    </div>
                    <input type="text" id="modal_image_model" name="image_model" list="modal_image_model_list" class="form-control" value="{{ $post->contentConfig?->image_model }}" placeholder="Để trống = Tự động khám phá model khả dụng từ API" style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:8px">
                    <datalist id="modal_image_model_list"></datalist>
                </div>

                <div>
                    <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:0.35rem">Mô tả ý tưởng hình ảnh (Image Prompt)</label>
                    <textarea name="custom_prompt" rows="3" class="form-control" placeholder="Để trống để AI tự tổng hợp từ tiêu đề và nội dung bài viết..." style="width:100%; padding:0.65rem; border:1px solid var(--border); border-radius:8px; font-size:0.88rem">{{ $post->cover_image_prompt }}</textarea>
                </div>
            </div>
            <div style="padding:1rem 1.5rem; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:0.6rem">
                <button type="button" class="btn btn-secondary" onclick="closeAiImageModal()">Đóng</button>
                <button type="submit" class="btn btn-primary" onclick="closeAiImageModal()">
                    <i class="fas fa-magic"></i> Tạo Thumbnail & Ảnh OG
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Lên lịch đăng (Schedule Modal) -->
<div id="schedule-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:1.5rem">
    <div style="background:var(--bg-card); max-width:450px; width:100%; border-radius:12px; border:1px solid var(--border); box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); overflow:hidden">
        <form method="POST" action="{{ route('admin.ai.posts.schedule', $post->id) }}">
            @csrf
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between">
                <h3 style="font-size:1.1rem; font-weight:700"><i class="fas fa-calendar-alt" style="color:var(--accent)"></i> Lên lịch xuất bản bài viết</h3>
                <button type="button" onclick="closeScheduleModal()" style="background:none; border:none; font-size:1.2rem; cursor:pointer">&times;</button>
            </div>
            <div style="padding:1.5rem">
                <label for="scheduled_at" style="display:block; font-weight:600; margin-bottom:0.5rem">Chọn ngày & giờ xuất bản (Giờ Việt Nam):</label>
                <input type="datetime-local" id="scheduled_at" name="scheduled_at" class="form-control" required
                    value="{{ now('Asia/Ho_Chi_Minh')->addHours(2)->format('Y-m-d\TH:i') }}"
                    style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                <small style="color:var(--text-muted); display:block; margin-top:0.5rem">
                    Hệ thống sẽ giữ bài viết ở trạng thái bí mật cho đến thời điểm này. Đúng thời gian trên, Laravel Scheduler sẽ tự động xuất bản ra trang web công khai.
                </small>
            </div>
            <div style="padding:1rem 1.5rem; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:0.5rem">
                <button type="button" class="btn btn-secondary" onclick="closeScheduleModal()">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Xác nhận lên lịch</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Regenerate Xác nhận -->
<div id="regenerate-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:1.5rem">
    <div style="background:var(--bg-card); max-width:480px; width:100%; border-radius:12px; border:1px solid var(--border); box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); overflow:hidden">
        <form method="POST" action="{{ route('admin.ai.posts.regenerate', $post->id) }}">
            @csrf
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between">
                <h3 style="font-size:1.1rem; font-weight:700; color:var(--warning)"><i class="fas fa-sync-alt"></i> Tạo lại nội dung bằng AI</h3>
                <button type="button" onclick="closeRegenerateModal()" style="background:none; border:none; font-size:1.2rem; cursor:pointer">&times;</button>
            </div>
            <div style="padding:1.5rem">
                @if($post->last_edited_by_admin)
                    <div style="padding:0.75rem 1rem; background:rgba(239,68,68,0.1); border:1px solid var(--danger); border-radius:8px; color:var(--danger); font-size:0.88rem; margin-bottom:1rem">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Cảnh báo:</strong> Bài viết này đã được Admin chỉnh sửa thủ công trước đó. Nếu tạo lại bằng AI, các chỉnh sửa của bạn sẽ bị thay thế!
                    </div>
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:600; font-size:0.9rem">
                        <input type="checkbox" name="confirm_overwrite" value="1" required>
                        <span>Tôi hiểu và đồng ý ghi đè bài viết này</span>
                    </label>
                @else
                    <p style="font-size:0.95rem; color:var(--text); line-height:1.6">
                        AI sẽ viết lại bài này dựa trên cấu hình nội dung đã gán. Tiêu đề và nội dung cũ sẽ được làm mới hoàn toàn. Bạn có muốn tiếp tục?
                    </p>
                @endif
            </div>
            <div style="padding:1rem 1.5rem; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:0.5rem">
                <button type="button" class="btn btn-secondary" onclick="closeRegenerateModal()">Hủy</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Bắt đầu tạo lại</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openScheduleModal() {
        document.getElementById('schedule-modal').style.display = 'flex';
    }
    function closeScheduleModal() {
        document.getElementById('schedule-modal').style.display = 'none';
    }

    function openRegenerateModal() {
        document.getElementById('regenerate-modal').style.display = 'flex';
    }
    function closeRegenerateModal() {
        document.getElementById('regenerate-modal').style.display = 'none';
    }
</script>
@endsection
