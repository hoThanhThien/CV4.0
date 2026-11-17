<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'vi' ? 'vi' : 'en' }}" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ app()->getLocale() === 'vi' ? 'Hồ Thành Thiện — Kỹ sư Phần mềm Full-Stack & RAG AI' : 'Ho Thanh Thien — Full-Stack & RAG AI Engineer' }}</title>
  <meta name="description" content="{{ app()->getLocale() === 'vi' ? 'Portfolio chính thức của Kỹ sư Phần mềm Hồ Thành Thiện (UTH - Loại Xuất sắc, GPA 4.0/4.0). Chuyên sâu Java Spring Boot, Laravel, Odoo ERP và RAG AI.' : 'Official portfolio of Software Engineer Ho Thanh Thien (B.Eng IT Distinction @ UTH, 4.0/4.0 GPA). Specialized in Java Spring Boot, Laravel, Odoo ERP & Applied RAG AI.' }}">
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
            'float': 'floatSlow 4.5s ease-in-out infinite',
            'spin-slow': 'spin 75s linear infinite',
          },
          keyframes: {
            blob: {
              '0%': { transform: 'translate(0px, 0px) scale(1)' },
              '33%': { transform: 'translate(35px, -45px) scale(1.08)' },
              '66%': { transform: 'translate(-25px, 25px) scale(0.94)' },
              '100%': { transform: 'translate(0px, 0px) scale(1)' },
            },
            floatSlow: {
              '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
              '50%': { transform: 'translateY(-10px) rotate(2deg)' },
            }
          }
        }
      }
    };
  </script>

  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
    :root {
      --radius: 168px;
      --mouse-x: -999px;
      --mouse-y: -999px;
    }
    @media (max-width: 639px) {
      :root {
        --radius: 112px;
      }
    }
    @media (max-width: 479px) {
      :root {
        --radius: 92px;
      }
    }

    /* 1. CLEAN PEARL WHITE TECHNICAL GRID & DARK MODE */
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

    /* Frosted Glass Surface */
    .frosted-glass {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
    }
    html.dark .frosted-glass {
      background: rgba(11, 17, 30, 0.85);
    }

    /* Mouse-Tracking Spotlight Glow Card */
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

    /* =========================================================================
       SKILL ANIMATION MODE 1: 3D VORTEX SPIN GRID & 180° FLIP CARD
    ========================================================================= */
    @keyframes skillVortexSpinIn {
      0% {
        opacity: 0;
        transform: rotateY(-180deg) rotateX(40deg) scale(0.28);
      }
      100% {
        opacity: 1;
        transform: rotateY(0deg) rotateX(0deg) scale(1);
      }
    }

    @keyframes iconSpin360 {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .skill-grid-item {
      perspective: 1100px;
      height: 152px;
      opacity: 0;
      animation: skillVortexSpinIn 0.65s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
      animation-delay: calc(var(--index, 0) * 55ms);
      transition: transform 0.25s ease, opacity 0.22s ease;
    }
    .skill-grid-item.is-exiting {
      opacity: 0 !important;
      transform: rotateY(90deg) scale(0.65) !important;
      animation: none !important;
    }
    .skill-grid-item .spin-icon-target {
      animation: iconSpin360 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
      animation-delay: calc(var(--index, 0) * 55ms);
    }

    .grid-flip-inner {
      position: relative;
      width: 100%;
      height: 100%;
      transform-style: preserve-3d;
      transition: transform 0.62s cubic-bezier(0.16, 1, 0.3, 1);
      cursor: pointer;
    }
    .skill-grid-item:hover .grid-flip-inner,
    .skill-grid-item.is-flipped .grid-flip-inner {
      transform: rotateY(180deg);
    }
    .grid-flip-front, .grid-flip-back {
      position: absolute;
      inset: 0;
      backface-visibility: hidden;
      -webkit-backface-visibility: hidden;
      border-radius: 1rem;
    }
    .grid-flip-back {
      transform: rotateY(180deg);
    }

    /* =========================================================================
       SKILL ANIMATION MODE 2: 3D ORBIT WHEEL (EXACT VORTEX + CONTINUOUS ORBIT)
    ========================================================================= */
    @keyframes orbitNodeEmerge {
      0% {
        opacity: 0;
        transform: translate(-50%, -50%) rotate(calc(var(--angle) - 160deg)) translateY(0px) scale(0.2);
      }
      100% {
        opacity: 1;
        transform: translate(-50%, -50%) rotate(var(--angle)) translateY(calc(-1 * var(--radius))) rotate(calc(-1 * var(--angle))) scale(1);
      }
    }
    @keyframes orbitStageRevolve {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    @keyframes orbitNodeCounterRevolve {
      from { transform: rotate(0deg); }
      to { transform: rotate(-360deg); }
    }

    .orbit-spinning-ring {
      transform-origin: 50% 50%;
      animation: orbitStageRevolve 32s linear infinite;
    }
    .orbit-node {
      position: absolute;
      top: 50%;
      left: 50%;
      opacity: 0;
      animation: orbitNodeEmerge 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
      user-select: none;
      cursor: pointer;
      pointer-events: auto;
      z-index: 20;
    }
    .orbit-node-upright {
      transform-origin: center center;
      animation: orbitNodeCounterRevolve 32s linear infinite;
    }
    .orbit-ring-counter {
      transform-origin: center center;
      animation: orbitNodeCounterRevolve 48s linear infinite;
    }
    #orbitStageBox.is-hover-paused .orbit-spinning-ring,
    #orbitStageBox.is-hover-paused .orbit-node-upright,
    #orbitStageBox.is-hover-paused .animate-spin-slow,
    #orbitStageBox.is-hover-paused .orbit-ring-counter,
    #orbitStageBox.is-manual-paused .orbit-spinning-ring,
    #orbitStageBox.is-manual-paused .orbit-node-upright,
    #orbitStageBox.is-manual-paused .animate-spin-slow,
    #orbitStageBox.is-manual-paused .orbit-ring-counter,
    #orbitStageBox:has(.orbit-node:hover) .orbit-spinning-ring,
    #orbitStageBox:has(.orbit-node:hover) .orbit-node-upright,
    #orbitStageBox:has(.orbit-node:hover) .animate-spin-slow,
    #orbitStageBox:has(.orbit-node:hover) .orbit-ring-counter {
      animation-play-state: paused;
    }
    .orbit-node-pill {
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .orbit-node:hover .orbit-node-pill,
    .orbit-node.is-active .orbit-node-pill {
      border-color: #0ea5e9;
      box-shadow: 0 10px 25px -4px rgba(14, 165, 233, 0.28), 0 0 0 2px rgba(14, 165, 233, 0.22);
      transform: scale(1.08);
    }
    .orbit-node:hover,
    .orbit-node.is-active {
      z-index: 40;
    }

    /* Terminal Typewriter Cursor */
    .cli-cursor {
      display: inline-block;
      width: 8px;
      height: 14px;
      background: #38bdf8;
      vertical-align: middle;
      margin-left: 3px;
      animation: blinkCursor 1s step-end infinite;
    }
    @keyframes blinkCursor {
      50% { opacity: 0; }
    }

    /* Spin replay icon spin */
    .replay-spinning svg {
      animation: spin 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
  </style>
</head>
<body class="bg-tech-grid text-slate-900 dark:text-slate-100 antialiased selection:bg-sky-500/20 selection:text-sky-700 dark:selection:text-sky-300 relative overflow-x-clip">

  <!-- 3 Khối Ánh Sáng Pastel Chuyển Động Chậm (Ambient Blobs) -->
  <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
    <div class="absolute -top-24 -left-20 w-96 h-96 bg-sky-300/30 dark:bg-sky-600/15 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl animate-blob"></div>
    <div class="absolute top-1/3 -right-24 w-[420px] h-[420px] bg-indigo-300/25 dark:bg-indigo-600/15 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl animate-blob" style="animation-delay: 2.5s;"></div>
    <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-emerald-200/30 dark:bg-emerald-600/10 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-3xl animate-blob" style="animation-delay: 5s;"></div>
  </div>

  <!-- =========================================================================
       NAVIGATION HEADER (CLEAN PEARL WHITE + DARK TOGGLE + EN/VI SYNC)
  ========================================================================= -->
  <header class="sticky top-0 z-50 frosted-glass border-b border-slate-200/80 dark:border-slate-800/80 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
      
      <!-- Brand Identity -->
      <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-display font-extrabold text-lg tracking-tight text-slate-900 dark:text-white group">
        <span class="w-9 h-9 rounded-xl overflow-hidden bg-gradient-to-br from-sky-500 to-indigo-600 shadow-md shadow-indigo-500/20 ring-1 ring-slate-900/5 dark:ring-white/15 flex-shrink-0 inline-flex items-center justify-center group-hover:scale-105 transition-transform">
          <img src="{{ asset('android-chrome-192x192.png') }}" alt="Ho Thanh Thien Logo" class="w-full h-full object-cover scale-[1.12]">
        </span>
        <span class="hidden sm:inline" data-i18n="brand_name">{{ app()->getLocale() === 'vi' ? 'Hồ Thành Thiện' : 'Ho Thanh Thien' }}</span>
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" title="Available for Hire"></span>
      </a>

      <!-- Center Nav Links -->
      <nav class="hidden md:flex items-center gap-1 text-sm font-medium text-slate-600 dark:text-slate-300">
        <a href="#projects" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_projects">Selected Work</a>
        <a href="#skills" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_skills">Tech Stack</a>
        <a href="#credentials" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_credentials">Credentials</a>
        <a href="#blog" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_blog">Engineering Blog</a>
        <a href="{{ route('projects.index') }}" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_all_projects">Archive</a>
      </nav>

      <!-- Right Controls: Language Switcher + Dark Mode Toggle -->
      <div class="flex items-center gap-2">
        <!-- 100% Language Sync Switcher -->
        <div class="inline-flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700 font-mono text-xs">
          <button type="button" id="btnLangEn" onclick="setLanguage('en')" class="px-2.5 py-1 rounded-lg font-bold transition-all">EN</button>
          <button type="button" id="btnLangVi" onclick="setLanguage('vi')" class="px-2.5 py-1 rounded-lg font-bold transition-all">VI</button>
        </div>

        <!-- White BG / Dark BG Toggle -->
        <button type="button" id="themeToggleBtn" onclick="toggleTheme()" class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all shadow-sm">
          <i data-lucide="moon" id="themeIconDark" class="w-3.5 h-3.5 text-indigo-500"></i>
          <i data-lucide="sun" id="themeIconLight" class="w-3.5 h-3.5 text-amber-400 hidden"></i>
          <span id="themeToggleLabel" class="hidden sm:inline">Dark BG</span>
        </button>

        <!-- Primary Contact CTA -->
        <a href="{{ route('contact') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-semibold hover:bg-slate-800 dark:hover:bg-sky-600 transition-all shadow-sm" data-i18n="nav_hire">
          Hire Me
        </a>

        <!-- Mobile Menu Toggle -->
        <button type="button" id="mobileMenuBtn" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200" aria-label="Menu">
          <i data-lucide="menu" class="w-4 h-4"></i>
        </button>
      </div>

    </div>

    <!-- Mobile Dropdown Navigation -->
    <div id="mobileMenuPanel" class="hidden md:hidden border-t border-slate-200/80 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-4 py-3 space-y-1 text-sm font-medium text-slate-700 dark:text-slate-200">
      <a href="#projects" onclick="closeMobileMenu()" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_projects">Selected Work</a>
      <a href="#skills" onclick="closeMobileMenu()" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_skills">Tech Stack</a>
      <a href="#credentials" onclick="closeMobileMenu()" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_credentials">Credentials</a>
      <a href="#blog" onclick="closeMobileMenu()" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_blog">Engineering Blog</a>
      <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_all_projects">Archive</a>
      <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-center font-semibold mt-2" data-i18n="nav_hire">Hire Me</a>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-16 space-y-16 sm:space-y-24">

    <!-- =========================================================================
         SECTION 1: HERO SECTION (SPLIT LAYOUT 7:5 — IMPRESSIVE IN 3 SECONDS)
    ========================================================================= -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
      
      <!-- Cột Trái (7 cols) -->
      <div class="lg:col-span-7 space-y-6">
        
        <!-- Status Availability Badge -->
        <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-700/80 shadow-sm text-xs font-mono font-semibold text-slate-700 dark:text-slate-200">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
          </span>
          <span data-i18n="hero_badge">Available for Full-Stack &amp; AI Engineer Roles • Ho Chi Minh City</span>
        </div>

        <!-- Giant Gradient Headline -->
        <h1 class="font-display text-[1.95rem] sm:text-5xl lg:text-[3.45rem] font-extrabold tracking-tight leading-[1.1] text-slate-900 dark:text-white">
          <span data-i18n="hero_title_1">Building scalable</span>
          <span class="bg-gradient-to-r from-sky-600 via-indigo-600 to-emerald-600 dark:from-sky-400 dark:via-indigo-400 dark:to-emerald-400 bg-clip-text text-transparent" data-i18n="hero_title_highlight">
            Web Systems &amp; RAG AI
          </span>
          <span data-i18n="hero_title_2">that solve real problems</span>
        </h1>

        <!-- Concise Verified Bio -->
        <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl" data-i18n="hero_desc">
          Honors B.Eng in Information Technology graduate at UTH with a 4.0/4.0 latest semester GPA. Hands-on experience architecting Java Spring Boot APIs, Laravel ecosystems, Odoo ERP workflows, and Python RAG/LLM chatbots.
        </p>

        <!-- 3 Verified Experience & Award Chips -->
        <div class="flex flex-wrap items-center gap-2.5 pt-1">
          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm text-xs font-semibold text-slate-700 dark:text-slate-200">
            <i data-lucide="briefcase" class="w-3.5 h-3.5 text-sky-500"></i>
            <span data-i18n="chip_aipower">Full-Stack Dev @ AIPower</span>
          </span>
          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm text-xs font-semibold text-slate-700 dark:text-slate-200">
            <i data-lucide="layers" class="w-3.5 h-3.5 text-indigo-500"></i>
            <span data-i18n="chip_enmasys">Odoo ERP @ Enmasys</span>
          </span>
          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm text-xs font-semibold text-slate-700 dark:text-slate-200">
            <i data-lucide="award" class="w-3.5 h-3.5 text-amber-500"></i>
            <span data-i18n="chip_award">2nd Prize UTH IT Logo Design</span>
          </span>
        </div>

        <!-- Đúng 2 nút CTA chính (Không chứa SĐT ở Hero) -->
        <div class="flex flex-wrap items-center gap-3.5 pt-2">
          <a href="#projects" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-slate-900 dark:bg-sky-500 text-white font-semibold text-sm hover:bg-slate-800 dark:hover:bg-sky-600 shadow-lg shadow-slate-900/15 dark:shadow-sky-500/25 hover:-translate-y-0.5 transition-all">
            <span data-i18n="cta_explore">Explore Featured Work</span>
            <i data-lucide="arrow-down-right" class="w-4 h-4"></i>
          </a>
          <a href="{{ asset('assets/docs/HoThanhThien_CV_Brochure.pdf') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 font-semibold text-sm hover:border-sky-400 hover:bg-sky-50/40 dark:hover:bg-slate-800 shadow-sm hover:-translate-y-0.5 transition-all">
            <i data-lucide="file-down" class="w-4 h-4 text-sky-600 dark:text-sky-400"></i>
            <span data-i18n="cta_brochure">Download Brochure PDF</span>
          </a>
        </div>

      </div>

      <!-- Cột Phải (5 cols): Interactive CLI Terminal + 3 Mini Bento KPI Cards -->
      <div class="lg:col-span-5 space-y-4">
        
        <!-- 1. Interactive CLI Terminal (macOS Dark Slate #0f172a) -->
        <div class="rounded-2xl bg-[#0f172a] border border-slate-800 shadow-2xl shadow-slate-900/20 overflow-hidden">
          <!-- macOS Titlebar -->
          <div class="px-4 py-3 bg-slate-900/90 border-b border-slate-800 flex items-center justify-between gap-2">
            <div class="flex items-center gap-1.5 flex-shrink-0">
              <span class="w-3 h-3 rounded-full bg-rose-500"></span>
              <span class="w-3 h-3 rounded-full bg-amber-400"></span>
              <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
            </div>
            <span class="font-mono text-[10px] sm:text-[11px] text-slate-400 truncate">thien@hothanhthien.io.vn — zsh</span>
            <span class="hidden sm:inline-block w-10"></span>
          </div>

          <!-- 4 Interactive Command Triggers -->
          <div class="px-4 py-2.5 bg-slate-900/50 border-b border-slate-800/80 flex flex-wrap gap-1.5">
            <button type="button" data-cli-key="whoami" onclick="runTerminalCmd('whoami')" class="cli-cmd-btn px-2.5 py-1 rounded-lg bg-sky-500/15 text-sky-400 border border-sky-500/30 font-mono text-[11px] font-semibold hover:bg-sky-500 hover:text-white transition-colors">
              whoami
            </button>
            <button type="button" data-cli-key="skills" onclick="runTerminalCmd('skills')" class="cli-cmd-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700 font-mono text-[11px] font-semibold hover:border-sky-400 hover:text-white transition-colors">
              skills --top
            </button>
            <button type="button" data-cli-key="awards" onclick="runTerminalCmd('awards')" class="cli-cmd-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700 font-mono text-[11px] font-semibold hover:border-sky-400 hover:text-white transition-colors">
              awards --verify
            </button>
            <button type="button" data-cli-key="rag" onclick="runTerminalCmd('rag')" class="cli-cmd-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700 font-mono text-[11px] font-semibold hover:border-sky-400 hover:text-white transition-colors">
              rag --status
            </button>
          </div>

          <!-- Terminal Output Window with Typewriter -->
          <div class="p-4 font-mono text-xs leading-relaxed min-h-[185px] text-slate-200 space-y-2 overflow-x-auto">
            <div class="text-slate-400">
              <span class="text-emerald-400 font-bold">$</span> <span id="cliTypedCommand" class="text-sky-300 font-semibold">whoami</span>
            </div>
            <pre id="cliOutputBody" class="whitespace-pre-wrap break-words text-slate-300 font-mono text-[11px] sm:text-[11.5px] leading-relaxed"></pre>
          </div>
        </div>

        <!-- 2. Hàng 3 thẻ Mini Bento khoe 3 con số đắt giá -->
        <div class="grid grid-cols-1 min-[420px]:grid-cols-3 gap-2.5 sm:gap-3">
          <div class="spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-3 sm:p-3.5 shadow-sm">
            <div class="font-display text-lg sm:text-2xl font-extrabold text-sky-600 dark:text-sky-400">4.0/4.0</div>
            <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400 mt-0.5 leading-tight" data-i18n="kpi_gpa">Latest Term GPA</div>
          </div>

          <div class="spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-3 sm:p-3.5 shadow-sm">
            <div class="font-display text-base sm:text-xl font-extrabold text-indigo-600 dark:text-indigo-400 leading-tight" data-i18n="kpi_degree_val">Distinction</div>
            <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400 mt-0.5 leading-tight" data-i18n="kpi_degree">B.Eng IT @ UTH</div>
          </div>

          <div class="spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-3 sm:p-3.5 shadow-sm">
            <div class="font-display text-base sm:text-xl font-extrabold text-emerald-600 dark:text-emerald-400 leading-tight" data-i18n="kpi_award_val">2nd Prize</div>
            <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400 mt-0.5 leading-tight" data-i18n="kpi_award">UTH IT Logo Design</div>
          </div>
        </div>

      </div>
    </section>

    <!-- =========================================================================
         SECTION 2: TECHNICAL SKILLS — 3D VORTEX FLIP GRID & 3D ORBIT WHEEL
    ========================================================================= -->
    <section id="skills" class="space-y-6">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="space-y-2">
          <div class="font-mono text-xs font-bold tracking-widest uppercase text-sky-600 dark:text-sky-400 flex items-center gap-2">
            <span class="w-5 h-[1px] bg-sky-500"></span>
            <span data-i18n="skills_kicker">01 // TECHNICAL ARCHITECTURE</span>
          </div>
          <h2 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white" data-i18n="skills_title">
            Interactive Stack Matrix &amp; Orbit Wheel
          </h2>
        </div>

        <!-- Mode Switcher Toggle + Spin Again -->
        <div class="flex flex-wrap items-center gap-2">
          <div class="inline-flex flex-wrap p-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm font-mono text-[11px] sm:text-xs">
            <button type="button" id="btnModeGrid" onclick="switchSkillMode('grid')" class="px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 transition-all">
              <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
              <span data-i18n="mode_grid">3D Spin &amp; Flip Grid</span>
            </button>
            <button type="button" id="btnModeOrbit" onclick="switchSkillMode('orbit')" class="px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 transition-all">
              <i data-lucide="orbit" class="w-3.5 h-3.5"></i>
              <span data-i18n="mode_orbit">3D Orbit Wheel</span>
            </button>
          </div>

          <button type="button" id="btnSkillReplay" onclick="replaySkillsAnimation()" class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sky-600 dark:text-sky-400 font-mono text-[11px] sm:text-xs font-bold hover:bg-sky-50 dark:hover:bg-slate-800 shadow-sm active:scale-95 transition-all">
            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
            <span data-i18n="btn_spin_again">Spin Again</span>
          </button>
        </div>
      </div>

      <!-- Outer Card Wrapper -->
      <div class="bg-white dark:bg-slate-900/90 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-4 sm:p-8 space-y-5 sm:space-y-6">
        
        <!-- Category Filter Tabs (5 Category Pills) -->
        <div id="skillCategoryTabs" class="flex flex-wrap items-center gap-2 pb-4 border-b border-slate-100 dark:border-slate-800">
          <!-- Injected by Vanilla JS -->
        </div>

        <!-- Hint Line -->
        <div class="flex flex-wrap items-center justify-between gap-2 text-xs font-mono text-sky-600 dark:text-sky-400 font-medium">
          <span data-i18n="skill_hint">✨ Hover or tap any skill card to 3D-flip &amp; inspect real project usage</span>
          <span id="activeSkillCountMeta" class="text-slate-400 text-[11px]">10 SKILLS</span>
        </div>

        <!-- CHẾ ĐỘ 1: 3D SPIN & FLIP GRID -->
        <div id="skillGridModeWrap" class="grid grid-cols-1 min-[440px]:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
          <!-- Dynamically populated by JS -->
        </div>

        <!-- CHẾ ĐỘ 2: 3D ORBIT WHEEL + RIGHT INSPECTOR -->
        <div id="skillOrbitModeWrap" class="hidden grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-stretch">
          
          <!-- Left Stage: Ice-Blue Radial Orbit Visualizer -->
          <div id="orbitStageBox" class="lg:col-span-7 rounded-2xl min-h-[320px] sm:min-h-[470px] bg-gradient-to-b from-sky-50/70 via-white to-indigo-50/40 dark:from-slate-950 dark:via-slate-900 dark:to-indigo-950/40 border border-slate-200/70 dark:border-slate-800 flex items-center justify-center relative overflow-hidden">
            
            <div class="absolute top-3 left-3 sm:top-3.5 sm:left-4 font-mono text-[10px] sm:text-[11px] text-slate-400 z-30">ORBIT // TRIG_ROTATION</div>
            <div class="absolute bottom-3 left-3 sm:bottom-3.5 sm:left-4 font-mono text-[10px] sm:text-[11px] text-slate-400 flex items-center gap-1.5 sm:gap-2 z-30">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <span id="orbitRadiusReadout">R=168px</span>
            </div>
            <button type="button" id="btnOrbitSpinToggle" onclick="toggleOrbitSpin()" class="absolute bottom-2.5 right-2.5 sm:bottom-3 sm:right-3.5 z-30 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/90 dark:bg-slate-900/90 border border-slate-200/90 dark:border-slate-700 text-[10px] sm:text-[11px] font-mono font-semibold text-sky-600 dark:text-sky-400 hover:border-sky-400 shadow-sm transition-all">
              <i data-lucide="pause" id="orbitSpinPauseIcon" class="w-3 h-3"></i>
              <i data-lucide="play" id="orbitSpinPlayIcon" class="w-3 h-3 hidden"></i>
              <span id="orbitSpinToggleLabel">Auto Orbit</span>
            </button>

            <!-- 2 Concentric Dashed Orbit Rings (Scaled Dynamically via JS for 0px Mobile Clipping) -->
            <div id="orbitInnerRing" class="w-[140px] h-[140px] sm:w-[210px] sm:h-[210px] rounded-full border border-dashed border-indigo-500/25 dark:border-indigo-400/25 absolute pointer-events-none orbit-ring-counter"></div>
            <div id="orbitOuterRing" class="w-[210px] h-[210px] sm:w-[330px] sm:h-[330px] rounded-full border border-dashed border-sky-500/35 dark:border-sky-400/35 absolute pointer-events-none animate-spin-slow"></div>

            <!-- Central Circular Core Hub -->
            <div class="relative z-10 w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-white dark:bg-slate-900 border-2 border-sky-400/60 shadow-xl shadow-sky-500/15 flex flex-col items-center justify-center text-center p-1.5 sm:p-2">
              <div class="text-[9px] sm:text-[10px] font-mono tracking-widest text-sky-600 dark:text-sky-400 font-bold uppercase">ACTIVE CORE</div>
              <div id="orbitHubCategory" class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white leading-tight mt-0.5 px-1">Core Stack</div>
              <div id="orbitHubCount" class="text-[9px] sm:text-[10px] font-mono text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5">10 Skills</div>
            </div>

            <!-- Dynamic Orbit Nodes Container -->
            <div id="orbitNodesStage" class="absolute inset-0 pointer-events-none orbit-spinning-ring">
              <!-- Populated via JS -->
            </div>
          </div>

          <!-- Right Column: 3D Orbit Inspector Card -->
          <div class="lg:col-span-5 spotlight-card bg-slate-50/70 dark:bg-slate-950/60 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 flex flex-col justify-between space-y-6">
            <div class="space-y-5 relative z-10">
              <div class="flex items-center justify-between border-b border-slate-200/70 dark:border-slate-800 pb-3.5">
                <span class="font-mono text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400" data-i18n="inspector_title">3D Orbit Inspector</span>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono font-semibold bg-emerald-100/80 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300" data-i18n="inspector_verified">Verified Proof</span>
              </div>

              <!-- Icon & Name -->
              <div class="flex items-center gap-4">
                <div id="inspectorIconBox" class="w-14 h-14 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center text-3xl">
                  <i class="devicon-laravel-original colored"></i>
                </div>
                <div>
                  <h3 id="inspectorSkillName" class="font-display text-2xl font-extrabold text-slate-900 dark:text-white">Laravel 11</h3>
                  <span id="inspectorDomain" class="text-xs font-mono text-slate-500 dark:text-slate-400">Backend &amp; CMS</span>
                </div>
              </div>

              <!-- Proficiency Animated Progress Bar -->
              <div class="space-y-2 bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200/70 dark:border-slate-800">
                <div class="flex items-center justify-between text-xs font-mono font-semibold">
                  <span class="text-slate-600 dark:text-slate-300" data-i18n="inspector_mastery">Engineering Mastery</span>
                  <span id="inspectorPercent" class="text-sky-600 dark:text-sky-400 font-bold">95%</span>
                </div>
                <div class="w-full h-2.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                  <div id="inspectorProgressBar" class="h-full rounded-full bg-gradient-to-r from-sky-500 via-indigo-500 to-emerald-500 transition-all duration-500" style="width: 95%;"></div>
                </div>
              </div>

              <!-- Production Project Proof -->
              <div class="space-y-2">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-slate-400" data-i18n="inspector_proof_label">Real-World Production Proof</div>
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 space-y-1.5">
                  <div id="inspectorProjectTitle" class="font-mono text-xs font-bold text-slate-900 dark:text-white">Personal Portfolio &amp; CMS</div>
                  <p id="inspectorProjectDesc" class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    Architected dynamic multilingual CMS, Blade SSR templates, SQLite/MySQL migrations &amp; automated XML sitemaps deployed live on hothanhthien.io.vn.
                  </p>
                </div>
              </div>
            </div>

            <div class="pt-3 border-t border-slate-200/70 dark:border-slate-800 flex items-center justify-between font-mono text-[11px] text-slate-400 relative z-10">
              <span data-i18n="inspector_footer_hint">Hover / Tap wheel nodes to inspect</span>
              <span class="text-sky-500 font-bold">ACTIVE</span>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- =========================================================================
         SECTION 3: ASYMMETRIC BENTO GRID (FEATURED PROJECTS & HONORS)
    ========================================================================= -->
    <section id="projects" class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div class="space-y-2">
          <div class="font-mono text-xs font-bold tracking-widest uppercase text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
            <span class="w-5 h-[1px] bg-indigo-500"></span>
            <span data-i18n="bento_kicker">02 // ASYMMETRIC CASE STUDIES &amp; HONORS</span>
          </div>
          <h2 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white" data-i18n="bento_title">
            Production Systems &amp; Recognition
          </h2>
        </div>

        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 text-sm font-mono font-semibold text-sky-600 dark:text-sky-400 hover:underline">
          <span data-i18n="bento_view_all">View all projects</span>
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

      <!-- Bento Grid 3 Columns -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- THẺ 1 (Chiếm 2 cột - lg:col-span-2): TourRist RAG AI Concierge -->
        <article class="lg:col-span-2 spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-7 flex flex-col justify-between gap-6">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center relative z-10">
            <div class="md:col-span-6 space-y-4">
              <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-200/80 dark:border-sky-500/30">
                  FLAGSHIP // APPLIED AI
                </span>
                <span class="font-mono text-[11px] text-slate-400">01/2026 — 06/2026</span>
              </div>

              <h3 class="font-display text-2xl font-extrabold text-slate-900 dark:text-white leading-snug" data-i18n="proj1_title">
                TourRist — Smart Travel Platform &amp; RAG AI Concierge
              </h3>

              <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-i18n="proj1_desc">
                Engineered a document-based RAG chatbot pairing semantic ChromaDB vector search with LLM grounding to generate zero-hallucination travel itineraries from curated local knowledge bases.
              </p>

              <div class="flex flex-wrap gap-1.5">
                <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Python</span>
                <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">FastAPI</span>
                <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">RAG / LLM</span>
                <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Vector Search</span>
              </div>

              <div class="flex items-center gap-3 pt-1">
                <a href="{{ url('/projects/2') }}" class="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-sky-600 dark:text-sky-400 hover:underline">
                  <span data-i18n="btn_case_study">Read Case Study</span>
                  <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                </a>
                <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-mono text-slate-500 hover:text-slate-900 dark:hover:text-white">
                  <i data-lucide="github" class="w-3.5 h-3.5"></i>
                  <span>GitHub</span>
                </a>
              </div>
            </div>

            <!-- Embedded Live RAG AI Chat Simulation -->
            <div class="md:col-span-6 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 p-4 space-y-3 font-mono text-xs">
              <div class="flex items-center justify-between border-b border-slate-200/70 dark:border-slate-800 pb-2">
                <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1.5">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                  RAG Pipeline Simulator
                </span>
                <div class="flex items-center gap-1.5">
                  <span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold">Match 98.4%</span>
                  <span class="px-2 py-0.5 rounded bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300 text-[10px] font-bold">180ms</span>
                </div>
              </div>

              <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800 text-slate-700 dark:text-slate-200">
                <span class="text-sky-600 dark:text-sky-400 font-bold">User:</span>
                <span data-i18n="rag_sim_q">Suggest a 1-day heritage route in Da Nang with vector citations.</span>
              </div>

              <div class="p-3 rounded-xl bg-sky-50/70 dark:bg-sky-950/30 border border-sky-200/70 dark:border-sky-800/50 text-slate-700 dark:text-slate-300 space-y-1.5">
                <div class="text-[10px] text-sky-600 dark:text-sky-400 font-bold flex items-center gap-1">
                  <i data-lucide="database" class="w-3 h-3"></i>
                  <span data-i18n="rag_sim_trace">Retrieved 4 Chunks • ChromaDB Cosine Top-K</span>
                </div>
                <p class="font-sans text-xs leading-relaxed" data-i18n="rag_sim_a">
                  Morning: Marble Mountains -> Noon: Han Market local cuisine -> Evening: Dragon Bridge. All locations verified via indexed PDF tourism corpus.
                </p>
              </div>
            </div>
          </div>
        </article>

        <!-- THẺ 2 (Chiếm 1 cột): Giải Nhì Thiết kế Logo Khoa CNTT UTH -->
        <article class="spotlight-card hover:-translate-y-1 bg-gradient-to-br from-white via-amber-50/30 to-sky-50/40 dark:from-slate-900 dark:via-slate-900 dark:to-indigo-950/30 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-7 flex flex-col justify-between">
          <div class="space-y-3 relative z-10">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-amber-100/80 dark:bg-amber-500/15 text-amber-800 dark:text-amber-300 border border-amber-200/80 dark:border-amber-500/30">
              <i data-lucide="trophy" class="w-3.5 h-3.5"></i>
              <span data-i18n="award_badge">DESIGN RECOGNITION</span>
            </span>

            <!-- Floating Vector Emblem IT•UTH -->
            <div class="py-5 flex items-center justify-center">
              <div class="animate-float relative w-28 h-28 rounded-3xl bg-gradient-to-br from-sky-500 via-indigo-600 to-slate-900 p-0.5 shadow-xl shadow-indigo-500/20 flex items-center justify-center">
                <div class="w-full h-full rounded-[22px] bg-white dark:bg-slate-950 flex flex-col items-center justify-center">
                  <span class="font-display text-2xl font-extrabold tracking-tight bg-gradient-to-r from-sky-600 to-indigo-600 bg-clip-text text-transparent">
                    IT&bull;UTH
                  </span>
                  <span class="font-mono text-[9px] tracking-widest text-slate-400 uppercase mt-0.5">IDENTITY</span>
                </div>
              </div>
            </div>

            <h3 class="font-display text-xl font-extrabold text-slate-900 dark:text-white leading-snug" data-i18n="award_title">
              2nd Prize — IT Faculty Logo &amp; Visual Identity (UTH)
            </h3>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-i18n="award_desc">
              Awarded by University of Transport HCMC, proving strong visual craft, typography precision, and clean UI/UX thinking alongside backend engineering.
            </p>
          </div>

          <a href="{{ route('certificates') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 hover:underline relative z-10">
            <span data-i18n="award_verify">Verify Official Certificate</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
          </a>
        </article>

        <!-- THẺ 3 (Chiếm 1 cột): Laundry & Multi-Service Platform (Spring Boot) -->
        <article class="spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-7 flex flex-col justify-between space-y-5">
          <div class="space-y-3 relative z-10">
            <div class="flex items-center justify-between">
              <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-500/30">
                ENTERPRISE JAVA
              </span>
              <span class="font-mono text-[11px] text-slate-400">01/2025 — 05/2025</span>
            </div>

            <h3 class="font-display text-xl font-extrabold text-slate-900 dark:text-white leading-snug" data-i18n="proj3_title">
              Laundry &amp; Multi-Service Platform
            </h3>

            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-i18n="proj3_desc">
              Order lifecycle state machine (Received -> Washing -> Ready -> Delivered) with Spring Boot REST APIs, JWT/OTP Auth &amp; Role-Based Access Control.
            </p>

            <!-- Microservices Architecture Flow Diagram -->
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 font-mono text-[10px] space-y-2">
              <div class="text-slate-400 font-bold">MICROSERVICES FLOW</div>
              <div class="flex flex-wrap items-center gap-1.5 text-slate-700 dark:text-slate-200">
                <span class="px-2 py-1 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">NextJS</span>
                <span class="text-sky-500">&rarr;</span>
                <span class="px-2 py-1 rounded bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-400/40 font-bold">JWT Guard</span>
                <span class="text-sky-500">&rarr;</span>
                <span class="px-2 py-1 rounded bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-400/40 font-bold">Spring Boot</span>
                <span class="text-sky-500">&rarr;</span>
                <span class="px-2 py-1 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">MySQL</span>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800 relative z-10">
            <a href="{{ url('/projects/3') }}" class="inline-flex items-center gap-1 text-xs font-mono font-bold text-sky-600 dark:text-sky-400 hover:underline">
              <span data-i18n="btn_case_study">Read Case Study</span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
            <a href="https://github.com/hoThanhThien/LT_JAVA_010412213603" target="_blank" rel="noopener" class="text-xs font-mono text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center gap-1">
              <i data-lucide="github" class="w-3.5 h-3.5"></i>
              <span>Code</span>
            </a>
          </div>
        </article>

        <!-- THẺ 4 (Chiếm 2 cột - lg:col-span-2): Kinh nghiệm Odoo ERP (Enmasys) & Full-Stack (AIPower) -->
        <article class="lg:col-span-2 spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-7 flex flex-col justify-between space-y-6">
          <div class="space-y-3 relative z-10">
            <div class="flex items-center justify-between flex-wrap gap-2">
              <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-indigo-50 dark:bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-500/30" data-i18n="exp_badge">
                INDUSTRY EXPERIENCE
              </span>
              <span class="font-mono text-[11px] text-slate-400" data-i18n="exp_period">03/2026 — PRESENT</span>
            </div>

            <h3 class="font-display text-2xl font-extrabold text-slate-900 dark:text-white" data-i18n="exp_bento_title">
              Odoo ERP Engineering @ Enmasys &amp; Full-Stack Web @ AIPower
            </h3>

            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed max-w-3xl" data-i18n="exp_bento_desc">
              Configured enterprise Odoo modules (Retail, Attendance, Accounting, B2B Sales) at Enmasys; engineered corporate &amp; education platforms using PHP, WordPress &amp; PostgreSQL at AIPower while maintaining hothanhthien.io.vn CMS in production.
            </p>
          </div>

          <!-- 3 Hộp Chỉ Số Kết Quả Thực Tế -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 relative z-10">
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/70 dark:border-slate-800">
              <div class="font-display text-xl font-extrabold text-indigo-600 dark:text-indigo-400" data-i18n="exp_stat1_val">4+ Modules</div>
              <div class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-1" data-i18n="exp_stat1_lbl">Odoo Retail, Sales, Accounting &amp; HR configured</div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/70 dark:border-slate-800">
              <div class="font-display text-xl font-extrabold text-sky-600 dark:text-sky-400" data-i18n="exp_stat2_val">PHP &amp; PostgreSQL</div>
              <div class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-1" data-i18n="exp_stat2_lbl">Production corporate &amp; education portals shipped</div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/70 dark:border-slate-800">
              <div class="font-display text-xl font-extrabold text-emerald-600 dark:text-emerald-400" data-i18n="exp_stat3_val">Laravel 11 SSR</div>
              <div class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-1" data-i18n="exp_stat3_lbl">Live bilingual CMS running at hothanhthien.io.vn</div>
            </div>
          </div>
        </article>

      </div>
    </section>

    <!-- =========================================================================
         SECTION 4: CREDENTIALS & ENGINEERING BLOG (100% SYNCHRONIZED EN/VI)
    ========================================================================= -->
    <section id="credentials" class="grid grid-cols-1 lg:grid-cols-12 gap-8">

      <!-- Credentials & Brochure PDF (5 cols) -->
      <div class="lg:col-span-5 space-y-5">
        <div class="space-y-1.5">
          <div class="font-mono text-xs font-bold tracking-widest uppercase text-emerald-600 dark:text-emerald-400" data-i18n="cred_kicker">03 // OFFICIAL CREDENTIALS</div>
          <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white" data-i18n="cred_title">Degrees &amp; Portfolio PDF</h2>
        </div>

        <div class="space-y-3">
          <a href="{{ asset('assets/docs/HoThanhThien_CV_Brochure.pdf') }}" target="_blank" rel="noopener" class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3 relative z-10">
              <div class="flex items-start gap-3">
                <span class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 flex items-center justify-center flex-shrink-0">
                  <i data-lucide="file-text" class="w-5 h-5"></i>
                </span>
                <div>
                  <h3 class="font-bold text-sm text-slate-900 dark:text-white" data-i18n="cert1_title">Engineer Portfolio &amp; CV Brochure (PDF)</h3>
                  <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="cert1_sub">Ho Thanh Thien &bull; Ready for Instant Download</p>
                </div>
              </div>
              <i data-lucide="download" class="w-4 h-4 text-sky-500 flex-shrink-0"></i>
            </div>
          </a>

          <a href="{{ route('certificates') }}" class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3 relative z-10">
              <div class="flex items-start gap-3">
                <span class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                  <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                </span>
                <div>
                  <h3 class="font-bold text-sm text-slate-900 dark:text-white" data-i18n="cert2_title">B.Eng in Information Technology — Distinction</h3>
                  <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="cert2_sub">University of Transport HCMC (UTH) &bull; GPA 3.6 / 4.0</p>
                </div>
              </div>
              <i data-lucide="external-link" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
            </div>
          </a>

          <a href="{{ route('certificates') }}" class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
            <div class="flex items-start justify-between gap-3 relative z-10">
              <div class="flex items-start gap-3">
                <span class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                  <i data-lucide="award" class="w-5 h-5"></i>
                </span>
                <div>
                  <h3 class="font-bold text-sm text-slate-900 dark:text-white" data-i18n="cert3_title">2nd Prize — IT Faculty Logo Design Contest</h3>
                  <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="cert3_sub">Faculty of Information Technology &bull; UTH Official Award</p>
                </div>
              </div>
              <i data-lucide="external-link" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
            </div>
          </a>
        </div>
      </div>

      <!-- Engineering Blog (7 cols) -->
      <div id="blog" class="lg:col-span-7 space-y-5">
        <div class="flex items-end justify-between">
          <div class="space-y-1.5">
            <div class="font-mono text-xs font-bold tracking-widest uppercase text-sky-600 dark:text-sky-400" data-i18n="blog_kicker">04 // ENGINEERING JOURNAL</div>
            <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white" data-i18n="blog_title">Technical Articles &amp; Notes</h2>
          </div>
          <a href="{{ route('blog.index') }}" class="text-xs font-mono font-bold text-sky-600 dark:text-sky-400 hover:underline" data-i18n="blog_all">All Posts &rarr;</a>
        </div>

        <div class="space-y-3">
          <a href="{{ url('/blog/building-document-based-rag-systems') }}" class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4 relative z-10">
              <div class="space-y-1">
                <div class="font-mono text-[11px] text-sky-600 dark:text-sky-400 font-bold">RAG AI // ARCHITECTURE</div>
                <h3 class="font-display font-bold text-base text-slate-900 dark:text-white" data-i18n="post1_title">Building Document-Based RAG Systems with LLMs</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-1" data-i18n="post1_desc">How to design a retrieval-augmented generation pipeline using vector search, chunking, and modern LLMs.</p>
              </div>
              <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
            </div>
          </a>

          <a href="{{ url('/blog/building-restful-apis-with-spring-boot') }}" class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4 relative z-10">
              <div class="space-y-1">
                <div class="font-mono text-[11px] text-emerald-600 dark:text-emerald-400 font-bold">JAVA // SPRING BOOT</div>
                <h3 class="font-display font-bold text-base text-slate-900 dark:text-white" data-i18n="post2_title">Building Secure RESTful APIs with Spring Boot</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-1" data-i18n="post2_desc">A comprehensive guide to creating scalable, production-ready REST APIs using Java Spring Boot and JWT security.</p>
              </div>
              <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
            </div>
          </a>

          <a href="{{ url('/blog/mastering-react-hooks') }}" class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4 relative z-10">
              <div class="space-y-1">
                <div class="font-mono text-[11px] text-indigo-600 dark:text-indigo-400 font-bold">FRONTEND // REACTJS</div>
                <h3 class="font-display font-bold text-base text-slate-900 dark:text-white" data-i18n="post3_title">Mastering React Hooks &amp; State Management</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-1" data-i18n="post3_desc">Learn how to utilize React Hooks effectively for scalable state management and functional components.</p>
              </div>
              <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
            </div>
          </a>
        </div>
      </div>

    </section>

  </main>

  <!-- =========================================================================
       FOOTER (WITH PHONE NUMBER, EMAIL & VERIFIED LINKS MOVED CLEANLY HERE)
  ========================================================================= -->
  <footer class="border-t border-slate-200/80 dark:border-slate-800/80 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
      <div class="space-y-2">
        <div class="flex items-center gap-2.5 font-display font-extrabold text-lg text-slate-900 dark:text-white">
          <span class="w-8 h-8 rounded-[10px] overflow-hidden bg-gradient-to-br from-sky-500 to-indigo-600 shadow-sm shadow-indigo-500/20 ring-1 ring-slate-900/5 dark:ring-white/15 flex-shrink-0 inline-flex items-center justify-center">
            <img src="{{ asset('android-chrome-192x192.png') }}" alt="Hồ Thành Thiện Logo" class="w-full h-full object-cover scale-[1.12]">
          </span>
          <span data-i18n="brand_name">{{ app()->getLocale() === 'vi' ? 'Hồ Thành Thiện' : 'Ho Thanh Thien' }}</span>
        </div>
        <p class="text-xs font-mono text-slate-500 dark:text-slate-400" data-i18n="footer_subtitle">
          Full-Stack Web Systems &amp; Applied RAG AI Engineer • Ho Chi Minh City, Vietnam
        </p>
      </div>

      <!-- Contact Details Safely Placed in Footer -->
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
       SECTION 6: FLOATING RAG AI COPILOT ("ASK THIEN'S RAG AI")
  ========================================================================= -->
  <div class="fixed bottom-4 right-4 sm:bottom-5 sm:right-5 z-50 flex flex-col items-end gap-3 max-w-[calc(100vw-2rem)]">
    
    <!-- Chat Copilot Drawer Window -->
    <div id="ragCopilotPanel" class="hidden w-[calc(100vw-2rem)] max-w-[385px] max-h-[min(78vh,540px)] flex flex-col bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-700 rounded-3xl shadow-2xl overflow-hidden transition-all">
      <!-- Header -->
      <div class="px-4 sm:px-5 py-3.5 sm:py-4 bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-2.5">
          <span class="w-8 h-8 rounded-xl bg-sky-500/20 border border-sky-400/40 flex items-center justify-center text-sky-300">
            <i data-lucide="bot" class="w-4 h-4"></i>
          </span>
          <div>
            <div class="text-xs font-display font-bold flex items-center gap-1.5">
              <span data-i18n="copilot_title">Thien's RAG AI Copilot</span>
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            </div>
            <div class="text-[10px] font-mono text-sky-300" data-i18n="copilot_sub">Grounded in verified UTH &amp; CV corpus</div>
          </div>
        </div>
        <button type="button" onclick="toggleCopilot()" class="p-1 rounded-lg hover:bg-white/10 text-slate-300">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>

      <!-- Messages Container -->
      <div id="copilotMessages" class="p-4 flex-1 min-h-[160px] max-h-[260px] overflow-y-auto space-y-3 bg-slate-50/70 dark:bg-slate-950/60 text-xs">
        <div class="p-3 rounded-2xl rounded-tl-sm bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-200 leading-relaxed shadow-sm" data-i18n="copilot_welcome">
          Hi recruiter! I am indexed with Ho Thanh Thien's UTH academic records (4.0/4.0 latest GPA, Distinction), TourRist RAG AI architecture, and Enmasys/AIPower engineering logs. Click any quick prompt below!
        </div>
      </div>

      <!-- 4 Quick Prompts for Recruiters -->
      <div id="copilotQuickPrompts" class="px-3.5 py-2.5 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 flex flex-wrap gap-1.5">
        <!-- Populated by JS in active EN/VI language -->
      </div>

      <!-- Input Box -->
      <form onsubmit="handleCopilotSubmit(event)" class="p-3 bg-white dark:bg-slate-900 border-t border-slate-200/80 dark:border-slate-800 flex items-center gap-2">
        <input id="copilotInput" type="text" placeholder="Ask about GPA, RAG AI, Enmasys..." class="flex-1 px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500">
        <button type="submit" class="p-2 rounded-xl bg-sky-500 text-white hover:bg-sky-600 transition-colors">
          <i data-lucide="send" class="w-3.5 h-3.5"></i>
        </button>
      </form>
    </div>

    <!-- Floating Trigger Button -->
    <button type="button" onclick="toggleCopilot()" class="inline-flex items-center gap-2.5 px-4 py-3 rounded-2xl bg-slate-900 dark:bg-sky-500 text-white font-semibold text-xs shadow-xl shadow-slate-900/25 dark:shadow-sky-500/30 hover:scale-105 active:scale-95 transition-all border border-slate-700/80 dark:border-sky-400/50">
      <span class="relative flex h-2.5 w-2.5">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 dark:bg-white opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-sky-400 dark:bg-white"></span>
      </span>
      <i data-lucide="sparkles" class="w-4 h-4 text-sky-400 dark:text-white"></i>
      <span data-i18n="copilot_btn">Ask Thien's RAG AI</span>
    </button>

  </div>

  <!-- =========================================================================
       COMPLETE VANILLA JAVASCRIPT ENGINE
  ========================================================================= -->
  <script>
    // 1. STATE MANAGEMENT
    let currentLang = "{{ app()->getLocale() === 'vi' ? 'vi' : 'en' }}";
    let currentSkillCategory = 0;
    let currentSkillMode = 'grid'; // 'grid' | 'orbit'
    let activeOrbitSkillIndex = 0;
    let typewriterTimer = null;

    // 2. MOUSE-TRACKING SPOTLIGHT GLOW ON ALL .spotlight-card ELEMENTS
    document.addEventListener('mousemove', (e) => {
      document.querySelectorAll('.spotlight-card').forEach(card => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        card.style.setProperty('--mouse-x', `${x}px`);
        card.style.setProperty('--mouse-y', `${y}px`);
      });
    });

    // 3. WHITE BG / DARK BG THEME TOGGLE
    function toggleTheme() {
      const htmlEl = document.documentElement;
      const isDark = htmlEl.classList.toggle('dark');
      localStorage.setItem('thien_theme', isDark ? 'dark' : 'light');
      updateThemeUI(isDark);
    }

    function updateThemeUI(isDark) {
      document.getElementById('themeIconDark').classList.toggle('hidden', isDark);
      document.getElementById('themeIconLight').classList.toggle('hidden', !isDark);
      document.getElementById('themeToggleLabel').textContent = isDark
        ? (currentLang === 'vi' ? 'Nền Trắng' : 'White BG')
        : (currentLang === 'vi' ? 'Nền Tối' : 'Dark BG');
    }

    if (localStorage.getItem('thien_theme') === 'dark') {
      document.documentElement.classList.add('dark');
    }

    function toggleMobileMenu() {
      const panel = document.getElementById('mobileMenuPanel');
      if (panel) panel.classList.toggle('hidden');
    }

    function closeMobileMenu() {
      const panel = document.getElementById('mobileMenuPanel');
      if (panel) panel.classList.add('hidden');
    }

    // 4. COMPLETE EN / VI TRANSLATION DICTIONARY (100% SYNCHRONIZED)
    const I18N = {
      en: {
        brand_name: 'Ho Thanh Thien',
        nav_projects: 'Selected Work',
        nav_skills: 'Tech Stack',
        nav_credentials: 'Credentials',
        nav_blog: 'Engineering Blog',
        nav_all_projects: 'Archive',
        nav_hire: 'Hire Me',
        hero_badge: 'Available for Full-Stack & AI Engineer Roles • Ho Chi Minh City',
        hero_title_1: 'Building scalable',
        hero_title_highlight: 'Web Systems & RAG AI',
        hero_title_2: 'that solve real problems',
        hero_desc: 'Honors B.Eng in Information Technology graduate at UTH with a 4.0/4.0 latest semester GPA. Hands-on experience architecting Java Spring Boot APIs, Laravel ecosystems, Odoo ERP workflows, and Python RAG/LLM chatbots.',
        chip_aipower: 'Full-Stack Dev @ AIPower',
        chip_enmasys: 'Odoo ERP @ Enmasys',
        chip_award: '2nd Prize UTH IT Logo Design',
        cta_explore: 'Explore Featured Work',
        cta_brochure: 'Download Brochure PDF',
        kpi_gpa: 'Latest Term GPA',
        kpi_degree_val: 'Distinction',
        kpi_degree: 'B.Eng IT @ UTH',
        kpi_award_val: '2nd Prize',
        kpi_award: 'UTH IT Logo Design',
        skills_kicker: '01 // TECHNICAL ARCHITECTURE',
        skills_title: 'Interactive Stack Matrix & Orbit Wheel',
        mode_grid: '3D Spin & Flip Grid',
        mode_orbit: '3D Orbit Wheel',
        btn_spin_again: 'Spin Again',
        skill_hint: '✨ Hover or tap any skill card to 3D-flip & inspect real project usage',
        inspector_title: '3D Orbit Inspector',
        inspector_verified: 'Verified Proof',
        inspector_mastery: 'Engineering Mastery',
        inspector_proof_label: 'Real-World Production Proof',
        inspector_footer_hint: 'Hover / Tap wheel nodes to inspect',
        bento_kicker: '02 // ASYMMETRIC CASE STUDIES & HONORS',
        bento_title: 'Production Systems & Recognition',
        bento_view_all: 'View all projects',
        proj1_title: 'TourRist — Smart Travel Platform & RAG AI Concierge',
        proj1_desc: 'Engineered a document-based RAG chatbot pairing semantic ChromaDB vector search with LLM grounding to generate zero-hallucination travel itineraries from curated local knowledge bases.',
        btn_case_study: 'Read Case Study',
        rag_sim_q: 'Suggest a 1-day heritage route in Da Nang with vector citations.',
        rag_sim_trace: 'Retrieved 4 Chunks • ChromaDB Cosine Top-K',
        rag_sim_a: 'Morning: Marble Mountains -> Noon: Han Market local cuisine -> Evening: Dragon Bridge. All locations verified via indexed PDF tourism corpus.',
        award_badge: 'DESIGN RECOGNITION',
        award_title: '2nd Prize — IT Faculty Logo & Visual Identity (UTH)',
        award_desc: 'Awarded by University of Transport HCMC, proving strong visual craft, typography precision, and clean UI/UX thinking alongside backend engineering.',
        award_verify: 'Verify Official Certificate',
        proj3_title: 'Laundry & Multi-Service Platform',
        proj3_desc: 'Order lifecycle state machine (Received -> Washing -> Ready -> Delivered) with Spring Boot REST APIs, JWT/OTP Auth & Role-Based Access Control.',
        exp_badge: 'INDUSTRY EXPERIENCE',
        exp_period: '03/2026 — PRESENT',
        exp_bento_title: 'Odoo ERP Engineering @ Enmasys & Full-Stack Web @ AIPower',
        exp_bento_desc: 'Configured enterprise Odoo modules (Retail, Attendance, Accounting, B2B Sales) at Enmasys; engineered corporate & education platforms using PHP, WordPress & PostgreSQL at AIPower while maintaining hothanhthien.io.vn CMS in production.',
        exp_stat1_val: '4+ Modules',
        exp_stat1_lbl: 'Odoo Retail, Sales, Accounting & HR configured',
        exp_stat2_val: 'PHP & PostgreSQL',
        exp_stat2_lbl: 'Production corporate & education portals shipped',
        exp_stat3_val: 'Laravel 11 SSR',
        exp_stat3_lbl: 'Live bilingual CMS running at hothanhthien.io.vn',
        cred_kicker: '03 // OFFICIAL CREDENTIALS',
        cred_title: 'Degrees & Portfolio PDF',
        cert1_title: 'Engineer Portfolio & CV Brochure (PDF)',
        cert1_sub: 'Ho Thanh Thien • Ready for Instant Download',
        cert2_title: 'B.Eng in Information Technology — Distinction',
        cert2_sub: 'University of Transport HCMC (UTH) • GPA 3.6 / 4.0',
        cert3_title: '2nd Prize — IT Faculty Logo Design Contest',
        cert3_sub: 'Faculty of Information Technology • UTH Official Award',
        blog_kicker: '04 // ENGINEERING JOURNAL',
        blog_title: 'Technical Articles & Notes',
        blog_all: 'All Posts →',
        post1_title: 'Building Document-Based RAG Systems with LLMs',
        post1_desc: 'How to design a retrieval-augmented generation pipeline using vector search, chunking, and modern LLMs.',
        post2_title: 'Building Secure RESTful APIs with Spring Boot',
        post2_desc: 'A comprehensive guide to creating scalable, production-ready REST APIs using Java Spring Boot and JWT security.',
        post3_title: 'Mastering React Hooks & State Management',
        post3_desc: 'Learn how to utilize React Hooks effectively for scalable state management and functional components.',
        footer_subtitle: 'Full-Stack Web Systems & Applied RAG AI Engineer • Ho Chi Minh City, Vietnam',
        copilot_title: "Thien's RAG AI Copilot",
        copilot_sub: 'Grounded in verified UTH & CV corpus',
        copilot_welcome: "Hi recruiter! I am indexed with Ho Thanh Thien's UTH academic records (4.0/4.0 latest GPA, Distinction), TourRist RAG AI architecture, and Enmasys/AIPower engineering logs. Click any quick prompt below!",
        copilot_btn: "Ask Thien's RAG AI"
      },
      vi: {
        brand_name: 'Hồ Thành Thiện',
        nav_projects: 'Dự án Nổi bật',
        nav_skills: 'Kỹ năng',
        nav_credentials: 'Bằng cấp & PDF',
        nav_blog: 'Blog Kỹ thuật',
        nav_all_projects: 'Kho Dự án',
        nav_hire: 'Liên hệ ngay',
        hero_badge: 'Sẵn sàng cho vị trí Kỹ sư Full-Stack & AI • TP. Hồ Chí Minh',
        hero_title_1: 'Xây dựng Hệ thống Web',
        hero_title_highlight: '& RAG AI thực chiến',
        hero_title_2: 'giải quyết bài toán thực tế',
        hero_desc: 'Kỹ sư Công nghệ Thông tin tốt nghiệp Loại Xuất sắc tại ĐH GTVT TP.HCM (UTH) với GPA kỳ gần nhất 4.0/4.0. Kinh nghiệm thực chiến Java Spring Boot, Laravel, Odoo ERP và Chatbot AI RAG/LLM.',
        chip_aipower: 'Full-Stack Dev @ AIPower',
        chip_enmasys: 'Odoo ERP @ Enmasys',
        chip_award: 'Giải Nhì Thiết kế Logo CNTT UTH',
        cta_explore: 'Khám phá Dự án Tiêu biểu',
        cta_brochure: 'Tải Hồ sơ Brochure PDF',
        kpi_gpa: 'GPA Kỳ Gần Nhất',
        kpi_degree_val: 'Xuất sắc',
        kpi_degree: 'Kỹ sư CNTT @ UTH',
        kpi_award_val: 'Giải Nhì',
        kpi_award: 'Thiết kế Logo CNTT UTH',
        skills_kicker: '01 // KIẾN TRÚC KỸ NĂNG CHUYÊN MÔN',
        skills_title: 'Ma trận Kỹ năng 3D & Vòng xoay Quỹ đạo',
        mode_grid: 'Lưới Xoay & Lật 3D',
        mode_orbit: 'Vòng xoay Quỹ đạo 3D',
        btn_spin_again: 'Quay lại',
        skill_hint: '✨ Rê chuột hoặc chạm vào thẻ kỹ năng để lật 3D 180° & xem dự án áp dụng thực tế',
        inspector_title: 'Bảng soi Kỹ năng 3D',
        inspector_verified: 'Đã kiểm chứng',
        inspector_mastery: 'Độ thành thạo thực chiến',
        inspector_proof_label: 'Minh chứng Dự án Production',
        inspector_footer_hint: 'Rê chuột / Chạm node trên vòng quay để xem',
        bento_kicker: '02 // DỰ ÁN TRỌNG ĐIỂM & VINH DANH',
        bento_title: 'Hệ thống Thực tế & Thành tích Đạt được',
        bento_view_all: 'Xem tất cả dự án',
        proj1_title: 'TourRist — Nền tảng Du lịch Thông minh & Trợ lý AI RAG',
        proj1_desc: 'Phát triển chatbot AI dựa trên tài liệu kết hợp tìm kiếm vector ChromaDB và sinh câu trả lời LLM không bịa đặt (zero-hallucination), cung cấp gợi ý lịch trình chính xác từ kho tri thức nội bộ.',
        btn_case_study: 'Xem Case Study',
        rag_sim_q: 'Gợi ý lịch trình tham quan Đà Nẵng 1 ngày kèm trích dẫn vector.',
        rag_sim_trace: 'Truy xuất 4 đoạn văn bản • ChromaDB Cosine Top-K',
        rag_sim_a: 'Sáng: Ngũ Hành Sơn -> Trưa: Ẩm thực Chợ Hàn -> Tối: Cầu Rồng. Toàn bộ địa điểm được xác thực từ tài liệu du lịch chuẩn hóa.',
        award_badge: 'GIẢI THƯỞNG THIẾT KẾ',
        award_title: 'Giải Nhì — Sáng tác Logo & Nhận diện Khoa CNTT (UTH)',
        award_desc: 'Chứng nhận từ ĐH GTVT TP.HCM, khẳng định tư duy thẩm mỹ UI/UX, typography và nghệ thuật thiết kế giao diện chuẩn mực bên cạnh nền tảng lập trình hệ thống.',
        award_verify: 'Xác thực Giấy khen & Bằng cấp',
        proj3_title: 'Hệ thống Quản lý Giặt ủi & Đa dịch vụ',
        proj3_desc: 'Máy trạng thái vòng đời đơn hàng (Tiếp nhận -> Đang giặt -> Sẵn sàng -> Đã giao) xây dựng trên Spring Boot REST API, bảo mật JWT/OTP và phân quyền RBAC.',
        exp_badge: 'KINH NGHIỆM THỰC TẾ',
        exp_period: '03/2026 — HIỆN TẠI',
        exp_bento_title: 'Kỹ sư Odoo ERP @ Enmasys & Full-Stack Web @ AIPower',
        exp_bento_desc: 'Triển khai và tùy chỉnh các phân hệ Odoo ERP (Bán lẻ, Chấm công, Kế toán, Sales B2B) tại Enmasys; phát triển website doanh nghiệp và giáo dục với PHP, WordPress & PostgreSQL tại AIPower.',
        exp_stat1_val: '4+ Phân hệ',
        exp_stat1_lbl: 'Tùy chỉnh Odoo Bán lẻ, Sales, Kế toán & Nhân sự',
        exp_stat2_val: 'PHP & PostgreSQL',
        exp_stat2_lbl: 'Bàn giao website giáo dục & doanh nghiệp tại AIPower',
        exp_stat3_val: 'Laravel 11 SSR',
        exp_stat3_lbl: 'Hệ thống Portfolio CMS song ngữ chạy live tại hothanhthien.io.vn',
        cred_kicker: '03 // BẰNG CẤP & CHỨNG NHẬN CHÍNH QUY',
        cred_title: 'Hồ sơ Năng lực & Brochure PDF',
        cert1_title: 'Brochure & Hồ sơ Năng lực Kỹ sư Phần mềm (PDF)',
        cert1_sub: 'Hồ Thành Thiện • Xem trước & Tải xuống trực tiếp',
        cert2_title: 'Bằng Cử nhân Công nghệ Thông tin — Loại Xuất sắc',
        cert2_sub: 'Trường ĐH Giao thông Vận tải TP.HCM (UTH) • GPA 3.6 / 4.0',
        cert3_title: 'Giải Nhì Sáng tác Logo & Nhận diện Khoa CNTT',
        cert3_sub: 'Khoa Công nghệ Thông tin - UTH • Khen thưởng chính thức',
        blog_kicker: '04 // BÀI VIẾT KỸ THUẬT',
        blog_title: 'Chia sẻ Kiến thức & Ghi chép Hệ thống',
        blog_all: 'Xem tất cả →',
        post1_title: 'Xây dựng Hệ thống RAG AI Dựa trên Tài liệu với LLM',
        post1_desc: 'Hướng dẫn thiết kế pipeline Retrieval-Augmented Generation sử dụng tìm kiếm vector ChromaDB, chia đoạn dữ liệu và LLM hiện đại.',
        post2_title: 'Thiết kế RESTful API Bảo mật với Java Spring Boot & JWT',
        post2_desc: 'Cẩm nang xây dựng REST API chuẩn production, phân quyền RBAC và xác thực Stateless JWT với Spring Boot.',
        post3_title: 'Làm chủ React Hooks & Quản lý Trạng thái Hệ thống',
        post3_desc: 'Áp dụng Custom Hooks, Context và mô hình component hàm tối ưu hiệu năng cho ứng dụng React/Next.js.',
        footer_subtitle: 'Kỹ sư Hệ thống Web Full-Stack & Ứng dụng RAG AI • TP. Hồ Chí Minh, Việt Nam',
        copilot_title: 'Trợ lý RAG AI của Thiện',
        copilot_sub: 'Truy xuất dữ liệu thật từ hồ sơ UTH & CV',
        copilot_welcome: 'Chào nhà tuyển dụng! Tôi được nạp dữ liệu xác thực từ bảng điểm ĐH GTVT TP.HCM (GPA kỳ gần nhất 4.0/4.0, Tốt nghiệp Xuất sắc), kiến trúc dự án TourRist RAG AI và kinh nghiệm Enmasys / AIPower. Hãy bấm thử gợi ý bên dưới!',
        copilot_btn: 'Hỏi Trợ lý RAG AI'
      }
    };

    // 5. VERIFIED SKILL CATEGORIES DATASET (WITH EN/VI TRANSLATED PROOFS)
    const SKILL_CATEGORIES = [
      {
        id: 'core',
        label: { en: 'Core Stack (Top 10)', vi: 'Công nghệ Cốt lõi (Top 10)' },
        hubName: { en: 'Core Stack', vi: 'Core Stack' },
        icon: 'star',
        iconColor: 'text-amber-500',
        items: [
          { name: 'Java', icon: 'custom:java', mastery: 88, domain: { en: 'Enterprise Backend', vi: 'Backend Doanh nghiệp' }, project: { en: 'Laundry Management System', vi: 'Hệ thống Quản lý Giặt ủi' }, desc: { en: 'Spring Boot REST APIs, finite state order lifecycle & JPA', vi: 'Spring Boot REST API, máy trạng thái đơn hàng & JPA' } },
          { name: 'PHP', icon: 'custom:php', mastery: 92, domain: { en: 'Web Architecture', vi: 'Kiến trúc Web' }, project: { en: 'Personal Portfolio & AIPower', vi: 'Portfolio CMS & AIPower' }, desc: { en: 'Laravel 11 SSR, OOP modules & corporate portals', vi: 'Laravel 11 SSR, module OOP & cổng thông tin doanh nghiệp' } },
          { name: 'Python', icon: 'custom:python', mastery: 88, domain: { en: 'AI & Data Pipelines', vi: 'AI & Xử lý dữ liệu' }, project: { en: 'TourRist RAG Chatbot', vi: 'Chatbot RAG TourRist' }, desc: { en: 'FastAPI endpoints, text chunking & embeddings', vi: 'FastAPI endpoints, chia đoạn văn bản & vector embeddings' } },
          { name: 'Odoo ERP', icon: 'custom:odoo', mastery: 85, domain: { en: 'Enterprise ERP', vi: 'Hệ thống ERP' }, project: { en: 'Enmasys Enterprise Internship', vi: 'Thực tập Doanh nghiệp Enmasys' }, desc: { en: 'Configured Retail, Accounting & B2B Sales ORM', vi: 'Cấu hình phân hệ Bán lẻ, Kế toán & Sales B2B' } },
          { name: 'RAG AI', icon: 'custom:rag', mastery: 88, domain: { en: 'Applied GenAI', vi: 'AI Ứng dụng' }, project: { en: 'TourRist AI Concierge', vi: 'Trợ lý Du lịch RAG AI' }, desc: { en: 'ChromaDB semantic cosine retrieval & prompt grounding', vi: 'Tìm kiếm vector ChromaDB & giảm thiểu ảo giác LLM' } },
          { name: 'ReactJS', icon: 'devicon-react-original colored', mastery: 90, domain: { en: 'Reactive Frontend', vi: 'Giao diện Phản ứng' }, project: { en: 'Web Admin & Service UIs', vi: 'Giao diện Quản trị Web' }, desc: { en: 'Modular hooks architecture & reactive components', vi: 'Kiến trúc React Hooks tối ưu trạng thái' } },
          { name: 'Tailwind CSS', icon: 'devicon-tailwindcss-original colored', mastery: 94, domain: { en: 'UI Styling Engine', vi: 'Hệ thống Giao diện' }, project: { en: 'Portfolio Design System', vi: 'Hệ thống Design Portfolio' }, desc: { en: 'Design tokens, glassmorphism & dark/light parity', vi: 'Design tokens, hiệu ứng kính mờ & Dark/Light Mode' } },
          { name: 'PostgreSQL', icon: 'devicon-postgresql-plain colored', mastery: 86, domain: { en: 'Relational Database', vi: 'CSDL Quan hệ' }, project: { en: 'AIPower & Odoo DBs', vi: 'CSDL AIPower & Odoo' }, desc: { en: 'ACID transactions, JSONB columns & query tuning', vi: 'Giao dịch ACID, trường JSONB & tối ưu truy vấn' } },
          { name: 'Docker', icon: 'devicon-docker-plain colored', mastery: 86, domain: { en: 'Containerization', vi: 'Đóng gói Container' }, project: { en: 'Microservice Compose Stacks', vi: 'Môi trường Docker Compose' }, desc: { en: 'Isolated container networking & clean CI/CD parity', vi: 'Cô lập dịch vụ container & đồng bộ môi trường deploy' } },
          { name: 'Figma', icon: 'custom:figma', mastery: 90, domain: { en: 'UI/UX & Branding', vi: 'Thiết kế UI/UX' }, project: { en: '2nd Prize UTH IT Logo', vi: 'Giải Nhì Logo CNTT UTH' }, desc: { en: 'Editorial grid layouts, wireframes & vector brand art', vi: 'Lưới bố cục tạp chí, wireframe & thiết kế nhận diện vector' } }
        ]
      },
      {
        id: 'backend',
        label: { en: 'Backend & ERP', vi: 'Backend & ERP' },
        hubName: { en: 'Backend / ERP', vi: 'Backend / ERP' },
        icon: 'server',
        iconColor: 'text-indigo-500',
        items: [
          { name: 'Laravel 11', icon: 'devicon-laravel-original colored', mastery: 95, domain: { en: 'Fullstack PHP', vi: 'Fullstack PHP' }, project: { en: 'hothanhthien.io.vn CMS', vi: 'CMS hothanhthien.io.vn' }, desc: { en: 'Blade SSR, Eloquent ORM, dynamic sitemaps & Auth', vi: 'Blade SSR, Eloquent ORM, sitemap động & phân quyền' } },
          { name: 'Spring Boot', icon: 'devicon-spring-original colored', mastery: 88, domain: { en: 'Java Microservices', vi: 'Java Backend' }, project: { en: 'Laundry Management API', vi: 'API Quản lý Giặt ủi' }, desc: { en: 'Spring Data JPA, Hibernate & stateless REST', vi: 'Spring Data JPA, Hibernate & RESTful API bảo mật' } },
          { name: 'FastAPI', icon: 'devicon-fastapi-plain colored', mastery: 86, domain: { en: 'Async Python API', vi: 'API Python Bất đồng bộ' }, project: { en: 'TourRist Inference Gateway', vi: 'Cổng API AI TourRist' }, desc: { en: 'Async ingestion, Pydantic validation & OpenAPI', vi: 'Nạp tài liệu bất đồng bộ, Pydantic & OpenAPI docs' } },
          { name: 'Odoo ORM', icon: 'custom:odoo', mastery: 85, domain: { en: 'ERP Workflows', vi: 'Nghiệp vụ ERP' }, project: { en: 'Enmasys Enterprise ERP', vi: 'Thực tập Enmasys ERP' }, desc: { en: 'B2B Sales workflow mapping & accounting customization', vi: 'Chuẩn hóa quy trình Sales B2B & kế toán doanh nghiệp' } },
          { name: 'JWT & RBAC', icon: 'custom:jwt', mastery: 90, domain: { en: 'Auth Security', vi: 'Bảo mật & Phân quyền' }, project: { en: 'Laundry Security Guard', vi: 'Lớp bảo vệ JWT Giặt ủi' }, desc: { en: 'Token lifecycle, OTP verification & role guards', vi: 'Vòng đời token, xác thực OTP & kiểm soát vai trò' } }
        ]
      },
      {
        id: 'rag',
        label: { en: 'RAG AI & Databases', vi: 'RAG AI & Cơ sở Dữ liệu' },
        hubName: { en: 'RAG & Data', vi: 'RAG & Dữ liệu' },
        icon: 'database',
        iconColor: 'text-sky-500',
        items: [
          { name: 'Vector Search', icon: 'custom:vector', mastery: 88, domain: { en: 'Semantic Retrieval', vi: 'Tìm kiếm Ngữ nghĩa' }, project: { en: 'TourRist Knowledge Store', vi: 'Kho Tri thức TourRist' }, desc: { en: 'ChromaDB HNSW cosine similarity indexing', vi: 'Đánh chỉ mục ChromaDB & truy xuất tương đồng cosine' } },
          { name: 'LLM Grounding', icon: 'custom:llm', mastery: 86, domain: { en: 'RAG Pipeline', vi: 'Chuỗi Xử lý RAG' }, project: { en: 'Zero-Hallucination Chatbot', vi: 'Chatbot Chống Ảo giác' }, desc: { en: 'Context assembly strictly bound to private corpus', vi: 'Tổng hợp ngữ cảnh chặt chẽ từ tài liệu nội bộ' } },
          { name: 'MySQL 8.0', icon: 'custom:mysql', mastery: 90, domain: { en: 'Relational Engine', vi: 'CSDL Quan hệ' }, project: { en: 'Portfolio & Laundry DB', vi: 'CSDL Portfolio & Giặt ủi' }, desc: { en: '3NF normalization, foreign keys & index tuning', vi: 'Chuẩn hóa 3NF, khóa ngoại & tối ưu chỉ mục' } },
          { name: 'PostgreSQL', icon: 'devicon-postgresql-plain colored', mastery: 86, domain: { en: 'Enterprise SQL', vi: 'SQL Doanh nghiệp' }, project: { en: 'AIPower & Odoo ERP', vi: 'Hệ thống AIPower & Odoo' }, desc: { en: 'Relational views, JSONB indexing & reliability', vi: 'View quan hệ, JSONB & đảm bảo toàn vẹn dữ liệu' } },
          { name: 'SQLite', icon: 'custom:sqlite', mastery: 92, domain: { en: 'Embedded SQL', vi: 'CSDL Nhúng' }, project: { en: 'CI/CD Testing & Dev', vi: 'Kiểm thử & Phát triển' }, desc: { en: 'WAL concurrency & fast in-memory automated tests', vi: 'Chế độ WAL & chạy test tự động tốc độ cao' } }
        ]
      },
      {
        id: 'frontend',
        label: { en: 'Frontend & UI/UX', vi: 'Frontend & UI/UX' },
        hubName: { en: 'Frontend / UI', vi: 'Giao diện UI' },
        icon: 'layers',
        iconColor: 'text-emerald-500',
        items: [
          { name: 'Next.js', icon: 'custom:nextjs', mastery: 86, domain: { en: 'React SSR', vi: 'React SSR' }, project: { en: 'Laundry Customer Portal', vi: 'Cổng Khách hàng Giặt ủi' }, desc: { en: 'Server rendering, order tracking & API integration', vi: 'SSR, theo dõi trạng thái đơn hàng & tích hợp API' } },
          { name: 'JavaScript ES6+', icon: 'custom:js', mastery: 92, domain: { en: 'Interactive DOM', vi: 'Tương tác DOM' }, project: { en: 'Orbit Wheel & Terminal UI', vi: 'Vòng xoay 3D & Terminal' }, desc: { en: 'Trigonometry orbital math, spotlight & typewriter FX', vi: 'Toán học lượng giác quỹ đạo, spotlight & hiệu ứng gõ chữ' } },
          { name: 'Tailwind CSS', icon: 'devicon-tailwindcss-original colored', mastery: 94, domain: { en: 'Design Systems', vi: 'Hệ thống Thiết kế' }, project: { en: 'Pearl White Portfolio', vi: 'Giao diện Portfolio Sáng' }, desc: { en: 'Responsive bento layout & clean dark/light modes', vi: 'Bố cục bento đáp ứng & chế độ Sáng/Tối đồng bộ' } },
          { name: 'Blade Engine', icon: 'devicon-laravel-original colored', mastery: 96, domain: { en: 'SSR Components', vi: 'Component SSR' }, project: { en: 'hothanhthien.io.vn Core', vi: 'Lõi hothanhthien.io.vn' }, desc: { en: 'Zero hydration lag modular views & i18n bindings', vi: 'Hiển thị tức thì không độ trễ & gắn kết đa ngôn ngữ' } },
          { name: 'UI/UX Craft', icon: 'custom:figma', mastery: 90, domain: { en: 'Visual Identity', vi: 'Nhận diện Thương hiệu' }, project: { en: '2nd Prize Logo UTH', vi: 'Giải Nhì Logo CNTT UTH' }, desc: { en: 'Typography hierarchy, WCAG contrast & micro-UX', vi: 'Phân cấp chữ, độ tương phản WCAG & vi tương tác' } }
        ]
      },
      {
        id: 'devops',
        label: { en: 'DevOps & Architecture', vi: 'DevOps & Kiến trúc' },
        hubName: { en: 'DevOps / Ops', vi: 'Vận hành' },
        icon: 'box',
        iconColor: 'text-rose-500',
        items: [
          { name: 'Docker Compose', icon: 'devicon-docker-plain colored', mastery: 86, domain: { en: 'Container Ops', vi: 'Vận hành Container' }, project: { en: 'Local & AI Stacks', vi: 'Môi trường Dev & AI' }, desc: { en: 'Multi-container services & environment parity', vi: 'Đa dịch vụ container & đồng bộ dev-to-prod' } },
          { name: 'Nginx', icon: 'devicon-nginx-original colored', mastery: 86, domain: { en: 'Web Server', vi: 'Máy chủ Web' }, project: { en: 'hothanhthien.io.vn Prod', vi: 'Production hothanhthien.io.vn' }, desc: { en: 'Reverse proxy, SSL termination & HTTP/2 tuning', vi: 'Reverse proxy, chứng chỉ SSL & tối ưu HTTP/2' } },
          { name: 'Git & GitHub', icon: 'devicon-git-plain colored', mastery: 92, domain: { en: 'Version Control', vi: 'Quản lý Mã nguồn' }, project: { en: '10+ Production Repos', vi: '10+ Repo Thực tế' }, desc: { en: 'Semantic commits, clean branches & PR workflows', vi: 'Commit chuẩn semantic & quản lý nhánh sạch sẽ' } },
          { name: 'Linux / Cron', icon: 'custom:linux', mastery: 88, domain: { en: 'SysAdmin', vi: 'Quản trị Hệ thống' }, project: { en: 'cPanel Scheduled Jobs', vi: 'Tác vụ Tự động cPanel' }, desc: { en: 'Automated schedulers, queues & bash diagnostics', vi: 'Lập lịch cron tự động, worker & xử lý log hệ thống' } }
        ]
      }
    ];

    // 6. TERMINAL COMMANDS & TYPEWRITER ENGINE
    const CLI_RESPONSES = {
      whoami: {
        cmd: 'whoami',
        en: `> engineer: Ho Thanh Thien (Hồ Thành Thiện)
> degree:   B.Eng Information Technology @ UTH [Distinction]
> term_gpa: 4.0 / 4.0 (Latest Semester Perfect Score)
> roles:    Full-Stack Dev @ AIPower | Odoo ERP @ Enmasys
> location: Ho Chi Minh City, Vietnam`,
        vi: `> kỹ_sư:    Hồ Thành Thiện
> bằng_cấp: Cử nhân CNTT @ ĐH GTVT TP.HCM (UTH) [Loại Xuất sắc]
> điểm_gpa: 4.0 / 4.0 (Kỳ gần nhất đạt điểm tuyệt đối)
> kinh_nghiệm: Full-Stack @ AIPower | Odoo ERP @ Enmasys
> địa_điểm: TP. Hồ Chí Minh, Việt Nam`
      },
      skills: {
        cmd: 'skills --top',
        en: `[✓] Backend:   Java Spring Boot, PHP Laravel 11, FastAPI, Odoo ERP
[✓] AI / Data: Python RAG Pipelines, ChromaDB, Vector Search, MySQL/PostgreSQL
[✓] Frontend:  ReactJS, Next.js, Tailwind CSS, Blade SSR, ES6+
[✓] Infra:     Docker Compose, Nginx, Git, Linux & cPanel Deployment`,
        vi: `[✓] Backend:   Java Spring Boot, PHP Laravel 11, FastAPI, Odoo ERP
[✓] AI / Data: Pipeline Python RAG, ChromaDB, Vector Search, MySQL/PostgreSQL
[✓] Frontend:  ReactJS, Next.js, Tailwind CSS, Blade SSR, ES6+
[✓] Hạ tầng:   Docker Compose, Nginx, Git, Linux & Triển khai cPanel`
      },
      awards: {
        cmd: 'awards --verify',
        en: `★ GPA Achievement: 4.0 / 4.0 Latest Semester | 3.6 / 4.0 Cumulative
★ Graduation:      Bachelor of IT — Excellent Honors (UTH)
★ Design Honor:    2nd Prize — UTH IT Faculty Logo & Visual Identity`,
        vi: `★ Học tập:    GPA Kỳ gần nhất 4.0 / 4.0 | Tích lũy 3.6 / 4.0
★ Tốt nghiệp: Cử nhân CNTT — Loại Xuất sắc (ĐH GTVT TP.HCM)
★ Thiết kế:   Giải Nhì Cuộc thi Sáng tác Logo Khoa CNTT UTH`
      },
      rag: {
        cmd: 'rag --status',
        en: `PIPELINE: TourRist Document RAG Engine
STATUS:   ● ONLINE (ChromaDB Vector Index Active)
METRICS:  Cosine Top-K Match 98.4% | Avg Retrieval Latency ~180ms`,
        vi: `HỆ THỐNG: Động cơ RAG Tài liệu TourRist
TRẠNG THÁI: ● ĐANG HOẠT ĐỘNG (Chỉ mục Vector ChromaDB Sẵn sàng)
THÔNG SỐ:   Khớp Vector Top-K 98.4% | Độ trễ truy xuất ~180ms`
      }
    };

    let activeCliKey = 'whoami';

    function runTerminalCmd(key) {
      activeCliKey = key;
      const payload = CLI_RESPONSES[key];
      if (!payload) return;

      document.querySelectorAll('.cli-cmd-btn').forEach(btn => {
        const isActive = btn.getAttribute('data-cli-key') === key;
        btn.className = isActive
          ? 'cli-cmd-btn px-2.5 py-1 rounded-lg bg-sky-500/15 text-sky-400 border border-sky-500/30 font-mono text-[11px] font-semibold hover:bg-sky-500 hover:text-white transition-colors'
          : 'cli-cmd-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700 font-mono text-[11px] font-semibold hover:border-sky-400 hover:text-white transition-colors';
      });

      document.getElementById('cliTypedCommand').textContent = payload.cmd;
      const outputEl = document.getElementById('cliOutputBody');
      const text = payload[currentLang];

      if (typewriterTimer) clearInterval(typewriterTimer);
      outputEl.textContent = '';

      let i = 0;
      typewriterTimer = setInterval(() => {
        if (i < text.length) {
          i = Math.min(text.length, i + 2);
          outputEl.textContent = text.slice(0, i);
        } else {
          outputEl.textContent = text;
          const cursor = document.createElement('span');
          cursor.className = 'cli-cursor';
          outputEl.appendChild(cursor);
          clearInterval(typewriterTimer);
        }
      }, 14);
    }

    // 7. SKILLS ENGINE (TABS, 3D SPIN/FLIP GRID, 3D ORBIT WHEEL & INSPECTOR)
    function renderSkillTabs() {
      const container = document.getElementById('skillCategoryTabs');
      container.innerHTML = '';

      SKILL_CATEGORIES.forEach((cat, idx) => {
        const isActive = idx === currentSkillCategory;
        const btn = document.createElement('button');
        btn.type = 'button';

        btn.className = isActive
          ? 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-sky-500 text-white shadow-lg shadow-sky-500/20 flex items-center gap-2 transition-all'
          : 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center gap-2 transition-all';

        const iconColor = isActive ? 'text-white' : cat.iconColor;
        btn.innerHTML = `<i data-lucide="${cat.icon}" class="w-3.5 h-3.5 ${iconColor}"></i><span>${cat.label[currentLang]}</span>`;

        btn.addEventListener('click', () => {
          if (currentSkillCategory !== idx) {
            switchCategoryAnimated(idx);
          }
        });
        container.appendChild(btn);
      });

      if (window.lucide) lucide.createIcons();
    }

    function switchCategoryAnimated(nextIndex) {
      if (currentSkillMode === 'grid') {
        const oldCards = document.querySelectorAll('#skillGridModeWrap .skill-grid-item');
        oldCards.forEach(c => c.classList.add('is-exiting'));
        setTimeout(() => {
          currentSkillCategory = nextIndex;
          activeOrbitSkillIndex = 0;
          renderSkillTabs();
          renderActiveSkillView();
        }, 210);
      } else {
        currentSkillCategory = nextIndex;
        activeOrbitSkillIndex = 0;
        renderSkillTabs();
        renderActiveSkillView();
      }
    }

    function switchSkillMode(mode, shouldRender = true) {
      currentSkillMode = mode;
      const btnGrid = document.getElementById('btnModeGrid');
      const btnOrbit = document.getElementById('btnModeOrbit');
      const gridWrap = document.getElementById('skillGridModeWrap');
      const orbitWrap = document.getElementById('skillOrbitModeWrap');

      if (mode === 'grid') {
        btnGrid.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 bg-sky-500 text-white shadow-sm transition-all';
        btnOrbit.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all';
        gridWrap.classList.remove('hidden');
        orbitWrap.classList.add('hidden');
      } else {
        btnOrbit.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 bg-sky-500 text-white shadow-sm transition-all';
        btnGrid.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all';
        gridWrap.classList.add('hidden');
        orbitWrap.classList.remove('hidden');
      }
      if (shouldRender) {
        renderActiveSkillView();
      }
    }

    function replaySkillsAnimation() {
      const btn = document.getElementById('btnSkillReplay');
      btn.classList.add('replay-spinning');
      renderActiveSkillView();
      setTimeout(() => btn.classList.remove('replay-spinning'), 620);
    }

    function renderActiveSkillView() {
      const cat = SKILL_CATEGORIES[currentSkillCategory];
      const items = cat.items;
      document.getElementById('activeSkillCountMeta').textContent = `${items.length} SKILLS`;

      if (currentSkillMode === 'grid') {
        render3DSpinFlipGrid(items);
      } else {
        render3DOrbitWheel(cat, items);
      }
    }

    const CUSTOM_BRAND_SVGS = @json(\App\Support\TechIcon::jsSvgMap());

    function renderSkillIcon(item, variant = 'grid') {
      const iconKey = item.icon || '';
      const boxSizes = {
        grid: { svg: 'w-6 h-6', odoo: 'w-8 h-5', orbitText: '' },
        orbit: { svg: 'w-4 h-4', odoo: 'w-6 h-3.5', orbitText: 'text-base sm:text-lg' },
        inspector: { svg: 'w-8 h-8', odoo: 'w-11 h-6', orbitText: '' }
      }[variant] || { svg: 'w-6 h-6', odoo: 'w-8 h-5', orbitText: '' };

      if (CUSTOM_BRAND_SVGS[iconKey]) {
        return CUSTOM_BRAND_SVGS[iconKey]
          .replace(/__SZ__/g, boxSizes.svg)
          .replace(/__ODOO__/g, boxSizes.odoo);
      }

      const cls = boxSizes.orbitText ? `${iconKey} ${boxSizes.orbitText}` : iconKey;
      return `<i class="${cls}"></i>`;
    }

    function render3DSpinFlipGrid(items) {
      const wrap = document.getElementById('skillGridModeWrap');
      wrap.innerHTML = '';

      items.forEach((item, index) => {
        const card = document.createElement('div');
        card.className = 'skill-grid-item';
        card.style.setProperty('--index', index);
        card.style.animationDelay = `${index * 55}ms`;

        card.innerHTML = `
          <div class="grid-flip-inner">
            <!-- Front Face -->
            <div class="grid-flip-front spotlight-card bg-slate-50/70 dark:bg-slate-950/70 border border-slate-200/80 dark:border-slate-800 p-4 flex flex-col justify-between">
              <div class="flex items-start justify-between relative z-10">
                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center text-2xl shadow-sm spin-icon-target">
                  ${renderSkillIcon(item, 'grid')}
                </div>
                <span class="font-mono text-[11px] font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-500/15 px-2 py-0.5 rounded-md">${item.mastery}%</span>
              </div>
              <div class="relative z-10 text-left">
                <div class="font-display font-bold text-sm text-slate-900 dark:text-white">${item.name}</div>
                <div class="font-mono text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5">${item.domain[currentLang]}</div>
              </div>
            </div>

            <!-- Back Face (180deg Flipped Project Proof) -->
            <div class="grid-flip-back bg-gradient-to-br from-slate-900 via-slate-800 to-sky-950 text-white p-4 flex flex-col justify-between border border-sky-400/40 shadow-lg">
              <div class="space-y-1.5 text-left">
                <div class="font-mono text-[10px] uppercase tracking-wider text-sky-300 font-bold leading-snug line-clamp-1">${item.project[currentLang]}</div>
                <p class="text-[11px] text-slate-200 leading-relaxed line-clamp-3">${item.desc[currentLang]}</p>
              </div>
              <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-700/70 font-mono text-[10px]">
                <span class="inline-flex items-center gap-1.5 text-emerald-300 font-semibold whitespace-nowrap">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                  <span>${currentLang === 'vi' ? 'Đã áp dụng' : 'Production Proof'}</span>
                </span>
                <span class="text-sky-300/90 font-bold truncate">${item.name}</span>
              </div>
            </div>
          </div>
        `;

        card.addEventListener('click', () => card.classList.toggle('is-flipped'));
        wrap.appendChild(card);
      });
    }

    let isOrbitManualPaused = false;

    function updateOrbitSpinLabel() {
      const label = document.getElementById('orbitSpinToggleLabel');
      const pauseIcon = document.getElementById('orbitSpinPauseIcon');
      const playIcon = document.getElementById('orbitSpinPlayIcon');
      if (pauseIcon) pauseIcon.classList.toggle('hidden', isOrbitManualPaused);
      if (playIcon) playIcon.classList.toggle('hidden', !isOrbitManualPaused);
      if (label) {
        label.textContent = isOrbitManualPaused
          ? (currentLang === 'vi' ? 'Tạm dừng' : 'Paused')
          : (currentLang === 'vi' ? 'Đang xoay' : 'Auto Orbit');
      }
    }

    function toggleOrbitSpin() {
      const stageBox = document.getElementById('orbitStageBox');
      isOrbitManualPaused = !isOrbitManualPaused;
      if (stageBox) {
        stageBox.classList.toggle('is-manual-paused', isOrbitManualPaused);
      }
      updateOrbitSpinLabel();
    }

    function syncOrbitGeometry(nodeCount) {
      const stageBox = document.getElementById('orbitStageBox');
      if (!stageBox) return 168;

      const boxWidth = stageBox.clientWidth || window.innerWidth;
      const isCompact = boxWidth < 460;
      const pillAllowance = isCompact ? 104 : 136;
      const safeRadius = Math.max(76, Math.min(168, Math.floor((boxWidth - pillAllowance) / 2)));

      stageBox.style.setProperty('--radius', `${safeRadius}px`);
      stageBox.style.minHeight = `${Math.max(300, safeRadius * 2 + (isCompact ? 116 : 138))}px`;

      const outerRing = document.getElementById('orbitOuterRing');
      const innerRing = document.getElementById('orbitInnerRing');
      if (outerRing) {
        const dOuter = Math.max(150, safeRadius * 2 - 4);
        outerRing.style.width = `${dOuter}px`;
        outerRing.style.height = `${dOuter}px`;
      }
      if (innerRing) {
        const dInner = Math.max(112, Math.round(safeRadius * 1.25));
        innerRing.style.width = `${dInner}px`;
        innerRing.style.height = `${dInner}px`;
      }

      const readout = document.getElementById('orbitRadiusReadout');
      if (readout && typeof nodeCount === 'number') {
        readout.textContent = `R=${safeRadius}px • N=${nodeCount}`;
      }
      return safeRadius;
    }

    function render3DOrbitWheel(cat, items) {
      const stageBox = document.getElementById('orbitStageBox');
      const stage = document.getElementById('orbitNodesStage');
      const N = items.length;

      document.getElementById('orbitHubCategory').textContent = cat.hubName[currentLang];
      document.getElementById('orbitHubCount').textContent = currentLang === 'vi' ? `${N} Kỹ năng` : `${N} Skills`;

      if (stageBox) {
        stageBox.classList.remove('is-hover-paused');
      }

      stage.innerHTML = '';
      syncOrbitGeometry(N);
      // Synchronize outer ring rotation and child counter-rotation clocks at t=0
      stage.classList.remove('orbit-spinning-ring');
      void stage.offsetWidth;
      stage.classList.add('orbit-spinning-ring');

      items.forEach((item, index) => {
        const angle = Math.round((360 / items.length) * index);
        const node = document.createElement('div');
        node.className = `orbit-node ${index === activeOrbitSkillIndex ? 'is-active' : ''}`;
        node.style.setProperty('--angle', `${angle}deg`);
        node.style.animationDelay = `${index * 65}ms`;

        node.innerHTML = `
          <div class="orbit-node-upright">
            <div class="orbit-node-pill px-2 py-1 sm:px-2.5 sm:py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-700 shadow-md flex items-center gap-1 sm:gap-1.5">
              ${renderSkillIcon(item, 'orbit')}
              <span class="text-[10px] sm:text-xs font-bold text-slate-800 dark:text-slate-100 whitespace-nowrap">${item.name}</span>
            </div>
          </div>
        `;

        const handleInspect = () => {
          activeOrbitSkillIndex = index;
          stage.querySelectorAll('.orbit-node').forEach((n, idx) => n.classList.toggle('is-active', idx === index));
          updateOrbitInspector(item);
        };

        node.addEventListener('mouseenter', () => {
          if (stageBox) stageBox.classList.add('is-hover-paused');
          handleInspect();
        });
        node.addEventListener('mouseleave', () => {
          if (stageBox) stageBox.classList.remove('is-hover-paused');
        });
        node.addEventListener('click', handleInspect);
        stage.appendChild(node);
      });

      if (items[activeOrbitSkillIndex]) {
        updateOrbitInspector(items[activeOrbitSkillIndex]);
      }
      updateOrbitSpinLabel();
    }

    function updateOrbitInspector(item) {
      document.getElementById('inspectorIconBox').innerHTML = renderSkillIcon(item, 'inspector');
      document.getElementById('inspectorSkillName').textContent = item.name;
      document.getElementById('inspectorDomain').textContent = item.domain[currentLang];
      document.getElementById('inspectorPercent').textContent = `${item.mastery}%`;
      document.getElementById('inspectorProgressBar').style.width = `${item.mastery}%`;
      document.getElementById('inspectorProjectTitle').textContent = item.project[currentLang];
      document.getElementById('inspectorProjectDesc').textContent = item.desc[currentLang];
    }

    // 8. COMPLETE 100% LANGUAGE SWITCHER (EN | VI)
    function setLanguage(lang) {
      currentLang = lang;
      localStorage.setItem('thien_lang', lang);

      // Update active button state
      const btnEn = document.getElementById('btnLangEn');
      const btnVi = document.getElementById('btnLangVi');
      btnEn.className = lang === 'en'
        ? 'px-2.5 py-1 rounded-lg font-bold bg-white dark:bg-slate-700 text-sky-600 dark:text-sky-300 shadow-sm'
        : 'px-2.5 py-1 rounded-lg font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white';
      btnVi.className = lang === 'vi'
        ? 'px-2.5 py-1 rounded-lg font-bold bg-white dark:bg-slate-700 text-sky-600 dark:text-sky-300 shadow-sm'
        : 'px-2.5 py-1 rounded-lg font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white';

      // Translate every [data-i18n] element synchronously
      const dict = I18N[lang];
      document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (dict[key]) {
          el.textContent = dict[key];
        }
      });

      // Update input placeholders & dynamic UI states
      document.getElementById('copilotInput').placeholder = lang === 'vi'
        ? 'Hỏi về GPA UTH, RAG AI, Enmasys...'
        : 'Ask about GPA, RAG AI, Enmasys...';

      updateThemeUI(document.documentElement.classList.contains('dark'));
      updateOrbitSpinLabel();
      renderSkillTabs();
      renderActiveSkillView();
      renderCopilotPrompts();
      runTerminalCmd(activeCliKey);

      // Sync Laravel backend locale quietly without full page flicker if running on Laravel
      try { fetch(`/lang/${lang}`, { method: 'GET', credentials: 'same-origin' }); } catch (e) {}
    }

    // 9. FLOATING RAG AI COPILOT ENGINE
    const COPILOT_QA = [
      {
        label: { en: 'UTH GPA & Honors?', vi: 'GPA & Bằng cấp UTH?' },
        answer: {
          en: 'Ho Thanh Thien graduated with Distinction (Excellent Classification) in Information Technology at University of Transport HCMC (UTH), achieving a 3.6/4.0 cumulative GPA, a perfect 4.0/4.0 latest semester GPA, and 2nd Prize in the UTH IT Faculty Logo Design competition.',
          vi: 'Hồ Thành Thiện tốt nghiệp Kỹ sư CNTT Loại Xuất sắc tại ĐH Giao thông Vận tải TP.HCM (UTH) với GPA tích lũy 3.6/4.0, đạt 4.0/4.0 kỳ gần nhất và Giải Nhì Sáng tác Logo Nhận diện Khoa CNTT UTH.'
        }
      },
      {
        label: { en: 'TourRist RAG AI?', vi: 'Kiến trúc RAG TourRist?' },
        answer: {
          en: 'TourRist is a document-grounded RAG AI chatbot engineered with Python, FastAPI, ChromaDB vector search, and chunking pipelines. It pairs semantic cosine similarity lookup (98.4% vector match, ~180ms retrieval) with strict LLM prompt grounding to eliminate hallucinations.',
          vi: 'TourRist là chatbot RAG AI dựa trên tài liệu nội bộ, xây dựng bằng Python, FastAPI và ChromaDB vector search. Hệ thống đạt tỷ lệ khớp vector 98.4% (~180ms độ trễ), ràng buộc LLM chỉ trả lời theo ngữ cảnh tài liệu nhằm loại bỏ ảo giác (hallucination).'
        }
      },
      {
        label: { en: 'Enmasys & AIPower?', vi: 'Kinh nghiệm Enmasys & AIPower?' },
        answer: {
          en: 'At Enmasys Enterprise Internship (03/2026–07/2026), Thien analyzed business workflows and configured Odoo ERP modules for Retail, Attendance, Accounting, and B2B Sales. At AIPower (06/2026–10/2026), he shipped full-stack corporate & education web applications using PHP, WordPress, JavaScript, and PostgreSQL.',
          vi: 'Tại Enmasys (03/2026–07/2026), Thiện cấu hình các phân hệ Odoo ERP (Bán lẻ, Chấm công, Kế toán, B2B Sales). Tại AIPower (06/2026–10/2026), anh phát triển hệ thống web giáo dục & doanh nghiệp bằng PHP, WordPress, JS và PostgreSQL.'
        }
      },
      {
        label: { en: 'Tech Stack & Contact?', vi: 'Stack & Thông tin liên hệ?' },
        answer: {
          en: 'Core stack: Java Spring Boot, PHP Laravel 11, Python/FastAPI, Odoo ERP, ReactJS/Next.js, MySQL/PostgreSQL & Docker/Nginx. Currently available in Ho Chi Minh City! Reach out at hothanhthien119@gmail.com or +84 375 227 764.',
          vi: 'Công nghệ cốt lõi: Java Spring Boot, PHP Laravel 11, Python/FastAPI, Odoo ERP, ReactJS/Next.js, MySQL/PostgreSQL & Docker/Nginx. Hiện sẵn sàng nhận việc tại TP.HCM! Email: hothanhthien119@gmail.com | SĐT: +84 375 227 764.'
        }
      }
    ];

    function toggleCopilot() {
      const panel = document.getElementById('ragCopilotPanel');
      panel.classList.toggle('hidden');
      if (window.lucide) lucide.createIcons();
    }

    function renderCopilotPrompts() {
      const wrap = document.getElementById('copilotQuickPrompts');
      wrap.innerHTML = '';
      COPILOT_QA.forEach((item, idx) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'px-2.5 py-1 rounded-lg bg-sky-50 dark:bg-sky-500/15 border border-sky-200/80 dark:border-sky-500/30 text-[11px] font-mono font-semibold text-sky-700 dark:text-sky-300 hover:bg-sky-500 hover:text-white transition-colors';
        btn.textContent = item.label[currentLang];
        btn.addEventListener('click', () => appendCopilotTurn(item.label[currentLang], item.answer[currentLang]));
        wrap.appendChild(btn);
      });
    }

    function handleCopilotSubmit(e) {
      e.preventDefault();
      const input = document.getElementById('copilotInput');
      const query = input.value.trim();
      if (!query) return;
      input.value = '';

      const qLower = query.toLowerCase();
      let matched = COPILOT_QA[3].answer[currentLang];
      if (qLower.includes('gpa') || qLower.includes('uth') || qLower.includes('điểm') || qLower.includes('bằng') || qLower.includes('logo')) {
        matched = COPILOT_QA[0].answer[currentLang];
      } else if (qLower.includes('rag') || qLower.includes('ai') || qLower.includes('tourrist') || qLower.includes('vector')) {
        matched = COPILOT_QA[1].answer[currentLang];
      } else if (qLower.includes('enmasys') || qLower.includes('odoo') || qLower.includes('aipower') || qLower.includes('kinh nghiệm') || qLower.includes('work')) {
        matched = COPILOT_QA[2].answer[currentLang];
      }
      appendCopilotTurn(query, matched);
    }

    function appendCopilotTurn(userMsg, aiMsg) {
      const box = document.getElementById('copilotMessages');

      const uBubble = document.createElement('div');
      uBubble.className = 'ml-auto max-w-[85%] p-2.5 rounded-2xl rounded-tr-sm bg-sky-500 text-white font-medium shadow-sm';
      uBubble.textContent = userMsg;
      box.appendChild(uBubble);

      const aBubble = document.createElement('div');
      aBubble.className = 'mr-auto max-w-[92%] p-3 rounded-2xl rounded-tl-sm bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-200 leading-relaxed shadow-sm space-y-1.5';

      const meta = document.createElement('div');
      meta.className = 'font-mono text-[10px] font-bold text-emerald-600 dark:text-emerald-400';
      meta.textContent = currentLang === 'vi'
        ? 'Cosine Match 98.4% • Nguồn CV Xác thực'
        : 'Cosine Match 98.4% • Verified CV Source';

      const body = document.createElement('div');
      body.textContent = aiMsg;

      aBubble.appendChild(meta);
      aBubble.appendChild(body);
      box.appendChild(aBubble);
      box.scrollTop = box.scrollHeight;
    }

    // 10. INITIAL BOOTSTRAP
    document.addEventListener('DOMContentLoaded', () => {
      switchSkillMode('grid', false);
      setLanguage(currentLang);
      if (window.lucide) lucide.createIcons();

      const orbitBox = document.getElementById('orbitStageBox');
      if (orbitBox && typeof ResizeObserver !== 'undefined') {
        const ro = new ResizeObserver(() => {
          if (currentSkillMode === 'orbit') {
            const items = SKILL_CATEGORIES[currentSkillCategory].items;
            syncOrbitGeometry(items.length);
          }
        });
        ro.observe(orbitBox);
      } else {
        window.addEventListener('resize', () => {
          if (currentSkillMode === 'orbit') {
            const items = SKILL_CATEGORIES[currentSkillCategory].items;
            syncOrbitGeometry(items.length);
          }
        });
      }
    });
  </script>
</body>
</html>
