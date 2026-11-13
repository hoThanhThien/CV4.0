@extends('layouts.app')

@section('title', app()->getLocale() === 'vi' ? 'Hồ Thành Thiện | Kỹ sư Full-Stack Developer & AI Solutions' : 'Ho Thanh Thien | Fullstack Software Engineer')

@section('styles')
<style>
    /* =========================================================
       DARK EDITORIAL TECH // HERO BENTO SECTION
    ========================================================= */
    .hero-editorial {
        padding: clamp(2.5rem, 5vw, 4.25rem) 0 clamp(2.5rem, 5vw, 3.75rem);
        border-bottom: 1px solid var(--border);
        position: relative;
    }

    .hero-bento-grid {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 1.25rem;
    }

    /* Cell 1: Main Name & Profile (8 cols) */
    .hero-main-card {
        grid-column: span 8;
        padding: clamp(1.75rem, 3.5vw, 2.75rem);
    }
    .hero-kicker-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }
    .mono-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        font-family: var(--font-mono);
        font-size: 0.76rem;
        font-weight: 700;
        color: var(--accent-lime);
        letter-spacing: 0.05em;
    }
    .kicker-pulse {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--accent-lime);
        box-shadow: 0 0 10px var(--accent-lime);
        animation: radarPulse 1.8s infinite ease-out;
    }

    .hero-giant-name {
        font-family: var(--font-display);
        font-size: clamp(3rem, 6.2vw, 5.8rem);
        font-weight: 800;
        line-height: 0.98;
        letter-spacing: -0.04em;
        color: #F8FAFC;
        margin-bottom: 1.25rem;
        display: flex;
        flex-direction: column;
    }
    .hero-giant-name .text-accent {
        color: var(--accent-lime);
        text-shadow: 0 0 32px rgba(198, 244, 50, 0.22);
    }

    .hero-role-ticker {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin-bottom: 1.35rem;
    }
    .ticker-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.35rem 0.75rem;
        border-radius: var(--radius-sm);
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border);
        color: #F8FAFC;
        font-family: var(--font-mono);
        font-size: 0.8rem;
        font-weight: 600;
    }
    .ticker-badge i { color: var(--accent-lime); }
    .ticker-dot { color: var(--text-muted); font-size: 0.8rem; }

    .hero-impact-text {
        font-size: clamp(1.02rem, 1.45vw, 1.18rem);
        line-height: 1.72;
        color: var(--text-secondary);
        max-width: 58ch;
        margin-bottom: 2rem;
    }

    .hero-actions-group {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.85rem;
        margin-bottom: 2rem;
    }
    .hero-btn-main {
        font-size: 0.94rem;
        padding: 0.75rem 1.6rem;
    }
    .hero-btn-secondary {
        font-size: 0.94rem;
        padding: 0.75rem 1.35rem;
    }

    .hero-connect-strip {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.85rem 1.6rem;
        padding-top: 1.45rem;
        border-top: 1px solid var(--border);
    }
    .connect-link {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: color var(--duration-fast) ease;
    }
    .connect-link i { color: var(--accent-lime); font-size: 0.92rem; }
    .connect-link:hover { color: #FFFFFF; }

    /* Cell 2: Status & Radar (4 cols) */
    .hero-status-card {
        grid-column: span 4;
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .status-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }
    .live-radar-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--accent-lime);
        box-shadow: 0 0 12px var(--accent-lime);
        animation: radarPulse 1.8s infinite;
    }
    .status-title {
        font-family: var(--font-display);
        font-size: 1.32rem;
        font-weight: 700;
        color: #F8FAFC;
        line-height: 1.24;
        margin-bottom: 0.35rem;
    }
    .status-sub {
        font-family: var(--font-mono);
        font-size: 0.8rem;
        color: var(--accent-lime);
        font-weight: 600;
        margin-bottom: 1.35rem;
    }
    .status-meta-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .status-meta-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.86rem;
        color: var(--text-secondary);
    }
    .status-meta-item i {
        color: var(--accent-sky);
        width: 16px;
        text-align: center;
    }
    .status-cta {
        width: 100%;
        margin-top: auto;
    }

    /* Cell 3: Interactive Terminal Runtime (7 cols) */
    .hero-terminal-card {
        grid-column: span 7;
        padding: 0;
    }
    .terminal-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1.25rem;
        background: rgba(6, 9, 15, 0.7);
        border-bottom: 1px solid var(--border);
    }
    .terminal-badge-live {
        font-family: var(--font-mono);
        font-size: 0.68rem;
        color: var(--accent-lime);
        background: rgba(198, 244, 50, 0.1);
        border: 1px solid rgba(198, 244, 50, 0.25);
        padding: 0.1rem 0.45rem;
        border-radius: 3px;
        font-weight: 700;
    }
    .terminal-code-body {
        padding: 1.25rem;
        font-family: var(--font-mono);
        font-size: 0.82rem;
        line-height: 1.68;
    }
    .t-line { color: var(--text-secondary); margin-bottom: 0.65rem; }
    .t-prompt { color: var(--accent-lime); font-weight: 700; margin-right: 0.45rem; }
    .t-cmd { color: #FFFFFF; font-weight: 600; }
    .t-json {
        margin: 0;
        color: #CBD5E1;
        background: none;
        padding: 0;
        border: none;
        font-family: var(--font-mono);
        font-size: 0.81rem;
        overflow-x: auto;
    }
    .t-key { color: var(--accent-sky); }
    .t-val { color: #F8FAFC; }
    .t-accent { color: var(--accent-lime); font-weight: 600; }

    /* Cell 4: Blueprint Stats (5 cols) */
    .hero-blueprint-card {
        grid-column: span 5;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .blueprint-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }
    .blueprint-stats-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.35rem;
    }
    .bp-stat {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 1rem;
    }
    .bp-val {
        font-family: var(--font-display);
        font-size: 2.1rem;
        font-weight: 800;
        color: #FFFFFF;
        line-height: 1;
        margin-bottom: 0.35rem;
    }
    .bp-label {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--text-muted);
    }
    .bp-highlights {
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
    }
    .bp-pill {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.5rem 0.8rem;
        border-radius: var(--radius-sm);
        background: rgba(255, 255, 255, 0.025);
        border: 1px solid var(--border);
        font-size: 0.82rem;
        color: var(--text-secondary);
    }
    .bp-pill i { color: var(--accent-lime); font-size: 0.84rem; }

    @media (max-width: 1040px) {
        .hero-main-card { grid-column: span 12; }
        .hero-status-card { grid-column: span 12; }
        .hero-terminal-card { grid-column: span 12; }
        .hero-blueprint-card { grid-column: span 12; }
    }

    /* =========================================================
       SECTION 01 // SELECTED WORKS (CASE STUDIES)
    ========================================================= */
    .projects-case-stack {
        display: flex;
        flex-direction: column;
        gap: 2.25rem;
    }
    .project-showcase-row {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr);
        overflow: hidden;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .project-showcase-row:hover {
        border-color: var(--accent-lime);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .project-showcase-row.is-reversed .project-showcase-media { order: 2; }
    .project-showcase-row.is-reversed .project-showcase-info { order: 1; }

    .project-showcase-media {
        display: block;
        position: relative;
        min-height: 290px;
        background: var(--surface-ink);
        border-right: 1px solid var(--border);
        text-decoration: none;
        overflow: hidden;
    }
    .project-showcase-row.is-reversed .project-showcase-media {
        border-right: none;
        border-left: 1px solid var(--border);
    }
    .project-showcase-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .project-showcase-info {
        padding: clamp(1.5rem, 3.2vw, 2.35rem);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .project-meta-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.85rem;
    }
    .project-index-code {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: var(--accent-lime);
        font-weight: 700;
        letter-spacing: 0.06em;
    }
    .project-featured-tag {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(198, 244, 50, 0.12);
        color: var(--accent-lime);
        border: 1px solid rgba(198, 244, 50, 0.3);
        padding: 0.15rem 0.55rem;
        border-radius: var(--radius-sm);
    }
    .project-showcase-title {
        font-family: var(--font-display);
        font-size: clamp(1.35rem, 2.3vw, 1.85rem);
        font-weight: 700;
        color: #F8FAFC;
        line-height: 1.22;
        margin-bottom: 0.75rem;
    }
    .project-showcase-title a {
        color: inherit;
        text-decoration: none;
        transition: color var(--duration-fast) ease;
    }
    .project-showcase-title a:hover { color: var(--accent-lime); }

    .project-showcase-desc {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 1.35rem;
        flex: 1;
    }
    .project-showcase-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 1.5rem;
    }
    .project-action-bar {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-wrap: wrap;
        padding-top: 1.15rem;
        border-top: 1px solid var(--border);
    }

    @media (max-width: 900px) {
        .project-showcase-row { grid-template-columns: 1fr; }
        .project-showcase-row.is-reversed .project-showcase-media { order: 0; border-left: none; }
        .project-showcase-media { border-right: none; border-bottom: 1px solid var(--border); min-height: 240px; }
    }

    /* =========================================================
       SECTION 02 // CAREER TIMELINE
    ========================================================= */
    .exp-timeline {
        border-left: 2px solid var(--border-strong);
        padding-left: clamp(1.25rem, 3.2vw, 2.25rem);
        display: flex;
        flex-direction: column;
        gap: 1.85rem;
    }
    .exp-entry {
        position: relative;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: clamp(1.35rem, 3vw, 2rem);
        transition: border-color var(--duration-fast) ease;
    }
    .exp-entry:hover { border-color: var(--accent-lime); }
    .exp-entry::before {
        content: '';
        position: absolute;
        left: calc(-1 * clamp(1.25rem, 3.2vw, 2.25rem) - 7px);
        top: 2rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--accent-lime);
        border: 2px solid #06090F;
        box-shadow: 0 0 10px var(--accent-lime);
    }
    .exp-entry-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.65rem;
        margin-bottom: 0.75rem;
    }
    .exp-role-title {
        font-family: var(--font-display);
        font-size: 1.25rem;
        font-weight: 700;
        color: #F8FAFC;
        margin-bottom: 0.2rem;
    }
    .exp-company-name {
        font-weight: 600;
        color: var(--accent-lime);
        font-size: 0.96rem;
    }
    .exp-period-badge {
        font-family: var(--font-mono);
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--text-secondary);
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border);
        padding: 0.25rem 0.65rem;
        border-radius: var(--radius-sm);
        white-space: nowrap;
    }
    .exp-bullets {
        margin: 0.85rem 0 1.15rem 1.15rem;
        color: var(--text-secondary);
        font-size: 0.94rem;
        line-height: 1.7;
    }
    .exp-bullets li { margin-bottom: 0.4rem; }
    .exp-tech-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        padding-top: 0.95rem;
        border-top: 1px solid var(--border);
    }

    /* =========================================================
       SECTION 03 // TECHNICAL MATRIX (SKILLS BENTO)
    ========================================================= */
    .skill-filter-row {
        display: flex;
        gap: 0.45rem;
        overflow-x: auto;
        padding-bottom: 0.4rem;
        margin-bottom: 1.85rem;
        scrollbar-width: none;
    }
    .skill-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.42rem 0.95rem;
        border-radius: var(--radius-sm);
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-family: var(--font-mono);
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        flex-shrink: 0;
        transition: all var(--duration-fast) ease;
    }
    .skill-filter-btn:hover { border-color: var(--accent-lime); color: #FFFFFF; }
    .skill-filter-btn.active {
        background: var(--accent-lime);
        border-color: var(--accent-lime);
        color: #06090F;
        font-weight: 700;
        box-shadow: 0 0 12px rgba(198, 244, 50, 0.35);
    }

    .skills-grid-8 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem;
    }
    .skill-group-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.4rem 1.55rem;
        display: flex;
        flex-direction: column;
        gap: 0.95rem;
        transition: border-color var(--duration-fast) ease;
    }
    .skill-group-card:hover { border-color: rgba(198, 244, 50, 0.35); }
    .skill-group-card.is-hidden { display: none; }
    .skill-group-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border);
    }
    .skill-group-title-wrap { display: flex; align-items: center; gap: 0.65rem; }
    .skill-group-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        background: rgba(198, 244, 50, 0.1);
        color: var(--accent-lime);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }
    .skill-group-title {
        font-family: var(--font-display);
        font-size: 1.05rem;
        font-weight: 700;
        color: #F8FAFC;
    }
    .skill-chip-cloud { display: flex; flex-wrap: wrap; gap: 0.45rem; }
    .skill-tech-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        padding: 0.38rem 0.75rem;
        background: var(--bg-primary);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 0.84rem;
        font-weight: 500;
        color: var(--text-primary);
        transition: border-color var(--duration-fast) ease, transform var(--duration-fast) ease;
    }
    .skill-tech-chip:hover {
        border-color: var(--accent-lime);
        transform: translateY(-1px);
    }
    .skill-tech-chip i { color: var(--accent-lime); font-size: 0.82rem; }

    @media (max-width: 820px) {
        .skills-grid-8 { grid-template-columns: 1fr; }
    }

    /* =========================================================
       SECTION 04 // ACADEMIC & RECOGNITION BENTO
    ========================================================= */
    .academic-split {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
        gap: 1.5rem;
        align-items: stretch;
    }
    .edu-card {
        background: var(--bg-card);
        color: #F8FAFC;
        border-radius: var(--radius-md);
        padding: clamp(1.5rem, 3.2vw, 2.25rem);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: 1px solid var(--border);
        transition: border-color var(--duration-fast) ease;
    }
    .edu-card:hover { border-color: var(--accent-lime); }
    .edu-label {
        font-family: var(--font-mono);
        font-size: 0.76rem;
        color: var(--accent-lime);
        font-weight: 700;
        margin-bottom: 0.55rem;
    }
    .edu-school {
        font-family: var(--font-display);
        font-size: 1.35rem;
        font-weight: 800;
        color: #FFFFFF;
        margin-bottom: 0.35rem;
    }
    .edu-degree {
        font-size: 0.98rem;
        color: #CBD5E1;
        font-weight: 500;
        margin-bottom: 1.25rem;
    }
    .edu-stats-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border);
    }
    .edu-stat-val {
        font-family: var(--font-display);
        font-size: 1.6rem;
        font-weight: 800;
        color: #FFFFFF;
    }
    .edu-stat-key {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--text-muted);
    }

    .ach-list { display: flex; flex-direction: column; gap: 0.85rem; }
    .ach-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.15rem 1.3rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        transition: border-color var(--duration-fast) ease;
    }
    .ach-item:hover { border-color: var(--accent-lime); }
    .ach-badge {
        min-width: 60px;
        text-align: center;
        font-family: var(--font-display);
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--accent-lime);
        background: rgba(198, 244, 50, 0.1);
        padding: 0.5rem 0.6rem;
        border-radius: var(--radius-sm);
        flex-shrink: 0;
    }
    .ach-item-title {
        font-family: var(--font-display);
        font-size: 1.02rem;
        font-weight: 700;
        color: #F8FAFC;
        margin-bottom: 0.25rem;
    }
    .ach-item-sub {
        font-size: 0.88rem;
        color: var(--text-secondary);
        line-height: 1.55;
    }

    @media (max-width: 860px) {
        .academic-split { grid-template-columns: 1fr; }
    }

    /* =========================================================
       SECTION 05 // CREDENTIALS & BLOG
    ========================================================= */
    .docs-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.35rem; }
    .doc-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.4rem;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease;
    }
    .doc-card:hover { border-color: var(--accent-lime); }
    .doc-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 0.85rem;
    }
    .doc-cat-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.22rem 0.55rem;
        border-radius: var(--radius-sm);
        background: var(--bg-primary);
        color: #F8FAFC;
    }
    .doc-cat-tag i { color: var(--accent-lime); }
    .doc-title {
        font-family: var(--font-display);
        font-size: 1.08rem;
        font-weight: 700;
        color: #F8FAFC;
        line-height: 1.35;
        margin-bottom: 0.35rem;
    }
    .doc-issuer {
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--accent-lime);
        margin-bottom: 0.55rem;
    }
    .doc-desc {
        font-size: 0.88rem;
        color: var(--text-secondary);
        line-height: 1.55;
        margin-bottom: 1.25rem;
        flex: 1;
    }
    .doc-actions {
        display: flex;
        gap: 0.5rem;
        padding-top: 0.95rem;
        border-top: 1px solid var(--border);
    }
    .doc-actions .btn { flex: 1; min-width: 0; }

    @media (max-width: 960px) {
        .docs-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 600px) {
        .docs-grid { grid-template-columns: 1fr; }
    }

    /* Blog Magazine Grid */
    .blog-magazine-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr);
        gap: 1.5rem;
    }
    .blog-lead-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease;
    }
    .blog-lead-card:hover { border-color: var(--accent-lime); }
    .blog-lead-media {
        position: relative;
        aspect-ratio: 16 / 9;
        background: var(--surface-ink);
        overflow: hidden;
        border-bottom: 1px solid var(--border);
    }
    .blog-lead-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .blog-lead-body {
        padding: 1.45rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .blog-lead-title {
        font-family: var(--font-display);
        font-size: 1.35rem;
        font-weight: 700;
        color: #F8FAFC;
        line-height: 1.3;
        margin-bottom: 0.55rem;
    }
    .blog-lead-desc {
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.62;
        margin-bottom: 1.25rem;
        flex: 1;
    }
    .blog-meta-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-family: var(--font-mono);
        font-size: 0.76rem;
        color: var(--text-muted);
    }

    .blog-secondary-stack {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .blog-mini-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease;
    }
    .blog-mini-card:hover { border-color: var(--accent-lime); }
    .blog-mini-title {
        font-family: var(--font-display);
        font-size: 1.05rem;
        font-weight: 700;
        color: #F8FAFC;
        line-height: 1.32;
        margin-bottom: 0.45rem;
    }
    .blog-mini-desc {
        color: var(--text-secondary);
        font-size: 0.86rem;
        line-height: 1.55;
        margin-bottom: 0.85rem;
    }

    @media (max-width: 860px) {
        .blog-magazine-grid { grid-template-columns: 1fr; }
    }

    /* =========================================================
       SECTION 07 // MINIMALIST CONTACT CALLOUT
    ========================================================= */
    .contact-callout-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: clamp(2rem, 5vw, 3.5rem);
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
        gap: 2.5rem;
        align-items: center;
    }
    .contact-giant-heading {
        font-family: var(--font-display);
        font-size: clamp(2.2rem, 4.4vw, 3.4rem);
        font-weight: 800;
        line-height: 1.08;
        letter-spacing: -0.03em;
        color: #F8FAFC;
        margin-bottom: 1rem;
    }
    .contact-cta-desc {
        color: var(--text-secondary);
        font-size: 1.02rem;
        line-height: 1.68;
        margin-bottom: 1.75rem;
        max-width: 48ch;
    }
    .contact-email-copy-bar {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 1.15rem;
        border-radius: var(--radius-sm);
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border-strong);
        color: #F8FAFC;
        font-family: var(--font-mono);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all var(--duration-fast) ease;
    }
    .contact-email-copy-bar:hover {
        border-color: var(--accent-lime);
        background: rgba(198, 244, 50, 0.08);
    }
    .contact-email-copy-bar i { color: var(--accent-lime); }

    .contact-card-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        background: var(--bg-primary);
        padding: 1.5rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
    }

    @media (max-width: 860px) {
        .contact-callout-card { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
@php
    $isVi = app()->getLocale() === 'vi';

    // Phase 3: Deduplicated 8-Category Skills Organizer
    $skillBuckets = [
        'languages' => ['label' => __('Programming Languages'), 'icon' => 'fas fa-code', 'items' => []],
        'frontend' => ['label' => __('Frontend Development'), 'icon' => 'fas fa-layer-group', 'items' => []],
        'backend' => ['label' => __('Backend Development'), 'icon' => 'fas fa-server', 'items' => []],
        'database' => ['label' => __('Database Management'), 'icon' => 'fas fa-database', 'items' => []],
        'api' => ['label' => __('API Development'), 'icon' => 'fas fa-network-wired', 'items' => []],
        'ai' => ['label' => __('AI / RAG / LLM'), 'icon' => 'fas fa-microchip', 'items' => []],
        'devops' => ['label' => __('DevOps & Deployment'), 'icon' => 'fas fa-cloud', 'items' => []],
        'tools' => ['label' => __('Tools & Platforms'), 'icon' => 'fas fa-screwdriver-wrench', 'items' => []],
    ];

    $seenNormalizedNames = [];
    $omitSubtopics = [
        'agile / scrum',
        'clean code & documentation',
        'mvc & solid principles',
        'design patterns',
        'session management',
        'password hashing',
        'php (backend)',
    ];

    foreach ($skills as $rawCat => $catItems) {
        foreach ($catItems as $sk) {
            $nameLow = strtolower(trim($sk->name));
            if (in_array($nameLow, $omitSubtopics, true)) {
                continue;
            }
            if (isset($seenNormalizedNames[$nameLow])) {
                continue;
            }
            $seenNormalizedNames[$nameLow] = true;

            $catLow = strtolower($rawCat);
            if ($catLow === 'languages') {
                $skillBuckets['languages']['items'][] = $sk;
            } elseif ($catLow === 'frontend' || str_contains($nameLow, 'next') || str_contains($nameLow, 'responsive') || str_contains($nameLow, 'blade')) {
                $skillBuckets['frontend']['items'][] = $sk;
            } elseif ($catLow === 'backend') {
                $skillBuckets['backend']['items'][] = $sk;
            } elseif ($catLow === 'database') {
                $skillBuckets['database']['items'][] = $sk;
            } elseif (str_contains($nameLow, 'rag') || str_contains($nameLow, 'llm') || str_contains($nameLow, 'vector')) {
                $skillBuckets['ai']['items'][] = $sk;
            } elseif (str_contains($nameLow, 'docker') || str_contains($nameLow, 'aws') || str_contains($nameLow, 'nginx') || str_contains($nameLow, 'cpanel') || str_contains($nameLow, 'ci/cd')) {
                $skillBuckets['devops']['items'][] = $sk;
            } elseif ($catLow === 'api development' || str_contains($nameLow, 'jwt') || str_contains($nameLow, 'rbac') || str_contains($nameLow, 'otp') || str_contains($nameLow, 'websocket') || str_contains($nameLow, 'payment')) {
                $skillBuckets['api']['items'][] = $sk;
            } else {
                $skillBuckets['tools']['items'][] = $sk;
            }
        }
    }
    $skillBuckets = array_filter($skillBuckets, fn($b) => count($b['items']) > 0);
@endphp

<!-- =========================================================
     HERO BENTO GRID (EDITORIAL ASYMMETRIC)
========================================================= -->
<section class="hero-editorial" id="hero">
    <div class="container hero-animate">
        <div class="hero-bento-grid">
            <!-- Cell 1: Main Name & Profile (8 cols) -->
            <div class="bento-card hero-main-card has-spotlight">
                <div class="bento-card-content">
                    <div class="hero-kicker-row">
                        <span class="mono-badge"><span class="kicker-pulse"></span> ARCHITECTURAL PORTFOLIO // 2026</span>
                        <span class="mono-label">HCMC &bull; VIETNAM</span>
                    </div>

                    <h1 class="hero-giant-name">
                        <span class="name-line">HỒ THÀNH</span>
                        <span class="name-line text-accent">THIỆN</span>
                    </h1>

                    <div class="hero-role-ticker" aria-label="Professional Roles">
                        <span class="ticker-badge"><i class="fas fa-terminal"></i> Fullstack Engineer</span>
                        <span class="ticker-dot">&bull;</span>
                        <span class="ticker-badge"><i class="fas fa-robot"></i> Applied AI &amp; RAG</span>
                        <span class="ticker-dot">&bull;</span>
                        <span class="ticker-badge"><i class="fab fa-laravel"></i> Laravel &amp; Spring Boot</span>
                    </div>

                    <p class="hero-impact-text">
                        {{ $isVi
                            ? 'Kỹ sư Fullstack tốt nghiệp loại Xuất sắc ngành CNTT tại UTH. Chuyên phát triển các hệ sinh thái web hiệu năng cao, kiến trúc API bảo mật và giải pháp AI/RAG thực chiến chạy production.'
                            : 'Fullstack Engineer graduated with Excellent Honors in IT from UTH. Specializing in high-performance web platforms, secure API architectures, and real-world applied AI/RAG systems.' }}
                    </p>

                    <div class="hero-actions-group">
                        <a href="#projects" class="btn btn-primary hero-btn-main">
                            <span>{{ __('View Projects') }}</span>
                            <i class="fas fa-arrow-down" aria-hidden="true"></i>
                        </a>
                        <a href="{{ asset('assets/HoThanhThien_CV_Brochure.pdf') }}" download class="btn btn-outline hero-btn-secondary">
                            <i class="fas fa-file-pdf" aria-hidden="true"></i>
                            <span>{{ $isVi ? 'Tải Brochure PDF' : 'Download CV Brochure' }}</span>
                        </a>
                        <button type="button" class="btn btn-outline hero-btn-secondary" onclick="openCmdPalette()" title="Ctrl+K">
                            <i class="fas fa-search" aria-hidden="true"></i>
                            <span>⌘K</span>
                        </button>
                    </div>

                    <div class="hero-connect-strip">
                        <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="connect-link">
                            <i class="fab fa-github"></i> <span>GitHub</span>
                        </a>
                        <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="connect-link">
                            <i class="fab fa-linkedin"></i> <span>LinkedIn</span>
                        </a>
                        <button type="button" class="connect-link" onclick="copyContactEmail()">
                            <i class="fas fa-copy"></i> <span>hothanhthien119@gmail.com</span>
                        </button>
                        <a href="tel:0375227764" class="connect-link" title="{{ $isVi ? 'Nhấp để gọi' : 'Click to call' }}">
                            <i class="fas fa-phone-alt"></i> <span>0375 &bull;&bull;&bull; 764</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cell 2: Live Status & Target Roles (4 cols) -->
            <div class="bento-card hero-status-card has-spotlight">
                <div class="bento-card-content">
                    <div class="status-top">
                        <span class="mono-idx">STATUS // 01</span>
                        <span class="live-radar-dot" aria-hidden="true"></span>
                    </div>

                    <div class="status-title">{{ $isVi ? 'Sẵn sàng phỏng vấn' : 'Available for Hire' }}</div>
                    <div class="status-sub">{{ $isVi ? 'Cơ hội Full-time / Kỹ sư chính thức' : 'Full-time Engineering Roles' }}</div>

                    <div class="status-meta-list">
                        <div class="status-meta-item">
                            <i class="fas fa-location-dot"></i>
                            <span>TP. Hồ Chí Minh (HCMC &bull; UTC+7)</span>
                        </div>
                        <div class="status-meta-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>UTH IT &bull; GPA 3.6 / 4.0</span>
                        </div>
                        <div class="status-meta-item">
                            <i class="fas fa-code"></i>
                            <span>Laravel &bull; Spring Boot &bull; React &bull; AI</span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm status-cta" onclick="openAiChatDrawer()">
                        <i class="fas fa-sparkles"></i>
                        <span>{{ $isVi ? 'Hỏi trợ lý AI về Thiện' : 'Ask AI about Thien' }}</span>
                    </button>
                </div>
            </div>

            <!-- Cell 3: Interactive Terminal Runtime (7 cols) -->
            <div class="bento-card hero-terminal-card has-spotlight">
                <div class="bento-card-content">
                    <div class="terminal-bar">
                        <div class="specimen-dots"><span></span><span></span><span></span></div>
                        <span class="specimen-host">hothanhthien.io.vn/api/engineer</span>
                        <span class="terminal-badge-live">LIVE RUNTIME</span>
                    </div>
                    <div class="terminal-code-body">
                        <div class="t-line"><span class="t-prompt">$</span> <span class="t-cmd">curl -s https://hothanhthien.io.vn/api/profile | jq</span></div>
                        <pre class="t-json">{
  <span class="t-key">"name"</span>: <span class="t-val">"Hồ Thành Thiện"</span>,
  <span class="t-key">"role"</span>: <span class="t-accent">"Fullstack Software &amp; AI Engineer"</span>,
  <span class="t-key">"education"</span>: <span class="t-val">"B.S. IT (UTH) — Cumulative GPA 3.6 / 4.0"</span>,
  <span class="t-key">"recent_gpa"</span>: <span class="t-val">"4.0 / 4.0 (Perfect Semester Honors)"</span>,
  <span class="t-key">"core_stack"</span>: [<span class="t-val">"Laravel 11"</span>, <span class="t-val">"Spring Boot"</span>, <span class="t-val">"ReactJS"</span>, <span class="t-val">"RAG/FastAPI"</span>],
  <span class="t-key">"deployment"</span>: <span class="t-val">"Docker Compose • Nginx • cPanel"</span>
}</pre>
                    </div>
                </div>
            </div>

            <!-- Cell 4: Blueprint Specs (5 cols) -->
            <div class="bento-card hero-blueprint-card has-spotlight">
                <div class="bento-card-content">
                    <div class="blueprint-top">
                        <span class="mono-idx">SPECS // 02</span>
                        <span class="mono-label">VERIFIED HIGHLIGHTS</span>
                    </div>

                    <div class="blueprint-stats-grid">
                        <div class="bp-stat">
                            <div class="bp-val">3.6</div>
                            <div class="bp-label">Cumulative GPA / 4.0</div>
                        </div>
                        <div class="bp-stat">
                            <div class="bp-val text-accent">4.0</div>
                            <div class="bp-label">Latest Semester GPA</div>
                        </div>
                    </div>

                    <div class="bp-highlights">
                        <div class="bp-pill"><i class="fas fa-award"></i> {{ $isVi ? 'Tốt nghiệp Xuất sắc UTH' : 'UTH Excellent Honors' }}</div>
                        <div class="bp-pill"><i class="fas fa-trophy"></i> {{ $isVi ? 'Giải Nhì Sáng tác Logo Khoa CNTT' : '2nd Prize Logo Design Award' }}</div>
                        <div class="bp-pill"><i class="fas fa-robot"></i> {{ $isVi ? 'Kiến trúc RAG Pipeline (TourRist)' : 'RAG Pipeline Architecture (TourRist)' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     TECH MARQUEE STREAM
========================================================= -->
@include('components.tech-marquee')

<!-- =========================================================
     SECTION 01 // SELECTED WORKS (FEATURED CASE STUDIES)
========================================================= -->
@if($featuredProjects->count())
<section class="section" id="projects">
    <div class="container">
        <x-section-header
            number="01"
            kicker="{{ $isVi ? 'DỰ ÁN TIÊU BIỂU' : 'SELECTED WORKS' }}"
            title="{!! __('heading_featured_projects') !!}"
            subtitle="{{ __('A curated selection of applications and systems built for real-world impact') }}">
            <x-slot:action>
                <a href="{{ route('projects.index') }}" class="btn btn-outline btn-sm">
                    <span>{{ __('View All Projects') }}</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </x-slot:action>
        </x-section-header>

        <div class="projects-case-stack" data-stagger>
            @foreach($featuredProjects->take(3) as $index => $project)
                @php
                    $cleanDesc = $project->clean_description ? $project->summary : $project->description;
                    $hostLabel = $project->demo_url
                        ? preg_replace('#^https?://#', '', rtrim($project->demo_url, '/'))
                        : ($project->github_url ? preg_replace('#^https?://#', '', rtrim($project->github_url, '/')) : 'project.spec');
                    $isPortfolio = $project->id === 1 || str_contains(strtolower($project->title), 'portfolio');
                    $isTourrist = $project->id === 2 || str_contains(strtolower($project->title), 'tourrist');
                    $isLaundry = $project->id === 3 || str_contains(strtolower($project->title), 'laundry');
                    $hasCustomImage = $project->image && !str_contains($project->image, 'placeholder') && !str_contains($project->image, 'stock') && file_exists(public_path('storage/' . $project->image));
                @endphp
                <article class="project-showcase-row {{ $index % 2 === 1 ? 'is-reversed' : '' }}" data-reveal>
                    <a href="{{ route('projects.show', $project->id) }}" class="project-showcase-media" aria-label="{{ $project->title }}">
                        @if($hasCustomImage)
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async">
                        @elseif($isPortfolio)
                            <div class="specimen-plate">
                                <div class="specimen-topbar">
                                    <span class="specimen-dots"><span></span><span></span><span></span></span>
                                    <span class="specimen-host">hothanhthien.io.vn</span>
                                </div>
                                <div class="specimen-terminal-body">
                                    <div class="terminal-line"><span class="terminal-prompt">$</span> curl -I https://hothanhthien.io.vn/</div>
                                    <div class="terminal-output"><span class="terminal-badge-ok">HTTP/2 200 OK</span> server: nginx/cpanel</div>
                                    <div class="terminal-line"><span class="terminal-prompt">$</span> php artisan --version</div>
                                    <div class="terminal-output">Laravel Framework <span class="terminal-highlight">11.x</span> (PHP 8.2)</div>
                                    <div class="terminal-tags-row">
                                        <span class="specimen-pill">Blade Engine</span>
                                        <span class="specimen-pill">MySQL/SQLite</span>
                                        <span class="specimen-pill">Bilingual VI/EN</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($isTourrist)
                            <div class="specimen-plate">
                                <div class="specimen-topbar">
                                    <span class="specimen-dots"><span></span><span></span><span></span></span>
                                    <span class="specimen-host">github.com/hoThanhThien/TourRist</span>
                                </div>
                                <div class="specimen-pipeline-body">
                                    <div class="terminal-line" style="color:var(--accent-sky); font-weight:600;">PIPELINE // RAG ARCHITECTURE</div>
                                    <div class="pipeline-flow">
                                        <div class="pipeline-node"><i class="fas fa-file-lines"></i> Docs</div>
                                        <div class="pipeline-arrow">&rarr;</div>
                                        <div class="pipeline-node"><i class="fas fa-layer-group"></i> Chunks</div>
                                        <div class="pipeline-arrow">&rarr;</div>
                                        <div class="pipeline-node active"><i class="fas fa-database"></i> ChromaDB</div>
                                        <div class="pipeline-arrow">&rarr;</div>
                                        <div class="pipeline-node"><i class="fas fa-robot"></i> LLM</div>
                                    </div>
                                    <div class="terminal-tags-row">
                                        <span class="specimen-pill">FastAPI</span>
                                        <span class="specimen-pill">Python</span>
                                        <span class="specimen-pill">Vector Search</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($isLaundry)
                            <div class="specimen-plate">
                                <div class="specimen-topbar">
                                    <span class="specimen-dots"><span></span><span></span><span></span></span>
                                    <span class="specimen-host">github.com/hoThanhThien/LT_JAVA...</span>
                                </div>
                                <div class="specimen-workflow-body">
                                    <div class="terminal-line" style="color:#A78BFA; font-weight:600;">STATE MACHINE // ORDER LIFECYCLE</div>
                                    <div class="workflow-steps">
                                        <div class="wf-step done">RECEIVED</div>
                                        <div class="wf-sep">&rsaquo;</div>
                                        <div class="wf-step active">WASHING</div>
                                        <div class="wf-sep">&rsaquo;</div>
                                        <div class="wf-step">READY</div>
                                        <div class="wf-sep">&rsaquo;</div>
                                        <div class="wf-step">DELIVERED</div>
                                    </div>
                                    <div class="terminal-tags-row">
                                        <span class="specimen-pill">Spring Boot</span>
                                        <span class="specimen-pill">NextJS</span>
                                        <span class="specimen-pill">JWT + RBAC</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="specimen-plate">
                                <div class="specimen-topbar">
                                    <span class="specimen-dots"><span></span><span></span><span></span></span>
                                    <span class="specimen-host">{{ $hostLabel }}</span>
                                </div>
                                <div class="specimen-terminal-body">
                                    <div class="terminal-line" style="color:var(--accent-cyan); font-weight:600;">ENTERPRISE // WEB PLATFORM</div>
                                    <div class="terminal-line"><span class="terminal-prompt">&bull;</span> Responsive Architecture</div>
                                    <div class="terminal-line"><span class="terminal-prompt">&bull;</span> Normalized Relational DB</div>
                                    <div class="terminal-tags-row">
                                        <span class="specimen-pill">PHP</span>
                                        <span class="specimen-pill">PostgreSQL</span>
                                        <span class="specimen-pill">Responsive UI</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </a>

                    <div class="project-showcase-info">
                        <div class="project-meta-top">
                            <span class="project-index-code">CASE STUDY // {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            @if($project->featured)
                                <span class="project-featured-tag">{{ __('Featured') }}</span>
                            @endif
                        </div>

                        <h3 class="project-showcase-title">
                            <a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a>
                        </h3>

                        <p class="project-showcase-desc">
                            {{ $cleanDesc }}
                        </p>

                        @if($project->technologies->count())
                        <div class="project-showcase-tags">
                            @foreach($project->technologies as $tech)
                                <span class="tag">{{ $tech->name }}</span>
                            @endforeach
                        </div>
                        @endif

                        <div class="project-action-bar">
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary btn-sm">
                                <span>{{ __('View Case Study') }}</span>
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                            @if($project->github_url)
                            <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                                <i class="fab fa-github" aria-hidden="true"></i>
                                <span>{{ __('Code') }}</span>
                            </a>
                            @endif
                            @if($project->demo_url)
                            <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                                <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                <span>{{ __('Demo') }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- =========================================================
     SECTION 02 // CAREER TIMELINE (WORK EXPERIENCE)
========================================================= -->
@if($experiences->count())
<section class="section section--alt" id="experience">
    <div class="container">
        <x-section-header
            number="02"
            kicker="{{ $isVi ? 'LỘ TRÌNH KINH NGHIỆM' : 'CAREER TIMELINE' }}"
            title="{!! __('heading_experience') !!}"
            subtitle="{{ __('My professional journey so far') }}" />

        <div class="exp-timeline" data-stagger>
            @foreach($experiences as $exp)
                <article class="exp-entry">
                    <div class="exp-entry-head">
                        <div>
                            <h3 class="exp-role-title">{{ $exp->localized_position }}</h3>
                            <div class="exp-company-name">{{ $exp->localized_company }}</div>
                        </div>
                        <span class="exp-period-badge">{{ $exp->formatted_date }}</span>
                    </div>

                    @php
                        $expSentences = array_values(array_filter(array_map('trim', explode('.', (string) $exp->localized_description))));
                    @endphp
                    @if(count($expSentences) > 1)
                        <ul class="exp-bullets">
                            @foreach($expSentences as $sentence)
                                <li>{{ $sentence }}.</li>
                            @endforeach
                        </ul>
                    @else
                        <p style="color:var(--text-secondary); font-size:0.94rem; line-height:1.68; margin-bottom:1rem;">{{ $exp->localized_description }}</p>
                    @endif

                    @if(count($exp->tech_tags))
                    <div class="exp-tech-row">
                        @foreach($exp->tech_tags as $tag)
                            <span class="tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- =========================================================
     SECTION 03 // TECHNICAL MATRIX (SKILLS BENTO)
========================================================= -->
@if(count($skillBuckets))
<section class="section" id="skills">
    <div class="container">
        <x-section-header
            number="03"
            kicker="{{ $isVi ? 'BẢNG KỸ NĂNG CHUYÊN MÔN' : 'TECHNICAL STACK' }}"
            title="{!! __('heading_my_skills') !!}"
            subtitle="{{ __('Technologies and architectural patterns I work with on production systems') }}" />

        <div class="skill-filter-row" role="tablist" aria-label="{{ __('heading_my_skills') }}">
            <button type="button" class="skill-filter-btn active" data-skill-filter="all">{{ __('All Stacks') }}</button>
            @foreach($skillBuckets as $bucketKey => $bucket)
                <button type="button" class="skill-filter-btn" data-skill-filter="{{ $bucketKey }}">{{ $bucket['label'] }}</button>
            @endforeach
        </div>

        <div class="skills-grid-8" data-stagger>
            @foreach($skillBuckets as $bucketKey => $bucket)
                <article class="skill-group-card" data-skill-group="{{ $bucketKey }}">
                    <div class="skill-group-head">
                        <div class="skill-group-title-wrap">
                            <span class="skill-group-icon" aria-hidden="true"><i class="{{ $bucket['icon'] }}"></i></span>
                            <h3 class="skill-group-title">{{ $bucket['label'] }}</h3>
                        </div>
                        <span class="mono-label">{{ count($bucket['items']) }} {{ $isVi ? 'CÔNG NGHỆ' : 'TECH' }}</span>
                    </div>

                    <div class="skill-chip-cloud">
                        @foreach($bucket['items'] as $skill)
                            <span class="skill-tech-chip">
                                <i class="{{ $skill->icon ?: 'fas fa-check' }}" aria-hidden="true"></i>
                                <span>{{ $skill->name }}</span>
                            </span>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- =========================================================
     SECTION 04 // ACADEMIC & RECOGNITION (EDUCATION & HONORS)
========================================================= -->
<section class="section section--alt" id="education">
    <div class="container">
        <x-section-header
            number="04"
            kicker="{{ $isVi ? 'HỌC VẤN & THÀNH TÍCH' : 'ACADEMIC & RECOGNITION' }}"
            title="{!! __('heading_education') !!} &amp; {!! __('heading_achievements') !!}"
            subtitle="{{ __('Formal academic background & verified credentials') }}" />

        <div class="academic-split" data-reveal>
            <div class="edu-card">
                <div>
                    <div class="edu-label">2023 – 2026 // {{ __('Information Technology') }}</div>
                    <h3 class="edu-school">{{ __('University of Transport Ho Chi Minh City') }}</h3>
                    <div class="edu-degree">{{ __("Bachelor's Degree Information Technology") }}</div>
                    <p style="font-size:0.9rem; color:var(--text-secondary); line-height:1.62;">
                        {{ __('Member Google Developer Chapter University of Transport Ho Chi Minh City') }}
                    </p>
                </div>

                <div class="edu-stats-row">
                    <div>
                        <div class="edu-stat-val">3.6 / 4.0</div>
                        <div class="edu-stat-key">{{ __('Cumulative GPA') }}</div>
                    </div>
                    <div>
                        <div class="edu-stat-val" style="color:var(--accent-lime)">{{ __('Excellent') }}</div>
                        <div class="edu-stat-key">{{ __('Classification') }}</div>
                    </div>
                </div>
            </div>

            <div class="ach-list">
                <div class="ach-item">
                    <div class="ach-badge">4.0</div>
                    <div>
                        <h3 class="ach-item-title">{{ __('GPA in Most Recent Semester — 4.0/4.0') }}</h3>
                        <p class="ach-item-sub">{{ $isVi ? 'Thành tích học tập kỳ gần nhất đạt điểm tuyệt đối 4.0/4.0.' : 'Perfect 4.0/4.0 GPA achieved in the most recent academic semester.' }}</p>
                    </div>
                </div>

                <div class="ach-item">
                    <div class="ach-badge">UTH</div>
                    <div>
                        <h3 class="ach-item-title">{{ __('Bachelor’s Degree – Excellent Graduation Classification') }}</h3>
                        <p class="ach-item-sub">{{ $isVi ? 'Tốt nghiệp Cử nhân chuyên ngành Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH).' : 'Graduated with Bachelor of Information Technology with Excellent Honors from UTH.' }}</p>
                    </div>
                </div>

                <div class="ach-item">
                    <div class="ach-badge"><i class="fas fa-award"></i></div>
                    <div>
                        <h3 class="ach-item-title">{{ __('Second Prize – IT Faculty Logo & Identity Design') }}</h3>
                        <p class="ach-item-sub">{{ $isVi ? 'Giải Nhì cuộc thi thiết kế sáng tạo biểu trưng nhận diện cho Khoa/Viện Công nghệ Thông tin.' : 'Second Prize in the official visual identity & emblem design contest for the IT Faculty.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     SECTION 05 // CREDENTIALS & BROCHURE
========================================================= -->
@if($certificates->count())
<section class="section" id="credentials">
    <div class="container">
        <x-section-header
            number="05"
            kicker="{{ $isVi ? 'HỒ SƠ NĂNG LỰC & CHỨNG CHỈ' : 'CREDENTIALS' }}"
            title="{!! __('heading_credentials') !!}"
            subtitle="{{ __('credentials_subtitle') }}">
            <x-slot:action>
                <a href="{{ route('certificates') }}" class="btn btn-outline btn-sm">
                    <span>{{ __('View All Documents') }}</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </x-slot:action>
        </x-section-header>

        <div class="docs-grid" data-stagger>
            @foreach($certificates as $cert)
                @php
                    $isBrochure = ($cert->category === 'brochure');
                @endphp
                <article class="doc-card">
                    <div class="doc-card-head">
                        <span class="doc-cat-tag">
                            <i class="{{ $cert->category_icon }}" aria-hidden="true"></i>
                            <span>{{ $cert->category_label }}</span>
                        </span>
                        @if($cert->issue_date)
                            <span class="mono-label">{{ $cert->issue_date }}</span>
                        @endif
                    </div>

                    <h3 class="doc-title">{{ $cert->title }}</h3>
                    @if($cert->issuer)
                        <div class="doc-issuer">{{ $cert->issuer }}</div>
                    @endif
                    <p class="doc-desc">{{ Str::limit($cert->description, 130) }}</p>

                    <div style="display:flex; justify-content:space-between; align-items:center; font-family:var(--font-mono); font-size:0.74rem; color:var(--text-muted); margin-bottom:0.85rem; padding-top:0.75rem; border-top:1px solid var(--border-subtle);">
                        @if($isBrochure)
                            <span><i class="fas fa-file-pdf" style="color:var(--accent-lime);"></i> PDF &bull; 402 KB</span>
                            <span style="color:#34D399;"><i class="fas fa-check-circle"></i> {{ $isVi ? 'Đã số hóa' : 'Verified' }}</span>
                        @else
                            <span><i class="fas fa-award" style="color:var(--accent-sky);"></i> {{ $isVi ? 'Bản chính quy UTH' : 'UTH Official Record' }}</span>
                            <span style="color:#FBBF24;"><i class="fas fa-shield-alt"></i> {{ $isVi ? 'Scan theo yêu cầu' : 'Scan on request' }}</span>
                        @endif
                    </div>

                    <div class="doc-actions">
                        @if($isBrochure)
                            <button type="button" class="btn btn-primary btn-sm" onclick="openHomeCertModal('{{ $cert->file_url }}', '{{ addslashes($cert->title) }}', '{{ $cert->file_type }}')">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                                <span>{{ $isVi ? 'Xem PDF' : 'View PDF' }}</span>
                            </button>
                            <a href="{{ $cert->file_url }}" download class="btn btn-outline btn-sm">
                                <i class="fas fa-download" aria-hidden="true"></i>
                                <span>{{ __('Download') }}</span>
                            </a>
                        @else
                            <button type="button" class="btn btn-navy btn-sm" onclick="openCertDetailModal('{{ addslashes($cert->title) }}', '{{ addslashes($cert->issuer ?? 'UTH') }}', '{{ addslashes($cert->description) }}')">
                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                <span>{{ $isVi ? 'Xem hồ sơ' : 'View Details' }}</span>
                            </button>
                            <a href="{{ route('contact') }}?subject={{ urlencode($cert->title) }}" class="btn btn-outline btn-sm">
                                <i class="fas fa-envelope" aria-hidden="true"></i>
                                <span>{{ $isVi ? 'Yêu cầu scan' : 'Request Scan' }}</span>
                            </a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- =========================================================
     SECTION 06 // ENGINEERING JOURNAL (BLOG)
========================================================= -->
@if($recentPosts->count())
<section class="section section--alt" id="blog">
    <div class="container">
        <x-section-header
            number="06"
            kicker="{{ $isVi ? 'TẠP CHÍ KỸ THUẬT' : 'ENGINEERING JOURNAL' }}"
            title="{!! __('heading_recent_articles') !!}"
            subtitle="{{ __('Thoughts, architectural explorations and technical insights') }}">
            <x-slot:action>
                <a href="{{ route('blog.index') }}" class="btn btn-outline btn-sm">
                    <span>{{ __('Read All Articles') }}</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </x-slot:action>
        </x-section-header>

        @php
            $leadPost = $recentPosts->first();
            $secondaryPosts = $recentPosts->skip(1);
        @endphp

        <div class="blog-magazine-grid">
            @if($leadPost)
            <a href="{{ route('blog.show', $leadPost->slug) }}" class="blog-lead-card" data-reveal>
                <div class="blog-lead-media">
                    @if($leadPost->thumbnail_url)
                        <img src="{{ $leadPost->thumbnail_url }}" alt="{{ $leadPost->title }}" loading="lazy">
                    @else
                        <div class="specimen-plate" style="height:100%; min-height:220px;">
                            <div class="specimen-topbar">
                                <span class="specimen-dots"><span></span><span></span><span></span></span>
                                <span class="specimen-host">journal.entry</span>
                            </div>
                            <div class="specimen-terminal-body">
                                <div class="terminal-line" style="color:var(--accent-lime); font-weight:700;">ARTICLE // FEATURED EXPLORATION</div>
                                <div class="terminal-line">{{ Str::limit($leadPost->title, 48) }}</div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="blog-lead-body">
                    <div class="blog-meta-row" style="margin-bottom:0.75rem;">
                        <span><i class="fas fa-calendar-alt"></i> {{ $leadPost->published_at ? $leadPost->published_at->format('M d, Y') : $leadPost->created_at->format('M d, Y') }}</span>
                        <span><i class="fas fa-clock"></i> {{ max(1, ceil(str_word_count(strip_tags($leadPost->content)) / 200)) }} {{ __('min read') }}</span>
                    </div>

                    <h3 class="blog-lead-title">{{ $leadPost->title }}</h3>
                    <p class="blog-lead-desc">
                        {{ $leadPost->excerpt ? Str::limit(strip_tags($leadPost->excerpt), 160) : Str::limit(strip_tags($leadPost->content), 160) }}
                    </p>

                    <div style="display:flex; align-items:center; gap:0.45rem; color:var(--accent-lime); font-family:var(--font-mono); font-size:0.8rem; font-weight:700;">
                        <span>{{ $isVi ? 'Đọc bài viết' : 'Read Article' }}</span>
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
            @endif

            <div class="blog-secondary-stack">
                @foreach($secondaryPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="blog-mini-card" data-reveal>
                        <div class="blog-meta-row" style="margin-bottom:0.5rem;">
                            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                            <span>&bull;</span>
                            <span>{{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} {{ __('min read') }}</span>
                        </div>
                        <h4 class="blog-mini-title">{{ $post->title }}</h4>
                        <p class="blog-mini-desc">
                            {{ $post->excerpt ? Str::limit(strip_tags($post->excerpt), 110) : Str::limit(strip_tags($post->content), 110) }}
                        </p>
                        <div style="color:var(--accent-lime); font-family:var(--font-mono); font-size:0.76rem; font-weight:700;">
                            <span>{{ $isVi ? 'Đọc tiếp' : 'Read more' }} &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- =========================================================
     SECTION 07 // MINIMALIST CONTACT CALLOUT
========================================================= -->
<section class="section" id="contact">
    <div class="container">
        <div class="contact-callout-card" data-reveal>
            <div>
                <div class="section-kicker">
                    <span class="mono-idx">07 //</span>
                    <span>{{ $isVi ? 'KẾT NỐI & HỢP TÁC' : 'GET IN TOUCH' }}</span>
                </div>
                <h2 class="contact-giant-heading">
                    {!! $isVi ? 'Cùng kiến tạo những giải pháp vượt trội.' : 'Let’s build something exceptional together.' !!}
                </h2>
                <p class="contact-cta-desc">
                    {{ $isVi
                        ? 'Tôi hiện đang tìm kiếm cơ hội việc làm Fullstack / Backend / Applied AI tại TP.HCM. Rất mong được kết nối và trao đổi cùng Quý công ty!'
                        : 'I am currently open to Fullstack / Backend / Applied AI opportunities in Ho Chi Minh City. Let\'s discuss how I can contribute to your engineering team!' }}
                </p>

                <button type="button" class="contact-email-copy-bar" onclick="copyContactEmail()" title="{{ $isVi ? 'Nhấp để sao chép' : 'Click to copy email' }}">
                    <i class="fas fa-copy"></i>
                    <span>hothanhthien119@gmail.com</span>
                    <span class="mono-label" style="color:var(--accent-lime); font-size:0.72rem; margin-left:0.35rem;">[1-CLICK COPY]</span>
                </button>
            </div>

            <div class="contact-card-actions">
                <a href="{{ route('contact') }}" class="btn btn-primary" style="width:100%; justify-content:center;">
                    <i class="fas fa-paper-plane"></i>
                    <span>{{ __('Contact Me') }}</span>
                </a>
                <a href="{{ asset('assets/HoThanhThien_CV_Brochure.pdf') }}" download class="btn btn-outline" style="width:100%; justify-content:center;">
                    <i class="fas fa-download"></i>
                    <span>{{ $isVi ? 'Tải Hồ Sơ Năng Lực (PDF)' : 'Download Brochure (PDF)' }}</span>
                </a>
                <div style="display:flex; justify-content:center; gap:1.25rem; padding-top:0.65rem; border-top:1px solid var(--border);">
                    <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="connect-link"><i class="fab fa-github"></i> GitHub</a>
                    <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="connect-link"><i class="fab fa-linkedin"></i> LinkedIn</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Home Certificate Preview Modal -->
<div class="cert-modal" id="homeCertModal" role="dialog" aria-modal="true" aria-labelledby="homeCertModalTitleText" onclick="closeHomeCertModal(event)" style="display:none; position:fixed; inset:0; z-index:2800; background:rgba(6,9,15,0.78); backdrop-filter:blur(8px); align-items:center; justify-content:center; padding:1rem;">
    <div class="cert-modal-box" onclick="event.stopPropagation()" style="background:#0B111E; border:1px solid var(--border-strong); border-radius:var(--radius-lg); width:100%; max-width:980px; height:88vh; max-height:840px; display:flex; flex-direction:column; overflow:hidden; box-shadow:var(--shadow-lg);">
        <div style="display:flex; align-items:center; justify-content:space-between; padding:0.85rem 1.25rem; border-bottom:1px solid var(--border); background:#0B111E;">
            <div style="display:flex; align-items:center; gap:0.55rem; font-family:var(--font-display); font-size:1rem; font-weight:700; color:#F8FAFC;">
                <i class="fas fa-file-pdf" style="color:var(--accent-lime)"></i>
                <span id="homeCertModalTitleText">{{ $isVi ? 'Tài liệu' : 'Document' }}</span>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <a id="homeCertModalDownloadBtn" href="#" download class="btn btn-primary btn-sm">
                    <i class="fas fa-download"></i>
                    <span>{{ __('Download') }}</span>
                </a>
                <button type="button" class="ai-btn-close" onclick="closeHomeCertModalDirect()" aria-label="{{ $isVi ? 'Đóng' : 'Close' }}">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        </div>
        <div id="homeCertModalBody" style="flex:1; background:#06090F; display:flex; align-items:center; justify-content:center; overflow:auto; padding:0.75rem;"></div>
    </div>
</div>

<!-- Home Certificate Detail Modal -->
<div class="cert-modal" id="homeCertDetailModal" role="dialog" aria-modal="true" aria-labelledby="homeCertDetailTitle" onclick="closeCertDetailModal(event)" style="display:none; position:fixed; inset:0; z-index:2800; background:rgba(6,9,15,0.78); backdrop-filter:blur(8px); align-items:center; justify-content:center; padding:1rem;">
    <div class="cert-modal-box" onclick="event.stopPropagation()" style="background:#0B111E; border:1px solid var(--border-strong); border-radius:var(--radius-lg); width:100%; max-width:560px; display:flex; flex-direction:column; overflow:hidden; box-shadow:var(--shadow-lg);">
        <div style="display:flex; align-items:center; justify-content:space-between; padding:1rem 1.35rem; border-bottom:1px solid var(--border);">
            <div style="display:flex; align-items:center; gap:0.55rem; font-family:var(--font-display); font-size:1.05rem; font-weight:700; color:#F8FAFC;">
                <i class="fas fa-award" style="color:var(--accent-lime)"></i>
                <span id="homeCertDetailTitle">{{ $isVi ? 'Chi tiết hồ sơ' : 'Record Details' }}</span>
            </div>
            <button type="button" class="ai-btn-close" onclick="closeCertDetailModalDirect()" aria-label="{{ $isVi ? 'Đóng' : 'Close' }}">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:1.45rem;">
            <div id="homeCertDetailIssuer" style="font-family:var(--font-mono); font-size:0.78rem; color:var(--accent-lime); font-weight:700; margin-bottom:0.65rem;"></div>
            <p id="homeCertDetailDesc" style="color:var(--text-secondary); font-size:0.94rem; line-height:1.68; margin-bottom:1.5rem;"></p>
            <div style="display:flex; gap:0.65rem;">
                <a href="{{ route('contact') }}" class="btn btn-primary btn-sm" style="flex:1; justify-content:center;">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $isVi ? 'Liên hệ xác minh / Yêu cầu scan' : 'Request Official Scan' }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
/* Skill Group Filter Tabs */
document.querySelectorAll('[data-skill-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.getAttribute('data-skill-filter');
        document.querySelectorAll('[data-skill-filter]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('[data-skill-group]').forEach(card => {
            const group = card.getAttribute('data-skill-group');
            const match = target === 'all' || group === target;
            card.classList.toggle('is-hidden', !match);
        });
    });
});

/* Home Certificate Modal Helpers */
function openHomeCertModal(url, title, type) {
    const modal = document.getElementById('homeCertModal');
    const titleText = document.getElementById('homeCertModalTitleText');
    const downloadBtn = document.getElementById('homeCertModalDownloadBtn');
    const body = document.getElementById('homeCertModalBody');

    if (!modal || !body) return;
    if (titleText) titleText.textContent = title;
    if (downloadBtn) { downloadBtn.href = url; downloadBtn.download = title; }

    const isPdf = (type === 'pdf') || url.toLowerCase().endsWith('.pdf');
    if (isPdf) {
        body.innerHTML = '<iframe src="' + url + '#toolbar=1&navpanes=0" style="width:100%; height:100%; border:none; border-radius:4px; background:#fff;" title="' + title + '"></iframe>';
    } else {
        body.innerHTML = '<img src="' + url + '" alt="' + title + '" style="max-width:100%; max-height:100%; object-fit:contain; border-radius:4px;">';
    }
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeHomeCertModal(e) {
    if (e.target.id === 'homeCertModal') closeHomeCertModalDirect();
}

function closeHomeCertModalDirect() {
    const modal = document.getElementById('homeCertModal');
    const body = document.getElementById('homeCertModalBody');
    if (modal) modal.style.display = 'none';
    if (body) body.innerHTML = '';
    document.body.style.overflow = '';
}

function openCertDetailModal(title, issuer, desc) {
    const modal = document.getElementById('homeCertDetailModal');
    const titleEl = document.getElementById('homeCertDetailTitle');
    const issuerEl = document.getElementById('homeCertDetailIssuer');
    const descEl = document.getElementById('homeCertDetailDesc');

    if (!modal) return;
    if (titleEl) titleEl.textContent = title;
    if (issuerEl) issuerEl.textContent = 'ĐƠN VỊ CẤP // ' + issuer;
    if (descEl) descEl.textContent = desc;

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeCertDetailModal(e) {
    if (e.target.id === 'homeCertDetailModal') closeCertDetailModalDirect();
}

function closeCertDetailModalDirect() {
    const modal = document.getElementById('homeCertDetailModal');
    if (modal) modal.style.display = 'none';
    document.body.style.overflow = '';
}
</script>
@endsection
