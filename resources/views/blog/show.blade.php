@extends('layouts.pearl')

@section('active_nav', 'blog')
@section('title', explode(' | ', $post->clean_title)[0] . ' | ' . (app()->getLocale() === 'vi' ? 'Hồ Thành Thiện' : 'Ho Thanh Thien'))
@section('description', $post->localized_excerpt ? \Illuminate\Support\Str::limit($post->localized_excerpt, 160) : \Illuminate\Support\Str::limit(strip_tags((string) $post->localized_content), 160))
@section('og_type', 'article')
@section('og_image', $post->og_image_url)

@section('meta')
<meta property="article:published_time" content="{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}">
<meta property="article:author" content="Ho Thanh Thien">
@endsection

@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => explode(' | ', $post->clean_title)[0],
    'description' => $post->localized_excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $post->localized_content), 200),
    'image' => $post->og_image_url,
    'datePublished' => $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String(),
    'dateModified' => $post->updated_at->toIso8601String(),
    'author' => [
        '@type' => 'Person',
        'name' => 'Hồ Thành Thiện',
        'url' => url('/'),
    ],
    'publisher' => [
        '@type' => 'Person',
        'name' => 'Hồ Thành Thiện',
    ],
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => url()->current(),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('styles')
  .pearl-prose {
    font-size: 1.03rem;
    line-height: 1.8;
    color: #334155;
  }
  html.dark .pearl-prose {
    color: #cbd5e1;
  }
  .pearl-prose h2 {
    font-family: var(--font-display);
    font-size: 1.55rem;
    font-weight: 800;
    color: #0f172a;
    margin: 2.1rem 0 0.85rem;
    padding-bottom: 0.45rem;
    border-bottom: 1px solid rgba(226, 232, 240, 0.9);
    letter-spacing: -0.02em;
  }
  html.dark .pearl-prose h2 {
    color: #ffffff;
    border-bottom-color: rgba(51, 65, 85, 0.85);
  }
  .pearl-prose h3 {
    font-family: var(--font-display);
    font-size: 1.24rem;
    font-weight: 700;
    color: #0f172a;
    margin: 1.65rem 0 0.65rem;
  }
  html.dark .pearl-prose h3 {
    color: #f1f5f9;
  }
  .pearl-prose p { margin-bottom: 1.25rem; }
  .pearl-prose ul, .pearl-prose ol { margin: 0 0 1.35rem 1.4rem; }
  .pearl-prose ul { list-style: disc; }
  .pearl-prose ol { list-style: decimal; }
  .pearl-prose li { margin-bottom: 0.45rem; }
  .pearl-prose strong { color: #0f172a; font-weight: 700; }
  html.dark .pearl-prose strong { color: #ffffff; }
  .pearl-prose a { color: #0284c7; text-decoration: underline; text-underline-offset: 3px; }
  html.dark .pearl-prose a { color: #38bdf8; }
  .pearl-prose pre {
    background: #0f172a;
    color: #e2e8f0;
    border: 1px solid #1e293b;
    padding: 1.25rem;
    border-radius: 1rem;
    overflow-x: auto;
    margin: 1.6rem 0;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.86rem;
    line-height: 1.65;
  }
  .pearl-prose code {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.87em;
    background: rgba(14, 165, 233, 0.1);
    color: #0284c7;
    padding: 0.15rem 0.42rem;
    border-radius: 0.375rem;
  }
  html.dark .pearl-prose code {
    background: rgba(56, 189, 248, 0.14);
    color: #38bdf8;
  }
  .pearl-prose pre code { background: none; padding: 0; color: inherit; }
  .pearl-prose blockquote {
    border-left: 3px solid #0ea5e9;
    padding: 0.9rem 1.2rem;
    background: rgba(14, 165, 233, 0.06);
    border-radius: 0 0.85rem 0.85rem 0;
    margin: 1.5rem 0;
    color: #1e293b;
  }
  html.dark .pearl-prose blockquote {
    background: rgba(56, 189, 248, 0.08);
    color: #e2e8f0;
  }
  .pearl-prose img {
    max-width: 100%;
    height: auto;
    border-radius: 1rem;
    margin: 1.5rem 0;
    border: 1px solid rgba(226, 232, 240, 0.9);
  }
  html.dark .pearl-prose img {
    border-color: rgba(51, 65, 85, 0.9);
  }
  .pearl-prose table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
    font-size: 0.92rem;
    overflow-x: auto;
    display: block;
  }
  .pearl-prose th, .pearl-prose td {
    border: 1px solid rgba(203, 213, 225, 0.9);
    padding: 0.65rem 0.9rem;
    text-align: left;
  }
  html.dark .pearl-prose th, html.dark .pearl-prose td {
    border-color: rgba(51, 65, 85, 0.9);
  }
  .pearl-prose th {
    background: #f1f5f9;
    color: #0f172a;
    font-weight: 700;
  }
  html.dark .pearl-prose th {
    background: #1e293b;
    color: #f8fafc;
  }
@endsection

@section('content')
@php
    $isVi = app()->getLocale() === 'vi';
    $wordCount = str_word_count(strip_tags((string) $post->localized_content));
    $readingMinutes = max(1, (int) ceil($wordCount / 200));
    $cleanTitle = explode(' | ', $post->clean_title)[0];
    $thumbUrl = $post->thumbnail_url;
@endphp

<!-- =========================================================================
     SECTION 1: EDITORIAL ARTICLE HEADER BENTO
========================================================================= -->
<section class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm">
  <div class="relative z-10 space-y-5">

    <!-- Breadcrumb -->
    <nav class="flex flex-wrap items-center gap-2 font-mono text-xs text-slate-500 dark:text-slate-400" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors inline-flex items-center gap-1">
        <i data-lucide="home" class="w-3.5 h-3.5"></i>
        <span data-i18n="breadcrumb_home">{{ $isVi ? 'Trang chủ' : 'Home' }}</span>
      </a>
      <span>/</span>
      <a href="{{ route('blog.index') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors" data-i18n="nav_blog">
        {{ $isVi ? 'Blog Kỹ thuật' : 'Engineering Blog' }}
      </a>
      <span>/</span>
      <span class="text-slate-900 dark:text-white font-semibold truncate max-w-xs sm:max-w-lg">{{ $cleanTitle }}</span>
    </nav>

    <!-- Meta Pills -->
    <div class="flex flex-wrap items-center gap-2.5 font-mono text-xs">
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-50 dark:bg-sky-500/15 border border-sky-200/80 dark:border-sky-500/30 text-sky-700 dark:text-sky-300 font-semibold">
        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
        <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}</span>
      </span>
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-500/15 border border-indigo-200/80 dark:border-indigo-500/30 text-indigo-700 dark:text-indigo-300 font-semibold">
        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
        <span>{{ $readingMinutes }}</span>
        <span data-i18n="min_read">{{ $isVi ? 'phút đọc' : 'min read' }}</span>
      </span>
      @if($post->ai_generated)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200/80 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 font-bold">
          <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
          <span>AI Assisted</span>
        </span>
      @endif
    </div>

    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
      <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.65rem] font-extrabold tracking-tight leading-[1.15] text-slate-900 dark:text-white max-w-4xl">
        {{ $cleanTitle }}
      </h1>

      <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all flex-shrink-0">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
        <span data-i18n="btn_all_articles">{{ $isVi ? 'Tất cả bài viết' : 'All Articles' }}</span>
      </a>
    </div>

  </div>
</section>

<!-- =========================================================================
     SECTION 2: ARTICLE CONTENT + STICKY SIDEBAR
========================================================================= -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

  <!-- Main Article Body -->
  <div class="lg:col-span-8 space-y-6">
    @if($thumbUrl)
      <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-4 sm:p-5 shadow-sm">
        <div class="relative z-10 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 aspect-[16/9]">
          <img src="{{ $thumbUrl }}" alt="{{ $cleanTitle }}" loading="lazy" decoding="async" class="w-full h-full object-cover" onerror="this.closest('.spotlight-card').style.display='none'">
        </div>
      </div>
    @endif

    <article class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm">
      <div class="relative z-10 pearl-prose">
        {!! $post->localized_content !!}
      </div>

      @if($post->faq && is_array($post->faq) && count($post->faq) > 0)
        <div class="relative z-10 mt-10 pt-8 border-t border-slate-200/80 dark:border-slate-800 space-y-4">
          <h3 class="font-display text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
            <i data-lucide="help-circle" class="w-5 h-5 text-sky-500"></i>
            <span data-i18n="faq_heading">{{ $isVi ? 'Câu hỏi thường gặp' : 'Frequently Asked Questions' }}</span>
          </h3>
          <div class="space-y-3">
            @foreach($post->faq as $item)
              @if(isset($item['question']) && isset($item['answer']))
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/90 dark:bg-slate-950/70 border border-slate-200/80 dark:border-slate-800 space-y-2">
                  <div class="font-display font-bold text-base text-slate-900 dark:text-white">{{ $item['question'] }}</div>
                  <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $item['answer'] }}</p>
                </div>
              @endif
            @endforeach
          </div>
        </div>
      @endif

      @if($post->sources && is_array($post->sources) && count($post->sources) > 0)
        <div class="relative z-10 mt-8 pt-6 border-t border-slate-200/80 dark:border-slate-800 space-y-3">
          <h4 class="font-mono text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 flex items-center gap-2">
            <i data-lucide="book-open" class="w-4 h-4 text-indigo-500"></i>
            <span data-i18n="sources_heading">{{ $isVi ? 'Tài liệu tham khảo' : 'References & Sources' }}</span>
          </h4>
          <ul class="space-y-2 text-sm">
            @foreach($post->sources as $src)
              @if(!empty($src['url']))
                <li class="flex items-start gap-2">
                  <i data-lucide="external-link" class="w-3.5 h-3.5 text-sky-500 mt-1 flex-shrink-0"></i>
                  <a href="{{ $src['url'] }}" target="_blank" rel="noopener nofollow" class="text-sky-600 dark:text-sky-400 hover:underline break-words">
                    {{ $src['title'] ?? $src['url'] }}
                  </a>
                </li>
              @endif
            @endforeach
          </ul>
        </div>
      @endif
    </article>
  </div>

  <!-- Sticky Editorial Sidebar -->
  <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
    @if($post->localized_excerpt)
      <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        <div class="relative z-10 space-y-3">
          <h3 class="font-mono text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 flex items-center gap-2">
            <i data-lucide="file-text" class="w-4 h-4 text-sky-500"></i>
            <span data-i18n="summary_heading">{{ $isVi ? 'Tóm tắt nội dung' : 'Article Summary' }}</span>
          </h3>
          <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $post->localized_excerpt }}</p>
        </div>
      </div>
    @endif

    <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
      <div class="relative z-10 space-y-4">
        <h3 class="font-mono text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 flex items-center gap-2">
          <i data-lucide="info" class="w-4 h-4 text-indigo-500"></i>
          <span data-i18n="details_heading">{{ $isVi ? 'Thông tin bài viết' : 'Article Metadata' }}</span>
        </h3>
        <div class="divide-y divide-slate-200/70 dark:divide-slate-800 text-xs font-mono">
          <div class="py-2.5 flex items-center justify-between text-slate-600 dark:text-slate-300">
            <span class="text-slate-500 dark:text-slate-400" data-i18n="meta_published">{{ $isVi ? 'Ngày đăng' : 'Published' }}</span>
            <span class="font-semibold">{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}</span>
          </div>
          <div class="py-2.5 flex items-center justify-between text-slate-600 dark:text-slate-300">
            <span class="text-slate-500 dark:text-slate-400" data-i18n="meta_read_time">{{ $isVi ? 'Thời lượng đọc' : 'Read Time' }}</span>
            <span class="font-semibold">{{ $readingMinutes }} <span data-i18n="min_read">{{ $isVi ? 'phút' : 'min read' }}</span></span>
          </div>
          @if($post->ai_generated)
            <div class="py-2.5 flex items-center justify-between text-slate-600 dark:text-slate-300">
              <span class="text-slate-500 dark:text-slate-400">Editorial</span>
              <span class="text-emerald-600 dark:text-emerald-400 font-bold">AI Assisted</span>
            </div>
          @endif
        </div>

        <div class="pt-2 space-y-2">
          <a href="{{ route('blog.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-mono font-bold hover:bg-sky-600 dark:hover:bg-sky-400 transition-all">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span data-i18n="btn_all_articles">{{ $isVi ? 'Xem tất cả bài viết' : 'All Articles' }}</span>
          </a>
          <a href="{{ route('contact') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all">
            <i data-lucide="mail" class="w-3.5 h-3.5"></i>
            <span data-i18n="btn_discuss">{{ $isVi ? 'Trao đổi với tác giả' : 'Discuss with Author' }}</span>
          </a>
        </div>
      </div>
    </div>
  </aside>

</div>
@endsection

@section('scripts')
<script>
  window.PAGE_I18N = {
    en: {
      min_read: 'min read',
      btn_all_articles: 'All Articles',
      faq_heading: 'Frequently Asked Questions',
      sources_heading: 'References & Sources',
      summary_heading: 'Article Summary',
      details_heading: 'Article Metadata',
      meta_published: 'Published',
      meta_read_time: 'Read Time',
      btn_discuss: 'Discuss with Author'
    },
    vi: {
      min_read: 'phút đọc',
      btn_all_articles: 'Tất cả bài viết',
      faq_heading: 'Câu hỏi thường gặp',
      sources_heading: 'Tài liệu tham khảo',
      summary_heading: 'Tóm tắt nội dung',
      details_heading: 'Thông tin bài viết',
      meta_published: 'Ngày đăng',
      meta_read_time: 'Thời lượng đọc',
      btn_discuss: 'Trao đổi với tác giả'
    }
  };
</script>
@endsection
