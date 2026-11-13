@extends('layouts.app')

@section('title', $project->title . ' | ' . __('Hồ Thành Thiện'))
@section('description', Str::limit(strip_tags($project->clean_description ?? $project->description), 155))
@section('og_type', 'article')
@section('og_image', $project->image ? asset('storage/' . $project->image) : asset('images/og-image.webp'))

@section('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "{{ e($project->title) }}",
  "description": "{{ e(Str::limit(strip_tags($project->clean_description ?? $project->description), 200)) }}",
  "applicationCategory": "WebApplication",
  "operatingSystem": "All",
  "author": {
    "@type": "Person",
    "name": "Hồ Thành Thiện",
    "url": "{{ url('/') }}"
  }
  @if($project->image)
  ,"image": "{{ asset('storage/' . $project->image) }}"
  @endif
  @if($project->demo_url)
  ,"url": "{{ $project->demo_url }}"
  @endif
}
</script>
@endsection

@section('styles')
<style>
    .project-hero {
        padding: 2rem 0 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg-primary);
    }
    .project-hero .breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.45rem;
        font-family: var(--font-mono);
        font-size: 0.78rem;
        color: var(--text-muted);
    }
    .project-hero .breadcrumb a {
        color: var(--text-secondary);
        text-decoration: none;
    }
    .project-hero .breadcrumb a:hover {
        color: var(--accent);
        text-decoration: underline;
    }

    .project-detail {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 330px;
        gap: clamp(1.75rem, 4vw, 3.25rem);
        align-items: start;
        width: 100%;
    }
    .project-detail > div:first-child {
        min-width: 0;
        max-width: 100%;
    }

    .project-image-full {
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid var(--border-strong);
        background: var(--surface-ink);
        aspect-ratio: 16 / 9;
        width: 100%;
        box-shadow: var(--shadow-md);
    }
    .project-image-full img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .project-title {
        font-family: var(--font-display);
        font-size: clamp(1.85rem, 3.8vw, 2.75rem);
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 1.15rem;
        letter-spacing: -0.03em;
        line-height: 1.15;
        word-break: break-word;
    }
    .project-desc {
        color: var(--text-secondary);
        font-size: 1.02rem;
        line-height: 1.8;
        max-width: 68ch;
        word-break: break-word;
        overflow-wrap: break-word;
        white-space: pre-line;
    }

    .project-sidebar {
        min-width: 0;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    @media (min-width: 961px) {
        .project-sidebar {
            position: sticky;
            top: 90px;
        }
    }
    .project-sidebar .card {
        padding: 1.3rem;
    }
    .project-sidebar .card h4 {
        font-family: var(--font-mono);
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .tech-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .project-meta-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.65rem 0;
        border-bottom: 1px solid var(--border);
        font-size: 0.92rem;
    }
    .project-meta-item:last-child { border-bottom: none; padding-bottom: 0; }
    .project-meta-item i { color: var(--accent); width: 16px; text-align: center; flex-shrink: 0; }
    .project-meta-item a { color: var(--text-primary); text-decoration: none; font-weight: 600; word-break: break-word; }
    .project-meta-item a:hover { color: var(--accent); text-decoration: underline; }

    @media (max-width: 960px) {
        .project-detail { grid-template-columns: 100%; gap: 2rem; }
    }
</style>
@endsection

@section('content')
@php
    $hostLabel = $project->demo_url
        ? preg_replace('#^https?://#', '', rtrim($project->demo_url, '/'))
        : ($project->github_url ? preg_replace('#^https?://#', '', rtrim($project->github_url, '/')) : 'architecture.spec');
@endphp

<section class="project-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">{{ __('Home') }}</a>
            <span>/</span>
            <a href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
            <span>/</span>
            <span>{{ $project->title }}</span>
        </div>
    </div>
</section>

<section class="section" style="padding-top:2.25rem">
    <div class="container">
        <div class="project-detail hero-animate">
            <!-- Main Content -->
            <div>
                <div class="project-image-full">
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="specimen-plate" style="display:none;">
                            <div class="specimen-topbar">
                                <span class="specimen-dots"><span></span><span></span><span></span></span>
                                <span class="specimen-host">{{ $hostLabel }}</span>
                            </div>
                            <div class="specimen-center">
                                <div class="specimen-index">CASE STUDY // #{{ $project->id }}</div>
                                <div class="specimen-title">{{ $project->title }}</div>
                            </div>
                            <div class="specimen-footer">
                                @foreach($project->technologies->take(6) as $t)
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
                                <div class="specimen-index">CASE STUDY // #{{ $project->id }}</div>
                                <div class="specimen-title">{{ $project->title }}</div>
                            </div>
                            <div class="specimen-footer">
                                @foreach($project->technologies->take(6) as $t)
                                    <span class="specimen-pill">{{ $t->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <h1 class="project-title">{{ $project->title }}</h1>
                <div class="project-desc">
                    <p style="font-size:1.06rem; line-height:1.75; color:var(--text-primary); margin-bottom:1.5rem;">
                        {{ $project->summary }}
                    </p>

                    @if(count($project->highlights) > 1)
                        <h3 style="font-size:1.15rem; font-weight:700; color:var(--text-primary); margin:1.75rem 0 0.85rem;">
                            {{ (app()->getLocale() === 'vi') ? 'Điểm nhấn kỹ thuật & Triển khai' : 'Key Engineering Highlights' }}
                        </h3>
                        <ul style="margin-left:1.25rem; display:flex; flex-direction:column; gap:0.65rem; color:var(--text-secondary); line-height:1.68;">
                            @foreach(array_slice($project->highlights, 1) as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="project-sidebar">
                @if($project->technologies->count())
                <div class="card">
                    <h4><i class="fas fa-microchip"></i> {{ __('Technologies') }}</h4>
                    <div class="tech-tags">
                        @foreach($project->technologies as $tech)
                            <span class="tag">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="card">
                    <h4><i class="fas fa-link"></i> {{ __('Links') }}</h4>
                    @if($project->github_url)
                    <div class="project-meta-item">
                        <i class="fab fa-github"></i>
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener">{{ __('View on GitHub') }}</a>
                    </div>
                    @endif
                    @if($project->demo_url)
                    <div class="project-meta-item">
                        <i class="fas fa-external-link-alt"></i>
                        <a href="{{ $project->demo_url }}" target="_blank" rel="noopener">{{ __('Live Demo') }}</a>
                    </div>
                    @endif
                    @if(!$project->github_url && !$project->demo_url)
                    <p style="color: var(--text-muted); font-size: 0.88rem;">{{ __('No links available') }}</p>
                    @endif
                </div>

                @if($project->featured)
                <div class="card" style="background: var(--bg-secondary);">
                    <div style="display:flex; align-items:center; gap:0.5rem; color: var(--text-primary); font-weight:600; font-size: 0.9rem;">
                        <i class="fas fa-bookmark" style="color: var(--accent);"></i> {{ __('Featured Project') }}
                    </div>
                </div>
                @endif

                <a href="{{ route('contact') }}" class="btn btn-primary" style="width:100%;">
                    <i class="fas fa-paper-plane"></i> {{ __('Contact Me') }}
                </a>

                <a href="{{ route('projects.index') }}" class="btn btn-outline" style="width:100%;">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Projects') }}
                </a>
            </aside>
        </div>
    </div>
</section>
@endsection
