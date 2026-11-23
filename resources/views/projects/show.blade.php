@extends('layouts.pearl')

@section('active_nav', 'projects')
@section('title', $project->localized_title . ' | ' . (app()->getLocale() === 'vi' ? 'Hồ Thành Thiện' : 'Ho Thanh Thien'))
@section('description', \Illuminate\Support\Str::limit($project->clean_description, 160))
@section('og_type', 'article')
@if($project->image && !str_ends_with(strtolower($project->image), '.svg'))
@section('og_image', asset('storage/' . $project->image))
@endif

@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'SoftwareApplication',
    'name' => $project->localized_title,
    'description' => \Illuminate\Support\Str::limit($project->clean_description, 300),
    'applicationCategory' => 'WebApplication',
    'operatingSystem' => 'Web',
    'url' => url()->current(),
    'author' => [
        '@type' => 'Person',
        'name' => 'Hồ Thành Thiện',
        'url' => url('/'),
    ],
    'keywords' => $project->technologies->pluck('name')->implode(', '),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
@php
    $isVi = app()->getLocale() === 'vi';
    $hostLabel = $project->demo_url
        ? preg_replace('#^https?://#', '', rtrim($project->demo_url, '/'))
        : ($project->github_url ? preg_replace('#^https?://#', '', rtrim($project->github_url, '/')) : 'architecture.spec');
    $isPortfolio = $project->id === 1 || str_contains(strtolower($project->title), 'portfolio');
    $isTourrist = $project->id === 2 || str_contains(strtolower($project->title), 'tourrist');
    $isLaundry = $project->id === 3 || str_contains(strtolower($project->title), 'laundry');
    $hasCustomImage = $project->has_real_image;
@endphp

<!-- =========================================================================
     SECTION 1: PROJECT HEADER & BREADCRUMB BENTO
========================================================================= -->
<section class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm">
  <div class="relative z-10 space-y-5">

    <!-- Breadcrumb -->
    <nav class="flex flex-wrap items-center gap-2 font-mono text-xs text-slate-600 dark:text-slate-400" aria-label="Breadcrumb">
      <a href="{{ route('home') }}" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors inline-flex items-center gap-1">
        <i data-lucide="home" class="w-3.5 h-3.5"></i>
        <span data-i18n="breadcrumb_home">{{ $isVi ? 'Trang chủ' : 'Home' }}</span>
      </a>
      <span>/</span>
      <a href="{{ route('projects.index') }}" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors" data-i18n="nav_all_projects">
        {{ $isVi ? 'Kho Dự án' : 'Projects' }}
      </a>
      <span>/</span>
      <span class="text-slate-900 dark:text-white font-semibold truncate max-w-xs sm:max-w-md">{{ $project->localized_title }}</span>
    </nav>

    <!-- Meta Pills -->
    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2.5 font-mono text-xs">
      @if($project->period)
        <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold">
          <i data-lucide="calendar" class="w-3.5 h-3.5 text-sky-700 dark:text-sky-400"></i>
          <span>{{ $project->period }}</span>
        </span>
      @endif
      @if($project->role)
        <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-500/15 border border-indigo-200/80 dark:border-indigo-500/30 text-indigo-700 dark:text-indigo-300 font-semibold">
          <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
          <span>{{ $project->role }}</span>
        </span>
      @endif
      @if($project->featured)
        <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200/80 dark:border-emerald-500/30 text-emerald-700 dark:text-emerald-300 font-bold">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
          <span data-i18n="badge_featured">{{ $isVi ? 'Dự án Nổi bật' : 'Featured System' }}</span>
        </span>
      @endif
    </div>

    <!-- Title + Quick Actions -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
      <div class="space-y-3 max-w-3xl">
        <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.75rem] font-extrabold tracking-tight leading-[1.12] text-slate-900 dark:text-white">
          {{ $project->localized_title }}
        </h1>
        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed">
          {{ $project->summary }}
        </p>
      </div>

      <div class="flex flex-wrap items-center gap-2.5 flex-shrink-0">
        @if($project->demo_url)
          <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-700 text-white text-xs font-mono font-bold hover:bg-sky-800 transition-all shadow-lg shadow-sky-700/20">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
            <span data-i18n="btn_live_demo">{{ $isVi ? 'Xem Demo Trực tiếp' : 'Live Demo' }}</span>
          </a>
        @endif
        @if($project->github_url)
          <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 dark:bg-slate-800 border border-slate-900 dark:border-slate-700 text-white text-xs font-mono font-bold hover:bg-slate-800 dark:hover:border-sky-400 transition-all shadow-sm">
            <i data-lucide="github" class="w-3.5 h-3.5"></i>
            <span data-i18n="btn_source">{{ $isVi ? 'Mã nguồn GitHub' : 'Source Code' }}</span>
          </a>
        @endif
        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all">
          <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
          <span data-i18n="btn_all_projects">{{ $isVi ? 'Tất cả dự án' : 'All Projects' }}</span>
        </a>
      </div>
    </div>

  </div>
</section>

<!-- =========================================================================
     SECTION 2: MAIN ARCHITECTURE SHOWCASE + STICKY METADATA SIDEBAR
========================================================================= -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

  <!-- Left Column: Visual Architecture Specimen + Overview + Highlights -->
  <div class="lg:col-span-8 space-y-6">

    <!-- Visual Specimen Frame -->
    <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-4 sm:p-6 shadow-sm">
      <div class="relative z-10">
        @if($hasCustomImage)
          <div class="rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 aspect-[16/9]">
            <img src="{{ asset('storage/' . $project->image) }}" width="1280" height="720" alt="{{ $project->localized_title }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
          </div>
        @else
          <!-- Terminal / Architecture Specimen Plate (Crisp Dark Slate Window contrasting clean Pearl White card) -->
          <div class="rounded-2xl overflow-hidden bg-[#0F172A] border border-slate-800 shadow-inner">
            <!-- macOS Window Header -->
            <div class="h-10 px-4 bg-slate-900/95 border-b border-slate-800 flex items-center justify-between gap-3">
              <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500/90"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400/90"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/90"></span>
              </div>
              <span class="font-mono text-xs text-slate-300 truncate">{{ $hostLabel }}</span>
              <span class="font-mono text-xs px-2 py-0.5 rounded bg-sky-500/15 text-sky-300 border border-sky-500/30">SPECIMEN</span>
            </div>

            @if($isPortfolio)
              <div class="p-6 sm:p-8 font-mono text-xs sm:text-sm space-y-4 text-slate-200">
                <div class="text-sky-300 text-xs uppercase tracking-widest font-bold">PRODUCTION // LARAVEL SSR + AI CMS</div>
                <div class="space-y-2 bg-slate-950/70 rounded-xl p-4 border border-slate-800">
                  <div><span class="text-emerald-400 font-bold">$</span> curl -I https://hothanhthien.io.vn/</div>
                  <div class="text-slate-300 pl-3"><span class="px-1.5 py-0.5 rounded bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 text-xs">HTTP/2 200 OK</span> server: nginx / cpanel</div>
                  <div class="pt-1"><span class="text-emerald-400 font-bold">$</span> php artisan --version</div>
                  <div class="text-slate-300 pl-3">Laravel Framework <span class="text-sky-300 font-bold">11.x</span> (PHP 8.2 &bull; Bilingual VI/EN)</div>
                </div>
                <div class="flex flex-wrap gap-2">
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">Blade Engine</span>
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">MySQL / SQLite</span>
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">Bilingual EN / VI</span>
                </div>
              </div>
            @elseif($isTourrist)
              <div class="p-6 sm:p-8 font-mono space-y-6">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <span class="text-xs font-bold tracking-widest uppercase text-sky-300">PIPELINE // RAG ARCHITECTURE</span>
                  <span class="text-xs text-emerald-300 bg-emerald-500/15 border border-emerald-500/30 px-2.5 py-0.5 rounded-full">Cosine Match 98.4% &bull; 180ms</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                  <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800 flex flex-col items-center text-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-slate-300"></i>
                    <span class="text-xs font-bold text-white">1. Docs</span>
                    <span class="text-xs text-slate-300">PDF / Guides</span>
                  </div>
                  <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800 flex flex-col items-center text-center gap-2">
                    <i data-lucide="layers" class="w-5 h-5 text-indigo-400"></i>
                    <span class="text-xs font-bold text-white">2. Chunks</span>
                    <span class="text-xs text-slate-300">Token Overlap</span>
                  </div>
                  <div class="p-3.5 rounded-xl bg-sky-500/15 border border-sky-400/50 flex flex-col items-center text-center gap-2 shadow-lg shadow-sky-500/10">
                    <i data-lucide="database" class="w-5 h-5 text-sky-400"></i>
                    <span class="text-xs font-bold text-sky-300">3. ChromaDB</span>
                    <span class="text-xs text-sky-200">k-NN Vector</span>
                  </div>
                  <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-800 flex flex-col items-center text-center gap-2">
                    <i data-lucide="bot" class="w-5 h-5 text-emerald-400"></i>
                    <span class="text-xs font-bold text-white">4. LLM Reply</span>
                    <span class="text-xs text-slate-300">Cited Output</span>
                  </div>
                </div>

                <div class="flex flex-wrap gap-2">
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">FastAPI Async</span>
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">Python Embeddings</span>
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">Vector Search</span>
                </div>
              </div>
            @elseif($isLaundry)
              <div class="p-6 sm:p-8 font-mono space-y-6">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <span class="text-xs font-bold tracking-widest uppercase text-indigo-300">STATE MACHINE // ORDER LIFECYCLE</span>
                  <span class="text-xs text-sky-300 bg-sky-500/15 border border-sky-500/30 px-2.5 py-0.5 rounded-full">Spring Boot + Next.js</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                  <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/40 text-center space-y-1">
                    <div class="text-xs text-emerald-300 font-bold">STEP 01</div>
                    <div class="text-xs font-bold text-white">RECEIVED</div>
                    <div class="text-xs text-emerald-200">Check-in &amp; Tag</div>
                  </div>
                  <div class="p-3.5 rounded-xl bg-indigo-500/20 border border-indigo-400 text-center space-y-1 shadow-lg shadow-indigo-500/15">
                    <div class="text-xs text-indigo-200 font-bold">STEP 02 &bull; ACTIVE</div>
                    <div class="text-xs font-bold text-white">WASHING</div>
                    <div class="text-xs text-indigo-200">Service Queue</div>
                  </div>
                  <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-700 text-center space-y-1">
                    <div class="text-xs text-slate-300 font-bold">STEP 03</div>
                    <div class="text-xs font-bold text-white">READY</div>
                    <div class="text-xs text-slate-300">QA &amp; Notify</div>
                  </div>
                  <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-700 text-center space-y-1">
                    <div class="text-xs text-slate-300 font-bold">STEP 04</div>
                    <div class="text-xs font-bold text-white">DELIVERED</div>
                    <div class="text-xs text-slate-300">Invoice Settled</div>
                  </div>
                </div>

                <div class="flex flex-wrap gap-2">
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">Java Spring Boot</span>
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">Next.js Frontend</span>
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">JWT + OTP Security</span>
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">MySQL Normalized Schema</span>
                </div>
              </div>
            @else
              <div class="p-6 sm:p-8 font-mono text-xs sm:text-sm space-y-4 text-slate-200">
                <div class="text-sky-300 text-xs uppercase tracking-widest font-bold">ENTERPRISE // WEB PLATFORM</div>
                <div class="space-y-2 bg-slate-950/70 rounded-xl p-4 border border-slate-800">
                  <div><span class="text-sky-300 font-bold">&bull;</span> Production-ready Modular Architecture</div>
                  <div><span class="text-sky-300 font-bold">&bull;</span> Normalized Relational Database &amp; Clean APIs</div>
                  <div><span class="text-emerald-300 font-bold">&bull;</span> Responsive UI/UX Across Viewports</div>
                </div>
                <div class="flex flex-wrap gap-2">
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">Full-Stack Web</span>
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">PostgreSQL / MySQL</span>
                  <span class="px-2.5 py-1 rounded-lg bg-slate-800/90 border border-slate-700 text-xs text-slate-200">Clean Architecture</span>
                </div>
              </div>
            @endif
          </div>
        @endif
      </div>
    </div>

    <!-- Engineering Overview & Highlights Card -->
    <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm">
      <div class="relative z-10 space-y-6">
        <div class="space-y-2">
          <div class="font-mono text-xs font-bold uppercase tracking-widest text-sky-700 dark:text-sky-400" data-i18n="overview_kicker">
            {{ $isVi ? 'MÔ TẢ HỆ THỐNG' : 'SYSTEM OVERVIEW' }}
          </div>
          <h2 class="font-display text-2xl font-extrabold text-slate-900 dark:text-white" data-i18n="overview_heading">
            {{ $isVi ? 'Bối cảnh & Mục tiêu Xây dựng' : 'Architecture & Problem Statement' }}
          </h2>
          <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-base">
            {{ $project->summary }}
          </p>
        </div>

        @if(count($project->highlights) > 1)
          <div class="pt-5 border-t border-slate-200/80 dark:border-slate-800 space-y-4">
            <h3 class="font-display text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
              <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-700 dark:text-emerald-400"></i>
              <span data-i18n="highlights_heading">{{ $isVi ? 'Điểm nhấn Kỹ thuật & Triển khai' : 'Key Engineering Highlights' }}</span>
            </h3>
            <ul class="space-y-3">
              @foreach(array_slice($project->highlights, 1) as $item)
                <li class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50/90 dark:bg-slate-950/70 border border-slate-200/70 dark:border-slate-800/90 text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                  <span class="w-5 h-5 rounded-lg bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                  </span>
                  <span>{{ $item }}</span>
                </li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
    </div>

  </div>

  <!-- Right Column: Sticky Sidebar (Technologies, Links, CTA) -->
  <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">

    @if($project->technologies->count())
      <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        <div class="relative z-10 space-y-4">
          <h3 class="font-mono text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-400 flex items-center gap-2">
            <i data-lucide="cpu" class="w-4 h-4 text-sky-700 dark:text-sky-400"></i>
            <span data-i18n="sidebar_tech">{{ $isVi ? 'Công nghệ sử dụng' : 'Technologies Used' }}</span>
          </h3>

          <div class="flex flex-wrap gap-2">
            @foreach($project->technologies as $tech)
              <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/90 dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 shadow-xs">
                {!! \App\Support\TechIcon::render($tech->name) !!}
                <span>{{ $tech->name }}</span>
              </span>
            @endforeach
          </div>
        </div>
      </div>
    @endif

    @if($project->github_url || $project->demo_url)
      <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
        <div class="relative z-10 space-y-4">
          <h3 class="font-mono text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-400 flex items-center gap-2">
            <i data-lucide="link" class="w-4 h-4 text-indigo-600 dark:text-indigo-400"></i>
            <span data-i18n="sidebar_links">{{ $isVi ? 'Liên kết Dự án' : 'Project Links' }}</span>
          </h3>

          <div class="space-y-2.5 text-sm">
            @if($project->github_url)
              <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 hover:border-sky-400 transition-all group">
                <span class="inline-flex items-center gap-2.5 font-semibold text-slate-800 dark:text-slate-100">
                  <i data-lucide="github" class="w-4 h-4 text-slate-700 dark:text-slate-300"></i>
                  <span>GitHub Repository</span>
                </span>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-600 dark:text-slate-400 group-hover:text-sky-700 dark:group-hover:text-sky-400 transition-colors"></i>
              </a>
            @endif

            @if($project->demo_url)
              <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-sky-50/70 dark:bg-sky-500/10 border border-sky-200/80 dark:border-sky-500/30 hover:border-sky-400 transition-all group">
                <span class="inline-flex items-center gap-2.5 font-semibold text-sky-700 dark:text-sky-300">
                  <i data-lucide="globe" class="w-4 h-4"></i>
                  <span data-i18n="btn_live_demo">{{ $isVi ? 'Xem Demo Trực tiếp' : 'Live Demo' }}</span>
                </span>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-sky-700 dark:text-sky-400"></i>
              </a>
            @endif
          </div>
        </div>
      </div>
    @endif

    <!-- Contact CTA Box -->
    <div class="spotlight-card bg-gradient-to-br from-slate-900 to-slate-800 dark:from-slate-900 dark:to-indigo-950/90 border border-slate-800 dark:border-sky-500/30 rounded-3xl p-6 text-white shadow-md">
      <div class="relative z-10 space-y-4">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 font-mono text-xs font-bold uppercase tracking-wider">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
          <span data-i18n="cta_kicker">{{ $isVi ? 'HỢP TÁC & TUYỂN DỤNG' : 'DISCUSS ARCHITECTURE' }}</span>
        </div>
        <h3 class="font-display text-xl font-extrabold" data-i18n="cta_title">
          {{ $isVi ? 'Quan tâm đến hệ thống tương tự?' : 'Interested in a similar system?' }}
        </h3>
        <p class="text-xs text-slate-300 leading-relaxed" data-i18n="cta_desc">
          {{ $isVi ? 'Trao đổi trực tiếp về giải pháp Backend, RAG AI hoặc cơ hội Full-Stack Engineer.' : 'Let’s discuss how I can architect reliable backend services or applied AI pipelines for your team.' }}
        </p>
        <a href="{{ route('contact') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-sky-700 text-white text-xs font-mono font-bold hover:bg-sky-800 transition-all shadow-lg shadow-sky-700/25">
          <i data-lucide="mail" class="w-3.5 h-3.5"></i>
          <span data-i18n="cta_btn">{{ $isVi ? 'Liên hệ với Thiện' : 'Contact Thien' }}</span>
        </a>
      </div>
    </div>

  </aside>

</div>
@endsection

@section('scripts')
<script>
  window.PAGE_I18N = {
    en: {
      badge_featured: 'Featured System',
      btn_live_demo: 'Live Demo',
      btn_source: 'Source Code',
      btn_all_projects: 'All Projects',
      overview_kicker: 'SYSTEM OVERVIEW',
      overview_heading: 'Architecture & Problem Statement',
      highlights_heading: 'Key Engineering Highlights',
      sidebar_tech: 'Technologies Used',
      sidebar_links: 'Project Links',
      cta_kicker: 'DISCUSS ARCHITECTURE',
      cta_title: 'Interested in a similar system?',
      cta_desc: 'Let’s discuss how I can architect reliable backend services or applied AI pipelines for your team.',
      cta_btn: 'Contact Thien'
    },
    vi: {
      badge_featured: 'Dự án Nổi bật',
      btn_live_demo: 'Xem Demo Trực tiếp',
      btn_source: 'Mã nguồn GitHub',
      btn_all_projects: 'Tất cả dự án',
      overview_kicker: 'MÔ TẢ HỆ THỐNG',
      overview_heading: 'Bối cảnh & Mục tiêu Xây dựng',
      highlights_heading: 'Điểm nhấn Kỹ thuật & Triển khai',
      sidebar_tech: 'Công nghệ sử dụng',
      sidebar_links: 'Liên kết Dự án',
      cta_kicker: 'HỢP TÁC & TUYỂN DỤNG',
      cta_title: 'Quan tâm đến hệ thống tương tự?',
      cta_desc: 'Trao đổi trực tiếp về giải pháp Backend, RAG AI hoặc cơ hội Full-Stack Engineer.',
      cta_btn: 'Liên hệ với Thiện'
    }
  };
</script>
@endsection
