@extends('layouts.app')

@section('title', 'Blog | Alex Nguyen')
@section('description', 'Articles and thoughts on web development, technology and software engineering.')

@section('styles')
<style>
    .page-hero {
        padding: 5rem 0 3rem; text-align: center;
        background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,0.15) 0%, transparent 70%);
    }
    .page-hero h1 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; margin-bottom: 0.75rem; }
    .page-hero p { color: var(--text-secondary); font-size: 1.1rem; }

    .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }

    .blog-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden; display: flex; flex-direction: column;
        text-decoration: none; color: inherit; transition: all 0.3s ease;
    }
    .blog-card:hover { border-color: rgba(99,102,241,0.4); transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); }

    .blog-image {
        height: 200px; overflow: hidden;
        background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(6,182,212,0.2));
        display: flex; align-items: center; justify-content: center;
    }
    .blog-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
    .blog-card:hover .blog-image img { transform: scale(1.05); }
    .blog-image-placeholder { font-size: 3rem; opacity: 0.3; }

    .blog-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .blog-meta { display: flex; align-items: center; gap: 1rem; font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.75rem; }
    .blog-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.4; }
    .blog-excerpt { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6; flex: 1; }
    .blog-read-more {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--accent-light); font-size: 0.85rem; font-weight: 600;
        margin-top: 1rem; text-decoration: none;
    }

    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-secondary); }
    .empty-state i { font-size: 4rem; margin-bottom: 1rem; opacity: 0.3; }
</style>
@endsection

@section('content')
<section class="page-hero">
    <div class="container">
        <h1>My <span class="gradient-text">Blog</span></h1>
        <p>Thoughts, tutorials and learnings from the dev trenches</p>
    </div>
</section>

<section class="section" style="padding-top: 2rem">
    <div class="container">
        @if($posts->count())
        <div class="blog-grid">
            @foreach($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
                <div class="blog-image">
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                    @else
                        <span class="blog-image-placeholder"><i class="fas fa-pen-nib"></i></span>
                    @endif
                </div>
                <div class="blog-body">
                    <div class="blog-meta">
                        @if($post->published_at)
                        <span><i class="fas fa-calendar-alt"></i> {{ $post->published_at->format('M d, Y') }}</span>
                        @endif
                        <span><i class="fas fa-clock"></i> {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                    </div>
                    <div class="blog-title">{{ $post->title }}</div>
                    @if($post->excerpt)
                    <p class="blog-excerpt">{{ Str::limit($post->excerpt, 130) }}</p>
                    @endif
                    <span class="blog-read-more">Read more <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            @endforeach
        </div>

        @if($posts->hasPages())
        <div class="pagination-wrap">
            @if($posts->onFirstPage())
                <span class="page-link" style="opacity:0.4">« Prev</span>
            @else
                <a href="{{ $posts->previousPageUrl() }}" class="page-link">« Prev</a>
            @endif

            @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $posts->currentPage() == $page ? 'active' : '' }}">{{ $page }}</a>
            @endforeach

            @if($posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" class="page-link">Next »</a>
            @else
                <span class="page-link" style="opacity:0.4">Next »</span>
            @endif
        </div>
        @endif

        @else
        <div class="empty-state">
            <i class="fas fa-pen-nib"></i>
            <h3>No articles yet</h3>
            <p>Stay tuned for upcoming posts!</p>
        </div>
        @endif
    </div>
</section>
@endsection
