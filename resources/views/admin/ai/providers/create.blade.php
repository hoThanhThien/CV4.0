@extends('layouts.admin')

@section('title', 'Thêm AI Provider mới — AI Blog Studio')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-plus-circle" style="color:var(--accent)"></i> Thêm AI Provider mới</h1>
        <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem">
            Cấu hình khóa API và tham số cho nhà cung cấp AI. Khóa API sẽ được mã hóa an toàn trong cơ sở dữ liệu.
        </p>
    </div>
    <a href="{{ route('admin.ai.providers.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại danh sách
    </a>
</div>

<div style="max-width:800px; background:var(--bg-card); border:1px solid var(--border); border-radius:12px; padding:2rem">
    <form method="POST" action="{{ route('admin.ai.providers.store') }}">
        @csrf

        <!-- Provider Type -->
        <div style="margin-bottom:1.5rem">
            <label style="display:block; font-weight:600; margin-bottom:0.5rem">Loại nhà cung cấp AI <span style="color:var(--danger)">*</span></label>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap:0.75rem">
                <label style="display:flex; align-items:center; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="provider_type" value="openai" {{ old('provider_type', 'openai') === 'openai' ? 'checked' : '' }} onchange="onProviderTypeChange('openai')">
                    <div>
                        <div style="font-weight:700">OpenAI</div>
                        <small style="color:var(--text-muted)">GPT-4o, Mini</small>
                    </div>
                </label>
                <label style="display:flex; align-items:center; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="provider_type" value="gemini" {{ old('provider_type') === 'gemini' ? 'checked' : '' }} onchange="onProviderTypeChange('gemini')">
                    <div>
                        <div style="font-weight:700">Google Gemini</div>
                        <small style="color:var(--text-muted)">Flash, Pro</small>
                    </div>
                </label>
                <label style="display:flex; align-items:center; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="provider_type" value="deepseek" {{ old('provider_type') === 'deepseek' ? 'checked' : '' }} onchange="onProviderTypeChange('deepseek')">
                    <div>
                        <div style="font-weight:700">DeepSeek</div>
                        <small style="color:var(--text-muted)">V3, Coder</small>
                    </div>
                </label>
                <label style="display:flex; align-items:center; gap:0.5rem; padding:0.85rem; border:1px solid var(--border); border-radius:8px; cursor:pointer; background:var(--bg)">
                    <input type="radio" name="provider_type" value="custom" {{ old('provider_type') === 'custom' ? 'checked' : '' }} onchange="onProviderTypeChange('custom')">
                    <div>
                        <div style="font-weight:700">Custom API</div>
                        <small style="color:var(--text-muted)">OpenAI Compatible</small>
                    </div>
                </label>
            </div>
        </div>

        <!-- Name -->
        <div style="margin-bottom:1.5rem">
            <label for="name" style="display:block; font-weight:600; margin-bottom:0.5rem">Tên hiển thị <span style="color:var(--danger)">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', 'OpenAI Primary') }}" placeholder="VD: OpenAI Primary hoặc DeepSeek Pro" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
        </div>

        <!-- API Key -->
        <div style="margin-bottom:1.5rem">
            <label for="api_key" style="display:block; font-weight:600; margin-bottom:0.5rem">
                Khóa API (API Key) <span style="color:var(--danger)">*</span>
            </label>
            <div style="position:relative">
                <input type="password" id="api_key" name="api_key" class="form-control" value="{{ old('api_key') }}" placeholder="Nhập API Key (VD: sk-...)" required style="width:100%; padding:0.75rem; padding-right:3rem; border:1px solid var(--border); border-radius:8px">
                <button type="button" onclick="togglePasswordVisibility('api_key')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--text-muted); cursor:pointer">
                    <i class="fas fa-eye" id="api_key_eye"></i>
                </button>
            </div>
            <small style="color:var(--text-muted); display:block; margin-top:0.35rem">
                <i class="fas fa-shield-alt" style="color:var(--success)"></i> Khóa API được mã hóa bằng chuẩn AES-256 của Laravel ngay khi lưu và không bao giờ bị lộ ra frontend.
            </small>
        </div>

        <!-- Model -->
        <div style="margin-bottom:1.5rem">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:0.75rem; margin-bottom:0.5rem; flex-wrap:wrap">
                <label for="model" style="font-weight:600; margin:0">Model AI sử dụng <span style="color:var(--danger)">*</span></label>
                <button type="button" id="btnFetchModels" onclick="fetchLiveModels()" class="btn btn-secondary" style="padding:0.35rem 0.75rem; font-size:0.8rem">
                    <i class="fas fa-sync-alt" id="fetchModelsIcon"></i> Lấy danh sách Model từ API
                </button>
            </div>
            <input type="text" id="model" name="model" class="form-control" value="{{ old('model') }}" placeholder="Nhập mã model hoặc bấm 'Lấy danh sách Model từ API' sau khi điền API Key" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            <div id="model-suggestions" style="margin-top:0.5rem; display:flex; gap:0.5rem; flex-wrap:wrap"></div>
            <small id="model-help" style="color:var(--text-muted); display:block; margin-top:0.4rem; font-size:0.83rem">
                <i class="fas fa-info-circle"></i> Danh sách model được truy vấn động trực tiếp từ API nhà cung cấp theo khóa API của bạn (không hardcode).
            </small>
        </div>

        <!-- Base URL (Custom) -->
        <div id="base_url_group" style="margin-bottom:1.5rem; display:none">
            <label for="base_url" style="display:block; font-weight:600; margin-bottom:0.5rem">Base URL (cho Custom Provider)</label>
            <input type="url" id="base_url" name="base_url" class="form-control" value="{{ old('base_url') }}" placeholder="VD: https://api.groq.com/openai/v1 hoặc https://openrouter.ai/api/v1" style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            <small style="color:var(--text-muted); display:block; margin-top:0.35rem">
                Địa chỉ API tương thích OpenAI. Chỉ chấp nhận giao thức http:// hoặc https://.
            </small>
        </div>

        <!-- Parameters Grid -->
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1rem; margin-bottom:1.5rem">
            <div>
                <label for="temperature" style="display:block; font-weight:600; margin-bottom:0.5rem">Temperature: <span id="temp-val">0.7</span></label>
                <input type="range" id="temperature" name="temperature" min="0" max="2" step="0.1" value="{{ old('temperature', 0.7) }}" oninput="document.getElementById('temp-val').innerText = this.value" style="width:100%">
                <small style="color:var(--text-muted); font-size:0.75rem">Độ sáng tạo nội dung (0: logic, 2: cực kỳ sáng tạo)</small>
            </div>
            <div>
                <label for="max_tokens" style="display:block; font-weight:600; margin-bottom:0.5rem">Max Output Tokens</label>
                <input type="number" id="max_tokens" name="max_tokens" value="{{ old('max_tokens', 8192) }}" min="500" max="65536" style="width:100%; padding:0.6rem; border:1px solid var(--border); border-radius:8px">
            </div>
            <div>
                <label for="timeout" style="display:block; font-weight:600; margin-bottom:0.5rem">Timeout (giây)</label>
                <input type="number" id="timeout" name="timeout" value="{{ old('timeout', 120) }}" min="10" max="300" style="width:100%; padding:0.6rem; border:1px solid var(--border); border-radius:8px">
            </div>
        </div>

        <!-- Checkboxes -->
        <div style="display:flex; gap:2rem; margin-bottom:2rem; padding:1rem; background:var(--bg); border:1px solid var(--border); border-radius:8px">
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <span style="font-weight:600">Kích hoạt Provider này</span>
            </label>
            <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer">
                <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                <span style="font-weight:600">Đặt làm Provider mặc định</span>
            </label>
        </div>

        <div style="display:flex; gap:1rem; justify-content:flex-end">
            <a href="{{ route('admin.ai.providers.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Lưu nhà cung cấp AI
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const defaultNames = {
        openai: 'OpenAI Primary',
        gemini: 'Google Gemini',
        deepseek: 'DeepSeek Pro',
        custom: 'Custom LLM'
    };

    function onProviderTypeChange(type) {
        const baseUrlGroup = document.getElementById('base_url_group');
        const nameInput = document.getElementById('name');
        const suggestionsBox = document.getElementById('model-suggestions');

        baseUrlGroup.style.display = (type === 'custom') ? 'block' : 'none';

        const allDefaults = Object.values(defaultNames);
        if (nameInput && (!nameInput.value || allDefaults.includes(nameInput.value))) {
            nameInput.value = defaultNames[type] || 'AI Provider';
        }

        if (suggestionsBox) {
            suggestionsBox.innerHTML = '';
        }
    }

    function selectModel(m) {
        document.getElementById('model').value = m;
    }

    async function fetchLiveModels() {
        const type = document.querySelector('input[name="provider_type"]:checked')?.value || 'gemini';
        const apiKey = document.getElementById('api_key').value.trim();
        const baseUrl = document.getElementById('base_url')?.value.trim() || '';
        const suggestionsBox = document.getElementById('model-suggestions');
        const icon = document.getElementById('fetchModelsIcon');

        if (!apiKey) {
            if (window.AdminNotify) window.AdminNotify.toast('Vui lòng nhập API Key trước khi tải danh sách Model.', 'warning');
            document.getElementById('api_key').focus();
            return;
        }

        icon.classList.add('fa-spin');
        suggestionsBox.innerHTML = '<span style="font-size:0.82rem; color:var(--text-muted)">Đang truy vấn danh sách Model trực tiếp từ API...</span>';

        try {
            const resp = await fetch("{{ route('admin.ai.providers.fetch-models') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}"
                },
                body: JSON.stringify({ provider_type: type, api_key: apiKey, base_url: baseUrl || null })
            });
            const data = await resp.json();

            if (!resp.ok || !data.success || !Array.isArray(data.models)) {
                suggestionsBox.innerHTML = `<span style="font-size:0.82rem; color:var(--danger)">${data.message || 'Không thể tải danh sách model.'}</span>`;
                return;
            }

            const modelInput = document.getElementById('model');
            if (!modelInput.value && data.models[0]?.id) {
                modelInput.value = data.models[0].id;
            }

            suggestionsBox.innerHTML = '<span style="font-size:0.8rem; color:var(--text-muted); width:100%">Model khả dụng trên API (' + data.models.length + '):</span> ' +
                data.models.slice(0, 12).map(m => `<button type="button" onclick="selectModel('${m.id}')" title="${m.display_name || m.id}" class="badge" style="cursor:pointer; background:rgba(99,102,241,0.1); color:var(--accent); border:1px solid rgba(99,102,241,0.2); padding:0.3rem 0.6rem; border-radius:6px; font-family:monospace">${m.id}</button>`).join(' ');
        } catch (err) {
            suggestionsBox.innerHTML = '<span style="font-size:0.82rem; color:var(--danger)">Lỗi kết nối khi truy vấn danh sách Model.</span>';
        } finally {
            icon.classList.remove('fa-spin');
        }
    }

    document.getElementById('model').addEventListener('blur', function() {
        const type = document.querySelector('input[name="provider_type"]:checked')?.value;
        let val = this.value.trim();
        if (type === 'gemini' && val) {
            val = val.replace(/^models\//i, '').replace(/^\/+/, '').trim().toLowerCase().replace(/\s+/g, '-');
        }
        this.value = val;
    });

    function togglePasswordVisibility(id) {
        const input = document.getElementById(id);
        const icon = document.getElementById(id + '_eye');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const checkedType = document.querySelector('input[name="provider_type"]:checked');
        if (checkedType) onProviderTypeChange(checkedType.value);
    });
</script>
@endsection
