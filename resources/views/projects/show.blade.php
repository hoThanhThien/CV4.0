@extends('layouts.app')

@section('title', $project->title . ' | ' . __('Hồ Thành Thiện'))
@section('description', Str::limit(strip_tags($project->description), 155))
@section('og_type', 'article')
@section('og_image', $project->image ? asset('storage/' . $project->image) : asset('images/og-image.webp'))

@section('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "{{ e($project->title) }}",
  "description": "{{ e(Str::limit(strip_tags($project->description), 200)) }}",
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
        padding: 5rem 0 3rem;
        background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,0.15) 0%, transparent 70%);
    }
    .project-hero .breadcrumb {
        color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1.5rem; word-break: break-word;
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
        min-height: 280px; display: flex; align-items: center; justify-content: center;
    }
    .project-image-full img { width: 100%; height: auto; max-height: 480px; object-fit: cover; display: block; }
    .project-image-full .placeholder { font-size: 5rem; opacity: 0.3; }

    .project-title { font-size: clamp(1.8rem, 4.5vw, 2.8rem); font-weight: 900; margin-bottom: 1rem; letter-spacing: -0.02em; word-break: break-word; }
    .project-desc { color: var(--text-secondary); font-size: 1rem; line-height: 1.8; word-break: break-word; }

    .project-sidebar .card { margin-bottom: 1.25rem; }
    .project-sidebar .card h4 { font-size: 0.85rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; }

    .tech-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; }

    .project-meta-item {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.6rem 0; border-bottom: 1px solid var(--border); font-size: 0.9rem; word-break: break-word;
    }
    .project-meta-item:last-child { border-bottom: none; }
    .project-meta-item i { color: var(--accent-light); width: 16px; text-align: center; flex-shrink: 0; }
    .project-meta-item a { color: var(--text-primary); text-decoration: none; word-break: break-all; }
    .project-meta-item a:hover { color: var(--accent); }

    @media (max-width: 768px) {
        .project-hero { padding: 3rem 0 1.5rem; }
        .project-detail { grid-template-columns: 1fr; gap: 2rem; }
        .project-image-full { min-height: auto; margin-bottom: 1.25rem; }
    }

    @media (max-width: 480px) {
        .project-hero { padding: 2.25rem 0 1.25rem; }
        .project-title { font-size: clamp(1.5rem, 6vw, 2rem); }
        .project-meta-item { font-size: 0.85rem; }
    }
</style>
@endsection

@section('content')
<section class="project-hero">
    <div class="container">
        <div class="breadcrumb reveal-fade">
            <a href="{{ route('home') }}">{{ __('Home') }}</a> /
            <a href="{{ route('projects.index') }}">{{ __('Projects') }}</a> /
            <span>{{ $project->title }}</span>
        </div>
    </div>
</section>

<section class="section" style="padding-top:1rem">
    <div class="container">
        <div class="project-detail">
            <!-- Main Content -->
            <div class="reveal">
                <div class="project-image-full">
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async">
                    @else
                        <div class="project-image-placeholder" style="min-height: 320px;">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                    @endif
                </div>

                <h1 class="project-title">{{ $project->title }}</h1>
                <p class="project-desc">{{ $project->description }}</p>
            </div>

            <!-- Sidebar -->
            <aside class="project-sidebar reveal delay-1">
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
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">{{ __('No links available') }}</p>
                    @endif
                </div>

                @if($project->featured)
                <div class="card" style="background: rgba(124,58,237,0.1); border-color: rgba(124,58,237,0.3)">
                    <div style="display:flex; align-items:center; gap:0.5rem; color: var(--accent-light); font-weight:700;">
                        <i class="fas fa-star"></i> {{ __('Featured Project') }}
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
