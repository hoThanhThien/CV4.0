<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
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
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

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
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="@yield('title', $defaultTitle)">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $defaultTitle)">
    <meta name="twitter:description" content="@yield('description', $defaultDescription)">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-image.png'))">
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
          "image": "{{ asset('images/og-image.png') }}",
          "email": "hothanhthien119@gmail.com",
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Ho Chi Minh City",
            "addressCountry": "VN"
          },
          "sameAs": [
            "https://github.com/hoThanhThien"
          ],
          "knowsAbout": ["PHP", "Laravel", "JavaScript", "Vue.js", "React", "MySQL", "RESTful API", "Full-Stack Development", "Clean Architecture"],
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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            --border: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --accent: #6366f1;
            --accent-light: #8b5cf6;
            --accent-glow: rgba(99, 102, 241, 0.2);
            --cyan: #06b6d4;
            --green: #10b981;
            --pink: #ec4899;
            --gradient: linear-gradient(135deg, #6366f1, #06b6d4);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-secondary); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* Navigation */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.75rem 2rem;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }

        .nav-logo {
            font-size: 1.4rem; font-weight: 800;
            color: var(--accent);
            text-decoration: none;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1002;
        }

        /* Scroll Progress Bar */
        .scroll-progress-bar {
            position: fixed; top: 0; left: 0; height: 3px; width: 0%;
            background: linear-gradient(90deg, #6366f1, #06b6d4, #8b5cf6);
            z-index: 1200; pointer-events: none;
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.7);
            transition: width 0.1s ease-out;
        }

        .nav-links { display: flex; gap: 0.25rem; list-style: none; align-items: center; }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none; padding: 0.5rem 1rem;
            border-radius: 8px; font-size: 0.9rem; font-weight: 500;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .nav-links a:not(.btn):not(.lang-btn):hover, .nav-links a:not(.btn):not(.lang-btn).active {
            color: var(--accent);
            background: rgba(99, 102, 241, 0.08);
        }

        .nav-cta {
            background: var(--gradient); background-size: 200% auto; color: white !important;
            padding: 0.5rem 1.25rem !important; border-radius: 8px !important;
            box-shadow: 0 4px 14px var(--accent-glow);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .nav-cta:hover { opacity: 1; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99, 102, 241, 0.35); }

        .lang-switcher {
            display: inline-flex; align-items: center; gap: 3px;
            padding: 3px; background: #f1f5f9;
            border: 1px solid var(--border); border-radius: 10px;
            margin-left: 0.5rem;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: all 0.25s ease;
        }
        .lang-switcher:hover {
            border-color: rgba(99, 102, 241, 0.35);
        }
        .lang-switcher .lang-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            width: auto !important;
            padding: 0.35rem 0.65rem !important;
            border-radius: 7px;
            text-decoration: none;
            color: var(--text-secondary) !important;
            font-weight: 700;
            font-size: 0.82rem;
            line-height: 1;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }
        .lang-switcher .lang-btn:hover {
            color: var(--text-primary) !important;
        }
        .lang-switcher .lang-btn.active {
            color: var(--accent) !important;
            background: #ffffff !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        }
        .flag-icon {
            border-radius: 2px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
            flex-shrink: 0;
            display: inline-block;
            vertical-align: middle;
            transition: transform 0.2s ease;
        }
        .lang-switcher .lang-btn:hover .flag-icon {
            transform: scale(1.08);
        }

        .hamburger {
            display: none; background: var(--bg-card); border: 1px solid var(--border);
            color: var(--text-primary); width: 42px; height: 42px; border-radius: 10px;
            align-items: center; justify-content: center; font-size: 1.15rem; cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            z-index: 1002;
        }
        .hamburger:hover { background: var(--bg-secondary); border-color: var(--accent); }
        .hamburger:active { transform: scale(0.95); }

        .nav-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
            z-index: 998; opacity: 0; transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .nav-backdrop.open { display: block; opacity: 1; pointer-events: auto; }

        /* Page content offset for fixed navbar */
        .page-content { padding-top: 70px; min-height: calc(100vh - 200px); }

        /* Footer */
        .footer {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border);
            padding: 3.5rem 2rem 2.5rem;
            text-align: center;
        }

        .footer-logo {
            font-size: 1.5rem; font-weight: 800;
            color: var(--accent);
            margin-bottom: 1rem; display: block;
            transition: transform 0.3s ease;
        }
        .footer-logo:hover { transform: scale(1.05); }

        .footer-links { display: flex; justify-content: center; gap: 2rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .footer-links a { color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; transition: color 0.2s, transform 0.2s; padding: 0.25rem 0.5rem; }
        .footer-links a:hover { color: var(--accent); transform: translateY(-1px); }

        .footer-socials { display: flex; justify-content: center; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .footer-socials a {
            width: 42px; height: 42px; border-radius: 10px;
            background: var(--bg-card); border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary); text-decoration: none;
            font-size: 1.1rem; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .footer-socials a:hover { color: var(--accent-light); border-color: var(--accent); transform: translateY(-3px) scale(1.08); box-shadow: 0 6px 16px var(--accent-glow); }

        .footer-copy { color: var(--text-secondary); font-size: 0.85rem; line-height: 1.6; }

        /* Shared utilities */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 2rem; width: 100%; }
        .section { padding: 5.5rem 0; }
        .section-title {
            font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 800;
            text-align: center; margin-bottom: 0.75rem; letter-spacing: -0.02em;
        }
        .section-subtitle {
            color: var(--text-secondary); text-align: center;
            font-size: 1.05rem; margin-bottom: 3.5rem; max-width: 600px; margin-left: auto; margin-right: auto;
        }

        /* Animated Shimmering Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #06b6d4 50%, #8b5cf6 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShimmer 6s ease infinite;
        }
        @keyframes gradientShimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px; padding: 1.5rem;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .card:hover {
            border-color: rgba(99, 102, 241, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.12), 0 2px 8px rgba(0,0,0,0.04);
        }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;
            padding: 0.6rem 1.25rem; border-radius: 10px; min-height: 42px;
            font-size: 0.9rem; font-weight: 600; text-decoration: none;
            border: none; cursor: pointer; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            touch-action: manipulation;
        }
        .btn:active { transform: scale(0.97); }

        /* Button Shine Effect */
        .btn-primary {
            background: var(--gradient); background-size: 200% auto; color: white;
            position: relative; overflow: hidden;
            box-shadow: 0 4px 14px var(--accent-glow);
            transition: all 0.4s ease;
        }
        .btn-primary::after {
            content: ''; position: absolute; top: -50%; left: -90%; width: 50%; height: 200%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: rotate(25deg); pointer-events: none; transition: none;
        }
        .btn-primary:hover::after {
            left: 150%; transition: left 0.75s ease-in-out;
        }
        .btn-primary:hover {
            background-position: right center; opacity: 1; transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.4);
        }

        .btn-outline { background: transparent; color: var(--text-primary); border: 1px solid var(--border); }
        .btn-outline:hover {
            border-color: var(--accent); color: var(--accent);
            background: rgba(99,102,241,0.06); transform: translateY(-2px);
        }

        .tag {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.25rem 0.75rem; border-radius: 6px;
            font-size: 0.78rem; font-weight: 600;
            background: rgba(124, 58, 237, 0.12); color: var(--accent-light);
            border: 1px solid rgba(124, 58, 237, 0.25);
            transition: all 0.2s ease;
        }
        .tag:hover {
            background: rgba(124, 58, 237, 0.2);
            border-color: var(--accent);
            transform: translateY(-1px);
        }

        /* Alerts */
        .alert { padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; font-size: 0.9rem; word-break: break-word; }
        .alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: #10b981; }
        .alert-danger { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; }

        /* Pagination */
        .pagination-wrap { display: flex; justify-content: center; gap: 0.5rem; margin-top: 3rem; flex-wrap: wrap; }
        .pagination-wrap .page-link {
            padding: 0.5rem 0.9rem; border-radius: 8px; text-decoration: none; min-height: 40px; min-width: 40px;
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--bg-card); border: 1px solid var(--border); color: var(--text-secondary);
            transition: all 0.2s; font-size: 0.9rem;
        }
        .pagination-wrap .page-link:hover, .pagination-wrap .page-link.active {
            background: var(--accent); border-color: var(--accent); color: white; transform: translateY(-1px);
        }

        /* Scroll Reveal Animation System */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-fade {
            opacity: 0;
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity;
        }
        .reveal-fade.revealed {
            opacity: 1;
        }
        .reveal-left {
            opacity: 0;
            transform: translateX(-35px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-left.revealed {
            opacity: 1;
            transform: translateX(0);
        }
        .reveal-right {
            opacity: 0;
            transform: translateX(35px);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-right.revealed {
            opacity: 1;
            transform: translateX(0);
        }

        /* Animation Stagger Delays */
        .delay-1 { transition-delay: 0.1s; }
        .delay-2 { transition-delay: 0.2s; }
        .delay-3 { transition-delay: 0.3s; }
        .delay-4 { transition-delay: 0.4s; }
        .delay-5 { transition-delay: 0.5s; }

        @media (prefers-reduced-motion: reduce) {
            .reveal, .reveal-fade, .reveal-left, .reveal-right {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
            .gradient-text { animation: none !important; }
        }

        /* Mobile adjustments & responsive rules */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.75rem 1.25rem;
            }
            .navbar.scrolled {
                padding: 0.65rem 1.25rem;
            }
            .hamburger {
                display: flex;
            }
            .nav-links {
                position: absolute; top: 100%; left: 0; right: 0;
                background: #ffffff !important;
                padding: 1.25rem 1.25rem 1.75rem;
                border-bottom: 2px solid var(--border);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                flex-direction: column; align-items: stretch; gap: 0.5rem;
                opacity: 0; visibility: hidden; transform: translateY(-10px);
                transition: opacity 0.25s ease, transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.25s;
                max-height: calc(100vh - 65px);
                max-height: calc(100dvh - 65px);
                overflow-y: auto;
                z-index: 1001;
            }
            .nav-links.open {
                opacity: 1; visibility: visible; transform: translateY(0);
            }
            .nav-links a:not(.btn):not(.lang-btn) {
                padding: 0.9rem 1.15rem; font-size: 1rem; font-weight: 600;
                border-radius: 12px; display: flex; align-items: center; justify-content: space-between;
                width: 100%; color: var(--text-primary);
                background: #f8fafc;
                border: 1px solid transparent;
                transition: all 0.2s ease;
            }
            .nav-links a:not(.btn):not(.lang-btn):hover, .nav-links a:not(.btn):not(.lang-btn).active {
                color: var(--accent);
                background: rgba(99, 102, 241, 0.08);
                border-color: rgba(99, 102, 241, 0.2);
            }
            .nav-cta {
                text-align: center; justify-content: center !important;
                margin-top: 0.5rem; padding: 0.95rem 1.25rem !important;
                font-size: 1rem !important; font-weight: 700 !important;
                border-radius: 12px !important;
                display: flex !important; width: 100% !important;
            }
            .lang-switcher {
                margin-left: 0; margin-top: 0.75rem; padding: 4px;
                width: 100%; border-radius: 14px;
                background: #f1f5f9;
                border: 1px solid var(--border);
                display: flex; gap: 4px;
                box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
            }
            .lang-switcher .lang-btn {
                flex: 1;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 8px !important;
                padding: 0.65rem 1rem !important;
                border-radius: 10px;
                font-weight: 700;
                font-size: 0.92rem;
                background: transparent !important;
                color: var(--text-secondary) !important;
                box-shadow: none !important;
                border: none !important;
                width: auto !important;
                transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .lang-switcher .lang-btn .flag-icon {
                width: 18px;
                height: 13.5px;
            }
            .lang-switcher .lang-btn.active {
                background: #ffffff !important;
                color: var(--accent) !important;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.05) !important;
            }

            .container { padding: 0 1.25rem; }
            .section { padding: 3.5rem 0; }
            .section-title { font-size: clamp(1.6rem, 5vw, 2.1rem); margin-bottom: 0.5rem; }
            .section-subtitle { font-size: 0.95rem; margin-bottom: 2.25rem; }
            .card { padding: 1.25rem; border-radius: 14px; }
            .page-content { padding-top: 65px; }

            .footer { padding: 2.75rem 1.25rem 2rem; }
            .footer-links { gap: 1rem 1.25rem; }
            .footer-socials { gap: 0.75rem; }
            .footer-socials a { width: 44px; height: 44px; }

            .back-to-top {
                bottom: 1.25rem; right: 1.25rem;
                width: 42px; height: 42px; font-size: 1.05rem;
            }
        }

        @media (max-width: 480px) {
            .container { padding: 0 1rem; }
            .navbar { padding: 0.65rem 1rem; }
            .navbar.scrolled { padding: 0.6rem 1rem; }
            .nav-logo { font-size: 1.25rem; }
            .section { padding: 3rem 0; }
            .card { padding: 1rem; }
            .pagination-wrap { gap: 0.35rem; }
            .pagination-wrap .page-link { padding: 0.4rem 0.7rem; font-size: 0.82rem; min-height: 36px; min-width: 36px; }
        }

        /* Back to top */
        .back-to-top {
            position: fixed; bottom: 2rem; right: 2rem; z-index: 999;
            width: 45px; height: 45px; border-radius: 50%;
            background: var(--accent); color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; cursor: pointer; border: none;
            box-shadow: 0 4px 12px var(--accent-glow);
            opacity: 0; pointer-events: none; transform: translateY(20px);
            transition: all 0.3s ease;
        }
        .back-to-top.visible {
            opacity: 1; pointer-events: auto; transform: translateY(0);
        }
        .back-to-top:hover {
            background: var(--accent-light); transform: translateY(-3px); box-shadow: 0 6px 16px var(--accent-glow);
        }

        /* Noise overlay */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: -1;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Top Scroll Progress Bar -->
    <div class="scroll-progress-bar" id="scrollProgressBar"></div>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <a href="{{ route('home') }}" class="nav-logo">&lt;{{ __('Thiện') }} /&gt;</a>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
            <i class="fas fa-bars" id="hamburger-icon"></i>
        </button>
        <ul class="nav-links" id="nav-links">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Home') }}</a></li>
            <li><a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'active' : '' }}">{{ __('Projects') }}</a></li>
            <li><a href="{{ route('blog.index') }}" class="{{ request()->routeIs('blog.*') ? 'active' : '' }}">{{ __('Blog') }}</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __('About') }}</a></li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('Contact') }}</a></li>
            <li><a href="{{ route('contact') }}" class="btn btn-primary nav-cta">{{ __('Hire Me') }}</a></li>
            <li class="lang-switcher" role="group" aria-label="Language selection">
                <a href="{{ route('lang.switch', 'vi') }}" class="lang-btn {{ session('locale') == 'vi' ? 'active' : '' }}" title="Tiếng Việt">
                    <svg class="flag-icon" viewBox="0 0 640 480" width="18" height="13.5" aria-hidden="true">
                        <rect width="640" height="480" fill="#da251d"/>
                        <polygon points="320,120 357,235 477,235 380,305 417,420 320,350 223,420 260,305 163,235 283,235" fill="#ffff00"/>
                    </svg>
                    <span>VI</span>
                </a>
                <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ session('locale') != 'vi' ? 'active' : '' }}" title="English">
                    <svg class="flag-icon" viewBox="0 0 640 480" width="18" height="13.5" aria-hidden="true">
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
        @if(session('success'))
            <div class="container" style="padding-top:1rem">
                <div class="alert alert-success">{{ session('success') }}</div>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <span class="footer-logo">&lt;{{ __('Thiện') }} /&gt;</span>
        <nav class="footer-links">
            <a href="{{ route('home') }}">{{ __('Home') }}</a>
            <a href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
            <a href="{{ route('blog.index') }}">{{ __('Blog') }}</a>
            <a href="{{ route('about') }}">{{ __('About') }}</a>
            <a href="{{ route('contact') }}">{{ __('Contact') }}</a>
        </nav>
        <div class="footer-socials">
            <a href="https://github.com/hoThanhThien" aria-label="GitHub"><i class="fab fa-github"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="{{ route('contact') }}" aria-label="{{ __('Contact') }}" title="{{ __('Contact') }}"><i class="fas fa-envelope"></i></a>
        </div>
        <p class="footer-copy">&copy; {{ date('Y') }} {{ __('Hồ Thành Thiện') }}. {{ __('Built with Laravel & ♥') }}</p>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Scroll progress bar
        const progressBar = document.getElementById('scrollProgressBar');
        function updateProgress() {
            const scrollTop = window.scrollY || document.documentElement.scrollTop;
            const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            if (progressBar) progressBar.style.width = Math.min(100, Math.max(0, progress)) + '%';
        }
        window.addEventListener('scroll', updateProgress, { passive: true });

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });

        // Mobile menu toggle & interactions
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
                if (isOpen) {
                    hamburgerIcon.classList.remove('fa-bars');
                    hamburgerIcon.classList.add('fa-xmark');
                } else {
                    hamburgerIcon.classList.remove('fa-xmark');
                    hamburgerIcon.classList.add('fa-bars');
                }
            }
            if (isOpen) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        hamburger.addEventListener('click', () => toggleMenu());
        if (navBackdrop) navBackdrop.addEventListener('click', () => toggleMenu(false));

        // Close menu on link click
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => toggleMenu(false));
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navLinks.classList.contains('open')) {
                toggleMenu(false);
            }
        });

        // Back to top
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        }, { passive: true });
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Scroll Reveal Observer
        const revealElements = document.querySelectorAll('.reveal, .reveal-fade, .reveal-left, .reveal-right');
        if ('IntersectionObserver' in window && revealElements.length > 0) {
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '0px 0px -40px 0px',
                threshold: 0.12
            });

            revealElements.forEach(el => revealObserver.observe(el));
        } else {
            // Fallback for browsers without IntersectionObserver
            revealElements.forEach(el => el.classList.add('revealed'));
        }
    </script>

    @yield('scripts')
</body>
</html>
