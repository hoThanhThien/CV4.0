@extends('layouts.app')

@section('title', __('Hồ Thành Thiện') . ' | ' . (app()->getLocale() == 'vi' ? 'Kỹ sư Full-Stack Web Developer' : 'Full-Stack Software Engineer'))
@section('description', __('Aspiring Fullstack Developer with hands-on experience in building web applications through academic and personal projects. Skilled in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs, with a solid foundation in database design and software development.'))
@section('keywords', app()->getLocale() == 'vi' ? 'Hồ Thành Thiện, Kỹ sư Full-Stack, Lập trình viên Web, Laravel Developer Việt Nam, PHP Developer, JavaScript, React, Odoo, RAG LLM, Clean Architecture, Tuyển dụng lập trình viên' : 'Ho Thanh Thien, Full-Stack Developer, Web Developer, Laravel Software Engineer, PHP Developer Vietnam, JavaScript, React, Odoo, RAG LLM, Clean Code Architecture')

@section('styles')
<style>
    /* ===== HERO ===== */
    .hero {
        min-height: calc(100svh - 70px);
        min-height: 88vh;
        display: flex; align-items: center;
        position: relative; overflow: hidden;
        padding: 4.5rem 0 3.5rem;
    }

    .hero-bg {
        position: absolute; inset: 0; z-index: 0;
        background: radial-gradient(ellipse 80% 80% at 50% -20%, rgba(124,58,237,0.25) 0%, transparent 60%),
                    radial-gradient(ellipse 60% 60% at 80% 50%, rgba(6,182,212,0.1) 0%, transparent 60%);
    }

    /* Ambient Floating Glow Blobs */
    .hero-glow-blob {
        position: absolute; border-radius: 50%; filter: blur(75px);
        pointer-events: none; z-index: 0; opacity: 0.4;
    }
    .blob-1 {
        width: 380px; height: 380px; top: 5%; left: 0%;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.45) 0%, transparent 70%);
        animation: floatBlob1 14s ease-in-out infinite alternate;
    }
    .blob-2 {
        width: 420px; height: 420px; bottom: 5%; right: 0%;
        background: radial-gradient(circle, rgba(6, 182, 212, 0.35) 0%, transparent 70%);
        animation: floatBlob2 16s ease-in-out infinite alternate;
    }
    @keyframes floatBlob1 {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(50px, 30px) scale(1.12); }
    }
    @keyframes floatBlob2 {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(-45px, -35px) scale(1.15); }
    }

    .hero-grid {
        position: absolute; inset: 0; z-index: -1;
        background-size: 40px 40px;
        background-image: linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);
        mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
    }

    .hero-content { position: relative; z-index: 1; max-width: 820px; width: 100%; }

    .hero-line {
        display: block;
    }

    @media (min-width: 992px) {
        .hero-content {
            max-width: 980px;
        }
        .hero-line-1,
        .hero-line-2 {
            white-space: nowrap;
        }
    }

    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.35rem 0.9rem; border-radius: 50px;
        background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3);
        color: var(--accent-light); font-size: 0.85rem; font-weight: 600;
        margin-bottom: 1.25rem; animation: fadeInDown 0.6s ease;
        text-decoration: none; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 10px rgba(99, 102, 241, 0.1);
    }
    .hero-badge:hover {
        background: rgba(124,58,237,0.25);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.2);
    }
    .hero-badge .dot {
        width: 8px; height: 8px; border-radius: 50%; background: var(--green);
        box-shadow: 0 0 8px var(--green); animation: pulse 2s infinite;
    }

    @keyframes pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.4; transform: scale(0.85); } }

    .hero h1 {
        font-size: clamp(2.1rem, 4.4vw, 3.4rem); font-weight: 900;
        line-height: 1.2; margin-bottom: 1.25rem; letter-spacing: -0.02em;
        word-break: normal; overflow-wrap: break-word;
    }

    .hero p {
        font-size: clamp(1rem, 2.5vw, 1.15rem); color: var(--text-secondary);
        margin-bottom: 1.75rem; max-width: 680px; line-height: 1.7;
    }

    .hero-actions {
        display: flex; gap: 1rem; flex-wrap: wrap;
    }

    .hero-scroll {
        position: absolute; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
        display: flex; flex-direction: column; align-items: center; gap: 0.5rem;
        color: var(--text-secondary); font-size: 0.8rem; animation: fadeInUp 1s ease 0.8s both;
    }
    .hero-scroll .scroll-line {
        width: 1px; height: 40px; background: linear-gradient(to bottom, var(--accent), transparent);
        animation: scrollDown 1.5s ease-in-out infinite;
    }

    @keyframes scrollDown { 0% { opacity: 0; transform: translateY(-10px); } 100% { opacity: 1; transform: translateY(10px); } }
    @keyframes fadeInDown { from { opacity:0; transform: translateY(-20px); } to { opacity:1; transform: none; } }
    @keyframes fadeInUp { from { opacity:0; transform: translateY(30px); } to { opacity:1; transform: none; } }

    /* ===== KEY ACHIEVEMENTS (PRESTIGE EDITION) ===== */
    .achievements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 320px), 1fr));
        gap: 1.75rem;
    }
    .achievement-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.85);
        border-radius: 22px;
        padding: 1.85rem 1.65rem 1.65rem;
        position: relative;
        overflow: hidden;
        transition: all 0.38s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 20px -4px rgba(15, 23, 42, 0.05);
    }
    .achievement-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: var(--card-gradient, var(--gradient));
        transition: height 0.3s ease;
    }
    .achievement-card::after {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 140px; height: 140px;
        background: var(--card-glow, rgba(99, 102, 241, 0.12));
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0.5;
        transition: all 0.4s ease;
        pointer-events: none;
    }
    .achievement-card:hover {
        transform: translateY(-8px);
        border-color: var(--card-border-hover, rgba(99, 102, 241, 0.4));
        box-shadow: 0 20px 40px -12px var(--card-glow, rgba(99, 102, 241, 0.2)), 0 2px 6px rgba(0,0,0,0.03);
    }
    .achievement-card:hover::before {
        height: 6px;
    }
    .achievement-card:hover::after {
        opacity: 0.85;
        transform: scale(1.25);
    }

    /* Card Themes */
    .achievement-card.theme-blue {
        --card-gradient: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
        --card-glow: rgba(79, 70, 229, 0.2);
        --card-border-hover: rgba(79, 70, 229, 0.4);
        --card-icon-gradient: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);
        --card-icon-shadow: rgba(37, 99, 235, 0.35);
        --chip-bg: rgba(79, 70, 229, 0.08);
        --chip-color: #4338ca;
        --chip-border: rgba(79, 70, 229, 0.2);
    }
    .achievement-card.theme-emerald {
        --card-gradient: linear-gradient(135deg, #059669 0%, #06b6d4 100%);
        --card-glow: rgba(5, 150, 105, 0.2);
        --card-border-hover: rgba(5, 150, 105, 0.4);
        --card-icon-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
        --card-icon-shadow: rgba(16, 185, 129, 0.35);
        --chip-bg: rgba(5, 150, 105, 0.08);
        --chip-color: #047857;
        --chip-border: rgba(5, 150, 105, 0.2);
    }
    .achievement-card.theme-rose {
        --card-gradient: linear-gradient(135deg, #db2777 0%, #f59e0b 100%);
        --card-glow: rgba(219, 39, 119, 0.2);
        --card-border-hover: rgba(219, 39, 119, 0.4);
        --card-icon-gradient: linear-gradient(135deg, #db2777 0%, #f43f5e 100%);
        --card-icon-shadow: rgba(244, 63, 94, 0.35);
        --chip-bg: rgba(219, 39, 119, 0.08);
        --chip-color: #be185d;
        --chip-border: rgba(219, 39, 119, 0.2);
    }

    .achievement-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.15rem;
    }
    .achievement-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: var(--card-icon-gradient);
        box-shadow: 0 10px 22px -6px var(--card-icon-shadow);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        flex-shrink: 0;
    }
    .achievement-card:hover .achievement-icon-wrap {
        transform: scale(1.08) rotate(-4deg);
    }
    .achievement-icon-wrap svg {
        width: 26px;
        height: 26px;
        stroke: currentColor;
    }
    .achievement-year-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.03em;
        padding: 0.32rem 0.8rem;
        border-radius: 50px;
        background: rgba(15, 23, 42, 0.04);
        color: var(--text-primary);
        border: 1px solid rgba(15, 23, 42, 0.08);
    }
    .achievement-year-badge .year-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        box-shadow: 0 0 8px currentColor;
    }

    .achievement-highlight-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        background: var(--chip-bg);
        color: var(--chip-color);
        border: 1px solid var(--chip-border);
        font-size: 0.78rem;
        font-weight: 700;
        margin-bottom: 0.85rem;
        width: fit-content;
    }
    .achievement-highlight-chip i {
        font-size: 0.72rem;
    }

    .achievement-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.4;
        margin-bottom: 0.65rem;
        letter-spacing: -0.015em;
        transition: color 0.25s ease;
    }
    .achievement-card:hover .achievement-title {
        color: var(--accent);
    }
    .achievement-desc {
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.65;
        flex: 1;
        margin-bottom: 1.25rem;
    }

    .achievement-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(226, 232, 240, 0.7);
        margin-top: auto;
    }
    .achievement-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.22rem 0.55rem;
        border-radius: 6px;
        background: var(--bg-secondary);
        color: var(--text-muted);
        border: 1px solid rgba(226, 232, 240, 0.6);
        transition: all 0.2s ease;
    }
    .achievement-tag i {
        color: var(--chip-color);
        font-size: 0.65rem;
    }
    .achievement-card:hover .achievement-tag {
        background: #ffffff;
        color: var(--text-secondary);
        border-color: var(--chip-border);
    }

    /* ===== SKILLS ===== */
    .skills-section { background: var(--bg-secondary); }
    .skills-categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 340px), 1fr));
        gap: 1.5rem;
    }
    .skill-category-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }
    .skill-category-card:hover {
        border-color: rgba(99, 102, 241, 0.35);
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.05);
    }
    .skill-category-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--accent-light);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border);
    }
    .skill-items-list {
        display: flex;
        flex-direction: column;
        gap: 0.9rem;
    }
    .skill-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .skill-item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .skill-item-header span i {
        margin-right: 0.45rem;
        color: var(--accent);
    }
    .skill-item-details {
        font-size: 0.8rem;
        color: var(--text-secondary);
        line-height: 1.45;
        background: var(--bg-secondary);
        padding: 0.3rem 0.6rem;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    /* ===== EXPERIENCE TIMELINE ===== */
    .timeline {
        position: relative;
        padding-left: 3.75rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 20.5px;
        top: 24px;
        bottom: 24px;
        width: 3px;
        background: linear-gradient(180deg, var(--accent) 0%, #8b5cf6 45%, #06b6d4 85%, rgba(6, 182, 212, 0) 100%);
        border-radius: 99px;
        box-shadow: 0 0 12px rgba(99, 102, 241, 0.4);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 2.25rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-marker {
        position: absolute;
        left: -3.75rem;
        top: 1.25rem;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
    }
    .timeline-marker-inner {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(6, 182, 212, 0.1)), var(--bg-card);
        border: 2px solid var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 0.95rem;
        box-shadow: 0 0 16px rgba(99, 102, 241, 0.25), inset 0 0 10px rgba(99, 102, 241, 0.12);
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        z-index: 2;
    }
    .timeline-marker-pulse {
        position: absolute;
        inset: -5px;
        border-radius: 50%;
        border: 2px solid var(--accent);
        opacity: 0.8;
        animation: timelinePulse 2.4s cubic-bezier(0.24, 0, 0.38, 1) infinite;
        pointer-events: none;
        z-index: 1;
    }
    .timeline-item:hover .timeline-marker-inner {
        background: linear-gradient(135deg, var(--accent), #06b6d4);
        border-color: #06b6d4;
        color: #ffffff;
        transform: scale(1.15) rotate(10deg);
        box-shadow: 0 0 24px rgba(99, 102, 241, 0.65), 0 0 12px rgba(6, 182, 212, 0.45);
    }
    .timeline-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 18px -2px rgba(0, 0, 0, 0.04);
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }
    .timeline-card::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 1.75rem;
        width: 14px;
        height: 14px;
        background: var(--bg-card);
        border-left: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        transform: rotate(45deg);
        transition: border-color 0.35s ease, background 0.35s ease;
        z-index: 1;
    }
    .timeline-item:hover .timeline-card {
        border-color: rgba(99, 102, 241, 0.4);
        transform: translateY(-3px) translateX(6px);
        box-shadow: 0 16px 36px -6px rgba(99, 102, 241, 0.14);
    }
    .timeline-item:hover .timeline-card::before {
        border-color: rgba(99, 102, 241, 0.4);
    }
    .timeline-header {
        display: flex; align-items: flex-start; justify-content: space-between;
        flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.65rem;
    }
    .timeline-date-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.3rem 0.75rem; border-radius: 50px;
        background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.25);
        color: var(--accent); font-size: 0.78rem; font-weight: 700;
    }
    .timeline-company {
        font-size: 1.15rem; font-weight: 800; color: var(--text-primary);
        display: flex; align-items: center; gap: 0.5rem;
    }
    .timeline-company i { color: var(--accent); font-size: 0.95rem; }
    .timeline-position-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-size: 0.9rem; font-weight: 600; color: var(--accent-light);
        margin-bottom: 0.85rem;
    }
    .timeline-desc {
        color: var(--text-secondary); font-size: 0.92rem; line-height: 1.65;
        margin-bottom: 1rem;
    }
    .timeline-bullets {
        list-style: none;
        padding-left: 0;
        margin: 0.5rem 0 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }
    .timeline-bullets li {
        position: relative;
        padding-left: 1.25rem;
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
    }
    .timeline-bullets li::before {
        content: '•';
        position: absolute;
        left: 0.25rem;
        top: 0;
        color: var(--accent);
        font-weight: 900;
        font-size: 1.15rem;
    }
    .timeline-tags { display: flex; flex-wrap: wrap; gap: 0.45rem; }
    .timeline-tag {
        font-size: 0.78rem; font-weight: 600; padding: 0.25rem 0.65rem;
        background: var(--bg-secondary); border: 1px solid var(--border);
        border-radius: 6px; color: var(--text-secondary);
        transition: all 0.2s ease;
    }
    .timeline-card:hover .timeline-tag {
        border-color: rgba(99, 102, 241, 0.25); color: var(--accent);
    }

    /* ===== PROJECTS ===== */
    .projects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr)); gap: 1.5rem; }
    .project-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        display: flex; flex-direction: column;
    }
    .project-card:hover {
        border-color: rgba(99,102,241,0.4);
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(99,102,241,0.12), 0 4px 15px rgba(0,0,0,0.04);
    }
    .project-image {
        height: 200px; overflow: hidden; position: relative;
        background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(6,182,212,0.2));
        display: flex; align-items: center; justify-content: center;
    }
    .project-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    .project-card:hover .project-image img { transform: scale(1.08); }
    .project-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .project-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.75rem; }
    .project-title { font-size: 1.15rem; font-weight: 800; margin-bottom: 0.5rem; }
    .project-desc { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6; flex: 1; margin-bottom: 1.25rem; }
    .project-links { display: flex; gap: 0.5rem; margin-top: auto; flex-wrap: wrap; }
    .project-links .btn { flex: 1 1 calc(33.333% - 0.5rem); min-width: 80px; justify-content: center; font-size: 0.85rem; padding: 0.5rem 0.75rem; }

    /* ===== BLOG ===== */
    .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 280px), 1fr)); gap: 1.5rem; }
    .blog-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden; text-decoration: none; color: inherit;
        display: flex; flex-direction: column;
        transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .blog-card:hover { border-color: rgba(99,102,241,0.4); transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.06); }
    .blog-image { height: 180px; overflow: hidden; background: linear-gradient(135deg, rgba(124,58,237,0.15), rgba(6,182,212,0.15)); }
    .blog-image img { width: 100%; height: 100%; object-fit: cover; }
    .blog-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .blog-date { color: var(--accent-light); font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem; }
    .blog-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.5rem; }
    .blog-excerpt { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6; }

    /* ===== CTA ===== */
    .cta-section {
        padding: 5rem 0; text-align: center;
        background: radial-gradient(ellipse 80% 60% at 50% 50%, rgba(124,58,237,0.2) 0%, transparent 70%);
        border-top: 1px solid var(--border);
    }
    .cta-section h2 { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 900; margin-bottom: 1rem; }
    .cta-section p { color: var(--text-secondary); font-size: 1.1rem; max-width: 500px; margin: 0 auto 2rem; }

    @media (max-width: 768px) {
        .hero { padding: 3.5rem 0 2rem; }
        .timeline { padding-left: 3rem; }
        .timeline::before { left: 16px; }
        .timeline-marker { left: -3rem; width: 34px; height: 34px; }
        .timeline-marker-inner { width: 34px; height: 34px; font-size: 0.8rem; }
        .timeline-card::before { left: -6px; width: 10px; height: 10px; top: 1.6rem; }
    }

    @media (max-width: 480px) {
        .hero-actions { flex-direction: column; width: 100%; gap: 0.75rem; }
        .hero-actions .btn { width: 100%; justify-content: center; }
        .project-image { height: 180px; }
        .project-body { padding: 1.1rem; }
        .project-links .btn { min-width: 70px; flex: 1; }
        .cta-section .btn { width: 100%; justify-content: center; }
    }

    /* ===== HOME CREDENTIALS SHOWCASE ===== */
    .home-cert-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 320px), 1fr));
        gap: 1.75rem;
    }
    .home-cert-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 1.6rem;
        display: flex;
        flex-direction: column;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    .home-cert-card:hover {
        transform: translateY(-6px);
        border-color: rgba(124, 58, 237, 0.35);
        box-shadow: 0 16px 36px -8px var(--accent-glow);
    }
    .home-cert-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.1rem;
    }
    .home-cert-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        border: 1px solid var(--border);
    }
    .home-cert-cat {
        font-size: 0.76rem;
        font-weight: 700;
        padding: 0.3rem 0.65rem;
        border-radius: 50px;
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        color: var(--accent-light);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .home-cert-title {
        font-size: 1.15rem;
        font-weight: 800;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }
    .home-cert-meta {
        font-size: 0.82rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.85rem;
    }
    .home-cert-meta i { color: var(--accent-light); }
    .home-cert-desc {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.4rem;
        flex: 1;
    }
    .home-cert-footer {
        padding-top: 1rem;
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
    .home-cert-size {
        font-size: 0.78rem;
        color: var(--text-muted);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
</style>
@endsection

@section('content')

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-glow-blob blob-1"></div>
    <div class="hero-glow-blob blob-2"></div>
    <div class="hero-grid"></div>
    <div class="container">
        <div class="hero-content">
            <a href="{{ route('contact') }}" class="hero-badge">
                <span class="dot"></span>
                {{ __('Available for opportunities') }}
            </a>
            
            <h1>
                <span class="hero-line hero-line-1">{{ __("Programmers don't just") }} <span class="gradient-text">{{ __('write code,') }}</span></span>
                <span class="hero-line hero-line-2">{!! __('they create solutions.') !!}</span>
            </h1>
            <p>{{ __('Aspiring Fullstack Developer with hands-on experience in building web applications through academic and personal projects. Skilled in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs, with a solid foundation in database design and software development.') }}</p>
            
            <div class="hero-actions">
                <a href="{{ route('projects.index') }}" class="btn btn-primary">
                    <i class="fas fa-rocket"></i> {{ __('View My Work') }}
                </a>
                <a href="{{ route('certificates') }}" class="btn btn-outline" style="border-color: rgba(124,58,237,0.45); background: rgba(124,58,237,0.06)">
                    <i class="fas fa-file-pdf" style="color:#ef4444"></i> {{ __('Brochure & Giấy khen') }}
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline">
                    <i class="fas fa-paper-plane"></i> {{ __('Get In Touch') }}
                </a>
                <a href="tel:0375227764" class="btn btn-outline" title="{{ __('Phone') }}">
                    <i class="fas fa-phone-alt"></i> 0375227764
                </a>
            </div>


        </div>
    </div>
    <div class="hero-scroll">
        <span>{{ __('Scroll') }}</span>
        <div class="scroll-line"></div>
    </div>
</section>

<!-- ===== KEY ACHIEVEMENTS ===== -->
<section class="section" style="padding-top: 3.5rem">
    <div class="container">
        <h2 class="section-title reveal">{!! __('heading_achievements') !!}</h2>
        <p class="section-subtitle reveal delay-1">{{ __('Academic recognition and competition awards') }}</p>

        <div class="achievements-grid reveal delay-2">
            <!-- 1. GPA 4.0 -->
            <div class="achievement-card theme-blue">
                <div class="achievement-top">
                    <div class="achievement-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <div class="achievement-top-meta">
                        <span class="achievement-year-badge"><span class="year-dot"></span>2026</span>
                    </div>
                </div>
                <div class="achievement-highlight-chip">
                    <i class="fas fa-star"></i>
                    <span>{{ app()->getLocale() == 'vi' ? 'Học kỳ gần nhất • GPA 4.0/4.0' : 'Latest Semester • GPA 4.0/4.0' }}</span>
                </div>
                <h3 class="achievement-title">{{ __('GPA in Most Recent Semester — 4.0/4.0') }}</h3>
                <p class="achievement-desc">
                    {{ app()->getLocale() == 'vi' ? 'Đạt kết quả học tập xuất sắc với điểm GPA 4.0/4.0 trong học kỳ gần nhất, thể hiện tính kỷ luật cao và tư duy logic chuyên sâu.' : 'Achieved a perfect 4.0/4.0 GPA in the most recent semester, demonstrating high dedication, discipline, and strong software engineering competence.' }}
                </p>
                <div class="achievement-tags">
                    <span class="achievement-tag"><i class="fas fa-check-circle"></i> {{ app()->getLocale() == 'vi' ? 'GPA tuyệt đối 4.0' : 'Perfect 4.0 GPA' }}</span>
                    <span class="achievement-tag"><i class="fas fa-check-circle"></i> {{ app()->getLocale() == 'vi' ? 'Kỷ luật cao' : 'High Discipline' }}</span>
                    <span class="achievement-tag"><i class="fas fa-check-circle"></i> {{ app()->getLocale() == 'vi' ? 'Tư duy logic' : 'Logical Thinking' }}</span>
                </div>
            </div>

            <!-- 2. Excellent Graduation -->
            <div class="achievement-card theme-emerald">
                <div class="achievement-top">
                    <div class="achievement-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                    </div>
                    <div class="achievement-top-meta">
                        <span class="achievement-year-badge"><span class="year-dot"></span>2026</span>
                    </div>
                </div>
                <div class="achievement-highlight-chip">
                    <i class="fas fa-graduation-cap"></i>
                    <span>{{ app()->getLocale() == 'vi' ? 'Tốt nghiệp Xuất sắc • UTH' : 'Summa Cum Laude • UTH' }}</span>
                </div>
                <h3 class="achievement-title">{{ __('Bachelor’s Degree – Excellent Graduation Classification') }}</h3>
                <p class="achievement-desc">
                    {{ app()->getLocale() == 'vi' ? 'Bằng Cử nhân Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH) với xếp loại Tốt nghiệp Xuất sắc.' : "Bachelor's Degree in Information Technology from University of Transport Ho Chi Minh City with Excellent Graduation Classification." }}
                </p>
                <div class="achievement-tags">
                    <span class="achievement-tag"><i class="fas fa-check-circle"></i> {{ app()->getLocale() == 'vi' ? 'Loại Xuất sắc' : 'Summa Cum Laude' }}</span>
                    <span class="achievement-tag"><i class="fas fa-check-circle"></i> {{ app()->getLocale() == 'vi' ? 'ĐH GTVT TP.HCM' : 'UTH University' }}</span>
                    <span class="achievement-tag"><i class="fas fa-check-circle"></i> {{ app()->getLocale() == 'vi' ? 'Cử nhân CNTT' : 'IT Engineer' }}</span>
                </div>
            </div>

            <!-- 3. Logo Design Award -->
            <div class="achievement-card theme-rose">
                <div class="achievement-top">
                    <div class="achievement-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="13.5" cy="6.5" r=".8" fill="currentColor"></circle>
                            <circle cx="17.5" cy="10.5" r=".8" fill="currentColor"></circle>
                            <circle cx="8.5" cy="7.5" r=".8" fill="currentColor"></circle>
                            <circle cx="6.5" cy="12.5" r=".8" fill="currentColor"></circle>
                            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.563-2.512 5.563-5.563C22 6.5 17.5 2 12 2z"></path>
                        </svg>
                    </div>
                    <div class="achievement-top-meta">
                        <span class="achievement-year-badge"><span class="year-dot"></span>2025</span>
                    </div>
                </div>
                <div class="achievement-highlight-chip">
                    <i class="fas fa-trophy"></i>
                    <span>{{ app()->getLocale() == 'vi' ? 'Giải Nhì • Thiết kế Cấp Viện' : 'Second Prize • Logo Design' }}</span>
                </div>
                <h3 class="achievement-title">{{ __('Second prize in the institute/faculty logo design competition') }}</h3>
                <p class="achievement-desc">
                    {{ __('Designed a competition-winning logo concept, demonstrating strong creativity, visual communication, and design thinking.') }}
                </p>
                <div class="achievement-tags">
                    <span class="achievement-tag"><i class="fas fa-check-circle"></i> {{ app()->getLocale() == 'vi' ? 'Giải Nhì cấp Viện' : '2nd Prize Winner' }}</span>
                    <span class="achievement-tag"><i class="fas fa-check-circle"></i> {{ app()->getLocale() == 'vi' ? 'Design Thinking' : 'Design Thinking' }}</span>
                    <span class="achievement-tag"><i class="fas fa-check-circle"></i> {{ app()->getLocale() == 'vi' ? 'Truyền thông thị giác' : 'Visual Aesthetics' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== SKILLS ===== -->
@if($skills->count())
<section class="section skills-section">
    <div class="container">
        <h2 class="section-title reveal">{!! __('heading_my_skills') !!}</h2>
        <p class="section-subtitle reveal delay-1">{{ __('Technologies and tools I work with every day') }}</p>

        <div class="skills-categories-grid reveal delay-2">
            @foreach($skills as $category => $categorySkills)
            <div class="skill-category-card">
                <div class="skill-category-title">
                    <i class="fas fa-layer-group"></i> {{ $category }}
                </div>
                <div class="skill-items-list">
                    @foreach($categorySkills as $skill)
                    <div class="skill-item">
                        <div class="skill-item-header">
                            <span>
                                <i class="{{ $skill->icon ?: 'fas fa-check-circle' }}"></i>
                                {{ $skill->name }}
                            </span>
                        </div>
                        @if($skill->details)
                        <div class="skill-item-details">
                            {{ $skill->details }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===== EXPERIENCE ===== -->
@if($experiences->count())
<section class="section">
    <div class="container">
        <h2 class="section-title reveal">{!! __('heading_experience') !!}</h2>
        <p class="section-subtitle reveal delay-1">{{ __('My professional journey so far') }}</p>

        <div style="max-width:760px; margin:0 auto">
            <div class="timeline">
                @foreach($experiences as $exp)
                <div class="timeline-item reveal delay-{{ ($loop->index % 3) + 1 }}">
                    <div class="timeline-marker">
                        <div class="timeline-marker-inner">
                            @if($loop->first)
                                <i class="fas fa-briefcase"></i>
                            @else
                                <i class="fas fa-laptop-code"></i>
                            @endif
                        </div>
                        @if($loop->first)
                            <span class="timeline-marker-pulse"></span>
                        @endif
                    </div>
                    <div class="timeline-card">
                        <div class="timeline-header">
                            <h3 class="timeline-company">
                                <i class="fas fa-building"></i>
                                {{ $exp->localized_company }}
                            </h3>
                            <span class="timeline-date-badge">
                                <i class="far fa-calendar-alt"></i>
                                {{ $exp->formatted_date }}
                            </span>
                        </div>
                        <div class="timeline-position-badge">
                            <i class="fas fa-code-branch"></i>
                            {{ $exp->localized_position }}
                        </div>
                        <div class="timeline-desc">
                            @php
                                $sentences = array_filter(array_map('trim', explode('.', $exp->localized_description)));
                            @endphp
                            @if(count($sentences) > 1)
                                <ul class="timeline-bullets">
                                    @foreach($sentences as $s)
                                        @if(!empty($s))
                                        <li>{{ $s }}.</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p>{{ $exp->localized_description }}</p>
                            @endif
                        </div>
                        @if(count($exp->tech_tags))
                        <div class="timeline-tags">
                            @foreach($exp->tech_tags as $tag)
                            <span class="timeline-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- ===== FEATURED PROJECTS ===== -->
@if($featuredProjects->count())
<section class="section" style="background: var(--bg-secondary)">
    <div class="container">
        <h2 class="section-title reveal">{!! __('heading_featured_projects') !!}</h2>
        <p class="section-subtitle reveal delay-1">{{ __('Some of my recent and favourite work') }}</p>

        <div class="projects-grid">
            @foreach($featuredProjects as $project)
            <div class="project-card reveal delay-{{ ($loop->index % 3) + 1 }}">
                <div class="project-image">
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/og-image.webp') }}';">
                    @else
                        <img src="{{ asset('images/og-image.webp') }}" alt="{{ $project->title }} - Thumbnail" loading="lazy" decoding="async">
                    @endif
                </div>
                <div class="project-body">
                    <div class="project-tags">
                        @foreach($project->technologies->take(5) as $tech)
                            <span class="tag">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                    <div class="project-title">{{ $project->title }}</div>
                    <p class="project-desc">{{ Str::limit(strip_tags($project->description), 130) }}</p>
                    <div class="project-links">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> {{ __('View') }}
                        </a>
                        @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                            <i class="fab fa-github"></i> {{ __('Code') }}
                        </a>
                        @endif
                        @if($project->demo_url)
                        <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                            <i class="fas fa-external-link-alt"></i> {{ __('Demo') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center; margin-top:2.5rem" class="reveal delay-2">
            <a href="{{ route('projects.index') }}" class="btn btn-outline">
                <i class="fas fa-th-large"></i> {{ __('View All Projects') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- ===== BROCHURE & CREDENTIALS SHOWCASE ===== -->
@if(isset($certificates) && $certificates->count())
<section class="section">
    <div class="container">
        <h2 class="section-title reveal">{!! __('heading_credentials') !!}</h2>
        <p class="section-subtitle reveal delay-1">{{ __('credentials_subtitle') }}</p>

        <div class="home-cert-grid">
            @foreach($certificates->take(3) as $cert)
            <div class="home-cert-card reveal delay-{{ ($loop->index % 3) + 1 }}">
                <div class="home-cert-top">
                    <div class="home-cert-icon" style="color: {{ $cert->is_pdf ? '#ef4444' : '#06b6d4' }}; background: {{ $cert->is_pdf ? 'rgba(239,68,68,0.1)' : 'rgba(6,182,212,0.1)' }}">
                        <i class="fas {{ $cert->is_pdf ? 'fa-file-pdf' : 'fa-file-image' }}"></i>
                    </div>
                    <span class="home-cert-cat">
                        <i class="{{ $cert->category_icon }}"></i> {{ $cert->category_label }}
                    </span>
                </div>
                <h3 class="home-cert-title">{{ $cert->title }}</h3>
                <div class="home-cert-meta">
                    @if($cert->issuer)
                        <span><i class="fas fa-building"></i> {{ $cert->issuer }}</span>
                    @endif
                    @if($cert->issue_date)
                        <span>&bull;</span>
                        <span><i class="fas fa-calendar-alt"></i> {{ $cert->issue_date }}</span>
                    @endif
                </div>
                <p class="home-cert-desc">{{ Str::limit($cert->description, 130) }}</p>
                <div class="home-cert-footer">
                    <span class="home-cert-size">
                        <i class="fas fa-file"></i> {{ strtoupper($cert->file_type) }} ({{ $cert->file_size ?? 'N/A' }})
                    </span>
                    <div style="display:flex; gap:0.4rem">
                        <a href="{{ $cert->file_url }}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> {{ __('View') }}
                        </a>
                        <a href="{{ $cert->file_url }}" download class="btn btn-outline btn-sm" title="{{ __('Download') }}">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center; margin-top:2.5rem" class="reveal delay-2">
            <a href="{{ route('certificates') }}" class="btn btn-outline">
                <i class="fas fa-folder-open"></i> {{ __('All Documents') }} ({{ \App\Models\Certificate::count() }})
            </a>
        </div>
    </div>
</section>
@endif

<!-- ===== BLOG ===== -->
@if($recentPosts->count())
<section class="section">
    <div class="container">
        <h2 class="section-title reveal">{!! __('heading_recent_articles') !!}</h2>
        <p class="section-subtitle reveal delay-1">{{ __('Thoughts, learnings and insights from my journey') }}</p>

        <div class="blog-grid">
            @foreach($recentPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="blog-card reveal delay-{{ ($loop->index % 3) + 1 }}">
                <div class="blog-image">
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/og-image.webp') }}';">
                    @endif
                </div>
                <div class="blog-body">
                    <div class="blog-date">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}
                    </div>
                    <div class="blog-title">{{ $post->title }}</div>
                    @if($post->excerpt)
                    <p class="blog-excerpt">{{ Str::limit($post->excerpt, 120) }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        <div style="text-align:center; margin-top:2.5rem" class="reveal delay-2">
            <a href="{{ route('blog.index') }}" class="btn btn-outline">
                <i class="fas fa-book-open"></i> {{ __('Read All Articles') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- ===== CTA ===== -->
<section class="cta-section">
    <div class="container reveal">
        <h2>{!! __('cta_heading') !!}</h2>
        <p>{{ __("I'm always open to interesting projects and opportunities.") }}</p>
        <a href="{{ route('contact') }}" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> {{ __('Send Me a Message') }}
        </a>
    </div>
</section>

@endsection
