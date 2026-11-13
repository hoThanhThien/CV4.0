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
    <meta name="theme-color" content="#0B1120">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/webp" href="{{ asset('favicon.webp') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Canonical & Multi-Language Alternate URLs -->
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

    <!-- Typography: Plus Jakarta Sans (Modern SaaS/Tech Display), IBM Plex Sans (UI/Body), IBM Plex Mono (Specs) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap">

    <!-- Font Awesome 6.5 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    <script>
        document.documentElement.classList.add('has-reveal');
    </script>

    <style>
        :root {
            /* Dark Navy & Refined Blue/Cyan Portfolio System */
            --navy-deep: #070B14;
            --navy-surface: #0D1526;
            --navy-elevated: #131E36;
            --navy-border: rgba(148, 163, 184, 0.16);

            /* High-Contrast Reading & Section Surfaces */
            --bg-primary: #F8FAFC;
            --bg-secondary: #EFF4FA;
            --bg-card: #FFFFFF;
            --surface-ink: #0B1120;
            --border: #E2E8F0;
            --border-strong: #CBD5E1;

            /* Typography Ink */
            --text-primary: #0F172A;
            --text-secondary: #475569;
            --text-muted: #64748B;

            /* Refined Blue & Cyan Accents */
            --accent: #2563EB;
            --accent-hover: #1D4ED8;
            --accent-cyan: #06B6D4;
            --accent-sky: #38BDF8;
            --accent-soft: rgba(37, 99, 235, 0.08);
            --green: #0F766E;
            --danger: #DC2626;

            /* Font Families */
            --font-display: 'Plus Jakarta Sans', 'IBM Plex Sans', -apple-system, sans-serif;
            --font-sans: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'IBM Plex Mono', monospace;

            /* Motion & Elevation */
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
            --duration-fast: 150ms;
            --duration-base: 260ms;
            --duration-slow: 400ms;
            --shadow-sm: 0 2px 8px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 12px 28px -10px rgba(15, 23, 42, 0.10);
            --shadow-lg: 0 24px 48px -16px rgba(7, 11, 20, 0.24);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: clip;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.65;
            overflow-x: clip;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, .display-heading {
            font-family: var(--font-display);
            color: var(--text-primary);
            letter-spacing: -0.025em;
        }

        .mono-label {
            font-family: var(--font-mono);
            font-size: 0.76rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: var(--text-muted);
        }

        /* Skip Navigation Accessibility */
        .skip-link {
            position: fixed;
            top: -100%;
            left: 1rem;
            z-index: 9999;
            background: var(--accent);
            color: #FFFFFF;
            padding: 0.65rem 1.15rem;
            border-radius: var(--radius-sm);
            font-size: 0.86rem;
            font-weight: 600;
            text-decoration: none;
        }
        .skip-link:focus {
            top: 0.75rem;
            outline: 2px solid var(--accent-sky);
            outline-offset: 2px;
        }

        /* Scroll Progress Line */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 2px;
            background: linear-gradient(90deg, var(--accent) 0%, var(--accent-cyan) 100%);
            z-index: 1200;
            pointer-events: none;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 clamp(1.15rem, 3.8vw, 2.5rem);
        }

        .section {
            padding: clamp(3.5rem, 6vw, 5.25rem) 0;
        }

        .section--alt {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        /* Section Header */
        .section-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            padding-bottom: 1.25rem;
            margin-bottom: clamp(1.85rem, 3.8vw, 2.75rem);
            border-bottom: 1px solid var(--border);
        }
        .section-head-left { max-width: 66ch; }
        .section-kicker {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: var(--font-mono);
            font-size: 0.76rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }
        .section-kicker::before {
            content: '';
            display: inline-block;
            width: 16px;
            height: 2px;
            background: var(--accent-cyan);
        }
        .section-head h2 {
            font-size: clamp(1.75rem, 3.3vw, 2.35rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 0.4rem;
        }
        .section-head p {
            color: var(--text-secondary);
            font-size: 0.98rem;
            line-height: 1.6;
        }

        /* Dark Navy Secondary Page Header Band */
        .page-header-band {
            padding: clamp(2.75rem, 5vw, 4rem) 0 clamp(2.25rem, 4vw, 3rem);
            background: linear-gradient(180deg, var(--navy-deep) 0%, var(--navy-surface) 100%);
            color: #F8FAFC;
            border-bottom: 1px solid var(--navy-border);
            position: relative;
        }
        .page-header-inner {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        .page-header-band .section-kicker { color: var(--accent-sky); }
        .page-header-band h1 {
            font-size: clamp(2rem, 4.4vw, 3rem);
            font-weight: 800;
            line-height: 1.12;
            color: #FFFFFF;
            margin-bottom: 0.6rem;
        }
        .page-header-band p {
            color: #94A3B8;
            font-size: clamp(0.96rem, 1.45vw, 1.06rem);
            max-width: 60ch;
            line-height: 1.65;
        }
        .page-header-band .mono-label { color: #94A3B8; }

        a:focus-visible, button:focus-visible, input:focus-visible, textarea:focus-visible, select:focus-visible, summary:focus-visible {
            outline: 2px solid var(--accent-cyan);
            outline-offset: 2px;
        }

        /* =========================================================
           DARK NAVY STICKY NAVBAR (No wrapping at <= 1024px)
        ========================================================= */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 68px;
            background: rgba(7, 11, 20, 0.90);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--navy-border);
            transition: background var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out);
        }
        .navbar.scrolled {
            background: rgba(7, 11, 20, 0.97);
            box-shadow: 0 8px 28px rgba(7, 11, 20, 0.24);
        }
        .navbar-inner {
            height: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 clamp(1.15rem, 3.8vw, 2.5rem);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
        }

        .nav-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            text-decoration: none;
            color: #F8FAFC;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.06rem;
            letter-spacing: -0.02em;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .nav-logo-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-cyan) 100%);
            color: #FFFFFF;
            font-family: var(--font-mono);
            font-size: 0.74rem;
            font-weight: 600;
        }

        .nav-links {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 0.2rem;
        }
        .nav-links > li > a.nav-item-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.78rem;
            color: #CBD5E1;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            white-space: nowrap;
            border-radius: var(--radius-sm);
            transition: color var(--duration-fast) ease;
        }
        .nav-links > li > a.nav-item-link::after {
            content: '';
            position: absolute;
            left: 0.78rem;
            right: 0.78rem;
            bottom: 0.16rem;
            height: 2px;
            background: var(--accent-sky);
            transform: scaleX(0);
            transform-origin: left center;
            transition: transform 220ms var(--ease-out);
        }
        .nav-links > li > a.nav-item-link:hover,
        .nav-links > li > a.nav-item-link.active {
            color: #FFFFFF;
            font-weight: 600;
        }
        .nav-links > li > a.nav-item-link:hover::after,
        .nav-links > li > a.nav-item-link.active::after {
            transform: scaleX(1);
        }

        .nav-actions-cluster {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin-left: 0.55rem;
            flex-shrink: 0;
        }

        .nav-cta {
            height: 36px !important;
            min-height: 36px !important;
            padding: 0 0.95rem !important;
            font-size: 0.85rem !important;
            white-space: nowrap;
        }

        .lang-switcher {
            display: inline-flex;
            align-items: center;
            padding: 3px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: var(--radius-sm);
            gap: 2px;
            flex-shrink: 0;
        }
        .lang-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.24rem 0.52rem;
            border-radius: 4px;
            text-decoration: none;
            font-family: var(--font-mono);
            font-size: 0.74rem;
            font-weight: 600;
            color: #94A3B8;
            white-space: nowrap;
            transition: background var(--duration-fast) ease, color var(--duration-fast) ease;
        }
        .lang-btn:hover { color: #FFFFFF; }
        .lang-btn.active {
            background: var(--accent);
            color: #FFFFFF;
        }
        .flag-icon { border-radius: 1.5px; flex-shrink: 0; display: block; }

        .hamburger {
            display: none;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(148, 163, 184, 0.24);
            border-radius: var(--radius-sm);
            color: #F8FAFC;
            font-size: 1rem;
            cursor: pointer;
        }

        .nav-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(7, 11, 20, 0.65);
            backdrop-filter: blur(4px);
            z-index: 990;
            opacity: 0;
            transition: opacity var(--duration-base) ease;
        }
        .nav-backdrop.open { display: block; opacity: 1; }

        @media (max-width: 1024px) {
            .hamburger { display: inline-flex; }
            .nav-links {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                width: min(330px, 86vw);
                background: var(--navy-surface);
                border-left: 1px solid var(--navy-border);
                flex-direction: column;
                align-items: stretch;
                padding: 5.25rem 1.5rem 2rem;
                gap: 0.4rem;
                transform: translateX(100%);
                transition: transform 280ms var(--ease-out);
                z-index: 995;
                overflow-y: auto;
                box-shadow: -12px 0 40px rgba(7, 11, 20, 0.45);
            }
            .nav-links.open { transform: translateX(0); }
            .nav-links > li > a.nav-item-link {
                width: 100%;
                padding: 0.78rem 0.9rem;
                font-size: 1rem;
                border-bottom: 1px solid rgba(148, 163, 184, 0.12);
                border-radius: 0;
            }
            .nav-links > li > a.nav-item-link::after { display: none; }
            .nav-links > li > a.nav-item-link.active {
                color: var(--accent-sky);
                background: rgba(56, 189, 248, 0.1);
                border-radius: var(--radius-sm);
                border-bottom-color: transparent;
            }
            .nav-actions-cluster {
                flex-direction: column;
                align-items: stretch;
                margin-left: 0;
                margin-top: 1rem;
                gap: 0.75rem;
            }
            .nav-cta { height: 44px !important; justify-content: center; width: 100%; font-size: 0.94rem !important; }
            .lang-switcher { justify-content: center; padding: 4px; }
            .lang-btn { flex: 1; justify-content: center; padding: 0.45rem; }
        }

        /* =========================================================
           BUTTONS, TAGS, CARDS, PAGINATION
        ========================================================= */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.62rem 1.2rem;
            min-height: 42px;
            border-radius: var(--radius-sm);
            font-family: var(--font-sans);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            white-space: nowrap;
            transition: background var(--duration-fast) ease, border-color var(--duration-fast) ease, color var(--duration-fast) ease, transform var(--duration-fast) ease, box-shadow var(--duration-fast) ease;
        }
        .btn:active { transform: translateY(1px); }
        .btn-sm { padding: 0.44rem 0.9rem; min-height: 36px; font-size: 0.84rem; }

        .btn-primary {
            background: var(--accent);
            color: #FFFFFF;
            border-color: var(--accent);
        }
        .btn-primary:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.45);
        }
        .btn-navy {
            background: var(--navy-surface);
            color: #FFFFFF;
            border-color: var(--navy-surface);
        }
        .btn-navy:hover {
            background: var(--accent);
            border-color: var(--accent);
            transform: translateY(-1px);
        }
        .btn-outline {
            background: var(--bg-card);
            color: var(--text-primary);
            border-color: var(--border-strong);
        }
        .btn-outline:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-1px);
        }
        .btn-outline-dark {
            background: rgba(255, 255, 255, 0.04);
            color: #F8FAFC;
            border-color: rgba(148, 163, 184, 0.3);
        }
        .btn-outline-dark:hover {
            border-color: var(--accent-sky);
            color: var(--accent-sky);
            background: rgba(56, 189, 248, 0.08);
            transform: translateY(-1px);
        }

        .tag {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.24rem 0.58rem;
            border-radius: var(--radius-sm);
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            font-family: var(--font-mono);
            font-size: 0.74rem;
            font-weight: 500;
            color: var(--text-secondary);
            line-height: 1.35;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out);
        }

        .pagination-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 3rem;
        }
        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 0.75rem;
            border-radius: var(--radius-sm);
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            text-decoration: none;
            font-family: var(--font-mono);
            font-size: 0.84rem;
            font-weight: 500;
        }
        .page-link:hover { border-color: var(--accent); color: var(--accent); }
        .page-link.active { background: var(--navy-surface); border-color: var(--navy-surface); color: #FFFFFF; }

        /* =========================================================
           ARCHITECTURAL PROJECT SPECIMEN FRAME (When project.image is null)
        ========================================================= */
        .specimen-plate {
            position: relative;
            width: 100%;
            height: 100%;
            min-height: 240px;
            background: linear-gradient(145deg, var(--navy-deep) 0%, var(--navy-elevated) 100%);
            color: #F8FAFC;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.35rem 1.5rem 1.5rem;
            overflow: hidden;
        }
        .specimen-plate::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(56, 189, 248, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(56, 189, 248, 0.05) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }
        .specimen-topbar {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        }
        .specimen-dots { display: inline-flex; align-items: center; gap: 5px; }
        .specimen-dots span { width: 7px; height: 7px; border-radius: 50%; background: rgba(148, 163, 184, 0.35); }
        .specimen-host {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: #94A3B8;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 72%;
        }
        .specimen-center { position: relative; z-index: 1; margin: 1.5rem 0; }
        .specimen-index {
            font-family: var(--font-mono);
            font-size: 0.74rem;
            color: var(--accent-sky);
            letter-spacing: 0.06em;
            margin-bottom: 0.45rem;
        }
        .specimen-title {
            font-family: var(--font-display);
            font-size: clamp(1.25rem, 2.3vw, 1.6rem);
            font-weight: 800;
            line-height: 1.22;
            color: #FFFFFF;
            max-width: 24ch;
        }
        .specimen-footer { position: relative; z-index: 1; display: flex; flex-wrap: wrap; gap: 0.35rem; align-items: center; }
        .specimen-pill {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            background: rgba(56, 189, 248, 0.12);
            border: 1px solid rgba(56, 189, 248, 0.25);
            color: #E0F2FE;
        }

        /* =========================================================
           DARK NAVY FOOTER
        ========================================================= */
        .page-content { flex: 1 0 auto; }

        .footer {
            background: var(--navy-deep);
            color: #CBD5E1;
            border-top: 1px solid var(--navy-border);
            padding: 3rem 0 2.25rem;
            margin-top: auto;
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 clamp(1.15rem, 3.8vw, 2.5rem);
            display: grid;
            grid-template-columns: 1.4fr 1.2fr 1fr;
            gap: 2rem;
            align-items: start;
        }
        .footer-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            font-family: var(--font-display);
            font-size: 1.08rem;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 0.55rem;
        }
        .footer-copy { font-size: 0.86rem; color: #94A3B8; line-height: 1.6; }
        .footer-col-title {
            font-family: var(--font-mono);
            font-size: 0.74rem;
            font-weight: 600;
            color: var(--accent-sky);
            margin-bottom: 0.75rem;
        }
        .footer-links { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.45rem 1rem; }
        .footer-links a {
            color: #CBD5E1;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: color var(--duration-fast) ease;
        }
        .footer-links a:hover { color: var(--accent-sky); }
        .footer-contact-list { display: flex; flex-direction: column; gap: 0.45rem; margin-bottom: 0.9rem; font-size: 0.86rem; }
        .footer-contact-list a { color: #CBD5E1; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; }
        .footer-contact-list a:hover { color: var(--accent-sky); }
        .footer-socials { display: flex; align-items: center; gap: 0.55rem; }
        .footer-socials a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(148, 163, 184, 0.25);
            color: #CBD5E1;
            text-decoration: none;
            transition: border-color var(--duration-fast) ease, color var(--duration-fast) ease, background var(--duration-fast) ease;
        }
        .footer-socials a:hover {
            border-color: var(--accent-sky);
            color: var(--accent-sky);
            background: rgba(56, 189, 248, 0.08);
        }
        @media (max-width: 820px) {
            .footer-inner { grid-template-columns: 1fr; gap: 1.75rem; }
        }

        /* Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 950;
            width: 42px;
            height: 42px;
            border-radius: var(--radius-sm);
            background: var(--navy-surface);
            color: #FFFFFF;
            border: 1px solid rgba(56, 189, 248, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            cursor: pointer;
            box-shadow: var(--shadow-md);
            opacity: 0;
            pointer-events: none;
            transform: translateY(10px);
            transition: opacity 0.2s ease, transform 0.2s ease, background 0.2s ease;
        }
        .back-to-top.visible { opacity: 1; pointer-events: auto; transform: translateY(0); }
        .back-to-top:hover { background: var(--accent); }
        .back-to-top.hide-on-focus { opacity: 0 !important; pointer-events: none !important; }

        /* Reveal Motion */
        .hero-animate { animation: heroReveal 500ms var(--ease-out) both; }
        @keyframes heroReveal {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .has-reveal [data-reveal] {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity 440ms var(--ease-out), transform 440ms var(--ease-out);
            transition-delay: calc(var(--stagger-i, 0) * 50ms);
        }
        .has-reveal [data-reveal].is-visible { opacity: 1; transform: translateY(0); }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .hero-animate { animation: none !important; }
            .has-reveal [data-reveal] { opacity: 1 !important; transform: none !important; transition: none !important; }
            *, *::before, *::after { transition-duration: 0.01ms !important; animation-duration: 0.01ms !important; }
        }
    </style>

    @yield('styles')
</head>
<body>
    <a href="#main-content" class="skip-link">{{ $isVi ? 'Chuyển tới nội dung chính' : 'Skip to main content' }}</a>
    <div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="navbar-inner">
            <a href="{{ route('home') }}" class="nav-logo">
                <span class="nav-logo-mark" aria-hidden="true">HT</span>
                <span>{{ __('Hồ Thành Thiện') }}</span>
            </a>

            <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
                <i class="fas fa-bars" id="hamburger-icon"></i>
            </button>

            <ul class="nav-links" id="nav-links">
                <li><a href="{{ route('home') }}" class="nav-item-link {{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Home') }}</a></li>
                <li><a href="{{ route('projects.index') }}" class="nav-item-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">{{ __('Projects') }}</a></li>
                <li><a href="{{ route('certificates') }}" class="nav-item-link {{ request()->routeIs('certificates') ? 'active' : '' }}">{{ __('Credentials') }}</a></li>
                <li><a href="{{ route('blog.index') }}" class="nav-item-link {{ request()->routeIs('blog.*') ? 'active' : '' }}">{{ __('Blog') }}</a></li>
                <li><a href="{{ route('about') }}" class="nav-item-link {{ request()->routeIs('about') ? 'active' : '' }}">{{ __('About') }}</a></li>

                <li class="nav-actions-cluster">
                    <a href="{{ route('contact') }}" class="btn btn-primary nav-cta {{ request()->routeIs('contact') ? 'active' : '' }}">
                        {{ __('Contact') }}
                    </a>
                    <div class="lang-switcher">
                        <a href="{{ route('lang.switch', 'vi') }}" class="lang-btn {{ $currentLocale === 'vi' ? 'active' : '' }}" title="Tiếng Việt" aria-label="Tiếng Việt">
                            <svg class="flag-icon" viewBox="0 0 640 480" width="15" height="11" aria-hidden="true">
                                <rect width="640" height="480" fill="#da251d"/>
                                <polygon points="320,120 357,235 477,235 380,305 417,420 320,350 223,420 260,305 163,235 283,235" fill="#ffff00"/>
                            </svg>
                            <span>VI</span>
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ $currentLocale !== 'vi' ? 'active' : '' }}" title="English" aria-label="English">
                            <svg class="flag-icon" viewBox="0 0 640 480" width="15" height="11" aria-hidden="true">
                                <path fill="#012169" d="M0 0h640v480H0z"/>
                                <path fill="#FFF" d="m75 0 245 180L565 0h75v60L435 240l205 180v60h-75L320 300 75 480H0v-60l205-180L0 60V0h75z"/>
                                <path fill="#C8102E" d="m424 288 216 159v33h-25L383 312h41zm-208 0-41 24L0 447v33h25l191-140v-52zM640 0v15L453 150h-41L640 0zM0 0l191 140v52L0 41V0z"/>
                                <path fill="#FFF" d="M240 0h160v480H240zM0 160h640v160H0z"/>
                                <path fill="#C8102E" d="M272 0h96v480h-96zM0 192h640v96H0z"/>
                            </svg>
                            <span>EN</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="nav-backdrop" id="nav-backdrop"></div>

    <!-- Page Content -->
    <main class="page-content" id="main-content" tabindex="-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-inner">
            <div>
                <div class="footer-logo">
                    <span class="nav-logo-mark" aria-hidden="true">HT</span>
                    <span>{{ __('Hồ Thành Thiện') }}</span>
                </div>
                <p class="footer-copy">
                    &copy; {{ date('Y') }} {{ __('Hồ Thành Thiện') }}. {{ __('Built with Laravel & ♥') }}
                </p>
            </div>

            <div>
                <div class="footer-col-title">{{ $isVi ? 'ĐIỀU HƯỚNG' : 'NAVIGATION' }}</div>
                <nav class="footer-links">
                    <a href="{{ route('home') }}">{{ __('Home') }}</a>
                    <a href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
                    <a href="{{ route('certificates') }}">{{ __('Credentials') }}</a>
                    <a href="{{ route('blog.index') }}">{{ __('Blog') }}</a>
                    <a href="{{ route('about') }}">{{ __('About') }}</a>
                    <a href="{{ route('contact') }}">{{ __('Contact') }}</a>
                </nav>
            </div>

            <div>
                <div class="footer-col-title">{{ __('Contact') }}</div>
                <div class="footer-contact-list">
                    <a href="mailto:hothanhthien119@gmail.com"><i class="fas fa-envelope"></i> hothanhthien119@gmail.com</a>
                    <a href="tel:0375227764"><i class="fas fa-phone-alt"></i> 0375227764</a>
                </div>
                <div class="footer-socials">
                    <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" aria-label="GitHub"><i class="fab fa-github"></i></a>
                    <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="{{ route('contact') }}" aria-label="{{ __('Contact') }}" title="{{ __('Contact') }}"><i class="fas fa-paper-plane"></i></a>
                </div>
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
        const scrollProgress = document.getElementById('scrollProgress');

        let isScrolling = false;
        window.addEventListener('scroll', () => {
            if (!isScrolling) {
                window.requestAnimationFrame(() => {
                    const scrollTop = window.scrollY || document.documentElement.scrollTop;
                    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                    if (navbar) navbar.classList.toggle('scrolled', scrollTop > 40);
                    if (backToTop) backToTop.classList.toggle('visible', scrollTop > 360);
                    if (scrollProgress && docHeight > 0) {
                        scrollProgress.style.width = Math.min(100, (scrollTop / docHeight) * 100) + '%';
                    }
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
            if (!navLinks || !hamburger) return;
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

        if (hamburger) hamburger.addEventListener('click', () => toggleMenu());
        if (navBackdrop) navBackdrop.addEventListener('click', () => toggleMenu(false));
        if (navLinks) navLinks.querySelectorAll('a').forEach(link => link.addEventListener('click', () => toggleMenu(false)));
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navLinks && navLinks.classList.contains('open')) toggleMenu(false);
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth > 1024 && navLinks && navLinks.classList.contains('open')) toggleMenu(false);
        });
        if (backToTop) backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-stagger]').forEach(container => {
                Array.from(container.children).forEach((child, idx) => {
                    child.setAttribute('data-reveal', '');
                    child.style.setProperty('--stagger-i', Math.min(idx, 5));
                });
            });

            const revealEls = document.querySelectorAll('[data-reveal]');
            if (!revealEls.length) return;

            if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                revealEls.forEach(el => el.classList.add('is-visible'));
                return;
            }

            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.06, rootMargin: '0px 0px -24px 0px' });

            revealEls.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight * 0.94) {
                    el.classList.add('is-visible');
                } else {
                    io.observe(el);
                }
            });

            setTimeout(() => {
                revealEls.forEach(el => {
                    if (el.getBoundingClientRect().top < window.innerHeight + 120) {
                        el.classList.add('is-visible');
                    }
                });
            }, 900);
        });
    </script>

    @yield('scripts')
</body>
</html>
