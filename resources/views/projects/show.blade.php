@extends('layouts.app')

@section('title', $project->title . ' | Projects')

@section('styles')
<style>
    .project-hero {
        padding: 5rem 0 3rem;
        background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,0.15) 0%, transparent 70%);
    }
    .project-hero .breadcrumb {
        color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1.5rem;
    }
    .project-hero .breadcrumb a { color: var(--accent-light); text-decoration: none; }
    .project-hero .breadcrumb a:hover { text-decoration: underline; }

    .project-detail {
        display: grid; grid-template-columns: 1fr 350px; gap: 3rem; align-items: start;
    }

    .project-image-full {
        border-radius: 16px; overflow: hidden; margin-bottom: 2rem;
        border: 1px solid var(--border);
        background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(6,182,212,0.2));
        min-height: 300px; display: flex; align-items: center; justify-content: center;
    }
    .project-image-full img { width: 100%; display: block; }
    .project-image-full .placeholder { font-size: 5rem; opacity: 0.3; }

    .project-title { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 900; margin-bottom: 1rem; }
    .project-desc { color: var(--text-secondary); font-size: 1rem; line-height: 1.8; }

    .project-sidebar .card { margin-bottom: 1.25rem; }
    .project-sidebar .card h4 { font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; }

    .tech-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; }

    .project-meta-item {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.5rem 0; border-bottom: 1px solid var(--border); font-size: 0.9rem;
    }
    .project-meta-item:last-child { border-bottom: none; }
    .project-meta-item i { color: var(--accent-light); width: 16px; text-align: center; }

    @media (max-width: 768px) {
        .project-detail { grid-template-columns: 1fr; }
        .project-sidebar { order: -1; }
    }
</style>
@endsection

@section('content')
<section class="project-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> /
            <a href="{{ route('projects.index') }}">Projects</a> /
            {{ $project->title }}
        </div>
    </div>
</section>

<section class="section" style="padding-top:1rem">
    <div class="container">
        <div class="project-detail">
            <!-- Main Content -->
            <div>
                <div class="project-image-full">
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}">
                    @else
                        <span class="placeholder"><i class="fas fa-code"></i></span>
                    @endif
                </div>

                <h1 class="project-title">{{ $project->title }}</h1>
                <p class="project-desc">{{ $project->description }}</p>
            </div>

            <!-- Sidebar -->
            <aside class="project-sidebar">
                @if($project->technologies->count())
                <div class="card">
                    <h4><i class="fas fa-microchip"></i> Technologies</h4>
                    <div class="tech-tags">
                        @foreach($project->technologies as $tech)
                            <span class="tag">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="card">
                    <h4><i class="fas fa-link"></i> Links</h4>
                    @if($project->github_url)
                    <div class="project-meta-item">
                        <i class="fab fa-github"></i>
                        <a href="{{ $project->github_url }}" target="_blank" style="color: var(--text-primary); text-decoration: none;">View on GitHub</a>
                    </div>
                    @endif
                    @if($project->demo_url)
                    <div class="project-meta-item">
                        <i class="fas fa-external-link-alt"></i>
                        <a href="{{ $project->demo_url }}" target="_blank" style="color: var(--text-primary); text-decoration: none;">Live Demo</a>
                    </div>
                    @endif
                    @if(!$project->github_url && !$project->demo_url)
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">No links available</p>
                    @endif
                </div>

                @if($project->featured)
                <div class="card" style="background: rgba(124,58,237,0.1); border-color: rgba(124,58,237,0.3)">
                    <div style="display:flex; align-items:center; gap:0.5rem; color: var(--accent-light); font-weight:700;">
                        <i class="fas fa-star"></i> Featured Project
                    </div>
                </div>
                @endif

                <a href="{{ route('projects.index') }}" class="btn btn-outline" style="width:100%; justify-content:center">
                    <i class="fas fa-arrow-left"></i> Back to Projects
                </a>
            </aside>
        </div>
    </div>
</section>
@endsection
