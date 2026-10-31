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
            <label for="model" style="display:block; font-weight:600; margin-bottom:0.5rem">Model AI sử dụng <span style="color:var(--danger)">*</span></label>
            <input type="text" id="model" name="model" class="form-control" value="{{ old('model', 'gpt-4o-mini') }}" placeholder="VD: gpt-4o-mini" required style="width:100%; padding:0.75rem; border:1px solid var(--border); border-radius:8px">
            <div id="model-suggestions" style="margin-top:0.5rem; display:flex; gap:0.5rem; flex-wrap:wrap">
                <!-- Javascript will inject suggestions -->
            </div>
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
                <input type="number" id="max_tokens" name="max_tokens" value="{{ old('max_tokens', 4000) }}" min="500" max="16000" style="width:100%; padding:0.6rem; border:1px solid var(--border); border-radius:8px">
            </div>
            <div>
                <label for="timeout" style="display:block; font-weight:600; margin-bottom:0.5rem">Timeout (giây)</label>
                <input type="number" id="timeout" name="timeout" value="{{ old('timeout', 60) }}" min="10" max="300" style="width:100%; padding:0.6rem; border:1px solid var(--border); border-radius:8px">
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
    const modelSuggestions = {
        openai: ['gpt-4o-mini', 'gpt-4o', 'gpt-3.5-turbo'],
        gemini: ['gemini-1.5-flash', 'gemini-1.5-pro'],
        deepseek: ['deepseek-chat', 'deepseek-coder'],
        custom: ['llama-3.1-70b', 'mixtral-8x7b-32768']
    };

    function onProviderTypeChange(type) {
        const baseUrlGroup = document.getElementById('base_url_group');
        const modelInput = document.getElementById('model');
        const suggestionsBox = document.getElementById('model-suggestions');

        if (type === 'custom') {
            baseUrlGroup.style.display = 'block';
        } else {
            baseUrlGroup.style.display = 'none';
        }

        const list = modelSuggestions[type] || [];
        if (list.length > 0 && (!modelInput.value || Object.values(modelSuggestions).flat().includes(modelInput.value))) {
            modelInput.value = list[0];
        }

        suggestionsBox.innerHTML = '<span style="font-size:0.8rem; color:var(--text-muted)">Gợi ý:</span> ' +
            list.map(m => `<button type="button" onclick="document.getElementById('model').value='${m}'" class="badge" style="cursor:pointer; background:rgba(99,102,241,0.1); color:var(--accent); border:1px solid rgba(99,102,241,0.2)">${m}</button>`).join(' ');
    }

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
