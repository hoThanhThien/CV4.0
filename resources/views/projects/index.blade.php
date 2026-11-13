@extends('layouts.app')

@section('title', __('Projects') . ' | ' . __('Hồ Thành Thiện'))
@section('description', app()->getLocale() == 'vi' ? 'Khám phá danh sách các dự án, sản phẩm phần mềm và kiến trúc hệ thống hiện đại được phát triển bởi Kỹ sư Hồ Thành Thiện.' : 'Explore a curated portfolio of modern web applications, AI/RAG architectures, and enterprise systems developed by Ho Thanh Thien.')
@section('keywords', app()->getLocale() == 'vi' ? 'Dự án Hồ Thành Thiện, Portfolio Dự án, Web Apps, Laravel, Spring Boot, RAG AI, TourRist, Laundry' : 'Ho Thanh Thien Projects, Software Portfolio, Laravel Web Apps, Spring Boot, RAG AI Architecture')

@section('styles')
<style>
    .projects-filter-bar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
        scrollbar-width: thin;
        scrollbar-color: var(--border-strong) transparent;
        -webkit-overflow-scrolling: touch;
    }
    .projects-filter-bar::-webkit-scrollbar {
        height: 4px;
    }
    .projects-filter-bar::-webkit-scrollbar-thumb {
        background: var(--border-strong);
        border-radius: 4px;
    }
    .proj-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.95rem;
        border-radius: var(--radius-sm);
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-family: var(--font-mono);
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        flex-shrink: 0;
        transition: all var(--duration-fast) ease;
    }
    .proj-filter-btn:hover {
        border-color: var(--accent-lime);
        color: var(--text-primary);
    }
    .proj-filter-btn.active {
        background: var(--accent-lime);
        border-color: var(--accent-lime);
        color: #06090F;
        font-weight: 700;
        box-shadow: 0 0 16px rgba(198, 244, 50, 0.35);
    }

    .projects-editorial-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.85rem;
    }

    .project-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .project-card:hover {
        border-color: var(--border-lime);
        box-shadow: 0 14px 34px -8px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(198, 244, 50, 0.22);
        transform: translateY(-2px);
    }
    .project-card.is-hidden {
        display: none !important;
    }

    .project-media-link {
        display: block;
        position: relative;
        aspect-ratio: 16 / 10;
        background: var(--surface-ink);
        border-bottom: 1px solid var(--border);
        overflow: hidden;
        text-decoration: none;
    }
    .project-media-link img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform var(--duration-slow) var(--ease-out);
    }
    .project-card:hover .project-media-link img {
        transform: scale(1.025);
    }

    .project-featured-badge {
        position: absolute;
        top: 0.85rem;
        right: 0.85rem;
        z-index: 3;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(6, 9, 15, 0.92);
        backdrop-filter: blur(8px);
        color: var(--accent-lime);
        border: 1px solid rgba(198, 244, 50, 0.4);
        padding: 0.22rem 0.65rem;
        border-radius: var(--radius-sm);
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .project-featured-badge .badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--accent-lime);
        box-shadow: 0 0 8px var(--accent-lime);
        animation: pulseDot 2s infinite ease-in-out;
    }
    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.85); }
    }

    /* Specimen terminal & architectural plates */
    .specimen-plate {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 240px;
        background: linear-gradient(145deg, var(--navy-deep) 0%, var(--navy-elevated) 100%);
        color: #F8FAFC;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1.35rem 1.5rem 1.5rem;
        overflow: hidden;
    }
    .specimen-plate::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(56, 189, 248, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(56, 189, 248, 0.05) 1px, transparent 1px);
        background-size: 28px 28px;
        pointer-events: none;
    }
    .specimen-topbar {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.14);
    }
    .specimen-dots { display: inline-flex; align-items: center; gap: 5px; }
    .specimen-dots span { width: 8px; height: 8px; border-radius: 50%; }
    .specimen-dots span:nth-child(1) { background: #EF4444; }
    .specimen-dots span:nth-child(2) { background: #F59E0B; }
    .specimen-dots span:nth-child(3) { background: #10B981; }
    .specimen-host { font-family: var(--font-mono); font-size: 0.72rem; color: #94A3B8; }

    .specimen-terminal-body, .specimen-pipeline-body, .specimen-workflow-body {
        position: relative;
        z-index: 1;
        margin: auto 0;
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        font-family: var(--font-mono);
        font-size: 0.78rem;
    }
    .terminal-line { color: #CBD5E1; }
    .terminal-prompt { color: var(--accent-sky); font-weight: 700; margin-right: 0.35rem; }
    .terminal-output { color: #94A3B8; padding-left: 0.85rem; font-size: 0.74rem; }
    .terminal-badge-ok {
        display: inline-block;
        padding: 0.1rem 0.4rem;
        border-radius: 3px;
        background: rgba(16, 185, 129, 0.2);
        color: #34D399;
        font-weight: 600;
        margin-right: 0.35rem;
    }
    .terminal-highlight { color: #F8FAFC; font-weight: 600; }
    .terminal-tags-row { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.5rem; }
    .specimen-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.55rem;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(148, 163, 184, 0.18);
        font-size: 0.7rem;
        color: #E2E8F0;
    }

    .pipeline-flow, .workflow-steps {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin: 0.5rem 0;
    }
    .pipeline-node, .wf-step {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        background: rgba(15, 23, 42, 0.85);
        border: 1px solid rgba(148, 163, 184, 0.22);
        font-size: 0.72rem;
        color: #CBD5E1;
    }
    .pipeline-node.active, .wf-step.active {
        background: rgba(198, 244, 50, 0.15);
        border-color: var(--accent-lime);
        color: var(--accent-lime);
        font-weight: 600;
    }
    .wf-step.done {
        background: rgba(16, 185, 129, 0.12);
        border-color: #10B981;
        color: #34D399;
    }
    .pipeline-arrow, .wf-sep { color: #64748B; font-weight: 700; }

    .project-card-body {
        padding: 1.45rem 1.55rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .project-meta-ribbon {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        font-family: var(--font-mono);
        font-size: 0.74rem;
    }
    .project-idx-tag {
        color: var(--accent-sky);
        font-weight: 700;
        letter-spacing: 0.05em;
    }
    .project-period-tag, .project-role-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.15rem 0.5rem;
        border-radius: var(--radius-sm);
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border);
        color: var(--text-secondary);
    }
    .project-role-tag {
        color: #E2E8F0;
    }

    .project-title {
        font-family: var(--font-display);
        font-size: 1.32rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.55rem;
        line-height: 1.25;
        letter-spacing: -0.02em;
    }
    .project-title a {
        color: inherit;
        text-decoration: none;
        transition: color var(--duration-fast) ease;
    }
    .project-title a:hover {
        color: var(--accent-lime);
    }
    .project-desc {
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.62;
        margin-bottom: 0.85rem;
    }

    .project-highlights-list {
        list-style: none;
        padding: 0;
        margin: 0 0 1.15rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }
    .project-highlights-list li {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        font-size: 0.83rem;
        line-height: 1.5;
        color: #CBD5E1;
    }
    .project-highlights-list li i {
        color: var(--accent-lime);
        font-size: 0.68rem;
        margin-top: 0.35rem;
        flex-shrink: 0;
    }

    .project-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-bottom: 1.25rem;
        margin-top: auto;
    }
    .project-tech-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.22rem 0.58rem;
        border-radius: var(--radius-sm);
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(148, 163, 184, 0.16);
        color: #CBD5E1;
        font-family: var(--font-mono);
        font-size: 0.74rem;
        font-weight: 500;
        transition: all var(--duration-fast) ease;
    }
    .project-card:hover .project-tech-pill {
        border-color: rgba(148, 163, 184, 0.28);
        color: #F8FAFC;
    }

    .project-links {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        padding-top: 1.1rem;
        border-top: 1px solid var(--border);
    }
    .project-links .btn-primary {
        flex: 1 1 auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        min-height: 38px;
        background: var(--accent-lime);
        color: #06090F;
        border-color: var(--accent-lime);
        font-weight: 700;
    }
    .project-links .btn-primary:hover {
        background: var(--accent-lime-hover);
        border-color: var(--accent-lime-hover);
        color: #06090F;
        box-shadow: 0 6px 20px -3px rgba(198, 244, 50, 0.35);
    }
    .project-links .btn-outline {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        min-height: 38px;
        padding: 0.45rem 0.85rem;
        background: rgba(255, 255, 255, 0.03);
        border-color: var(--border-strong);
        color: #F8FAFC;
    }
    .project-links .btn-outline:hover {
        border-color: var(--accent-lime);
        color: var(--accent-lime);
        background: rgba(198, 244, 50, 0.06);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
    }
    .empty-state i {
        font-size: 2.25rem;
        margin-bottom: 0.85rem;
        color: var(--text-muted);
    }

    @media (max-width: 860px) {
        .projects-editorial-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 480px) {
        .project-card-body { padding: 1.15rem; }
        .project-links {
            flex-wrap: wrap;
        }
        .project-links .btn-primary {
            width: 100%;
            flex: 1 0 100%;
        }
        .project-links .btn-outline {
            flex: 1 1 calc(50% - 0.28rem);
            text-align: center;
        }
    }
</style>
@endsection

@section('content')
@php
    $isVi = app()->getLocale() === 'vi';
    $allTechNames = \App\Models\Project::with('technologies')->get()->flatMap->technologies->pluck('name')->countBy()->sortDesc()->take(8)->keys()->all();
@endphp

<section class="page-header-band">
    <div class="container hero-animate">
        <div class="page-header-inner">
            <div>
                <div class="section-kicker" style="color:var(--accent-sky);">ARCHIVE // SOFTWARE &amp; SYSTEMS</div>
                <h1>{!! __('heading_my_projects') !!}</h1>
                <p>{{ __("Things I've built and worked on") }}</p>
            </div>
            <div class="mono-label">{{ $projects->total() }} {{ $isVi ? 'DỰ ÁN' : 'PROJECTS' }}</div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:2.25rem">
    <div class="container">
        @if($projects->count())
            @if(count($allTechNames))
            <div class="projects-filter-bar" role="tablist" aria-label="{{ __('Filter by stack') }}">
                <button type="button" class="proj-filter-btn active" data-proj-filter="all">{{ __('All Stacks') }}</button>
                @foreach($allTechNames as $tName)
                    <button type="button" class="proj-filter-btn" data-proj-filter="{{ strtolower($tName) }}">{{ $tName }}</button>
                @endforeach
            </div>
            @endif

            <div class="projects-editorial-grid" data-stagger>
                @foreach($projects as $idx => $project)
                    @php
                        $hostLabel = $project->demo_url
                            ? preg_replace('#^https?://#', '', rtrim($project->demo_url, '/'))
                            : ($project->github_url ? preg_replace('#^https?://#', '', rtrim($project->github_url, '/')) : 'architecture.spec');
                        $techSlugList = $project->technologies->pluck('name')->map(fn($n) => strtolower($n))->implode('|');
                        $isPortfolio = $project->id === 1 || str_contains(strtolower($project->title), 'portfolio');
                        $isTourrist = $project->id === 2 || str_contains(strtolower($project->title), 'tourrist');
                        $isLaundry = $project->id === 3 || str_contains(strtolower($project->title), 'laundry');
                        $hasCustomImage = $project->has_real_image;
                    @endphp
                    <article class="project-card bento-card has-spotlight" data-proj-techs="{{ $techSlugList }}">
                        <a href="{{ route('projects.show', $project->id) }}" class="project-media-link" aria-label="{{ $project->title }}">
                            @if($project->featured)
                                <span class="project-featured-badge">
                                    <span class="badge-dot"></span>
                                    <span>{{ __('Featured') }}</span>
                                </span>
                            @endif

                            @if($hasCustomImage)
                                <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async">
                            @elseif($isPortfolio)
                                <div class="specimen-plate">
                                    <div class="specimen-topbar">
                                        <span class="specimen-dots"><span></span><span></span><span></span></span>
                                        <span class="specimen-host">hothanhthien.io.vn</span>
                                    </div>
                                    <div class="specimen-terminal-body">
                                        <div class="terminal-line"><span class="terminal-prompt">$</span> curl -I https://hothanhthien.io.vn/</div>
                                        <div class="terminal-output"><span class="terminal-badge-ok">HTTP/2 200 OK</span> server: nginx/cpanel</div>
                                        <div class="terminal-line"><span class="terminal-prompt">$</span> php artisan --version</div>
                                        <div class="terminal-output">Laravel Framework <span class="terminal-highlight">11.x</span> (PHP 8.2)</div>
                                        <div class="terminal-tags-row">
                                            <span class="specimen-pill">Blade Engine</span>
                                            <span class="specimen-pill">MySQL/SQLite</span>
                                            <span class="specimen-pill">Bilingual VI/EN</span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($isTourrist)
                                <div class="specimen-plate">
                                    <div class="specimen-topbar">
                                        <span class="specimen-dots"><span></span><span></span><span></span></span>
                                        <span class="specimen-host">github.com/hoThanhThien/TourRist</span>
                                    </div>
                                    <div class="specimen-pipeline-body">
                                        <div class="terminal-line" style="color:var(--accent-sky); font-weight:600;">PIPELINE // RAG ARCHITECTURE</div>
                                        <div class="pipeline-flow">
                                            <div class="pipeline-node"><i class="fas fa-file-lines"></i> Docs</div>
                                            <div class="pipeline-arrow">&rarr;</div>
                                            <div class="pipeline-node"><i class="fas fa-layer-group"></i> Chunks</div>
                                            <div class="pipeline-arrow">&rarr;</div>
                                            <div class="pipeline-node active"><i class="fas fa-database"></i> ChromaDB</div>
                                            <div class="pipeline-arrow">&rarr;</div>
                                            <div class="pipeline-node"><i class="fas fa-robot"></i> LLM</div>
                                        </div>
                                        <div class="terminal-tags-row">
                                            <span class="specimen-pill">FastAPI</span>
                                            <span class="specimen-pill">Python</span>
                                            <span class="specimen-pill">Vector Search</span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($isLaundry)
                                <div class="specimen-plate">
                                    <div class="specimen-topbar">
                                        <span class="specimen-dots"><span></span><span></span><span></span></span>
                                        <span class="specimen-host">github.com/hoThanhThien/LT_JAVA...</span>
                                    </div>
                                    <div class="specimen-workflow-body">
                                        <div class="terminal-line" style="color:#A78BFA; font-weight:600;">STATE MACHINE // ORDER LIFECYCLE</div>
                                        <div class="workflow-steps">
                                            <div class="wf-step done">RECEIVED</div>
                                            <div class="wf-sep">&rsaquo;</div>
                                            <div class="wf-step active">WASHING</div>
                                            <div class="wf-sep">&rsaquo;</div>
                                            <div class="wf-step">READY</div>
                                            <div class="wf-sep">&rsaquo;</div>
                                            <div class="wf-step">DELIVERED</div>
                                        </div>
                                        <div class="terminal-tags-row">
                                            <span class="specimen-pill">Spring Boot</span>
                                            <span class="specimen-pill">NextJS</span>
                                            <span class="specimen-pill">JWT + RBAC</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="specimen-plate">
                                    <div class="specimen-topbar">
                                        <span class="specimen-dots"><span></span><span></span><span></span></span>
                                        <span class="specimen-host">{{ $hostLabel }}</span>
                                    </div>
                                    <div class="specimen-terminal-body">
                                        <div class="terminal-line" style="color:var(--accent-cyan); font-weight:600;">ENTERPRISE // WEB PLATFORM</div>
                                        <div class="terminal-line"><span class="terminal-prompt">&bull;</span> Responsive Architecture</div>
                                        <div class="terminal-line"><span class="terminal-prompt">&bull;</span> Normalized Relational DB</div>
                                        <div class="terminal-tags-row">
                                            <span class="specimen-pill">PHP</span>
                                            <span class="specimen-pill">PostgreSQL</span>
                                            <span class="specimen-pill">Responsive UI</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </a>

                        <div class="project-card-body">
                            <div class="project-meta-ribbon">
                                <span class="project-idx-tag">PRJ // {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                @if($project->period)
                                    <span class="project-period-tag"><i class="fas fa-calendar-alt" aria-hidden="true"></i> {{ $project->period }}</span>
                                @endif
                                @if($project->role)
                                    <span class="project-role-tag"><i class="fas fa-user-tag" aria-hidden="true"></i> {{ $project->role }}</span>
                                @endif
                            </div>

                            <h2 class="project-title">
                                <a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a>
                            </h2>

                            <p class="project-desc">
                                {{ $project->summary }}
                            </p>

                            @if(count($project->highlights) > 1)
                            <ul class="project-highlights-list">
                                @foreach(array_slice($project->highlights, 1, 2) as $highlight)
                                    <li>
                                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                        <span>{{ $highlight }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            @endif

                            @if($project->technologies->count())
                            <div class="project-tags">
                                @foreach($project->technologies->take(5) as $tech)
                                    <span class="project-tech-pill">{{ $tech->name }}</span>
                                @endforeach
                            </div>
                            @endif

                            <div class="project-links">
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary btn-sm">
                                    <span>{{ __('View Case Study') }}</span>
                                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                                @if($project->github_url)
                                <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm" title="{{ __('Code') }}">
                                    <i class="fab fa-github" aria-hidden="true"></i>
                                    <span>{{ __('Code') }}</span>
                                </a>
                                @endif
                                @if($project->demo_url)
                                <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm" title="{{ __('Demo') }}">
                                    <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                    <span>{{ __('Demo') }}</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach

                <div id="noFilterMatch" class="empty-state" style="display:none; grid-column: 1 / -1; margin-top: 1rem;">
                    <i class="fas fa-filter" aria-hidden="true"></i>
                    <h3>{{ __('No projects found') }}</h3>
                    <p>{{ $isVi ? 'Không có dự án nào sử dụng công nghệ này trong danh sách.' : 'No projects found using this technology in the current list.' }}</p>
                    <button type="button" class="btn btn-outline btn-sm" onclick="resetProjFilter()" style="margin-top:0.75rem;">
                        <i class="fas fa-rotate-left"></i> {{ __('All Stacks') }}
                    </button>
                </div>
            </div>

            @if($projects->hasPages())
            <nav class="pagination-wrap" aria-label="Pagination">
                @if(!$projects->onFirstPage())
                    <a href="{{ $projects->previousPageUrl() }}" class="page-link" aria-label="{{ __('Previous') }}">&lsaquo;</a>
                @endif

                @foreach($projects->links()->elements as $element)
                    @if(is_string($element))
                        <span class="page-link disabled">{{ $element }}</span>
                    @elseif(is_array($element))
                        @foreach($element as $page => $url)
                            <a href="{{ $url }}" class="page-link {{ $projects->currentPage() == $page ? 'active' : '' }}" {{ $projects->currentPage() == $page ? 'aria-current="page"' : '' }}>{{ $page }}</a>
                        @endforeach
                    @endif
                @endforeach

                @if($projects->hasMorePages())
                    <a href="{{ $projects->nextPageUrl() }}" class="page-link" aria-label="{{ __('Next') }}">&rsaquo;</a>
                @endif
            </nav>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-code" aria-hidden="true"></i>
                <h3>{{ __('No projects yet') }}</h3>
                <p>{{ __('Check back soon!') }}</p>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
function resetProjFilter() {
    const allBtn = document.querySelector('[data-proj-filter="all"]');
    if (allBtn) allBtn.click();
}

document.querySelectorAll('[data-proj-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.getAttribute('data-proj-filter');
        document.querySelectorAll('[data-proj-filter]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        let visibleCount = 0;
        document.querySelectorAll('[data-proj-techs]').forEach(card => {
            const techs = card.getAttribute('data-proj-techs').split('|');
            const match = target === 'all' || techs.includes(target);
            card.classList.toggle('is-hidden', !match);
            if (match) visibleCount++;
        });

        const noMatchEl = document.getElementById('noFilterMatch');
        if (noMatchEl) {
            noMatchEl.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    });
});
</script>
@endsection
