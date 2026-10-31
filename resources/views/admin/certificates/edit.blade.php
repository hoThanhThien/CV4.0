@extends('layouts.admin')

@section('title', 'Chỉnh sửa Brochure / Giấy khen')

@section('content')
<div class="page-header">
    <div>
        <h1>Chỉnh sửa: {{ $certificate->title }}</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">Cập nhật thông tin, thay thế file đính kèm hoặc ảnh bìa.</p>
    </div>
    <div style="display:flex; gap:0.5rem">
        <a href="{{ $certificate->file_url }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-external-link-alt"></i> Xem file hiện tại
        </a>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.certificates.update', $certificate->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div style="display:grid; grid-template-columns:1fr 360px; gap:1.5rem; align-items:start">
        <!-- Main Form -->
        <div>
            <div class="form-card" style="margin-bottom:1.5rem">
                <h3 style="font-size:1.1rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem; color:var(--text)">
                    <i class="fas fa-info-circle" style="color:var(--primary)"></i> Thông tin tài liệu
                </h3>

                <div class="form-group">
                    <label class="form-label" for="title">Tiêu đề / Tên chứng chỉ, tài liệu <span style="color:#ef4444">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $certificate->title) }}" placeholder="VD: Hồ sơ năng lực & Brochure 2026, Giấy khen Giải Nhì NCKH..." required>
                    @error('title')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem">
                    <div class="form-group">
                        <label class="form-label" for="category">Phân loại <span style="color:#ef4444">*</span></label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="brochure" {{ old('category', $certificate->category) == 'brochure' ? 'selected' : '' }}>📄 Brochure / Hồ sơ năng lực</option>
                            <option value="certificate" {{ old('category', $certificate->category) == 'certificate' ? 'selected' : '' }}>📜 Giấy khen / Chứng chỉ</option>
                            <option value="degree" {{ old('category', $certificate->category) == 'degree' ? 'selected' : '' }}>🎓 Bằng tốt nghiệp / Bằng cấp</option>
                            <option value="award" {{ old('category', $certificate->category) == 'award' ? 'selected' : '' }}>🏆 Giải thưởng / Huy chương</option>
                            <option value="other" {{ old('category', $certificate->category) == 'other' ? 'selected' : '' }}>📁 Tài liệu khác</option>
                        </select>
                        @error('category')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="issuer">Đơn vị cấp / Tổ chức</label>
                        <input type="text" id="issuer" name="issuer" class="form-control" value="{{ old('issuer', $certificate->issuer) }}" placeholder="VD: ĐH Công nghiệp Hà Nội, HaUI, Cisco...">
                        @error('issuer')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="issue_date">Thời gian / Ngày cấp</label>
                    <input type="text" id="issue_date" name="issue_date" class="form-control" value="{{ old('issue_date', $certificate->issue_date) }}" placeholder="VD: 2026, 06/2026, hoặc Năm 2024 - 2026">
                    @error('issue_date')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Mô tả / Ghi chú chi tiết</label>
                    <textarea id="description" name="description" class="form-control" rows="5" placeholder="Mô tả nội dung tài liệu, thành tích đạt được, ý nghĩa hoặc thông số đề tài...">{{ old('description', $certificate->description) }}</textarea>
                    <span style="font-size:0.8rem; color:var(--text-muted); display:block; margin-top:0.35rem">
                        Hỗ trợ xuống dòng và các gạch đầu dòng để trình bày thông tin đẹp mắt trên website.
                    </span>
                    @error('description')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>

        <!-- Sidebar / Files Upload -->
        <div>
            <!-- Main Document File -->
            <div class="form-card" style="margin-bottom:1.5rem">
                <h3 style="font-size:1.05rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; color:var(--text)">
                    <i class="fas fa-file-alt" style="color:var(--primary)"></i> File tài liệu
                </h3>

                <!-- Current file preview -->
                <div style="padding:0.75rem; border-radius:8px; background:rgba(99,102,241,0.06); border:1px solid var(--border); margin-bottom:1rem; display:flex; align-items:center; gap:0.75rem">
                    <div style="font-size:1.8rem; color:{{ $certificate->is_pdf ? '#ef4444' : '#06b6d4' }}">
                        <i class="fas {{ $certificate->is_pdf ? 'fa-file-pdf' : 'fa-file-image' }}"></i>
                    </div>
                    <div style="flex:1; min-width:0">
                        <div style="font-size:0.85rem; font-weight:600; text-overflow:ellipsis; overflow:hidden; white-space:nowrap">
                            {{ basename($certificate->file_path) }}
                        </div>
                        <div style="font-size:0.75rem; color:var(--text-muted)">
                            Định dạng: <span style="text-transform:uppercase; font-weight:600">{{ $certificate->file_type }}</span> &bull; Dung lượng: {{ $certificate->file_size ?? 'N/A' }}
                        </div>
                    </div>
                    <a href="{{ $certificate->file_url }}" target="_blank" class="btn btn-secondary" style="padding:0.35rem 0.6rem; font-size:0.8rem" title="Xem file">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>

                <div class="form-group">
                    <label class="form-label" for="file">Tải file mới thay thế (Nếu cần)</label>
                    <input type="file" id="file" name="file" class="form-control" accept=".pdf,image/jpeg,image/png,image/webp" onchange="handleFileSelect(this)">
                    <span style="font-size:0.78rem; color:var(--text-muted); display:block; margin-top:0.35rem">
                        Để trống nếu muốn giữ nguyên file hiện tại. Hỗ trợ <strong>.PDF, .JPG, .PNG, .WEBP</strong> (tối đa 25MB).
                    </span>
                    @error('file')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror

                    <!-- Preview box when new file selected -->
                    <div id="file-info-preview" style="display:none; margin-top:0.85rem; padding:0.75rem; border-radius:8px; background:rgba(99,102,241,0.12); border:1px dashed var(--primary); align-items:center; gap:0.75rem">
                        <div id="file-icon" style="font-size:1.8rem; color:var(--primary)"></div>
                        <div style="flex:1; min-width:0">
                            <div style="font-size:0.75rem; color:var(--primary); font-weight:600">File mới được chọn:</div>
                            <div id="file-name" style="font-weight:600; font-size:0.88rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis"></div>
                            <div id="file-size" style="font-size:0.75rem; color:var(--text-muted)"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thumbnail / Cover Image -->
            <div class="form-card" style="margin-bottom:1.5rem">
                <h3 style="font-size:1.05rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; color:var(--text)">
                    <i class="fas fa-image" style="color:var(--primary)"></i> Ảnh bìa / Xem trước
                </h3>

                @if($certificate->thumbnail)
                    <div style="margin-bottom:0.75rem">
                        <span style="font-size:0.78rem; color:var(--text-muted); display:block; margin-bottom:0.35rem">Ảnh bìa hiện tại:</span>
                        <img src="{{ $certificate->thumbnail_url }}" alt="Thumbnail" style="width:100%; max-height:160px; object-fit:cover; border-radius:8px; border:1px solid var(--border)">
                    </div>
                @endif

                <div class="form-group">
                    <label class="form-label" for="thumbnail">{{ $certificate->thumbnail ? 'Thay đổi ảnh bìa' : 'Thêm ảnh bìa' }}</label>
                    <input type="file" id="thumbnail" name="thumbnail" class="form-control" accept="image/*" onchange="previewThumbnail(this)">
                    <span style="font-size:0.78rem; color:var(--text-muted); display:block; margin-top:0.35rem">
                        Nếu để trống đối với file PDF, hệ thống tự hiển thị giao diện PDF sang trọng.
                    </span>
                    @error('thumbnail')<span style="font-size:0.8rem; color:#ef4444">{{ $message }}</span>@enderror

                    <div id="thumb-preview-wrap" style="display:none; margin-top:0.85rem">
                        <span style="font-size:0.75rem; color:var(--primary); font-weight:600; display:block; margin-bottom:0.25rem">Ảnh mới được chọn:</span>
                        <img id="thumb-preview" src="" alt="Thumbnail preview" style="width:100%; max-height:160px; object-fit:cover; border-radius:8px; border:1px solid var(--border)">
                    </div>
                </div>
            </div>

            <!-- Display Settings -->
            <div class="form-card" style="margin-bottom:1.5rem">
                <h3 style="font-size:1.05rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem; color:var(--text)">
                    <i class="fas fa-cog" style="color:var(--primary)"></i> Cài đặt hiển thị
                </h3>

                <div class="form-group">
                    <label class="form-label" for="order">Thứ tự ưu tiên hiển thị</label>
                    <input type="number" id="order" name="order" class="form-control" value="{{ old('order', $certificate->order) }}" min="0">
                    <span style="font-size:0.78rem; color:var(--text-muted)">Số nhỏ hơn sẽ đứng trước (0 là ưu tiên cao nhất).</span>
                </div>

                <div style="margin-top:1rem; padding:0.75rem; border-radius:8px; background:var(--bg-subtle, rgba(255,255,255,0.03)); border:1px solid var(--border)">
                    <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; font-weight:500; font-size:0.9rem">
                        <input type="checkbox" name="featured" value="1" {{ old('featured', $certificate->featured) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--primary)">
                        <span><i class="fas fa-star" style="color:#eab308"></i> Ghim nổi bật trang chủ</span>
                    </label>
                    <span style="font-size:0.78rem; color:var(--text-muted); display:block; margin-top:0.35rem; margin-left:1.75rem">
                        Tài liệu này sẽ xuất hiện trên trang chủ để nhà tuyển dụng/đối tác xem ngay.
                    </span>
                </div>

                <div style="margin-top:1.5rem">
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.75rem">
                        <i class="fas fa-save"></i> Cập nhật thay đổi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function handleFileSelect(input) {
    const file = input.files[0];
    const preview = document.getElementById('file-info-preview');
    const icon = document.getElementById('file-icon');
    const name = document.getElementById('file-name');
    const size = document.getElementById('file-size');

    if (file) {
        preview.style.display = 'flex';
        name.textContent = file.name;
        size.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

        if (file.name.toLowerCase().endsWith('.pdf') || file.type === 'application/pdf') {
            icon.className = 'fas fa-file-pdf';
            icon.style.color = '#ef4444';
        } else {
            icon.className = 'fas fa-file-image';
            icon.style.color = '#06b6d4';
        }
    } else {
        preview.style.display = 'none';
    }
}

function previewThumbnail(input) {
    const file = input.files[0];
    const wrap = document.getElementById('thumb-preview-wrap');
    const img = document.getElementById('thumb-preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            wrap.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        wrap.style.display = 'none';
    }
}
</script>
@endsection
