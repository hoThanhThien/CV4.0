@extends('layouts.admin')

@section('title', 'Brochure & Giấy khen')

@section('content')
<div class="page-header">
    <div>
        <h1>Brochure & Giấy khen</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">Quản lý các tài liệu PDF, hồ sơ năng lực, chứng chỉ, bằng cấp và giấy khen.</p>
    </div>
    <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm tài liệu mới
    </a>
</div>

<!-- Category Filter Pills -->
<div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.5rem">
    <a href="{{ route('admin.certificates.index') }}" class="badge {{ !request('category') ? 'badge-purple' : '' }}" style="padding:0.45rem 0.9rem; font-size:0.85rem; text-decoration:none; background:{{ !request('category') ? '' : 'var(--bg-card)' }}; border:1px solid var(--border)">
        Tất cả ({{ \App\Models\Certificate::count() }})
    </a>
    <a href="{{ route('admin.certificates.index', ['category' => 'brochure']) }}" class="badge {{ request('category') == 'brochure' ? 'badge-purple' : '' }}" style="padding:0.45rem 0.9rem; font-size:0.85rem; text-decoration:none; background:{{ request('category') == 'brochure' ? '' : 'var(--bg-card)' }}; border:1px solid var(--border)">
        <i class="fas fa-book-open"></i> Brochure / Hồ sơ
    </a>
    <a href="{{ route('admin.certificates.index', ['category' => 'certificate']) }}" class="badge {{ request('category') == 'certificate' ? 'badge-purple' : '' }}" style="padding:0.45rem 0.9rem; font-size:0.85rem; text-decoration:none; background:{{ request('category') == 'certificate' ? '' : 'var(--bg-card)' }}; border:1px solid var(--border)">
        <i class="fas fa-certificate"></i> Giấy khen / Chứng chỉ
    </a>
    <a href="{{ route('admin.certificates.index', ['category' => 'degree']) }}" class="badge {{ request('category') == 'degree' ? 'badge-purple' : '' }}" style="padding:0.45rem 0.9rem; font-size:0.85rem; text-decoration:none; background:{{ request('category') == 'degree' ? '' : 'var(--bg-card)' }}; border:1px solid var(--border)">
        <i class="fas fa-graduation-cap"></i> Bằng cấp chính quy
    </a>
    <a href="{{ route('admin.certificates.index', ['category' => 'award']) }}" class="badge {{ request('category') == 'award' ? 'badge-purple' : '' }}" style="padding:0.45rem 0.9rem; font-size:0.85rem; text-decoration:none; background:{{ request('category') == 'award' ? '' : 'var(--bg-card)' }}; border:1px solid var(--border)">
        <i class="fas fa-trophy"></i> Giải thưởng
    </a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:60px">Xem trước</th>
                <th>Tiêu đề & Đơn vị cấp</th>
                <th>Phân loại</th>
                <th>Định dạng</th>
                <th>Hiển thị</th>
                <th>Thứ tự</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($certificates as $cert)
            <tr>
                <td>
                    @if($cert->thumbnail)
                        <img src="{{ $cert->thumbnail_url }}" alt="" style="width:48px; height:48px; object-fit:cover; border-radius:8px; border:1px solid var(--border)">
                    @elseif($cert->is_pdf)
                        <div style="width:48px; height:48px; border-radius:8px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.25); display:flex; align-items:center; justify-content:center; color:#ef4444; font-size:1.4rem">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                    @else
                        <div style="width:48px; height:48px; border-radius:8px; background:rgba(6,182,212,0.1); border:1px solid rgba(6,182,212,0.25); display:flex; align-items:center; justify-content:center; color:#06b6d4; font-size:1.4rem">
                            <i class="fas fa-file-image"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <div style="font-weight:700; color:var(--text-primary); font-size:0.95rem">{{ $cert->title }}</div>
                    @if($cert->issuer || $cert->issue_date)
                    <div style="font-size:0.8rem; color:var(--text-muted); margin-top:0.2rem">
                        @if($cert->issuer)<i class="fas fa-building" style="font-size:0.75rem"></i> {{ $cert->issuer }}@endif
                        @if($cert->issuer && $cert->issue_date) • @endif
                        @if($cert->issue_date)<i class="far fa-calendar-alt" style="font-size:0.75rem"></i> {{ $cert->issue_date }}@endif
                    </div>
                    @endif
                </td>
                <td>
                    <span class="badge badge-purple">
                        <i class="{{ $cert->category_icon }}"></i>
                        {{ $cert->category_label }}
                    </span>
                </td>
                <td>
                    @if($cert->is_pdf)
                        <span class="badge" style="background:rgba(239,68,68,0.1); color:#ef4444; border:1px solid rgba(239,68,68,0.2)">
                            <i class="fas fa-file-pdf"></i> PDF
                        </span>
                    @else
                        <span class="badge" style="background:rgba(6,182,212,0.1); color:#06b6d4; border:1px solid rgba(6,182,212,0.2)">
                            <i class="fas fa-image"></i> IMAGE
                        </span>
                    @endif
                    @if($cert->file_size)
                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:0.2rem">{{ $cert->file_size }}</div>
                    @endif
                </td>
                <td>
                    @if($cert->featured)
                        <span class="badge badge-success"><i class="fas fa-check"></i> Trang chủ</span>
                    @else
                        <span class="badge" style="background:rgba(100,116,139,0.1); color:var(--text-muted)">Ẩn</span>
                    @endif
                </td>
                <td style="color:var(--text-muted); font-weight:600">{{ $cert->order }}</td>
                <td>
                    <div style="display:flex; gap:0.4rem; align-items:center">
                        <a href="{{ $cert->file_url }}" target="_blank" class="btn btn-secondary btn-sm" title="Xem file">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <a href="{{ route('admin.certificates.edit', $cert->id) }}" class="btn btn-secondary btn-sm" title="Sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.certificates.destroy', $cert->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài liệu này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:3.5rem 1rem">
                    <div style="font-size:3rem; color:var(--text-muted); margin-bottom:1rem; opacity:0.5">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3 style="font-size:1.15rem; font-weight:700; margin-bottom:0.5rem">Chưa có tài liệu hoặc giấy khen nào</h3>
                    <p style="color:var(--text-muted); font-size:0.9rem; margin-bottom:1.5rem">Tải lên các file PDF brochure, giấy khen, bằng khen hoặc chứng chỉ của bạn để hiển thị cho nhà tuyển dụng.</p>
                    <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm tài liệu đầu tiên
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($certificates->hasPages())
    <div style="margin-top:1.5rem">
        {{ $certificates->links() }}
    </div>
@endif
@endsection
