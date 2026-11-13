@extends('layouts.admin')

@section('title', 'Admin Profile')

@section('styles')
<style>
    .profile-shell {
        max-width: 1180px;
        margin: 0 auto;
    }

    /* Profile Overview Hero Card */
    .profile-hero-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .profile-cover {
        position: relative;
        height: 136px;
        background:
            linear-gradient(125deg, #12161f 0%, #172554 62%, #1e3a8a 100%);
        overflow: hidden;
    }

    .profile-cover::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(to right, rgba(255, 255, 255, 0.06) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
        background-size: 28px 28px;
        mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.25));
    }

    .profile-cover-meta {
        position: relative;
        z-index: 1;
        height: 100%;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: flex-start;
        justify-content: flex-end;
    }

    .env-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.3rem 0.7rem;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #f8fafc;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.74rem;
        font-weight: 500;
    }

    .env-chip-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #4ade80;
    }

    .profile-identity-wrap {
        padding: 0 1.5rem 1.35rem;
    }

    .profile-identity-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1.25rem;
        margin-top: -36px;
        position: relative;
        z-index: 2;
        flex-wrap: wrap;
    }

    .profile-identity-main {
        display: flex;
        align-items: flex-end;
        gap: 1.15rem;
        min-width: 0;
        flex: 1 1 320px;
    }

    .profile-avatar-btn {
        position: relative;
        width: 76px;
        height: 76px;
        border-radius: 16px;
        background: var(--text);
        color: #ffffff;
        border: 3px solid #ffffff;
        box-shadow: 0 6px 18px rgba(18, 22, 31, 0.14);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.65rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        cursor: pointer;
        flex-shrink: 0;
        transition: transform 0.2s var(--ease-out), background-color 0.2s ease, box-shadow 0.2s ease;
    }

    .profile-avatar-btn:hover {
        background: var(--accent);
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(30, 64, 175, 0.22);
    }

    .profile-avatar-badge {
        position: absolute;
        right: -4px;
        bottom: -4px;
        width: 24px;
        height: 24px;
        border-radius: 7px;
        background: #ffffff;
        border: 1px solid var(--border);
        color: var(--text);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        transition: color 0.16s ease, border-color 0.16s ease;
    }

    .profile-avatar-btn:hover .profile-avatar-badge {
        color: var(--accent);
        border-color: #bfdbfe;
    }

    .profile-title-block {
        padding-top: 2.35rem;
        min-width: 0;
        flex: 1;
    }

    .profile-name-line {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        flex-wrap: wrap;
        margin-bottom: 0.25rem;
    }

    .profile-name {
        font-size: 1.38rem;
        font-weight: 700;
        letter-spacing: -0.025em;
        color: var(--text);
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .profile-email-row {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        flex-wrap: wrap;
        color: var(--text-muted);
        font-size: 0.88rem;
    }

    .profile-email-text {
        overflow-wrap: anywhere;
    }

    .copy-pill-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.32rem;
        padding: 0.18rem 0.52rem;
        border-radius: 5px;
        background: #f7f8fa;
        border: 1px solid var(--border);
        color: #434d5e;
        font-family: inherit;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: border-color 0.16s ease, color 0.16s ease, background-color 0.16s ease;
    }

    .copy-pill-btn:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: #eff6ff;
    }

    .profile-hero-actions {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    /* Permissions / Role Strip */
    .profile-perm-strip {
        margin-top: 1.15rem;
        padding-top: 1rem;
        border-top: 1px solid #eef1f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
        flex-wrap: wrap;
    }

    .perm-group {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        flex-wrap: wrap;
    }

    .perm-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-right: 0.25rem;
    }

    .perm-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.22rem 0.58rem;
        border-radius: 6px;
        background: #f7f8fa;
        border: 1px solid var(--border);
        color: #434d5e;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .perm-chip i {
        color: var(--accent);
        font-size: 0.7rem;
    }

    /* Animated Tab Bar */
    .profile-tabs-wrap {
        position: relative;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0.35rem 0.5rem 0;
        margin-bottom: 1.35rem;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .profile-tabs-wrap::-webkit-scrollbar { display: none; }

    .profile-tabs {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        min-width: 100%;
    }

    .profile-tab-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.68rem 0.95rem 0.78rem;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-family: inherit;
        font-size: 0.87rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        border-radius: 7px 7px 0 0;
        transition: color 0.16s ease, background-color 0.16s ease;
    }

    .profile-tab-btn:hover {
        color: var(--text);
        background: #f7f8fa;
    }

    .profile-tab-btn.active {
        color: var(--accent);
    }

    .profile-tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        padding: 0 0.38rem;
        border-radius: 999px;
        background: #eef1f5;
        color: #434d5e;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .profile-tab-btn.active .profile-tab-count {
        background: #eff6ff;
        color: var(--accent);
    }

    .profile-tab-indicator {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 2.5px;
        background: var(--accent);
        border-radius: 2px 2px 0 0;
        transition: transform 0.26s var(--ease-out), width 0.26s var(--ease-out);
        pointer-events: none;
    }

    /* Tab Panels & Crossfade */
    .profile-tab-panel {
        display: none;
    }

    .profile-tab-panel.active {
        display: block;
        animation: panelEnter 0.24s var(--ease-out) both;
    }

    @keyframes panelEnter {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Grid Layout & Cards */
    .profile-grid {
        display: grid;
        grid-template-columns: 1.38fr 1fr;
        gap: 1.25rem;
        align-items: start;
    }

    .profile-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.5rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .profile-card:hover {
        border-color: #cbd5e1;
        box-shadow: var(--shadow-sm);
    }

    .profile-card-stack {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
        padding-bottom: 1rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid #eef1f5;
        flex-wrap: wrap;
    }

    .card-head h2 {
        font-size: 1.03rem;
        font-weight: 700;
        color: var(--text);
        letter-spacing: -0.015em;
        margin-bottom: 0.2rem;
    }

    .card-head p {
        font-size: 0.83rem;
        color: var(--text-muted);
        line-height: 1.45;
    }

    /* Key-value Ledger */
    .ledger-list {
        display: flex;
        flex-direction: column;
    }

    .ledger-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.72rem 0;
        border-bottom: 1px solid #eef1f5;
        font-size: 0.86rem;
    }

    .ledger-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .ledger-key {
        color: var(--text-muted);
        font-weight: 500;
        flex-shrink: 0;
    }

    .ledger-val {
        color: var(--text);
        font-weight: 600;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .mono-val {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.81rem;
    }

    /* Schema Transparency Notice Box */
    .schema-callout {
        margin-top: 1.35rem;
        padding: 0.95rem 1.05rem;
        border-radius: 8px;
        background: #f7f8fa;
        border: 1px solid var(--border);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.83rem;
        color: #434d5e;
        line-height: 1.55;
    }

    .schema-callout i {
        color: var(--accent);
        margin-top: 0.18rem;
        flex-shrink: 0;
    }

    /* Snapshot Mini Grid */
    .mini-kpi-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .mini-kpi {
        padding: 0.85rem 0.95rem;
        border-radius: 8px;
        background: #f7f8fa;
        border: 1px solid var(--border);
    }

    .mini-kpi-val {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--text);
        line-height: 1.2;
    }

    .mini-kpi-label {
        font-size: 0.77rem;
        color: var(--text-muted);
        margin-top: 0.2rem;
    }

    /* Form Controls with Toggle & Strength Meter */
    .input-icon-wrap {
        position: relative;
    }

    .input-eye-btn {
        position: absolute;
        right: 0.65rem;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 6px;
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s ease, background-color 0.15s ease;
    }

    .input-eye-btn:hover {
        color: var(--text);
        background: #eef1f5;
    }

    .pw-meter-wrap {
        margin-top: 0.55rem;
    }

    .pw-meter-track {
        width: 100%;
        height: 5px;
        border-radius: 999px;
        background: #eef1f5;
        overflow: hidden;
    }

    .pw-meter-fill {
        height: 100%;
        width: 0%;
        border-radius: 999px;
        background: var(--border);
        transition: width 0.22s var(--ease-out), background-color 0.22s ease;
    }

    .pw-meter-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.35rem;
        font-size: 0.77rem;
        color: var(--text-muted);
    }

    .field-error {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.8rem;
        color: var(--danger);
        font-weight: 500;
    }

    /* Session & Token Item */
    .session-box {
        padding: 1rem;
        border-radius: 8px;
        background: #f7f8fa;
        border: 1px solid var(--border);
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
    }

    .session-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid var(--border);
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .token-list {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
    }

    .token-row {
        padding: 0.85rem 0.95rem;
        border-radius: 8px;
        background: #f7f8fa;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
        flex-wrap: wrap;
        transition: border-color 0.16s ease;
    }

    .token-row:hover {
        border-color: #cbd5e1;
    }

    /* Staggered Activity Timeline */
    .activity-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
        flex-wrap: wrap;
    }

    .filter-pills {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        flex-wrap: wrap;
    }

    .filter-pill {
        padding: 0.36rem 0.75rem;
        border-radius: 999px;
        border: 1px solid var(--border);
        background: #f7f8fa;
        color: #434d5e;
        font-family: inherit;
        font-size: 0.79rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.16s ease, border-color 0.16s ease, color 0.16s ease;
    }

    .filter-pill:hover {
        border-color: #cbd5e1;
        color: var(--text);
    }

    .filter-pill.active {
        background: var(--text);
        border-color: var(--text);
        color: #ffffff;
    }

    .timeline-list {
        position: relative;
        padding-left: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .timeline-list::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 10px;
        bottom: 10px;
        width: 1px;
        background: var(--border);
    }

    .timeline-item {
        position: relative;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.9rem 1rem;
        border-radius: 8px;
        background: #f7f8fa;
        border: 1px solid var(--border);
        margin-left: 0.75rem;
        transition: border-color 0.16s ease, background-color 0.16s ease, transform 0.2s var(--ease-out);
    }

    .timeline-item:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateX(2px);
    }

    .profile-tab-panel.active .timeline-item:not(.is-hidden) {
        animation: timelineFade 0.28s var(--ease-out) both;
        animation-delay: calc(var(--stagger-i, 0) * 35ms);
    }

    @keyframes timelineFade {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .timeline-item.is-hidden {
        display: none;
    }

    .timeline-node {
        position: absolute;
        left: -2.05rem;
        top: 0.95rem;
        width: 26px;
        height: 26px;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
    }

    .timeline-node.tone-cobalt { color: var(--accent); border-color: #bfdbfe; background: #eff6ff; }
    .timeline-node.tone-success { color: var(--success); border-color: #bbf7d0; background: #f0fdf4; }
    .timeline-node.tone-danger { color: var(--danger); border-color: #fecaca; background: #fef2f2; }
    .timeline-node.tone-neutral { color: #434d5e; }

    /* Preferences Cards */
    .lang-option-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.85rem;
    }

    .lang-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.95rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: #f7f8fa;
        text-decoration: none;
        color: var(--text);
        transition: border-color 0.18s ease, background-color 0.18s ease, transform 0.16s ease;
    }

    .lang-card:hover {
        border-color: var(--accent);
        background: #ffffff;
        transform: translateY(-1px);
    }

    .lang-card.selected {
        border-color: var(--accent);
        background: #eff6ff;
    }

    /* Quick Edit Modal */
    .profile-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9990;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.22s var(--ease-out), visibility 0.22s ease;
    }

    .profile-modal-overlay.open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    .profile-modal-dialog {
        width: 100%;
        max-width: 460px;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 22px 48px rgba(15, 23, 42, 0.18);
        transform: scale(0.97) translateY(8px);
        transition: transform 0.24s var(--ease-out);
        overflow: hidden;
    }

    .profile-modal-overlay.open .profile-modal-dialog {
        transform: scale(1) translateY(0);
    }

    .profile-modal-head {
        padding: 1.15rem 1.35rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .profile-modal-head h3 {
        font-size: 1.02rem;
        font-weight: 700;
        color: var(--text);
    }

    .modal-close-btn {
        width: 32px;
        height: 32px;
        border-radius: 7px;
        border: 1px solid var(--border);
        background: #f7f8fa;
        color: var(--text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s ease, border-color 0.15s ease;
    }

    .modal-close-btn:hover {
        color: var(--text);
        border-color: var(--text);
    }

    .profile-modal-body {
        padding: 1.35rem;
    }

    .profile-modal-foot {
        padding: 1rem 1.35rem;
        border-top: 1px solid var(--border);
        background: #f7f8fa;
        display: flex;
        justify-content: flex-end;
        gap: 0.6rem;
    }

    @media (max-width: 960px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .profile-cover {
            height: 108px;
        }
        .profile-identity-wrap {
            padding: 0 1rem 1.15rem;
        }
        .profile-identity-row {
            margin-top: -30px;
            flex-direction: column;
            align-items: stretch;
        }
        .profile-identity-main {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.65rem;
        }
        .profile-avatar-btn {
            width: 64px;
            height: 64px;
            font-size: 1.35rem;
        }
        .profile-title-block {
            padding-top: 0;
        }
        .profile-hero-actions {
            width: 100%;
        }
        .profile-hero-actions .btn {
            flex: 1 1 auto;
            justify-content: center;
        }
        .profile-card {
            padding: 1.15rem;
        }
        .lang-option-grid,
        .mini-kpi-grid {
            grid-template-columns: 1fr;
        }
        .ledger-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.2rem;
        }
        .ledger-val {
            text-align: left;
        }
        .timeline-list {
            padding-left: 1.15rem;
        }
        .timeline-list::before {
            left: 11px;
        }
        .timeline-item {
            flex-wrap: wrap;
            gap: 0.65rem;
            margin-left: 0.5rem;
            padding: 0.85rem;
        }
        .timeline-node {
            left: -1.65rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .profile-tab-indicator,
        .profile-tab-panel.active,
        .timeline-item,
        .profile-modal-overlay,
        .profile-modal-dialog,
        .profile-avatar-btn,
        .pw-meter-fill {
            animation: none !important;
            transition: none !important;
        }
    }
</style>
@endsection

@section('content')
@php
    $words = preg_split('/\s+/', trim((string) $user->name)) ?: ['A'];
    $initials = count($words) >= 2
        ? mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr(end($words), 0, 1))
        : mb_strtoupper(mb_substr((string) $user->name, 0, 2));
    $handle = \Illuminate\Support\Str::before((string) $user->email, '@');
@endphp

<div class="profile-shell">
    <!-- 1. Profile Overview Card -->
    <section class="profile-hero-card" aria-label="Tổng quan hồ sơ quản trị viên">
        <div class="profile-cover">
            <div class="profile-cover-meta">
                <span class="env-chip">
                    <span class="env-chip-dot" aria-hidden="true"></span>
                    <span>CMS Workspace · {{ strtoupper((string) app()->environment()) }}</span>
                </span>
            </div>
        </div>

        <div class="profile-identity-wrap">
            <div class="profile-identity-row">
                <div class="profile-identity-main">
                    <button type="button"
                            class="profile-avatar-btn"
                            id="openQuickEditFromAvatar"
                            aria-label="Mở chỉnh sửa nhanh hồ sơ {{ $user->name }}"
                            title="Nhấn để chỉnh sửa tên / email">
                        <span>{{ $initials }}</span>
                        <span class="profile-avatar-badge" aria-hidden="true">
                            <i class="fas fa-pen"></i>
                        </span>
                    </button>

                    <div class="profile-title-block">
                        <div class="profile-name-line">
                            <h1 class="profile-name">{{ $user->name }}</h1>
                            <span class="badge badge-purple">
                                <i class="fas fa-shield-alt"></i> Administrator
                            </span>
                            @if($user->email_verified_at)
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Email đã xác thực
                                </span>
                            @else
                                <span class="badge badge-info">
                                    <i class="fas fa-circle"></i> Đang hoạt động
                                </span>
                            @endif
                        </div>

                        <div class="profile-email-row">
                            <span class="profile-email-text">{{ $user->email }}</span>
                            <button type="button"
                                    class="copy-pill-btn"
                                    id="copyEmailBtn"
                                    data-email="{{ $user->email }}">
                                <i class="far fa-copy"></i>
                                <span>Sao chép</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="profile-hero-actions">
                    <button type="button" class="btn btn-primary" id="openQuickEditBtn">
                        <i class="fas fa-user-edit"></i> Chỉnh sửa hồ sơ
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="activateProfileTab('security')">
                        <i class="fas fa-key"></i> Đổi mật khẩu
                    </button>
                </div>
            </div>

            <!-- Role & Permissions Summary -->
            <div class="profile-perm-strip">
                <div class="perm-group">
                    <span class="perm-label">Quyền truy cập CMS:</span>
                    <span class="perm-chip"><i class="fas fa-check"></i> Projects &amp; Skills</span>
                    <span class="perm-chip"><i class="fas fa-check"></i> Blog &amp; AI Studio</span>
                    <span class="perm-chip"><i class="fas fa-check"></i> Brochure &amp; Certificates</span>
                    <span class="perm-chip"><i class="fas fa-check"></i> Contact Inbox</span>
                    <span class="perm-chip"><i class="fas fa-check"></i> Sanctum API</span>
                </div>
                <span class="mono-val" style="color: var(--text-muted); font-size: 0.77rem;">
                    Account #{{ $user->id }}
                </span>
            </div>
        </div>
    </section>

    <!-- Workspace Tab Navigation -->
    <div class="profile-tabs-wrap">
        <div class="profile-tabs" role="tablist" aria-label="Các mục thiết lập hồ sơ" id="profileTabList">
            <button type="button"
                    role="tab"
                    class="profile-tab-btn {{ $activeTab === 'overview' ? 'active' : '' }}"
                    data-tab="overview"
                    aria-selected="{{ $activeTab === 'overview' ? 'true' : 'false' }}"
                    aria-controls="panel-overview"
                    id="tab-overview">
                <i class="fas fa-id-card"></i>
                <span>Thông tin &amp; Tài khoản</span>
            </button>

            <button type="button"
                    role="tab"
                    class="profile-tab-btn {{ $activeTab === 'security' ? 'active' : '' }}"
                    data-tab="security"
                    aria-selected="{{ $activeTab === 'security' ? 'true' : 'false' }}"
                    aria-controls="panel-security"
                    id="tab-security">
                <i class="fas fa-lock"></i>
                <span>Bảo mật &amp; Phiên</span>
                <span class="profile-tab-count">{{ $tokens->count() }}</span>
            </button>

            <button type="button"
                    role="tab"
                    class="profile-tab-btn {{ $activeTab === 'activity' ? 'active' : '' }}"
                    data-tab="activity"
                    aria-selected="{{ $activeTab === 'activity' ? 'true' : 'false' }}"
                    aria-controls="panel-activity"
                    id="tab-activity">
                <i class="fas fa-history"></i>
                <span>Hoạt động gần đây</span>
                <span class="profile-tab-count">{{ $activities->count() }}</span>
            </button>

            <button type="button"
                    role="tab"
                    class="profile-tab-btn {{ $activeTab === 'preferences' ? 'active' : '' }}"
                    data-tab="preferences"
                    aria-selected="{{ $activeTab === 'preferences' ? 'true' : 'false' }}"
                    aria-controls="panel-preferences"
                    id="tab-preferences">
                <i class="fas fa-sliders-h"></i>
                <span>Tùy chọn</span>
            </button>

            <span class="profile-tab-indicator" id="profileTabIndicator" aria-hidden="true"></span>
        </div>
    </div>

    <!-- TAB 1: Personal Information & Account Information -->
    <section id="panel-overview"
             role="tabpanel"
             aria-labelledby="tab-overview"
             class="profile-tab-panel {{ $activeTab === 'overview' ? 'active' : '' }}">
        <div class="profile-grid">
            <!-- 2. Personal Information -->
            <div class="profile-card">
                <div class="card-head">
                    <div>
                        <h2>Thông tin cá nhân</h2>
                        <p>Cập nhật tên hiển thị và địa chỉ email đăng nhập quản trị viên.</p>
                    </div>
                    <span class="badge badge-secondary">Bảng users</span>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" class="js-profile-form" data-loading-text="Đang lưu hồ sơ...">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label" for="name">Họ và tên <span style="color:var(--danger)">*</span></label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control"
                               value="{{ old('name', $user->name) }}"
                               required
                               maxlength="255"
                               autocomplete="name">
                        @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Địa chỉ Email <span style="color:var(--danger)">*</span></label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', $user->email) }}"
                               required
                               maxlength="255"
                               autocomplete="email">
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                        <span style="display:block; margin-top:0.35rem; font-size:0.78rem; color:var(--text-muted)">
                            Email này dùng đồng thời cho đăng nhập Admin Panel và API <code class="mono-val">/api/v1/auth/login</code>.
                        </span>
                    </div>

                    <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; flex-wrap:wrap; margin-top:1.25rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                        <span style="font-size:0.78rem; color:var(--text-muted)">
                            Avatar ký tự đầu tự động cập nhật theo tên hiển thị.
                        </span>
                    </div>
                </form>

                <div class="schema-callout">
                    <i class="fas fa-database"></i>
                    <div>
                        <strong>Phạm vi schema hiện tại:</strong> Bảng <code class="mono-val">users</code> hiện lưu trữ <code class="mono-val">name</code>, <code class="mono-val">email</code> và <code class="mono-val">password</code>. Các trường mở rộng như số điện thoại, ảnh đại diện tải lên và tiểu sử cá nhân chưa có cột dữ liệu tương ứng nên không hiển thị dữ liệu giả lập.
                    </div>
                </div>
            </div>

            <!-- 3. Account Information & CMS Footprint -->
            <div class="profile-card-stack">
                <div class="profile-card">
                    <div class="card-head">
                        <div>
                            <h2>Thông tin tài khoản</h2>
                            <p>Chi tiết định danh và trạng thái thực tế trong cơ sở dữ liệu.</p>
                        </div>
                        <span class="badge badge-success">Active</span>
                    </div>

                    <div class="ledger-list">
                        <div class="ledger-row">
                            <span class="ledger-key">Định danh (Handle)</span>
                            <span class="ledger-val mono-val">{{ '@' . $handle }}</span>
                        </div>
                        <div class="ledger-row">
                            <span class="ledger-key">Vai trò hệ thống</span>
                            <span class="ledger-val">Administrator (Admin Guard)</span>
                        </div>
                        <div class="ledger-row">
                            <span class="ledger-key">Trạng thái tài khoản</span>
                            <span class="ledger-val">
                                {{ $user->email_verified_at ? 'Hoạt động · Đã xác thực' : 'Đang hoạt động' }}
                            </span>
                        </div>
                        <div class="ledger-row">
                            <span class="ledger-key">Ngày khởi tạo</span>
                            <span class="ledger-val mono-val">
                                {{ $user->created_at ? $user->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}
                            </span>
                        </div>
                        <div class="ledger-row">
                            <span class="ledger-key">Cập nhật gần nhất</span>
                            <span class="ledger-val mono-val">
                                {{ $user->updated_at ? $user->updated_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'N/A' }}
                            </span>
                        </div>
                        <div class="ledger-row">
                            <span class="ledger-key">Lần đăng nhập cuối</span>
                            <span class="ledger-val" style="color:var(--text-muted); font-weight:500;">
                                Đang trong phiên (chưa lưu cột riêng)
                            </span>
                        </div>
                    </div>
                </div>

                <div class="profile-card">
                    <div class="card-head" style="margin-bottom:1rem; padding-bottom:0.85rem;">
                        <div>
                            <h2>Dữ liệu CMS đang quản lý</h2>
                            <p>Thống kê thời gian thực từ hệ thống Portfolio.</p>
                        </div>
                    </div>

                    <div class="mini-kpi-grid">
                        <div class="mini-kpi">
                            <div class="mini-kpi-val">{{ $cmsSummary['projects'] }}</div>
                            <div class="mini-kpi-label">Dự án Portfolio</div>
                        </div>
                        <div class="mini-kpi">
                            <div class="mini-kpi-val">{{ $cmsSummary['blog_posts'] }}</div>
                            <div class="mini-kpi-label">Bài viết ({{ $cmsSummary['published_posts'] }} đã đăng)</div>
                        </div>
                        <div class="mini-kpi">
                            <div class="mini-kpi-val">{{ $cmsSummary['certificates'] }}</div>
                            <div class="mini-kpi-label">Brochure &amp; Giấy khen</div>
                        </div>
                        <div class="mini-kpi">
                            <div class="mini-kpi-val">{{ $cmsSummary['ai_providers'] }}</div>
                            <div class="mini-kpi-label">AI Providers kích hoạt</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TAB 2: Security Settings (Change Password, 2FA Status, Sessions & Sanctum Tokens) -->
    <section id="panel-security"
             role="tabpanel"
             aria-labelledby="tab-security"
             class="profile-tab-panel {{ $activeTab === 'security' ? 'active' : '' }}">
        <div class="profile-grid">
            <!-- 4A. Change Password + 2FA -->
            <div class="profile-card-stack">
                <div class="profile-card">
                    <div class="card-head">
                        <div>
                            <h2>Đổi mật khẩu</h2>
                            <p>Sử dụng mật khẩu tối thiểu 8 ký tự, kết hợp chữ và số để bảo vệ CMS.</p>
                        </div>
                        <span class="badge badge-secondary">Bcrypt Hash</span>
                    </div>

                    <form action="{{ route('admin.profile.password') }}" method="POST" class="js-profile-form" data-loading-text="Đang cập nhật mật khẩu...">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label" for="current_password">Mật khẩu hiện tại</label>
                            <div class="input-icon-wrap">
                                <input type="password"
                                       id="current_password"
                                       name="current_password"
                                       class="form-control"
                                       style="padding-right:2.6rem"
                                       required
                                       autocomplete="current-password">
                                <button type="button"
                                        class="input-eye-btn"
                                        onclick="toggleSecretField('current_password', this)"
                                        aria-label="Ẩn hoặc hiện mật khẩu hiện tại">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="new_password">Mật khẩu mới</label>
                            <div class="input-icon-wrap">
                                <input type="password"
                                       id="new_password"
                                       name="password"
                                       class="form-control"
                                       style="padding-right:2.6rem"
                                       required
                                       minlength="8"
                                       autocomplete="new-password">
                                <button type="button"
                                        class="input-eye-btn"
                                        onclick="toggleSecretField('new_password', this)"
                                        aria-label="Ẩn hoặc hiện mật khẩu mới">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <div class="pw-meter-wrap" id="pwMeterWrap">
                                <div class="pw-meter-track">
                                    <div class="pw-meter-fill" id="pwMeterFill"></div>
                                </div>
                                <div class="pw-meter-meta">
                                    <span id="pwMeterLabel">Nhập ít nhất 8 ký tự</span>
                                    <span id="pwLengthHint">0 ký tự</span>
                                </div>
                            </div>
                            @error('password')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Xác nhận mật khẩu mới</label>
                            <div class="input-icon-wrap">
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="form-control"
                                       style="padding-right:2.6rem"
                                       required
                                       minlength="8"
                                       autocomplete="new-password">
                                <button type="button"
                                        class="input-eye-btn"
                                        onclick="toggleSecretField('password_confirmation', this)"
                                        aria-label="Ẩn hoặc hiện xác nhận mật khẩu">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <span id="pwMatchHint" style="display:block; margin-top:0.35rem; font-size:0.78rem; color:var(--text-muted)"></span>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-shield-alt"></i> Cập nhật mật khẩu
                        </button>
                    </form>
                </div>

                <div class="profile-card">
                    <div class="card-head" style="margin-bottom:0.85rem; padding-bottom:0.85rem;">
                        <div>
                            <h2>Xác thực hai yếu tố (2FA)</h2>
                            <p>Lớp bảo vệ bổ sung ngoài mật khẩu đăng nhập.</p>
                        </div>
                        <span class="badge badge-secondary">Chưa hỗ trợ</span>
                    </div>
                    <p style="font-size:0.84rem; color:#434d5e; line-height:1.55;">
                        Hệ thống sử dụng cơ chế xác thực phiên Laravel Session Regeneration kết hợp middleware bảo vệ toàn bộ route <code class="mono-val">/admin/*</code>. Tính năng TOTP/2FA chưa có trường cấu hình trong schema hiện tại.
                    </p>
                </div>
            </div>

            <!-- 4B. Active Web Session & Sanctum API Tokens -->
            <div class="profile-card-stack">
                <div class="profile-card">
                    <div class="card-head">
                        <div>
                            <h2>Phiên làm việc hiện tại</h2>
                            <p>Thông tin trực tiếp từ trình duyệt đang kết nối.</p>
                        </div>
                        <span class="badge badge-success">Đang hoạt động</span>
                    </div>

                    <div class="session-box">
                        <div class="session-icon" aria-hidden="true">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-weight:700; font-size:0.9rem; color:var(--text);">
                                {{ $currentSession['browser'] }} trên {{ $currentSession['os'] }}
                            </div>
                            <div class="mono-val" style="font-size:0.78rem; color:var(--text-muted); margin-top:0.2rem; overflow-wrap:anywhere;">
                                IP {{ $currentSession['ip_address'] }} · Session {{ $currentSession['session_id_prefix'] }}…
                            </div>
                            <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-top:0.55rem;">
                                <span class="badge badge-secondary">Driver: {{ $currentSession['driver'] }}</span>
                                <span class="badge badge-secondary">Timeout: {{ $currentSession['lifetime_minutes'] }} phút</span>
                                <span class="badge badge-{{ $currentSession['is_secure'] ? 'success' : 'warning' }}">
                                    {{ $currentSession['is_secure'] ? 'HTTPS' : 'HTTP Local' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid #eef1f5; display:flex; align-items:center; justify-content:space-between; gap:0.75rem; flex-wrap:wrap;">
                        <span style="font-size:0.8rem; color:var(--text-muted);">
                            Session driver hiện tại là <code class="mono-val">{{ $currentSession['driver'] }}</code> nên chỉ theo dõi được phiên đang hoạt động.
                        </span>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-sign-out-alt"></i> Kết thúc phiên này
                            </button>
                        </form>
                    </div>
                </div>

                <div class="profile-card">
                    <div class="card-head">
                        <div>
                            <h2>Sanctum API Tokens</h2>
                            <p>Danh sách token đã cấp qua <code class="mono-val">/api/v1/auth/login</code>.</p>
                        </div>
                        <span class="badge badge-purple">{{ $tokens->count() }} token</span>
                    </div>

                    @if($tokens->isEmpty())
                        <div style="padding:1.25rem; text-align:center; border-radius:8px; background:#f7f8fa; border:1px dashed var(--border); color:var(--text-muted); font-size:0.84rem;">
                            <i class="fas fa-key" style="display:block; font-size:1.2rem; margin-bottom:0.45rem;"></i>
                            Chưa có Personal Access Token nào đang hoạt động.
                        </div>
                    @else
                        <div class="token-list">
                            @foreach($tokens as $token)
                                <div class="token-row">
                                    <div style="min-width:0; flex:1;">
                                        <div style="font-weight:700; font-size:0.88rem; color:var(--text);">
                                            {{ $token->name }}
                                            <span class="mono-val" style="font-weight:500; font-size:0.76rem; color:var(--text-muted);">#{{ $token->id }}</span>
                                        </div>
                                        <div style="font-size:0.77rem; color:var(--text-muted); margin-top:0.18rem;">
                                            Tạo: {{ $token->created_at?->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                                            · Dùng cuối: {{ $token->last_used_at ? $token->last_used_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'Chưa sử dụng' }}
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.profile.tokens.destroy', $token->id) }}"
                                          method="POST"
                                          data-confirm="Bạn chắc chắn muốn thu hồi API Token '{{ $token->name }}'?"
                                          data-confirm-type="danger"
                                          data-confirm-title="Thu hồi Sanctum API Token"
                                          data-confirm-btn="<i class='fas fa-trash-alt'></i> Thu hồi ngay">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-ban"></i> Thu hồi
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- TAB 3: Recent Activity Timeline -->
    <section id="panel-activity"
             role="tabpanel"
             aria-labelledby="tab-activity"
             class="profile-tab-panel {{ $activeTab === 'activity' ? 'active' : '' }}">
        <div class="profile-card">
            <div class="card-head">
                <div>
                    <h2>Nhật ký hoạt động CMS</h2>
                    <p>Tổng hợp theo thời gian thực từ thay đổi tài khoản, bài viết, dự án, chứng chỉ và AI Automation.</p>
                </div>

                <div class="activity-toolbar">
                    <div class="filter-pills" role="group" aria-label="Lọc loại hoạt động">
                        <button type="button" class="filter-pill active" data-filter="all">Tất cả ({{ $activities->count() }})</button>
                        <button type="button" class="filter-pill" data-filter="account">Tài khoản</button>
                        <button type="button" class="filter-pill" data-filter="content">Nội dung CMS</button>
                        <button type="button" class="filter-pill" data-filter="ai">AI Studio</button>
                    </div>
                </div>
            </div>

            @if($activities->isEmpty())
                <div style="padding:2rem 1rem; text-align:center; color:var(--text-muted); font-size:0.88rem;">
                    Chưa ghi nhận hoạt động nào trong cơ sở dữ liệu.
                </div>
            @else
                <div class="timeline-list" id="activityTimelineList">
                    @foreach($activities as $idx => $item)
                        <div class="timeline-item"
                             data-category="{{ $item['category'] }}"
                             style="--stagger-i: {{ $idx }};">
                            <span class="timeline-node tone-{{ $item['tone'] }}" aria-hidden="true">
                                <i class="fas {{ $item['icon'] }}"></i>
                            </span>
                            <div style="min-width:0; flex:1;">
                                <div style="font-weight:600; font-size:0.89rem; color:var(--text); overflow-wrap:anywhere;">
                                    {{ $item['title'] }}
                                </div>
                                <div style="font-size:0.8rem; color:var(--text-muted); margin-top:0.2rem; overflow-wrap:anywhere;">
                                    {{ $item['detail'] }}
                                </div>
                            </div>
                            <div style="display:flex; align-items:center; gap:0.65rem; flex-shrink:0;">
                                <div style="text-align:right;">
                                    <div style="font-size:0.78rem; font-weight:600; color:#434d5e;">
                                        {{ $item['timestamp']->diffForHumans() }}
                                    </div>
                                    <div class="mono-val" style="font-size:0.72rem; color:var(--text-muted);">
                                        {{ $item['timestamp']->timezone('Asia/Ho_Chi_Minh')->format('d/m H:i') }}
                                    </div>
                                </div>
                                @if(!empty($item['url']))
                                    <a href="{{ $item['url'] }}" class="btn btn-secondary btn-sm" title="Xem chi tiết">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="activityEmptyFilter" style="display:none; padding:1.5rem; text-align:center; color:var(--text-muted); font-size:0.86rem;">
                    Không có mục hoạt động nào thuộc nhóm đã chọn.
                </div>
            @endif
        </div>
    </section>

    <!-- TAB 4: Preferences -->
    <section id="panel-preferences"
             role="tabpanel"
             aria-labelledby="tab-preferences"
             class="profile-tab-panel {{ $activeTab === 'preferences' ? 'active' : '' }}">
        <div class="profile-grid">
            <div class="profile-card">
                <div class="card-head">
                    <div>
                        <h2>Ngôn ngữ &amp; Giao diện</h2>
                        <p>Chuyển đổi ngôn ngữ hiển thị Portfolio đang lưu trong session trình duyệt.</p>
                    </div>
                    <span class="badge badge-purple">{{ strtoupper((string) $currentSession['locale']) }}</span>
                </div>

                <div class="form-group">
                    <label class="form-label">Ngôn ngữ Portfolio công khai</label>
                    <div class="lang-option-grid">
                        <a href="{{ route('lang.switch', 'vi') }}"
                           class="lang-card {{ $currentSession['locale'] === 'vi' ? 'selected' : '' }}">
                            <div>
                                <div style="font-weight:700; font-size:0.9rem;">Tiếng Việt (VI)</div>
                                <div style="font-size:0.77rem; color:var(--text-muted);">Mặc định phiên hiện tại</div>
                            </div>
                            @if($currentSession['locale'] === 'vi')
                                <span class="badge badge-purple">Đang chọn</span>
                            @endif
                        </a>

                        <a href="{{ route('lang.switch', 'en') }}"
                           class="lang-card {{ $currentSession['locale'] === 'en' ? 'selected' : '' }}">
                            <div>
                                <div style="font-weight:700; font-size:0.9rem;">English (EN)</div>
                                <div style="font-size:0.77rem; color:var(--text-muted);">International audience</div>
                            </div>
                            @if($currentSession['locale'] === 'en')
                                <span class="badge badge-purple">Active</span>
                            @endif
                        </a>
                    </div>
                </div>

                <div style="padding-top:1rem; border-top:1px solid #eef1f5;">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; flex-wrap:wrap;">
                        <div>
                            <div style="font-weight:700; font-size:0.89rem; color:var(--text);">
                                Chủ đề giao diện hệ thống
                            </div>
                            <div style="font-size:0.8rem; color:var(--text-muted); margin-top:0.15rem;">
                                Đang sử dụng bảng màu chuẩn Mineral Paper &amp; Cobalt Graphite.
                            </div>
                        </div>
                        <span class="badge badge-secondary">Light Fixed Theme</span>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <div class="card-head">
                    <div>
                        <h2>Thông báo &amp; Cảnh báo CMS</h2>
                        <p>Các cảnh báo nổi bật hiển thị tự động trong Admin Sidebar &amp; Toast.</p>
                    </div>
                    <button type="button"
                            class="btn btn-secondary btn-sm"
                            onclick="window.showAdminToast('Hệ thống thông báo Admin Toast đang hoạt động ổn định.', 'success', 'Kiểm tra thông báo')">
                        <i class="fas fa-bell"></i> Thử Toast
                    </button>
                </div>

                <div class="ledger-list">
                    <div class="ledger-row">
                        <span class="ledger-key">Tin nhắn liên hệ chưa đọc</span>
                        <span class="ledger-val">
                            <a href="{{ route('admin.messages.index') }}" style="color:var(--accent); text-decoration:none;">
                                {{ $cmsSummary['unread_messages'] }} tin nhắn
                            </a>
                        </span>
                    </div>
                    <div class="ledger-row">
                        <span class="ledger-key">Bài viết AI chờ duyệt</span>
                        <span class="ledger-val">
                            <a href="{{ route('admin.ai.posts.index') }}" style="color:var(--accent); text-decoration:none;">
                                {{ $cmsSummary['pending_ai_posts'] }} bài viết
                            </a>
                        </span>
                    </div>
                    <div class="ledger-row">
                        <span class="ledger-key">Lịch chạy AI Automation bật</span>
                        <span class="ledger-val">
                            <a href="{{ route('admin.ai.automations.index') }}" style="color:var(--accent); text-decoration:none;">
                                {{ $cmsSummary['active_automations'] }} lịch chạy
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Quick Edit Profile Modal -->
<div class="profile-modal-overlay"
     id="quickEditProfileModal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="quickEditModalTitle">
    <div class="profile-modal-dialog">
        <div class="profile-modal-head">
            <h3 id="quickEditModalTitle">Chỉnh sửa nhanh hồ sơ</h3>
            <button type="button" class="modal-close-btn" id="closeQuickEditBtn" aria-label="Đóng cửa sổ">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" class="js-profile-form" data-loading-text="Đang lưu...">
            @csrf
            @method('PUT')
            <div class="profile-modal-body">
                <div class="form-group">
                    <label class="form-label" for="modal_name">Họ và tên</label>
                    <input type="text" id="modal_name" name="name" class="form-control" value="{{ $user->name }}" required maxlength="255">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="modal_email">Địa chỉ Email</label>
                    <input type="email" id="modal_email" name="email" class="form-control" value="{{ $user->email }}" required maxlength="255">
                </div>
            </div>
            <div class="profile-modal-foot">
                <button type="button" class="btn btn-secondary" id="cancelQuickEditBtn">Hủy</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function() {
    const tabList = document.getElementById('profileTabList');
    const tabBtns = Array.from(document.querySelectorAll('.profile-tab-btn'));
    const tabPanels = Array.from(document.querySelectorAll('.profile-tab-panel'));
    const indicator = document.getElementById('profileTabIndicator');

    function positionIndicator(btn) {
        if (!btn || !indicator) return;
        indicator.style.width = btn.offsetWidth + 'px';
        indicator.style.transform = 'translateX(' + btn.offsetLeft + 'px)';
    }

    window.activateProfileTab = function(tabName, focusTab) {
        const targetBtn = tabBtns.find(b => b.dataset.tab === tabName) || tabBtns[0];
        if (!targetBtn) return;

        tabBtns.forEach(b => {
            const active = (b === targetBtn);
            b.classList.toggle('active', active);
            b.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        tabPanels.forEach(p => {
            p.classList.toggle('active', p.id === 'panel-' + targetBtn.dataset.tab);
        });

        positionIndicator(targetBtn);

        if (focusTab) targetBtn.focus();

        if (window.history && window.history.replaceState) {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', targetBtn.dataset.tab);
            window.history.replaceState({}, '', url.toString());
        }
    };

    tabBtns.forEach((btn, index) => {
        btn.addEventListener('click', () => activateProfileTab(btn.dataset.tab));
        btn.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') {
                e.preventDefault();
                const next = tabBtns[(index + 1) % tabBtns.length];
                activateProfileTab(next.dataset.tab, true);
            } else if (e.key === 'ArrowLeft') {
                e.preventDefault();
                const prev = tabBtns[(index - 1 + tabBtns.length) % tabBtns.length];
                activateProfileTab(prev.dataset.tab, true);
            }
        });
    });

    const initialActive = tabBtns.find(b => b.classList.contains('active')) || tabBtns[0];
    requestAnimationFrame(() => positionIndicator(initialActive));
    window.addEventListener('resize', () => {
        const active = tabBtns.find(b => b.classList.contains('active'));
        positionIndicator(active);
    });

    // Copy email interaction
    const copyBtn = document.getElementById('copyEmailBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', async () => {
            const email = copyBtn.dataset.email || '';
            try {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    await navigator.clipboard.writeText(email);
                } else {
                    const tmp = document.createElement('textarea');
                    tmp.value = email;
                    tmp.setAttribute('readonly', '');
                    tmp.style.position = 'fixed';
                    tmp.style.opacity = '0';
                    document.body.appendChild(tmp);
                    tmp.select();
                    document.execCommand('copy');
                    tmp.remove();
                }
                copyBtn.innerHTML = '<i class="fas fa-check"></i><span>Đã chép</span>';
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="far fa-copy"></i><span>Sao chép</span>';
                }, 1800);
            } catch (_) {}
        });
    }

    // Quick Edit Modal
    const modal = document.getElementById('quickEditProfileModal');
    const openBtns = [
        document.getElementById('openQuickEditBtn'),
        document.getElementById('openQuickEditFromAvatar')
    ].filter(Boolean);
    const closeBtns = [
        document.getElementById('closeQuickEditBtn'),
        document.getElementById('cancelQuickEditBtn')
    ].filter(Boolean);
    let lastTriggerEl = null;

    function openQuickModal(trigger) {
        if (!modal) return;
        lastTriggerEl = trigger || null;
        modal.classList.add('open');
        const nameInput = document.getElementById('modal_name');
        if (nameInput) setTimeout(() => nameInput.focus(), 60);
    }

    function closeQuickModal() {
        if (!modal) return;
        modal.classList.remove('open');
        if (lastTriggerEl && typeof lastTriggerEl.focus === 'function') {
            lastTriggerEl.focus();
        }
    }

    openBtns.forEach(btn => btn.addEventListener('click', () => openQuickModal(btn)));
    closeBtns.forEach(btn => btn.addEventListener('click', closeQuickModal));
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeQuickModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('open')) closeQuickModal();
        });
    }

    // Password toggle visibility
    window.toggleSecretField = function(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.className = 'far fa-eye-slash';
        } else {
            input.type = 'password';
            if (icon) icon.className = 'far fa-eye';
        }
    };

    // Password strength & match indicator
    const newPw = document.getElementById('new_password');
    const confPw = document.getElementById('password_confirmation');
    const fill = document.getElementById('pwMeterFill');
    const label = document.getElementById('pwMeterLabel');
    const lenHint = document.getElementById('pwLengthHint');
    const matchHint = document.getElementById('pwMatchHint');

    function evaluatePassword() {
        if (!newPw || !fill) return;
        const v = newPw.value || '';
        lenHint.textContent = v.length + ' ký tự';
        let score = 0;
        if (v.length >= 8) score++;
        if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
        if (/\d/.test(v)) score++;
        if (/[^A-Za-z0-9]/.test(v)) score++;

        const levels = [
            { w: '0%', color: 'var(--border)', text: 'Nhập ít nhất 8 ký tự' },
            { w: '28%', color: '#dc2626', text: 'Yếu — nên thêm chữ hoa và số' },
            { w: '56%', color: '#b45309', text: 'Trung bình' },
            { w: '82%', color: '#1e40af', text: 'Khá tốt' },
            { w: '100%', color: '#15803d', text: 'Mật khẩu mạnh' },
        ];
        const lv = v.length === 0 ? levels[0] : levels[Math.max(1, score)];
        fill.style.width = lv.w;
        fill.style.backgroundColor = lv.color;
        label.textContent = lv.text;

        if (confPw && matchHint) {
            if (!confPw.value) {
                matchHint.textContent = '';
            } else if (confPw.value === v) {
                matchHint.style.color = '#15803d';
                matchHint.innerHTML = '<i class="fas fa-check"></i> Khớp mật khẩu mới';
            } else {
                matchHint.style.color = '#dc2626';
                matchHint.textContent = 'Mật khẩu xác nhận chưa khớp.';
            }
        }
    }

    if (newPw) newPw.addEventListener('input', evaluatePassword);
    if (confPw) confPw.addEventListener('input', evaluatePassword);

    // Form loading feedback
    document.querySelectorAll('.js-profile-form').forEach(form => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                const txt = form.dataset.loadingText || 'Đang xử lý...';
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + txt;
            }
        });
    });

    // Activity timeline category filters
    const filterPills = Array.from(document.querySelectorAll('.filter-pill'));
    const timelineItems = Array.from(document.querySelectorAll('.timeline-item'));
    const emptyFilter = document.getElementById('activityEmptyFilter');

    filterPills.forEach(pill => {
        pill.addEventListener('click', () => {
            const filter = pill.dataset.filter;
            filterPills.forEach(p => p.classList.toggle('active', p === pill));

            let visibleCount = 0;
            timelineItems.forEach(item => {
                const match = (filter === 'all' || item.dataset.category === filter);
                item.classList.toggle('is-hidden', !match);
                if (match) visibleCount++;
            });

            if (emptyFilter) {
                emptyFilter.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    });
})();
</script>
@endsection
