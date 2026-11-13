@extends('layouts.app')

@section('title', __('About Me') . ' | ' . __('Hồ Thành Thiện'))
@section('description', app()->getLocale() == 'vi' ? 'Tìm hiểu về Hồ Thành Thiện — Kỹ sư Full-Stack Web Developer (ĐH GTVT TP.HCM), thành tích GPA 4.0/4.0, tốt nghiệp Xuất sắc, giải thưởng thiết kế logo và kinh nghiệm lập trình.' : 'Learn more about Ho Thanh Thien — Full-Stack Web Developer (UTH), academic honors (4.0 GPA, Excellent Graduation), design award, and engineering experience.')
@section('keywords', app()->getLocale() == 'vi' ? 'Về Hồ Thành Thiện, Hồ Thành Thiện UTH, Google Developer Chapter UTH, Kỹ sư phần mềm Full-Stack' : 'About Ho Thanh Thien, UTH IT Engineer, Google Developer Chapter, Full-Stack Developer Profile')

@section('styles')
<style>
    .about-hero {
        padding: clamp(2.75rem, 5vw, 4.25rem) 0 clamp(2.5rem, 4.5vw, 3.5rem);
        background: var(--bg-primary);
        border-bottom: 1px solid var(--border-strong);
    }

    .about-hero-grid {
        display: grid;
        grid-template-columns: 200px minmax(0, 1fr);
        gap: clamp(1.75rem, 4vw, 3.5rem);
        align-items: start;
    }

    .about-monogram-plate {
        background: var(--surface-ink);
        color: #FFFFFF;
        border: 1px solid #232938;
        border-radius: var(--radius-lg);
        padding: 1.5rem 1.25rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: var(--shadow-md);
    }
    .about-monogram-initials {
        font-family: var(--font-display);
        font-size: 3.1rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.05em;
        color: #FFFFFF;
        margin-bottom: 0.55rem;
    }
    .about-monogram-tag {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: #93C5FD;
        padding: 0.2rem 0.6rem;
        border: 1px solid rgba(147, 197, 253, 0.25);
        border-radius: var(--radius-sm);
    }
    .about-monogram-meta {
        margin-top: 1.15rem;
        padding-top: 0.9rem;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        width: 100%;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: #9CA3AF;
        line-height: 1.5;
    }

    .about-hero-text h1 {
        font-size: clamp(2rem, 4.4vw, 3.3rem);
        font-weight: 800;
        line-height: 1.08;
        letter-spacing: -0.035em;
        margin-bottom: 0.5rem;
    }
    .about-role-line {
        font-family: var(--font-mono);
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--accent);
        margin-bottom: 1.15rem;
    }
    .about-lead-bio {
        font-size: 1.04rem;
        color: var(--text-secondary);
        line-height: 1.72;
        max-width: 64ch;
        margin-bottom: 1.4rem;
    }

    .about-meta-strip {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin-bottom: 1.5rem;
    }
    .about-meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        padding: 0.34rem 0.72rem;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 0.84rem;
        color: var(--text-secondary);
        text-decoration: none;
        transition: border-color var(--duration-fast) ease, color var(--duration-fast) ease;
    }
    .about-meta-chip i {
        color: var(--accent);
        font-size: 0.8rem;
    }
    a.about-meta-chip:hover {
        border-color: var(--text-primary);
        color: var(--text-primary);
    }

    .about-cta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
    }

    /* Engineering Principles & Narrative */
    .story-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
        gap: clamp(1.75rem, 4vw, 3.5rem);
        align-items: start;
    }
    .story-narrative p {
        font-size: 1rem;
        color: var(--text-secondary);
        line-height: 1.78;
        margin-bottom: 1.1rem;
        max-width: 60ch;
    }
    .story-pillars {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .pillar-row {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.15rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.9rem;
    }
    .pillar-num {
        font-family: var(--font-mono);
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--accent);
        background: var(--accent-soft);
        padding: 0.25rem 0.5rem;
        border-radius: var(--radius-sm);
        flex-shrink: 0;
        margin-top: 0.1rem;
    }
    .pillar-title {
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.2rem;
    }
    .pillar-desc {
        font-size: 0.88rem;
        color: var(--text-secondary);
        line-height: 1.55;
    }

    /* Achievements Editorial Cards */
    .achievements-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.25rem;
    }
    .ach-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
    }
    .ach-card-top {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 0.75rem;
        padding-bottom: 0.9rem;
        margin-bottom: 0.9rem;
        border-bottom: 1px solid var(--border);
    }
    .ach-metric {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.04em;
        line-height: 1;
    }
    .ach-year {
        font-family: var(--font-mono);
        font-size: 0.76rem;
        color: var(--accent);
        font-weight: 600;
    }
    .ach-title {
        font-family: var(--font-display);
        font-size: 1.08rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.35;
        margin-bottom: 0.55rem;
    }
    .ach-desc {
        font-size: 0.9rem;
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 1rem;
        flex: 1;
    }
    .ach-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    /* Education Ledger Card */
    .education-plate {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: clamp(1.4rem, 3vw, 2rem);
    }
    .education-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding-bottom: 1.25rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid var(--border);
    }
    .education-school {
        font-family: var(--font-display);
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.3rem;
    }
    .education-degree {
        font-size: 0.96rem;
        font-weight: 600;
        color: var(--accent);
    }
    .education-period {
        font-family: var(--font-mono);
        font-size: 0.82rem;
        color: var(--text-secondary);
        background: var(--bg-primary);
        border: 1px solid var(--border);
        padding: 0.32rem 0.75rem;
        border-radius: var(--radius-sm);
    }
    .education-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }
    .edu-metric-box {
        background: var(--bg-primary);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 0.95rem 1.1rem;
    }
    .edu-metric-label {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
    }
    .edu-metric-val {
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    /* Grouped Skills 8-Domain Grid */
    .about-skills-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem;
    }
    .about-skill-panel {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.35rem 1.45rem;
    }
    .about-skill-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 0.75rem;
        margin-bottom: 0.85rem;
        border-bottom: 1px solid var(--border);
    }
    .about-skill-title {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-family: var(--font-display);
        font-size: 1.04rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .about-skill-title i {
        color: var(--accent);
        font-size: 0.9rem;
    }
    .about-skill-rows {
        display: flex;
        flex-wrap: wrap;
        gap: 0.42rem;
        margin-bottom: 0.65rem;
    }
    .about-skill-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.38rem;
        padding: 0.32rem 0.62rem;
        background: var(--bg-primary);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--text-primary);
    }
    .about-skill-chip i {
        color: var(--accent);
        font-size: 0.78rem;
    }

    /* Experience Ledger */
    .exp-ledger {
        border-top: 1px solid var(--border-strong);
    }
    .exp-ledger-row {
        display: grid;
        grid-template-columns: 240px minmax(0, 1fr);
        gap: 2rem;
        padding: 1.85rem 0;
        border-bottom: 1px solid var(--border);
        align-items: start;
    }
    .exp-ledger-date {
        font-family: var(--font-mono);
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--accent);
        margin-bottom: 0.35rem;
    }
    .exp-ledger-company {
        font-family: var(--font-display);
        font-size: 1.08rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .exp-ledger-role {
        font-family: var(--font-display);
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.65rem;
    }
    .exp-ledger-bullets {
        margin: 0 0 1rem 1.15rem;
        color: var(--text-secondary);
        font-size: 0.94rem;
        line-height: 1.68;
    }
    .exp-ledger-bullets li {
        margin-bottom: 0.38rem;
    }
    .exp-ledger-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.38rem;
    }

    @media (max-width: 960px) {
        .about-hero-grid { grid-template-columns: 1fr; }
        .about-monogram-plate { max-width: 240px; }
        .story-grid { grid-template-columns: 1fr; }
        .achievements-grid { grid-template-columns: 1fr; }
        .education-metrics { grid-template-columns: 1fr; }
        .about-skills-grid { grid-template-columns: 1fr; }
        .exp-ledger-row { grid-template-columns: 1fr; gap: 0.85rem; }
    }
</style>
@endsection

@section('content')
@php $isVi = app()->getLocale() === 'vi'; @endphp

<!-- Editorial Hero -->
<section class="about-hero">
    <div class="container">
        <div class="about-hero-grid hero-animate">
            <aside class="about-monogram-plate" aria-hidden="true">
                <div class="about-monogram-initials">HT</div>
                <div class="about-monogram-tag">FULL-STACK // AI</div>
                <div class="about-monogram-meta">
                    B.Sc. Information Technology<br>UTH · 2023–2026
                </div>
            </aside>

            <div class="about-hero-text">
                <div class="editorial-kicker">{{ __('About Me') }}</div>
                <h1>{{ __("Hi, I'm") }} {{ __('Hồ Thành Thiện') }}</h1>
                <div class="about-role-line">Full-Stack Web Developer · Laravel · Java Spring Boot · ReactJS</div>
                <p class="about-lead-bio">
                    {{ __('Aspiring Fullstack Developer with hands-on experience in building web applications through academic and personal projects. Skilled in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs, with a solid foundation in database design and software development.') }}
                </p>

                <div class="about-meta-strip">
                    <a href="tel:0375227764" class="about-meta-chip"><i class="fas fa-phone-alt"></i> 0375227764</a>
                    <a href="mailto:hothanhthien119@gmail.com" class="about-meta-chip"><i class="fas fa-envelope"></i> hothanhthien119@gmail.com</a>
                    <a href="https://hothanhthien.io.vn/" target="_blank" rel="noopener" class="about-meta-chip"><i class="fas fa-globe"></i> hothanhthien.io.vn</a>
                    <span class="about-meta-chip"><i class="fas fa-location-dot"></i> HCM</span>
                </div>

                <div class="about-cta-row">
                    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-folder-open" aria-hidden="true"></i> {{ __('See My Work') }}
                    </a>
                    <a href="{{ route('certificates') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-file-pdf" aria-hidden="true"></i> {{ __('Credentials') }}
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-paper-plane" aria-hidden="true"></i> {{ __('Get In Touch') }}
                    </a>
                    <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                        <i class="fab fa-github" aria-hidden="true"></i> GitHub
                    </a>
                    <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                        <i class="fab fa-linkedin" aria-hidden="true"></i> LinkedIn
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Personal Story & Principles -->
<section class="section">
    <div class="container">
        <div class="editorial-head" data-reveal>
            <div class="editorial-head-left">
                <div class="editorial-kicker">01 // {{ __('Personal Story & Philosophy') }}</div>
                <h2>{{ __('Design & Engineering Principles') }}</h2>
            </div>
        </div>

        <div class="story-grid" data-reveal>
            <div class="story-narrative">
                <p>
                    {{ __("I'm a passionate developer with a love for building clean, performant, and user-friendly web applications. I bridge the gap between design and functionality to create digital experiences that matter.") }}
                </p>
                <p>
                    {{ __("When I'm not coding, you'll find me exploring new technologies, contributing to open source, or brewing the perfect cup of coffee ☕") }}
                </p>
            </div>

            <div class="story-pillars">
                <div class="pillar-row">
                    <span class="pillar-num">01</span>
                    <div>
                        <h3 class="pillar-title">{{ __('Engineering Craft') }}</h3>
                        <p class="pillar-desc">{{ $isVi ? 'Ưu tiên thiết kế CSDL chuẩn hóa, API rõ ràng và mã nguồn dễ bảo trì lâu dài.' : 'Prioritizing normalized schema design, predictable APIs, and maintainable architecture.' }}</p>
                    </div>
                </div>
                <div class="pillar-row">
                    <span class="pillar-num">02</span>
                    <div>
                        <h3 class="pillar-title">{{ __('Fullstack & AI Systems') }}</h3>
                        <p class="pillar-desc">{{ $isVi ? 'Kết hợp frontend trực quan với backend vững chắc (Laravel, Spring Boot) và ứng dụng RAG / LLM thực tiễn.' : 'Bridging expressive interfaces with resilient backends and practical RAG / LLM pipelines.' }}</p>
                    </div>
                </div>
                <div class="pillar-row">
                    <span class="pillar-num">03</span>
                    <div>
                        <h3 class="pillar-title">{{ __('Academic Discipline') }}</h3>
                        <p class="pillar-desc">{{ $isVi ? 'Duy trì kỷ luật học thuật cao (GPA 4.0/4.0 học kỳ gần nhất, tốt nghiệp Xuất sắc ĐH GTVT TP.HCM).' : 'Sustaining high rigor (4.0 semester GPA & Excellent graduation standing at UTH).' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Key Achievements -->
<section class="section section--alt">
    <div class="container">
        <div class="editorial-head" data-reveal>
            <div class="editorial-head-left">
                <div class="editorial-kicker">02 // MILESTONES</div>
                <h2>{!! __('heading_achievements') !!}</h2>
                <p>{{ __('Academic recognition and competition awards') }}</p>
            </div>
        </div>

        <div class="achievements-grid" data-stagger>
            <article class="ach-card">
                <div class="ach-card-top">
                    <span class="ach-metric">4.0</span>
                    <span class="ach-year">2026</span>
                </div>
                <h3 class="ach-title">{{ __('GPA in Most Recent Semester — 4.0/4.0') }}</h3>
                <p class="ach-desc">
                    {{ $isVi ? 'Đạt kết quả học tập xuất sắc với điểm GPA 4.0/4.0 trong học kỳ gần nhất, thể hiện tính kỷ luật cao và tư duy logic chuyên sâu.' : 'Achieved a perfect 4.0/4.0 GPA in the most recent semester, demonstrating high dedication, discipline, and strong software engineering competence.' }}
                </p>
                <div class="ach-tags">
                    <span class="tag">{{ $isVi ? 'GPA tuyệt đối 4.0' : 'Perfect 4.0 GPA' }}</span>
                    <span class="tag">{{ $isVi ? 'Kỷ luật cao' : 'High Discipline' }}</span>
                </div>
            </article>

            <article class="ach-card">
                <div class="ach-card-top">
                    <span class="ach-metric">3.6</span>
                    <span class="ach-year">2026</span>
                </div>
                <h3 class="ach-title">{{ __('Bachelor’s Degree – Excellent Graduation Classification') }}</h3>
                <p class="ach-desc">
                    {{ $isVi ? 'Bằng Cử nhân Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH) với xếp loại Tốt nghiệp Xuất sắc.' : "Bachelor's Degree in Information Technology from University of Transport Ho Chi Minh City with Excellent Graduation Classification." }}
                </p>
                <div class="ach-tags">
                    <span class="tag">{{ $isVi ? 'Loại Xuất sắc' : 'Excellent' }}</span>
                    <span class="tag">{{ $isVi ? 'ĐH GTVT TP.HCM' : 'UTH' }}</span>
                </div>
            </article>

            <article class="ach-card">
                <div class="ach-card-top">
                    <span class="ach-metric">2nd</span>
                    <span class="ach-year">2025</span>
                </div>
                <h3 class="ach-title">{{ __('Second prize in the institute/faculty logo design competition') }}</h3>
                <p class="ach-desc">
                    {{ __('Designed a competition-winning logo concept, demonstrating strong creativity, visual communication, and design thinking.') }}
                </p>
                <div class="ach-tags">
                    <span class="tag">Design Thinking</span>
                    <span class="tag">{{ $isVi ? 'Truyền thông thị giác' : 'Visual Identity' }}</span>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- Education -->
<section class="section">
    <div class="container">
        <div class="editorial-head" data-reveal>
            <div class="editorial-head-left">
                <div class="editorial-kicker">03 // ACADEMIC RECORD</div>
                <h2>{!! __('heading_education') !!}</h2>
                <p>{{ __('Formal academic background & verified credentials') }}</p>
            </div>
        </div>

        <div class="education-plate" data-reveal>
            <div class="education-head">
                <div>
                    <h3 class="education-school">{{ __('University of Transport Ho Chi Minh City') }}</h3>
                    <div class="education-degree">{{ __("Bachelor's Degree Information Technology") }}</div>
                </div>
                <span class="education-period">2023 – 2026</span>
            </div>

            <div class="education-metrics">
                <div class="edu-metric-box">
                    <div class="edu-metric-label">{{ __('Cumulative GPA') }}</div>
                    <div class="edu-metric-val">3.6 / 4.0</div>
                </div>
                <div class="edu-metric-box">
                    <div class="edu-metric-label">{{ __('Classification') }}</div>
                    <div class="edu-metric-val">{{ __('Excellent') }}</div>
                </div>
                <div class="edu-metric-box">
                    <div class="edu-metric-label">{{ __('Community & Clubs') }}</div>
                    <div class="edu-metric-val">{{ __('Member Google Developer Chapter University of Transport Ho Chi Minh City') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technical Skills -->
@if($skills->count())
@php
    $aboutBuckets = [
        'languages' => ['label' => __('Programming Languages'), 'icon' => 'fas fa-code', 'items' => []],
        'frontend' => ['label' => __('Frontend Development'), 'icon' => 'fas fa-layer-group', 'items' => []],
        'backend' => ['label' => __('Backend Development'), 'icon' => 'fas fa-server', 'items' => []],
        'databases' => ['label' => __('Databases'), 'icon' => 'fas fa-database', 'items' => []],
        'architecture' => ['label' => __('API & Architecture'), 'icon' => 'fas fa-sitemap', 'items' => []],
        'devops' => ['label' => __('DevOps & Cloud'), 'icon' => 'fas fa-cloud', 'items' => []],
        'ai' => ['label' => __('AI & Integration'), 'icon' => 'fas fa-microchip', 'items' => []],
        'tools' => ['label' => __('Development Tools'), 'icon' => 'fas fa-screwdriver-wrench', 'items' => []],
    ];
    foreach ($skills as $rawCat => $catItems) {
        foreach ($catItems as $sk) {
            $nameLow = strtolower($sk->name);
            $catLow = strtolower($rawCat);
            if ($catLow === 'languages') { $aboutBuckets['languages']['items'][] = $sk; }
            elseif ($catLow === 'frontend' || str_contains($nameLow, 'next') || str_contains($nameLow, 'responsive') || str_contains($nameLow, 'tailwind') || str_contains($nameLow, 'blade')) { $aboutBuckets['frontend']['items'][] = $sk; }
            elseif ($catLow === 'backend') { $aboutBuckets['backend']['items'][] = $sk; }
            elseif ($catLow === 'database') { $aboutBuckets['databases']['items'][] = $sk; }
            elseif (str_contains($nameLow, 'rag') || str_contains($nameLow, 'llm') || str_contains($nameLow, 'vector') || str_contains($nameLow, 'ai ') || $nameLow === 'ai') { $aboutBuckets['ai']['items'][] = $sk; }
            elseif (str_contains($nameLow, 'docker') || str_contains($nameLow, 'aws') || str_contains($nameLow, 'nginx') || str_contains($nameLow, 'cpanel') || str_contains($nameLow, 'ci/cd') || $nameLow === 'firebase') { $aboutBuckets['devops']['items'][] = $sk; }
            elseif (in_array($catLow, ['api development', 'software engineering', 'authentication']) || str_contains($nameLow, 'websocket') || str_contains($nameLow, 'socket.io') || str_contains($nameLow, 'payment') || str_contains($nameLow, 'auth')) { $aboutBuckets['architecture']['items'][] = $sk; }
            else { $aboutBuckets['tools']['items'][] = $sk; }
        }
    }
    $aboutBuckets = array_filter($aboutBuckets, fn($b) => count($b['items']) > 0);
@endphp
<section class="section section--alt">
    <div class="container">
        <div class="editorial-head" data-reveal>
            <div class="editorial-head-left">
                <div class="editorial-kicker">04 // CAPABILITIES</div>
                <h2>{!! __('heading_my_skills') !!}</h2>
                <p>{{ __('Technologies and tools I work with every day') }}</p>
            </div>
        </div>

        <div class="about-skills-grid" data-stagger>
            @foreach($aboutBuckets as $bucket)
                @php $detailed = array_filter($bucket['items'], fn($i) => !empty(trim((string) $i->details))); @endphp
                <div class="about-skill-panel">
                    <div class="about-skill-head">
                        <div class="about-skill-title">
                            <i class="{{ $bucket['icon'] }}" aria-hidden="true"></i>
                            <span>{{ $bucket['label'] }}</span>
                        </div>
                        <span class="mono-label">{{ count($bucket['items']) }}</span>
                    </div>

                    <div class="about-skill-rows">
                        @foreach($bucket['items'] as $skill)
                            <span class="about-skill-chip" title="{{ $skill->details ?: $skill->name }}">
                                <i class="{{ $skill->icon ?: 'fas fa-check' }}" aria-hidden="true"></i>
                                <span>{{ $skill->name }}</span>
                            </span>
                        @endforeach
                    </div>

                    @if(count($detailed))
                    <details style="margin-top:0.75rem; border-top:1px dashed var(--border); padding-top:0.65rem;">
                        <summary style="cursor:pointer; font-family:var(--font-mono); font-size:0.76rem; color:var(--text-muted); font-weight:600;">
                            {{ __('Technical notes') }} ({{ count($detailed) }})
                        </summary>
                        <div style="margin-top:0.55rem; display:flex; flex-direction:column; gap:0.4rem; font-size:0.84rem; color:var(--text-secondary); line-height:1.55;">
                            @foreach($detailed as $ds)
                                <div><strong style="color:var(--text-primary);">{{ $ds->name }}:</strong> {{ $ds->details }}</div>
                            @endforeach
                        </div>
                    </details>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Work Experience -->
@if($experiences->count())
<section class="section">
    <div class="container">
        <div class="editorial-head" data-reveal>
            <div class="editorial-head-left">
                <div class="editorial-kicker">05 // WORK HISTORY</div>
                <h2>{!! __('heading_experience') !!}</h2>
                <p>{{ __('My professional journey so far') }}</p>
            </div>
        </div>

        <div class="exp-ledger" data-stagger>
            @foreach($experiences as $exp)
                <div class="exp-ledger-row">
                    <div>
                        <div class="exp-ledger-date">{{ $exp->formatted_date }}</div>
                        <div class="exp-ledger-company">{{ $exp->localized_company }}</div>
                    </div>
                    <div>
                        <h3 class="exp-ledger-role">{{ $exp->localized_position }}</h3>
                        @php $sentences = array_values(array_filter(array_map('trim', explode('.', (string) $exp->localized_description)))); @endphp
                        @if(count($sentences) > 1)
                            <ul class="exp-ledger-bullets">
                                @foreach($sentences as $s)
                                    <li>{{ $s }}.</li>
                                @endforeach
                            </ul>
                        @else
                            <p style="color:var(--text-secondary); font-size:0.94rem; line-height:1.68; margin-bottom:1rem;">{{ $exp->localized_description }}</p>
                        @endif

                        @if(count($exp->tech_tags))
                        <div class="exp-ledger-tags">
                            @foreach($exp->tech_tags as $tag)
                                <span class="tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
