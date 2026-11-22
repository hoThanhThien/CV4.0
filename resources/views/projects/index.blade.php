@php
    $isVi = app()->getLocale() === 'vi';
    $archiveStats = $archiveMeta ?? \App\Support\PortfolioCache::projectArchiveMeta();
    $allTechNames = $archiveStats['allTechNames'] ?? [];
    $totalProjectsCount = $archiveStats['totalCount'] ?? $projects->total();
    $featuredCount = $archiveStats['featuredCount'] ?? 0;

    $renderTechIcon = static fn (string $name): string => \App\Support\TechIcon::render($name, 'w-3.5 h-3.5', false);
@endphp
@extends('layouts.pearl')

@section('active_nav', 'projects')
@section('title', __('Projects') . ' | ' . ($isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien'))
@section('description', $isVi ? 'Khám phá danh sách các dự án, sản phẩm phần mềm và kiến trúc hệ thống hiện đại được phát triển bởi Kỹ sư Hồ Thành Thiện.' : 'Explore a curated portfolio of modern web applications, AI/RAG architectures, and enterprise systems developed by Ho Thanh Thien.')
@section('keywords', $isVi ? 'Dự án Hồ Thành Thiện, Portfolio Dự án, Web Apps, Laravel, Spring Boot, RAG AI, TourRist, Laundry' : 'Ho Thanh Thien Projects, Software Portfolio, Laravel Web Apps, Spring Boot, RAG AI Architecture')

@section('styles')
  .projects-filter-bar::-webkit-scrollbar {
    height: 5px;
  }
  .projects-filter-bar::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.35);
    border-radius: 9999px;
  }
@endsection

@section('content')

    <!-- =========================================================================
         SECTION 1: ARCHIVE HERO BAND (PEARL WHITE GLASS BENTO HEADER)
    ========================================================================= -->
    <section class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm">
      <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

        <div class="lg:col-span-8 space-y-4">
          <!-- Breadcrumb -->
          <nav class="flex items-center gap-2 font-mono text-xs text-slate-500 dark:text-slate-400" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors inline-flex items-center gap-1">
              <i data-lucide="home" class="w-3.5 h-3.5"></i>
              <span data-i18n="breadcrumb_home">{{ __('Home') }}</span>
            </a>
            <span>/</span>
            <span class="text-slate-900 dark:text-white font-semibold" data-i18n="nav_all_projects">Archive</span>
          </nav>

          <!-- Kicker Badge -->
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-50 dark:bg-sky-500/15 border border-sky-200/80 dark:border-sky-500/30 font-mono text-[11px] font-bold tracking-widest uppercase text-sky-700 dark:text-sky-300">
            <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
            <span data-i18n="archive_kicker">ARCHIVE // SOFTWARE &amp; SYSTEMS</span>
          </div>

          <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.85rem] font-extrabold tracking-tight leading-[1.12] text-slate-900 dark:text-white">
            <span data-i18n="archive_title_1">Engineered Systems &amp;</span>
            <span class="bg-gradient-to-r from-sky-600 via-indigo-600 to-emerald-600 dark:from-sky-400 dark:via-indigo-400 dark:to-emerald-400 bg-clip-text text-transparent" data-i18n="archive_title_highlight">
              Production Archive
            </span>
          </h1>

          <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed" data-i18n="archive_subtitle">
            {{ __("Things I've built and worked on") }} — spanning RAG AI pipelines, Spring Boot microservices, Laravel SSR platforms, and enterprise Odoo workflows.
          </p>
        </div>

        <!-- Right Quick Stats & Actions -->
        <div class="lg:col-span-4 space-y-3">
          <div class="grid grid-cols-3 gap-2 sm:gap-2.5">
            <div class="p-2.5 sm:p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
              <div class="font-display text-xl sm:text-2xl font-extrabold text-sky-600 dark:text-sky-400">{{ $projects->total() }}</div>
              <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="stat_total">Projects</div>
            </div>
            <div class="p-2.5 sm:p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
              <div class="font-display text-xl sm:text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $featuredCount }}</div>
              <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="stat_featured">Featured</div>
            </div>
            <div class="p-2.5 sm:p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
              <div class="font-display text-xl sm:text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ count($allTechNames) }}+</div>
              <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="stat_stacks">Stacks</div>
            </div>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap gap-2">
            <a href="{{ route('home') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all">
              <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
              <span data-i18n="btn_back_home">Overview</span>
            </a>
            <a href="{{ asset('assets/docs/HoThanhThien_CV_Brochure.pdf') }}" download class="flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-mono font-semibold hover:bg-slate-800 dark:hover:bg-sky-600 transition-all shadow-sm">
              <i data-lucide="download" class="w-3.5 h-3.5"></i>
              <span data-i18n="btn_brochure">Brochure PDF</span>
            </a>
          </div>
        </div>

      </div>
    </section>

    @if($projects->count())
    <!-- =========================================================================
         SECTION 2: STACK FILTER BAR & LIVE SEARCH
    ========================================================================= -->
    <section class="space-y-4">
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">

        <!-- Tech Stack Filter Pills -->
        @if(count($allTechNames))
        <div class="projects-filter-bar flex items-center gap-2 overflow-x-auto pb-1.5" role="tablist" aria-label="{{ __('Filter by stack') }}">
          <button type="button" class="proj-filter-btn px-3.5 py-2 rounded-xl text-xs font-mono font-semibold bg-sky-500 text-white shadow-lg shadow-sky-500/20 whitespace-nowrap flex items-center gap-1.5 transition-all" data-proj-filter="all">
            <i data-lucide="layers" class="w-3.5 h-3.5"></i>
            <span data-i18n="filter_all">{{ __('All Stacks') }}</span>
          </button>
          @foreach($allTechNames as $tName)
            @php
              $lowerName = strtolower($tName);
              $renderedTechIcon = $renderTechIcon($tName);
            @endphp
            <button type="button" class="proj-filter-btn px-3.5 py-2 rounded-xl text-xs font-mono font-semibold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:border-sky-400 whitespace-nowrap flex items-center gap-1.5 shadow-sm transition-all" data-proj-filter="{{ $lowerName }}">
              {!! $renderedTechIcon !!}
              <span>{{ $tName }}</span>
            </button>
          @endforeach
        </div>
        @endif

        <!-- Quick Search + Active Counter -->
        <div class="flex items-center gap-2.5 flex-shrink-0">
          <div class="relative flex-1 sm:w-64">
            <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            <input type="text" id="projectSearchInput" data-i18n-placeholder="search_placeholder" placeholder="Search project or stack..." class="w-full pl-9 pr-3 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 shadow-sm">
          </div>
          <span id="visibleProjectsCount" class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 font-mono text-[11px] font-bold text-slate-600 dark:text-slate-300 whitespace-nowrap">
            {{ $projects->count() }} / {{ $projects->total() }}
          </span>
        </div>

      </div>
    </section>

    <!-- =========================================================================
         SECTION 3: PROJECTS SHOWCASE BENTO GRID (2 COLUMNS)
    ========================================================================= -->
    <section>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($projects as $idx => $project)
          @php
              $hostLabel = $project->demo_url
                  ? preg_replace('#^https?://#', '', rtrim($project->demo_url, '/'))
                  : ($project->github_url ? preg_replace('#^https?://#', '', rtrim($project->github_url, '/')) : 'architecture.spec');
              $techSlugList = $project->technologies->pluck('name')->map(fn($n) => strtolower($n))->implode('|');
              $searchBlob = strtolower($project->title . ' ' . $project->localized_title . ' ' . $project->summary . ' ' . $project->technologies->pluck('name')->implode(' ') . ' ' . ($project->role ?? ''));
              $isPortfolio = $project->id === 1 || str_contains(strtolower($project->title), 'portfolio');
              $isTourrist = $project->id === 2 || str_contains(strtolower($project->title), 'tourrist');
              $isLaundry = $project->id === 3 || str_contains(strtolower($project->title), 'laundry');
              $hasCustomImage = $project->has_real_image;
          @endphp

          <article class="project-card spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden flex flex-col justify-between" data-proj-techs="{{ $techSlugList }}" data-proj-search="{{ e($searchBlob) }}">

            <!-- Top Visual Stage -->
            <div class="p-4 pb-0 relative z-10">
              <a href="{{ route('projects.show', $project->id) }}" class="group block relative aspect-[16/10] rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-slate-950" aria-label="{{ $project->localized_title }}">

                @if($project->featured)
                  <span class="absolute top-3 right-3 z-20 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-emerald-200 dark:border-emerald-500/40 text-emerald-700 dark:text-emerald-300 font-mono text-[10px] font-bold uppercase tracking-wider shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span data-i18n="badge_featured">{{ __('Featured') }}</span>
                  </span>
                @endif

                @if($hasCustomImage)
                  <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->localized_title }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                  <div class="hidden w-full h-full bg-slate-950 text-slate-100 p-5 flex-col justify-between">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2.5 font-mono text-[11px] text-slate-400">
                      <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500"></span><span class="w-2 h-2 rounded-full bg-amber-400"></span><span class="w-2 h-2 rounded-full bg-emerald-500"></span></span>
                      <span class="truncate">{{ $hostLabel }}</span>
                    </div>
                    <div class="font-display font-bold text-lg text-white">{{ $project->localized_title }}</div>
                    <div class="font-mono text-[11px] text-sky-400">ARCHITECTURE // PRODUCTION CASE STUDY</div>
                  </div>
                @elseif($isPortfolio)
                  <!-- Specimen: Live Portfolio Terminal -->
                  <div class="w-full h-full bg-slate-950 text-slate-100 p-5 flex flex-col justify-between relative">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(56,189,248,0.16),transparent_55%)] pointer-events-none"></div>
                    <div class="relative z-10 flex items-center justify-between border-b border-slate-800 pb-2.5">
                      <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                      </div>
                      <span class="font-mono text-[11px] text-slate-400">hothanhthien.io.vn</span>
                    </div>
                    <div class="relative z-10 font-mono text-xs space-y-2 my-auto">
                      <div><span class="text-sky-400 font-bold">$</span> <span class="text-slate-200">curl -I https://hothanhthien.io.vn/</span></div>
                      <div class="pl-3 text-slate-400 text-[11px]"><span class="px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-semibold mr-1.5">HTTP/2 200 OK</span> nginx / cpanel</div>
                      <div><span class="text-sky-400 font-bold">$</span> <span class="text-slate-200">php artisan --version</span></div>
                      <div class="pl-3 text-slate-300 text-[11px]">Laravel <span class="text-sky-300 font-semibold">11.x SSR</span> &bull; PHP 8.2</div>
                    </div>
                    <div class="relative z-10 flex flex-wrap gap-1.5 pt-2 border-t border-slate-800/80 font-mono text-[10px]">
                      <span class="px-2 py-0.5 rounded bg-slate-900 border border-slate-700 text-slate-300">Blade SSR</span>
                      <span class="px-2 py-0.5 rounded bg-slate-900 border border-slate-700 text-slate-300">MySQL/SQLite</span>
                      <span class="px-2 py-0.5 rounded bg-slate-900 border border-slate-700 text-slate-300">EN / VI Sync</span>
                    </div>
                  </div>
                @elseif($isTourrist)
                  <!-- Specimen: RAG AI Pipeline -->
                  <div class="w-full h-full bg-gradient-to-br from-slate-950 via-slate-900 to-sky-950 text-slate-100 p-5 flex flex-col justify-between relative">
                    <div class="relative z-10 flex items-center justify-between border-b border-slate-800 pb-2.5">
                      <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                      </div>
                      <span class="font-mono text-[11px] text-sky-300">Match 98.4% &bull; 180ms</span>
                    </div>
                    <div class="relative z-10 space-y-2.5 my-auto font-mono">
                      <div class="text-[11px] font-bold text-sky-400 tracking-wider uppercase">PIPELINE // APPLIED RAG AI</div>
                      <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                        <span class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-700 text-slate-200">PDF Docs</span>
                        <span class="text-slate-500">&rarr;</span>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-700 text-slate-200">Chunks</span>
                        <span class="text-slate-500">&rarr;</span>
                        <span class="px-2.5 py-1 rounded-lg bg-sky-500/20 border border-sky-400/50 text-sky-300 font-bold">ChromaDB</span>
                        <span class="text-slate-500">&rarr;</span>
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-500/20 border border-emerald-400/40 text-emerald-300 font-bold">Grounded LLM</span>
                      </div>
                    </div>
                    <div class="relative z-10 flex flex-wrap gap-1.5 pt-2 border-t border-slate-800/80 font-mono text-[10px]">
                      <span class="px-2 py-0.5 rounded bg-slate-900/90 border border-slate-700 text-slate-300">FastAPI</span>
                      <span class="px-2 py-0.5 rounded bg-slate-900/90 border border-slate-700 text-slate-300">Python Embeddings</span>
                      <span class="px-2 py-0.5 rounded bg-slate-900/90 border border-slate-700 text-slate-300">Cosine Top-K</span>
                    </div>
                  </div>
                @elseif($isLaundry)
                  <!-- Specimen: Enterprise Java State Machine -->
                  <div class="w-full h-full bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-slate-100 p-5 flex flex-col justify-between relative">
                    <div class="relative z-10 flex items-center justify-between border-b border-slate-800 pb-2.5">
                      <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                      </div>
                      <span class="font-mono text-[11px] text-indigo-300">Spring Boot &bull; JWT Guard</span>
                    </div>
                    <div class="relative z-10 space-y-2.5 my-auto font-mono">
                      <div class="text-[11px] font-bold text-indigo-300 tracking-wider uppercase">STATE MACHINE // ORDER LIFECYCLE</div>
                      <div class="flex flex-wrap items-center gap-1.5 text-[10px]">
                        <span class="px-2 py-1 rounded-lg bg-emerald-500/15 border border-emerald-400/40 text-emerald-300 font-bold">RECEIVED</span>
                        <span class="text-slate-500">&rsaquo;</span>
                        <span class="px-2 py-1 rounded-lg bg-sky-500/20 border border-sky-400/50 text-sky-300 font-bold">WASHING</span>
                        <span class="text-slate-500">&rsaquo;</span>
                        <span class="px-2 py-1 rounded-lg bg-slate-900 border border-slate-700 text-slate-300">READY</span>
                        <span class="text-slate-500">&rsaquo;</span>
                        <span class="px-2 py-1 rounded-lg bg-slate-900 border border-slate-700 text-slate-300">DELIVERED</span>
                      </div>
                    </div>
                    <div class="relative z-10 flex flex-wrap gap-1.5 pt-2 border-t border-slate-800/80 font-mono text-[10px]">
                      <span class="px-2 py-0.5 rounded bg-slate-900/90 border border-slate-700 text-slate-300">Java Spring Boot</span>
                      <span class="px-2 py-0.5 rounded bg-slate-900/90 border border-slate-700 text-slate-300">Next.js</span>
                      <span class="px-2 py-0.5 rounded bg-slate-900/90 border border-slate-700 text-slate-300">RBAC Auth</span>
                    </div>
                  </div>
                @else
                  <!-- Specimen: Enterprise Platform Preview -->
                  <div class="w-full h-full bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950/70 text-slate-100 p-5 flex flex-col justify-between relative">
                    <div class="relative z-10 flex items-center justify-between border-b border-slate-800 pb-2.5">
                      <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                      </div>
                      <span class="font-mono text-[11px] text-slate-400 truncate max-w-[200px]">{{ $hostLabel }}</span>
                    </div>
                    <div class="relative z-10 font-mono text-xs space-y-1.5 my-auto">
                      <div class="text-[11px] font-bold text-emerald-400 tracking-wider uppercase">ENTERPRISE // WEB PLATFORM</div>
                      <div class="text-slate-200"><span class="text-emerald-400 mr-1.5">&bull;</span> Production Responsive Interface</div>
                      <div class="text-slate-300"><span class="text-sky-400 mr-1.5">&bull;</span> Relational Schema &amp; Backend Integration</div>
                    </div>
                    <div class="relative z-10 flex flex-wrap gap-1.5 pt-2 border-t border-slate-800/80 font-mono text-[10px]">
                      @forelse($project->technologies->take(3) as $pillTech)
                        <span class="px-2 py-0.5 rounded bg-slate-900/90 border border-slate-700 text-slate-300">{{ $pillTech->name }}</span>
                      @empty
                        <span class="px-2 py-0.5 rounded bg-slate-900/90 border border-slate-700 text-slate-300">Full-Stack Web</span>
                      @endforelse
                    </div>
                  </div>
                @endif

              </a>
            </div>

            <!-- Card Body -->
            <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-4 relative z-10">
              <div class="space-y-3">
                <!-- Meta Ribbon -->
                <div class="flex flex-wrap items-center gap-2 font-mono text-[11px]">
                  <span class="px-2.5 py-0.5 rounded-md bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-200/80 dark:border-sky-500/30 font-bold">
                    PRJ // {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}
                  </span>
                  @if($project->period)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                      <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i>
                      {{ $project->period }}
                    </span>
                  @endif
                  @if($project->role)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border border-indigo-200/70 dark:border-indigo-500/30 font-medium">
                      <i data-lucide="user-check" class="w-3 h-3"></i>
                      {{ $project->role }}
                    </span>
                  @endif
                </div>

                <!-- Title -->
                <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white leading-snug">
                  <a href="{{ route('projects.show', $project->id) }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                    {{ $project->localized_title }}
                  </a>
                </h2>

                <!-- Summary -->
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                  {{ $project->summary }}
                </p>

                <!-- Engineering Highlights -->
                @if(count($project->highlights) > 1)
                <ul class="space-y-1.5 pt-1">
                  @foreach(array_slice($project->highlights, 1, 2) as $highlight)
                    <li class="flex items-start gap-2 text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                      <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0 mt-0.5"></i>
                      <span>{{ $highlight }}</span>
                    </li>
                  @endforeach
                </ul>
                @endif
              </div>

              <div class="space-y-4 pt-2">
                <!-- Technology Pills -->
                @if($project->technologies->count())
                <div class="flex flex-wrap gap-1.5">
                  @foreach($project->technologies->take(6) as $tech)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100/90 dark:bg-slate-800/90 border border-slate-200/70 dark:border-slate-700/80 text-[11px] font-mono font-medium text-slate-700 dark:text-slate-200">
                      {!! $renderTechIcon($tech->name) !!}
                      <span>{{ $tech->name }}</span>
                    </span>
                  @endforeach
                </div>
                @endif

                <!-- Action Links -->
                <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                  <a href="{{ route('projects.show', $project->id) }}" class="flex-1 min-w-[160px] inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-semibold hover:bg-slate-800 dark:hover:bg-sky-600 transition-all shadow-sm">
                    <span data-i18n="btn_case_study">{{ __('View Case Study') }}</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                  </a>

                  @if($project->github_url)
                  <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all" title="{{ __('Code') }}">
                    <i data-lucide="github" class="w-3.5 h-3.5"></i>
                    <span>{{ __('Code') }}</span>
                  </a>
                  @endif

                  @if($project->demo_url)
                  <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all" title="{{ __('Demo') }}">
                    <i data-lucide="external-link" class="w-3.5 h-3.5 text-sky-500"></i>
                    <span>{{ __('Demo') }}</span>
                  </a>
                  @endif
                </div>
              </div>
            </div>

          </article>
        @endforeach

        <!-- No Match Empty Filter State -->
        <div id="noFilterMatch" class="hidden lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-10 text-center space-y-3 shadow-sm">
          <div class="w-12 h-12 mx-auto rounded-2xl bg-sky-50 dark:bg-sky-500/15 text-sky-500 flex items-center justify-center">
            <i data-lucide="filter-x" class="w-6 h-6"></i>
          </div>
          <h3 class="font-display text-lg font-bold text-slate-900 dark:text-white" data-i18n="empty_filter_title">{{ __('No projects found') }}</h3>
          <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto" data-i18n="empty_filter_desc">
            {{ $isVi ? 'Không có dự án nào phù hợp với bộ lọc công nghệ hoặc từ khóa tìm kiếm hiện tại.' : 'No projects matched the selected technology filter or search query.' }}
          </p>
          <div>
            <button type="button" onclick="resetProjFilter()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-sky-500 text-white text-xs font-semibold hover:bg-sky-600 transition-all shadow-sm">
              <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
              <span data-i18n="filter_reset">{{ __('All Stacks') }}</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      @if($projects->hasPages())
      <nav class="mt-8 flex items-center justify-center gap-2 flex-wrap" aria-label="Pagination">
        @if(!$projects->onFirstPage())
          <a href="{{ $projects->previousPageUrl() }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all shadow-sm" aria-label="{{ __('Previous') }}">&lsaquo;</a>
        @endif

        @foreach($projects->links()->elements as $element)
          @if(is_string($element))
            <span class="px-3 py-2 text-xs font-mono text-slate-400">{{ $element }}</span>
          @elseif(is_array($element))
            @foreach($element as $page => $url)
              @if($projects->currentPage() == $page)
                <span class="px-3.5 py-2 rounded-xl bg-sky-500 text-white text-xs font-mono font-bold shadow-md shadow-sky-500/20" aria-current="page">{{ $page }}</span>
              @else
                <a href="{{ $url }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all shadow-sm">{{ $page }}</a>
              @endif
            @endforeach
          @endif
        @endforeach

        @if($projects->hasMorePages())
          <a href="{{ $projects->nextPageUrl() }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all shadow-sm" aria-label="{{ __('Next') }}">&rsaquo;</a>
        @endif
      </nav>
      @endif
    </section>

    @else
    <!-- Empty Database State -->
    <section class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-12 text-center space-y-3 shadow-sm">
      <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center">
        <i data-lucide="folder-code" class="w-6 h-6"></i>
      </div>
      <h3 class="font-display text-lg font-bold text-slate-900 dark:text-white">{{ __('No projects yet') }}</h3>
      <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Check back soon!') }}</p>
    </section>
    @endif

@endsection

@section('scripts')
  <script>
    let activeTechFilter = 'all';
    const totalProjectsInArchive = {{ $projects->total() }};

    window.PAGE_I18N = {
      en: {
        nav_all_projects: 'Archive',
        archive_kicker: 'ARCHIVE // SOFTWARE & SYSTEMS',
        archive_title_1: 'Engineered Systems &',
        archive_title_highlight: 'Production Archive',
        archive_subtitle: "Things I've built and worked on — spanning RAG AI pipelines, Spring Boot microservices, Laravel SSR platforms, and enterprise Odoo workflows.",
        stat_total: 'Projects',
        stat_featured: 'Featured',
        stat_stacks: 'Stacks',
        filter_all: 'All Stacks',
        badge_featured: 'Featured',
        btn_case_study: 'View Case Study',
        empty_filter_title: 'No projects found',
        empty_filter_desc: 'No projects matched the selected technology filter or search query.',
        filter_reset: 'Reset Filter',
        search_placeholder: 'Search project or stack...'
      },
      vi: {
        nav_all_projects: 'Kho Dự án',
        archive_kicker: 'KHO LƯU TRỮ // HỆ THỐNG & PHẦN MỀM',
        archive_title_1: 'Các Hệ thống &',
        archive_title_highlight: 'Dự án Đã phát triển',
        archive_subtitle: 'Tổng hợp các sản phẩm thực tế đã triển khai — bao gồm pipeline RAG AI, microservice Spring Boot, nền tảng Laravel SSR và hệ thống doanh nghiệp Odoo.',
        stat_total: 'Dự án',
        stat_featured: 'Nổi bật',
        stat_stacks: 'Công nghệ',
        filter_all: 'Tất cả công nghệ',
        badge_featured: 'Nổi bật',
        btn_case_study: 'Xem chi tiết dự án',
        empty_filter_title: 'Không tìm thấy dự án',
        empty_filter_desc: 'Không có dự án nào phù hợp với bộ lọc công nghệ hoặc từ khóa tìm kiếm hiện tại.',
        filter_reset: 'Xóa bộ lọc',
        search_placeholder: 'Tìm dự án hoặc công nghệ...'
      }
    };

    function applyProjectFilters() {
      const searchInput = document.getElementById('projectSearchInput');
      const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
      let visibleCount = 0;

      document.querySelectorAll('[data-proj-techs]').forEach(card => {
        const techs = card.getAttribute('data-proj-techs').split('|');
        const searchBlob = card.getAttribute('data-proj-search') || '';
        const techMatch = activeTechFilter === 'all' || techs.includes(activeTechFilter);
        const searchMatch = query === '' || searchBlob.includes(query);
        const show = techMatch && searchMatch;

        card.classList.toggle('hidden', !show);
        if (show) visibleCount++;
      });

      const counterEl = document.getElementById('visibleProjectsCount');
      if (counterEl) {
        counterEl.textContent = `${visibleCount} / ${totalProjectsInArchive}`;
      }

      const noMatchEl = document.getElementById('noFilterMatch');
      if (noMatchEl) {
        noMatchEl.classList.toggle('hidden', visibleCount > 0);
      }
    }

    function setActiveFilterStyles(activeBtn) {
      document.querySelectorAll('[data-proj-filter]').forEach(btn => {
        const isActive = btn === activeBtn;
        btn.className = isActive
          ? 'proj-filter-btn px-3.5 py-2 rounded-xl text-xs font-mono font-semibold bg-sky-500 text-white shadow-lg shadow-sky-500/20 whitespace-nowrap flex items-center gap-1.5 transition-all'
          : 'proj-filter-btn px-3.5 py-2 rounded-xl text-xs font-mono font-semibold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:border-sky-400 whitespace-nowrap flex items-center gap-1.5 shadow-sm transition-all';
      });
    }

    function resetProjFilter() {
      activeTechFilter = 'all';
      const searchInput = document.getElementById('projectSearchInput');
      if (searchInput) searchInput.value = '';
      const allBtn = document.querySelector('[data-proj-filter="all"]');
      if (allBtn) setActiveFilterStyles(allBtn);
      applyProjectFilters();
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('[data-proj-filter]').forEach(btn => {
        btn.addEventListener('click', () => {
          activeTechFilter = btn.getAttribute('data-proj-filter');
          setActiveFilterStyles(btn);
          applyProjectFilters();
        });
      });

      const searchInput = document.getElementById('projectSearchInput');
      if (searchInput) {
        searchInput.addEventListener('input', applyProjectFilters);
      }
    });
  </script>
@endsection
