@extends('layouts.app')

@section('title', __('Projects') . ' | ' . __('Hồ Thành Thiện'))
@section('description', app()->getLocale() == 'vi' ? 'Khám phá danh sách các dự án, sản phẩm phần mềm và ứng dụng web hiện đại được phát triển bởi Hồ Thành Thiện.' : 'Explore a curated portfolio of modern web applications, open-source tools, and software projects developed by Ho Thanh Thien.')
@section('keywords', app()->getLocale() == 'vi' ? 'Dự án Hồ Thành Thiện, Portfolio Dự án, Web Apps, Laravel Projects, Vue.js Projects' : 'Ho Thanh Thien Projects, Software Portfolio, Laravel Web Apps, Vue.js React Applications')

@section('styles')
<style>
    .projects-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-bottom: 2rem;
    }
    .proj-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.42rem 0.85rem;
        border-radius: var(--radius-sm);
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-family: var(--font-sans);
        font-size: 0.84rem;
        font-weight: 600;
        cursor: pointer;
        transition: border-color var(--duration-fast) ease, color var(--duration-fast) ease, background var(--duration-fast) ease;
    }
    .proj-filter-btn:hover {
        border-color: var(--text-primary);
        color: var(--text-primary);
    }
    .proj-filter-btn.active {
        background: var(--surface-ink);
        border-color: var(--surface-ink);
        color: #FFFFFF;
    }

    .projects-editorial-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.75rem;
    }

    .project-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .project-card:hover {
        border-color: var(--text-primary);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .project-card.is-hidden {
        display: none;
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
        background: rgba(17, 20, 27, 0.86);
        backdrop-filter: blur(6px);
        color: #FFFFFF;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.22rem 0.6rem;
        border-radius: var(--radius-sm);
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
    }

    .project-body {
        padding: 1.4rem 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .project-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-bottom: 0.85rem;
    }
    .project-title {
        font-family: var(--font-display);
        font-size: 1.24rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.55rem;
        line-height: 1.28;
    }
    .project-title a {
        color: inherit;
        text-decoration: none;
    }
    .project-title a:hover {
        color: var(--accent);
    }
    .project-desc {
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.65;
        flex: 1;
        margin-bottom: 1.35rem;
    }

    .project-links {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
    }
    .project-links .btn {
        flex: 1 1 auto;
        min-width: 110px;
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

    @media (max-width: 820px) {
        .projects-editorial-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 480px) {
        .project-body { padding: 1.15rem; }
        .project-links .btn { min-width: 0; }
    }
</style>
@endsection

@section('content')
@php
    $isVi = app()->getLocale() === 'vi';
    $allTechNames = [];
    foreach ($projects as $p) {
        foreach ($p->technologies as $t) {
            $allTechNames[$t->name] = ($allTechNames[$t->name] ?? 0) + 1;
        }
    }
    arsort($allTechNames);
    $filterTechs = array_slice(array_keys($allTechNames), 0, 8);
@endphp

<section class="page-header-band">
    <div class="container hero-animate">
        <div class="page-header-inner">
            <div>
                <div class="editorial-kicker">ARCHIVE // SOFTWARE &amp; SYSTEMS</div>
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
            @if(count($filterTechs))
            <div class="projects-filter-bar" role="tablist" aria-label="{{ __('Filter by stack') }}">
                <button type="button" class="proj-filter-btn active" data-proj-filter="all">{{ __('All Stacks') }}</button>
                @foreach($filterTechs as $tName)
                    <button type="button" class="proj-filter-btn" data-proj-filter="{{ strtolower($tName) }}">{{ $tName }}</button>
                @endforeach
            </div>
            @endif

            <div class="projects-editorial-grid" data-stagger>
                @foreach($projects as $idx => $project)
                    @php
                        $rawDesc = (string) ($project->clean_description ?? $project->description);
                        $cleanBody = preg_replace('/^[^|]+\|\s*\d{2}\/\d{4}\s*-\s*(?:\d{2}\/\d{4}|Present)\s*/u', '', $rawDesc);
                        $cleanBody = str_ireplace(['+Project Description:', 'Project Description:'], '', (string) $cleanBody);
                        $cleanBody = trim((string) $cleanBody);
                        $hostLabel = $project->demo_url
                            ? preg_replace('#^https?://#', '', rtrim($project->demo_url, '/'))
                            : ($project->github_url ? preg_replace('#^https?://#', '', rtrim($project->github_url, '/')) : 'architecture.spec');
                        $techSlugList = $project->technologies->pluck('name')->map(fn($n) => strtolower($n))->implode('|');
                    @endphp
                    <article class="project-card" data-proj-techs="{{ $techSlugList }}">
                        <a href="{{ route('projects.show', $project->id) }}" class="project-media-link" aria-label="{{ $project->title }}">
                            @if($project->featured)
                                <span class="project-featured-badge">{{ __('Featured') }}</span>
                            @endif

                            @if($project->image)
                                <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="specimen-plate" style="display:none;">
                                    <div class="specimen-topbar">
                                        <span class="specimen-dots"><span></span><span></span><span></span></span>
                                        <span class="specimen-host">{{ $hostLabel }}</span>
                                    </div>
                                    <div class="specimen-center">
                                        <div class="specimen-index">PRJ // {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                        <div class="specimen-title">{{ $project->title }}</div>
                                    </div>
                                    <div class="specimen-footer">
                                        @foreach($project->technologies->take(4) as $t)
                                            <span class="specimen-pill">{{ $t->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="specimen-plate">
                                    <div class="specimen-topbar">
                                        <span class="specimen-dots"><span></span><span></span><span></span></span>
                                        <span class="specimen-host">{{ $hostLabel }}</span>
                                    </div>
                                    <div class="specimen-center">
                                        <div class="specimen-index">PRJ // {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                        <div class="specimen-title">{{ $project->title }}</div>
                                    </div>
                                    <div class="specimen-footer">
                                        @foreach($project->technologies->take(4) as $t)
                                            <span class="specimen-pill">{{ $t->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </a>

                        <div class="project-body">
                            @if($project->technologies->count())
                            <div class="project-tags">
                                @foreach($project->technologies->take(5) as $tech)
                                    <span class="tag">{{ $tech->name }}</span>
                                @endforeach
                            </div>
                            @endif

                            <h2 class="project-title">
                                <a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a>
                            </h2>

                            <p class="project-desc">
                                {{ Str::limit($cleanBody !== '' ? $cleanBody : strip_tags($project->description), 165) }}
                            </p>

                            <div class="project-links">
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary btn-sm">
                                    <span>{{ __('View Case Study') }}</span>
                                </a>
                                @if($project->github_url)
                                <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                                    <i class="fab fa-github" aria-hidden="true"></i>
                                    <span>{{ __('Code') }}</span>
                                </a>
                                @endif
                                @if($project->demo_url)
                                <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                                    <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                    <span>{{ __('Demo') }}</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </article>
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
document.querySelectorAll('[data-proj-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.getAttribute('data-proj-filter');
        document.querySelectorAll('[data-proj-filter]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('[data-proj-techs]').forEach(card => {
            const techs = card.getAttribute('data-proj-techs').split('|');
            const match = target === 'all' || techs.includes(target);
            card.classList.toggle('is-hidden', !match);
        });
    });
});
</script>
@endsection
