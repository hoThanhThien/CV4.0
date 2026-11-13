@extends('layouts.app')

@section('title', __('Credentials') . ' | ' . __('Hồ Thành Thiện'))
@section('description', app()->getLocale() == 'vi' ? 'Hồ sơ năng lực cá nhân, Brochure PDF, bằng cấp chính quy, chứng chỉ chuyên môn và các giải thưởng đạt được của Kỹ sư Hồ Thành Thiện.' : 'Official degrees, verified certificates, honors, awards and portfolio brochure of Ho Thanh Thien - Full-Stack Developer.')
@section('keywords', app()->getLocale() == 'vi' ? 'Hồ sơ năng lực, Brochure Hồ Thành Thiện, Bằng Cử nhân CNTT UTH, Chứng chỉ lập trình, Giấy khen, Giải thưởng' : 'Credentials, Ho Thanh Thien Brochure, IT Degree UTH, Certificates, Honors, Awards')

@section('styles')
<style>
    .cert-header {
        padding: 3.5rem 0 2.25rem;
        background: var(--bg-secondary, #eef1f5);
        border-bottom: 1px solid var(--border, #dce1e9);
    }
    .cert-header h1 {
        font-size: clamp(1.9rem, 4vw, 2.75rem);
        font-weight: 700;
        letter-spacing: -0.025em;
        color: var(--text-primary, #12161f);
        margin-bottom: 0.65rem;
        line-height: 1.15;
    }
    .cert-header p {
        color: var(--text-secondary, #434d5e);
        font-size: 1.02rem;
        max-width: 65ch;
        line-height: 1.65;
        margin-bottom: 1.75rem;
    }

    /* Filter Tabs */
    .cert-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        align-items: center;
    }
    .cert-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 0.95rem;
        border-radius: 7px;
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        color: var(--text-secondary, #434d5e);
        text-decoration: none;
        font-size: 0.86rem;
        font-weight: 500;
        transition: border-color 0.16s ease, color 0.16s ease, background 0.16s ease;
    }
    .cert-filter-btn:hover {
        border-color: var(--text-primary, #12161f);
        color: var(--text-primary, #12161f);
    }
    .cert-filter-btn.active {
        background: var(--text-primary, #12161f);
        border-color: var(--text-primary, #12161f);
        color: #ffffff;
    }
    .cert-filter-btn .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.35rem;
        height: 1.25rem;
        padding: 0 0.35rem;
        border-radius: 4px;
        background: var(--bg-secondary, #eef1f5);
        color: var(--text-muted, #687385);
        font-size: 0.74rem;
        font-weight: 600;
    }
    .cert-filter-btn.active .count-badge {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
    }

    /* Grid */
    .cert-section {
        padding: 2.75rem 0 5rem;
    }
    .cert-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.5rem;
    }
    @media (max-width: 1024px) {
        .cert-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 640px) {
        .cert-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Card */
    .cert-card {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
    }
    .cert-card:hover {
        border-color: #b8c2d1;
        box-shadow: 0 8px 24px rgba(18, 22, 31, 0.06);
        transform: translateY(-2px);
    }

    /* Document Preview Frame */
    .cert-preview-wrap {
        position: relative;
        aspect-ratio: 16 / 10;
        background: #f1f3f7;
        border-bottom: 1px solid var(--border, #dce1e9);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .cert-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 5px;
    }
    .cert-preview-wrap:has(.cert-preview-img) {
        padding: 0;
    }

    /* Crisp Architectural Paper PDF Mockup */
    .pdf-mockup-plate {
        width: 100%;
        height: 100%;
        background: #ffffff;
        border: 1px solid var(--border, #dce1e9);
        border-radius: 6px;
        padding: 0.9rem 1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(18, 22, 31, 0.04);
    }
    .pdf-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding-bottom: 0.55rem;
        border-bottom: 1px solid #eceff4;
    }
    .pdf-badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        font-size: 0.72rem;
        font-weight: 600;
    }
    .pdf-file-note {
        font-size: 0.74rem;
        color: var(--text-muted, #687385);
        font-weight: 500;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 55%;
    }
    .pdf-lines-mock {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        margin: 0.5rem 0;
    }
    .pdf-line {
        height: 6px;
        border-radius: 3px;
        background: #e5e9f0;
    }
    .pdf-footer-tag {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: var(--text-muted, #687385);
        padding-top: 0.45rem;
        border-top: 1px solid #f1f3f7;
    }
    .pdf-verified-inline {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        color: #15803d;
        font-weight: 600;
    }

    /* Overlay Pills inside Preview */
    .cert-cat-pill {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 5px;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid var(--border, #dce1e9);
        color: var(--text-primary, #12161f);
        font-size: 0.74rem;
        font-weight: 600;
    }
    .cert-cat-pill i {
        color: var(--accent, #1e40af);
    }
    .cert-featured-pill {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.6rem;
        border-radius: 5px;
        background: var(--text-primary, #12161f);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 600;
    }

    /* Card Body */
    .cert-body {
        padding: 1.25rem 1.35rem 1.35rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .cert-meta-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.45rem 0.9rem;
        font-size: 0.8rem;
        color: var(--text-muted, #687385);
        margin-bottom: 0.6rem;
    }
    .cert-meta-row span {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .cert-title {
        font-size: 1.06rem;
        font-weight: 700;
        color: var(--text-primary, #12161f);
        line-height: 1.4;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .cert-desc {
        font-size: 0.88rem;
        color: var(--text-secondary, #434d5e);
        line-height: 1.6;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Extra Attachments */
    .cert-extras {
        margin-bottom: 1rem;
        padding-top: 0.8rem;
        border-top: 1px dashed var(--border, #dce1e9);
    }
    .cert-extras-title {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text-muted, #687385);
        margin-bottom: 0.45rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .cert-extras-list {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .cert-extra-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        font-size: 0.8rem;
        background: var(--bg-primary, #f7f8fa);
        padding: 0.4rem 0.6rem;
        border-radius: 6px;
        border: 1px solid var(--border, #dce1e9);
    }
    .cert-extra-name {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        min-width: 0;
        flex: 1;
        color: var(--text-secondary, #434d5e);
    }
    .cert-extra-name span.truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .cert-extra-size {
        font-size: 0.74rem;
        color: var(--text-muted, #687385);
        flex-shrink: 0;
    }
    .cert-extra-actions {
        display: flex;
        gap: 0.3rem;
        flex-shrink: 0;
    }
    .cert-mini-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 5px;
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        color: var(--text-secondary, #434d5e);
        font-size: 0.74rem;
        cursor: pointer;
        text-decoration: none;
        transition: border-color 0.15s ease, color 0.15s ease;
    }
    .cert-mini-btn:hover {
        border-color: var(--text-primary, #12161f);
        color: var(--text-primary, #12161f);
    }

    /* Card Footer */
    .cert-footer {
        margin-top: auto;
        padding-top: 0.9rem;
        border-top: 1px solid #eef1f5;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }
    .cert-footer-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        font-size: 0.78rem;
        color: var(--text-muted, #687385);
    }
    .cert-file-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 500;
    }
    .cert-attach-count {
        color: var(--accent, #1e40af);
        font-weight: 600;
    }
    .cert-action-btns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.6rem;
    }
    .btn-cert-view {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.55rem 0.85rem;
        border-radius: 7px;
        background: var(--text-primary, #12161f);
        border: 1px solid var(--text-primary, #12161f);
        color: #ffffff;
        font-family: inherit;
        font-size: 0.83rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.16s ease, border-color 0.16s ease;
    }
    .btn-cert-view:hover {
        background: var(--accent, #1e40af);
        border-color: var(--accent, #1e40af);
    }
    .btn-cert-dl {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.55rem 0.85rem;
        border-radius: 7px;
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        color: var(--text-primary, #12161f);
        font-size: 0.83rem;
        font-weight: 600;
        text-decoration: none;
        transition: border-color 0.16s ease, background 0.16s ease;
    }
    .btn-cert-dl:hover {
        border-color: var(--text-primary, #12161f);
        background: var(--bg-primary, #f7f8fa);
    }

    /* Empty State */
    .cert-empty {
        text-align: center;
        padding: 4.5rem 1.5rem;
        background: var(--bg-card, #ffffff);
        border-radius: 10px;
        border: 1px solid var(--border, #dce1e9);
    }
    .cert-empty i.empty-icon {
        font-size: 2.5rem;
        color: var(--text-muted, #687385);
        margin-bottom: 0.85rem;
        display: block;
    }
    .cert-empty h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-primary, #12161f);
        margin-bottom: 0.45rem;
    }
    .cert-empty p {
        color: var(--text-secondary, #434d5e);
        margin-bottom: 1.35rem;
        font-size: 0.94rem;
    }

    /* Lightbox Modal */
    .cert-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(18, 22, 31, 0.72);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .cert-modal.active {
        display: flex;
    }
    .cert-modal-box {
        width: 100%;
        max-width: 980px;
        height: 88vh;
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 24px 60px rgba(18, 22, 31, 0.25);
    }
    .cert-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.9rem 1.25rem;
        background: var(--bg-card, #ffffff);
        border-bottom: 1px solid var(--border, #dce1e9);
    }
    .cert-modal-title {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-size: 0.96rem;
        font-weight: 700;
        color: var(--text-primary, #12161f);
        min-width: 0;
    }
    .cert-modal-title span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .cert-modal-title i {
        color: var(--accent, #1e40af);
    }
    .cert-modal-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    .btn-modal-ext,
    .btn-modal-dl {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.42rem 0.8rem;
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .btn-modal-ext {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        color: var(--text-secondary, #434d5e);
    }
    .btn-modal-ext:hover {
        border-color: var(--text-primary, #12161f);
        color: var(--text-primary, #12161f);
    }
    .btn-modal-dl {
        background: var(--text-primary, #12161f);
        border: 1px solid var(--text-primary, #12161f);
        color: #ffffff;
    }
    .btn-modal-dl:hover {
        background: var(--accent, #1e40af);
        border-color: var(--accent, #1e40af);
    }
    .btn-modal-close {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 6px;
        background: var(--bg-secondary, #eef1f5);
        border: 1px solid var(--border, #dce1e9);
        color: var(--text-primary, #12161f);
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .btn-modal-close:hover {
        background: #dce1e9;
    }
    .cert-modal-body {
        flex: 1;
        background: #eef1f5;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: auto;
        padding: 0.75rem;
    }
    .cert-modal-body iframe {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 6px;
        background: #ffffff;
    }
    .cert-modal-body img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 6px;
        box-shadow: 0 4px 18px rgba(18, 22, 31, 0.12);
    }
    @media (max-width: 640px) {
        .cert-modal {
            padding: 0.5rem;
        }
        .cert-modal-box {
            height: 92vh;
        }
        .btn-modal-ext span,
        .btn-modal-dl span {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<!-- Header Section -->
<section class="cert-header">
    <div class="container">
        <h1>{!! __('heading_credentials') !!}</h1>
        <p>{{ __('credentials_subtitle') }}</p>

        <!-- Category Filters -->
        <div class="cert-filter-bar">
            <a href="{{ route('certificates') }}" class="cert-filter-btn {{ !request('category') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>{{ __('All Documents') }}</span>
                <span class="count-badge">{{ \App\Models\Certificate::count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'brochure']) }}" class="cert-filter-btn {{ request('category') == 'brochure' ? 'active' : '' }}">
                <i class="fas fa-book-open"></i>
                <span>{{ __('Portfolio Brochure') }}</span>
                <span class="count-badge">{{ \App\Models\Certificate::where('category', 'brochure')->count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'degree']) }}" class="cert-filter-btn {{ request('category') == 'degree' ? 'active' : '' }}">
                <i class="fas fa-graduation-cap"></i>
                <span>{{ __('Official Degree') }}</span>
                <span class="count-badge">{{ \App\Models\Certificate::where('category', 'degree')->count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'award']) }}" class="cert-filter-btn {{ request('category') == 'award' ? 'active' : '' }}">
                <i class="fas fa-trophy"></i>
                <span>{{ __('Award & Honor') }}</span>
                <span class="count-badge">{{ \App\Models\Certificate::where('category', 'award')->count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'certificate']) }}" class="cert-filter-btn {{ request('category') == 'certificate' ? 'active' : '' }}">
                <i class="fas fa-certificate"></i>
                <span>{{ __('Certificate') }}</span>
                <span class="count-badge">{{ \App\Models\Certificate::where('category', 'certificate')->count() }}</span>
            </a>
        </div>
    </div>
</section>

<!-- Content Grid Section -->
<section class="cert-section">
    <div class="container">
        @if($certificates->isEmpty())
            <div class="cert-empty">
                <i class="fas fa-folder-open empty-icon"></i>
                <h3>{{ __('No documents found') }}</h3>
                <p>{{ app()->getLocale() == 'vi' ? 'Hiện tại chưa có tài liệu nào trong danh mục này.' : 'No documents are currently available in this category.' }}</p>
                <a href="{{ route('certificates') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> {{ __('All Documents') }}
                </a>
            </div>
        @else
            <div class="cert-grid">
                @foreach($certificates as $cert)
                    <article class="cert-card">
                        <!-- Preview Frame -->
                        <div class="cert-preview-wrap">
                            <div class="cert-cat-pill">
                                <i class="{{ $cert->category_icon }}"></i>
                                <span>{{ $cert->category_label }}</span>
                            </div>

                            @if($cert->featured)
                                <div class="cert-featured-pill">
                                    <i class="fas fa-star"></i>
                                    <span>{{ __('Featured') }}</span>
                                </div>
                            @endif

                            @if($cert->thumbnail)
                                @if($cert->thumbnail_is_pdf)
                                    <div class="pdf-mockup-plate">
                                        <div class="pdf-header-bar">
                                            <div class="pdf-badge-tag">
                                                <i class="fas fa-file-pdf"></i> PDF Preview
                                            </div>
                                            <div class="pdf-file-note">{{ basename($cert->thumbnail) }}</div>
                                        </div>
                                        <div class="pdf-lines-mock">
                                            <div class="pdf-line" style="width:90%"></div>
                                            <div class="pdf-line" style="width:74%"></div>
                                            <div class="pdf-line" style="width:82%"></div>
                                        </div>
                                        <div class="pdf-footer-tag">
                                            <span class="pdf-verified-inline"><i class="fas fa-check-circle"></i> Verified</span>
                                            <span>{{ $cert->issuer ?? 'Official' }}</span>
                                        </div>
                                    </div>
                                @else
                                    <img src="{{ $cert->thumbnail_url }}" alt="{{ $cert->title }}" class="cert-preview-img" loading="lazy">
                                @endif
                            @elseif($cert->is_pdf)
                                <div class="pdf-mockup-plate">
                                    <div class="pdf-header-bar">
                                        <div class="pdf-badge-tag">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </div>
                                        <div class="pdf-file-note">{{ $cert->file_size ?? 'Document' }}</div>
                                    </div>
                                    <div class="pdf-lines-mock">
                                        <div class="pdf-line" style="width:90%"></div>
                                        <div class="pdf-line" style="width:74%"></div>
                                        <div class="pdf-line" style="width:84%"></div>
                                        <div class="pdf-line" style="width:52%"></div>
                                    </div>
                                    <div class="pdf-footer-tag">
                                        <span class="pdf-verified-inline"><i class="fas fa-check-circle"></i> Verified</span>
                                        <span>{{ $cert->issuer ?? 'Official' }}</span>
                                    </div>
                                </div>
                            @else
                                <img src="{{ $cert->file_url }}" alt="{{ $cert->title }}" class="cert-preview-img" loading="lazy">
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="cert-body">
                            <div class="cert-meta-row">
                                @if($cert->issuer)
                                    <span><i class="fas fa-building"></i> {{ $cert->issuer }}</span>
                                @endif
                                @if($cert->issue_date)
                                    <span><i class="fas fa-calendar-alt"></i> {{ $cert->issue_date }}</span>
                                @endif
                            </div>

                            <h2 class="cert-title" title="{{ $cert->title }}">{{ $cert->title }}</h2>

                            <p class="cert-desc">{{ $cert->description ?: (app()->getLocale() == 'vi' ? 'Tài liệu xác thực chính thức và hồ sơ năng lực cá nhân.' : 'Verified credential and portfolio documentation.') }}</p>

                            @if(count($cert->extra_files_with_urls) > 0)
                                <div class="cert-extras">
                                    <div class="cert-extras-title">
                                        <i class="fas fa-paperclip"></i>
                                        <span>{{ app()->getLocale() == 'vi' ? 'Tệp đính kèm' : 'Attachments' }} ({{ count($cert->extra_files_with_urls) }})</span>
                                    </div>
                                    <div class="cert-extras-list">
                                        @foreach($cert->extra_files_with_urls as $extra)
                                            <div class="cert-extra-item">
                                                <div class="cert-extra-name">
                                                    <i class="fas {{ $extra['is_pdf'] ? 'fa-file-pdf' : 'fa-file-image' }}" style="color:{{ $extra['is_pdf'] ? '#b91c1c' : '#1e40af' }}"></i>
                                                    <span class="truncate" title="{{ $extra['name'] }}">{{ $extra['name'] }}</span>
                                                    <span class="cert-extra-size">({{ $extra['size'] }})</span>
                                                </div>
                                                <div class="cert-extra-actions">
                                                    <button type="button" class="cert-mini-btn" onclick="openCertModal('{{ $extra['url'] }}', '{{ addslashes($extra['name']) }}', '{{ $extra['type'] }}')" title="{{ __('View Document') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="{{ $extra['url'] }}" download class="cert-mini-btn" title="{{ __('Download') }}">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Card Footer -->
                            <div class="cert-footer">
                                <div class="cert-footer-meta">
                                    <div class="cert-file-badge">
                                        <i class="fas {{ $cert->is_pdf ? 'fa-file-pdf' : 'fa-file-image' }}" style="color:{{ $cert->is_pdf ? '#b91c1c' : '#1e40af' }}"></i>
                                        <span>{{ strtoupper($cert->file_type) }} / {{ $cert->file_size ?? 'N/A' }}</span>
                                    </div>
                                    @if(count($cert->extra_files_with_urls) > 0)
                                        <span class="cert-attach-count">
                                            <i class="fas fa-paperclip"></i> +{{ count($cert->extra_files_with_urls) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="cert-action-btns">
                                    <button type="button" class="btn-cert-view" onclick="openCertModal('{{ $cert->file_url }}', '{{ addslashes($cert->title) }}', '{{ $cert->file_type }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <span>{{ __('View Document') }}</span>
                                    </button>
                                    <a href="{{ $cert->file_url }}" download class="btn-cert-dl" title="{{ __('Download') }}">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                        <span>{{ __('Download') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Document Viewer Modal -->
<div class="cert-modal" id="certModal" onclick="closeCertModal(event)">
    <div class="cert-modal-box" onclick="event.stopPropagation()">
        <div class="cert-modal-header">
            <div class="cert-modal-title" id="certModalTitle">
                <i class="fas fa-file-pdf"></i>
                <span id="certModalTitleText">{{ app()->getLocale() == 'vi' ? 'Tài liệu' : 'Document' }}</span>
            </div>
            <div class="cert-modal-actions">
                <a id="certModalExternalBtn" href="#" target="_blank" class="btn-modal-ext" title="{{ app()->getLocale() == 'vi' ? 'Mở trong tab mới' : 'Open in new tab' }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    <span>{{ app()->getLocale() == 'vi' ? 'Mở tab mới' : 'New Tab' }}</span>
                </a>
                <a id="certModalDownloadBtn" href="#" download class="btn-modal-dl" title="{{ __('Download') }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    <span>{{ __('Download') }}</span>
                </a>
                <button type="button" class="btn-modal-close" onclick="closeCertModalDirect()" title="{{ app()->getLocale() == 'vi' ? 'Đóng' : 'Close' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        </div>
        <div class="cert-modal-body" id="certModalBody">
            <!-- Dynamic Iframe or Image injected here -->
        </div>
    </div>
</div>

<script>
function openCertModal(url, title, type) {
    const modal = document.getElementById('certModal');
    const titleTextEl = document.getElementById('certModalTitleText');
    const bodyEl = document.getElementById('certModalBody');
    const extBtn = document.getElementById('certModalExternalBtn');
    const dlBtn = document.getElementById('certModalDownloadBtn');

    if (titleTextEl) {
        titleTextEl.textContent = title;
    }
    extBtn.href = url;
    dlBtn.href = url;

    if (type === 'pdf') {
        bodyEl.innerHTML = `<iframe src="${url}#toolbar=1" type="application/pdf"></iframe>`;
    } else {
        bodyEl.innerHTML = `<img src="${url}" alt="${title}">`;
    }

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeCertModalDirect() {
    const modal = document.getElementById('certModal');
    const bodyEl = document.getElementById('certModalBody');
    modal.classList.remove('active');
    bodyEl.innerHTML = '';
    document.body.style.overflow = '';
}

function closeCertModal(e) {
    if (e.target.id === 'certModal') {
        closeCertModalDirect();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCertModalDirect();
    }
});
</script>
@endsection
