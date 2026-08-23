@extends('layouts.app')

@section('title', __('Hồ Thành Thiện') . ' | Full-Stack Developer')
@section('description', 'Full-Stack Developer specializing in modern web technologies. Building fast, scalable and beautiful applications.')

@section('styles')
<style>
    /* ===== HERO ===== */
    .hero {
        min-height: 100vh; display: flex; align-items: center;
        position: relative; overflow: hidden;
    }

    .hero-bg {
        position: absolute; inset: 0; z-index: 0;
        background: radial-gradient(ellipse 80% 80% at 50% -20%, rgba(124,58,237,0.25) 0%, transparent 60%),
                    radial-gradient(ellipse 60% 60% at 80% 50%, rgba(6,182,212,0.1) 0%, transparent 60%);
    }

    .hero-grid {
        position: absolute; inset: 0; z-index: -1;
        background-size: 40px 40px;
        background-image: linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);
        mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);
    }

    .hero-content { position: relative; z-index: 1; max-width: 700px; }

    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.25rem 0.85rem; border-radius: 50px;
        background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3);
        color: var(--accent-light); font-size: 0.85rem; font-weight: 600;
        margin-bottom: 1rem; animation: fadeInDown 0.6s ease;
        text-decoration: none; transition: background 0.3s;
    }
    .hero-badge:hover {
        background: rgba(124,58,237,0.25);
    }

    .hero-badge .dot {
        width: 8px; height: 8px; border-radius: 50%; background: var(--green);
        box-shadow: 0 0 8px var(--green); animation: pulse 2s infinite;
    }

    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

    .hero h1 {
        font-size: clamp(2rem, 5vw, 3.8rem); font-weight: 900;
        line-height: 1.1; margin-bottom: 1rem;
        animation: fadeInUp 0.7s ease 0.1s both;
    }

    .hero p {
        font-size: 1.1rem; color: var(--text-secondary);
        margin-bottom: 1.5rem; max-width: 560px; line-height: 1.6;
        animation: fadeInUp 0.7s ease 0.2s both;
    }

    .hero-actions {
        display: flex; gap: 1rem; flex-wrap: wrap;
        animation: fadeInUp 0.7s ease 0.3s both;
    }

    .hero-scroll {
        position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%);
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

    .skills-categories { display: flex; flex-direction: column; gap: 3rem; }
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
        transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.4rem;
    }
    .skill-pill:hover { border-color: var(--accent); color: var(--accent); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(99,102,241,0.1); }


    /* ===== EXPERIENCE ===== */
    .timeline { position: relative; padding-left: 2rem; }
    .timeline::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 2px; background: linear-gradient(to bottom, var(--accent), transparent);
    }

    .timeline-item { position: relative; margin-bottom: 2.5rem; }
    .timeline-item::before {
        content: ''; position: absolute; left: -2.45rem; top: 0.4rem;
        width: 12px; height: 12px; border-radius: 50%;
        background: var(--accent); border: 2px solid var(--bg-primary);
        box-shadow: 0 0 12px var(--accent-glow);
    }

    .timeline-date {
        font-size: 0.78rem; font-weight: 700; color: var(--accent-light);
        text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem;
    }
    .timeline-company { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.15rem; }
    .timeline-position { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.75rem; }
    .timeline-desc { color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6; }

    /* ===== PROJECTS ===== */
    .projects-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }

    .project-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden;
        transition: all 0.3s ease; display: flex; flex-direction: column;
    }
    .project-card:hover { border-color: rgba(124,58,237,0.4); transform: translateY(-6px); box-shadow: 0 25px 60px rgba(0,0,0,0.5); }

    .project-image {
        height: 200px; overflow: hidden; position: relative;
        background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(6,182,212,0.2));
        display: flex; align-items: center; justify-content: center;
    }
    .project-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .project-card:hover .project-image img { transform: scale(1.05); }
    .project-image-placeholder { font-size: 3rem; opacity: 0.4; }

    .project-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .project-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 0.75rem; }
    .project-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.5rem; }
    .project-desc { color: var(--text-secondary); font-size: 0.88rem; line-height: 1.6; flex: 1; margin-bottom: 1rem; }
    .project-links { display: flex; gap: 0.5rem; margin-top: auto; flex-wrap: wrap; }
    .project-links .btn { flex: 1; min-width: max-content; justify-content: center; }

    /* ===== BLOG ===== */
    .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }

    .blog-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden;
        transition: all 0.3s ease; display: flex; flex-direction: column;
        text-decoration: none; color: inherit;
    }
    .blog-card:hover { border-color: rgba(124,58,237,0.4); transform: translateY(-4px); box-shadow: 0 20px 50px rgba(0,0,0,0.4); }

    .blog-image {
        height: 180px; overflow: hidden;
        background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(6,182,212,0.2));
    }
    .blog-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .blog-card:hover .blog-image img { transform: scale(1.05); }

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
</style>
@endsection

@section('content')

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
    <div class="container">
        <div class="hero-content">
            <a href="mailto:hothanhthien119@gmail.com" class="hero-badge">
                <span class="dot"></span>
                {{ __('Contact') }}
            </a>
            <h1>{{ __("Programmers don't just") }} <span class="gradient-text">{{ __('write code,') }}</span><br> <span style="white-space: nowrap;">{!! __('they create solutions.') !!}</span></h1>
            <p>{{ __("I'm Hồ Thành Thiện, a Full-Stack Developer crafting modern, scalable, and beautifully designed web applications with a passion for clean code.") }}</p>
            <div class="hero-actions">
                <a href="{{ route('projects.index') }}" class="btn btn-primary">
                    <i class="fas fa-rocket"></i> {{ __('View My Work') }}
                </a>
                <a href="mailto:hothanhthien119@gmail.com" class="btn btn-outline">
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
        <h2 class="section-title">{{ __('Technical') }} <span class="gradient-text">{{ __('Skills') }}</span></h2>
        <p class="section-subtitle">{{ __('Technologies and tools I work with every day') }}</p>

        <div class="skills-categories">
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
        <h2 class="section-title">{{ __('Work') }} <span class="gradient-text">{{ __('Experience') }}</span></h2>
        <p class="section-subtitle">{{ __('My professional journey so far') }}</p>

        <div style="max-width:700px; margin:0 auto">
            <div class="timeline">
                @foreach($experiences as $exp)
                <div class="timeline-item">
                    <div class="timeline-date">
                        {{ $exp->start_date->format('M Y') }} —
                        {{ $exp->current ? __('Present') : ($exp->end_date ? $exp->end_date->format('M Y') : '?') }}
                    </div>
                    <div class="timeline-company">{{ $exp->company }}</div>
                    <div class="timeline-position">{{ $exp->position }}</div>
                    <div class="timeline-desc">{{ $exp->description }}</div>
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
        <h2 class="section-title">{{ __('Featured') }} <span class="gradient-text">{{ __('Projects') }}</span></h2>
        <p class="section-subtitle">{{ __('Some of my recent and favourite work') }}</p>

        <div class="projects-grid">
            @foreach($featuredProjects as $project)
            <div class="project-card">
                <div class="project-image">
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}">
                    @else
                        <span class="project-image-placeholder"><i class="fas fa-code"></i></span>
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

        <div style="text-align:center; margin-top:2.5rem">
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
        <h2 class="section-title">{{ __('Recent') }} <span class="gradient-text">{{ __('Articles') }}</span></h2>
        <p class="section-subtitle">{{ __('Thoughts, learnings and insights from my journey') }}</p>

        <div class="blog-grid">
            @foreach($recentPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
                <div class="blog-image">
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
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

        <div style="text-align:center; margin-top:2.5rem">
            <a href="{{ route('blog.index') }}" class="btn btn-outline">
                <i class="fas fa-book-open"></i> {{ __('Read All Articles') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- ===== CTA ===== -->
<section class="cta-section">
    <div class="container">
        <h2>{{ __("Let's build something") }} <span class="gradient-text">{{ __('amazing') }}</span> {{ __('together') }}</h2>
        <p>{{ __("I'm always open to interesting projects and opportunities.") }}</p>
        <a href="mailto:hothanhthien119@gmail.com" class="btn btn-primary">
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
