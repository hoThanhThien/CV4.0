@extends('layouts.pearl')

@section('active_nav', 'blog')
@section('title', (app()->getLocale() === 'vi' ? 'Blog & Ghi chú Kỹ thuật | Hồ Thành Thiện' : 'Blog & Technical Notes | Ho Thanh Thien'))
@section('description', app()->getLocale() === 'vi'
    ? 'Tổng hợp bài viết chuyên sâu về Lập trình Web, Spring Boot, Laravel, ReactJS và Kiến trúc RAG AI của Kỹ sư Hồ Thành Thiện.'
    : 'Technical articles, system architecture notes, and hands-on engineering guides on Laravel, Spring Boot, React, and RAG AI by Ho Thanh Thien.')
@section('keywords', 'Ho Thanh Thien Blog, RAG AI Tutorials, Spring Boot Architecture, Laravel Blog, React Hooks')

@section('content')
@php
    $isVi = app()->getLocale() === 'vi';
    $lead = ($posts->count() > 1 && $posts->currentPage() === 1) ? $posts->first() : null;
    $gridPosts = $lead ? $posts->skip(1) : $posts;
@endphp

<!-- =========================================================================
     SECTION 1: PUBLICATIONS HERO BENTO HEADER
========================================================================= -->
<section class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm">
  <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

    <div class="lg:col-span-8 space-y-4">
      <nav class="flex items-center gap-2 font-mono text-xs text-slate-500 dark:text-slate-400" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors inline-flex items-center gap-1">
          <i data-lucide="home" class="w-3.5 h-3.5"></i>
          <span data-i18n="breadcrumb_home">{{ $isVi ? 'Trang chủ' : 'Home' }}</span>
        </a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold" data-i18n="nav_blog">{{ $isVi ? 'Blog Kỹ thuật' : 'Engineering Blog' }}</span>
      </nav>

      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-50 dark:bg-sky-500/15 border border-sky-200/80 dark:border-sky-500/30 font-mono text-[11px] font-bold tracking-widest uppercase text-sky-700 dark:text-sky-300">
        <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
        <span data-i18n="blog_kicker">PUBLICATIONS // ENGINEERING JOURNAL</span>
      </div>

      <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.8rem] font-extrabold tracking-tight leading-[1.12] text-slate-900 dark:text-white">
        <span data-i18n="blog_title_1">{{ $isVi ? 'Ghi chú Kỹ thuật &' : 'Engineering Notes &' }}</span>
        <span class="bg-gradient-to-r from-sky-600 via-indigo-600 to-emerald-600 dark:from-sky-400 dark:via-indigo-400 dark:to-emerald-400 bg-clip-text text-transparent" data-i18n="blog_title_highlight">
          {{ $isVi ? 'Kiến trúc Hệ thống' : 'Technical Deep Dives' }}
        </span>
      </h1>

      <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed" data-i18n="blog_subtitle">
        {{ $isVi
            ? 'Chia sẻ kiến thức thực chiến về xây dựng hệ thống RAG AI, bảo mật RESTful API với Spring Boot, Laravel và tối ưu trạng thái React.'
            : 'Articles, engineering architecture breakdowns, and production learnings covering RAG AI pipelines, Spring Boot APIs, Laravel, and React.' }}
      </p>
    </div>

    <!-- Right Quick Stats & Actions -->
    <div class="lg:col-span-4 space-y-3">
      <div class="grid grid-cols-2 gap-2.5">
        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
          <div class="font-display text-2xl font-extrabold text-sky-600 dark:text-sky-400">{{ $posts->total() }}</div>
          <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="stat_articles">
            {{ $isVi ? 'Bài viết' : 'Articles' }}
          </div>
        </div>
        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
          <div class="font-display text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $posts->lastPage() }}</div>
          <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="stat_pages">
            {{ $isVi ? 'Trang' : 'Pages' }}
          </div>
        </div>
      </div>

      <div class="flex flex-wrap sm:flex-nowrap gap-2">
        <a href="{{ route('home') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all">
          <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
          <span data-i18n="btn_back_home">{{ $isVi ? 'Trang chủ' : 'Overview' }}</span>
        </a>
        <a href="{{ asset('assets/docs/HoThanhThien_CV_Brochure.pdf') }}" download class="flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-mono font-semibold hover:bg-slate-800 dark:hover:bg-sky-600 transition-all shadow-sm">
          <i data-lucide="download" class="w-3.5 h-3.5"></i>
          <span data-i18n="btn_brochure">Brochure PDF</span>
        </a>
      </div>
    </div>

  </div>
</section>

@if($posts->count())
  <!-- =========================================================================
       SECTION 2: LEAD FEATURED ARTICLE (PAGE 1 ONLY WHEN >1 POST)
  ========================================================================= -->
  @if($lead)
    @php
        $leadWords = str_word_count(strip_tags((string) $lead->localized_content));
        $leadMinutes = max(1, (int) ceil($leadWords / 200));
        $leadThumb = $lead->thumbnail_url;
    @endphp
    <article class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm hover:-translate-y-1 transition-all group">
      <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12">

        <!-- Cover Visual / Typographic Fallback -->
        <a href="{{ route('blog.show', $lead->slug) }}" class="lg:col-span-6 relative min-h-[260px] lg:min-h-[340px] bg-slate-100 dark:bg-slate-950 overflow-hidden block border-b lg:border-b-0 lg:border-r border-slate-200/80 dark:border-slate-800">
          @if($leadThumb)
            <img src="{{ $leadThumb }}" alt="{{ $lead->localized_title }}" loading="lazy" decoding="async"
                 class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div style="display:none;" class="w-full h-full flex-col justify-between p-7 bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950 text-white">
              <div class="font-mono text-xs uppercase tracking-widest text-sky-400">FEATURED // JOURNAL</div>
              <div class="font-display text-xl sm:text-2xl font-extrabold leading-tight line-clamp-3">{{ $lead->localized_title }}</div>
              <div class="font-mono text-xs text-slate-400">hothanhthien.io.vn</div>
            </div>
          @else
            <div class="w-full h-full flex flex-col justify-between p-7 bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950 text-white">
              <div class="font-mono text-xs uppercase tracking-widest text-sky-400">FEATURED // JOURNAL</div>
              <div class="font-display text-xl sm:text-2xl font-extrabold leading-tight line-clamp-3">{{ $lead->title }}</div>
              <div class="font-mono text-xs text-slate-400">hothanhthien.io.vn</div>
            </div>
          @endif
        </a>

        <!-- Content -->
        <div class="lg:col-span-6 p-6 sm:p-8 lg:p-10 flex flex-col justify-between gap-6">
          <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-3 font-mono text-xs">
              <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200/80 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 font-bold uppercase tracking-wider text-[11px]">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span data-i18n="featured_badge">{{ $isVi ? 'Nổi bật' : 'Featured' }}</span>
              </span>
              <span class="text-slate-500 dark:text-slate-400 inline-flex items-center gap-1">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                {{ $lead->published_at ? $lead->published_at->format('M d, Y') : '' }}
              </span>
              <span class="text-slate-500 dark:text-slate-400 inline-flex items-center gap-1">
                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                {{ $leadMinutes }} <span data-i18n="min_read">{{ $isVi ? 'phút đọc' : 'min read' }}</span>
              </span>
            </div>

            <a href="{{ route('blog.show', $lead->slug) }}" class="block group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
              <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white leading-snug">
                {{ $lead->title }}
              </h2>
            </a>

            @if($lead->excerpt)
              <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-3">
                {{ $lead->excerpt }}
              </p>
            @endif
          </div>

          <div>
            <a href="{{ route('blog.show', $lead->slug) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-mono font-bold hover:bg-sky-600 dark:hover:bg-sky-400 transition-all shadow-sm">
              <span data-i18n="read_article">{{ $isVi ? 'Đọc bài viết' : 'Read Article' }}</span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
          </div>
        </div>

      </div>
    </article>
  @endif

  <!-- =========================================================================
       SECTION 3: ARTICLES BENTO GRID
  ========================================================================= -->
  <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($gridPosts as $idx => $post)
      @php
          $words = str_word_count(strip_tags((string) $post->localized_content));
          $minutes = max(1, (int) ceil($words / 200));
          $thumbUrl = $post->thumbnail_url;
      @endphp
      <article class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm hover:-translate-y-1 transition-all flex flex-col group">
        <a href="{{ route('blog.show', $post->slug) }}" class="relative z-10 aspect-[16/9] bg-slate-100 dark:bg-slate-950 border-b border-slate-200/80 dark:border-slate-800 overflow-hidden block">
          @if($thumbUrl)
            <img src="{{ $thumbUrl }}" alt="{{ $post->localized_title }}" loading="lazy" decoding="async"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div style="display:none;" class="w-full h-full flex-col justify-between p-5 bg-gradient-to-br from-slate-900 to-slate-800 text-white">
              <span class="font-mono text-[10px] uppercase tracking-widest text-sky-400">ARTICLE // NOTE</span>
              <span class="font-display text-base font-bold leading-snug line-clamp-2">{{ $post->localized_title }}</span>
              <span class="font-mono text-[10px] text-slate-400">hothanhthien.io.vn</span>
            </div>
          @else
            <div class="w-full h-full flex flex-col justify-between p-5 bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950 text-white">
              <span class="font-mono text-[10px] uppercase tracking-widest text-sky-400">ARTICLE // NOTE</span>
              <span class="font-display text-base font-bold leading-snug line-clamp-2">{{ $post->localized_title }}</span>
              <span class="font-mono text-[10px] text-slate-400">hothanhthien.io.vn</span>
            </div>
          @endif
        </a>

        <div class="relative z-10 p-6 flex-1 flex flex-col justify-between gap-4">
          <div class="space-y-3">
            <div class="flex items-center justify-between gap-2 font-mono text-[11px] text-slate-500 dark:text-slate-400">
              <span class="inline-flex items-center gap-1">
                <i data-lucide="calendar" class="w-3 h-3 text-sky-500"></i>
                {{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}
              </span>
              <span class="inline-flex items-center gap-1">
                <i data-lucide="clock" class="w-3 h-3 text-indigo-500"></i>
                {{ $minutes }} <span data-i18n="min_read">{{ $isVi ? 'phút đọc' : 'min read' }}</span>
              </span>
            </div>

            <a href="{{ route('blog.show', $post->slug) }}" class="block group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">
              <h3 class="font-display text-lg font-extrabold text-slate-900 dark:text-white leading-snug line-clamp-2">
                {{ $post->localized_title }}
              </h3>
            </a>

            @if($post->localized_excerpt)
              <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-3">
                {{ $post->localized_excerpt }}
              </p>
            @endif
          </div>

          <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
            <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1.5 font-mono text-xs font-bold text-sky-600 dark:text-sky-400 group-hover:gap-2.5 transition-all">
              <span data-i18n="read_more">{{ $isVi ? 'Đọc tiếp' : 'Read More' }}</span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
          </div>
        </div>
      </article>
    @endforeach
  </section>

  <!-- Pagination -->
  @if($posts->hasPages())
    <div class="flex flex-wrap items-center justify-center gap-2 pt-4">
      @if($posts->onFirstPage())
        <span class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-mono text-slate-400 cursor-not-allowed">&laquo;</span>
      @else
        <a href="{{ $posts->previousPageUrl() }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-colors">&laquo;</a>
      @endif

      @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
        @if($page == $posts->currentPage())
          <span class="px-3.5 py-2 rounded-xl bg-sky-500 border border-sky-500 text-xs font-mono font-bold text-white shadow-sm">{{ $page }}</span>
        @else
          <a href="{{ $url }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-colors">{{ $page }}</a>
        @endif
      @endforeach

      @if($posts->hasMorePages())
        <a href="{{ $posts->nextPageUrl() }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-colors">&raquo;</a>
      @else
        <span class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-mono text-slate-400 cursor-not-allowed">&raquo;</span>
      @endif
    </div>
  @endif

@else
  <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-14 text-center space-y-4">
    <div class="relative z-10 flex flex-col items-center space-y-3">
      <div class="w-14 h-14 rounded-2xl bg-sky-50 dark:bg-sky-500/15 border border-sky-200 dark:border-sky-500/30 flex items-center justify-center text-sky-600 dark:text-sky-400">
        <i data-lucide="newspaper" class="w-7 h-7"></i>
      </div>
      <h2 class="font-display text-xl font-extrabold text-slate-900 dark:text-white" data-i18n="empty_title">
        {{ $isVi ? 'Chưa có bài viết nào' : 'No articles published yet' }}
      </h2>
      <p class="text-sm text-slate-600 dark:text-slate-400 max-w-md" data-i18n="empty_desc">
        {{ $isVi ? 'Vui lòng quay lại sau để đọc các bài viết và ghi chú kỹ thuật mới nhất.' : 'Check back soon for new engineering notes, tutorials, and system write-ups.' }}
      </p>
    </div>
  </div>
@endif
@endsection

@section('scripts')
<script>
  window.PAGE_I18N = {
    en: {
      blog_kicker: 'PUBLICATIONS // ENGINEERING JOURNAL',
      blog_title_1: 'Engineering Notes &',
      blog_title_highlight: 'Technical Deep Dives',
      blog_subtitle: 'Articles, engineering architecture breakdowns, and production learnings covering RAG AI pipelines, Spring Boot APIs, Laravel, and React.',
      stat_articles: 'Articles',
      stat_pages: 'Pages',
      featured_badge: 'Featured',
      read_article: 'Read Article',
      read_more: 'Read More',
      min_read: 'min read',
      empty_title: 'No articles published yet',
      empty_desc: 'Check back soon for new engineering notes, tutorials, and system write-ups.'
    },
    vi: {
      blog_kicker: 'BÀI VIẾT // TẠP CHÍ KỸ THUẬT',
      blog_title_1: 'Ghi chú Kỹ thuật &',
      blog_title_highlight: 'Kiến trúc Hệ thống',
      blog_subtitle: 'Chia sẻ kiến thức thực chiến về xây dựng hệ thống RAG AI, bảo mật RESTful API với Spring Boot, Laravel và tối ưu trạng thái React.',
      stat_articles: 'Bài viết',
      stat_pages: 'Trang',
      featured_badge: 'Nổi bật',
      read_article: 'Đọc bài viết',
      read_more: 'Đọc tiếp',
      min_read: 'phút đọc',
      empty_title: 'Chưa có bài viết nào',
      empty_desc: 'Vui lòng quay lại sau để đọc các bài viết và ghi chú kỹ thuật mới nhất.'
    }
  };
</script>
@endsection
