@extends('layouts.admin')

@section('title', 'Lập lịch tự động — AI Blog Studio')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-clock" style="color:var(--accent)"></i> Lập lịch tạo & đăng bài tự động</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Hệ thống tự động kích hoạt AI theo lịch trình, sinh bài viết và phân bổ lịch xuất bản theo giờ Việt Nam (Asia/Ho_Chi_Minh).
        </p>
    </div>
    <a href="{{ route('admin.ai.automations.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo Automation mới
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tên Automation</th>
                <th>Cấu hình nội dung</th>
                <th>Tần suất & Giờ chạy</th>
                <th>Lần chạy tới (Next Run)</th>
                <th>Chế độ xuất bản</th>
                <th>Bài/Lần</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($automations as $auto)
                <tr>
                    <td style="color:var(--text-muted)">{{ $auto->id }}</td>
                    <td>
                        <div style="font-weight:700">{{ $auto->name }}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted)">Múi giờ: {{ $auto->timezone }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600">{{ $auto->contentConfig->name ?? '—' }}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted)">
                            Model: <code>{{ $auto->contentConfig ? $auto->contentConfig->getEffectiveModel() : 'default' }}</code>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600; text-transform:capitalize">
                            <i class="fas fa-calendar" style="color:var(--accent)"></i> {{ $auto->frequency }}
                        </div>
                        <div style="font-size:0.8rem; color:var(--text-muted)">
                            Lúc: <strong>{{ substr($auto->execution_time, 0, 5) }}</strong>
                            @if($auto->frequency === 'weekly')
                                (Thứ {{ ($auto->day_of_week === 0) ? 'CN' : ($auto->day_of_week + 1) }})
                            @elseif($auto->frequency === 'monthly')
                                (Ngày {{ $auto->day_of_month }})
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($auto->next_run_at && $auto->is_active)
                            <div style="font-size:0.85rem; font-weight:700; color:var(--accent)">
                                {{ $auto->next_run_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                            </div>
                            <small style="color:var(--text-muted)">{{ $auto->next_run_at->diffForHumans() }}</small>
                        @else
                            <span style="color:var(--text-muted); font-size:0.85rem">—</span>
                        @endif
                    </td>
                    <td>
                        @if($auto->publishing_mode === 'manual_approval')
                            <span class="badge badge-warning" title="Tạo bài lưu Pending Review để Admin duyệt"><i class="fas fa-user-check"></i> Manual Approval</span>
                        @elseif($auto->publishing_mode === 'auto_schedule')
                            <span class="badge badge-info" title="Tự động lên lịch đăng cách nhau theo khoảng thời gian"><i class="fas fa-calendar-alt"></i> Auto Schedule</span>
                        @else
                            <span class="badge badge-success" title="Tự động đăng bài ngay khi sinh"><i class="fas fa-bolt"></i> Auto Publish</span>
                        @endif
                    </td>
                    <td style="text-align:center; font-weight:700">
                        {{ $auto->articles_per_run }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.ai.automations.toggle-active', $auto->id) }}">
                            @csrf
                            @if($auto->is_active)
                                <button type="submit" class="badge badge-success" style="border:none; cursor:pointer" title="Bấm để tạm dừng">
                                    <i class="fas fa-play"></i> Bật
                                </button>
                            @else
                                <button type="submit" class="badge badge-secondary" style="border:none; cursor:pointer" title="Bấm để kích hoạt">
                                    <i class="fas fa-pause"></i> Tắt
                                </button>
                            @endif
                        </form>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.35rem; align-items:center">
                            <!-- Nút Run Now -->
                            <form method="POST" action="{{ route('admin.ai.automations.run-now', $auto->id) }}" onsubmit="return confirm('Kích hoạt chạy ngay Automation này?')">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--accent)" title="Chạy ngay lập tức">
                                    <i class="fas fa-bolt"></i> Chạy ngay
                                </button>
                            </form>

                            <a href="{{ route('admin.ai.automations.edit', $auto->id) }}" class="btn btn-secondary btn-sm" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.ai.automations.destroy', $auto->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa lịch tự động này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:3rem; color:var(--text-muted)">
                        <i class="fas fa-calendar-times" style="font-size:2.5rem; opacity:0.3; display:block; margin-bottom:0.75rem"></i>
                        Chưa có lịch tự động nào. Nhấn "Tạo Automation mới" để thiết lập lịch viết bài định kỳ!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
