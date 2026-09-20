@extends('layouts.app')

@section('title', __('Hồ Thành Thiện') . ' | ' . (app()->getLocale() == 'vi' ? 'Kỹ sư Full-Stack Web Developer' : 'Full-Stack Software Engineer'))
@section('description', __('I\'m Hồ Thành Thiện, a Full-Stack Developer crafting modern, scalable, and beautifully designed web applications with a passion for clean code.'))
@section('keywords', app()->getLocale() == 'vi' ? 'Hồ Thành Thiện, Kỹ sư Full-Stack, Lập trình viên Web, Laravel Developer Việt Nam, PHP Developer, JavaScript, Vue.js, React, Clean Architecture, Tuyển dụng lập trình viên' : 'Ho Thanh Thien, Full-Stack Developer, Web Developer, Laravel Software Engineer, PHP Developer Vietnam, JavaScript, Vue.js, React, Clean Code Architecture')

@section('styles')
<style>
    /* ===== HERO ===== */
    .hero {
        min-height: calc(100svh - 70px);
        min-height: 90vh;
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

    .hero-content { position: relative; z-index: 1; max-width: 700px; width: 100%; }

    .hero-line {
        display: block;
    }

    @media (min-width: 992px) {
        .hero-content {
            max-width: 1020px;
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
        font-size: clamp(2rem, 4.4vw, 3.5rem); font-weight: 900;
        line-height: 1.2; margin-bottom: 1.25rem; letter-spacing: -0.02em;
        word-break: normal; overflow-wrap: break-word;
    }

    .hero p {
        font-size: clamp(1rem, 2.5vw, 1.15rem); color: var(--text-secondary);
        margin-bottom: 1.75rem; max-width: 560px; line-height: 1.65;
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

    /* ===== SKILLS ===== */
    .skills-section { background: var(--bg-secondary); }

    .skills-categories { display: flex; flex-direction: column; gap: 2.75rem; }
    .skill-category-title {
        font-size: 1rem; font-weight: 700; color: var(--accent-light);
        margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .skill-category-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    .skills-pill-group { display: flex; flex-wrap: wrap; gap: 0.75rem; }
    .skill-pill {
        background: var(--bg-card); border: 1px solid var(--border);
        padding: 0.5rem 1rem; border-radius: 50px;
        font-size: 0.9rem; font-weight: 600;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1); display: inline-flex; align-items: center; gap: 0.45rem;
    }
    .skill-pill:hover {
        border-color: var(--accent); color: var(--accent);
        transform: translateY(-3px) scale(1.03);
        box-shadow: 0 8px 20px rgba(99,102,241,0.15);
    }
    .skill-pill i { transition: transform 0.3s ease; }
    .skill-pill:hover i { transform: scale(1.2) rotate(15deg); }


    /* ===== EXPERIENCE TIMELINE (TECH GLOW & INTERACTIVE NODES) ===== */
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

    /* Modern Node Marker */
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

    /* Radar Pulse Animation for current / first role */
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

    /* Hover Interaction on Marker */
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
    /* Arrow indicator connecting card to marker */
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

    @media (max-width: 640px) {
        .timeline { padding-left: 3rem; }
        .timeline::before { left: 16px; }
        .timeline-marker { left: -3rem; width: 34px; height: 34px; }
        .timeline-marker-inner { width: 34px; height: 34px; font-size: 0.8rem; }
        .timeline-card::before { left: -6px; width: 10px; height: 10px; top: 1.6rem; }
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
        letter-spacing: 0.02em;
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
    .project-image-placeholder { font-size: 3rem; opacity: 0.4; }

    .project-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .project-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.75rem; }
    .project-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; }
    .project-desc { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6; flex: 1; margin-bottom: 1.25rem; }
    .project-links { display: flex; gap: 0.5rem; margin-top: auto; flex-wrap: wrap; }
    .project-links .btn { flex: 1 1 calc(33.333% - 0.5rem); min-width: 80px; justify-content: center; font-size: 0.85rem; padding: 0.5rem 0.75rem; }

    /* ===== BLOG ===== */
    .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 280px), 1fr)); gap: 1.5rem; }

    .blog-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        display: flex; flex-direction: column;
        text-decoration: none; color: inherit;
    }
    .blog-card:hover {
        border-color: rgba(99,102,241,0.4);
        transform: translateY(-6px);
        box-shadow: 0 20px 45px rgba(99,102,241,0.12), 0 4px 15px rgba(0,0,0,0.04);
    }

    .blog-image {
        height: 180px; overflow: hidden;
        background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(6,182,212,0.2));
    }
    .blog-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    .blog-card:hover .blog-image img { transform: scale(1.08); }

    .blog-body { padding: 1.25rem; flex: 1; }
    .blog-date { font-size: 0.78rem; color: var(--text-secondary); margin-bottom: 0.5rem; }
    .blog-title { font-size: 1rem; font-weight: 700; margin-bottom: 0.5rem; line-height: 1.4; }
    .blog-excerpt { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6; }

    /* ===== CTA ===== */
    .cta-section {
        text-align: center; padding: 6rem 0;
        background: radial-gradient(ellipse 70% 70% at 50% 50%, rgba(124,58,237,0.1) 0%, transparent 70%);
    }
    .cta-section h2 { font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 900; margin-bottom: 1rem; }
    .cta-section p { color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 2rem; }

    /* ===== RESPONSIVE MEDIA QUERIES ===== */
    @media (max-width: 768px) {
        .hero {
            min-height: auto;
            padding: 3.5rem 0 2.5rem;
        }
        .hero-scroll { display: none; }
        .skills-categories { gap: 2rem; }
        .cta-section { padding: 3.5rem 0; }
        .blob-1, .blob-2 { opacity: 0.25; filter: blur(60px); }
    }

    @media (max-width: 640px) {
        .projects-grid, .blog-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .hero-actions {
            flex-direction: column;
            width: 100%;
            gap: 0.75rem;
        }
        .hero-actions .btn {
            width: 100%;
            justify-content: center;
        }
        .skills-pill-group { gap: 0.5rem; }
        .skill-pill { padding: 0.4rem 0.85rem; font-size: 0.82rem; }
        .project-image { height: 180px; }
        .project-body { padding: 1.1rem; }
        .project-links .btn { min-width: 70px; flex: 1; }
        .blog-image { height: 160px; }
        .blog-body { padding: 1.1rem; }
        .cta-section .btn { width: 100%; justify-content: center; }
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
                {{ __('Contact') }}
            </a>
            <h1>
                <span class="hero-line hero-line-1">{{ __("Programmers don't just") }} <span class="gradient-text">{{ __('write code,') }}</span></span>
                <span class="hero-line hero-line-2">{!! __('they create solutions.') !!}</span>
            </h1>
            <p>{{ __("I'm Hồ Thành Thiện, a Full-Stack Developer crafting modern, scalable, and beautifully designed web applications with a passion for clean code.") }}</p>
            <div class="hero-actions">
                <a href="{{ route('projects.index') }}" class="btn btn-primary">
                    <i class="fas fa-rocket"></i> {{ __('View My Work') }}
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline">
                    <i class="fas fa-paper-plane"></i> {{ __('Get In Touch') }}
                </a>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <span>{{ __('Scroll') }}</span>
        <div class="scroll-line"></div>
    </div>
</section>

<!-- ===== SKILLS ===== -->
@if($skills->count())
<section class="section skills-section">
    <div class="container">
        <h2 class="section-title reveal">Technical Skills</h2>
        <p class="section-subtitle reveal delay-1">{{ __('Technologies and tools I work with every day') }}</p>

        <div class="skills-categories reveal delay-2">
            @foreach($skills as $category => $categorySkills)
            <div>
                <div class="skill-category-title">
                    <i class="fas fa-layer-group"></i> {{ $category }}
                </div>
                <div class="skills-pill-group">
                    @foreach($categorySkills as $skill)
                    <div class="skill-pill">
                        <i class="fas fa-check-circle" style="color:var(--accent-light); font-size:0.8rem"></i>
                        {{ $skill->name }}
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
                                <i class="fas fa-laptop-code"></i>
                            @elseif($loop->iteration == 2)
                                <i class="fas fa-server"></i>
                            @else
                                <i class="fas fa-briefcase"></i>
                            @endif
                        </div>
                        @if($loop->first)
                            <span class="timeline-marker-pulse"></span>
                        @endif
                    </div>
                    <div class="timeline-card">
                        <div class="timeline-header">
                            <h3 class="timeline-company">
                                <i class="fas fa-briefcase"></i>
                                {{ $exp->company }}
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
                        <p class="timeline-desc">{{ $exp->localized_description }}</p>
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
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async">
                    @else
                        <div class="project-image-placeholder">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                    @endif
                </div>
                <div class="project-body">
                    <div class="project-tags">
                        @foreach($project->technologies->take(4) as $tech)
                            <span class="tag">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                    <div class="project-title">{{ $project->title }}</div>
                    <p class="project-desc">{{ Str::limit($project->description, 120) }}</p>
                    <div class="project-links">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> {{ __('View') }}
                        </a>
                        @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fab fa-github"></i> {{ __('Code') }}
                        </a>
                        @endif
                        @if($project->demo_url)
                        <a href="{{ $project->demo_url }}" target="_blank" class="btn btn-outline btn-sm">
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
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" loading="lazy" decoding="async">
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

@section('scripts')
<script>
    // Animate skill bars on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.querySelectorAll('.skill-fill').forEach(bar => {
                    bar.style.width = bar.dataset.level + '%';
                });
            }
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('.skills-categories > div').forEach(el => observer.observe(el));
</script>
@endsection
