@extends('layouts.admin')

@section('title', 'AI Blog Studio — Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1 style="display:flex; align-items:center; gap:0.5rem">
            <i class="fas fa-magic" style="color:var(--accent)"></i> AI Blog Automation Dashboard
        </h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Hệ thống tự động tạo và xuất bản bài viết theo lịch sử dụng OpenAI, Gemini, DeepSeek & Custom Models.
        </p>
    </div>
    <div style="display:flex; gap:0.5rem">
        <a href="{{ route('admin.ai.configs.create') }}" class="btn btn-secondary">
            <i class="fas fa-plus"></i> Tạo cấu hình nội dung
        </a>
        <a href="{{ route('admin.ai.automations.create') }}" class="btn btn-primary">
            <i class="fas fa-clock"></i> Lập lịch tự động mới
        </a>
    </div>
</div>

<!-- Stats Overview -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
    <div class="stat-card stat-purple">
        <div class="stat-icon"><i class="fas fa-robot"></i></div>
        <div class="stat-label">Tổng bài viết AI</div>
        <div class="stat-value">{{ $totalAiPosts }}</div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-label">Đã xuất bản (Live)</div>
        <div class="stat-value">{{ $publishedAiPosts }}</div>
    </div>
    <div class="stat-card" style="border-left: 3px solid var(--warning);">
        <div class="stat-icon" style="background:rgba(245,158,11,0.15); color:var(--warning)"><i class="fas fa-user-clock"></i></div>
        <div class="stat-label">Chờ duyệt (Pending)</div>
        <div class="stat-value" style="color:var(--warning)">{{ $pendingAiPosts }}</div>
    </div>
    <div class="stat-card stat-cyan">
        <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-label">Đã lên lịch (Scheduled)</div>
        <div class="stat-value">{{ $scheduledAiPosts }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(99,102,241,0.15); color:var(--accent)"><i class="fas fa-bolt"></i></div>
        <div class="stat-label">Automation đang bật</div>
        <div class="stat-value">{{ $activeAutomations }}</div>
    </div>
    <div class="stat-card" style="border-left: 3px solid var(--danger);">
        <div class="stat-icon" style="background:rgba(239,68,68,0.15); color:var(--danger)"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="stat-label">Lần chạy lỗi</div>
        <div class="stat-value" style="color:var(--danger)">{{ $failedRuns }}</div>
    </div>
</div>

<!-- Row: Next Run & Providers status -->
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:2rem;">
    <!-- Next Scheduled Run Card -->
    <div class="stat-card" style="display:flex; flex-direction:column; justify-content:space-between">
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem">
                <span style="font-weight:700; font-size:0.95rem; color:var(--text)"><i class="fas fa-hourglass-half" style="color:var(--accent)"></i> Lịch chạy tự động tiếp theo</span>
                @if($nextScheduledRun)
                    <span class="badge badge-success"><i class="fas fa-circle" style="font-size:0.45rem"></i> Đang kích hoạt</span>
                @else
                    <span class="badge badge-secondary">Chưa có lịch</span>
                @endif
            </div>

            @if($nextScheduledRun)
                <div style="margin-top:0.5rem">
                    <div style="font-size:1.15rem; font-weight:800; color:var(--accent-light)">
                        {{ $nextScheduledRun->name }}
                    </div>
                    <div style="font-size:0.85rem; color:var(--text-muted); margin-top:0.35rem">
                        Cấu hình: <strong>{{ $nextScheduledRun->contentConfig->name ?? '—' }}</strong> |
                        Chế độ: <strong>{{ strtoupper(str_replace('_', ' ', $nextScheduledRun->publishing_mode)) }}</strong>
                    </div>
                    <div style="margin-top:0.75rem; padding:0.65rem 0.85rem; background:rgba(99,102,241,0.06); border-radius:8px; font-size:0.85rem">
                        <i class="fas fa-calendar-check" style="color:var(--accent)"></i> Thời điểm chạy:
                        <strong style="color:var(--text)">
                            {{ $nextScheduledRun->next_run_at ? $nextScheduledRun->next_run_at->timezone('Asia/Ho_Chi_Minh')->format('H:i - d/m/Y') : '—' }}
                        </strong>
                        <span style="color:var(--text-muted); font-size:0.8rem">({{ $nextScheduledRun->next_run_at ? $nextScheduledRun->next_run_at->diffForHumans() : '' }})</span>
                    </div>
                </div>
            @else
                <p style="font-size:0.85rem; color:var(--text-muted); margin-top:0.5rem">
                    Chưa có Automation nào được kích hoạt. Bạn có thể tạo lịch trình mới để hệ thống tự động sinh bài định kỳ.
                </p>
                <div style="margin-top:1rem">
                    <a href="{{ route('admin.ai.automations.create') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-plus"></i> Tạo Automation
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Active Providers Quick Status -->
    <div class="stat-card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem">
            <span style="font-weight:700; font-size:0.95rem; color:var(--text)"><i class="fas fa-plug" style="color:var(--accent)"></i> Nhà cung cấp AI (Providers)</span>
            <a href="{{ route('admin.ai.providers.index') }}" style="font-size:0.8rem; color:var(--accent-light); text-decoration:none">Quản lý <i class="fas fa-arrow-right"></i></a>
        </div>
        <div style="display:flex; flex-direction:column; gap:0.5rem; margin-top:0.5rem">
            @forelse($providers as $p)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:0.5rem 0.75rem; background:var(--bg); border:1px solid var(--border); border-radius:8px; font-size:0.85rem">
                    <div style="display:flex; align-items:center; gap:0.5rem">
                        <span style="font-weight:600">{{ $p->name }}</span>
                        <code style="font-size:0.75rem; color:var(--text-muted)">{{ $p->model }}</code>
                        @if($p->is_default)
                            <span class="badge badge-success" style="font-size:0.65rem">Default</span>
                        @endif
                    </div>
                    <div>
                        @if($p->connection_status === 'connected')
                            <span class="badge badge-success"><i class="fas fa-check"></i> Connected</span>
                        @elseif($p->connection_status === 'failed')
                            <span class="badge badge-danger"><i class="fas fa-times"></i> Error</span>
                        @else
                            <span class="badge badge-secondary">Untested</span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:1.5rem; color:var(--text-muted); font-size:0.85rem">
                    Chưa có AI Provider nào. <a href="{{ route('admin.ai.providers.create') }}" style="color:var(--accent-light)">Thêm Provider</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent AI Posts Table -->
<div style="margin-bottom:2rem">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem">
        <h2 style="font-size:1.15rem; font-weight:700"><i class="fas fa-robot"></i> Bài viết do AI tạo gần đây</h2>
        <a href="{{ route('admin.ai.posts.index') }}" class="btn btn-secondary btn-sm">Xem tất cả bài AI</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tiêu đề bài viết</th>
                    <th>Chủ đề / Danh mục</th>
                    <th>Trạng thái</th>
                    <th>Thời gian</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPosts as $post)
                    <tr>
                        <td style="color:var(--text-muted)">{{ $post->id }}</td>
                        <td>
                            <div style="font-weight:600">{{ Str::limit($post->title, 55) }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.15rem">
                                Provider: <strong>{{ $post->provider->name ?? 'AI' }}</strong> ({{ $post->provider->model ?? 'Default' }})
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background:rgba(99,102,241,0.1); color:var(--accent)">
                                {{ $post->category ?? 'Tech' }}
                            </span>
                        </td>
                        <td>
                            @if($post->status === 'published')
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Đã xuất bản</span>
                            @elseif($post->status === 'scheduled')
                                <span class="badge badge-info" style="background:rgba(6,182,212,0.15); color:#0891b2">
                                    <i class="fas fa-clock"></i> Lên lịch: {{ $post->published_at ? $post->published_at->timezone('Asia/Ho_Chi_Minh')->format('d/m H:i') : '' }}
                                </span>
                            @elseif($post->status === 'pending_review')
                                <span class="badge badge-warning"><i class="fas fa-user-clock"></i> Chờ duyệt</span>
                            @else
                                <span class="badge badge-secondary">Nháp (Draft)</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted); font-size:0.8rem">
                            {{ $post->created_at ? $post->created_at->diffForHumans() : '—' }}
                        </td>
                        <td>
                            <div style="display:flex; gap:0.35rem">
                                <a href="{{ route('admin.ai.posts.preview', $post->id) }}" class="btn btn-secondary btn-sm" title="Xem trước và duyệt">
                                    <i class="fas fa-eye"></i> Xem trước
                                </a>
                                <a href="{{ route('admin.ai.posts.edit', $post->id) }}" class="btn btn-secondary btn-sm" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2.5rem; color:var(--text-muted)">
                            Chưa có bài viết nào được tạo bằng AI. Bạn có thể bấm "Tạo thử 1 bài" trong Cấu hình nội dung!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Automation Runs Table -->
<div>
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem">
        <h2 style="font-size:1.15rem; font-weight:700"><i class="fas fa-history"></i> Lịch sử chạy tự động gần đây</h2>
        <a href="{{ route('admin.ai.logs.index') }}" class="btn btn-secondary btn-sm">Xem toàn bộ nhật ký</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Automation / Chủ đề</th>
                    <th>Model AI</th>
                    <th>Kết quả bài viết</th>
                    <th>Tokens</th>
                    <th>Trạng thái</th>
                    <th>Thời điểm</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRuns as $run)
                    <tr>
                        <td style="color:var(--text-muted)">{{ $run->id }}</td>
                        <td>
                            <div style="font-weight:600">{{ $run->automation->name ?? 'Chạy thử nghiệm' }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted)">{{ $run->topic_processed }}</div>
                        </td>
                        <td>
                            <span style="font-size:0.8rem">{{ $run->provider_name }}</span>
                            <div style="font-size:0.75rem; color:var(--text-muted)">{{ $run->model_used }}</div>
                        </td>
                        <td>
                            <span style="color:var(--success); font-weight:700">{{ $run->articles_created }} thành công</span>
                            @if($run->articles_failed > 0)
                                / <span style="color:var(--danger)">{{ $run->articles_failed }} lỗi</span>
                            @endif
                        </td>
                        <td style="font-size:0.8rem; color:var(--text-muted)">
                            {{ number_format($run->total_tokens) }} tokens
                        </td>
                        <td>
                            @if($run->status === 'completed')
                                <span class="badge badge-success"><i class="fas fa-check"></i> Hoàn thành</span>
                            @elseif($run->status === 'running')
                                <span class="badge badge-warning"><i class="fas fa-spinner fa-spin"></i> Đang chạy</span>
                            @elseif($run->status === 'partially_completed')
                                <span class="badge badge-warning">Một phần</span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-times"></i> Thất bại</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted); font-size:0.8rem">
                            {{ $run->created_at ? $run->created_at->diffForHumans() : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:2rem; color:var(--text-muted)">
                            Chưa có nhật ký chạy tự động nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
