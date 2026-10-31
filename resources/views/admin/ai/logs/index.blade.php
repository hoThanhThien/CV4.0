@extends('layouts.admin')

@section('title', 'Nhật ký tự động hóa — AI Blog Studio')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-history" style="color:var(--accent)"></i> Nhật ký hoạt động tự động hóa</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Theo dõi chi tiết các lần chạy sinh bài của AI, kiểm tra lỗi và token usage.
        </p>
    </div>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Automation / Đợt chạy</th>
                <th>Provider & Model</th>
                <th>Chủ đề xử lý</th>
                <th>Kết quả tạo bài</th>
                <th>Token Usage</th>
                <th>Trạng thái</th>
                <th>Thời điểm</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($runs as $run)
                <tr>
                    <td style="color:var(--text-muted)">{{ $run->id }}</td>
                    <td>
                        <div style="font-weight:700">{{ $run->automation->name ?? 'Tạo thử nghiệm' }}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted)">Cấu hình: {{ $run->contentConfig->name ?? '—' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600; font-size:0.85rem">{{ $run->provider_name }}</div>
                        <code style="font-size:0.75rem; color:var(--text-muted)">{{ $run->model_used }}</code>
                    </td>
                    <td>
                        <div style="max-width:240px; font-size:0.85rem">{{ Str::limit($run->topic_processed, 50) }}</div>
                    </td>
                    <td>
                        <span style="color:var(--success); font-weight:700">{{ $run->articles_created }} thành công</span>
                        @if($run->articles_failed > 0)
                            <div style="color:var(--danger); font-size:0.8rem">{{ $run->articles_failed }} lỗi</div>
                        @endif
                    </td>
                    <td style="font-size:0.85rem; color:var(--text-muted)">
                        <div>{{ number_format($run->total_tokens) }} tokens</div>
                    </td>
                    <td>
                        @if($run->status === 'completed')
                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Hoàn thành</span>
                        @elseif($run->status === 'running')
                            <span class="badge badge-warning"><i class="fas fa-spinner fa-spin"></i> Đang chạy</span>
                        @elseif($run->status === 'partially_completed')
                            <span class="badge badge-warning"><i class="fas fa-exclamation-circle"></i> Một phần</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Thất bại</span>
                        @endif
                    </td>
                    <td style="font-size:0.8rem; color:var(--text-muted)">
                        <div>{{ $run->created_at ? $run->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : '—' }}</div>
                        <small>({{ $run->created_at ? $run->created_at->diffForHumans() : '' }})</small>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.35rem; align-items:center">
                            <a href="{{ route('admin.ai.logs.show', $run->id) }}" class="btn btn-secondary btn-sm" title="Xem chi tiết">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>

                            @if($run->status === 'failed' && $run->automation_id)
                                <form method="POST" action="{{ route('admin.ai.logs.retry', $run->id) }}" onsubmit="return confirm('Thử lại lượt chạy này?')">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--warning)" title="Thử lại">
                                        <i class="fas fa-redo"></i> Thử lại
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:3rem; color:var(--text-muted)">
                        <i class="fas fa-history" style="font-size:2.5rem; opacity:0.3; display:block; margin-bottom:0.75rem"></i>
                        Chưa có nhật ký hoạt động nào.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($runs->hasPages())
    <div style="margin-top:1.5rem">
        {{ $runs->links() }}
    </div>
@endif
@endsection
