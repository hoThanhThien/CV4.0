@extends('layouts.app')

@section('title', __('Projects') . ' | ' . __('Hồ Thành Thiện'))
@section('description', app()->getLocale() == 'vi' ? 'Khám phá danh sách các dự án, sản phẩm phần mềm và ứng dụng web hiện đại được phát triển bởi Hồ Thành Thiện.' : 'Explore a curated portfolio of modern web applications, open-source tools, and software projects developed by Ho Thanh Thien.')
@section('keywords', app()->getLocale() == 'vi' ? 'Dự án Hồ Thành Thiện, Portfolio Dự án, Web Apps, Laravel Projects, Vue.js Projects' : 'Ho Thanh Thien Projects, Software Portfolio, Laravel Web Apps, Vue.js React Applications')

@section('styles')
<style>
    .page-header-band {
        padding: 3.5rem 0 2.25rem;
        background: var(--bg-primary);
        border-bottom: 1px solid var(--border);
    }
    .page-header-band h1 {
        font-size: clamp(1.9rem, 4vw, 2.75rem);
        font-weight: 700;
        letter-spacing: -0.025em;
        color: var(--text-primary);
        margin-bottom: 0.6rem;
    }
    .page-header-band p {
        color: var(--text-secondary);
        font-size: 1.02rem;
        max-width: 62ch;
        line-height: 1.65;
    }

    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 320px), 1fr));
        gap: 1.5rem;
    }
    @media (max-width: 1024px) {
        .projects-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
    }
    @media (max-width: 640px) {
        .projects-grid {
            grid-template-columns: 1fr;
            gap: 1.15rem;
        }
    }

    .project-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .project-card:hover {
        border-color: var(--border-strong);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .project-image {
        height: 195px;
        overflow: hidden;
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .project-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform var(--duration-base) var(--ease-out);
    }
    .project-card:hover .project-image img {
        transform: scale(1.025);
    }

    .project-featured-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: var(--text-primary);
        color: #ffffff;
        padding: 0.22rem 0.6rem;
        border-radius: 5px;
        font-size: 0.76rem;
        font-weight: 600;
    }

    .project-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .project-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-bottom: 0.75rem;
    }
    .project-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.45rem;
        line-height: 1.35;
    }
    .project-desc {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        flex: 1;
        margin-bottom: 1.25rem;
    }
    .project-links {
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
        flex-wrap: wrap;
        width: 100%;
    }
    .project-links .btn {
        height: 36px;
        min-height: 36px;
        padding: 0 0.75rem;
        font-size: 0.84rem;
        font-weight: 600;
        border-radius: 7px;
        gap: 0.4rem;
        white-space: nowrap;
        flex: 1 1 calc(50% - 0.25rem);
        justify-content: center;
    }
    .project-links.has-3-links .btn:first-child,
    .project-links:has(.btn:nth-child(3)) .btn:first-child {
        flex: 1 1 100%;
        width: 100%;
    }
    .project-links.has-3-links .btn:not(:first-child),
    .project-links:has(.btn:nth-child(3)) .btn:not(:first-child) {
        flex: 1 1 calc(50% - 0.25rem);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
    }
    .empty-state i { font-size: 2.5rem; margin-bottom: 1rem; color: var(--text-muted); }

    @media (max-width: 768px) {
        .page-header-band { padding: 2.5rem 0 1.75rem; }
    }
    @media (max-width: 480px) {
        .project-image { height: 175px; }
        .project-body { padding: 1.1rem; }
        .project-links .btn { min-width: 0; }
        .project-links.has-3-links .btn:first-child,
        .project-links:has(.btn:nth-child(3)) .btn:first-child { flex: 1 1 100%; width: 100%; }
        .project-links.has-3-links .btn:not(:first-child),
        .project-links:has(.btn:nth-child(3)) .btn:not(:first-child) { flex: 1 1 calc(50% - 0.25rem); }
    }
</style>
@endsection

@section('content')
<section class="page-header-band">
    <div class="container hero-animate">
        <h1>{!! __('heading_my_projects') !!}</h1>
        <p>{{ __("Things I've built and worked on") }}</p>
    </div>
</section>

<section class="section" style="padding-top:2.5rem">
    <div class="container">
        @if($projects->count())
        <div class="projects-grid" data-stagger>
            @foreach($projects as $project)
            <div class="project-card">
                <div class="project-image">
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/og-image.webp') }}';">
                    @else
                        <img src="{{ asset('images/og-image.webp') }}" alt="{{ $project->title }} Thumbnail" loading="lazy" decoding="async">
                    @endif
                    @if($project->featured)
                        <span class="project-featured-badge">{{ __('Featured') }}</span>
                    @endif
                </div>
                <div class="project-body">
                    <div class="project-tags">
                        @foreach($project->technologies->take(5) as $tech)
                            <span class="tag">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                    <div class="project-title">{{ $project->title }}</div>
                    <p class="project-desc">{{ Str::limit(strip_tags($project->clean_description ?? $project->description), 130) }}</p>
                    @php $has3Links = !empty($project->github_url) && !empty($project->demo_url); @endphp
                    <div class="project-links {{ $has3Links ? 'has-3-links' : '' }}">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary btn-sm">
                            {{ __('Details') }}
                        </a>
                        @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                            <i class="fab fa-github"></i> {{ __('Code') }}
                        </a>
                        @endif
                        @if($project->demo_url)
                        <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                            <i class="fas fa-external-link-alt"></i> {{ __('Demo') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($projects->hasPages())
        <nav class="pagination-wrap" aria-label="Pagination">
            @foreach($projects->links()->elements[0] as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $projects->currentPage() == $page ? 'active' : '' }}" {{ $projects->currentPage() == $page ? 'aria-current="page"' : '' }}>{{ $page }}</a>
            @endforeach
        </nav>
        @endif

        @else
        <div class="empty-state">
            <i class="fas fa-code"></i>
            <h3>{{ __('No projects yet') }}</h3>
            <p>{{ __('Check back soon!') }}</p>
        </div>
        @endif
    </div>
</section>
@endsection
