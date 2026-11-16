@php
    $isVi = app()->getLocale() === 'vi';
    $activeNav = trim($__env->yieldContent('active_nav', ''));
    $defaultDesc = $isVi
        ? 'Portfolio Kỹ sư Phần mềm Hồ Thành Thiện — Chuyên sâu Web Systems (Laravel, Spring Boot, Odoo ERP) và Applied RAG AI.'
        : 'Software Engineer Portfolio of Ho Thanh Thien — Full-Stack Web Systems (Laravel, Spring Boot, Odoo ERP) & Applied RAG AI.';
@endphp
<!DOCTYPE html>
<html lang="{{ $isVi ? 'vi' : 'en' }}" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', ($isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien') . ' | Portfolio')</title>
  <meta name="description" content="@yield('description', $defaultDesc)">
  <meta name="keywords" content="@yield('keywords', 'Ho Thanh Thien, Software Engineer, Full-Stack Developer, Laravel, Spring Boot, RAG AI, Odoo ERP')">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="{{ url()->current() }}">

  <!-- OpenGraph Meta -->
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:title" content="@yield('title', ($isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien') . ' | Portfolio')">
  <meta property="og:description" content="@yield('description', $defaultDesc)">
  <meta property="og:image" content="@yield('og_image', asset('images/og-image.webp'))">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('title', ($isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien') . ' | Portfolio')">
  <meta name="twitter:description" content="@yield('description', $defaultDesc)">
  <meta name="twitter:image" content="@yield('og_image', asset('images/og-image.webp'))">

  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">

  @yield('meta')
  @yield('structured_data')

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
      --font-display: 'Space Grotesk', 'Inter', system-ui, sans-serif;
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

    @yield('styles')
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
       NAVIGATION HEADER (SYNCHRONIZED PEARL WHITE + DARK MODE TOGGLE)
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
        <a href="{{ route('about') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $activeNav === 'about' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" data-i18n="nav_about">
          {{ $isVi ? 'Giới thiệu' : 'About' }}
        </a>
        <a href="{{ route('projects.index') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $activeNav === 'projects' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" data-i18n="nav_all_projects">
          {{ $isVi ? 'Kho Dự án' : 'Projects' }}
        </a>
        <a href="{{ route('certificates') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $activeNav === 'credentials' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" data-i18n="nav_credentials">
          {{ $isVi ? 'Bằng cấp & PDF' : 'Credentials' }}
        </a>
        <a href="{{ route('blog.index') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $activeNav === 'blog' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" data-i18n="nav_blog">
          {{ $isVi ? 'Blog Kỹ thuật' : 'Engineering Blog' }}
        </a>
        <a href="{{ route('home') }}#skills" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_skills">
          {{ $isVi ? 'Kỹ năng' : 'Tech Stack' }}
        </a>
      </nav>

      <!-- Right Controls: Language Switcher + Dark Mode Toggle + Contact CTA -->
      <div class="flex items-center gap-2">
        <div class="inline-flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700 font-mono text-xs">
          <button type="button" id="btnLangEn" onclick="setLanguage('en', true)" class="px-2.5 py-1 rounded-lg font-bold transition-all">EN</button>
          <button type="button" id="btnLangVi" onclick="setLanguage('vi', true)" class="px-2.5 py-1 rounded-lg font-bold transition-all">VI</button>
        </div>

        <button type="button" id="themeToggleBtn" onclick="toggleTheme()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all shadow-sm">
          <i data-lucide="moon" id="themeIconDark" class="w-3.5 h-3.5 text-indigo-500"></i>
          <i data-lucide="sun" id="themeIconLight" class="w-3.5 h-3.5 text-amber-400 hidden"></i>
          <span id="themeToggleLabel" class="hidden sm:inline">Dark BG</span>
        </button>

        <a href="{{ route('contact') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-semibold hover:bg-slate-800 dark:hover:bg-sky-600 transition-all shadow-sm" data-i18n="nav_hire">
          {{ $isVi ? 'Liên hệ ngay' : 'Hire Me' }}
        </a>

        <button type="button" id="mobileMenuBtn" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200" aria-label="Menu">
          <i data-lucide="menu" class="w-4 h-4"></i>
        </button>
      </div>

    </div>

    <!-- Mobile Dropdown Navigation -->
    <div id="mobileMenuPanel" class="hidden md:hidden border-t border-slate-200/80 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-4 py-3 space-y-1 text-sm font-medium text-slate-700 dark:text-slate-200">
      <a href="{{ route('about') }}" class="block px-3 py-2 rounded-xl {{ $activeNav === 'about' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}" data-i18n="nav_about">About</a>
      <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-xl {{ $activeNav === 'projects' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}" data-i18n="nav_all_projects">Projects</a>
      <a href="{{ route('certificates') }}" class="block px-3 py-2 rounded-xl {{ $activeNav === 'credentials' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}" data-i18n="nav_credentials">Credentials</a>
      <a href="{{ route('blog.index') }}" class="block px-3 py-2 rounded-xl {{ $activeNav === 'blog' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}" data-i18n="nav_blog">Engineering Blog</a>
      <a href="{{ route('home') }}#skills" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_skills">Tech Stack</a>
      <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-center font-semibold mt-2" data-i18n="nav_hire">Hire Me</a>
    </div>
  </header>

  <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10">
    @yield('content')
  </main>

  <!-- =========================================================================
       FOOTER (SYNCHRONIZED PEARL WHITE + DARK MODE TOGGLE)
  ========================================================================= -->
  <footer class="border-t border-slate-200/80 dark:border-slate-800/80 bg-white/70 dark:bg-slate-950/70 backdrop-blur-md py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
      <div class="space-y-1 text-center md:text-left">
        <div class="font-display font-extrabold text-base text-slate-900 dark:text-white flex items-center justify-center md:justify-start gap-2">
          <span class="w-6 h-6 rounded-lg overflow-hidden bg-gradient-to-br from-sky-500 to-indigo-600 inline-flex items-center justify-center shadow-sm">
            <img src="{{ asset('android-chrome-192x192.png') }}" alt="Logo" class="w-full h-full object-cover scale-[1.12]">
          </span>
          <span data-i18n="brand_name">{{ $isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien' }}</span>
        </div>
        <p class="text-xs font-mono text-slate-500 dark:text-slate-400" data-i18n="footer_subtitle">
          Full-Stack Web Systems &amp; Applied RAG AI Engineer &bull; Ho Chi Minh City, Vietnam
        </p>
      </div>

      <div class="flex flex-wrap items-center justify-center gap-4 text-xs font-mono text-slate-600 dark:text-slate-300">
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
       SHARED VANILLA JS ENGINE (SPOTLIGHT GLOW, THEME TOGGLE, EN/VI I18N)
  ========================================================================= -->
  <script>
    let currentLang = "{{ $isVi ? 'vi' : 'en' }}";

    // 1. Mouse-Tracking Spotlight Glow
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

    // 4. Synchronized Base EN / VI Dictionary (Merged with page-specific PAGE_I18N)
    const BASE_I18N = {
      en: {
        brand_name: 'Ho Thanh Thien',
        nav_about: 'About',
        nav_projects: 'Selected Work',
        nav_skills: 'Tech Stack',
        nav_credentials: 'Credentials',
        nav_blog: 'Engineering Blog',
        nav_all_projects: 'Projects',
        nav_hire: 'Hire Me',
        breadcrumb_home: 'Home',
        btn_back_home: 'Overview',
        btn_brochure: 'Brochure PDF',
        footer_subtitle: 'Full-Stack Web Systems & Applied RAG AI Engineer • Ho Chi Minh City, Vietnam'
      },
      vi: {
        brand_name: 'Hồ Thành Thiện',
        nav_about: 'Giới thiệu',
        nav_projects: 'Dự án Nổi bật',
        nav_skills: 'Kỹ năng',
        nav_credentials: 'Bằng cấp & PDF',
        nav_blog: 'Blog Kỹ thuật',
        nav_all_projects: 'Kho Dự án',
        nav_hire: 'Liên hệ ngay',
        breadcrumb_home: 'Trang chủ',
        btn_back_home: 'Trang chủ',
        btn_brochure: 'Brochure PDF',
        footer_subtitle: 'Kỹ sư Hệ thống Web Full-Stack & Ứng dụng RAG AI • TP. Hồ Chí Minh, Việt Nam'
      }
    };

    function getMergedDict(lang) {
      const base = BASE_I18N[lang] || BASE_I18N.en;
      const page = (window.PAGE_I18N && window.PAGE_I18N[lang]) ? window.PAGE_I18N[lang] : {};
      return Object.assign({}, base, page);
    }

    function setLanguage(lang, syncSession = false) {
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

      const dict = getMergedDict(lang);
      document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (dict[key]) {
          el.textContent = dict[key];
        }
      });

      document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        const key = el.getAttribute('data-i18n-placeholder');
        if (dict[key]) {
          el.setAttribute('placeholder', dict[key]);
        }
      });

      document.querySelectorAll('[data-en][data-vi]').forEach(el => {
        const val = lang === 'vi' ? el.getAttribute('data-vi') : el.getAttribute('data-en');
        if (val !== null) {
          el.textContent = val;
        }
      });

      document.querySelectorAll('[data-lang-block]').forEach(el => {
        el.classList.toggle('hidden', el.getAttribute('data-lang-block') !== lang);
      });

      updateThemeUI(document.documentElement.classList.contains('dark'));

      if (typeof window.onPearlLangChange === 'function') {
        window.onPearlLangChange(lang, dict);
      }

      if (syncSession) {
        try { fetch(`/lang/${lang}`, { method: 'GET', credentials: 'same-origin' }); } catch (e) {}
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      setLanguage(currentLang, false);
      if (window.lucide) lucide.createIcons();
    });
  </script>

  @yield('scripts')
</body>
</html>
