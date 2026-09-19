@extends('layouts.app')

@section('title', __('Projects') . ' | ' . __('Hồ Thành Thiện'))
@section('description', 'Explore my portfolio of web development projects built with modern technologies.')

@section('styles')
<style>
    .page-hero {
        padding: 5rem 0 3rem;
        text-align: center;
        background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,0.15) 0%, transparent 70%);
    }
    .page-hero h1 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; margin-bottom: 0.75rem; letter-spacing: -0.02em; }
    .page-hero p { color: var(--text-secondary); font-size: 1.1rem; }

    .projects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr)); gap: 1.5rem; }

    .project-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;
        transition: all 0.3s ease;
    }
    .project-card:hover { border-color: rgba(99,102,241,0.4); transform: translateY(-6px); box-shadow: 0 25px 50px rgba(0,0,0,0.08); }

    .project-image {
        height: 200px; overflow: hidden;
        background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(6,182,212,0.2));
        display: flex; align-items: center; justify-content: center; position: relative;
    }
    .project-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
    .project-card:hover .project-image img { transform: scale(1.05); }
    .project-placeholder { font-size: 3rem; opacity: 0.3; }

    .project-featured-badge {
        position: absolute; top: 0.75rem; right: 0.75rem;
        background: var(--gradient); color: white;
        padding: 0.2rem 0.65rem; border-radius: 6px; font-size: 0.75rem; font-weight: 700;
    }

    .project-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .project-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.75rem; }
    .project-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; }
    .project-desc { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6; flex: 1; margin-bottom: 1.25rem; }
    .project-links { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: auto; }
    .project-links .btn { flex: 1 1 calc(33.333% - 0.5rem); min-width: 80px; justify-content: center; font-size: 0.85rem; padding: 0.5rem 0.75rem; }

    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-secondary); }
    .empty-state i { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }

    @media (max-width: 768px) {
        .page-hero { padding: 3.5rem 0 2rem; }
        .page-hero h1 { font-size: clamp(1.8rem, 6vw, 2.5rem); }
        .page-hero p { font-size: 1rem; }
    }

    @media (max-width: 640px) {
        .projects-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 480px) {
        .page-hero { padding: 2.75rem 0 1.5rem; }
        .project-image { height: 180px; }
        .project-body { padding: 1.1rem; }
        .project-links .btn { min-width: 70px; flex: 1; }
    }
</style>
@endsection

@section('content')
<section class="page-hero">
    <div class="container">
        <h1 class="reveal">{{ __('My') }} <span class="gradient-text">{{ __('Projects') }}</span></h1>
        <p class="reveal delay-1">{{ __("Things I've built and worked on") }}</p>
    </div>
</section>

<section class="section" style="padding-top:2rem">
    <div class="container">
        @if($projects->count())
        <div class="projects-grid">
            @foreach($projects as $project)
            <div class="project-card reveal delay-{{ ($loop->index % 3) + 1 }}">
                <div class="project-image">
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}">
                    @else
                        <img src="{{ asset('images/og-image.png') }}" alt="{{ $project->title }} - Thumbnail">
                    @endif
                    @if($project->featured)
                        <span class="project-featured-badge">⭐ {{ __('Featured') }}</span>
                    @endif
                </div>
                <div class="project-body">
                    <div class="project-tags">
                        @foreach($project->technologies->take(5) as $tech)
                            <span class="tag">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                    <div class="project-title">{{ $project->title }}</div>
                    <p class="project-desc">{{ Str::limit($project->description, 130) }}</p>
                    <div class="project-links">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> {{ __('Details') }}
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
        <div class="pagination-wrap reveal delay-2">
            @foreach($projects->links()->elements[0] as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $projects->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
        @endif

        @else
        <div class="empty-state reveal">
            <i class="fas fa-code"></i>
            <h3>{{ __('No projects yet') }}</h3>
            <p>{{ __('Check back soon!') }}</p>
        </div>
        @endif
    </div>
</section>
@endsection
