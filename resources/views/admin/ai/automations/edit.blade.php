@extends('layouts.admin')

@section('title', 'Sửa lịch tự động — ' . $automation->name)

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-edit" style="color:var(--accent)"></i> Sửa lịch tự động hóa</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Cập nhật giờ chạy, tần suất và chế độ xuất bản cho '{{ $automation->name }}'.
        </p>
    </div>
    <a href="{{ route('admin.ai.automations.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div style="max-width:850px; background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:2rem">
    <form method="POST" action="{{ route('admin.ai.automations.update', $automation->id) }}">
        @csrf
        @method('PUT')

        <div style="margin-bottom:1.5rem">
            <label for="name" style="display:block; font-weight:600; margin-bottom:0.5rem">Tên lịch tự động (Automation Name) <span style="color:var(--danger)">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $automation->name) }}" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="content_config_id" style="display:block; font-weight:600; margin-bottom:0.5rem">Cấu hình nội dung sử dụng <span style="color:var(--danger)">*</span></label>
            <select id="content_config_id" name="content_config_id" class="form-control" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                @foreach($configs as $c)
                    <option value="{{ $c->id }}" {{ old('content_config_id', $automation->content_config_id) == $c->id ? 'selected' : '' }}>
                        {{ $c->name }} (Chủ đề: {{ $c->main_topic }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Schedule details -->
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1rem; margin-bottom:1.5rem">
            <div>
                <label for="frequency" style="display:block; font-weight:600; margin-bottom:0.5rem">Tần suất chạy <span style="color:var(--danger)">*</span></label>
                <select id="frequency" name="frequency" class="form-control" onchange="onFrequencyChange(this.value)" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                    <option value="daily" {{ old('frequency', $automation->frequency) === 'daily' ? 'selected' : '' }}>Hàng ngày (Daily)</option>
                    <option value="weekly" {{ old('frequency', $automation->frequency) === 'weekly' ? 'selected' : '' }}>Hàng tuần (Weekly)</option>
                    <option value="monthly" {{ old('frequency', $automation->frequency) === 'monthly' ? 'selected' : '' }}>Hàng tháng (Monthly)</option>
                </select>
            </div>
            <div>
                <label for="execution_time" style="display:block; font-weight:600; margin-bottom:0.5rem">Giờ chạy (HH:MM) <span style="color:var(--danger)">*</span></label>
                <input type="time" id="execution_time" name="execution_time" class="form-control" value="{{ old('execution_time', substr($automation->execution_time, 0, 5)) }}" required style="width:100%; padding:0.7rem; border:1px solid var(--border); border-radius:8px">
            </div>
            <div>
                <label for="timezone" style="display:block; font-weight:600; margin-bottom:0.5rem">Múi giờ</label>
                <input type="text" id="timezone" name="timezone" class="form-control" value="{{ old('timezone', $automation->timezone) }}" readonly style="width:100%; padding:0.7rem; border:1px solid var(--border); border-radius:8px; background:var(--bg)">
            </div>
        </div>

        <!-- Day of Week / Month -->
        <div id="weekly_group" style="margin-bottom:1.5rem; display:none">
            <label for="day_of_week" style="display:block; font-weight:600; margin-bottom:0.5rem">Ngày trong tuần</label>
            <select id="day_of_week" name="day_of_week" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
                <option value="1" {{ old('day_of_week', $automation->day_of_week) == 1 ? 'selected' : '' }}>Thứ Hai</option>
                <option value="2" {{ old('day_of_week', $automation->day_of_week) == 2 ? 'selected' : '' }}>Thứ Ba</option>
                <option value="3" {{ old('day_of_week', $automation->day_of_week) == 3 ? 'selected' : '' }}>Thứ Tư</option>
                <option value="4" {{ old('day_of_week', $automation->day_of_week) == 4 ? 'selected' : '' }}>Thứ Năm</option>
                <option value="5" {{ old('day_of_week', $automation->day_of_week) == 5 ? 'selected' : '' }}>Thứ Sáu</option>
                <option value="6" {{ old('day_of_week', $automation->day_of_week) == 6 ? 'selected' : '' }}>Thứ Bảy</option>
                <option value="0" {{ old('day_of_week', $automation->day_of_week) === 0 ? 'selected' : '' }}>Chủ Nhật</option>
            </select>
        </div>

        <div id="monthly_group" style="margin-bottom:1.5rem; display:none">
            <label for="day_of_month" style="display:block; font-weight:600; margin-bottom:0.5rem">Ngày trong tháng (1 - 28)</label>
            <input type="number" id="day_of_month" name="day_of_month" min="1" max="28" value="{{ old('day_of_month', $automation->day_of_month ?: 1) }}" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <!-- Publishing Mode -->
        <div style="margin-bottom:1.5rem">
            <label style="display:block; font-weight:600; margin-bottom:0.5rem">Chế độ xuất bản bài viết <span style="color:var(--danger)">*</span></label>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:0.75rem">
                <label style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="publishing_mode" value="manual_approval" {{ old('publishing_mode', $automation->publishing_mode) === 'manual_approval' ? 'checked' : '' }}>
                    <div>
                        <div style="font-weight:700">Mode 1: Manual Approval (Khuyến nghị)</div>
                        <small style="color:var(--text-muted)">AI tạo bài → Lưu vào Pending Review → Admin kiểm tra & duyệt mới đăng.</small>
                    </div>
                </label>
                <label style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="publishing_mode" value="auto_schedule" {{ old('publishing_mode', $automation->publishing_mode) === 'auto_schedule' ? 'checked' : '' }}>
                    <div>
                        <div style="font-weight:700">Mode 2: Auto Schedule</div>
                        <small style="color:var(--text-muted)">AI tạo bài → Tự động lên lịch đăng vào các khung giờ hẹn tiếp theo.</small>
                    </div>
                </label>
                <label style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="publishing_mode" value="auto_publish" {{ old('publishing_mode', $automation->publishing_mode) === 'auto_publish' ? 'checked' : '' }}>
                    <div>
                        <div style="font-weight:700">Mode 3: Auto Publish</div>
                        <small style="color:var(--text-muted)">AI tạo bài → Xuất bản ngay lập tức ra trang công khai.</small>
                    </div>
                </label>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem">
            <div>
                <label for="articles_per_run" style="display:block; font-weight:600; margin-bottom:0.5rem">Số bài viết mỗi lần chạy (1 - 5)</label>
                <input type="number" id="articles_per_run" name="articles_per_run" min="1" max="5" value="{{ old('articles_per_run', $automation->articles_per_run) }}" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
            <div>
                <label for="interval_minutes" style="display:block; font-weight:600; margin-bottom:0.5rem">Khoảng cách giữa các bài (phút)</label>
                <input type="number" id="interval_minutes" name="interval_minutes" min="15" max="1440" value="{{ old('interval_minutes', $automation->interval_minutes) }}" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
        </div>

        <!-- Date Range -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem; margin-bottom:1.5rem">
            <div>
                <label for="start_date" style="display:block; font-weight:600; margin-bottom:0.5rem">Ngày bắt đầu áp dụng (tùy chọn)</label>
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $automation->start_date ? $automation->start_date->format('Y-m-d') : '') }}" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
            <div>
                <label for="end_date" style="display:block; font-weight:600; margin-bottom:0.5rem">Ngày kết thúc (tùy chọn)</label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $automation->end_date ? $automation->end_date->format('Y-m-d') : '') }}" class="form-control" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            </div>
        </div>

        <div style="margin-bottom:2rem; padding:1rem; background:var(--bg); border:1px solid var(--border); border-radius:8px">
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $automation->is_active) ? 'checked' : '' }}>
                <span style="font-weight:600">Kích hoạt lịch tự động này</span>
            </label>
        </div>

        <div style="display:flex; gap:1rem; justify-content:flex-end">
            <a href="{{ route('admin.ai.automations.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật Automation
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function onFrequencyChange(freq) {
        document.getElementById('weekly_group').style.display = (freq === 'weekly') ? 'block' : 'none';
        document.getElementById('monthly_group').style.display = (freq === 'monthly') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', () => {
        onFrequencyChange(document.getElementById('frequency').value);
    });
</script>
@endsection
