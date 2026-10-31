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
                <form method="POST" action="{{ route('admin.ai.posts.publish-now', $post->id) }}" onsubmit="return confirm('Xuất bản ngay bài viết này ra website?')">
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

    <!-- Cover image prompt if available -->
    @if($post->cover_image_prompt)
        <div style="margin-bottom:2rem; padding:1rem; background:rgba(99,102,241,0.05); border:1px dashed var(--accent); border-radius:10px; font-size:0.85rem">
            <strong><i class="fas fa-paint-brush" style="color:var(--accent)"></i> Gợi ý prompt tạo ảnh đại diện của AI (Image Prompt):</strong>
            <p style="margin-top:0.35rem; color:var(--text-muted); font-family:monospace">{{ $post->cover_image_prompt }}</p>
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

    <!-- SEO Meta summary box -->
    <div style="margin-top:3rem; padding:1.25rem; background:var(--bg); border:1px solid var(--border); border-radius:10px; font-size:0.85rem">
        <h4 style="font-size:0.9rem; font-weight:700; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-muted)">
            <i class="fas fa-search"></i> SEO Meta Preview
        </h4>
        <div><strong>SEO Title:</strong> {{ $post->meta_title ?? $post->title }}</div>
        <div style="margin-top:0.25rem"><strong>SEO Description:</strong> {{ $post->meta_description ?? $post->excerpt }}</div>
        @if($post->keywords)
            <div style="margin-top:0.25rem"><strong>Keywords:</strong> {{ $post->keywords }}</div>
        @endif
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
