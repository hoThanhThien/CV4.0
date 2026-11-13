@extends('layouts.app')

@section('title', __('Blog') . ' | ' . __('Hồ Thành Thiện'))
@section('description', app()->getLocale() == 'vi' ? 'Tổng hợp các bài viết, hướng dẫn lập trình Laravel, PHP, JavaScript và kinh nghiệm phát triển phần mềm thực chiến của Hồ Thành Thiện.' : 'Articles, architectural insights, and practical web development tutorials on Laravel, PHP, and modern JavaScript by Ho Thanh Thien.')
@section('keywords', app()->getLocale() == 'vi' ? 'Blog Hồ Thành Thiện, Lập trình web, Kiến thức Laravel, PHP Tips, Clean Code, Web Development' : 'Ho Thanh Thien Blog, Web Development Tutorials, Laravel Insights, Software Engineering, Clean Code')

@section('styles')
<style>
    .blog-lead-banner {
        display: grid;
        grid-template-columns: minmax(0, 1.12fr) minmax(0, 0.88fr);
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        margin-bottom: 2rem;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out);
    }
    .blog-lead-banner:hover {
        border-color: var(--text-primary);
        box-shadow: var(--shadow-md);
    }
    .blog-lead-visual {
        aspect-ratio: 16 / 9;
        background: var(--surface-ink);
        overflow: hidden;
    }
    .blog-lead-visual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .blog-typo-plate {
        width: 100%;
        height: 100%;
        min-height: 220px;
        padding: 1.75rem;
        background: var(--surface-ink);
        background-image: radial-gradient(circle at 85% 20%, rgba(29, 78, 216, 0.28), transparent 55%);
        color: #FFFFFF;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .blog-typo-kicker {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: #93C5FD;
    }
    .blog-typo-heading {
        font-family: var(--font-display);
        font-size: clamp(1.2rem, 2.3vw, 1.65rem);
        font-weight: 700;
        line-height: 1.25;
        color: #FFFFFF;
    }

    .blog-lead-info {
        padding: clamp(1.4rem, 3vw, 2.15rem);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .blog-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.4rem;
    }

    .blog-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .blog-card:hover {
        border-color: var(--text-primary);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .blog-card:hover .blog-title {
        color: var(--accent);
    }

    .blog-image {
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: var(--surface-ink);
        border-bottom: 1px solid var(--border);
    }
    .blog-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .blog-body {
        padding: 1.3rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .blog-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.7rem;
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: var(--text-muted);
        margin-bottom: 0.6rem;
    }
    .blog-title {
        font-family: var(--font-display);
        font-size: 1.14rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.55rem;
        line-height: 1.35;
        transition: color var(--duration-fast) ease;
    }
    .blog-excerpt {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.62;
        flex: 1;
    }
    .blog-read-more {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--accent);
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 1rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
    }

    @media (max-width: 1024px) {
        .blog-lead-banner { grid-template-columns: 1fr; }
        .blog-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .blog-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
@php $isVi = app()->getLocale() === 'vi'; @endphp

<section class="page-header-band">
    <div class="container hero-animate">
        <div class="page-header-inner">
            <div>
                <div class="section-kicker" style="color:var(--accent-sky);">PUBLICATIONS // {{ __('Engineering Journal') }}</div>
                <h1>{!! __('heading_my_blog') !!}</h1>
                <p>{{ __('Thoughts, tutorials and learnings from the dev trenches') }}</p>
            </div>
            <div class="mono-label">{{ $posts->total() }} {{ $isVi ? 'BÀI VIẾT' : 'ARTICLES' }}</div>
        </div>
    </div>
</section>

<section class="section" style="padding-top: 2.25rem">
    <div class="container">
        @if($posts->count())
            @php
                $showLead = $posts->currentPage() === 1 && $posts->count() > 1;
                $lead = $showLead ? $posts->first() : null;
                $gridPosts = $showLead ? $posts->skip(1) : $posts;
            @endphp

            @if($lead)
            <a href="{{ route('blog.show', $lead->slug) }}" class="blog-lead-banner" data-reveal>
                <div class="blog-lead-visual">
                    @if($lead->thumbnail_url)
                        <img src="{{ $lead->thumbnail_url }}" alt="{{ $lead->title }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="blog-typo-plate" style="display:none;">
                            <span class="blog-typo-kicker">{{ __('Latest Article') }}</span>
                            <div class="blog-typo-heading">{{ $lead->title }}</div>
                        </div>
                    @else
                        <div class="blog-typo-plate">
                            <span class="blog-typo-kicker">{{ __('Latest Article') }}</span>
                            <div class="blog-typo-heading">{{ $lead->title }}</div>
                        </div>
                    @endif
                </div>

                <div class="blog-lead-info">
                    <div class="blog-meta">
                        @if($lead->published_at)
                            <span>{{ $lead->published_at->format('M d, Y') }}</span>
                        @endif
                        <span>·</span>
                        <span>{{ max(1, ceil(str_word_count(strip_tags($lead->content)) / 200)) }} {{ __('min read') }}</span>
                    </div>
                    <h2 class="blog-title" style="font-size: clamp(1.35rem, 2.5vw, 1.75rem); margin-bottom:0.75rem;">{{ $lead->title }}</h2>
                    @if($lead->excerpt)
                        <p class="blog-excerpt" style="margin-bottom:0.5rem;">{{ Str::limit($lead->excerpt, 185) }}</p>
                    @endif
                    <span class="blog-read-more">
                        <span>{{ __('Read Article') }}</span>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </span>
                </div>
            </a>
            @endif

            <div class="blog-grid" data-stagger>
                @foreach($gridPosts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
                    <div class="blog-image">
                        @if($post->thumbnail_url)
                            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="blog-typo-plate" style="display:none; min-height:100%;">
                                <span class="blog-typo-kicker">{{ __('Engineering Journal') }}</span>
                                <div class="blog-typo-heading" style="font-size:1.05rem;">{{ Str::limit($post->title, 65) }}</div>
                            </div>
                        @else
                            <div class="blog-typo-plate" style="min-height:100%;">
                                <span class="blog-typo-kicker">{{ __('Engineering Journal') }}</span>
                                <div class="blog-typo-heading" style="font-size:1.05rem;">{{ Str::limit($post->title, 65) }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="blog-body">
                        <div class="blog-meta">
                            @if($post->published_at)
                                <span>{{ $post->published_at->format('M d, Y') }}</span>
                            @endif
                            <span>·</span>
                            <span>{{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} {{ __('min read') }}</span>
                        </div>
                        <h3 class="blog-title">{{ $post->title }}</h3>
                        @if($post->excerpt)
                            <p class="blog-excerpt">{{ Str::limit($post->excerpt, 130) }}</p>
                        @endif
                        <span class="blog-read-more">
                            <span>{{ __('Read more') }}</span>
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </span>
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
                <i class="fas fa-pen-nib" aria-hidden="true"></i>
                <h3>{{ __('No articles yet') }}</h3>
                <p>{{ __('Stay tuned for upcoming posts!') }}</p>
            </div>
        @endif
    </div>
</section>
@endsection
