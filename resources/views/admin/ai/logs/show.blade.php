@extends('layouts.admin')

@section('title', 'Chi tiết lượt chạy #' . $run->id)

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-file-alt" style="color:var(--accent)"></i> Chi tiết lượt chạy #{{ $run->id }}</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Thông tin chi tiết về token, thời gian xử lý và các bài viết đã được tạo.
        </p>
    </div>
    <div style="display:flex; gap:0.5rem">
        <a href="{{ route('admin.ai.logs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
        @if($run->status === 'failed' && $run->automation_id)
            <form method="POST" action="{{ route('admin.ai.logs.retry', $run->id) }}">
                @csrf
                <button type="submit" class="btn btn-primary" style="background:var(--warning); border-color:var(--warning)">
                    <i class="fas fa-redo"></i> Thử lại lượt chạy
                </button>
            </form>
        @endif
    </div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem">
    <div>
        <!-- Execution Overview Card -->
        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:1.5rem; margin-bottom:1.5rem">
            <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem"><i class="fas fa-info-circle" style="color:var(--accent)"></i> Thông tin tổng quan</h3>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem; font-size:0.9rem">
                <div>
                    <span style="color:var(--text-muted)">Automation:</span>
                    <strong>{{ $run->automation->name ?? 'Chạy thử nghiệm' }}</strong>
                </div>
                <div>
                    <span style="color:var(--text-muted)">Cấu hình nội dung:</span>
                    <strong>{{ $run->contentConfig->name ?? '—' }}</strong>
                </div>
                <div>
                    <span style="color:var(--text-muted)">Provider:</span>
                    <strong>{{ $run->provider_name }}</strong>
                </div>
                <div>
                    <span style="color:var(--text-muted)">Model sử dụng:</span>
                    <code>{{ $run->model_used }}</code>
                </div>
                <div>
                    <span style="color:var(--text-muted)">Thời gian bắt đầu:</span>
                    <span>{{ $run->started_at ? $run->started_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') : '—' }}</span>
                </div>
                <div>
                    <span style="color:var(--text-muted)">Thời gian hoàn thành:</span>
                    <span>{{ $run->completed_at ? $run->completed_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') : '—' }}</span>
                </div>
                <div>
                    <span style="color:var(--text-muted)">Chủ đề xử lý:</span>
                    <span>{{ $run->topic_processed }}</span>
                </div>
                <div>
                    <span style="color:var(--text-muted)">Trạng thái:</span>
                    @if($run->status === 'completed')
                        <span class="badge badge-success">Hoàn thành</span>
                    @elseif($run->status === 'running')
                        <span class="badge badge-warning">Đang chạy</span>
                    @elseif($run->status === 'partially_completed')
                        <span class="badge badge-warning">Một phần</span>
                    @else
                        <span class="badge badge-danger">Thất bại</span>
                    @endif
                </div>
            </div>

            @if($run->error_details)
                <div style="margin-top:1.5rem; padding:1rem; background:rgba(239,68,68,0.08); border:1px solid var(--danger); border-radius:8px">
                    <strong style="color:var(--danger); display:block; margin-bottom:0.35rem"><i class="fas fa-exclamation-triangle"></i> Chi tiết lỗi kỹ thuật:</strong>
                    <div style="font-size:0.85rem; font-family:monospace; color:var(--text); white-space:pre-wrap">{{ $run->error_details }}</div>
                </div>
            @endif
        </div>

        <!-- Created Posts List -->
        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:1.5rem">
            <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem"><i class="fas fa-newspaper" style="color:var(--accent)"></i> Bài viết được tạo trong lượt này</h3>
            <div style="display:flex; flex-direction:column; gap:0.75rem">
                @forelse($run->created_posts as $post)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:0.85rem 1rem; background:var(--bg); border:1px solid var(--border); border-radius:8px">
                        <div>
                            <div style="font-weight:700">#{{ $post->id }}: {{ $post->title }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.2rem">
                                Trạng thái: <strong>{{ strtoupper($post->status) }}</strong>
                            </div>
                        </div>
                        <a href="{{ route('admin.ai.posts.preview', $post->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-eye"></i> Xem trước
                        </a>
                    </div>
                @empty
                    <p style="color:var(--text-muted); font-size:0.9rem">Không có bài viết nào được tạo trong lượt chạy này.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Token Usage Summary -->
    <div>
        <div style="background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:1.5rem">
            <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:1rem"><i class="fas fa-coins" style="color:var(--accent)"></i> Tài nguyên & Chi phí</h3>
            <div style="display:flex; flex-direction:column; gap:0.75rem; font-size:0.9rem">
                <div style="display:flex; justify-content:space-between; padding-bottom:0.5rem; border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Prompt Tokens:</span>
                    <strong>{{ number_format($run->prompt_tokens) }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; padding-bottom:0.5rem; border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Completion Tokens:</span>
                    <strong>{{ number_format($run->completion_tokens) }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; padding-bottom:0.5rem; border-bottom:1px solid var(--border)">
                    <span style="color:var(--text-muted)">Tổng Token tiêu thụ:</span>
                    <strong style="color:var(--accent); font-size:1.1rem">{{ number_format($run->total_tokens) }}</strong>
                </div>
                <div style="display:flex; justify-content:space-between; padding-top:0.25rem">
                    <span style="color:var(--text-muted)">Số bài thành công:</span>
                    <strong style="color:var(--success)">{{ $run->articles_created }} / {{ $run->articles_requested }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
