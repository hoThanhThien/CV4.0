@extends('layouts.admin')

@section('title', 'Quản lý bài viết AI — AI Blog Studio')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-robot" style="color:var(--accent)"></i> Bài viết do AI tạo (AI Posts)</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Theo dõi, kiểm tra, xem trước, phê duyệt và lên lịch xuất bản cho các bài viết được AI sinh tự động.
        </p>
    </div>
    <div style="display:flex; gap:0.5rem">
        <a href="{{ route('admin.ai.configs.index') }}" class="btn btn-secondary">
            <i class="fas fa-sliders-h"></i> Cấu hình nội dung
        </a>
    </div>
</div>

<!-- Status Filter Tabs -->
<div style="display:flex; gap:0.5rem; margin-bottom:1.5rem; flex-wrap:wrap">
    <a href="{{ route('admin.ai.posts.index', ['status' => 'all']) }}" class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
        Tất cả ({{ $counts['all'] }})
    </a>
    <a href="{{ route('admin.ai.posts.index', ['status' => 'pending_review']) }}" class="btn {{ $status === 'pending_review' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
        <i class="fas fa-user-clock"></i> Chờ duyệt ({{ $counts['pending_review'] }})
    </a>
    <a href="{{ route('admin.ai.posts.index', ['status' => 'scheduled']) }}" class="btn {{ $status === 'scheduled' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
        <i class="fas fa-calendar-alt"></i> Đã lên lịch ({{ $counts['scheduled'] }})
    </a>
    <a href="{{ route('admin.ai.posts.index', ['status' => 'draft']) }}" class="btn {{ $status === 'draft' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
        Nháp ({{ $counts['draft'] }})
    </a>
    <a href="{{ route('admin.ai.posts.index', ['status' => 'published']) }}" class="btn {{ $status === 'published' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
        <i class="fas fa-check-circle"></i> Đã xuất bản ({{ $counts['published'] }})
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tiêu đề & Slug</th>
                <th>Provider & Model</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Thời điểm tạo</th>
                <th>Lịch đăng / Xuất bản</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
                <tr>
                    <td style="color:var(--text-muted)">{{ $post->id }}</td>
                    <td>
                        <div style="font-weight:700; max-width:320px">{{ Str::limit($post->title, 60) }}</div>
                        <code style="font-size:0.75rem; color:var(--accent-light); background:rgba(99,102,241,0.1); padding:0.15rem 0.4rem; border-radius:4px">/{{ $post->slug }}</code>
                        @if($post->last_edited_by_admin)
                            <span class="badge" style="background:rgba(245,158,11,0.15); color:var(--warning); font-size:0.65rem">Đã sửa tay</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600; font-size:0.85rem">{{ $post->provider->name ?? 'AI' }}</div>
                        <code style="font-size:0.75rem; color:var(--text-muted)">{{ $post->provider->model ?? 'Default' }}</code>
                    </td>
                    <td>
                        <span class="badge" style="background:rgba(0,0,0,0.05); color:var(--text)">{{ $post->category ?? 'Tech' }}</span>
                    </td>
                    <td>
                        @if($post->status === 'published')
                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Đã xuất bản</span>
                        @elseif($post->status === 'scheduled')
                            <span class="badge badge-info" style="background:rgba(6,182,212,0.15); color:#0891b2"><i class="fas fa-clock"></i> Đã lên lịch</span>
                        @elseif($post->status === 'pending_review')
                            <span class="badge badge-warning"><i class="fas fa-user-clock"></i> Chờ duyệt</span>
                        @else
                            <span class="badge badge-secondary">Bản nháp</span>
                        @endif
                    </td>
                    <td style="font-size:0.8rem; color:var(--text-muted)">
                        {{ $post->created_at ? $post->created_at->diffForHumans() : '—' }}
                    </td>
                    <td style="font-size:0.82rem">
                        @if($post->status === 'scheduled')
                            <div style="font-weight:700; color:#0891b2">
                                <i class="fas fa-calendar-alt"></i> {{ $post->published_at ? $post->published_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : '—' }}
                            </div>
                        @elseif($post->status === 'published')
                            <div style="color:var(--success)">
                                <i class="fas fa-check"></i> {{ $post->published_at ? $post->published_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y') : '—' }}
                            </div>
                        @else
                            <span style="color:var(--text-muted)">Chưa đăng</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:0.35rem; align-items:center">
                            <a href="{{ route('admin.ai.posts.preview', $post->id) }}" class="btn btn-secondary btn-sm" title="Xem trước và duyệt">
                                <i class="fas fa-eye"></i> Xem trước
                            </a>

                            @if($post->status === 'pending_review')
                                <form method="POST" action="{{ route('admin.ai.posts.approve', $post->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--success)" title="Phê duyệt vào Bản nháp">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                </form>
                            @endif

                            @if($post->status !== 'published')
                                <form method="POST" action="{{ route('admin.ai.posts.publish-now', $post->id) }}"
                                    data-confirm-type="publish"
                                    data-confirm-title="Xuất bản bài viết ngay?"
                                    data-confirm="Bài viết sẽ được đăng công khai ngay lập tức trên trang Blog của bạn.">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--accent)" title="Đăng ngay lập tức">
                                        <i class="fas fa-globe"></i> Đăng ngay
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('admin.ai.posts.edit', $post->id) }}" class="btn btn-secondary btn-sm" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.ai.posts.destroy', $post->id) }}"
                                data-confirm-type="danger"
                                data-confirm-title="Xóa bài viết AI?"
                                data-confirm="Bạn có chắc chắn muốn xóa vĩnh viễn bài viết này?">
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
                        <i class="fas fa-newspaper" style="font-size:2.5rem; opacity:0.3; display:block; margin-bottom:0.75rem"></i>
                        Không có bài viết AI nào phù hợp với bộ lọc hiện tại.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($posts->hasPages())
    <div style="margin-top:1.5rem">
        {{ $posts->links() }}
    </div>
@endif
@endsection
