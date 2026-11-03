@extends('layouts.app')

@section('title', __('About Me') . ' | ' . __('Hồ Thành Thiện'))
@section('description', __('Aspiring Fullstack Developer with hands-on experience in building web applications through academic and personal projects. Skilled in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs, with a solid foundation in database design and software development.'))
@section('keywords', app()->getLocale() == 'vi' ? 'Về Hồ Thành Thiện, Hồ Thành Thiện Full-Stack Developer, Kinh nghiệm lập trình, Kỹ năng Web Developer, UTH, Odoo Developer, RAG LLM' : 'About Ho Thanh Thien, Full-Stack Developer Experience, Web Developer Skills, Laravel Software Engineer, UTH, Odoo Developer, RAG LLM')

@section('styles')
<style>
    .about-hero {
        padding: 5rem 0 3rem;
        position: relative; overflow: hidden;
        background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,0.18) 0%, transparent 70%);
    }

    .about-intro { display: grid; grid-template-columns: auto 1fr; gap: 3rem; align-items: center; margin-bottom: 2rem; }

    .about-avatar {
        width: 190px; height: 190px; border-radius: 28px;
        background: var(--gradient);
        display: flex; align-items: center; justify-content: center;
        font-size: 4.8rem; font-weight: 900; color: white;
        box-shadow: 0 10px 40px var(--accent-glow); flex-shrink: 0;
        animation: floatAvatar 6s ease-in-out infinite alternate;
        position: relative;
    }
    .about-avatar::after {
        content: ''; position: absolute; inset: -4px; border-radius: 32px;
        border: 2px solid rgba(99, 102, 241, 0.3); pointer-events: none;
        animation: pulseRing 3s ease-in-out infinite;
    }
    @keyframes floatAvatar {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(1deg); }
        100% { transform: translateY(0) rotate(0deg); }
    }
    @keyframes pulseRing {
        0%, 100% { transform: scale(1); opacity: 0.6; }
        50% { transform: scale(1.05); opacity: 0.2; }
    }

    .about-text h1 { font-size: clamp(1.8rem, 4.5vw, 2.9rem); font-weight: 900; margin-bottom: 0.5rem; letter-spacing: -0.02em; }
    .about-text .role { color: var(--accent-light); font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
    .about-text p { color: var(--text-secondary); line-height: 1.8; font-size: 1.02rem; margin-bottom: 1.25rem; }
    
    .about-quick-meta {
        display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem;
    }
    .about-meta-pill {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.4rem 0.85rem; border-radius: 50px;
        background: var(--bg-card); border: 1px solid var(--border);
        font-size: 0.86rem; font-weight: 600; color: var(--text-primary);
        text-decoration: none; transition: all 0.25s ease;
    }
    .about-meta-pill:hover {
        border-color: var(--accent); color: var(--accent);
        transform: translateY(-2px);
    }
    .about-meta-pill i { color: var(--accent-light); font-size: 0.9rem; }

    .about-actions { display: flex; gap: 0.85rem; flex-wrap: wrap; margin-top: 1rem; }

    /* ===== KEY ACHIEVEMENTS (PRESTIGE EDITION) ===== */
    .achievements-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
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

    /* ===== EDUCATION CARD ===== */
    .education-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2.25rem;
        box-shadow: 0 8px 30px -8px rgba(0,0,0,0.06);
        position: relative;
        overflow: hidden;
        transition: all 0.35s ease;
    }
    .education-card:hover {
        border-color: rgba(99, 102, 241, 0.35);
        box-shadow: 0 16px 40px -10px rgba(99, 102, 241, 0.14);
    }
    .education-card::before {
        content: '';
        position: absolute;
        top: 0; bottom: 0; left: 0; width: 4px;
        background: var(--gradient);
    }
    .education-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .education-school {
        font-size: clamp(1.2rem, 3.5vw, 1.6rem);
        font-weight: 800;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .education-school i {
        color: var(--accent);
    }
    .education-timeline-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.9rem;
        border-radius: 50px;
        background: rgba(99, 102, 241, 0.12);
        border: 1px solid rgba(99, 102, 241, 0.25);
        color: var(--accent);
        font-size: 0.85rem;
        font-weight: 700;
    }
    .education-degree {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--accent-light);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .education-highlights {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-top: 1.25rem;
    }
    .education-highlight-item {
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .education-highlight-item i {
        font-size: 1.3rem;
        color: var(--accent);
        flex-shrink: 0;
    }
    .education-highlight-text .label {
        font-size: 0.78rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 700;
    }
    .education-highlight-text .val {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    /* ===== SKILLS CATEGORIES ===== */
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
        gap: 1rem;
    }
    .skill-item {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
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
        line-height: 1.5;
        background: var(--bg-secondary);
        padding: 0.35rem 0.65rem;
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
    @keyframes timelinePulse {
        0% { transform: scale(0.92); opacity: 0.85; }
        70% { transform: scale(1.55); opacity: 0; }
        100% { transform: scale(1.55); opacity: 0; }
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
    .timeline-company i {
        color: var(--accent); font-size: 0.95rem;
    }
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
    .timeline-tags {
        display: flex; flex-wrap: wrap; gap: 0.45rem;
    }
    .timeline-tag {
        font-size: 0.78rem; font-weight: 600; padding: 0.25rem 0.65rem;
        background: var(--bg-secondary); border: 1px solid var(--border);
        border-radius: 6px; color: var(--text-secondary);
        transition: all 0.2s ease;
    }
    .timeline-card:hover .timeline-tag {
        border-color: rgba(99, 102, 241, 0.25);
        color: var(--accent);
    }

    @media (max-width: 991px) {
        .about-intro { gap: 2rem; }
        .about-avatar { width: 160px; height: 160px; font-size: 4rem; }
    }

    @media (max-width: 1024px) {
        .achievements-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
        .achievement-card:last-child:nth-child(odd) {
            grid-column: 1 / -1;
            max-width: 580px;
            margin: 0 auto;
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .about-hero { padding: 3.5rem 0 2rem; }
        .about-intro { grid-template-columns: 1fr; gap: 1.75rem; text-align: center; }
        .about-avatar { margin: 0 auto; width: 130px; height: 130px; font-size: 3.2rem; border-radius: 22px; }
        .about-actions { justify-content: center; }
        .about-quick-meta { justify-content: center; }
        .timeline { padding-left: 2.75rem; }
        .timeline::before { left: 14px; }
        .timeline-marker { left: -2.75rem; width: 32px; height: 32px; }
        .timeline-marker-inner { width: 32px; height: 32px; font-size: 0.75rem; }
        .timeline-card::before { left: -6px; width: 10px; height: 10px; top: 1.6rem; }
    }

    @media (max-width: 600px) {
        .achievements-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }
        .achievement-card:last-child:nth-child(odd) {
            max-width: 100%;
        }
        .skills-categories-grid {
            grid-template-columns: 1fr;
        }
        .education-highlights {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .about-hero { padding: 2.75rem 0 1.5rem; }
        .about-actions { flex-direction: column; width: 100%; gap: 0.75rem; }
        .about-actions .btn { width: 100%; justify-content: center; }
        .about-quick-meta { flex-direction: column; align-items: stretch; gap: 0.5rem; }
        .about-meta-pill { justify-content: center; width: 100%; }
        .education-card { padding: 1.25rem 1rem; }
        .education-school { font-size: 1.15rem; }
        .timeline { padding-left: 2.25rem; }
        .timeline::before { left: 11px; }
        .timeline-marker { left: -2.25rem; width: 26px; height: 26px; }
        .timeline-marker-inner { width: 26px; height: 26px; font-size: 0.7rem; }
        .timeline-card { padding: 1.15rem; }
    }
</style>
@endsection

@section('content')
<!-- ===== HERO & SUMMARY ===== -->
<section class="about-hero">
    <div class="container">
        <div class="about-intro">
            <div class="about-avatar reveal-fade">T</div>
            <div class="about-text reveal delay-1">
                <h1>{{ __("Hi, I'm") }} <span class="gradient-text">{{ __('Hồ Thành Thiện') }}</span></h1>
                <div class="role">⚡ {{ __('Full-stack Developer') }}</div>
                <p>{{ __('Aspiring Fullstack Developer with hands-on experience in building web applications through academic and personal projects. Skilled in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs, with a solid foundation in database design and software development.') }}</p>
                
                <div class="about-quick-meta">
                    <a href="tel:0375227764" class="about-meta-pill">
                        <i class="fas fa-phone-alt"></i> 0375227764
                    </a>
                    <a href="mailto:hothanhthien119@gmail.com" class="about-meta-pill">
                        <i class="fas fa-envelope"></i> hothanhthien119@gmail.com
                    </a>
                    <a href="https://hothanhthien.io.vn/" target="_blank" rel="noopener" class="about-meta-pill">
                        <i class="fas fa-globe"></i> hothanhthien.io.vn
                    </a>
                    <span class="about-meta-pill">
                        <i class="fas fa-location-dot"></i> HCM
                    </span>
                </div>

                <div class="about-actions">
                    <a href="{{ route('projects.index') }}" class="btn btn-primary">
                        <i class="fas fa-rocket"></i> {{ __('See My Work') }}
                    </a>
                    <a href="{{ route('certificates') }}" class="btn btn-outline" style="border-color: rgba(124,58,237,0.45); background: rgba(124,58,237,0.06)">
                        <i class="fas fa-file-pdf" style="color:#ef4444"></i> {{ __('Brochure & Giấy khen') }}
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline">
                        <i class="fas fa-envelope"></i> {{ __('Get In Touch') }}
                    </a>
                    <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="btn btn-outline">
                        <i class="fab fa-github"></i> GitHub
                    </a>
                    <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="btn btn-outline">
                        <i class="fab fa-linkedin"></i> LinkedIn
                    </a>
                </div>
            </div>
        </div>
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

<!-- ===== EDUCATION ===== -->
<section class="section" style="background: var(--bg-secondary); padding-top: 3.5rem">
    <div class="container">
        <h2 class="section-title reveal">{!! __('heading_education') !!}</h2>
        <p class="section-subtitle reveal delay-1">{{ __('Formal academic background & verified credentials') }}</p>

        <div style="max-width: 860px; margin: 0 auto" class="reveal delay-2">
            <div class="education-card">
                <div class="education-header">
                    <div>
                        <h3 class="education-school">
                            <i class="fas fa-university"></i>
                            {{ __('University of Transport Ho Chi Minh City') }}
                        </h3>
                        <div class="education-degree">
                            <i class="fas fa-user-graduate"></i>
                            {{ __("Bachelor's Degree Information Technology") }}
                        </div>
                    </div>
                    <span class="education-timeline-badge">
                        <i class="far fa-calendar-alt"></i> 2023 - 2026
                    </span>
                </div>

                <div class="education-highlights">
                    <div class="education-highlight-item">
                        <i class="fas fa-chart-line"></i>
                        <div class="education-highlight-text">
                            <div class="label">{{ __('Cumulative GPA') }}</div>
                            <div class="val">3.6 / 4.0</div>
                        </div>
                    </div>

                    <div class="education-highlight-item">
                        <i class="fas fa-medal"></i>
                        <div class="education-highlight-text">
                            <div class="label">{{ __('Classification') }}</div>
                            <div class="val">{{ __('Excellent') }}</div>
                        </div>
                    </div>

                    <div class="education-highlight-item" style="grid-column: 1 / -1">
                        <i class="fab fa-google"></i>
                        <div class="education-highlight-text">
                            <div class="label">{{ __('Community & Clubs') }}</div>
                            <div class="val">{{ __('Member Google Developer Chapter University of Transport Ho Chi Minh City') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== TECHNICAL SKILLS ===== -->
@if($skills->count())
<section class="section">
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

<!-- ===== WORK EXPERIENCE ===== -->
@if($experiences->count())
<section class="section" style="background: var(--bg-secondary)">
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
@endsection
