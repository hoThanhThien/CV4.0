@extends('layouts.app')

@section('title', __('About Me') . ' | ' . __('Hồ Thành Thiện'))
@section('description', 'Learn more about Hồ Thành Thiện - Full-Stack Developer, his experience, skills and background.')

@section('styles')
<style>
    .about-hero {
        padding: 5rem 0 3rem;
        background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,0.15) 0%, transparent 70%);
    }

    .about-intro { display: grid; grid-template-columns: auto 1fr; gap: 3rem; align-items: center; margin-bottom: 5rem; }

    .about-avatar {
        width: 200px; height: 200px; border-radius: 24px;
        background: var(--gradient);
        display: flex; align-items: center; justify-content: center;
        font-size: 5rem; font-weight: 900; color: white;
        box-shadow: 0 0 60px var(--accent-glow); flex-shrink: 0;
    }

    .about-text h1 { font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 900; margin-bottom: 0.75rem; }
    .about-text .role { color: var(--accent-light); font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; }
    .about-text p { color: var(--text-secondary); line-height: 1.8; font-size: 1rem; margin-bottom: 1rem; }
    .about-actions { display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 1.5rem; }

    .skills-section { background: var(--bg-secondary); }
    .skills-pill-group { display: flex; flex-wrap: wrap; gap: 0.75rem; }
    .skill-pill {
        background: var(--bg-card); border: 1px solid var(--border);
        padding: 0.5rem 1rem; border-radius: 50px;
        font-size: 0.9rem; font-weight: 600;
        transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.4rem;
    }
    .skill-pill:hover { border-color: var(--accent); color: var(--accent); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(99,102,241,0.1); }
    .skill-category-title { font-size: 1rem; font-weight: 700; color: var(--accent-light); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
    .skill-category-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    .timeline { position: relative; padding-left: 2rem; }
    .timeline::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 2px; background: linear-gradient(to bottom, var(--accent), transparent); }
    .timeline-item { position: relative; margin-bottom: 2.5rem; }
    .timeline-item::before { content: ''; position: absolute; left: -2.45rem; top: 0.4rem; width: 12px; height: 12px; border-radius: 50%; background: var(--accent); border: 2px solid var(--bg-primary); box-shadow: 0 0 12px var(--accent-glow); }
    .timeline-date { font-size: 0.78rem; font-weight: 700; color: var(--accent-light); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem; }
    .timeline-company { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.15rem; }
    .timeline-position { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.75rem; }
    .timeline-desc { color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6; }

    @media (max-width: 768px) {
        .about-intro { grid-template-columns: 1fr; text-align: center; }
        .about-avatar { margin: 0 auto; width: 160px; height: 160px; font-size: 4rem; }
        .about-actions { justify-content: center; }
    }
</style>
@endsection

@section('content')
<section class="about-hero">
    <div class="container">
        <div class="about-intro">
            <div class="about-avatar">T</div>
            <div class="about-text">
                <h1>{{ __("Hi, I'm") }} <span class="gradient-text">{{ __('Hồ Thành Thiện') }}</span></h1>
                <div class="role">⚡ {{ __('Full-Stack Developer & Problem Solver') }}</div>
                <p>{{ __("I'm a passionate developer with a love for building clean, performant, and user-friendly web applications. I bridge the gap between design and functionality to create digital experiences that matter.") }}</p>
                <p>{{ __("When I'm not coding, you'll find me exploring new technologies, contributing to open source, or brewing the perfect cup of coffee ☕") }}</p>
                <div class="about-actions">
                    <a href="{{ route('projects.index') }}" class="btn btn-primary">
                        <i class="fas fa-rocket"></i> {{ __('See My Work') }}
                    </a>
                    <a href="mailto:hothanhthien119@gmail.com" class="btn btn-outline">
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
        <h2 class="section-title">{{ __('My') }} <span class="gradient-text">{{ __('Skills') }}</span></h2>
        <p class="section-subtitle">{{ __('Technologies I work with') }}</p>

        <div style="display:flex; flex-direction:column; gap:3rem">
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
        <h2 class="section-title">{{ __('Work') }} <span class="gradient-text">{{ __('Experience') }}</span></h2>
        <p class="section-subtitle">{{ __('My professional journey') }}</p>
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
@endsection


