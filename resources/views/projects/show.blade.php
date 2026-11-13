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
        padding: 2.5rem 0 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg-primary);
    }
    .project-hero .breadcrumb {
        color: var(--text-muted);
        font-size: 0.86rem;
        word-break: break-word;
    }
    .project-hero .breadcrumb a {
        color: var(--text-secondary);
        text-decoration: none;
        font-weight: 500;
    }
    .project-hero .breadcrumb a:hover {
        color: var(--accent);
        text-decoration: underline;
    }

    .project-detail {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 320px;
        gap: 2.75rem;
        align-items: start;
        width: 100%;
    }
    .project-detail > div:first-child {
        min-width: 0;
        max-width: 100%;
    }

    .project-image-full {
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 2rem;
        border: 1px solid var(--border);
        background: var(--bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }
    .project-image-full img {
        width: 100%;
        height: auto;
        max-height: 460px;
        object-fit: cover;
        display: block;
    }

    .project-title {
        font-size: clamp(1.75rem, 3.8vw, 2.45rem);
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.15rem;
        letter-spacing: -0.025em;
        line-height: 1.22;
        word-break: break-word;
    }
    .project-desc {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.78;
        max-width: 68ch;
        word-break: break-word;
        overflow-wrap: break-word;
        white-space: pre-line;
    }

    .project-sidebar {
        min-width: 0;
        width: 100%;
    }
    @media (min-width: 961px) {
        .project-sidebar {
            position: sticky;
            top: 88px;
        }
    }
    .project-sidebar .card {
        margin-bottom: 1.15rem;
        padding: 1.25rem;
    }
    .project-sidebar .card h4 {
        font-size: 0.86rem;
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
        padding: 0.6rem 0;
        border-bottom: 1px solid var(--border);
        font-size: 0.9rem;
    }
    .project-meta-item:last-child { border-bottom: none; padding-bottom: 0; }
    .project-meta-item i { color: var(--text-muted); width: 16px; text-align: center; flex-shrink: 0; }
    .project-meta-item a { color: var(--text-primary); text-decoration: none; font-weight: 500; word-break: break-word; }
    .project-meta-item a:hover { color: var(--accent); text-decoration: underline; }

    @media (max-width: 960px) {
        .project-hero { padding: 2rem 0 1rem; }
        .project-detail { grid-template-columns: 100%; gap: 2rem; }
        .project-image-full { margin-bottom: 1.5rem; }
    }
</style>
@endsection

@section('content')
<section class="project-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">{{ __('Home') }}</a> /
            <a href="{{ route('projects.index') }}">{{ __('Projects') }}</a> /
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
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/og-image.webp') }}';">
                    @else
                        <img src="{{ asset('images/og-image.webp') }}" alt="{{ $project->title }} Thumbnail" loading="lazy" decoding="async">
                    @endif
                </div>

                <h1 class="project-title">{{ $project->title }}</h1>
                <div class="project-desc">{!! nl2br(e($project->description)) !!}</div>
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

                <a href="{{ route('projects.index') }}" class="btn btn-outline" style="width:100%; justify-content:center">
                    <i class="fas fa-arrow-left"></i> {{ __('Back to Projects') }}
                </a>
            </aside>
        </div>
    </div>
</section>
@endsection
