@extends('layouts.app')

@section('title', __('Credentials') . ' | ' . __('Hồ Thành Thiện'))
@section('description', app()->getLocale() == 'vi' ? 'Hồ sơ năng lực cá nhân, Brochure PDF, bằng cấp chính quy, chứng chỉ chuyên môn và các giải thưởng đạt được của Kỹ sư Hồ Thành Thiện.' : 'Official degrees, verified certificates, honors, awards and portfolio brochure of Ho Thanh Thien - Full-Stack Developer.')
@section('keywords', app()->getLocale() == 'vi' ? 'Hồ sơ năng lực, Brochure Hồ Thành Thiện, Bằng cấp CNTT HaUI, Chứng chỉ lập trình, Giấy khen, Giải thưởng' : 'Credentials, Ho Thanh Thien Brochure, IT Degree HaUI, Certificates, Honors, Awards')

@section('styles')
<style>
    .cert-hero {
        padding: 5rem 0 2.5rem;
        position: relative;
        overflow: hidden;
        background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(124,58,237,0.18) 0%, transparent 70%);
        text-align: center;
    }
    .cert-hero h1 {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 900;
        letter-spacing: -0.02em;
        margin-bottom: 0.75rem;
    }
    .cert-hero p {
        color: var(--text-secondary);
        font-size: 1.05rem;
        max-width: 680px;
        margin: 0 auto 1.75rem;
        line-height: 1.7;
    }

    /* Filter pills */
    .cert-filter-bar {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.6rem;
        margin-bottom: 3rem;
    }
    .cert-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.15rem;
        border-radius: 50px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .cert-filter-btn:hover {
        border-color: var(--accent);
        color: var(--accent-light);
        transform: translateY(-2px);
    }
    .cert-filter-btn.active {
        background: var(--gradient);
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 15px var(--accent-glow);
    }
    .cert-filter-btn .count-badge {
        font-size: 0.75rem;
        padding: 0.15rem 0.45rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.2);
    }
    .cert-filter-btn:not(.active) .count-badge {
        background: rgba(0, 0, 0, 0.08);
        color: var(--text-secondary);
    }

    /* Certificates Grid */
    .cert-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 360px), 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }
    .cert-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.05);
    }
    .cert-card:hover {
        transform: translateY(-6px);
        border-color: rgba(124, 58, 237, 0.4);
        box-shadow: 0 16px 36px -8px var(--accent-glow);
    }

    /* Preview Header in Card */
    .cert-preview-wrap {
        position: relative;
        height: 220px;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.95) 100%);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cert-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .cert-card:hover .cert-preview-img {
        transform: scale(1.05);
    }

    /* PDF Mockup Plate */
    .pdf-mockup-plate {
        width: 82%;
        height: 80%;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: transform 0.3s ease;
    }
    .cert-card:hover .pdf-mockup-plate {
        transform: translateY(-4px) scale(1.02);
    }
    .pdf-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 0.5rem;
    }
    .pdf-badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #fee2e2;
        color: #ef4444;
        font-weight: 700;
        font-size: 0.72rem;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        letter-spacing: 0.04em;
    }
    .pdf-lines-mock {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        margin: 0.8rem 0;
    }
    .pdf-line {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
    }
    .pdf-footer-tag {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Category Pill on top-right */
    .cert-cat-pill {
        position: absolute;
        top: 1rem;
        right: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        z-index: 2;
    }
    .cert-featured-ribbon {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.25rem 0.65rem;
        border-radius: 50px;
        box-shadow: 0 2px 8px rgba(217, 119, 6, 0.4);
        display: flex;
        align-items: center;
        gap: 0.3rem;
        z-index: 2;
    }

    /* Card Body */
    .cert-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .cert-meta-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 0.6rem;
    }
    .cert-meta-row i { color: var(--accent-light); }
    .cert-title {
        font-size: 1.18rem;
        font-weight: 800;
        line-height: 1.4;
        margin-bottom: 0.65rem;
        color: var(--text-primary);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .cert-desc {
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Card Footer Actions */
    .cert-footer {
        padding-top: 1.1rem;
        margin-top: auto;
        border-top: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }
    .cert-footer-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
    .cert-file-badge {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .cert-action-btns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.65rem;
        width: 100%;
    }
    .btn-cert-view {
        padding: 0.6rem 0.9rem;
        border-radius: 12px;
        background: var(--gradient);
        color: #ffffff;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px var(--accent-glow);
    }
    .btn-cert-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px var(--accent-glow);
        color: #ffffff;
        filter: brightness(1.08);
    }
    .btn-cert-dl {
        padding: 0.6rem 0.9rem;
        border-radius: 12px;
        background: rgba(99, 102, 241, 0.08);
        border: 1.5px solid rgba(99, 102, 241, 0.3);
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        font-size: 0.86rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
    }
    .btn-cert-dl:hover {
        border-color: transparent;
        color: #ffffff;
        background: var(--gradient);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px var(--accent-glow);
    }
    .btn-cert-view svg,
    .btn-cert-dl svg {
        flex-shrink: 0;
    }

    /* Modal Viewer */
    .cert-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background: rgba(15, 23, 42, 0.82);
        backdrop-filter: blur(8px);
        animation: fadeIn 0.2s ease;
    }
    .cert-modal.active { display: flex; }
    .cert-modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        width: 100%;
        max-width: 960px;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .cert-modal-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        background: var(--bg-primary);
    }
    .cert-modal-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .cert-modal-body {
        flex: 1;
        min-height: 60vh;
        position: relative;
        overflow: hidden;
        background: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cert-modal-body iframe,
    .cert-modal-body img {
        width: 100%;
        height: 100%;
        min-height: 60vh;
        border: none;
        object-fit: contain;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="cert-hero">
    <div class="container">
        <h1>{!! __('heading_credentials') !!}</h1>
        <p>{{ __('credentials_subtitle') }}</p>

        <!-- Category Filters -->
        <div class="cert-filter-bar">
            <a href="{{ route('certificates') }}" class="cert-filter-btn {{ !request('category') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> {{ __('All Documents') }}
                <span class="count-badge">{{ \App\Models\Certificate::count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'brochure']) }}" class="cert-filter-btn {{ request('category') == 'brochure' ? 'active' : '' }}">
                <i class="fas fa-book-open"></i> {{ __('Portfolio Brochure') }}
                <span class="count-badge">{{ \App\Models\Certificate::where('category', 'brochure')->count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'degree']) }}" class="cert-filter-btn {{ request('category') == 'degree' ? 'active' : '' }}">
                <i class="fas fa-graduation-cap"></i> {{ __('Official Degree') }}
                <span class="count-badge">{{ \App\Models\Certificate::where('category', 'degree')->count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'award']) }}" class="cert-filter-btn {{ request('category') == 'award' ? 'active' : '' }}">
                <i class="fas fa-trophy"></i> {{ __('Award & Honor') }}
                <span class="count-badge">{{ \App\Models\Certificate::where('category', 'award')->count() }}</span>
            </a>
            <a href="{{ route('certificates', ['category' => 'certificate']) }}" class="cert-filter-btn {{ request('category') == 'certificate' ? 'active' : '' }}">
                <i class="fas fa-certificate"></i> {{ __('Certificate') }}
                <span class="count-badge">{{ \App\Models\Certificate::where('category', 'certificate')->count() }}</span>
            </a>
        </div>
    </div>
</section>

<!-- Content Grid Section -->
<section style="padding: 1rem 0 5rem">
    <div class="container">
        @if($certificates->isEmpty())
            <div style="text-align:center; padding:5rem 1rem; background:var(--bg-card); border-radius:20px; border:1px solid var(--border)">
                <i class="fas fa-folder-open" style="font-size:3.5rem; color:var(--text-muted); margin-bottom:1rem; display:block"></i>
                <h3 style="font-size:1.3rem; font-weight:700; margin-bottom:0.5rem">{{ __('No documents found') }}</h3>
                <p style="color:var(--text-muted); margin-bottom:1.5rem">Hiện tại chưa có tài liệu nào trong danh mục này.</p>
                <a href="{{ route('certificates') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Xem tất cả tài liệu
                </a>
            </div>
        @else
            <div class="cert-grid">
                @foreach($certificates as $cert)
                    <article class="cert-card">
                        <!-- Top visual / preview -->
                        <div class="cert-preview-wrap">
                            @if($cert->featured)
                                <div class="cert-featured-ribbon">
                                    <i class="fas fa-star"></i> Featured
                                </div>
                            @endif

                            <div class="cert-cat-pill">
                                <i class="{{ $cert->category_icon }}"></i>
                                <span>{{ $cert->category_label }}</span>
                            </div>

                            @if($cert->thumbnail)
                                @if($cert->thumbnail_is_pdf)
                                    <!-- PDF Thumbnail Mockup -->
                                    <div class="pdf-mockup-plate">
                                        <div class="pdf-header-bar">
                                            <div class="pdf-badge-tag">
                                                <i class="fas fa-file-pdf"></i> PREVIEW PDF
                                            </div>
                                            <div style="font-size:0.72rem; color:#64748b; font-weight:700">
                                                {{ basename($cert->thumbnail) }}
                                            </div>
                                        </div>
                                        <div class="pdf-lines-mock">
                                            <div class="pdf-line" style="width:90%"></div>
                                            <div class="pdf-line" style="width:75%"></div>
                                            <div class="pdf-line" style="width:85%"></div>
                                        </div>
                                        <div class="pdf-footer-tag">
                                            <span><i class="fas fa-check-circle" style="color:#10b981"></i> Verified</span>
                                            <span>{{ $cert->issuer ?? 'Official' }}</span>
                                        </div>
                                    </div>
                                @else
                                    <img src="{{ $cert->thumbnail_url }}" alt="{{ $cert->title }}" class="cert-preview-img" loading="lazy">
                                @endif
                            @elseif($cert->is_pdf)
                                <!-- Elegant PDF Plate Mockup -->
                                <div class="pdf-mockup-plate">
                                    <div class="pdf-header-bar">
                                        <div class="pdf-badge-tag">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </div>
                                        <div style="font-size:0.75rem; color:#64748b; font-weight:700">
                                            {{ $cert->file_size ?? 'DOCUMENT' }}
                                        </div>
                                    </div>
                                    <div class="pdf-lines-mock">
                                        <div class="pdf-line" style="width:90%"></div>
                                        <div class="pdf-line" style="width:75%"></div>
                                        <div class="pdf-line" style="width:85%"></div>
                                        <div class="pdf-line" style="width:50%"></div>
                                    </div>
                                    <div class="pdf-footer-tag">
                                        <span><i class="fas fa-check-circle" style="color:#10b981"></i> Verified</span>
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
                                    <span>&bull;</span>
                                    <span><i class="fas fa-calendar-alt"></i> {{ $cert->issue_date }}</span>
                                @endif
                            </div>

                            <h2 class="cert-title" title="{{ $cert->title }}">{{ $cert->title }}</h2>

                            <p class="cert-desc">{{ $cert->description ?: 'Tài liệu xác thực chính thức và hồ sơ năng lực cá nhân.' }}</p>

                            @if(count($cert->extra_files_with_urls) > 0)
                                <div style="margin-bottom:1rem; padding-top:0.75rem; border-top:1px dashed var(--border)">
                                    <div style="font-size:0.78rem; font-weight:700; color:var(--text-muted); margin-bottom:0.4rem; display:flex; align-items:center; gap:0.35rem">
                                        <i class="fas fa-paperclip" style="color:var(--accent-light)"></i> File đính kèm thêm ({{ count($cert->extra_files_with_urls) }}):
                                    </div>
                                    <div style="display:flex; flex-direction:column; gap:0.35rem">
                                        @foreach($cert->extra_files_with_urls as $extra)
                                            <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; font-size:0.8rem; background:rgba(255,255,255,0.03); padding:0.35rem 0.6rem; border-radius:6px; border:1px solid var(--border)">
                                                <div style="display:flex; align-items:center; gap:0.4rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; flex:1">
                                                    <i class="fas {{ $extra['is_pdf'] ? 'fa-file-pdf' : 'fa-file-image' }}" style="color:{{ $extra['is_pdf'] ? '#ef4444' : '#06b6d4' }}"></i>
                                                    <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap" title="{{ $extra['name'] }}">{{ $extra['name'] }}</span>
                                                    <span style="font-size:0.72rem; color:var(--text-muted); flex-shrink:0">({{ $extra['size'] }})</span>
                                                </div>
                                                <div style="display:flex; gap:0.3rem; flex-shrink:0">
                                                    <button type="button" class="btn btn-secondary" style="padding:0.15rem 0.45rem; font-size:0.72rem" onclick="openCertModal('{{ $extra['url'] }}', '{{ addslashes($extra['name']) }}', '{{ $extra['type'] }}')" title="Xem">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="{{ $extra['url'] }}" download class="btn btn-secondary" style="padding:0.15rem 0.45rem; font-size:0.72rem" title="Tải xuống">
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
                                        <i class="fas {{ $cert->is_pdf ? 'fa-file-pdf' : 'fa-file-image' }}" style="color:{{ $cert->is_pdf ? '#ef4444' : '#06b6d4' }}"></i>
                                        <span>{{ strtoupper($cert->file_type) }} &bull; {{ $cert->file_size ?? 'N/A' }}</span>
                                    </div>
                                    @if(count($cert->extra_files_with_urls) > 0)
                                        <span style="font-size:0.75rem; color:var(--accent-light); font-weight:700">
                                            <i class="fas fa-paperclip"></i> +{{ count($cert->extra_files_with_urls) }} đính kèm
                                        </span>
                                    @endif
                                </div>

                                <div class="cert-action-btns">
                                    <button type="button" class="btn-cert-view" onclick="openCertModal('{{ $cert->file_url }}', '{{ addslashes($cert->title) }}', '{{ $cert->file_type }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <span>{{ __('View Document') }}</span>
                                    </button>
                                    <a href="{{ $cert->file_url }}" download class="btn-cert-dl" title="{{ __('Download') }}">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
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
            <div class="cert-modal-title" id="certModalTitle">Tài liệu</div>
            <div style="display:flex; align-items:center; gap:0.5rem">
                <a id="certModalExternalBtn" href="#" target="_blank" class="btn btn-secondary" style="padding:0.4rem 0.8rem; font-size:0.82rem">
                    <i class="fas fa-external-link-alt"></i> Mở tab mới
                </a>
                <a id="certModalDownloadBtn" href="#" download class="btn btn-primary" style="padding:0.4rem 0.8rem; font-size:0.82rem">
                    <i class="fas fa-download"></i> Tải về
                </a>
                <button type="button" class="btn btn-secondary" style="padding:0.4rem 0.75rem" onclick="closeCertModalDirect()">
                    <i class="fas fa-times"></i>
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
    const titleEl = document.getElementById('certModalTitle');
    const bodyEl = document.getElementById('certModalBody');
    const extBtn = document.getElementById('certModalExternalBtn');
    const dlBtn = document.getElementById('certModalDownloadBtn');

    titleEl.textContent = title;
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
