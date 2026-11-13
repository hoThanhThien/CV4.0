@extends('layouts.app')

@section('title', __('Blog') . ' | ' . __('Hồ Thành Thiện'))
@section('description', app()->getLocale() == 'vi' ? 'Tổng hợp các bài viết, hướng dẫn lập trình Laravel, PHP, JavaScript và kinh nghiệm phát triển phần mềm thực chiến của Hồ Thành Thiện.' : 'Articles, architectural insights, and practical web development tutorials on Laravel, PHP, and modern JavaScript by Ho Thanh Thien.')
@section('keywords', app()->getLocale() == 'vi' ? 'Blog Hồ Thành Thiện, Lập trình web, Kiến thức Laravel, PHP Tips, Clean Code, Web Development' : 'Ho Thanh Thien Blog, Web Development Tutorials, Laravel Insights, Software Engineering, Clean Code')

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

    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 310px), 1fr));
        gap: 1.5rem;
    }

    .blog-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .blog-card:hover {
        border-color: var(--border-strong);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .blog-image {
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .blog-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform var(--duration-base) var(--ease-out);
    }
    .blog-card:hover .blog-image img {
        transform: scale(1.025);
    }
    .blog-image-placeholder { font-size: 2.25rem; color: var(--text-muted); }

    .blog-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .blog-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 0.6rem;
        flex-wrap: wrap;
    }
    .blog-title {
        font-size: 1.08rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    .blog-excerpt {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        flex: 1;
    }
    .blog-read-more {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: var(--accent);
        font-size: 0.86rem;
        font-weight: 600;
        margin-top: 1rem;
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

    @media (max-width: 1024px) {
        .blog-grid { grid-template-columns: repeat(2, 1fr); gap: 1.25rem; }
    }
    @media (max-width: 768px) {
        .page-header-band { padding: 2.5rem 0 1.75rem; }
    }
    @media (max-width: 640px) {
        .blog-grid { grid-template-columns: 1fr; gap: 1.15rem; }
    }
</style>
@endsection

@section('content')
<section class="page-header-band">
    <div class="container hero-animate">
        <h1>{!! __('heading_my_blog') !!}</h1>
        <p>{{ __('Thoughts, tutorials and learnings from the dev trenches') }}</p>
    </div>
</section>

<section class="section" style="padding-top: 2.5rem">
    <div class="container">
        @if($posts->count())
        <div class="blog-grid" data-stagger>
            @foreach($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
                <div class="blog-image">
                    @if($post->thumbnail_url)
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" onerror="this.onerror=null; this.src='{{ asset('images/og-image.webp') }}';">
                    @else
                        <span class="blog-image-placeholder"><i class="fas fa-pen-nib"></i></span>
                    @endif
                </div>
                <div class="blog-body">
                    <div class="blog-meta">
                        @if($post->published_at)
                        <span>{{ $post->published_at->format('M d, Y') }}</span>
                        @endif
                        <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} {{ __('min read') }}</span>
                    </div>
                    <div class="blog-title">{{ $post->title }}</div>
                    @if($post->excerpt)
                    <p class="blog-excerpt">{{ Str::limit($post->excerpt, 130) }}</p>
                    @endif
                    <span class="blog-read-more">{{ __('Read more') }}</span>
                </div>
            </a>
            @endforeach
        </div>

        @if($posts->hasPages())
        <nav class="pagination-wrap" aria-label="Pagination">
            @if($posts->onFirstPage())
                <span class="page-link" aria-disabled="true" style="opacity:0.45; pointer-events:none;">« {{ __('Prev') }}</span>
            @else
                <a href="{{ $posts->previousPageUrl() }}" class="page-link" rel="prev">« {{ __('Prev') }}</a>
            @endif

            @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $posts->currentPage() == $page ? 'active' : '' }}" {{ $posts->currentPage() == $page ? 'aria-current="page"' : '' }}>{{ $page }}</a>
            @endforeach

            @if($posts->hasMorePages())
                <a href="{{ $posts->nextPageUrl() }}" class="page-link" rel="next">{{ __('Next') }} »</a>
            @else
                <span class="page-link" aria-disabled="true" style="opacity:0.45; pointer-events:none;">{{ __('Next') }} »</span>
            @endif
        </nav>
        @endif

        @else
        <div class="empty-state">
            <i class="fas fa-pen-nib"></i>
            <h3>{{ __('No articles yet') }}</h3>
            <p>{{ __('Stay tuned for upcoming posts!') }}</p>
        </div>
        @endif
    </div>
</section>
@endsection
