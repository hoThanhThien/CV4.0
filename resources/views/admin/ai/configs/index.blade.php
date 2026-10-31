@extends('layouts.admin')

@section('title', 'Cấu hình nội dung AI — AI Blog Studio')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-sliders-h" style="color:var(--accent)"></i> Cấu hình nội dung bài viết</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Thiết lập chủ đề, nhóm từ khóa, ngôn ngữ, văn phong và chỉ dẫn riêng cho AI tạo bài viết.
        </p>
    </div>
    <a href="{{ route('admin.ai.configs.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tạo cấu hình mới
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tên cấu hình</th>
                <th>Chủ đề chính</th>
                <th>Chế độ chủ đề</th>
                <th>Provider & Model</th>
                <th>Ngôn ngữ / Tone</th>
                <th>Độ dài</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($configs as $cfg)
                <tr>
                    <td style="color:var(--text-muted)">{{ $cfg->id }}</td>
                    <td>
                        <div style="font-weight:700">{{ $cfg->name }}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted)">Danh mục: {{ $cfg->category }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600; color:var(--text)">{{ $cfg->main_topic }}</div>
                        @if(!empty($cfg->keywords_array))
                            <div style="display:flex; gap:0.25rem; flex-wrap:wrap; margin-top:0.25rem">
                                @foreach(array_slice($cfg->keywords_array, 0, 3) as $kw)
                                    <span class="badge" style="background:rgba(0,0,0,0.05); font-size:0.7rem; color:var(--text-muted)">#{{ $kw }}</span>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($cfg->topic_mode === 'admin_topic')
                            <span class="badge badge-secondary" title="Admin chỉ định chủ đề">Admin Topic</span>
                        @elseif($cfg->topic_mode === 'ai_select')
                            <span class="badge badge-info" title="AI tự chọn từ danh sách từ khóa">AI Auto-select</span>
                        @else
                            <span class="badge badge-success" title="AI nghiên cứu xu hướng">AI Research</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600; font-size:0.85rem">{{ $cfg->provider->name ?? 'Default Provider' }}</div>
                        <code style="font-size:0.75rem; color:var(--text-muted)">{{ $cfg->getEffectiveModel() }}</code>
                    </td>
                    <td>
                        <div>{{ strtoupper($cfg->language) }}</div>
                        <small style="color:var(--text-muted)">{{ $cfg->writing_tone }}</small>
                    </td>
                    <td>
                        <span class="badge" style="background:rgba(99,102,241,0.1); color:var(--accent); text-transform:capitalize">
                            {{ $cfg->article_length }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.35rem; align-items:center">
                            <!-- Nút Test Generate Now -->
                            <form method="POST" action="{{ route('admin.ai.configs.generate-now', $cfg->id) }}" onsubmit="return confirm('Hệ thống sẽ gọi AI tạo ngay 1 bài viết để bạn xem trước và duyệt. Tiếp tục?')">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--accent); font-weight:600" title="Chạy thử nghiệm tạo 1 bài viết ngay">
                                    <i class="fas fa-magic"></i> Tạo thử
                                </button>
                            </form>

                            <a href="{{ route('admin.ai.configs.edit', $cfg->id) }}" class="btn btn-secondary btn-sm" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.ai.configs.destroy', $cfg->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa cấu hình này?')">
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
                    <td colspan="8" style="text-align:center; padding:3rem; color:var(--text-muted)">
                        <i class="fas fa-sliders-h" style="font-size:2.5rem; opacity:0.3; display:block; margin-bottom:0.75rem"></i>
                        Chưa có cấu hình nội dung nào. Nhấn "Tạo cấu hình mới" để bắt đầu!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
