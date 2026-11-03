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
        padding: 5rem 0 2rem;
        background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,0.15) 0%, transparent 70%);
    }
    .breadcrumb { color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 2rem; word-break: break-word; }
    .breadcrumb a { color: var(--accent-light); text-decoration: none; }
    .breadcrumb a:hover { text-decoration: underline; }

    .post-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: 2.5rem;
        align-items: start;
        width: 100%;
        max-width: 100%;
    }
    .post-layout > article {
        min-width: 0;
        max-width: 100%;
        overflow: hidden;
    }

    .post-title {
        font-size: clamp(1.75rem, 4vw, 2.75rem);
        font-weight: 900;
        line-height: 1.25;
        margin-bottom: 1.25rem;
        letter-spacing: -0.02em;
        word-break: break-word;
        overflow-wrap: break-word;
    }
    .post-meta { display: flex; align-items: center; gap: 1.5rem; color: var(--text-secondary); font-size: 0.88rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .post-meta i { color: var(--accent-light); }

    .post-image {
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 2.5rem;
        border: 1px solid var(--border);
        width: 100%;
        max-width: 100%;
        background: var(--bg-secondary);
        box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.08);
    }
    .post-image img {
        width: 100%;
        max-width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
        aspect-ratio: 16 / 9;
    }

    .post-content {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.85;
        word-break: break-word;
        overflow-wrap: break-word;
        min-width: 0;
        max-width: 100%;
    }
    .post-content img { max-width: 100%; height: auto; border-radius: 12px; margin: 1.5rem 0; }
    .post-content h2, .post-content h3 { color: var(--text-primary); margin: 2rem 0 0.75rem; font-weight: 700; word-break: break-word; }
    .post-content h2 { font-size: clamp(1.3rem, 3.5vw, 1.5rem); }
    .post-content h3 { font-size: clamp(1.1rem, 3vw, 1.2rem); }
    .post-content p { margin-bottom: 1.25rem; }
    .post-content a { color: var(--accent-light); word-break: break-all; }
    .post-content ul, .post-content ol { margin: 1rem 0 1.25rem 1.5rem; }
    .post-content li { margin-bottom: 0.4rem; }
    .post-content code {
        background: rgba(124,58,237,0.15); color: var(--accent-light);
        padding: 0.2rem 0.5rem; border-radius: 5px; font-family: 'JetBrains Mono', monospace; font-size: 0.85em;
        word-break: break-all;
    }
    .post-content pre {
        background: #f1f5f9; border: 1px solid var(--border);
        border-radius: 12px; padding: 1.25rem; margin: 1.5rem 0; overflow-x: auto;
        max-width: 100%; -webkit-overflow-scrolling: touch;
    }
    .post-content pre code { background: none; padding: 0; color: var(--text-primary); word-break: normal; }
    .post-content blockquote {
        border-left: 3px solid var(--accent); padding: 0.5rem 1.25rem; margin: 1.5rem 0;
        background: rgba(124,58,237,0.05); border-radius: 0 8px 8px 0;
        color: var(--text-secondary); font-style: italic;
    }
    .post-content table {
        display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;
        border-collapse: collapse; margin: 1.5rem 0;
    }

    .post-sidebar {
        min-width: 0;
        width: 100%;
    }
    .post-sidebar .card { margin-bottom: 1rem; }
    .post-sidebar .card h4 { font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 0.75rem; }

    @media (max-width: 960px) {
        .post-hero { padding: 3rem 0 1.5rem; }
        .post-layout { grid-template-columns: 100%; gap: 2rem; }
        .post-title { font-size: clamp(1.5rem, 5vw, 2.1rem); }
        .post-meta { gap: 1rem; margin-bottom: 1.5rem; font-size: 0.82rem; }
        .post-image { margin-bottom: 1.75rem; border-radius: 12px; }
    }

    @media (max-width: 480px) {
        .post-hero { padding: 2.25rem 0 1.25rem; }
        .post-content pre { padding: 0.85rem; font-size: 0.8rem; }
        .breadcrumb { margin-bottom: 1.25rem; font-size: 0.8rem; }
    }
</style>
@endsection

@section('content')
<div class="post-hero">
    <div class="container">
        <div class="breadcrumb reveal-fade">
            <a href="{{ route('home') }}">{{ __('Home') }}</a> /
            <a href="{{ route('blog.index') }}">{{ __('Blog') }}</a> /
            <span>{{ Str::limit($post->title, 40) }}</span>
        </div>
    </div>
</div>

<section class="section" style="padding-top:1rem">
    <div class="container">
        <div class="post-layout">
            <!-- Main -->
            <article class="reveal">
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
                        <div style="margin-top:2.5rem; padding-top:1.5rem; border-top:1px solid var(--border)">
                            <h3 style="font-size:1.3rem; margin-bottom:1rem; color:var(--text-primary)"><i class="fas fa-question-circle" style="color:var(--accent-light)"></i> {{ __('FAQ') }}</h3>
                            <div style="display:flex; flex-direction:column; gap:0.75rem">
                                @foreach($post->faq as $faq)
                                    <div style="background:rgba(255,255,255,0.03); border:1px solid var(--border); border-radius:10px; padding:1rem">
                                        <div style="font-weight:700; color:var(--text-primary); margin-bottom:0.35rem">{{ $faq['question'] ?? '' }}</div>
                                        <div style="color:var(--text-secondary); font-size:0.95rem; line-height:1.6">{{ $faq['answer'] ?? '' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($post->sources) && is_array($post->sources))
                        <div style="margin-top:2rem; padding-top:1rem; border-top:1px solid var(--border); font-size:0.9rem">
                            <h4 style="color:var(--text-secondary); margin-bottom:0.5rem"><i class="fas fa-link"></i> {{ __('Sources & References') }}:</h4>
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
            <aside class="post-sidebar reveal delay-1">
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
                        <i class="fas fa-calendar" style="color: var(--accent-light); width:16px"></i>
                        {{ __('Published') }} {{ $post->published_at->format('M d, Y') }}
                    </div>
                    @endif
                    <div style="color: var(--text-secondary); font-size: 0.88rem;">
                        <i class="fas fa-clock" style="color: var(--accent-light); width:16px"></i>
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
