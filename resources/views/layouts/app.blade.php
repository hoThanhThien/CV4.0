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

    <!-- Typography: Space Grotesk (Editorial Display), Inter (Modern Body), JetBrains Mono (Specs & Terminal) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700;800&display=swap">

    <!-- Font Awesome 6.5 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    <script>
        document.documentElement.classList.add('has-reveal');
    </script>

    <style>
        :root {
            /* 2026 Dark Editorial Tech Design System */
            --bg-base: #06090F;
            --bg-primary: #06090F;
            --bg-secondary: #0A0F1A;
            --bg-card: #0B111E;
            --bg-card-hover: #10182A;
            --bg-elevated: #131E34;
            --surface-ink: #03060B;
            --navy-deep: #03060B;
            --navy-surface: #0A0F1A;
            --navy-elevated: #111B30;
            --navy-border: rgba(148, 163, 184, 0.12);

            /* High-Contrast Structural & Blueprint Hairlines */
            --border: rgba(148, 163, 184, 0.12);
            --border-subtle: rgba(148, 163, 184, 0.07);
            --border-strong: rgba(148, 163, 184, 0.24);
            --border-glow: rgba(198, 244, 50, 0.35);
            --border-lime: rgba(198, 244, 50, 0.35);

            /* Typography Ink (WCAG AAA compliant on #06090F) */
            --text-primary: #F8FAFC;
            --text-secondary: #94A3B8;
            --text-muted: #64748B;

            /* 2026 Signature Accents: Electric Lime (#C6F432) & Sky Blueprint (#38BDF8) */
            --accent: #C6F432;
            --accent-lime: #C6F432;
            --accent-lime-hover: #B4E61F;
            --accent-lime-soft: rgba(198, 244, 50, 0.10);
            --accent-lime-glow: rgba(198, 244, 50, 0.28);
            --accent-hover: #B4E61F;
            --accent-cyan: #22D3EE;
            --accent-sky: #38BDF8;
            --accent-indigo: #6366F1;
            --accent-soft: rgba(198, 244, 50, 0.10);
            --accent-glow: rgba(198, 244, 50, 0.22);
            --green: #10B981;
            --green-soft: rgba(16, 185, 129, 0.12);
            --danger: #EF4444;

            /* Font Families */
            --font-display: 'Space Grotesk', 'Inter', -apple-system, sans-serif;
            --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;

            /* Motion & Elevation */
            --ease-out: cubic-bezier(0.16, 1, 0.3, 1);
            --duration-fast: 150ms;
            --duration-base: 240ms;
            --duration-slow: 380ms;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 12px 28px -8px rgba(0, 0, 0, 0.45);
            --shadow-lg: 0 24px 48px -12px rgba(0, 0, 0, 0.6);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
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
           ENGINEERING TERMINAL STICKY NAVBAR
        ========================================================= */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 68px;
            background: rgba(11, 17, 32, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            transition: background var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), border-color var(--duration-fast) ease;
        }
        .navbar.scrolled {
            background: rgba(11, 17, 32, 0.98);
            border-bottom-color: var(--border-strong);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.6);
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
            flex-wrap: nowrap;
        }

        .nav-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            text-decoration: none;
            color: #F8FAFC;
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 1.14rem;
            letter-spacing: -0.02em;
            white-space: nowrap !important;
            flex-shrink: 0;
            transition: opacity var(--duration-fast) ease;
        }
        .nav-logo:hover { opacity: 0.9; }
        .nav-brand-code {
            color: var(--accent);
            font-family: var(--font-mono);
            font-weight: 700;
            text-shadow: 0 0 14px rgba(56, 189, 248, 0.35);
        }

        .nav-links {
            display: flex;
            align-items: center;
            list-style: none;
            gap: 0.25rem;
            flex-wrap: nowrap;
            margin: 0;
            padding: 0;
        }
        .nav-links > li {
            white-space: nowrap !important;
            flex-shrink: 0;
        }
        .nav-links > li > a.nav-item-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            padding: 0.45rem 0.8rem;
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            white-space: nowrap !important;
            border-radius: var(--radius-sm);
            transition: color var(--duration-fast) ease, background var(--duration-fast) ease;
        }
        .nav-links > li > a.nav-item-link::after {
            content: '';
            position: absolute;
            left: 0.8rem;
            right: 0.8rem;
            bottom: 0.12rem;
            height: 2px;
            background: var(--accent);
            transform: scaleX(0);
            transform-origin: left center;
            transition: transform 200ms var(--ease-out);
        }
        .nav-links > li > a.nav-item-link:hover {
            color: #F8FAFC;
        }
        .nav-links > li > a.nav-item-link.active {
            color: var(--accent);
            font-weight: 600;
        }
        .nav-links > li > a.nav-item-link.active::after {
            transform: scaleX(1);
        }

        .nav-actions-cluster {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin-left: 0.65rem;
            flex-shrink: 0;
            white-space: nowrap !important;
        }

        .nav-cta {
            height: 36px !important;
            min-height: 36px !important;
            padding: 0 1rem !important;
            font-size: 0.84rem !important;
            white-space: nowrap !important;
            border-radius: var(--radius-sm);
        }

        .lang-switcher {
            display: inline-flex;
            align-items: center;
            padding: 2px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            gap: 2px;
            flex-shrink: 0;
            white-space: nowrap !important;
        }
        .lang-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.32rem;
            padding: 0.22rem 0.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 600;
            color: #94A3B8;
            white-space: nowrap !important;
            transition: background var(--duration-fast) ease, color var(--duration-fast) ease;
        }
        .lang-btn:hover { color: #FFFFFF; }
        .lang-btn.active {
            background: var(--accent);
            color: #070B14;
        }
        .flag-icon { border-radius: 1.5px; flex-shrink: 0; display: block; }

        .hamburger {
            display: none;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: #F8FAFC;
            font-size: 1rem;
            cursor: pointer;
            transition: border-color var(--duration-fast) ease, color var(--duration-fast) ease;
        }
        .hamburger:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .nav-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(7, 11, 20, 0.75);
            backdrop-filter: blur(6px);
            z-index: 990;
            opacity: 0;
            transition: opacity var(--duration-base) ease;
        }
        .nav-backdrop.open { display: block; opacity: 1; }

        @media (max-width: 1040px) {
            .hamburger { display: inline-flex; }
            .nav-links {
                position: fixed;
                top: 0;
                right: 0;
                bottom: 0;
                width: min(340px, 86vw);
                background: #0D1526;
                border-left: 1px solid var(--border-strong);
                flex-direction: column;
                align-items: stretch;
                padding: 5.25rem 1.5rem 2rem;
                gap: 0.5rem;
                transform: translateX(100%);
                transition: transform 260ms var(--ease-out);
                z-index: 995;
                overflow-y: auto;
                box-shadow: -16px 0 40px rgba(0, 0, 0, 0.7);
            }
            .nav-links.open { transform: translateX(0); }
            .nav-links > li > a.nav-item-link {
                width: 100%;
                padding: 0.85rem 1rem;
                font-size: 0.96rem;
                border-bottom: 1px solid var(--border-subtle);
                border-radius: var(--radius-sm);
            }
            .nav-links > li > a.nav-item-link::after { display: none; }
            .nav-links > li > a.nav-item-link.active {
                color: var(--accent);
                background: rgba(56, 189, 248, 0.1);
                border-bottom-color: transparent;
            }
            .nav-actions-cluster {
                flex-direction: column;
                align-items: stretch;
                margin-left: 0;
                margin-top: 1.25rem;
                gap: 0.85rem;
            }
            .nav-cta { height: 44px !important; justify-content: center; width: 100%; font-size: 0.94rem !important; }
            .lang-switcher { justify-content: center; padding: 4px; }
            .lang-btn { flex: 1; justify-content: center; padding: 0.45rem; }
        }

        /* =========================================================
           BUTTONS, TAGS, CARDS, PAGINATION (Minimalist Dark SaaS)
        ========================================================= */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.62rem 1.25rem;
            min-height: 42px;
            border-radius: var(--radius-sm);
            font-family: var(--font-sans);
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            white-space: nowrap !important;
            transition: background var(--duration-fast) ease, border-color var(--duration-fast) ease, color var(--duration-fast) ease, transform var(--duration-fast) ease, box-shadow var(--duration-fast) ease;
        }
        .btn:active { transform: translateY(1px); }
        .btn-sm { padding: 0.44rem 0.9rem; min-height: 36px; font-size: 0.82rem; }

        .btn-primary {
            background: var(--accent);
            color: #070B14;
            border-color: var(--accent);
            font-weight: 700;
        }
        .btn-primary:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
            color: #070B14;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px -4px rgba(56, 189, 248, 0.45);
        }
        .btn-navy {
            background: var(--bg-elevated);
            color: #F8FAFC;
            border-color: var(--border-strong);
        }
        .btn-navy:hover {
            background: rgba(56, 189, 248, 0.12);
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-1px);
        }
        .btn-outline {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-primary);
            border-color: var(--border);
        }
        .btn-outline:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: rgba(56, 189, 248, 0.08);
            transform: translateY(-1px);
        }
        .btn-outline-dark {
            background: rgba(255, 255, 255, 0.04);
            color: #F8FAFC;
            border-color: var(--border-strong);
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
            padding: 0.22rem 0.58rem;
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            font-family: var(--font-mono);
            font-size: 0.72rem;
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
        .card:hover {
            border-color: var(--border-strong);
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
        .specimen-dots span { width: 8px; height: 8px; border-radius: 50%; }
        .specimen-dots span:nth-child(1) { background: #EF4444; }
        .specimen-dots span:nth-child(2) { background: #F59E0B; }
        .specimen-dots span:nth-child(3) { background: #10B981; }
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

        .specimen-terminal-body, .specimen-pipeline-body, .specimen-workflow-body {
            position: relative;
            z-index: 1;
            margin: auto 0;
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
            font-family: var(--font-mono);
            font-size: 0.78rem;
        }
        .terminal-line { color: #CBD5E1; }
        .terminal-prompt { color: var(--accent-sky); font-weight: 700; margin-right: 0.35rem; }
        .terminal-output { color: #94A3B8; padding-left: 0.85rem; font-size: 0.74rem; }
        .terminal-badge-ok {
            display: inline-block;
            padding: 0.1rem 0.4rem;
            border-radius: 3px;
            background: rgba(16, 185, 129, 0.2);
            color: #34D399;
            font-weight: 600;
            margin-right: 0.35rem;
        }
        .terminal-highlight { color: #F8FAFC; font-weight: 600; }
        .terminal-tags-row { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.5rem; }

        .pipeline-flow, .workflow-steps {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.45rem;
            margin: 0.5rem 0;
        }
        .pipeline-node, .wf-step {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(148, 163, 184, 0.22);
            font-size: 0.72rem;
            color: #CBD5E1;
        }
        .pipeline-node.active, .wf-step.active {
            background: rgba(56, 189, 248, 0.15);
            border-color: var(--accent-sky);
            color: var(--accent-sky);
            font-weight: 600;
        }
        .wf-step.done {
            background: rgba(16, 185, 129, 0.12);
            border-color: #10B981;
            color: #34D399;
        }
        .pipeline-arrow, .wf-sep { color: #64748B; font-weight: 700; }

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
        /* Blueprint Hairline Background Overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.032) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.032) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* =========================================================
           BENTO CARDS & CURSOR SPOTLIGHT INTERACTION
        ========================================================= */
        .bento-card {
            position: relative;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: border-color var(--duration-fast) ease, transform var(--duration-base) var(--ease-out), box-shadow var(--duration-base) var(--ease-out);
        }
        .bento-card:hover {
            border-color: rgba(198, 244, 50, 0.35);
        }
        .bento-card.has-spotlight::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(400px circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(198, 244, 50, 0.06), transparent 80%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
            z-index: 1;
        }
        .bento-card.has-spotlight:hover::after {
            opacity: 1;
        }
        .bento-card-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* =========================================================
           MARQUEE TECH STREAM
        ========================================================= */
        .marquee-wrapper {
            position: relative;
            overflow: hidden;
            width: 100%;
            mask-image: linear-gradient(90deg, transparent 0%, #000 7%, #000 93%, transparent 100%);
            -webkit-mask-image: linear-gradient(90deg, transparent 0%, #000 7%, #000 93%, transparent 100%);
            padding: 0.85rem 0;
        }
        .marquee-track {
            display: flex;
            width: max-content;
            animation: marqueeScroll 32s linear infinite;
        }
        .marquee-track:hover {
            animation-play-state: paused;
        }
        .marquee-group {
            display: flex;
            gap: 0.75rem;
            padding-right: 0.75rem;
        }
        .marquee-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.45rem 0.95rem;
            border-radius: var(--radius-sm);
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-primary);
            font-family: var(--font-mono);
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
            transition: border-color var(--duration-fast) ease, transform var(--duration-fast) ease, background var(--duration-fast) ease;
        }
        .marquee-pill:hover {
            border-color: var(--accent-lime);
            transform: translateY(-2px);
            background: rgba(198, 244, 50, 0.05);
        }
        @keyframes marqueeScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* =========================================================
           COMMAND PALETTE (CTRL+K / CMD+K)
        ========================================================= */
        .nav-cmd-trigger {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.38rem 0.75rem;
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            font-family: var(--font-mono);
            font-size: 0.78rem;
            cursor: pointer;
            white-space: nowrap;
            transition: all var(--duration-fast) ease;
        }
        .nav-cmd-trigger:hover {
            border-color: var(--accent-lime);
            color: var(--text-primary);
            background: rgba(198, 244, 50, 0.08);
        }
        .nav-cmd-trigger kbd {
            background: var(--bg-card);
            border: 1px solid var(--border-strong);
            border-radius: 3px;
            padding: 0.1rem 0.35rem;
            font-size: 0.68rem;
            color: var(--text-muted);
        }

        .cmd-palette-backdrop {
            position: fixed;
            inset: 0;
            z-index: 2500;
            background: rgba(6, 9, 15, 0.82);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: clamp(2rem, 10vh, 5rem) 1rem 1rem;
            animation: cmdFadeIn 0.18s ease-out;
        }
        @keyframes cmdFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .cmd-palette-box {
            background: #0B111E;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-md);
            width: 100%;
            max-width: 620px;
            box-shadow: 0 24px 60px -12px rgba(0, 0, 0, 0.85), 0 0 0 1px rgba(198, 244, 50, 0.2);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: cmdSlideDown 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes cmdSlideDown {
            from { opacity: 0; transform: translateY(-12px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .cmd-palette-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.95rem 1.25rem;
            border-bottom: 1px solid var(--border);
            background: var(--bg-card);
        }
        .cmd-search-icon {
            color: var(--accent-lime);
            font-size: 1rem;
        }
        .cmd-input {
            flex: 1;
            background: none;
            border: none;
            color: #F8FAFC;
            font-family: var(--font-sans);
            font-size: 1rem;
            outline: none !important;
        }
        .cmd-input::placeholder { color: var(--text-muted); }
        .cmd-badge {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--border);
            color: var(--text-muted);
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
        }
        .cmd-palette-body {
            max-height: 380px;
            overflow-y: auto;
            padding: 0.65rem;
        }
        .cmd-group {
            margin-bottom: 0.75rem;
        }
        .cmd-group-title {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.06em;
            padding: 0.4rem 0.75rem 0.25rem;
        }
        .cmd-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.6rem 0.85rem;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.88rem;
            width: 100%;
            background: none;
            border: 1px solid transparent;
            cursor: pointer;
            text-align: left;
            transition: all var(--duration-fast) ease;
        }
        .cmd-item:hover, .cmd-item.is-active {
            background: rgba(198, 244, 50, 0.10);
            border-color: rgba(198, 244, 50, 0.35);
            color: #F8FAFC;
        }
        .cmd-item-icon {
            width: 20px;
            text-align: center;
            color: var(--accent-lime);
        }
        .cmd-item-label {
            flex: 1;
            font-weight: 500;
        }
        .cmd-item-hint {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--text-muted);
        }
        .cmd-empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--text-muted);
            font-family: var(--font-mono);
            font-size: 0.86rem;
        }
        .cmd-empty-state i {
            font-size: 1.8rem;
            margin-bottom: 0.65rem;
            color: var(--text-muted);
            display: block;
        }
        .cmd-palette-footer {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 0.6rem 1.25rem;
            border-top: 1px solid var(--border);
            background: rgba(6, 9, 15, 0.6);
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--text-muted);
        }
        .cmd-footer-tip kbd {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--border);
            padding: 0.08rem 0.35rem;
            border-radius: 3px;
            color: var(--text-primary);
        }

        /* =========================================================
           FLOATING AI ASSISTANT ("ASK THIEN")
        ========================================================= */
        .ai-chat-trigger {
            position: fixed;
            bottom: 1.75rem;
            right: 1.75rem;
            z-index: 1500;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.6rem 1.2rem;
            border-radius: 9999px;
            background: #0B111E;
            border: 1px solid rgba(198, 244, 50, 0.45);
            color: #F8FAFC;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.7), 0 0 24px rgba(198, 244, 50, 0.22);
            cursor: pointer;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: -0.01em;
            transition: all var(--duration-fast) ease;
        }
        .ai-chat-trigger:hover {
            transform: translateY(-2px) scale(1.03);
            border-color: var(--accent-lime);
            box-shadow: 0 16px 38px rgba(0, 0, 0, 0.8), 0 0 30px rgba(198, 244, 50, 0.35);
        }
        .ai-pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent-lime);
            box-shadow: 0 0 10px var(--accent-lime);
            animation: radarPulse 1.8s infinite ease-out;
        }
        @keyframes radarPulse {
            0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(198, 244, 50, 0.8); }
            70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(198, 244, 50, 0); }
            100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(198, 244, 50, 0); }
        }
        .ai-trigger-icon { color: var(--accent-lime); font-size: 0.95rem; }
        .ai-trigger-badge {
            font-family: var(--font-mono);
            font-size: 0.65rem;
            font-weight: 800;
            background: var(--accent-lime);
            color: #06090F;
            padding: 0.1rem 0.4rem;
            border-radius: 9999px;
        }

        .ai-drawer-backdrop {
            position: fixed;
            inset: 0;
            z-index: 2600;
            background: rgba(6, 9, 15, 0.72);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            justify-content: flex-end;
            animation: drawerFadeIn 0.22s ease-out;
        }
        @keyframes drawerFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .ai-drawer-container {
            width: 100%;
            max-width: 450px;
            height: 100vh;
            background: #0B111E;
            border-left: 1px solid var(--border-strong);
            display: flex;
            flex-direction: column;
            box-shadow: -16px 0 48px rgba(0, 0, 0, 0.8);
            animation: drawerSlideIn 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes drawerSlideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        .ai-drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.15rem 1.35rem;
            border-bottom: 1px solid var(--border);
            background: rgba(6, 9, 15, 0.7);
        }
        .ai-header-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .ai-avatar-wrap {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            background: rgba(198, 244, 50, 0.12);
            border: 1px solid rgba(198, 244, 50, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ai-avatar-initials {
            font-family: var(--font-mono);
            font-weight: 800;
            font-size: 0.86rem;
            color: var(--accent-lime);
        }
        .ai-live-indicator {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10B981;
            border: 2px solid #0B111E;
        }
        .ai-header-title {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.05rem;
            color: #F8FAFC;
        }
        .ai-model-tag {
            font-family: var(--font-mono);
            font-size: 0.65rem;
            color: var(--accent-lime);
            background: rgba(198, 244, 50, 0.12);
            border: 1px solid rgba(198, 244, 50, 0.25);
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
        }
        .ai-header-subtitle {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }
        .ai-btn-close {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--duration-fast) ease;
        }
        .ai-btn-close:hover {
            border-color: var(--accent-lime);
            color: var(--accent-lime);
        }

        .ai-chips-bar {
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--border-subtle);
            background: rgba(6, 9, 15, 0.4);
        }
        .ai-chips-label {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            color: var(--text-muted);
            margin-bottom: 0.45rem;
        }
        .ai-chips-scroll {
            display: flex;
            gap: 0.45rem;
            overflow-x: auto;
            padding-bottom: 0.35rem;
            scrollbar-width: none;
        }
        .ai-chips-scroll::-webkit-scrollbar { display: none; }
        .ai-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.32rem 0.68rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 0.78rem;
            font-weight: 500;
            white-space: nowrap;
            cursor: pointer;
            flex-shrink: 0;
            transition: all var(--duration-fast) ease;
        }
        .ai-chip:hover {
            border-color: var(--accent-lime);
            color: var(--text-primary);
            background: rgba(198, 244, 50, 0.08);
        }

        .ai-chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }
        .ai-message {
            display: flex;
            gap: 0.75rem;
            max-width: 90%;
        }
        .ai-message.bot { align-self: flex-start; }
        .ai-message.user { align-self: flex-end; flex-direction: row-reverse; }
        .ai-message-avatar {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            background: rgba(198, 244, 50, 0.15);
            border: 1px solid rgba(198, 244, 50, 0.3);
            color: var(--accent-lime);
            font-family: var(--font-mono);
            font-size: 0.74rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .ai-message-bubble {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 0.85rem 1rem;
            color: #F8FAFC;
            font-size: 0.88rem;
            line-height: 1.6;
        }
        .ai-message.user .ai-message-bubble {
            background: rgba(198, 244, 50, 0.15);
            border-color: rgba(198, 244, 50, 0.35);
            color: #FFFFFF;
        }
        .ai-message-bubble p { margin: 0; }
        .ai-message-time {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            color: var(--text-muted);
            margin-top: 0.4rem;
            text-align: right;
        }

        .ai-chat-input-bar {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
            background: rgba(6, 9, 15, 0.8);
        }
        .ai-chat-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0.65rem 0.95rem;
            color: #F8FAFC;
            font-size: 0.9rem;
            outline: none !important;
            transition: border-color var(--duration-fast) ease;
        }
        .ai-chat-input:focus { border-color: var(--accent-lime); }
        .ai-chat-send {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background: var(--accent-lime);
            color: #06090F;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            transition: all var(--duration-fast) ease;
        }
        .ai-chat-send:hover {
            background: var(--accent-lime-hover);
            transform: scale(1.05);
        }

        /* =========================================================
           TOAST NOTIFICATION PILL
        ========================================================= */
        .toast-container {
            position: fixed;
            bottom: 5.5rem;
            right: 1.75rem;
            z-index: 3000;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            pointer-events: none;
        }
        .toast-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.65rem 1.15rem;
            border-radius: var(--radius-sm);
            background: #0B111E;
            border: 1px solid var(--accent-lime);
            color: #F8FAFC;
            font-family: var(--font-mono);
            font-size: 0.82rem;
            box-shadow: 0 12px 28px rgba(0,0,0,0.7), 0 0 16px rgba(198,244,50,0.25);
            animation: toastIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes toastIn {
            from { opacity: 0; transform: translateY(14px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @media (max-width: 640px) {
            .ai-chat-trigger { bottom: 1rem; right: 1rem; padding: 0.5rem 0.9rem; font-size: 0.82rem; }
            .ai-drawer-container { max-width: 100%; }
            .nav-cmd-trigger .cmd-text { display: none; }
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
            <a href="{{ route('home') }}" class="nav-logo" aria-label="Hồ Thành Thiện Portfolio">
                <span class="nav-brand-code">&lt;{{ $isVi ? 'Thiện' : 'Thien' }} /&gt;</span>
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
                    <button type="button" class="nav-cmd-trigger" onclick="openCmdPalette()" aria-label="{{ $isVi ? 'Mở tìm kiếm nhanh (Ctrl+K)' : 'Open command palette (Ctrl+K)' }}" title="{{ $isVi ? 'Tìm kiếm hoặc phím tắt (Ctrl+K)' : 'Search or shortcut (Ctrl+K)' }}">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <span class="cmd-text">{{ $isVi ? 'Tìm kiếm' : 'Search' }}</span>
                        <kbd>⌘K</kbd>
                    </button>

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
                    <span class="nav-brand-code">&lt;{{ $isVi ? 'Thiện' : 'Thien' }} /&gt;</span>
                </div>
                <p class="footer-copy">
                    &copy; {{ date('Y') }} {{ __('Hồ Thành Thiện') }}. {{ __('Built with Laravel & ♥') }}
                </p>
                <div style="margin-top:0.65rem; font-family:var(--font-mono); font-size:0.75rem; color:var(--text-muted); display:flex; align-items:center; gap:0.45rem;">
                    <span style="width:7px; height:7px; border-radius:50%; background:var(--accent-lime); box-shadow:0 0 8px var(--accent-lime); display:inline-block;"></span>
                    <span id="vietnamTime">--:--:--</span> (HCMC &bull; UTC+7)
                </div>
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
                    <a href="tel:0375227764" title="{{ $isVi ? 'Nhấp để gọi' : 'Click to call' }}"><i class="fas fa-phone-alt"></i> 0375 &bull;&bull;&bull; 764</a>
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

    <!-- Command Palette (Ctrl+K) -->
    @include('components.command-palette')

    <!-- Floating AI Assistant Drawer ("Ask Thien") -->
    @include('components.ai-chat-drawer')

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="toast-container" aria-live="polite"></div>

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

        /* Toast Notifications */
        function showToast(message) {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            const toast = document.createElement('div');
            toast.className = 'toast-pill';
            toast.innerHTML = '<i class="fas fa-circle-check" style="color:var(--accent-lime)"></i> <span>' + message + '</span>';
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(8px)';
                setTimeout(() => toast.remove(), 250);
            }, 3000);
        }

        /* 1-Click Copy Email */
        function copyContactEmail() {
            const email = 'hothanhthien119@gmail.com';
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(email).then(() => {
                    showToast('{{ $isVi ? "Đã sao chép email: " : "Email copied: " }}' + email);
                });
            } else {
                showToast(email);
            }
        }

        /* Real-time Vietnam Clock (HCMC UTC+7) */
        function updateVietnamTime() {
            const el = document.getElementById('vietnamTime');
            if (!el) return;
            const now = new Date();
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const vnTime = new Date(utc + (3600000 * 7));
            const hours = String(vnTime.getHours()).padStart(2, '0');
            const minutes = String(vnTime.getMinutes()).padStart(2, '0');
            const seconds = String(vnTime.getSeconds()).padStart(2, '0');
            el.textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateVietnamTime, 1000);
        updateVietnamTime();

        /* Spotlight Cursor Highlight for Bento Cards */
        document.addEventListener('mousemove', (e) => {
            const cards = document.querySelectorAll('.bento-card.has-spotlight');
            cards.forEach(card => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });

        /* =========================================================
           COMMAND PALETTE LOGIC (CTRL+K)
        ========================================================= */
        const cmdPalette = document.getElementById('cmdPalette');
        const cmdInput = document.getElementById('cmdInput');
        const cmdList = document.getElementById('cmdList');
        const cmdEmpty = document.getElementById('cmdEmpty');

        function openCmdPalette() {
            if (!cmdPalette || !cmdInput) return;
            cmdPalette.style.display = 'flex';
            cmdInput.value = '';
            filterCmdItems('');
            setTimeout(() => cmdInput.focus(), 50);
            document.body.style.overflow = 'hidden';
        }

        function closeCmdPalette() {
            if (!cmdPalette) return;
            cmdPalette.style.display = 'none';
            document.body.style.overflow = '';
        }

        if (cmdPalette) {
            cmdPalette.addEventListener('click', (e) => {
                if (e.target === cmdPalette) closeCmdPalette();
            });
        }

        function filterCmdItems(query) {
            const q = query.trim().toLowerCase();
            const groups = cmdList.querySelectorAll('.cmd-group');
            let hasAnyVisible = false;

            groups.forEach(group => {
                let groupVisibleCount = 0;
                const items = group.querySelectorAll('.cmd-item');
                items.forEach(item => {
                    const text = (item.textContent + ' ' + (item.getAttribute('data-tokens') || '')).toLowerCase();
                    const match = !q || text.includes(q);
                    item.style.display = match ? 'flex' : 'none';
                    if (match) {
                        groupVisibleCount++;
                        hasAnyVisible = true;
                    }
                });
                group.style.display = groupVisibleCount > 0 ? 'block' : 'none';
            });

            if (cmdEmpty) cmdEmpty.style.display = hasAnyVisible ? 'none' : 'block';
        }

        if (cmdInput) {
            cmdInput.addEventListener('input', (e) => filterCmdItems(e.target.value));
            cmdInput.addEventListener('keydown', (e) => {
                const visibleItems = Array.from(cmdList.querySelectorAll('.cmd-item')).filter(el => el.style.display !== 'none');
                let activeIdx = visibleItems.findIndex(el => el.classList.contains('is-active'));

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (activeIdx >= 0) visibleItems[activeIdx].classList.remove('is-active');
                    activeIdx = (activeIdx + 1) % visibleItems.length;
                    visibleItems[activeIdx].classList.add('is-active');
                    visibleItems[activeIdx].scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (activeIdx >= 0) visibleItems[activeIdx].classList.remove('is-active');
                    activeIdx = (activeIdx - 1 + visibleItems.length) % visibleItems.length;
                    visibleItems[activeIdx].classList.add('is-active');
                    visibleItems[activeIdx].scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    const target = activeIdx >= 0 ? visibleItems[activeIdx] : visibleItems[0];
                    if (target) {
                        closeCmdPalette();
                        target.click();
                    }
                } else if (e.key === 'Escape') {
                    closeCmdPalette();
                }
            });
        }

        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                if (cmdPalette && cmdPalette.style.display !== 'none') {
                    closeCmdPalette();
                } else {
                    openCmdPalette();
                }
            } else if (e.key === 'Escape' && cmdPalette && cmdPalette.style.display !== 'none') {
                closeCmdPalette();
            }
        });

        /* =========================================================
           AI ASSISTANT "ASK THIEN" KNOWLEDGE BASE & LOGIC
        ========================================================= */
        const aiDrawer = document.getElementById('aiChatDrawer');
        const aiMessages = document.getElementById('aiChatMessages');
        const aiInput = document.getElementById('aiUserInput');

        function toggleAiChatDrawer() {
            if (!aiDrawer) return;
            const isHidden = aiDrawer.style.display === 'none';
            if (isHidden) openAiChatDrawer();
            else closeAiChatDrawer();
        }

        function openAiChatDrawer() {
            if (!aiDrawer) return;
            aiDrawer.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => { if (aiInput) aiInput.focus(); }, 150);
        }

        function closeAiChatDrawer() {
            if (!aiDrawer) return;
            aiDrawer.style.display = 'none';
            document.body.style.overflow = '';
        }

        function closeAiChatOnBackdrop(e) {
            if (e.target === aiDrawer) closeAiChatDrawer();
        }

        const isViLang = {{ $isVi ? 'true' : 'false' }};

        function getAiKnowledgeResponse(query) {
            const q = query.toLowerCase();

            if (q.includes('kinh nghiệm') || q.includes('experience') || q.includes('enmasys') || q.includes('aipower') || q.includes('công việc')) {
                return isViLang
                    ? 'Hồ Thành Thiện đã có kinh nghiệm thực chiến tại:\n• AIPower Company (07/2026 - Nay): Kỹ sư Fullstack phụ trách xây dựng web công ty và nền tảng giáo dục bằng PHP, PostgreSQL, responsive UI.\n• Enmasys (12/2025 - 04/2026): Thực tập sinh Odoo, cấu hình và tùy biến các phân hệ ERP, tối ưu quy trình quản trị doanh nghiệp.\n• Ngoài ra, Thiện còn tự phát triển các hệ thống độc lập như Chatbot RAG (TourRist) và Quản lý Giặt ủi (NextJS + Spring Boot).'
                    : 'Ho Thanh Thien has solid real-world experience at:\n• AIPower Company (07/2026 - Present): Fullstack Developer engineering company and education portals using PHP, PostgreSQL and modern responsive architecture.\n• Enmasys (12/2025 - 04/2026): Odoo ERP Intern, customizing business modules and system workflows.\n• Independent systems: RAG AI chatbot (TourRist) and Laundry Management System (NextJS + Spring Boot).';
            }

            if (q.includes('tourrist') || q.includes('rag') || q.includes('ai') || q.includes('llm') || q.includes('chatbot')) {
                return isViLang
                    ? 'Dự án "TourRist" là sản phẩm AI/RAG tiêu biểu do Thiện phát triển:\n• Kiến trúc RAG: Đọc tài liệu riêng tư → Chunking → Embeddings → Lưu trữ Vector trong ChromaDB → Semantic Retrieval → Tổng hợp prompt truyền cho LLM.\n• Tech stack: Python, FastAPI, ChromaDB, LangChain, RESTful APIs.\n• Ý nghĩa: Giúp chatbot trả lời chính xác dựa trên tri thức nội bộ, loại bỏ hiện tượng ảo giác (hallucination) của mô hình.'
                    : 'The "TourRist" project is Thien\'s flagship Applied AI & RAG system:\n• RAG Pipeline: Ingests private documents → chunking & embedding → indexed into ChromaDB vector store → semantic search retrieval → context-augmented prompt generation for LLMs.\n• Stack: Python, FastAPI, ChromaDB, LangChain, RESTful APIs.\n• Key benefit: High-accuracy answers with zero hallucination from private knowledge bases.';
            }

            if (q.includes('học vấn') || q.includes('gpa') || q.includes('uth') || q.includes('bằng') || q.includes('education') || q.includes('degree')) {
                return isViLang
                    ? 'Về học vấn và thành tích chính thức:\n• Tốt nghiệp Cử nhân Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH).\n• Xếp loại tốt nghiệp: Xuất sắc.\n• Điểm tích lũy toàn khóa: 3.6 / 4.0.\n• Điểm kỳ gần nhất: Điểm tuyệt đối 4.0 / 4.0.\n• Thành tích ngoại khóa: Đạt Giải Nhì Cuộc thi Sáng tác Logo & Hệ thống Nhận diện Khoa/Viện CNTT.'
                    : 'Academic background & verified credentials:\n• Bachelor of Information Technology from University of Transport Ho Chi Minh City (UTH).\n• Graduation Classification: Excellent.\n• Cumulative GPA: 3.6 / 4.0.\n• Recent Semester GPA: Perfect 4.0 / 4.0.\n• Honors: Second Prize in Official IT Faculty Logo & Identity Design Competition.';
            }

            if (q.includes('stack') || q.includes('kỹ năng') || q.includes('skill') || q.includes('tech') || q.includes('ngôn ngữ')) {
                return isViLang
                    ? 'Bộ kỹ năng cốt lõi của Thiện gồm:\n• Backend: PHP, Laravel 11, Java Spring Boot, Python FastAPI, Odoo ERP.\n• Frontend: ReactJS, NextJS, JavaScript ES6+, Blade Engine, Tailwind CSS.\n• AI & Data: RAG Architecture, Vector Search (ChromaDB), MySQL, PostgreSQL.\n• DevOps & Triển khai: Docker, Docker Compose, Nginx, cPanel, Git, RESTful API & JWT Auth.'
                    : 'Thien\'s core technology stack:\n• Backend: PHP, Laravel 11, Java Spring Boot, Python FastAPI, Odoo ERP.\n• Frontend: ReactJS, NextJS, Modern JavaScript, Blade Engine, Tailwind CSS.\n• AI & Data: RAG Architecture, Vector Search (ChromaDB), MySQL, PostgreSQL.\n• DevOps & Systems: Docker, Docker Compose, Nginx, cPanel, Git, RESTful APIs, JWT Auth.';
            }

            if (q.includes('liên hệ') || q.includes('contact') || q.includes('tuyển dụng') || q.includes('hire') || q.includes('email') || q.includes('phone') || q.includes('phỏng vấn')) {
                return isViLang
                    ? 'Bạn có thể kết nối phỏng vấn với Hồ Thành Thiện ngay qua:\n• Email: hothanhthien119@gmail.com\n• Số điện thoại: 0375 227 764 (hoặc 0375 ••• 764)\n• LinkedIn: linkedin.com/in/httblackhat\n• GitHub: github.com/hoThanhThien\nThiện đang sẵn sàng cho cơ hội làm việc Full-time (Fullstack / Backend / Applied AI).'
                    : 'You can reach Ho Thanh Thien directly for interview & hiring:\n• Email: hothanhthien119@gmail.com\n• Phone: 0375 227 764\n• LinkedIn: linkedin.com/in/httblackhat\n• GitHub: github.com/hoThanhThien\nThien is currently available for Full-time opportunities (Fullstack / Backend / Applied AI).';
            }

            if (q.includes('dự án') || q.includes('project') || q.includes('portfolio') || q.includes('laundry')) {
                return isViLang
                    ? 'Thiện đã hoàn thiện 3 dự án trọng điểm chạy production:\n1. Personal Portfolio & CMS (Laravel 11, MySQL, bilingual, sitemap động).\n2. TourRist (RAG Chatbot với FastAPI, ChromaDB, vector search).\n3. Laundry Management System (NextJS + Spring Boot, State Machine đơn hàng, RBAC & JWT).\nBạn có thể nhấp vào mục "Dự án" trên menu để xem case study chi tiết từng sản phẩm!'
                    : 'Thien has built 3 flagship projects on production:\n1. Personal Portfolio & CMS (Laravel 11, MySQL, bilingual, dynamic sitemap).\n2. TourRist (RAG AI Chatbot with FastAPI, ChromaDB, Vector Search).\n3. Laundry Management System (NextJS + Spring Boot, Order State Machine, RBAC & JWT).\nVisit the "Projects" section to explore full case studies!';
            }

            return isViLang
                ? `Cảm ơn bạn đã hỏi! Hồ Thành Thiện là Kỹ sư Fullstack tốt nghiệp UTH (GPA 3.6/4.0), thành thạo Laravel, Java Spring Boot, ReactJS và kiến trúc AI/RAG. Bạn có thể nhấn vào các nút gợi ý phía trên hoặc liên hệ trực tiếp qua email: hothanhthien119@gmail.com.`
                : `Thank you for asking! Ho Thanh Thien is a Fullstack Engineer graduated from UTH (GPA 3.6/4.0), skilled in Laravel, Java Spring Boot, ReactJS, and RAG architectures. Feel free to use the suggested prompts above or reach out via email: hothanhthien119@gmail.com.`;
        }

        function appendAiMessage(role, text) {
            if (!aiMessages) return;
            const msgDiv = document.createElement('div');
            msgDiv.className = `ai-message ${role}`;
            const timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            if (role === 'user') {
                msgDiv.innerHTML = `
                    <div class="ai-message-bubble">
                        <p>${escapeHtml(text)}</p>
                        <div class="ai-message-time">${timeStr}</div>
                    </div>
                `;
            } else {
                msgDiv.innerHTML = `
                    <div class="ai-message-avatar">&lt;T/&gt;</div>
                    <div class="ai-message-bubble">
                        <p style="white-space: pre-line;">${escapeHtml(text)}</p>
                        <div class="ai-message-time">${timeStr}</div>
                    </div>
                `;
            }
            aiMessages.appendChild(msgDiv);
            aiMessages.scrollTop = aiMessages.scrollHeight;
        }

        function escapeHtml(str) {
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function handleAiSubmit(e) {
            e.preventDefault();
            if (!aiInput) return;
            const text = aiInput.value.trim();
            if (!text) return;
            aiInput.value = '';

            appendAiMessage('user', text);

            // Typing indicator simulation
            const typingDiv = document.createElement('div');
            typingDiv.className = 'ai-message bot';
            typingDiv.id = 'aiTypingIndicator';
            typingDiv.innerHTML = `
                <div class="ai-message-avatar">&lt;T/&gt;</div>
                <div class="ai-message-bubble" style="color:var(--text-muted); font-style:italic;">
                    <i class="fas fa-circle-notch fa-spin"></i> ${isViLang ? 'Đang phân tích dữ liệu CMS...' : 'Searching CMS knowledge base...'}
                </div>
            `;
            aiMessages.appendChild(typingDiv);
            aiMessages.scrollTop = aiMessages.scrollHeight;

            setTimeout(() => {
                typingDiv.remove();
                const response = getAiKnowledgeResponse(text);
                appendAiMessage('bot', response);
            }, 450);
        }

        function askAiPreset(question) {
            if (aiInput) aiInput.value = question;
            const form = document.querySelector('.ai-chat-input-bar');
            if (form) form.dispatchEvent(new Event('submit', { cancelable: true }));
        }

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
