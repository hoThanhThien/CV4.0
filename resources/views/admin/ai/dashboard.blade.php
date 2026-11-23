@extends('layouts.admin')

@section('title', 'AI Blog Studio — Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h1>
            <i data-lucide="sparkles"></i> AI Blog Automation &amp; Analytics
        </h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem; width:100%">
            Theo dõi lượt bài AI, hiệu suất pipeline, token sử dụng và trạng thái hộp thư liên hệ theo thời gian thực.
        </p>
    </div>
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap">
        <a href="{{ route('admin.ai.configs.create') }}" class="btn btn-secondary">
            <i data-lucide="plus"></i> Tạo cấu hình nội dung
        </a>
        <a href="{{ route('admin.ai.automations.create') }}" class="btn btn-primary">
            <i data-lucide="clock"></i> Lập lịch tự động mới
        </a>
    </div>
</div>

<!-- Stats Overview -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));">
    <div class="stat-card stat-purple">
        <div class="stat-icon"><i data-lucide="bot"></i></div>
        <div class="stat-label">Tổng bài viết AI</div>
        <div class="stat-value">{{ $totalAiPosts }}</div>
        <div class="stat-sub">+{{ $aiPostStats['generated_7d'] ?? 0 }} bài 7 ngày qua</div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-icon"><i data-lucide="check-circle-2"></i></div>
        <div class="stat-label">Đã xuất bản (Live)</div>
        <div class="stat-value">{{ $publishedAiPosts }}</div>
        <div class="stat-sub">Chiếm {{ $aiPostStats['ai_share_pct'] ?? 0 }}% tổng blog</div>
    </div>
    <div class="stat-card" style="border-left: 3px solid var(--warning);">
        <div class="stat-icon" style="background:rgba(245,158,11,0.14); color:var(--warning)"><i data-lucide="hourglass"></i></div>
        <div class="stat-label">Chờ duyệt (Pending)</div>
        <div class="stat-value" style="color:var(--warning)">{{ $pendingAiPosts }}</div>
        <div class="stat-sub">{{ $aiPostStats['draft'] ?? 0 }} nháp · {{ $scheduledAiPosts }} hẹn giờ</div>
    </div>
    <div class="stat-card stat-cyan">
        <div class="stat-icon"><i data-lucide="zap"></i></div>
        <div class="stat-label">Automation đang bật</div>
        <div class="stat-value">{{ $activeAutomations }}</div>
        <div class="stat-sub">Tỷ lệ chạy đạt {{ $runStats['success_rate'] ?? 100 }}%</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i data-lucide="activity"></i></div>
        <div class="stat-label">Tokens (30 ngày)</div>
        <div class="stat-value" style="font-size:1.55rem">{{ number_format($runStats['total_tokens_30d'] ?? 0) }}</div>
        <div class="stat-sub">{{ $runStats['articles_created_30d'] ?? 0 }} bài · {{ $failedRuns }} run lỗi</div>
    </div>
    <div class="stat-card {{ ($contactStats['unread'] ?? 0) > 0 ? 'stat-pink' : 'stat-green' }}">
        <div class="stat-icon"><i data-lucide="inbox"></i></div>
        <div class="stat-label">Liên hệ chưa đọc</div>
        <div class="stat-value" style="{{ ($contactStats['unread'] ?? 0) > 0 ? 'color:var(--danger)' : '' }}">{{ $contactStats['unread'] ?? 0 }}</div>
        <div class="stat-sub">Tổng {{ $contactStats['total'] ?? 0 }} · Đã xử lý {{ $contactStats['read_rate'] ?? 100 }}%</div>
    </div>
</div>

<!-- Row: AI Article Throughput & Contact Inbox Analytics -->
<div class="analytics-grid">
    <!-- AI Article Throughput & Quality -->
    <div class="panel-card">
        <div class="panel-header">
            <span class="panel-title"><i data-lucide="bar-chart-3"></i> Thống kê Lượt bài AI (7 ngày &amp; 30 ngày)</span>
            <span class="badge badge-purple">{{ $aiPostStats['generated_30d'] ?? 0 }} bài / 30d</span>
        </div>

        @php
            $maxDayCount = max(1, collect($dailyAiTrend ?? [])->max('posts') ?? 1);
            $bilingualPct = $totalAiPosts > 0 ? (int) round((($aiPostStats['bilingual_ready'] ?? 0) / $totalAiPosts) * 100) : 0;
        @endphp

        <div style="display:grid; grid-template-columns: repeat(7, 1fr); gap:0.5rem; align-items:end; height:104px; padding:0.75rem 0.65rem 0.45rem; background:var(--bg-subtle); border:1px solid var(--border); border-radius:10px; margin-bottom:1rem;">
            @foreach(($dailyAiTrend ?? []) as $day)
                @php
                    $barH = $day['posts'] > 0 ? max(16, (int) round(($day['posts'] / $maxDayCount) * 56)) : 4;
                @endphp
                <div style="display:flex; flex-direction:column; align-items:center; justify-content:flex-end; gap:0.25rem; height:100%" title="{{ $day['date'] }}: {{ $day['posts'] }} bài AI / {{ $day['runs'] }} lần chạy">
                    <span class="font-mono" style="font-size:0.68rem; color:var(--text-muted); font-weight:600">{{ $day['posts'] }}</span>
                    <div style="width:100%; max-width:22px; height:{{ $barH }}px; border-radius:5px; background:{{ $day['posts'] > 0 ? 'var(--gradient)' : 'var(--border-strong)' }}; transition:height 0.25s ease"></div>
                    <span class="font-mono" style="font-size:0.65rem; color:var(--text-muted)">{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>

        <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:var(--text-secondary)">
            <span>Tỷ lệ hoàn tất Pipeline ({{ $runStats['completed_runs'] ?? 0 }}/{{ $runStats['total_runs'] ?? 0 }} runs)</span>
            <strong class="font-mono" style="color:var(--accent)">{{ $runStats['success_rate'] ?? 100 }}%</strong>
        </div>
        <div class="metric-bar-track">
            <div class="metric-bar-fill" style="width: {{ min(100, $runStats['success_rate'] ?? 100) }}%"></div>
        </div>

        <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:var(--text-secondary)">
            <span>Bài AI sẵn sàng Song ngữ EN/VI ({{ $aiPostStats['bilingual_ready'] ?? 0 }}/{{ $totalAiPosts }})</span>
            <strong class="font-mono" style="color:var(--accent)">{{ $bilingualPct }}%</strong>
        </div>
        <div class="metric-bar-track" style="margin-bottom:0.25rem">
            <div class="metric-bar-fill" style="width: {{ min(100, $bilingualPct) }}%"></div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.85rem; padding-top:0.75rem; border-top:1px solid var(--border); font-size:0.8rem; color:var(--text-muted)">
            <span>Lỗi / Hủy: <strong style="color:var(--danger)">{{ $aiPostStats['failed'] ?? 0 }}</strong> bài</span>
            <a href="{{ route('admin.ai.logs.index') }}" style="color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:0.25rem">
                Nhật ký chạy <i data-lucide="arrow-right" style="width:13px; height:13px"></i>
            </a>
        </div>
    </div>

    <!-- Contact Messages Status Analytics -->
    <div class="panel-card">
        <div class="panel-header">
            <span class="panel-title"><i data-lucide="mail-open"></i> Trạng thái Liên hệ (Contact Status)</span>
            @if(($contactStats['unread'] ?? 0) > 0)
                <span class="badge badge-danger">{{ $contactStats['unread'] }} chưa đọc</span>
            @else
                <span class="badge badge-success">Đã xử lý 100%</span>
            @endif
        </div>

        <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:0.65rem; margin-bottom:0.9rem">
            <div style="padding:0.7rem 0.75rem; border-radius:10px; background:var(--bg-subtle); border:1px solid var(--border)">
                <div style="font-size:0.72rem; color:var(--text-muted)">Tổng liên hệ</div>
                <div class="font-display" style="font-size:1.25rem; font-weight:700; color:var(--text)">{{ $contactStats['total'] ?? 0 }}</div>
            </div>
            <div style="padding:0.7rem 0.75rem; border-radius:10px; background:var(--bg-subtle); border:1px solid var(--border)">
                <div style="font-size:0.72rem; color:var(--text-muted)">7 ngày qua</div>
                <div class="font-display" style="font-size:1.25rem; font-weight:700; color:var(--accent)">{{ $contactStats['last_7d'] ?? 0 }}</div>
            </div>
            <div style="padding:0.7rem 0.75rem; border-radius:10px; background:var(--bg-subtle); border:1px solid var(--border)">
                <div style="font-size:0.72rem; color:var(--text-muted)">30 ngày qua</div>
                <div class="font-display" style="font-size:1.25rem; font-weight:700; color:var(--text)">{{ $contactStats['last_30d'] ?? 0 }}</div>
            </div>
        </div>

        <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:var(--text-secondary)">
            <span>Tỷ lệ đã đọc ({{ $contactStats['read'] ?? 0 }} đã xem / {{ $contactStats['unread'] ?? 0 }} mới)</span>
            <strong class="font-mono" style="color:var(--accent)">{{ $contactStats['read_rate'] ?? 100 }}%</strong>
        </div>
        <div class="metric-bar-track">
            <div class="metric-bar-fill" style="width: {{ min(100, $contactStats['read_rate'] ?? 100) }}%"></div>
        </div>

        <div style="display:flex; flex-direction:column; gap:0.45rem; margin-top:0.75rem">
            @forelse(($recentMessages ?? collect())->take(3) as $msg)
                <a href="{{ route('admin.messages.show', $msg->id) }}" style="display:flex; align-items:center; justify-content:space-between; gap:0.65rem; padding:0.5rem 0.7rem; border-radius:9px; background:var(--bg-subtle); border:1px solid var(--border); text-decoration:none; color:var(--text); font-size:0.82rem">
                    <span style="min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap">
                        <strong style="font-weight:600">{{ $msg->name }}</strong>
                        <span style="color:var(--text-muted)"> · {{ Str::limit($msg->subject ?: $msg->message, 34) }}</span>
                    </span>
                    <span class="badge {{ $msg->is_read ? 'badge-secondary' : 'badge-danger' }}" style="font-size:0.68rem">
                        {{ $msg->is_read ? 'Đã đọc' : 'Mới' }}
                    </span>
                </a>
            @empty
                <div style="padding:0.85rem; text-align:center; border-radius:9px; background:var(--bg-subtle); color:var(--text-muted); font-size:0.82rem">
                    Hộp thư liên hệ hiện đang trống.
                </div>
            @endforelse
        </div>

        <div style="display:flex; justify-content:flex-end; margin-top:0.75rem; padding-top:0.65rem; border-top:1px solid var(--border)">
            <a href="{{ route('admin.messages.index') }}" style="font-size:0.8rem; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:0.25rem">
                Mở toàn bộ Hộp thư <i data-lucide="arrow-right" style="width:13px; height:13px"></i>
            </a>
        </div>
    </div>
</div>

<!-- Row: Next Run & Providers status -->
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; margin-bottom:2rem;">
    <!-- Next Scheduled Run Card -->
    <div class="stat-card" style="display:flex; flex-direction:column; justify-content:space-between">
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem">
                <span class="panel-title"><i data-lucide="hourglass"></i> Lịch chạy tự động tiếp theo</span>
                @if($nextScheduledRun)
                    <span class="badge badge-success">Đang kích hoạt</span>
                @else
                    <span class="badge badge-secondary">Chưa có lịch</span>
                @endif
            </div>

            @if($nextScheduledRun)
                <div style="margin-top:0.5rem">
                    <div style="font-size:1.15rem; font-weight:800; color:var(--accent)">
                        {{ $nextScheduledRun->name }}
                    </div>
                    <div style="font-size:0.85rem; color:var(--text-muted); margin-top:0.35rem">
                        Cấu hình: <strong>{{ $nextScheduledRun->contentConfig->name ?? '—' }}</strong> |
                        Chế độ: <strong>{{ strtoupper(str_replace('_', ' ', $nextScheduledRun->publishing_mode)) }}</strong>
                    </div>
                    <div style="margin-top:0.75rem; padding:0.65rem 0.85rem; background:var(--accent-tint); border:1px solid var(--border); border-radius:9px; font-size:0.85rem; display:flex; align-items:center; gap:0.45rem; flex-wrap:wrap">
                        <i data-lucide="calendar" style="width:15px; height:15px; color:var(--accent)"></i> Thời điểm chạy:
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
                        <i data-lucide="plus"></i> Tạo Automation
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Active Providers Quick Status -->
    <div class="stat-card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem">
            <span class="panel-title"><i data-lucide="plug"></i> Nhà cung cấp AI (Providers)</span>
            <a href="{{ route('admin.ai.providers.index') }}" style="font-size:0.8rem; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:0.25rem">
                Quản lý <i data-lucide="arrow-right" style="width:13px; height:13px"></i>
            </a>
        </div>
        <div style="display:flex; flex-direction:column; gap:0.5rem; margin-top:0.5rem">
            @forelse($providers as $p)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:0.55rem 0.75rem; background:var(--bg-subtle); border:1px solid var(--border); border-radius:9px; font-size:0.85rem; gap:0.5rem; flex-wrap:wrap">
                    <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap">
                        <span style="font-weight:600">{{ $p->name }}</span>
                        <code style="font-size:0.75rem; color:var(--text-muted)">{{ $p->model }}</code>
                        @if($p->is_default)
                            <span class="badge badge-success" style="font-size:0.65rem">Default</span>
                        @endif
                    </div>
                    <div>
                        @if($p->connection_status === 'connected')
                            <span class="badge badge-success"><i data-lucide="check"></i> Connected</span>
                        @elseif($p->connection_status === 'failed')
                            <span class="badge badge-danger"><i data-lucide="x"></i> Error</span>
                        @else
                            <span class="badge badge-secondary">Untested</span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:1.5rem; color:var(--text-muted); font-size:0.85rem">
                    Chưa có AI Provider nào. <a href="{{ route('admin.ai.providers.create') }}" style="color:var(--accent); font-weight:600">Thêm Provider</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent AI Posts Table -->
<div style="margin-bottom:2rem">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; gap:0.75rem; flex-wrap:wrap">
        <h2 style="font-size:1.12rem; font-weight:700; display:inline-flex; align-items:center; gap:0.45rem">
            <i data-lucide="bot" style="width:19px; height:19px; color:var(--accent)"></i> Bài viết do AI tạo gần đây
        </h2>
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
                            <span class="badge badge-purple">
                                {{ $post->category ?? 'Tech' }}
                            </span>
                        </td>
                        <td>
                            @if($post->status === 'published')
                                <span class="badge badge-success"><i data-lucide="check-circle-2"></i> Đã xuất bản</span>
                            @elseif($post->status === 'scheduled')
                                <span class="badge badge-info">
                                    <i data-lucide="clock"></i> Lên lịch: {{ $post->published_at ? $post->published_at->timezone('Asia/Ho_Chi_Minh')->format('d/m H:i') : '' }}
                                </span>
                            @elseif($post->status === 'pending_review')
                                <span class="badge badge-warning"><i data-lucide="hourglass"></i> Chờ duyệt</span>
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
                                    <i data-lucide="eye"></i> Xem trước
                                </a>
                                <a href="{{ route('admin.ai.posts.edit', $post->id) }}" class="btn btn-secondary btn-sm" title="Chỉnh sửa">
                                    <i data-lucide="pen-square"></i>
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
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; gap:0.75rem; flex-wrap:wrap">
        <h2 style="font-size:1.12rem; font-weight:700; display:inline-flex; align-items:center; gap:0.45rem">
            <i data-lucide="history" style="width:19px; height:19px; color:var(--accent)"></i> Lịch sử chạy tự động gần đây
        </h2>
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
                        <td style="font-size:0.8rem; color:var(--text-muted)" class="font-mono">
                            {{ number_format($run->total_tokens) }} tokens
                        </td>
                        <td>
                            @if($run->status === 'completed')
                                <span class="badge badge-success"><i data-lucide="check"></i> Hoàn thành</span>
                            @elseif($run->status === 'running')
                                <span class="badge badge-warning"><i data-lucide="clock"></i> Đang chạy</span>
                            @elseif($run->status === 'partially_completed')
                                <span class="badge badge-warning">Một phần</span>
                            @else
                                <span class="badge badge-danger"><i data-lucide="x"></i> Thất bại</span>
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
