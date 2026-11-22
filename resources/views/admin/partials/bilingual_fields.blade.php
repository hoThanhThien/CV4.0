@php
    $translations = ($model && is_array($model->translations)) ? $model->translations : [];
    $uid = $uid ?? ('i18n_' . uniqid());
    $hasVi = collect($fields)->keys()->contains(fn ($f) => !empty(old("translations.vi.$f", $translations['vi'][$f] ?? '')));
    $hasEn = collect($fields)->keys()->contains(fn ($f) => !empty(old("translations.en.$f", $translations['en'][$f] ?? '')));
@endphp

<div class="form-card" style="margin-bottom:1.5rem">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem">
        <div>
            <h3 style="font-size:1.05rem; margin:0; display:flex; align-items:center; gap:0.5rem; color:var(--text)">
                <i class="fas fa-language" style="color:var(--primary)"></i>
                <span>Bilingual Translations (VI / EN Override)</span>
            </h3>
            <p style="font-size:0.78rem; color:var(--text-muted); margin:0.25rem 0 0">
                Optional localized copy rendered automatically when visitors switch <code>?lang=vi</code> or <code>?lang=en</code>. Blank fields fall back to the main content above.
            </p>
        </div>

        <div style="display:inline-flex; gap:0.35rem; background:var(--bg); padding:0.25rem; border-radius:8px; border:1px solid var(--border)">
            <button type="button"
                    data-bilingual-tab="{{ $uid }}"
                    data-locale="vi"
                    onclick="switchBilingualTab('{{ $uid }}', 'vi')"
                    style="padding:0.35rem 0.75rem; border-radius:6px; border:none; cursor:pointer; font-size:0.78rem; font-weight:700; background:var(--primary); color:#fff">
                Tiếng Việt (VI){{ $hasVi ? ' ✓' : '' }}
            </button>
            <button type="button"
                    data-bilingual-tab="{{ $uid }}"
                    data-locale="en"
                    onclick="switchBilingualTab('{{ $uid }}', 'en')"
                    style="padding:0.35rem 0.75rem; border-radius:6px; border:none; cursor:pointer; font-size:0.78rem; font-weight:700; background:transparent; color:var(--text-muted)">
                English (EN){{ $hasEn ? ' ✓' : '' }}
            </button>
        </div>
    </div>

    @foreach(['vi', 'en'] as $loc)
        <div id="{{ $uid }}_pane_{{ $loc }}" style="{{ $loc === 'en' ? 'display:none;' : '' }}">
            @foreach($fields as $fieldKey => $cfg)
                @php
                    $inputId = "{$uid}_{$loc}_{$fieldKey}";
                    $inputName = "translations[{$loc}][{$fieldKey}]";
                    $val = old("translations.{$loc}.{$fieldKey}", $translations[$loc][$fieldKey] ?? '');
                    $fieldType = $cfg['type'] ?? 'text';
                    $rows = $cfg['rows'] ?? 4;
                    $placeholder = $cfg["placeholder_{$loc}"] ?? ($cfg['placeholder'] ?? '');
                    $locTag = strtoupper($loc);
                @endphp
                <div class="form-group">
                    <label class="form-label" for="{{ $inputId }}">
                        {{ $cfg['label'] ?? ucfirst($fieldKey) }} <span style="font-size:0.74rem; color:var(--primary); font-weight:700">[{{ $locTag }}]</span>
                    </label>
                    @if($fieldType === 'textarea')
                        <textarea id="{{ $inputId }}"
                                  name="{{ $inputName }}"
                                  class="form-control"
                                  rows="{{ $rows }}"
                                  style="line-height:1.55{{ !empty($cfg['monospace']) ? '; font-family:monospace; font-size:0.86rem' : '' }}"
                                  placeholder="{{ $placeholder }}">{{ $val }}</textarea>
                    @else
                        <input type="text"
                               id="{{ $inputId }}"
                               name="{{ $inputName }}"
                               class="form-control"
                               value="{{ $val }}"
                               placeholder="{{ $placeholder }}">
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<script>
    if (typeof window.switchBilingualTab !== 'function') {
        window.switchBilingualTab = function (uid, activeLocale) {
            ['vi', 'en'].forEach(function (loc) {
                var pane = document.getElementById(uid + '_pane_' + loc);
                if (pane) pane.style.display = (loc === activeLocale) ? 'block' : 'none';
            });
            document.querySelectorAll('[data-bilingual-tab="' + uid + '"]').forEach(function (btn) {
                var isActive = btn.getAttribute('data-locale') === activeLocale;
                btn.style.background = isActive ? 'var(--primary)' : 'transparent';
                btn.style.color = isActive ? '#ffffff' : 'var(--text-muted)';
            });
        };
    }
</script>
