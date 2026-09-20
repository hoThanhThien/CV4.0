@extends('layouts.app')

@section('title', __('About Me') . ' | ' . __('Hồ Thành Thiện'))
@section('description', __('I\'m a passionate developer with a love for building clean, performant, and user-friendly web applications. I bridge the gap between design and functionality to create digital experiences that matter.'))
@section('keywords', 'Về Hồ Thành Thiện, Hồ Thành Thiện Full-Stack Developer, Kinh nghiệm lập trình, Kỹ năng Web Developer')

@section('styles')
<style>
    .about-hero {
        padding: 5rem 0 3rem;
        position: relative; overflow: hidden;
        background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,0.18) 0%, transparent 70%);
    }

    .about-intro { display: grid; grid-template-columns: auto 1fr; gap: 3rem; align-items: center; margin-bottom: 5rem; }

    .about-avatar {
        width: 200px; height: 200px; border-radius: 28px;
        background: var(--gradient);
        display: flex; align-items: center; justify-content: center;
        font-size: 5rem; font-weight: 900; color: white;
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

    .about-text h1 { font-size: clamp(1.8rem, 4.5vw, 3rem); font-weight: 900; margin-bottom: 0.75rem; letter-spacing: -0.02em; }
    .about-text .role { color: var(--accent-light); font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; }
    .about-text p { color: var(--text-secondary); line-height: 1.8; font-size: 1rem; margin-bottom: 1rem; }
    .about-actions { display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1.5rem; }

    .skills-section { background: var(--bg-secondary); }
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

    .skill-category-title { font-size: 1rem; font-weight: 700; color: var(--accent-light); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
    .skill-category-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    .timeline { position: relative; padding-left: 2rem; }
    .timeline::before {
        content: ''; position: absolute; left: 8px; top: 1.5rem; bottom: 1.5rem;
        width: 2px; background: linear-gradient(to bottom, var(--accent) 0%, rgba(99, 102, 241, 0.2) 100%);
    }

    .timeline-item { position: relative; margin-bottom: 2rem; }
    .timeline-item::before {
        content: ''; position: absolute; left: -2rem; top: 1.5rem;
        width: 18px; height: 18px; border-radius: 50%;
        background: var(--bg-primary); border: 4px solid var(--accent);
        box-shadow: 0 0 12px var(--accent-glow);
        z-index: 2; transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .timeline-item:hover::before {
        background: var(--accent);
        transform: scale(1.2);
        box-shadow: 0 0 18px var(--accent);
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
    .timeline-card:hover {
        border-color: rgba(99, 102, 241, 0.4);
        transform: translateY(-3px) translateX(4px);
        box-shadow: 0 16px 32px -5px rgba(99, 102, 241, 0.12);
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

    @media (max-width: 768px) {
        .about-hero { padding: 3.5rem 0 2rem; }
        .about-intro { grid-template-columns: 1fr; gap: 2rem; text-align: center; margin-bottom: 3rem; }
        .about-avatar { margin: 0 auto; width: 140px; height: 140px; font-size: 3.5rem; border-radius: 24px; }
        .about-actions { justify-content: center; }
    }

    @media (max-width: 480px) {
        .about-hero { padding: 2.75rem 0 1.5rem; }
        .about-actions { flex-direction: column; width: 100%; gap: 0.75rem; }
        .about-actions .btn { width: 100%; justify-content: center; }
        .skills-pill-group { gap: 0.5rem; }
        .skill-pill { padding: 0.4rem 0.85rem; font-size: 0.82rem; }
    }
</style>
@endsection

@section('content')
<section class="about-hero">
    <div class="container">
        <div class="about-intro">
            <div class="about-avatar reveal-fade">T</div>
            <div class="about-text reveal delay-1">
                <h1>{{ __("Hi, I'm") }} <span class="gradient-text">{{ __('Hồ Thành Thiện') }}</span></h1>
                <div class="role">⚡ {{ __('Full-Stack Developer & Problem Solver') }}</div>
                <p>{{ __("I'm a passionate developer with a love for building clean, performant, and user-friendly web applications. I bridge the gap between design and functionality to create digital experiences that matter.") }}</p>
                <p>{{ __("When I'm not coding, you'll find me exploring new technologies, contributing to open source, or brewing the perfect cup of coffee ☕") }}</p>
                <div class="about-actions">
                    <a href="{{ route('projects.index') }}" class="btn btn-primary">
                        <i class="fas fa-rocket"></i> {{ __('See My Work') }}
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline">
                        <i class="fas fa-envelope"></i> {{ __('Get In Touch') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@if($skills->count())
<section class="section skills-section">
    <div class="container">
        <h2 class="section-title reveal">{!! __('heading_my_skills') !!}</h2>
        <p class="section-subtitle reveal delay-1">{{ __('Technologies I work with') }}</p>

        <div style="display:flex; flex-direction:column; gap:3rem" class="reveal delay-2">
            @foreach($skills as $category => $categorySkills)
            <div>
                <div class="skill-category-title"><i class="fas fa-layer-group"></i> {{ $category }}</div>
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

@if($experiences->count())
<section class="section">
    <div class="container">
        <h2 class="section-title reveal">{!! __('heading_experience') !!}</h2>
        <p class="section-subtitle reveal delay-1">{{ __('My professional journey') }}</p>
        <div style="max-width:760px; margin:0 auto">
            <div class="timeline">
                @foreach($experiences as $exp)
                <div class="timeline-item reveal delay-{{ ($loop->index % 3) + 1 }}">
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
@endsection


