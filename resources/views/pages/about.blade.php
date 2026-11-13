@extends('layouts.app')

@section('title', __('About Me') . ' | ' . __('Hồ Thành Thiện'))
@section('description', __('Aspiring Fullstack Developer with hands-on experience in building web applications through academic and personal projects. Skilled in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs, with a solid foundation in database design and software development.'))
@section('keywords', app()->getLocale() == 'vi' ? 'Về Hồ Thành Thiện, Hồ Thành Thiện Full-Stack Developer, Kinh nghiệm lập trình, Kỹ năng Web Developer, UTH, Odoo Developer, RAG LLM' : 'About Ho Thanh Thien, Full-Stack Developer Experience, Web Developer Skills, Laravel Software Engineer, UTH, Odoo Developer, RAG LLM')

@section('styles')
<style>
    .about-header {
        padding: 3.75rem 0 3rem;
        background: var(--bg-secondary, #eef1f5);
        border-bottom: 1px solid var(--border, #dce1e9);
    }
    .about-intro {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 2.5rem;
        align-items: start;
    }
    .about-monogram {
        width: 112px;
        height: 112px;
        border-radius: 12px;
        background: var(--text-primary, #12161f);
        color: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid var(--text-primary, #12161f);
        box-shadow: 0 8px 24px rgba(18, 22, 31, 0.08);
    }
    .about-monogram .initials {
        font-size: 2.35rem;
        font-weight: 700;
        letter-spacing: -0.04em;
        line-height: 1;
    }
    .about-monogram .monogram-tag {
        margin-top: 0.35rem;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        color: #9aa5b8;
    }

    .about-text h1 {
        font-size: clamp(1.9rem, 4vw, 2.75rem);
        font-weight: 700;
        letter-spacing: -0.025em;
        color: var(--text-primary, #12161f);
        line-height: 1.15;
        margin-bottom: 0.45rem;
    }
    .about-role {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--accent, #1e40af);
        margin-bottom: 1rem;
    }
    .about-bio {
        font-size: 1rem;
        color: var(--text-secondary, #434d5e);
        line-height: 1.7;
        max-width: 65ch;
        margin-bottom: 1.35rem;
    }

    .about-quick-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin-bottom: 1.6rem;
    }
    .about-meta-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.4rem 0.8rem;
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 6px;
        font-size: 0.84rem;
        color: var(--text-secondary, #434d5e);
        text-decoration: none;
        transition: border-color 0.15s ease, color 0.15s ease;
    }
    a.about-meta-chip:hover {
        border-color: var(--text-primary, #12161f);
        color: var(--text-primary, #12161f);
    }
    .about-meta-chip i {
        color: var(--accent, #1e40af);
        font-size: 0.82rem;
    }

    .about-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        align-items: center;
    }

    @media (max-width: 768px) {
        .about-intro {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .about-monogram {
            width: 84px;
            height: 84px;
        }
        .about-monogram .initials {
            font-size: 1.85rem;
        }
    }

    /* Section Headers */
    .about-section {
        padding: 4rem 0;
    }
    .about-section--tinted {
        background: var(--bg-secondary, #eef1f5);
        border-top: 1px solid var(--border, #dce1e9);
        border-bottom: 1px solid var(--border, #dce1e9);
    }
    .about-section-head {
        margin-bottom: 2rem;
    }
    .about-section-head h2 {
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--text-primary, #12161f);
        margin-bottom: 0.35rem;
    }
    .about-section-head p {
        font-size: 0.96rem;
        color: var(--text-secondary, #434d5e);
        max-width: 60ch;
    }

    /* Achievements Grid (Unified Calm Monochrome) */
    .achievements-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.35rem;
    }
    @media (max-width: 1024px) {
        .achievements-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 640px) {
        .achievements-grid {
            grid-template-columns: 1fr;
        }
    }
    .achievement-card {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 10px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast, 160ms) ease, box-shadow var(--duration-base, 260ms) var(--ease-out, cubic-bezier(0.16, 1, 0.3, 1)), transform var(--duration-base, 260ms) var(--ease-out, cubic-bezier(0.16, 1, 0.3, 1));
    }
    .achievement-card:hover {
        border-color: var(--border-strong, #b8c2d1);
        box-shadow: var(--shadow-md, 0 10px 24px -8px rgba(18, 22, 31, 0.1));
        transform: translateY(-2px);
    }
    .achievement-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .achievement-icon-wrap {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--bg-secondary, #eef1f5);
        border: 1px solid var(--border, #dce1e9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent, #1e40af);
    }
    .achievement-icon-wrap svg {
        width: 20px;
        height: 20px;
    }
    .achievement-year {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted, #687385);
        background: var(--bg-primary, #f7f8fa);
        border: 1px solid var(--border, #dce1e9);
        padding: 0.2rem 0.6rem;
        border-radius: 5px;
    }
    .achievement-subtitle {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--accent, #1e40af);
        margin-bottom: 0.45rem;
    }
    .achievement-title {
        font-size: 1.06rem;
        font-weight: 700;
        color: var(--text-primary, #12161f);
        line-height: 1.4;
        margin-bottom: 0.6rem;
    }
    .achievement-desc {
        font-size: 0.9rem;
        color: var(--text-secondary, #434d5e);
        line-height: 1.6;
        margin-bottom: 1.15rem;
        flex: 1;
    }
    .achievement-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        padding-top: 0.9rem;
        border-top: 1px solid #eef1f5;
    }
    .achievement-tag {
        font-size: 0.76rem;
        font-weight: 500;
        color: var(--text-secondary, #434d5e);
        background: var(--bg-primary, #f7f8fa);
        border: 1px solid var(--border, #dce1e9);
        padding: 0.2rem 0.55rem;
        border-radius: 4px;
    }

    /* Education Ledger Card */
    .education-card {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 10px;
        padding: 2rem;
    }
    .education-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        padding-bottom: 1.35rem;
        margin-bottom: 1.35rem;
        border-bottom: 1px solid var(--border, #dce1e9);
    }
    .education-school {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary, #12161f);
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }
    .education-school i {
        color: var(--accent, #1e40af);
    }
    .education-degree {
        font-size: 0.96rem;
        font-weight: 500;
        color: var(--text-secondary, #434d5e);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .education-period {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        background: var(--bg-primary, #f7f8fa);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 6px;
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--text-primary, #12161f);
        white-space: nowrap;
    }
    .education-metrics {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.15rem 2rem;
    }
    @media (max-width: 640px) {
        .education-card {
            padding: 1.35rem;
        }
        .education-metrics {
            grid-template-columns: 1fr;
        }
        .about-meta-chip {
            max-width: 100%;
            overflow-wrap: anywhere;
        }
    }
    .education-metric-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    .education-metric-icon {
        width: 34px;
        height: 34px;
        border-radius: 7px;
        background: var(--bg-secondary, #eef1f5);
        border: 1px solid var(--border, #dce1e9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent, #1e40af);
        flex-shrink: 0;
        margin-top: 2px;
    }
    .education-metric-label {
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--text-muted, #687385);
        margin-bottom: 0.15rem;
    }
    .education-metric-val {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary, #12161f);
    }

    /* Skills Index Grid */
    .skills-categories-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.35rem;
    }
    @media (max-width: 768px) {
        .skills-categories-grid {
            grid-template-columns: 1fr;
        }
    }
    .skill-category-panel {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 10px;
        padding: 1.5rem;
    }
    .skill-category-head {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-size: 1.02rem;
        font-weight: 700;
        color: var(--text-primary, #12161f);
        padding-bottom: 0.85rem;
        margin-bottom: 0.9rem;
        border-bottom: 1px solid var(--border, #dce1e9);
    }
    .skill-category-head i {
        color: var(--accent, #1e40af);
        font-size: 0.92rem;
    }
    .skill-rows {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .skill-row {
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f3f7;
    }
    .skill-row:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }
    .skill-row-name {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.93rem;
        font-weight: 600;
        color: var(--text-primary, #12161f);
    }
    .skill-row-name i {
        color: var(--accent, #1e40af);
        font-size: 0.82rem;
    }
    .skill-row-details {
        margin-top: 0.25rem;
        font-size: 0.85rem;
        color: var(--text-secondary, #434d5e);
        line-height: 1.55;
        padding-left: 1.25rem;
    }

    /* Experience Ledger */
    .exp-ledger {
        display: flex;
        flex-direction: column;
        border-top: 1px solid var(--border, #dce1e9);
    }
    .exp-ledger-row {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 1.75rem;
        padding: 1.85rem 0;
        border-bottom: 1px solid var(--border, #dce1e9);
        align-items: start;
    }
    @media (max-width: 768px) {
        .exp-ledger-row {
            grid-template-columns: 1fr;
            gap: 0.85rem;
        }
    }
    .exp-ledger-date {
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--accent, #1e40af);
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .exp-ledger-company {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary, #12161f);
        line-height: 1.35;
    }
    .exp-ledger-role {
        font-size: 1.06rem;
        font-weight: 700;
        color: var(--text-primary, #12161f);
        margin-bottom: 0.65rem;
    }
    .exp-ledger-bullets {
        margin: 0 0 1rem 1.15rem;
        padding: 0;
        color: var(--text-secondary, #434d5e);
        font-size: 0.93rem;
        line-height: 1.65;
    }
    .exp-ledger-bullets li {
        margin-bottom: 0.3rem;
    }
    .exp-ledger-p {
        color: var(--text-secondary, #434d5e);
        font-size: 0.93rem;
        line-height: 1.65;
        margin-bottom: 1rem;
        max-width: 66ch;
    }
    .exp-ledger-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }
    .exp-ledger-tag {
        font-size: 0.77rem;
        font-weight: 500;
        color: var(--text-secondary, #434d5e);
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        padding: 0.2rem 0.6rem;
        border-radius: 5px;
    }
</style>
@endsection

@section('content')
<!-- Hero & Summary -->
<section class="about-header">
    <div class="container">
        <div class="about-intro hero-animate">
            <div class="about-monogram" aria-hidden="true">
                <span class="initials">HT</span>
                <span class="monogram-tag">dev</span>
            </div>
            <div class="about-text">
                <h1>{{ __("Hi, I'm") }} {{ __('Hồ Thành Thiện') }}</h1>
                <div class="about-role">{{ __('Full-stack Developer') }}</div>
                <p class="about-bio">{{ __('Aspiring Fullstack Developer with hands-on experience in building web applications through academic and personal projects. Skilled in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs, with a solid foundation in database design and software development.') }}</p>

                <div class="about-quick-meta">
                    <a href="tel:0375227764" class="about-meta-chip">
                        <i class="fas fa-phone-alt"></i> 0375227764
                    </a>
                    <a href="mailto:hothanhthien119@gmail.com" class="about-meta-chip">
                        <i class="fas fa-envelope"></i> hothanhthien119@gmail.com
                    </a>
                    <a href="https://hothanhthien.io.vn/" target="_blank" rel="noopener" class="about-meta-chip">
                        <i class="fas fa-globe"></i> hothanhthien.io.vn
                    </a>
                    <span class="about-meta-chip">
                        <i class="fas fa-location-dot"></i> HCM
                    </span>
                </div>

                <div class="about-actions">
                    <a href="{{ route('projects.index') }}" class="btn btn-primary">
                        <i class="fas fa-folder-open"></i> {{ __('See My Work') }}
                    </a>
                    <a href="{{ route('certificates') }}" class="btn btn-outline">
                        <i class="fas fa-file-pdf"></i> {{ __('Brochure & Giấy khen') }}
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

<!-- Key Achievements -->
<section class="about-section">
    <div class="container">
        <div class="about-section-head" data-reveal>
            <h2>{!! __('heading_achievements') !!}</h2>
            <p>{{ __('Academic recognition and competition awards') }}</p>
        </div>

        <div class="achievements-grid" data-stagger>
            <!-- 1. GPA 4.0 -->
            <div class="achievement-card">
                <div class="achievement-top">
                    <div class="achievement-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <span class="achievement-year">2026</span>
                </div>
                <div class="achievement-subtitle">
                    {{ app()->getLocale() == 'vi' ? 'Học kỳ gần nhất / GPA 4.0/4.0' : 'Latest Semester / GPA 4.0/4.0' }}
                </div>
                <h3 class="achievement-title">{{ __('GPA in Most Recent Semester — 4.0/4.0') }}</h3>
                <p class="achievement-desc">
                    {{ app()->getLocale() == 'vi' ? 'Đạt kết quả học tập xuất sắc với điểm GPA 4.0/4.0 trong học kỳ gần nhất, thể hiện tính kỷ luật cao và tư duy logic chuyên sâu.' : 'Achieved a perfect 4.0/4.0 GPA in the most recent semester, demonstrating high dedication, discipline, and strong software engineering competence.' }}
                </p>
                <div class="achievement-tags">
                    <span class="achievement-tag">{{ app()->getLocale() == 'vi' ? 'GPA tuyệt đối 4.0' : 'Perfect 4.0 GPA' }}</span>
                    <span class="achievement-tag">{{ app()->getLocale() == 'vi' ? 'Kỷ luật cao' : 'High Discipline' }}</span>
                    <span class="achievement-tag">{{ app()->getLocale() == 'vi' ? 'Tư duy logic' : 'Logical Thinking' }}</span>
                </div>
            </div>

            <!-- 2. Excellent Graduation -->
            <div class="achievement-card">
                <div class="achievement-top">
                    <div class="achievement-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                    </div>
                    <span class="achievement-year">2026</span>
                </div>
                <div class="achievement-subtitle">
                    {{ app()->getLocale() == 'vi' ? 'Tốt nghiệp Xuất sắc / UTH' : 'Summa Cum Laude / UTH' }}
                </div>
                <h3 class="achievement-title">{{ __('Bachelor’s Degree – Excellent Graduation Classification') }}</h3>
                <p class="achievement-desc">
                    {{ app()->getLocale() == 'vi' ? 'Bằng Cử nhân Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH) với xếp loại Tốt nghiệp Xuất sắc.' : "Bachelor's Degree in Information Technology from University of Transport Ho Chi Minh City with Excellent Graduation Classification." }}
                </p>
                <div class="achievement-tags">
                    <span class="achievement-tag">{{ app()->getLocale() == 'vi' ? 'Loại Xuất sắc' : 'Summa Cum Laude' }}</span>
                    <span class="achievement-tag">{{ app()->getLocale() == 'vi' ? 'ĐH GTVT TP.HCM' : 'UTH University' }}</span>
                    <span class="achievement-tag">{{ app()->getLocale() == 'vi' ? 'Cử nhân CNTT' : 'IT Engineer' }}</span>
                </div>
            </div>

            <!-- 3. Logo Design Award -->
            <div class="achievement-card">
                <div class="achievement-top">
                    <div class="achievement-icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="13.5" cy="6.5" r=".8" fill="currentColor"></circle>
                            <circle cx="17.5" cy="10.5" r=".8" fill="currentColor"></circle>
                            <circle cx="8.5" cy="7.5" r=".8" fill="currentColor"></circle>
                            <circle cx="6.5" cy="12.5" r=".8" fill="currentColor"></circle>
                            <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.563-2.512 5.563-5.563C22 6.5 17.5 2 12 2z"></path>
                        </svg>
                    </div>
                    <span class="achievement-year">2025</span>
                </div>
                <div class="achievement-subtitle">
                    {{ app()->getLocale() == 'vi' ? 'Giải Nhì / Thiết kế Cấp Viện' : 'Second Prize / Logo Design' }}
                </div>
                <h3 class="achievement-title">{{ __('Second prize in the institute/faculty logo design competition') }}</h3>
                <p class="achievement-desc">
                    {{ __('Designed a competition-winning logo concept, demonstrating strong creativity, visual communication, and design thinking.') }}
                </p>
                <div class="achievement-tags">
                    <span class="achievement-tag">{{ app()->getLocale() == 'vi' ? 'Giải Nhì cấp Viện' : '2nd Prize Winner' }}</span>
                    <span class="achievement-tag">Design Thinking</span>
                    <span class="achievement-tag">{{ app()->getLocale() == 'vi' ? 'Truyền thông thị giác' : 'Visual Aesthetics' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Education -->
<section class="about-section about-section--tinted">
    <div class="container">
        <div class="about-section-head" data-reveal>
            <h2>{!! __('heading_education') !!}</h2>
            <p>{{ __('Formal academic background & verified credentials') }}</p>
        </div>

        <div class="education-card" data-reveal>
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
                <span class="education-period">
                    <i class="far fa-calendar-alt"></i> 2023 - 2026
                </span>
            </div>

            <div class="education-metrics">
                <div class="education-metric-item">
                    <div class="education-metric-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="education-metric-label">{{ __('Cumulative GPA') }}</div>
                        <div class="education-metric-val">3.6 / 4.0</div>
                    </div>
                </div>

                <div class="education-metric-item">
                    <div class="education-metric-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div>
                        <div class="education-metric-label">{{ __('Classification') }}</div>
                        <div class="education-metric-val">{{ __('Excellent') }}</div>
                    </div>
                </div>

                <div class="education-metric-item" style="grid-column: 1 / -1">
                    <div class="education-metric-icon">
                        <i class="fab fa-google"></i>
                    </div>
                    <div>
                        <div class="education-metric-label">{{ __('Community & Clubs') }}</div>
                        <div class="education-metric-val">{{ __('Member Google Developer Chapter University of Transport Ho Chi Minh City') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Technical Skills -->
@if($skills->count())
<section class="about-section">
    <div class="container">
        <div class="about-section-head" data-reveal>
            <h2>{!! __('heading_my_skills') !!}</h2>
            <p>{{ __('Technologies and tools I work with every day') }}</p>
        </div>

        <div class="skills-categories-grid" data-stagger>
            @foreach($skills as $category => $categorySkills)
            <div class="skill-category-panel">
                <div class="skill-category-head">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ $category }}</span>
                </div>
                <div class="skill-rows">
                    @foreach($categorySkills as $skill)
                    <div class="skill-row">
                        <div class="skill-row-name">
                            <i class="{{ $skill->icon ?: 'fas fa-check' }}"></i>
                            <span>{{ $skill->name }}</span>
                        </div>
                        @if($skill->details)
                        <div class="skill-row-details">{{ $skill->details }}</div>
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

<!-- Work Experience -->
@if($experiences->count())
<section class="about-section about-section--tinted">
    <div class="container">
        <div class="about-section-head" data-reveal>
            <h2>{!! __('heading_experience') !!}</h2>
            <p>{{ __('My professional journey so far') }}</p>
        </div>

        <div class="exp-ledger" data-stagger>
            @foreach($experiences as $exp)
            <div class="exp-ledger-row">
                <div>
                    <div class="exp-ledger-date">
                        <i class="far fa-calendar-alt"></i>
                        <span>{{ $exp->formatted_date }}</span>
                    </div>
                    <div class="exp-ledger-company">{{ $exp->localized_company }}</div>
                </div>
                <div>
                    <h3 class="exp-ledger-role">{{ $exp->localized_position }}</h3>
                    @php
                        $sentences = array_filter(array_map('trim', explode('.', $exp->localized_description)));
                    @endphp
                    @if(count($sentences) > 1)
                        <ul class="exp-ledger-bullets">
                            @foreach($sentences as $s)
                                @if(!empty($s))
                                <li>{{ $s }}.</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="exp-ledger-p">{{ $exp->localized_description }}</p>
                    @endif

                    @if(count($exp->tech_tags))
                    <div class="exp-ledger-tags">
                        @foreach($exp->tech_tags as $tag)
                        <span class="exp-ledger-tag">{{ $tag }}</span>
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
