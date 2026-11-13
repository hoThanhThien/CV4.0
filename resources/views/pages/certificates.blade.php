@extends('layouts.app')

@section('title', __('Brochures & Certificates') . ' | ' . __('Hồ Thành Thiện'))
@section('description', app()->getLocale() == 'vi' ? 'Xem và tải xuống Brochure hồ sơ năng lực, Bằng Cử nhân loại Xuất sắc, Giấy khen giải thưởng và các chứng chỉ chuyên môn của Hồ Thành Thiện.' : 'Explore and download official portfolio brochures, University of Transport HCMC degree credentials, awards, and verified certificates of Ho Thanh Thien.')
@section('keywords', app()->getLocale() == 'vi' ? 'Brochure Hồ Thành Thiện, Bằng Cử nhân CNTT, Giấy khen Thiết kế Logo, Hồ sơ năng lực Portfolio PDF' : 'Ho Thanh Thien Brochure, Official Degree UTH, Academic Certificates, Portfolio PDF Download')

@section('styles')
<style>
    .cert-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-top: 1.5rem;
    }
    .cert-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.9rem;
        border-radius: var(--radius-sm);
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: border-color var(--duration-fast) ease, color var(--duration-fast) ease, background var(--duration-fast) ease;
    }
    .cert-filter-btn i {
        color: var(--text-muted);
        font-size: 0.8rem;
    }
    .cert-filter-btn:hover {
        border-color: var(--text-primary);
        color: var(--text-primary);
    }
    .cert-filter-btn.active {
        background: var(--surface-ink);
        border-color: var(--surface-ink);
        color: #FFFFFF;
    }
    .cert-filter-btn.active i {
        color: #93C5FD;
    }
    .cert-count-badge {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        padding: 0.08rem 0.42rem;
        border-radius: 3px;
        background: var(--bg-primary);
        color: var(--text-muted);
    }
    .cert-filter-btn.active .cert-count-badge {
        background: rgba(255, 255, 255, 0.16);
        color: #FFFFFF;
    }

    .cert-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.4rem;
    }

    .cert-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .cert-card:hover {
        border-color: var(--text-primary);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .cert-preview-wrap {
        position: relative;
        height: 190px;
        background: var(--bg-primary);
        border-bottom: 1px solid var(--border);
        padding: 1.1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }
    .cert-preview-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .cert-preview-badges {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
    .cert-cat-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.24rem 0.58rem;
        border-radius: var(--radius-sm);
        background: var(--bg-card);
        border: 1px solid var(--border);
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-primary);
    }
    .cert-featured-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.22rem 0.52rem;
        border-radius: var(--radius-sm);
        background: var(--surface-ink);
        color: #FFFFFF;
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 600;
    }

    .pdf-spec-lines {
        position: relative;
        z-index: 1;
        margin-top: auto;
        padding-top: 0.9rem;
        border-top: 1px dashed var(--border-strong);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--text-muted);
    }

    .cert-body {
        padding: 1.3rem 1.35rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .cert-meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem 0.9rem;
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: var(--accent);
        margin-bottom: 0.5rem;
    }
    .cert-title {
        font-family: var(--font-display);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.35;
        margin-bottom: 0.55rem;
    }
    .cert-desc {
        font-size: 0.9rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 1.15rem;
        flex: 1;
    }

    .cert-extras {
        border-top: 1px dashed var(--border);
        padding-top: 0.75rem;
        margin-bottom: 1rem;
    }
    .cert-extras-title {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.45rem;
    }
    .cert-extras-list {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }
    .cert-extra-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.35rem 0.55rem;
        background: var(--bg-primary);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 0.8rem;
    }
    .cert-extra-name {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        min-width: 0;
        color: var(--text-primary);
    }
    .cert-extra-name span.truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .cert-extra-actions {
        display: inline-flex;
        gap: 0.25rem;
        flex-shrink: 0;
    }
    .cert-mini-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 3px;
        border: 1px solid var(--border);
        background: var(--bg-card);
        color: var(--text-secondary);
        cursor: pointer;
        text-decoration: none;
    }
    .cert-mini-btn:hover {
        border-color: var(--text-primary);
        color: var(--text-primary);
    }

    .cert-footer {
        padding-top: 0.9rem;
        border-top: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .cert-footer-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: var(--text-muted);
    }
    .cert-action-btns {
        display: flex;
        gap: 0.5rem;
    }
    .cert-action-btns .btn {
        flex: 1;
        min-width: 0;
    }

    .cert-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
    }
    .cert-empty i.empty-icon {
        font-size: 2.25rem;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
    }

    /* Document Viewer Modal */
    .cert-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 2000;
        background: rgba(17, 20, 27, 0.65);
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        transition: opacity 0.22s ease;
    }
    .cert-modal.active { display: flex; opacity: 1; }
    .cert-modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 980px;
        height: 88vh;
        max-height: 840px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }
    .cert-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg-card);
    }
    .cert-modal-title {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-family: var(--font-display);
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--text-primary);
        min-width: 0;
    }
    .cert-modal-title span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .cert-modal-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    .btn-modal-close {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: var(--radius-sm);
        background: var(--bg-primary);
        border: 1px solid var(--border);
        color: var(--text-primary);
        cursor: pointer;
    }
    .cert-modal-body {
        flex: 1;
        background: var(--bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: auto;
        padding: 0.75rem;
    }
    .cert-modal-body iframe { width: 100%; height: 100%; border: none; border-radius: var(--radius-sm); background: #FFFFFF; }
    .cert-modal-body img { max-width: 100%; max-height: 100%; object-fit: contain; border-radius: var(--radius-sm); }

    @media (max-width: 1024px) {
        .cert-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .cert-grid { grid-template-columns: 1fr; }
        .cert-action-btns { flex-direction: column; }
        .cert-modal-actions .btn span { display: none; }
    }
</style>
@endsection

@section('content')
@php $isVi = app()->getLocale() === 'vi'; @endphp

<section class="page-header-band">
    <div class="container hero-animate">
        <div class="editorial-kicker">VERIFIED ARCHIVE // CREDENTIALS</div>
        <h1>{!! __('heading_credentials') !!}</h1>
        <p>{{ __('credentials_subtitle') }}</p>

        <div class="cert-filter-bar">
            <a href="{{ route('certificates') }}" class="cert-filter-btn {{ !request('category') ? 'active' : '' }}">
                <i class="fas fa-th-large" aria-hidden="true"></i>
                <span>{{ __('All Documents') }}</span>
                <span class="cert-count-badge">{{ \App\Models\Certificate::count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'brochure']) }}" class="cert-filter-btn {{ request('category') == 'brochure' ? 'active' : '' }}">
                <i class="fas fa-book-open" aria-hidden="true"></i>
                <span>{{ __('Portfolio Brochure') }}</span>
                <span class="cert-count-badge">{{ \App\Models\Certificate::where('category', 'brochure')->count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'degree']) }}" class="cert-filter-btn {{ request('category') == 'degree' ? 'active' : '' }}">
                <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                <span>{{ __('Official Degree') }}</span>
                <span class="cert-count-badge">{{ \App\Models\Certificate::where('category', 'degree')->count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'award']) }}" class="cert-filter-btn {{ request('category') == 'award' ? 'active' : '' }}">
                <i class="fas fa-trophy" aria-hidden="true"></i>
                <span>{{ __('Award & Honor') }}</span>
                <span class="cert-count-badge">{{ \App\Models\Certificate::where('category', 'award')->count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'certificate']) }}" class="cert-filter-btn {{ request('category') == 'certificate' ? 'active' : '' }}">
                <i class="fas fa-certificate" aria-hidden="true"></i>
                <span>{{ __('Certificate') }}</span>
                <span class="cert-count-badge">{{ \App\Models\Certificate::where('category', 'certificate')->count() }}</span>
            </a>
        </div>
    </div>
</section>

<section class="section" style="padding-top:2.25rem">
    <div class="container">
        @if($certificates->isEmpty())
            <div class="cert-empty">
                <i class="fas fa-folder-open empty-icon" aria-hidden="true"></i>
                <h3>{{ __('No documents found') }}</h3>
                <p style="margin:0.5rem 0 1.25rem;">{{ $isVi ? 'Hiện tại chưa có tài liệu nào trong danh mục này.' : 'No documents are currently available in this category.' }}</p>
                <a href="{{ route('certificates') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> {{ __('All Documents') }}
                </a>
            </div>
        @else
            <div class="cert-grid" data-stagger>
                @foreach($certificates as $cert)
                    <article class="cert-card">
                        <div class="cert-preview-wrap">
                            @if($cert->thumbnail && !$cert->thumbnail_is_pdf)
                                <img src="{{ $cert->thumbnail_url }}" alt="{{ $cert->title }}" class="cert-preview-img" loading="lazy">
                            @elseif(!$cert->is_pdf)
                                <img src="{{ $cert->file_url }}" alt="{{ $cert->title }}" class="cert-preview-img" loading="lazy">
                            @endif

                            <div class="cert-preview-badges">
                                <span class="cert-cat-pill">
                                    <i class="{{ $cert->category_icon }}" aria-hidden="true"></i>
                                    <span>{{ $cert->category_label }}</span>
                                </span>
                                @if($cert->featured)
                                    <span class="cert-featured-pill">{{ __('Featured') }}</span>
                                @endif
                            </div>

                            @if($cert->is_pdf && (!$cert->thumbnail || $cert->thumbnail_is_pdf))
                                <div class="pdf-spec-lines">
                                    <span><i class="fas fa-file-pdf" style="color:#B91C1C" aria-hidden="true"></i> {{ strtoupper($cert->file_type) }} {{ $cert->file_size ? '· ' . $cert->file_size : '' }}</span>
                                    <span>{{ $isVi ? 'Đã xác thực' : 'Verified' }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="cert-body">
                            <div class="cert-meta-row">
                                @if($cert->issuer)
                                    <span>{{ $cert->issuer }}</span>
                                @endif
                                @if($cert->issue_date)
                                    <span>· {{ $cert->issue_date }}</span>
                                @endif
                            </div>

                            <h2 class="cert-title">{{ $cert->title }}</h2>

                            <p class="cert-desc">
                                {{ $cert->description ?: ($isVi ? 'Tài liệu xác thực chính thức và hồ sơ năng lực cá nhân.' : 'Verified credential and portfolio documentation.') }}
                            </p>

                            @if(count($cert->extra_files_with_urls) > 0)
                                <div class="cert-extras">
                                    <div class="cert-extras-title">
                                        <i class="fas fa-paperclip" aria-hidden="true"></i>
                                        <span>{{ $isVi ? 'Tệp đính kèm' : 'Attachments' }} ({{ count($cert->extra_files_with_urls) }})</span>
                                    </div>
                                    <div class="cert-extras-list">
                                        @foreach($cert->extra_files_with_urls as $extra)
                                            <div class="cert-extra-item">
                                                <div class="cert-extra-name">
                                                    <i class="fas {{ $extra['is_pdf'] ? 'fa-file-pdf' : 'fa-file-image' }}" style="color:{{ $extra['is_pdf'] ? '#B91C1C' : '#1D4ED8' }}" aria-hidden="true"></i>
                                                    <span class="truncate" title="{{ $extra['name'] }}">{{ $extra['name'] }}</span>
                                                </div>
                                                <div class="cert-extra-actions">
                                                    <button type="button" class="cert-mini-btn" onclick="openCertModal('{{ $extra['url'] }}', '{{ addslashes($extra['name']) }}', '{{ $extra['type'] }}')" title="{{ __('View Document') }}">
                                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                                    </button>
                                                    <a href="{{ $extra['url'] }}" download class="cert-mini-btn" title="{{ __('Download') }}">
                                                        <i class="fas fa-download" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="cert-footer">
                                <div class="cert-footer-meta">
                                    <span>{{ strtoupper($cert->file_type) }} / {{ $cert->file_size ?? 'N/A' }}</span>
                                    @if(count($cert->extra_files_with_urls) > 0)
                                        <span>+{{ count($cert->extra_files_with_urls) }} {{ $isVi ? 'tệp' : 'files' }}</span>
                                    @endif
                                </div>

                                <div class="cert-action-btns">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="openCertModal('{{ $cert->file_url }}', '{{ addslashes($cert->title) }}', '{{ $cert->file_type }}')">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                        <span>{{ __('View Document') }}</span>
                                    </button>
                                    <a href="{{ $cert->file_url }}" download class="btn btn-outline btn-sm">
                                        <i class="fas fa-download" aria-hidden="true"></i>
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
<div class="cert-modal" id="certModal" role="dialog" aria-modal="true" aria-labelledby="certModalTitleText" onclick="closeCertModal(event)">
    <div class="cert-modal-box" onclick="event.stopPropagation()">
        <div class="cert-modal-header">
            <div class="cert-modal-title" id="certModalTitle">
                <i class="fas fa-file-pdf" style="color:var(--accent)" aria-hidden="true"></i>
                <span id="certModalTitleText">{{ $isVi ? 'Tài liệu' : 'Document' }}</span>
            </div>
            <div class="cert-modal-actions">
                <a id="certModalExternalBtn" href="#" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                    <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                    <span>{{ $isVi ? 'Mở tab mới' : 'New Tab' }}</span>
                </a>
                <a id="certModalDownloadBtn" href="#" download class="btn btn-primary btn-sm">
                    <i class="fas fa-download" aria-hidden="true"></i>
                    <span>{{ __('Download') }}</span>
                </a>
                <button type="button" id="certModalCloseBtn" class="btn-modal-close" onclick="closeCertModalDirect()" aria-label="{{ $isVi ? 'Đóng' : 'Close' }}">
                    <i class="fas fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="cert-modal-body" id="certModalBody"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let lastCertTrigger = null;

function openCertModal(url, title, type) {
    lastCertTrigger = document.activeElement;
    const modal = document.getElementById('certModal');
    const titleTextEl = document.getElementById('certModalTitleText');
    const bodyEl = document.getElementById('certModalBody');
    const extBtn = document.getElementById('certModalExternalBtn');
    const dlBtn = document.getElementById('certModalDownloadBtn');

    if (titleTextEl) titleTextEl.textContent = title;
    if (extBtn) extBtn.href = url;
    if (dlBtn) dlBtn.href = url;

    if (type === 'pdf') {
        bodyEl.innerHTML = `<iframe src="${url}#toolbar=1" title="${title}"></iframe>`;
    } else {
        bodyEl.innerHTML = `<img src="${url}" alt="${title}">`;
    }

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        const closeBtn = document.getElementById('certModalCloseBtn');
        if (closeBtn) closeBtn.focus();
    }, 50);
}

function closeCertModalDirect() {
    const modal = document.getElementById('certModal');
    const bodyEl = document.getElementById('certModalBody');
    if (!modal || !modal.classList.contains('active')) return;
    modal.classList.remove('active');
    setTimeout(() => {
        if (bodyEl && !modal.classList.contains('active')) bodyEl.innerHTML = '';
    }, 240);
    document.body.style.overflow = '';
    if (lastCertTrigger && typeof lastCertTrigger.focus === 'function') {
        lastCertTrigger.focus();
    }
}

function closeCertModal(e) {
    if (e.target && e.target.id === 'certModal') {
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
