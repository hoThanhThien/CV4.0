@php
    $isVi = app()->getLocale() === 'vi';
    $allProjectsForMeta = \App\Models\Project::with('technologies')->get();
    $allTechNames = $allProjectsForMeta->flatMap->technologies->pluck('name')->countBy()->sortDesc()->take(10)->keys()->all();
    $featuredCount = $allProjectsForMeta->where('featured', true)->count();

    $techIconMap = [
        'laravel' => 'devicon-laravel-original colored',
        'blade' => 'devicon-laravel-original colored',
        'php' => 'devicon-php-plain colored',
        'java' => 'devicon-java-plain colored',
        'spring boot' => 'devicon-spring-original colored',
        'spring' => 'devicon-spring-original colored',
        'python' => 'devicon-python-plain colored',
        'django' => 'devicon-django-plain text-emerald-600 dark:text-emerald-400',
        'fastapi' => 'devicon-fastapi-plain colored',
        'react' => 'devicon-react-original colored',
        'reactjs' => 'devicon-react-original colored',
        'nextjs' => 'devicon-nextjs-plain text-slate-900 dark:text-white',
        'next.js' => 'devicon-nextjs-plain text-slate-900 dark:text-white',
        'tailwind css' => 'devicon-tailwindcss-original colored',
        'tailwindcss' => 'devicon-tailwindcss-original colored',
        'bootstrap' => 'devicon-bootstrap-plain colored',
        'mysql' => 'devicon-mysql-original colored',
        'postgresql' => 'devicon-postgresql-plain colored',
        'postgres' => 'devicon-postgresql-plain colored',
        'sqlite' => 'devicon-sqlite-plain colored',
        'sql server' => 'devicon-microsoftsqlserver-plain colored',
        'docker' => 'devicon-docker-plain colored',
        'docker & docker compose' => 'devicon-docker-plain colored',
        'javascript' => 'devicon-javascript-plain colored',
        'javascript (es6+)' => 'devicon-javascript-plain colored',
        'typescript' => 'devicon-typescript-plain colored',
        'html' => 'devicon-html5-plain colored',
        'html5' => 'devicon-html5-plain colored',
        'css' => 'devicon-css3-plain colored',
        'css3' => 'devicon-css3-plain colored',
        'wordpress' => 'devicon-wordpress-plain colored',
        'figma' => 'devicon-figma-plain colored',
        'git' => 'devicon-git-plain colored',
        'git & github' => 'devicon-git-plain colored',
        'nginx' => 'devicon-nginx-original colored',
        'linux' => 'devicon-linux-plain text-slate-900 dark:text-slate-100',
        'firebase' => 'devicon-firebase-plain colored',
        'aws (ec2 / s3)' => 'devicon-amazonwebservices-plain-wordmark colored',
        'odoo' => 'custom:odoo',
        'odoo erp' => 'custom:odoo',
        'rag' => 'custom:rag',
        'rag ai' => 'custom:rag',
        'ai' => 'custom:rag',
        'llm' => 'custom:llm',
        'vector search' => 'custom:vector',
        'chroma' => 'custom:vector',
        'chromadb' => 'custom:vector',
        'jwt' => 'custom:jwt',
        'jwt authentication' => 'custom:jwt',
        'cpanel' => 'custom:cpanel',
    ];

    $renderTechIcon = static function (string $name) use ($techIconMap): string {
        $key = $techIconMap[strtolower($name)] ?? null;
        if (!$key) {
            return '';
        }

        return match ($key) {
            'custom:odoo' => '<svg viewBox="0 7.2 24 9.8" class="w-4 h-3 text-[#714B67] dark:text-[#C58FB7] flex-shrink-0" fill="currentColor" aria-hidden="true"><path d="M21.1002 15.7957c-1.6015 0-2.8997-1.2983-2.8997-2.8998s1.2983-2.8997 2.8997-2.8997c1.6015 0 2.8998 1.2982 2.8998 2.8997 0 1.5999-1.2979 2.8998-2.8998 2.8998zm0-1.2c.9388.0006 1.7003-.7601 1.7008-1.6989.0004-.9388-.7602-1.7003-1.699-1.7007h-.0018c-.9388.0004-1.6994.7619-1.699 1.7007.0005.9381.761 1.6985 1.699 1.699zm-6.0655 1.2c-1.6014 0-2.8997-1.2983-2.8997-2.8998s1.2983-2.8997 2.8997-2.8997c1.6015 0 2.8998 1.2982 2.8998 2.8997 0 1.5999-1.2999 2.8998-2.8998 2.8998zm0-1.2c.9389.0006 1.7003-.7601 1.7008-1.6989.0005-.9388-.7602-1.7003-1.699-1.7007h-.0018c-.9388.0004-1.6994.7619-1.699 1.7007.0005.9381.761 1.6985 1.699 1.699zM11.865 12.858c0 1.6199-1.2979 2.9378-2.8977 2.9378s-2.8998-1.314-2.8998-2.9358 1.1799-2.8597 2.8998-2.8597c.6359 0 1.2239.134 1.6998.484v-1.68a.6.6 0 0 1 1.2 0v4.0537h-.002zm-2.8977 1.7399c.9388.0005 1.7002-.7602 1.7007-1.699.0005-.9388-.7602-1.7003-1.699-1.7007h-.0017c-.9389.0004-1.6995.7619-1.699 1.7007.0004.9381.7608 1.6985 1.699 1.699zm-6.0675 1.1979C1.2983 15.7957 0 14.4974 0 12.8959s1.2983-2.8997 2.8998-2.8997 2.8997 1.2982 2.8997 2.8997c0 1.5999-1.2999 2.8998-2.8997 2.8998zm0-1.2c.9388.0006 1.7002-.7601 1.7007-1.699.0005-.9387-.7602-1.7002-1.699-1.7006h-.0017c-.9388.0004-1.6995.7619-1.699 1.7007.0004.9381.7608 1.6985 1.699 1.699z"/></svg>',
            'custom:rag' => '<svg viewBox="0 0 24 24" class="w-3.5 h-3.5 flex-shrink-0" fill="none" aria-hidden="true"><path d="M4.5 3.5h8.2L17 7.8v3.8M4.5 3.5v17a1.5 1.5 0 001.5 1.5h4.2M4.5 3.5A1.5 1.5 0 003 5v14.5" stroke="#0EA5E9" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.6 11.2l1.25 3.25 3.25 1.25-3.25 1.25-1.25 3.25-1.25-3.25-3.25-1.25 3.25-1.25 1.25-3.25z" fill="#6366F1"/></svg>',
            'custom:vector' => '<svg viewBox="0 0 24 24" class="w-3.5 h-3.5 flex-shrink-0" fill="none" aria-hidden="true"><rect x="3" y="6.5" width="9" height="11" rx="4.5" fill="#F43F5E" fill-opacity="0.9"/><rect x="7.5" y="6.5" width="9" height="11" rx="4.5" fill="#F59E0B" fill-opacity="0.9"/><rect x="12" y="6.5" width="9" height="11" rx="4.5" fill="#0EA5E9" fill-opacity="0.9"/></svg>',
            'custom:llm' => '<svg viewBox="0 0 24 24" class="w-3.5 h-3.5 flex-shrink-0" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="8.5" stroke="#6366F1" stroke-width="1.8" stroke-dasharray="3 2.5"/><circle cx="12" cy="5.5" r="2" fill="#0EA5E9"/><circle cx="6.5" cy="16" r="2" fill="#6366F1"/><circle cx="17.5" cy="16" r="2" fill="#10B981"/><circle cx="12" cy="12" r="2.3" fill="#10B981"/></svg>',
            'custom:jwt' => '<img src="https://cdn.simpleicons.org/jsonwebtokens/D63AFF?viewbox=auto" alt="JWT" class="w-3.5 h-3.5 object-contain flex-shrink-0" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'inline-block\';"><svg viewBox="0 0 24 24" class="w-3.5 h-3.5 hidden flex-shrink-0" fill="none" aria-hidden="true"><path d="M12 2.5L4.5 5.8v5.9c0 5.1 3.2 9.4 7.5 10.8 4.3-1.4 7.5-5.7 7.5-10.8V5.8L12 2.5z" fill="#D63AFF" fill-opacity="0.2" stroke="#D63AFF" stroke-width="1.8"/></svg>',
            'custom:cpanel' => '<img src="https://cdn.simpleicons.org/cpanel/FF6C2C?viewbox=auto" alt="cPanel" class="w-3.5 h-3.5 object-contain flex-shrink-0" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'inline-block\';"><svg viewBox="0 0 24 24" class="w-3.5 h-3.5 hidden flex-shrink-0 text-[#FF6C2C]" fill="currentColor" aria-hidden="true"><rect x="3" y="4" width="18" height="7" rx="2"/><rect x="3" y="13" width="18" height="7" rx="2"/></svg>',
            default => '<i class="' . e($key) . ' text-xs"></i>',
        };
    };
@endphp
<!DOCTYPE html>
<html lang="{{ $isVi ? 'vi' : 'en' }}" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('Projects') }} | {{ $isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien' }}</title>
  <meta name="description" content="{{ $isVi ? 'Khám phá danh sách các dự án, sản phẩm phần mềm và kiến trúc hệ thống hiện đại được phát triển bởi Kỹ sư Hồ Thành Thiện.' : 'Explore a curated portfolio of modern web applications, AI/RAG architectures, and enterprise systems developed by Ho Thanh Thien.' }}">
  <meta name="keywords" content="{{ $isVi ? 'Dự án Hồ Thành Thiện, Portfolio Dự án, Web Apps, Laravel, Spring Boot, RAG AI, TourRist, Laundry' : 'Ho Thanh Thien Projects, Software Portfolio, Laravel Web Apps, Spring Boot, RAG AI Architecture' }}">
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">

  <!-- Google Fonts: Space Grotesk, Inter, JetBrains Mono -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Devicon CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            display: ['Space Grotesk', 'Inter', 'system-ui', 'sans-serif'],
            mono: ['JetBrains Mono', 'monospace'],
          },
          animation: {
            'blob': 'blob 10s infinite alternate',
          },
          keyframes: {
            blob: {
              '0%': { transform: 'translate(0px, 0px) scale(1)' },
              '33%': { transform: 'translate(35px, -45px) scale(1.08)' },
              '66%': { transform: 'translate(-25px, 25px) scale(0.94)' },
              '100%': { transform: 'translate(0px, 0px) scale(1)' },
            }
          }
        }
      }
    };
    if (localStorage.getItem('thien_theme') === 'dark') {
      document.documentElement.classList.add('dark');
    }
  </script>

  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
    :root {
      --mouse-x: -999px;
      --mouse-y: -999px;
    }

    .bg-tech-grid {
      background-color: #f8fafc;
      background-image:
        linear-gradient(to right, rgba(15, 23, 42, 0.045) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(15, 23, 42, 0.045) 1px, transparent 1px);
      background-size: 38px 38px;
      transition: background-color 0.35s ease, color 0.35s ease;
    }
    html.dark .bg-tech-grid {
      background-color: #06090F;
      background-image:
        linear-gradient(to right, rgba(148, 163, 184, 0.06) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(148, 163, 184, 0.06) 1px, transparent 1px);
    }

    .frosted-glass {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
    }
    html.dark .frosted-glass {
      background: rgba(11, 17, 30, 0.85);
    }

    .spotlight-card {
      position: relative;
      overflow: hidden;
      transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease, box-shadow 0.25s ease;
    }
    .spotlight-card::before {
      content: "";
      position: absolute;
      inset: 0;
      pointer-events: none;
      background: radial-gradient(
        520px circle at var(--mouse-x) var(--mouse-y),
        rgba(14, 165, 233, 0.09),
        transparent 45%
      );
      opacity: 0;
      transition: opacity 0.25s ease;
      z-index: 1;
    }
    html.dark .spotlight-card::before {
      background: radial-gradient(
        520px circle at var(--mouse-x) var(--mouse-y),
        rgba(56, 189, 248, 0.14),
        transparent 45%
      );
    }
    .spotlight-card:hover::before {
      opacity: 1;
    }

    .projects-filter-bar::-webkit-scrollbar {
      height: 5px;
    }
    .projects-filter-bar::-webkit-scrollbar-thumb {
      background: rgba(148, 163, 184, 0.35);
      border-radius: 9999px;
    }
  </style>
</head>
<body class="bg-tech-grid text-slate-900 dark:text-slate-100 antialiased selection:bg-sky-500/20 selection:text-sky-700 dark:selection:text-sky-300 relative overflow-x-clip min-h-screen flex flex-col">

  <!-- Ambient Pastel Blobs -->
  <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="absolute -top-24 -left-20 w-96 h-96 bg-sky-300/30 dark:bg-sky-600/15 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl animate-blob"></div>
    <div class="absolute top-1/3 -right-24 w-[420px] h-[420px] bg-indigo-300/25 dark:bg-indigo-600/15 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl animate-blob" style="animation-delay: 2.5s;"></div>
    <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-emerald-200/30 dark:bg-emerald-600/10 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl animate-blob" style="animation-delay: 5s;"></div>
  </div>

  <!-- =========================================================================
       NAVIGATION HEADER (SYNCHRONIZED WITH HOMEPAGE PEARL WHITE + DARK TOGGLE)
  ========================================================================= -->
  <header class="sticky top-0 z-50 frosted-glass border-b border-slate-200/80 dark:border-slate-800/80 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">

      <!-- Brand Identity -->
      <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-display font-extrabold text-lg tracking-tight text-slate-900 dark:text-white group">
        <span class="w-9 h-9 rounded-xl overflow-hidden bg-gradient-to-br from-sky-500 to-indigo-600 shadow-md shadow-indigo-500/20 ring-1 ring-slate-900/5 dark:ring-white/15 flex-shrink-0 inline-flex items-center justify-center group-hover:scale-105 transition-transform">
          <img src="{{ asset('android-chrome-192x192.png') }}" alt="Ho Thanh Thien Logo" class="w-full h-full object-cover scale-[1.12]">
        </span>
        <span class="hidden sm:inline" data-i18n="brand_name">{{ $isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien' }}</span>
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" title="Available for Hire"></span>
      </a>

      <!-- Center Nav Links -->
      <nav class="hidden md:flex items-center gap-1 text-sm font-medium text-slate-600 dark:text-slate-300">
        <a href="{{ route('home') }}#projects" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_projects">Selected Work</a>
        <a href="{{ route('home') }}#skills" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_skills">Tech Stack</a>
        <a href="{{ route('home') }}#credentials" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_credentials">Credentials</a>
        <a href="{{ route('home') }}#blog" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_blog">Engineering Blog</a>
        <a href="{{ route('projects.index') }}" class="px-3 py-1.5 rounded-lg bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-semibold border border-sky-200/80 dark:border-sky-500/30 transition-colors" data-i18n="nav_all_projects" aria-current="page">Archive</a>
      </nav>

      <!-- Right Controls: Language Switcher + Dark Mode Toggle + CTA -->
      <div class="flex items-center gap-2">
        <div class="inline-flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700 font-mono text-xs">
          <button type="button" id="btnLangEn" onclick="setLanguage('en')" class="px-2.5 py-1 rounded-lg font-bold transition-all">EN</button>
          <button type="button" id="btnLangVi" onclick="setLanguage('vi')" class="px-2.5 py-1 rounded-lg font-bold transition-all">VI</button>
        </div>

        <button type="button" id="themeToggleBtn" onclick="toggleTheme()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all shadow-sm">
          <i data-lucide="moon" id="themeIconDark" class="w-3.5 h-3.5 text-indigo-500"></i>
          <i data-lucide="sun" id="themeIconLight" class="w-3.5 h-3.5 text-amber-400 hidden"></i>
          <span id="themeToggleLabel" class="hidden sm:inline">Dark BG</span>
        </button>

        <a href="{{ route('contact') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-semibold hover:bg-slate-800 dark:hover:bg-sky-600 transition-all shadow-sm" data-i18n="nav_hire">
          Hire Me
        </a>

        <button type="button" id="mobileMenuBtn" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200" aria-label="Menu">
          <i data-lucide="menu" class="w-4 h-4"></i>
        </button>
      </div>

    </div>

    <!-- Mobile Dropdown Navigation -->
    <div id="mobileMenuPanel" class="hidden md:hidden border-t border-slate-200/80 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-4 py-3 space-y-1 text-sm font-medium text-slate-700 dark:text-slate-200">
      <a href="{{ route('home') }}#projects" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_projects">Selected Work</a>
      <a href="{{ route('home') }}#skills" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_skills">Tech Stack</a>
      <a href="{{ route('home') }}#credentials" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_credentials">Credentials</a>
      <a href="{{ route('home') }}#blog" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_blog">Engineering Blog</a>
      <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-xl bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-semibold" data-i18n="nav_all_projects">Archive</a>
      <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-center font-semibold mt-2" data-i18n="nav_hire">Hire Me</a>
    </div>
  </header>

  <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10">

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
          <div class="grid grid-cols-3 gap-2.5">
            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
              <div class="font-display text-2xl font-extrabold text-sky-600 dark:text-sky-400">{{ $projects->total() }}</div>
              <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="stat_total">Projects</div>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
              <div class="font-display text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $featuredCount }}</div>
              <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="stat_featured">Featured</div>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
              <div class="font-display text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ count($allTechNames) }}+</div>
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
            <input type="text" id="projectSearchInput" placeholder="Search project or stack..." class="w-full pl-9 pr-3 py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-xs font-mono text-slate-700 dark:text-slate-200 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 shadow-sm">
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
              $searchBlob = strtolower($project->title . ' ' . $project->summary . ' ' . $project->technologies->pluck('name')->implode(' ') . ' ' . ($project->role ?? ''));
              $isPortfolio = $project->id === 1 || str_contains(strtolower($project->title), 'portfolio');
              $isTourrist = $project->id === 2 || str_contains(strtolower($project->title), 'tourrist');
              $isLaundry = $project->id === 3 || str_contains(strtolower($project->title), 'laundry');
              $hasCustomImage = $project->has_real_image;
          @endphp

          <article class="project-card spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden flex flex-col justify-between" data-proj-techs="{{ $techSlugList }}" data-proj-search="{{ e($searchBlob) }}">

            <!-- Top Visual Stage -->
            <div class="p-4 pb-0 relative z-10">
              <a href="{{ route('projects.show', $project->id) }}" class="group block relative aspect-[16/10] rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-slate-950" aria-label="{{ $project->title }}">

                @if($project->featured)
                  <span class="absolute top-3 right-3 z-20 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-emerald-200 dark:border-emerald-500/40 text-emerald-700 dark:text-emerald-300 font-mono text-[10px] font-bold uppercase tracking-wider shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span data-i18n="badge_featured">{{ __('Featured') }}</span>
                  </span>
                @endif

                @if($hasCustomImage)
                  <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                  <div class="hidden w-full h-full bg-slate-950 text-slate-100 p-5 flex-col justify-between">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2.5 font-mono text-[11px] text-slate-400">
                      <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500"></span><span class="w-2 h-2 rounded-full bg-amber-400"></span><span class="w-2 h-2 rounded-full bg-emerald-500"></span></span>
                      <span class="truncate">{{ $hostLabel }}</span>
                    </div>
                    <div class="font-display font-bold text-lg text-white">{{ $project->title }}</div>
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
                    {{ $project->title }}
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

  </main>

  <!-- =========================================================================
       FOOTER (MATCHING HOMEPAGE PEARL WHITE + DARK TOGGLE)
  ========================================================================= -->
  <footer class="border-t border-slate-200/80 dark:border-slate-800/80 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
      <div class="space-y-2">
        <div class="flex items-center gap-2.5 font-display font-extrabold text-lg text-slate-900 dark:text-white">
          <span class="w-8 h-8 rounded-[10px] overflow-hidden bg-gradient-to-br from-sky-500 to-indigo-600 shadow-sm shadow-indigo-500/20 ring-1 ring-slate-900/5 dark:ring-white/15 flex-shrink-0 inline-flex items-center justify-center">
            <img src="{{ asset('android-chrome-192x192.png') }}" alt="Hồ Thành Thiện Logo" class="w-full h-full object-cover scale-[1.12]">
          </span>
          <span data-i18n="brand_name">{{ $isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien' }}</span>
        </div>
        <p class="text-xs font-mono text-slate-500 dark:text-slate-400" data-i18n="footer_subtitle">
          Full-Stack Web Systems &amp; Applied RAG AI Engineer &bull; Ho Chi Minh City, Vietnam
        </p>
      </div>

      <div class="flex flex-wrap items-center gap-4 text-xs font-mono text-slate-600 dark:text-slate-300">
        <a href="mailto:hothanhthien119@gmail.com" class="inline-flex items-center gap-1.5 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
          <i data-lucide="mail" class="w-3.5 h-3.5 text-sky-500"></i>
          <span>hothanhthien119@gmail.com</span>
        </a>
        <a href="tel:+84375227764" class="inline-flex items-center gap-1.5 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
          <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-500"></i>
          <span>+84 375 227 764</span>
        </a>
        <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
          <i data-lucide="github" class="w-3.5 h-3.5"></i>
          <span>GitHub</span>
        </a>
      </div>
    </div>
  </footer>

  <!-- =========================================================================
       VANILLA JS ENGINE (THEME, EN/VI SYNC, SPOTLIGHT, FILTER & SEARCH)
  ========================================================================= -->
  <script>
    let currentLang = "{{ $isVi ? 'vi' : 'en' }}";
    let activeTechFilter = 'all';
    const totalProjectsInArchive = {{ $projects->total() }};

    // 1. Spotlight Cursor Glow
    document.addEventListener('mousemove', (e) => {
      document.querySelectorAll('.spotlight-card').forEach(card => {
        const rect = card.getBoundingClientRect();
        card.style.setProperty('--mouse-x', `${e.clientX - rect.left}px`);
        card.style.setProperty('--mouse-y', `${e.clientY - rect.top}px`);
      });
    });

    // 2. White BG / Dark BG Toggle
    function toggleTheme() {
      const isDark = document.documentElement.classList.toggle('dark');
      localStorage.setItem('thien_theme', isDark ? 'dark' : 'light');
      updateThemeUI(isDark);
    }

    function updateThemeUI(isDark) {
      const iconDark = document.getElementById('themeIconDark');
      const iconLight = document.getElementById('themeIconLight');
      const label = document.getElementById('themeToggleLabel');
      if (iconDark) iconDark.classList.toggle('hidden', isDark);
      if (iconLight) iconLight.classList.toggle('hidden', !isDark);
      if (label) {
        label.textContent = isDark
          ? (currentLang === 'vi' ? 'Nền Trắng' : 'White BG')
          : (currentLang === 'vi' ? 'Nền Tối' : 'Dark BG');
      }
    }

    // 3. Mobile Nav Toggle
    function toggleMobileMenu() {
      const panel = document.getElementById('mobileMenuPanel');
      if (panel) panel.classList.toggle('hidden');
    }

    // 4. EN / VI UI Dictionary
    const I18N = {
      en: {
        brand_name: 'Ho Thanh Thien',
        nav_projects: 'Selected Work',
        nav_skills: 'Tech Stack',
        nav_credentials: 'Credentials',
        nav_blog: 'Engineering Blog',
        nav_all_projects: 'Archive',
        nav_hire: 'Hire Me',
        breadcrumb_home: 'Home',
        archive_kicker: 'ARCHIVE // SOFTWARE & SYSTEMS',
        archive_title_1: 'Engineered Systems &',
        archive_title_highlight: 'Production Archive',
        archive_subtitle: "Things I've built and worked on — spanning RAG AI pipelines, Spring Boot microservices, Laravel SSR platforms, and enterprise Odoo workflows.",
        stat_total: 'Projects',
        stat_featured: 'Featured',
        stat_stacks: 'Stacks',
        btn_back_home: 'Overview',
        btn_brochure: 'Brochure PDF',
        filter_all: 'All Stacks',
        badge_featured: 'Featured',
        btn_case_study: 'View Case Study',
        empty_filter_title: 'No projects found',
        empty_filter_desc: 'No projects matched the selected technology filter or search query.',
        filter_reset: 'Reset Filter',
        footer_subtitle: 'Full-Stack Web Systems & Applied RAG AI Engineer • Ho Chi Minh City, Vietnam',
        search_placeholder: 'Search project or stack...'
      },
      vi: {
        brand_name: 'Hồ Thành Thiện',
        nav_projects: 'Dự án Nổi bật',
        nav_skills: 'Kỹ năng',
        nav_credentials: 'Bằng cấp & PDF',
        nav_blog: 'Blog Kỹ thuật',
        nav_all_projects: 'Kho Dự án',
        nav_hire: 'Liên hệ ngay',
        breadcrumb_home: 'Trang chủ',
        archive_kicker: 'KHO LƯU TRỮ // HỆ THỐNG & PHẦN MỀM',
        archive_title_1: 'Các Hệ thống &',
        archive_title_highlight: 'Dự án Đã phát triển',
        archive_subtitle: 'Tổng hợp các sản phẩm thực tế đã triển khai — bao gồm pipeline RAG AI, microservice Spring Boot, nền tảng Laravel SSR và hệ thống doanh nghiệp Odoo.',
        stat_total: 'Dự án',
        stat_featured: 'Nổi bật',
        stat_stacks: 'Công nghệ',
        btn_back_home: 'Trang chủ',
        btn_brochure: 'Brochure PDF',
        filter_all: 'Tất cả công nghệ',
        badge_featured: 'Nổi bật',
        btn_case_study: 'Xem chi tiết dự án',
        empty_filter_title: 'Không tìm thấy dự án',
        empty_filter_desc: 'Không có dự án nào phù hợp với bộ lọc công nghệ hoặc từ khóa tìm kiếm hiện tại.',
        filter_reset: 'Xóa bộ lọc',
        footer_subtitle: 'Kỹ sư Hệ thống Web Full-Stack & Ứng dụng RAG AI • TP. Hồ Chí Minh, Việt Nam',
        search_placeholder: 'Tìm dự án hoặc công nghệ...'
      }
    };

    function setLanguage(lang) {
      currentLang = lang;
      localStorage.setItem('thien_lang', lang);

      const btnEn = document.getElementById('btnLangEn');
      const btnVi = document.getElementById('btnLangVi');
      if (btnEn && btnVi) {
        btnEn.className = lang === 'en'
          ? 'px-2.5 py-1 rounded-lg font-bold bg-white dark:bg-slate-700 text-sky-600 dark:text-sky-300 shadow-sm'
          : 'px-2.5 py-1 rounded-lg font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white';
        btnVi.className = lang === 'vi'
          ? 'px-2.5 py-1 rounded-lg font-bold bg-white dark:bg-slate-700 text-sky-600 dark:text-sky-300 shadow-sm'
          : 'px-2.5 py-1 rounded-lg font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white';
      }

      const dict = I18N[lang] || I18N.en;
      document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (dict[key]) {
          el.textContent = dict[key];
        }
      });

      const searchInput = document.getElementById('projectSearchInput');
      if (searchInput && dict.search_placeholder) {
        searchInput.placeholder = dict.search_placeholder;
      }

      updateThemeUI(document.documentElement.classList.contains('dark'));
      try { fetch(`/lang/${lang}`, { method: 'GET', credentials: 'same-origin' }); } catch (e) {}
    }

    // 5. Combined Tech Stack Filter & Live Search
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

      setLanguage(currentLang);
      if (window.lucide) lucide.createIcons();
    });
  </script>
</body>
</html>
