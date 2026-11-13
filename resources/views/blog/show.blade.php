@extends('layouts.app')

@section('title', $post->title . ' | ' . __('Hồ Thành Thiện'))
@section('description', $post->excerpt ? Str::limit(strip_tags($post->excerpt), 155) : Str::limit(strip_tags($post->content), 155))
@section('og_type', 'article')
@section('og_image', $post->og_image_url)

@section('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "headline": "{{ e($post->title) }}",
  "description": "{{ e($post->excerpt ? Str::limit(strip_tags($post->excerpt), 180) : Str::limit(strip_tags($post->content), 180)) }}",
  "author": {
    "@type": "Person",
    "name": "Hồ Thành Thiện",
    "url": "{{ url('/') }}"
  },
  "publisher": {
    "@type": "Person",
    "name": "Hồ Thành Thiện",
    "url": "{{ url('/') }}"
  },
  "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url()->current() }}"
  },
  "image": "{{ $post->og_image_url }}"
}
</script>
@endsection

@section('styles')
<style>
    .post-hero {
        padding: 2.5rem 0 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg-primary);
    }
    .breadcrumb {
        color: var(--text-muted);
        font-size: 0.86rem;
        word-break: break-word;
    }
    .breadcrumb a {
        color: var(--text-secondary);
        text-decoration: none;
        font-weight: 500;
    }
    .breadcrumb a:hover {
        color: var(--accent);
        text-decoration: underline;
    }

    .post-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: 3rem;
        align-items: start;
        width: 100%;
    }
    .post-layout > article {
        min-width: 0;
        max-width: 100%;
    }

    .post-title {
        font-size: clamp(1.75rem, 3.8vw, 2.5rem);
        font-weight: 700;
        line-height: 1.22;
        margin-bottom: 1rem;
        letter-spacing: -0.025em;
        color: var(--text-primary);
        word-break: break-word;
        max-width: 24ch;
    }
    .post-meta {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        color: var(--text-muted);
        font-size: 0.88rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    .post-meta i { color: var(--text-muted); margin-right: 0.25rem; }

    .post-image {
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 2.25rem;
        border: 1px solid var(--border);
        width: 100%;
        background: var(--bg-secondary);
    }
    .post-image img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
        aspect-ratio: 16 / 9;
    }

    .post-content {
        color: var(--text-secondary);
        font-size: 1.02rem;
        line-height: 1.8;
        max-width: 68ch;
        word-break: break-word;
        overflow-wrap: break-word;
    }
    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        border: 1px solid var(--border);
        margin: 1.5rem 0;
    }
    .post-content h2, .post-content h3 {
        color: var(--text-primary);
        margin: 2rem 0 0.75rem;
        font-weight: 700;
        letter-spacing: -0.015em;
        line-height: 1.3;
    }
    .post-content h2 { font-size: clamp(1.3rem, 3vw, 1.55rem); }
    .post-content h3 { font-size: clamp(1.1rem, 2.5vw, 1.25rem); }
    .post-content p { margin-bottom: 1.25rem; }
    .post-content a { color: var(--accent); text-decoration: underline; text-underline-offset: 2px; word-break: break-word; }
    .post-content ul, .post-content ol { margin: 1rem 0 1.25rem 1.5rem; }
    .post-content li { margin-bottom: 0.45rem; }
    .post-content code {
        background: var(--bg-secondary);
        color: var(--text-primary);
        border: 1px solid var(--border);
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.85em;
        word-break: break-word;
    }
    .post-content pre {
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.15rem 1.25rem;
        margin: 1.5rem 0;
        overflow-x: auto;
        max-width: 100%;
    }
    .post-content pre code {
        background: none;
        border: none;
        padding: 0;
        color: var(--text-primary);
        word-break: normal;
        font-size: 0.86rem;
        line-height: 1.6;
    }
    .post-content blockquote {
        border-left: 3px solid var(--accent);
        padding: 0.6rem 1.25rem;
        margin: 1.5rem 0;
        background: var(--bg-card);
        border-top: 1px solid var(--border);
        border-right: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        border-radius: 0 8px 8px 0;
        color: var(--text-secondary);
    }
    .post-content table {
        display: block;
        width: 100%;
        overflow-x: auto;
        border-collapse: collapse;
        margin: 1.5rem 0;
    }

    .faq-section {
        margin-top: 2.5rem;
        padding-top: 1.75rem;
        border-top: 1px solid var(--border);
    }
    .faq-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1rem 1.15rem;
    }

    .post-sidebar {
        min-width: 0;
        width: 100%;
    }
    .post-sidebar .card {
        margin-bottom: 1rem;
        padding: 1.25rem;
    }
    .post-sidebar .card h4 {
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    @media (max-width: 960px) {
        .post-hero { padding: 2rem 0 1rem; }
        .post-layout { grid-template-columns: 100%; gap: 2rem; }
        .post-image { margin-bottom: 1.75rem; }
    }

    @media (max-width: 480px) {
        .post-content pre { padding: 0.85rem; font-size: 0.82rem; }
    }
</style>
@endsection

@section('content')
<div class="post-hero">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">{{ __('Home') }}</a> /
            <a href="{{ route('blog.index') }}">{{ __('Blog') }}</a> /
            <span>{{ Str::limit($post->title, 48) }}</span>
        </div>
    </div>
</div>

<section class="section" style="padding-top:2.25rem">
    <div class="container">
        <div class="post-layout">
            <!-- Main -->
            <article>
                <h1 class="post-title">{{ $post->title }}</h1>
                <div class="post-meta">
                    @if($post->published_at)
                    <span><i class="fas fa-calendar-alt"></i> {{ $post->published_at->format('F d, Y') }}</span>
                    @endif
                    <span><i class="fas fa-clock"></i> {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} {{ __('min read') }}</span>
                </div>

                @if($post->thumbnail_url)
                <div class="post-image">
                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/og-image.webp') }}';">
                </div>
                @endif

                <div class="post-content">
                    @if($post->ai_generated || str_contains($post->content, '<p>') || str_contains($post->content, '<h2>'))
                        {!! $post->content !!}
                    @else
                        {!! nl2br(e($post->content)) !!}
                    @endif

                    @if(!empty($post->faq) && is_array($post->faq))
                        <div class="faq-section">
                            <h3 style="margin-top:0; margin-bottom:1rem; color:var(--text-primary)">{{ __('FAQ') }}</h3>
                            <div style="display:flex; flex-direction:column; gap:0.75rem">
                                @foreach($post->faq as $faq)
                                    <div class="faq-item">
                                        <div style="font-weight:600; color:var(--text-primary); margin-bottom:0.35rem">{{ $faq['question'] ?? '' }}</div>
                                        <div style="color:var(--text-secondary); font-size:0.95rem; line-height:1.6">{{ $faq['answer'] ?? '' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($post->sources) && is_array($post->sources))
                        <div style="margin-top:2rem; padding-top:1.25rem; border-top:1px solid var(--border); font-size:0.92rem">
                            <h4 style="color:var(--text-primary); font-weight:600; margin-bottom:0.5rem">{{ __('Sources & References') }}</h4>
                            <ul style="margin-left:1.5rem">
                                @foreach($post->sources as $source)
                                    <li>
                                        <a href="{{ $source['url'] ?? '#' }}" target="_blank" rel="noopener">{{ $source['title'] ?? ($source['url'] ?? '') }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="post-sidebar">
                @if($post->excerpt)
                <div class="card">
                    <h4><i class="fas fa-align-left"></i> {{ __('Summary') }}</h4>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6">{{ $post->excerpt }}</p>
                </div>
                @endif

                <div class="card">
                    <h4><i class="fas fa-info-circle"></i> {{ __('Details') }}</h4>
                    @if($post->published_at)
                    <div style="color: var(--text-secondary); font-size: 0.88rem; margin-bottom: 0.5rem;">
                        <i class="fas fa-calendar" style="color: var(--text-muted); width:16px"></i>
                        {{ __('Published') }} {{ $post->published_at->format('M d, Y') }}
                    </div>
                    @endif
                    <div style="color: var(--text-secondary); font-size: 0.88rem;">
                        <i class="fas fa-clock" style="color: var(--text-muted); width:16px"></i>
                        {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} {{ __('min read') }}
                    </div>
                </div>

                <a href="{{ route('blog.index') }}" class="btn btn-outline" style="width:100%; justify-content:center">
                    <i class="fas fa-arrow-left"></i> {{ __('All Articles') }}
                </a>
            </aside>
        </div>
    </div>
</section>
@endsection
