<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@php
    $currentLocale = app()->getLocale();
    $isVi = $currentLocale === 'vi';
    $currentBaseUrl = url()->current();
    $canonicalUrl = request()->has('lang') ? $currentBaseUrl . '?lang=' . $currentLocale : $currentBaseUrl;
    
    $defaultTitle = $isVi 
        ? 'Hồ Thành Thiện | Kỹ sư Full-Stack Web Developer' 
        : 'Ho Thanh Thien | Full-Stack Web Developer Portfolio';
        
    $defaultDescription = $isVi 
        ? 'Portfolio của Hồ Thành Thiện - Kỹ sư Full-Stack Developer chuyên xây dựng ứng dụng web hiện đại, kiến trúc tối ưu, bảo mật cao và trải nghiệm mượt mà.'
        : 'Portfolio of Ho Thanh Thien - Full-Stack Developer crafting modern, scalable, high-performance web applications with clean architecture and delightful user experiences.';
        
    $defaultKeywords = $isVi
        ? 'Hồ Thành Thiện, Kỹ sư Full-Stack, Lập trình viên Web, Laravel Developer Việt Nam, PHP Developer, JavaScript, Vue.js, React, Clean Architecture, Tuyển dụng lập trình viên, Web Portfolio'
        : 'Ho Thanh Thien, Full-Stack Developer, Web Developer, Laravel Software Engineer, PHP Developer Vietnam, JavaScript, Vue.js, React, Clean Code, Web Portfolio';
        
    $siteName = $isVi ? 'Hồ Thành Thiện | Hồ Sơ Năng Lực & Dự Án' : 'Ho Thanh Thien | Portfolio & Engineering';
@endphp
    <title>@yield('title', $defaultTitle)</title>

    <!-- Core SEO Meta Tags -->
    <meta name="description" content="@yield('description', $defaultDescription)">
    <meta name="keywords" content="@yield('keywords', $defaultKeywords)">
    <meta name="author" content="Hồ Thành Thiện">
    <meta http-equiv="content-language" content="{{ $currentLocale }}">
    <meta name="language" content="{{ $isVi ? 'Vietnamese' : 'English' }}">
    <meta name="geo.region" content="{{ $isVi ? 'VN-SG' : 'VN' }}">
    <meta name="geo.placename" content="Ho Chi Minh City, Vietnam">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="bingbot" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="theme-color" content="#1e40af">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Canonical & Multi-Language Alternate URLs (Hreflang for Google SEO) -->
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="vi" href="{{ $currentBaseUrl }}?lang=vi">
    <link rel="alternate" hreflang="en" href="{{ $currentBaseUrl }}?lang=en">
    <link rel="alternate" hreflang="x-default" href="{{ $currentBaseUrl }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ $isVi ? 'vi_VN' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ $isVi ? 'en_US' : 'vi_VN' }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', $defaultTitle)">
    <meta property="og:description" content="@yield('description', $defaultDescription)">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.webp'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="@yield('title', $defaultTitle)">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $defaultTitle)">
    <meta name="twitter:description" content="@yield('description', $defaultDescription)">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-image.webp'))">
    <meta name="twitter:creator" content="@hoThanhThien">

    <!-- Global Structured Data (Schema.org / JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "WebSite",
          "@id": "{{ url('/') }}#website",
          "url": "{{ url('/') }}",
          "name": "{{ $isVi ? 'Hồ Thành Thiện - Kỹ sư Full-Stack Web Developer' : 'Ho Thanh Thien - Full-Stack Software Engineer' }}",
          "description": "{{ $defaultDescription }}",
          "inLanguage": "{{ $isVi ? 'vi-VN' : 'en-US' }}"
        },
        {
          "@type": "Person",
          "@id": "{{ url('/') }}#person",
          "name": "Hồ Thành Thiện",
          "alternateName": "Ho Thanh Thien",
          "jobTitle": "{{ $isVi ? 'Kỹ sư Lập trình Full-Stack' : 'Full-Stack Software Engineer' }}",
          "url": "{{ url('/') }}",
          "image": "{{ asset('images/og-image.webp') }}",
          "email": "hothanhthien119@gmail.com",
          "telephone": "+84375227764",
          "alumniOf": {
            "@type": "CollegeOrUniversity",
            "name": "University of Transport Ho Chi Minh City"
          },
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Ho Chi Minh City",
            "addressCountry": "VN"
          },
          "sameAs": [
            "https://github.com/hoThanhThien",
            "https://www.linkedin.com/in/httblackhat"
          ],
          "knowsAbout": ["ReactJS", "Java", "Spring Boot", "PHP", "Laravel", "Python", "FastAPI", "Odoo", "RAG", "LLM", "Vector Search", "MySQL", "PostgreSQL", "RESTful API", "Full-Stack Development", "Clean Architecture"],
          "knowsLanguage": ["vi", "en"]
        }
      ]
    }
    </script>

    @if(request()->path() !== '/')
    @php
        $pathSegments = explode('/', trim(request()->path(), '/'));
        $breadcrumbs = [];
        $breadcrumbs[] = [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => $isVi ? 'Trang chủ' : 'Home',
            'item' => url('/') . ($isVi ? '?lang=vi' : '?lang=en')
        ];
        $cumulativePath = '';
        foreach ($pathSegments as $idx => $segment) {
            $cumulativePath .= '/' . $segment;
            $crumbName = ucfirst($segment);
            if ($segment === 'about') $crumbName = $isVi ? 'Giới thiệu' : 'About';
            elseif ($segment === 'contact') $crumbName = $isVi ? 'Liên hệ' : 'Contact';
            elseif ($segment === 'projects') $crumbName = $isVi ? 'Dự án' : 'Projects';
            elseif ($segment === 'blog') $crumbName = $isVi ? 'Bài viết' : 'Blog';
            elseif ($segment === 'certificates') $crumbName = $isVi ? 'Brochure & Giấy khen' : 'Credentials';
            
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $idx + 2,
                'name' => $crumbName,
                'item' => url($cumulativePath) . ($isVi ? '?lang=vi' : '?lang=en')
            ];
        }
    @endphp
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": {!! json_encode($breadcrumbs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    }
    </script>
    @endif

    @yield('structured_data')

    <!-- Performance & Font Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    <!-- IBM Plex Sans & IBM Plex Mono (Vietnamese-optimized engineering typography) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=IBM+Plex+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap">

    <!-- Font Awesome 6.5 Full Icon Set -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    <style>
        :root {
            /* Core Restrained Mineral Paper & Cobalt Graphite Palette */
            --bg-primary: #f7f8fa;
            --bg-secondary: #eef1f5;
            --bg-card: #ffffff;
            --border: #dce1e9;
            --border-strong: #c2cad6;
            --text-primary: #12161f;
            --text-secondary: #434d5e;
            --text-muted: #687385;
            --accent: #1e40af;
            --accent-hover: #1e3a8a;
            --accent-light: #1e40af;
            --accent-soft: rgba(30, 64, 175, 0.08);
            --accent-glow: rgba(30, 64, 175, 0.12);
            --green: #15803d;
            --danger: #b91c1c;
            --gradient: #12161f;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 16px;
            line-height: 1.65;
            overflow-x: hidden;
        }

        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
        }

        /* Project & Media Placeholders */
        .project-image-placeholder {
            width: 100%;
            height: 100%;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-secondary);
            color: var(--text-muted);
            font-size: 2rem;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border-strong); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* Navigation */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            height: 64px;
            padding: 0 2rem;
            background: rgba(247, 248, 250, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.96);
        }

        .nav-logo {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-primary);
            text-decoration: none;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1002;
            white-space: nowrap;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-logo::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 2px;
            background: var(--accent);
            display: inline-block;
        }

        .nav-links {
            display: flex;
            gap: 0.25rem;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 0.45rem 0.8rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.16s ease, background-color 0.16s ease;
            position: relative;
            white-space: nowrap;
        }

        .nav-links a:not(.btn):not(.lang-btn):hover {
            color: var(--text-primary);
            background: var(--bg-secondary);
        }
        .nav-links a:not(.btn):not(.lang-btn).active {
            color: var(--accent);
            font-weight: 600;
            background: transparent;
        }
        .nav-links a:not(.btn):not(.lang-btn).active::after {
            content: '';
            position: absolute;
            left: 0.8rem;
            right: 0.8rem;
            bottom: 0.15rem;
            height: 1.5px;
            background: var(--accent);
        }

        .nav-cta {
            background: var(--text-primary) !important;
            color: #ffffff !important;
            padding: 0.45rem 1rem !important;
            min-height: 36px !important;
            border-radius: 6px !important;
            margin-left: 0.35rem;
            font-size: 0.88rem !important;
            font-weight: 600 !important;
            white-space: nowrap;
        }
        .nav-cta:hover {
            background: #1e293b !important;
            color: #ffffff !important;
        }

        .lang-switcher {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            padding: 2px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 7px;
            margin-left: 0.5rem;
            flex-shrink: 0;
        }
        .lang-switcher .lang-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 5px !important;
            padding: 0.3rem 0.55rem !important;
            border-radius: 5px;
            text-decoration: none;
            color: var(--text-secondary) !important;
            font-weight: 600;
            font-size: 0.78rem;
            line-height: 1;
            transition: color 0.16s ease, background-color 0.16s ease;
            background: transparent !important;
            border: none !important;
            white-space: nowrap;
        }
        .lang-switcher .lang-btn:hover {
            color: var(--text-primary) !important;
        }
        .lang-switcher .lang-btn.active {
            color: var(--text-primary) !important;
            background: var(--bg-card) !important;
            box-shadow: 0 1px 2px rgba(18, 22, 31, 0.08) !important;
        }
        .flag-icon {
            border-radius: 1.5px;
            flex-shrink: 0;
            display: inline-block;
            vertical-align: middle;
        }

        @media (min-width: 1025px) and (max-width: 1200px) {
            .navbar { padding: 0 1.25rem; }
            .nav-links a { padding: 0.4rem 0.6rem; font-size: 0.85rem; }
            .nav-cta { padding: 0.4rem 0.85rem !important; font-size: 0.84rem !important; }
        }

        .hamburger {
            display: none;
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-primary);
            width: 40px; height: 40px; min-width: 40px;
            border-radius: 8px;
            align-items: center; justify-content: center;
            font-size: 1.05rem;
            cursor: pointer;
            transition: border-color 0.16s ease, background-color 0.16s ease;
            position: relative;
            z-index: 1002;
        }
        .hamburger:hover { background: var(--bg-secondary); border-color: var(--border-strong); }

        .nav-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(18, 22, 31, 0.45);
            backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
            z-index: 998;
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
        }
        .nav-backdrop.open { display: block; opacity: 1; pointer-events: auto; }

        /* Page content offset for fixed navbar */
        .page-content { padding-top: 64px; min-height: calc(100vh - 200px); }

        /* Footer */
        .footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border);
            padding: 3rem 2rem 2.5rem;
        }
        .footer-inner {
            max-width: 1160px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
        }
        .footer-brand-block {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        .footer-logo {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            display: inline-block;
        }
        .footer-copy { color: var(--text-muted); font-size: 0.85rem; }

        .footer-links {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            align-items: center;
        }
        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: color 0.16s ease;
        }
        .footer-links a:hover { color: var(--accent); }

        .footer-socials {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .footer-socials a {
            width: 36px; height: 36px;
            border-radius: 7px;
            background: var(--bg-primary);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.16s ease, border-color 0.16s ease, background-color 0.16s ease;
        }
        .footer-socials a:hover {
            color: var(--text-primary);
            border-color: var(--text-primary);
            background: var(--bg-card);
        }

        /* Shared utilities */
        .container { max-width: 1160px; margin: 0 auto; padding: 0 2rem; width: 100%; }
        .section { padding: 4.5rem 0; }
        .section-header-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 2.25rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--border);
        }
        .section-title {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 700;
            text-align: left;
            margin-bottom: 0.4rem;
            letter-spacing: -0.025em;
            line-height: 1.25;
            color: var(--text-primary);
        }
        .section-subtitle {
            color: var(--text-secondary);
            text-align: left;
            font-size: 1rem;
            margin-bottom: 0;
            max-width: 64ch;
            line-height: 1.6;
        }
        .section-head-standalone {
            margin-bottom: 2.25rem;
        }

        /* Clean fallback if any legacy markup references gradient-text */
        .gradient-text {
            color: inherit;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1.5rem;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }
        .card:hover {
            border-color: var(--border-strong);
        }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0 1.15rem; border-radius: 8px; min-height: 40px; height: 40px;
            font-size: 0.9rem; font-weight: 600; text-decoration: none;
            border: 1px solid transparent; cursor: pointer;
            transition: background-color 0.16s ease, border-color 0.16s ease, color 0.16s ease;
            touch-action: manipulation;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--text-primary);
            color: #ffffff;
            border-color: var(--text-primary);
        }
        .btn-primary:hover {
            background: #232a3b;
            border-color: #232a3b;
            color: #ffffff;
        }

        .btn-accent {
            background: var(--accent);
            color: #ffffff;
            border-color: var(--accent);
        }
        .btn-accent:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
            color: #ffffff;
        }

        .btn-outline {
            background: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }
        .btn-outline:hover {
            border-color: var(--text-primary);
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .btn-sm {
            min-height: 36px;
            height: 36px;
            padding: 0 0.9rem;
            font-size: 0.84rem;
        }

        .tag {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.2rem 0.6rem; border-radius: 5px;
            font-size: 0.78rem; font-weight: 500;
            background: var(--bg-secondary);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        /* Alerts */
        .alert { padding: 1rem 1.25rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; word-break: break-word; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: var(--green); }
        .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: var(--danger); }

        /* Pagination */
        .pagination-wrap { display: flex; justify-content: center; gap: 0.45rem; margin-top: 3rem; flex-wrap: wrap; }
        .pagination-wrap .page-link {
            padding: 0.45rem 0.85rem; border-radius: 7px; text-decoration: none; min-height: 38px; min-width: 38px;
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--bg-card); border: 1px solid var(--border); color: var(--text-secondary);
            transition: all 0.16s ease; font-size: 0.88rem; font-weight: 500;
        }
        .pagination-wrap .page-link:hover, .pagination-wrap .page-link.active {
            background: var(--text-primary); border-color: var(--text-primary); color: #ffffff;
        }

        /* Single orchestrated entrance option */
        @keyframes quietEnter {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hero-animate {
            animation: quietEnter 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @media (prefers-reduced-motion: reduce) {
            .hero-animate { animation: none !important; }
            html { scroll-behavior: auto; }
        }

        /* Mobile & Tablet Navigation <= 1024px */
        @media (max-width: 1024px) {
            .navbar { padding: 0 1.25rem; }
            .hamburger { display: flex; }
            .nav-links {
                position: absolute; top: 100%; left: 0; right: 0;
                background: var(--bg-card);
                padding: 1rem 1.25rem 1.5rem;
                border-bottom: 1px solid var(--border);
                box-shadow: 0 16px 32px -12px rgba(18, 22, 31, 0.12);
                flex-direction: column; align-items: stretch; gap: 0.4rem;
                opacity: 0; visibility: hidden; transform: translateY(-6px);
                transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
                max-height: calc(100vh - 64px);
                overflow-y: auto;
                z-index: 1001;
            }
            .nav-links.open { opacity: 1; visibility: visible; transform: translateY(0); }
            .nav-links a:not(.btn):not(.lang-btn) {
                padding: 0.8rem 1rem; font-size: 0.96rem; font-weight: 500;
                border-radius: 8px; display: flex; align-items: center;
                width: 100%; color: var(--text-primary);
                background: var(--bg-primary);
                white-space: normal;
            }
            .nav-links a:not(.btn):not(.lang-btn).active::after { display: none; }
            .nav-links a:not(.btn):not(.lang-btn).active {
                color: var(--accent);
                background: var(--accent-soft);
                font-weight: 600;
            }
            .nav-cta {
                margin-left: 0; margin-top: 0.4rem;
                width: 100% !important; justify-content: center !important;
                min-height: 44px !important; height: 44px !important;
            }
            .lang-switcher {
                margin-left: 0; margin-top: 0.5rem; padding: 3px; width: 100%;
                display: flex; gap: 4px;
            }
            .lang-switcher .lang-btn {
                flex: 1; padding: 0.55rem 0.75rem !important; font-size: 0.88rem;
            }

            .container { padding: 0 1.25rem; }
            .section { padding: 3.25rem 0; }
            .section-header-row { margin-bottom: 1.75rem; padding-bottom: 1rem; }
            .footer-inner { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 480px) {
            .container { padding: 0 1rem; }
            .navbar { padding: 0 1rem; }
            .section { padding: 2.75rem 0; }
            .card { padding: 1.15rem; }
        }

        /* Minimal Back to top */
        .back-to-top {
            position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 999;
            width: 40px; height: 40px; border-radius: 50%;
            background: var(--bg-card); color: var(--text-primary);
            border: 1px solid var(--border-strong);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem; cursor: pointer;
            box-shadow: 0 4px 12px rgba(18, 22, 31, 0.08);
            opacity: 0; pointer-events: none; transform: translateY(12px);
            transition: opacity 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
        }
        .back-to-top.visible { opacity: 1; pointer-events: auto; transform: translateY(0); }
        .back-to-top:hover { border-color: var(--text-primary); background: var(--bg-secondary); }
        .back-to-top.hide-on-focus { opacity: 0 !important; pointer-events: none !important; }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <a href="{{ route('home') }}" class="nav-logo">{{ __('Hồ Thành Thiện') }}</a>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
            <i class="fas fa-bars" id="hamburger-icon"></i>
        </button>
        <ul class="nav-links" id="nav-links">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Home') }}</a></li>
            <li><a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">{{ __('Projects') }}</a></li>
            <li><a href="{{ route('certificates') }}" class="{{ request()->routeIs('certificates') ? 'active' : '' }}">{{ __('Credentials') }}</a></li>
            <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">{{ __('Blog') }}</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __('About') }}</a></li>
            <li><a href="{{ route('contact') }}" class="btn btn-primary nav-cta {{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('Contact') }}</a></li>
            <li class="lang-switcher">
                <a href="{{ route('lang.switch', 'vi') }}" class="lang-btn {{ session('locale') == 'vi' ? 'active' : '' }}" title="Tiếng Việt" aria-label="Tiếng Việt">
                    <svg class="flag-icon" viewBox="0 0 640 480" width="16" height="12" aria-hidden="true">
                        <rect width="640" height="480" fill="#da251d"/>
                        <polygon points="320,120 357,235 477,235 380,305 417,420 320,350 223,420 260,305 163,235 283,235" fill="#ffff00"/>
                    </svg>
                    <span>VI</span>
                </a>
                <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ session('locale') != 'vi' ? 'active' : '' }}" title="English" aria-label="English">
                    <svg class="flag-icon" viewBox="0 0 640 480" width="16" height="12" aria-hidden="true">
                        <path fill="#012169" d="M0 0h640v480H0z"/>
                        <path fill="#FFF" d="m75 0 245 180L565 0h75v60L435 240l205 180v60h-75L320 300 75 480H0v-60l205-180L0 60V0h75z"/>
                        <path fill="#C8102E" d="m424 288 216 159v33h-25L383 312h41zm-208 0-41 24L0 447v33h25l191-140v-52zM640 0v15L453 150h-41L640 0zM0 0l191 140v52L0 41V0z"/>
                        <path fill="#FFF" d="M240 0h160v480H240zM0 160h640v160H0z"/>
                        <path fill="#C8102E" d="M272 0h96v480h-96zM0 192h640v96H0z"/>
                    </svg>
                    <span>EN</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="nav-backdrop" id="nav-backdrop"></div>

    <!-- Page Content -->
    <main class="page-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-brand-block">
                <span class="footer-logo">{{ __('Hồ Thành Thiện') }}</span>
                <p class="footer-copy">&copy; {{ date('Y') }} {{ __('Hồ Thành Thiện') }}. {{ __('Built with Laravel & ♥') }}</p>
            </div>
            <nav class="footer-links">
                <a href="{{ route('home') }}">{{ __('Home') }}</a>
                <a href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
                <a href="{{ route('certificates') }}">{{ __('Credentials') }}</a>
                <a href="{{ route('blog.index') }}">{{ __('Blog') }}</a>
                <a href="{{ route('about') }}">{{ __('About') }}</a>
                <a href="{{ route('contact') }}">{{ __('Contact') }}</a>
            </nav>
            <div class="footer-socials">
                <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" aria-label="GitHub"><i class="fab fa-github"></i></a>
                <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                <a href="{{ route('contact') }}" aria-label="{{ __('Contact') }}" title="{{ __('Contact') }}"><i class="fas fa-envelope"></i></a>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        const navbar = document.getElementById('navbar');
        const backToTop = document.getElementById('backToTop');

        let isScrolling = false;
        window.addEventListener('scroll', () => {
            if (!isScrolling) {
                window.requestAnimationFrame(() => {
                    const scrollTop = window.scrollY || document.documentElement.scrollTop;
                    if (navbar) navbar.classList.toggle('scrolled', scrollTop > 40);
                    if (backToTop) backToTop.classList.toggle('visible', scrollTop > 360);
                    isScrolling = false;
                });
                isScrolling = true;
            }
        }, { passive: true });

        const hamburger = document.getElementById('hamburger');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const navLinks = document.getElementById('nav-links');
        const navBackdrop = document.getElementById('nav-backdrop');

        function toggleMenu(forceState) {
            const isOpen = typeof forceState === 'boolean' ? forceState : !navLinks.classList.contains('open');
            navLinks.classList.toggle('open', isOpen);
            if (navBackdrop) navBackdrop.classList.toggle('open', isOpen);
            hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (hamburgerIcon) {
                hamburgerIcon.classList.toggle('fa-bars', !isOpen);
                hamburgerIcon.classList.toggle('fa-xmark', isOpen);
            }
            document.body.style.overflow = isOpen ? 'hidden' : '';
        }

        hamburger.addEventListener('click', () => toggleMenu());
        if (navBackdrop) navBackdrop.addEventListener('click', () => toggleMenu(false));
        navLinks.querySelectorAll('a').forEach(link => link.addEventListener('click', () => toggleMenu(false)));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navLinks.classList.contains('open')) toggleMenu(false);
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024 && navLinks.classList.contains('open')) toggleMenu(false);
        });
        backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    </script>

    @yield('scripts')
</body>
</html>
