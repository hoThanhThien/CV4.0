@extends('layouts.admin')

@section('title', 'AI Providers — AI Blog Studio')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-brain" style="color:var(--accent)"></i> AI Providers</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Quản lý khóa API, models và kết nối tới OpenAI, Google Gemini, DeepSeek và Custom Models.
        </p>
    </div>
    <a href="{{ route('admin.ai.providers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Provider mới
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nhà cung cấp</th>
                <th>Loại Provider</th>
                <th>Model</th>
                <th>API Key</th>
                <th>Kết nối</th>
                <th>Mặc định</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($providers as $provider)
                <tr>
                    <td style="color:var(--text-muted)">{{ $provider->id }}</td>
                    <td>
                        <div style="font-weight:700">{{ $provider->name }}</div>
                        @if($provider->base_url)
                            <div style="font-size:0.75rem; color:var(--text-muted); word-break:break-all">{{ Str::limit($provider->base_url, 40) }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge" style="background:rgba(99,102,241,0.1); color:var(--accent); text-transform:uppercase; font-size:0.7rem">
                            {{ $provider->provider_type }}
                        </span>
                    </td>
                    <td>
                        <code style="font-size:0.8rem; background:rgba(0,0,0,0.05); padding:0.2rem 0.45rem; border-radius:5px">{{ $provider->model }}</code>
                    </td>
                    <td style="font-size:0.82rem; font-family:monospace; color:var(--text-muted)">
                        {{ $provider->masked_api_key }}
                    </td>
                    <td>
                        <div id="conn-badge-{{ $provider->id }}">
                            @if($provider->connection_status === 'connected')
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Connected</span>
                            @elseif($provider->connection_status === 'failed')
                                <span class="badge badge-danger" title="{{ $provider->last_error_message }}"><i class="fas fa-exclamation-circle"></i> Failed</span>
                            @else
                                <span class="badge badge-secondary">Untested</span>
                            @endif
                        </div>
                        @if($provider->last_tested_at)
                            <div style="font-size:0.7rem; color:var(--text-muted); margin-top:0.2rem">
                                {{ $provider->last_tested_at->diffForHumans() }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($provider->is_default)
                            <span class="badge badge-success" style="font-size:0.7rem"><i class="fas fa-star"></i> Mặc định</span>
                        @else
                            <form method="POST" action="{{ route('admin.ai.providers.set-default', $provider->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm" style="font-size:0.75rem; padding:0.2rem 0.5rem" title="Đặt làm mặc định">
                                    Đặt
                                </button>
                            </form>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.ai.providers.toggle-active', $provider->id) }}">
                            @csrf
                            @if($provider->is_active)
                                <button type="submit" class="badge badge-success" style="border:none; cursor:pointer" title="Bấm để tắt">
                                    <i class="fas fa-toggle-on"></i> Đang bật
                                </button>
                            @else
                                <button type="submit" class="badge badge-secondary" style="border:none; cursor:pointer" title="Bấm để bật">
                                    <i class="fas fa-toggle-off"></i> Tạm tắt
                                </button>
                            @endif
                        </form>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.35rem; align-items:center">
                            <!-- Nút Test Connection AJAX -->
                            <button type="button" class="btn btn-secondary btn-sm test-conn-btn"
                                data-id="{{ $provider->id }}"
                                data-url="{{ route('admin.ai.providers.test', $provider->id) }}"
                                title="Kiểm tra kết nối API thật">
                                <i class="fas fa-plug"></i> Test
                            </button>

                            <a href="{{ route('admin.ai.providers.edit', $provider->id) }}" class="btn btn-secondary btn-sm" title="Sửa cấu hình">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.ai.providers.destroy', $provider->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa Provider này?')">
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
                        <i class="fas fa-brain" style="font-size:2.5rem; opacity:0.3; display:block; margin-bottom:0.75rem"></i>
                        Chưa có nhà cung cấp AI nào. Vui lòng nhấn "Thêm Provider mới" để bắt đầu!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal hiển thị kết quả Test Connection -->
<div id="test-result-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center; padding:1.5rem">
    <div style="background:var(--bg-card); max-width:500px; width:100%; border-radius:12px; border:1px solid var(--border); box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); overflow:hidden">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between">
            <h3 id="modal-title" style="font-size:1.1rem; font-weight:700">Kết quả kiểm tra kết nối API</h3>
            <button type="button" onclick="closeTestModal()" style="background:none; border:none; font-size:1.2rem; cursor:pointer; color:var(--text-muted)">&times;</button>
        </div>
        <div style="padding:1.5rem" id="modal-body">
            <!-- Nội dung trả về từ AJAX -->
        </div>
        <div style="padding:1rem 1.5rem; border-top:1px solid var(--border); display:flex; justify-content:flex-end">
            <button type="button" class="btn btn-secondary" onclick="closeTestModal()">Đóng</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.test-conn-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const providerId = this.dataset.id;
            const testUrl = this.dataset.url;
            const originalHtml = this.innerHTML;
            const badgeContainer = document.getElementById('conn-badge-' + providerId);

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';

            fetch(testUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                showTestModal(data);
                if (data.success) {
                    if (badgeContainer) {
                        badgeContainer.innerHTML = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Connected</span>';
                    }
                } else {
                    if (badgeContainer) {
                        badgeContainer.innerHTML = '<span class="badge badge-danger"><i class="fas fa-exclamation-circle"></i> Failed</span>';
                    }
                }
            })
            .catch(err => {
                showTestModal({
                    success: false,
                    message: 'Lỗi gửi yêu cầu: ' + err.message,
                    code: 'NETWORK_ERROR'
                });
            })
            .finally(() => {
                this.disabled = false;
                this.innerHTML = originalHtml;
            });
        });
    });

    function showTestModal(data) {
        const modal = document.getElementById('test-result-modal');
        const body = document.getElementById('modal-body');

        let icon = data.success
            ? '<div style="width:48px; height:48px; border-radius:50%; background:rgba(16,185,129,0.15); color:var(--success); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1rem"><i class="fas fa-check"></i></div>'
            : '<div style="width:48px; height:48px; border-radius:50%; background:rgba(239,68,68,0.15); color:var(--danger); display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1rem"><i class="fas fa-exclamation-triangle"></i></div>';

        let statusClass = data.success ? 'color:var(--success)' : 'color:var(--danger)';
        let statusTitle = data.success ? 'KẾT NỐI THÀNH CÔNG!' : 'KẾT NỐI THẤT BẠI';

        body.innerHTML = `
            ${icon}
            <h4 style="font-size:1.1rem; font-weight:700; margin-bottom:0.5rem; ${statusClass}">${statusTitle}</h4>
            <div style="background:var(--bg); border:1px solid var(--border); border-radius:8px; padding:1rem; font-size:0.9rem; margin-top:0.75rem; line-height:1.6">
                ${data.message}
            </div>
            ${data.code ? `<div style="margin-top:0.5rem; font-size:0.8rem; color:var(--text-muted)">Mã phản hồi kỹ thuật: <code>${data.code}</code></div>` : ''}
        `;

        modal.style.display = 'flex';
    }

    function closeTestModal() {
        document.getElementById('test-result-modal').style.display = 'none';
    }
</script>
@endsection
