@php
    $isVi = app()->getLocale() === 'vi';
    $activeNav = trim($__env->yieldContent('active_nav', ''));
    $navMode = trim($__env->yieldContent('nav_mode', 'subpage'));
    $isHomeNav = $navMode === 'home';
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
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="icon" type="image/webp" sizes="64x64" href="{{ asset('favicon.webp') }}">

  @yield('meta')
  @yield('structured_data')

  <!-- Preload Primary Variable WebFonts (Subsetted Latin WOFF2) -->
  <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/inter-400-latin.woff2') }}" crossorigin>
  <link rel="preload" as="font" type="font/woff2" href="{{ asset('fonts/space-grotesk-600-latin.woff2') }}" crossorigin>

  <!-- FOUC Prevention: apply stored theme before CSS paints -->
  <script>
    if (localStorage.getItem('thien_theme') === 'dark') {
      document.documentElement.classList.add('dark');
    }
  </script>

  @php
    $pearlCssPath = public_path('css/pearl.css');
    $pearlCssInline = is_file($pearlCssPath) ? file_get_contents($pearlCssPath) : '';
    $yieldedStyles = trim($__env->yieldContent('styles'));
  @endphp

  @if($pearlCssInline !== '')
    <style>{!! $pearlCssInline !!}@if($yieldedStyles !== ''){{ "\n" . $yieldedStyles }}@endif</style>
  @else
    <link rel="stylesheet" href="{{ asset('css/pearl.css') }}?v={{ @filemtime($pearlCssPath) ?: 1 }}">
    @if($yieldedStyles !== '')
      <style>{!! $yieldedStyles !!}</style>
    @endif
  @endif

  <!-- Self-Hosted Bundled Lucide Icons (Async post-paint) -->
  <script src="{{ asset('js/lucide-pearl.js') }}?v={{ @filemtime(public_path('js/lucide-pearl.js')) ?: 1 }}" async></script>
</head>
<body class="bg-tech-grid text-slate-900 dark:text-slate-100 antialiased selection:bg-sky-500/20 selection:text-sky-700 dark:selection:text-sky-300 relative overflow-x-clip min-h-screen flex flex-col">

  <!-- Static Pastel Ambient Backdrop (Zero GPU Blur Animation Cost) -->
  <div class="fixed inset-0 pointer-events-none -z-10 opacity-75 dark:opacity-45" style="background: radial-gradient(520px circle at 8% 8%, rgba(56, 189, 248, 0.16), transparent 65%), radial-gradient(560px circle at 92% 40%, rgba(99, 102, 241, 0.14), transparent 65%), radial-gradient(500px circle at 42% 94%, rgba(16, 185, 129, 0.12), transparent 65%);"></div>

  <!-- =========================================================================
       NAVIGATION HEADER (SYNCHRONIZED PEARL WHITE + DARK MODE TOGGLE)
  ========================================================================= -->
  <header class="sticky top-0 z-50 frosted-glass border-b border-slate-200/80 dark:border-slate-800/80 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">

      <!-- Brand Identity -->
      <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-display font-extrabold text-lg tracking-tight text-slate-900 dark:text-white group">
        <span class="w-9 h-9 rounded-xl overflow-hidden bg-gradient-to-br from-sky-500 to-indigo-600 shadow-md shadow-indigo-500/20 ring-1 ring-slate-900/5 dark:ring-white/15 flex-shrink-0 inline-flex items-center justify-center group-hover:scale-105 transition-transform">
          <img src="{{ asset('images/avatar-sm.webp') }}" width="36" height="36" alt="Ho Thanh Thien Logo" class="w-9 h-9 object-cover">
        </span>
        <span class="hidden sm:inline" data-i18n="brand_name">{{ $isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien' }}</span>
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" title="Available for Hire"></span>
      </a>

      <!-- Center Nav Links -->
      @if($isHomeNav)
        <nav class="hidden md:flex items-center gap-1 text-sm font-medium text-slate-600 dark:text-slate-300">
          <a href="#projects" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_projects">
            {{ $isVi ? 'Dự án Nổi bật' : 'Selected Work' }}
          </a>
          <a href="#skills" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_skills">
            {{ $isVi ? 'Kỹ năng' : 'Tech Stack' }}
          </a>
          <a href="#credentials" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_credentials">
            {{ $isVi ? 'Bằng cấp & PDF' : 'Credentials' }}
          </a>
          <a href="#blog" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_blog">
            {{ $isVi ? 'Blog Kỹ thuật' : 'Engineering Blog' }}
          </a>
          <a href="{{ route('projects.index') }}" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_all_projects">
            {{ $isVi ? 'Kho Dự án' : 'Archive' }}
          </a>
        </nav>
      @else
        <nav class="hidden md:flex items-center gap-1 text-sm font-medium text-slate-600 dark:text-slate-300">
          <a href="{{ route('about') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $activeNav === 'about' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" data-i18n="nav_about">
            {{ $isVi ? 'Giới thiệu' : 'About' }}
          </a>
          <a href="{{ route('projects.index') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $activeNav === 'projects' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" data-i18n="nav_all_projects">
            {{ $isVi ? 'Kho Dự án' : 'Projects' }}
          </a>
          <a href="{{ route('certificates') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $activeNav === 'credentials' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" data-i18n="nav_credentials">
            {{ $isVi ? 'Bằng cấp & PDF' : 'Credentials' }}
          </a>
          <a href="{{ route('blog.index') }}" class="px-3 py-1.5 rounded-lg transition-colors {{ $activeNav === 'blog' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}" data-i18n="nav_blog">
            {{ $isVi ? 'Blog Kỹ thuật' : 'Engineering Blog' }}
          </a>
          <a href="{{ route('home') }}#skills" class="px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition-colors" data-i18n="nav_skills">
            {{ $isVi ? 'Kỹ năng' : 'Tech Stack' }}
          </a>
        </nav>
      @endif

      <!-- Right Controls: Language Switcher + Dark Mode Toggle + Contact CTA -->
      <div class="flex items-center gap-2">
        <div class="inline-flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700 font-mono text-xs">
          <button type="button" id="btnLangEn" onclick="setLanguage('en', true)" class="px-2.5 py-1 rounded-lg font-bold transition-all">EN</button>
          <button type="button" id="btnLangVi" onclick="setLanguage('vi', true)" class="px-2.5 py-1 rounded-lg font-bold transition-all">VI</button>
        </div>

        <button type="button" id="themeToggleBtn" onclick="toggleTheme()" aria-label="{{ $isVi ? 'Chuyển giao diện Sáng hoặc Tối' : 'Toggle Light or Dark theme' }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all shadow-sm">
          <i data-lucide="moon" id="themeIconDark" class="w-3.5 h-3.5 text-indigo-600"></i>
          <i data-lucide="sun" id="themeIconLight" class="w-3.5 h-3.5 text-amber-400 hidden"></i>
          <span id="themeToggleLabel" class="hidden sm:inline">Dark BG</span>
        </button>

        <a href="{{ route('contact') }}" @if($activeNav === 'contact') aria-current="page" @endif class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl {{ $activeNav === 'contact' ? 'bg-sky-700 text-white shadow-lg shadow-sky-700/20 hover:bg-sky-800' : 'bg-slate-900 dark:bg-sky-500 text-white hover:bg-slate-800 dark:hover:bg-sky-600 shadow-sm' }} text-xs font-semibold transition-all" data-i18n="nav_hire">
          {{ $isVi ? 'Liên hệ ngay' : 'Hire Me' }}
        </a>

        <button type="button" id="mobileMenuBtn" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200" aria-label="Menu">
          <i data-lucide="menu" class="w-4 h-4"></i>
        </button>
      </div>

    </div>

    <!-- Mobile Dropdown Navigation -->
    <div id="mobileMenuPanel" class="hidden md:hidden border-t border-slate-200/80 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-4 py-3 space-y-1 text-sm font-medium text-slate-700 dark:text-slate-200">
      @if($isHomeNav)
        <a href="#projects" onclick="closeMobileMenu()" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_projects">Selected Work</a>
        <a href="#skills" onclick="closeMobileMenu()" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_skills">Tech Stack</a>
        <a href="#credentials" onclick="closeMobileMenu()" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_credentials">Credentials</a>
        <a href="#blog" onclick="closeMobileMenu()" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_blog">Engineering Blog</a>
        <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_all_projects">Archive</a>
      @else
        <a href="{{ route('about') }}" class="block px-3 py-2 rounded-xl {{ $activeNav === 'about' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}" data-i18n="nav_about">About</a>
        <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-xl {{ $activeNav === 'projects' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}" data-i18n="nav_all_projects">Projects</a>
        <a href="{{ route('certificates') }}" class="block px-3 py-2 rounded-xl {{ $activeNav === 'credentials' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}" data-i18n="nav_credentials">Credentials</a>
        <a href="{{ route('blog.index') }}" class="block px-3 py-2 rounded-xl {{ $activeNav === 'blog' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 font-semibold' : 'hover:bg-slate-100 dark:hover:bg-slate-800' }}" data-i18n="nav_blog">Engineering Blog</a>
        <a href="{{ route('home') }}#skills" class="block px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800" data-i18n="nav_skills">Tech Stack</a>
      @endif
      <a href="{{ route('contact') }}" @if($activeNav === 'contact') aria-current="page" @endif class="block px-3 py-2 rounded-xl {{ $activeNav === 'contact' ? 'bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400' : 'bg-slate-900 dark:bg-sky-500 text-white' }} text-center font-semibold mt-2" data-i18n="nav_hire">Hire Me</a>
    </div>
  </header>

  <main class="@yield('main_class', 'flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-10')">
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
            <img src="{{ asset('images/avatar-sm.webp') }}" width="24" height="24" alt="Ho Thanh Thien Logo" class="w-6 h-6 object-cover">
          </span>
          <span data-i18n="brand_name">{{ $isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien' }}</span>
        </div>
        <p class="text-xs font-mono text-slate-600 dark:text-slate-400" data-i18n="footer_subtitle">
          Full-Stack Web Systems &amp; Applied RAG AI Engineer &bull; Ho Chi Minh City, Vietnam
        </p>
      </div>

      <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-xs font-mono text-slate-700 dark:text-slate-300">
        <a href="mailto:hothanhthien119@gmail.com" class="inline-flex items-center gap-1.5 py-1.5 hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
          <i data-lucide="mail" class="w-3.5 h-3.5 text-sky-700 dark:text-sky-400"></i>
          <span>hothanhthien119@gmail.com</span>
        </a>
        <a href="tel:+84375227764" class="inline-flex items-center gap-1.5 py-1.5 hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
          <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-700 dark:text-emerald-400"></i>
          <span>+84 375 227 764</span>
        </a>
        <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 py-1.5 hover:text-sky-700 dark:hover:text-sky-400 transition-colors">
          <i data-lucide="github" class="w-3.5 h-3.5"></i>
          <span>GitHub</span>
        </a>
      </div>
    </div>
  </footer>

  @yield('floating_widgets')

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
      const btn = document.getElementById('themeToggleBtn');
      if (iconDark) iconDark.classList.toggle('hidden', isDark);
      if (iconLight) iconLight.classList.toggle('hidden', !isDark);
      const nextText = isDark
        ? (currentLang === 'vi' ? 'Nền Trắng' : 'White BG')
        : (currentLang === 'vi' ? 'Nền Tối' : 'Dark BG');
      if (label) {
        label.textContent = nextText;
      }
      if (btn) {
        btn.setAttribute('aria-label', nextText);
      }
    }

    // 3. Mobile Nav Toggle
    function toggleMobileMenu() {
      const panel = document.getElementById('mobileMenuPanel');
      if (panel) panel.classList.toggle('hidden');
    }

    function closeMobileMenu() {
      const panel = document.getElementById('mobileMenuPanel');
      if (panel) panel.classList.add('hidden');
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
      window.currentLang = lang;
      localStorage.setItem('thien_lang', lang);

      const btnEn = document.getElementById('btnLangEn');
      const btnVi = document.getElementById('btnLangVi');
      if (btnEn && btnVi) {
        btnEn.className = lang === 'en'
          ? 'px-2.5 py-1 rounded-lg font-bold bg-white dark:bg-slate-700 text-sky-700 dark:text-sky-300 shadow-sm'
          : 'px-2.5 py-1 rounded-lg font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white';
        btnVi.className = lang === 'vi'
          ? 'px-2.5 py-1 rounded-lg font-bold bg-white dark:bg-slate-700 text-sky-700 dark:text-sky-300 shadow-sm'
          : 'px-2.5 py-1 rounded-lg font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white';
      }

      const dict = getMergedDict(lang);
      const isInitialMatchingSsr = !syncSession && lang === (document.documentElement.lang || 'en');

      if (!isInitialMatchingSsr) {
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
      }

      updateThemeUI(document.documentElement.classList.contains('dark'));

      if (typeof window.onPearlLangChange === 'function') {
        window.onPearlLangChange(lang, dict, syncSession);
      }

      if (syncSession) {
        if ((document.documentElement.lang || 'en') !== lang) {
          const url = new URL(window.location.href);
          url.searchParams.set('lang', lang);
          window.location.assign(url.toString());
          return;
        }
        try { fetch(`/lang/${lang}`, { method: 'GET', credentials: 'same-origin' }); } catch (e) {}
      }
    }
  </script>

  @yield('scripts')

  <script>
    window.setLanguage = setLanguage;
    document.addEventListener('DOMContentLoaded', () => {
      const hydratePearl = () => {
        setTimeout(() => {
          window.__pearlHydrated = true;
          setLanguage(currentLang, false);
          if (window.lucide) lucide.createIcons();
        }, 0);
      };
      if (window.requestAnimationFrame) {
        requestAnimationFrame(hydratePearl);
      } else {
        hydratePearl();
      }
    });
  </script>
</body>
</html>
