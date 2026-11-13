@extends('layouts.app')

@section('title', app()->getLocale() === 'vi' ? 'Hồ Thành Thiện | Kỹ sư Full-Stack Web Developer' : 'Ho Thanh Thien | Fullstack Developer')

@section('styles')
<style>
    /* =========================================================
       DARK NAVY & BLUE/CYAN HERO SECTION
    ========================================================= */
    .hero-navy {
        background:
            radial-gradient(circle at 82% 18%, rgba(37, 99, 235, 0.22) 0%, transparent 48%),
            radial-gradient(circle at 14% 82%, rgba(6, 182, 212, 0.12) 0%, transparent 45%),
            linear-gradient(180deg, var(--navy-deep) 0%, var(--navy-surface) 100%);
        color: #F8FAFC;
        padding: clamp(3rem, 6vw, 5.25rem) 0 clamp(3rem, 5.5vw, 4.5rem);
        border-bottom: 1px solid var(--navy-border);
        position: relative;
        overflow: hidden;
    }
    .hero-navy::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(56, 189, 248, 0.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(56, 189, 248, 0.035) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }
    .hero-navy > .container {
        position: relative;
        z-index: 1;
    }

    .hero-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.85rem;
        padding-bottom: 1.4rem;
        margin-bottom: clamp(1.75rem, 3.5vw, 2.75rem);
        border-bottom: 1px solid rgba(148, 163, 184, 0.16);
        font-family: var(--font-mono);
        font-size: 0.78rem;
        color: #94A3B8;
    }

    .availability-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.3rem 0.8rem;
        border-radius: 999px;
        background: rgba(15, 118, 110, 0.18);
        border: 1px solid rgba(45, 212, 191, 0.35);
        color: #99F6E4;
        font-family: var(--font-sans);
        font-size: 0.8rem;
        font-weight: 600;
    }
    .availability-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2DD4BF;
        box-shadow: 0 0 0 3px rgba(45, 212, 191, 0.22);
    }

    .hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.32fr) minmax(0, 0.92fr);
        gap: clamp(2rem, 4.5vw, 4rem);
        align-items: center;
    }

    .hero-role-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        font-family: var(--font-mono);
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--accent-sky);
        margin-bottom: 0.85rem;
    }
    .hero-role-tag::before {
        content: '';
        width: 18px;
        height: 2px;
        background: var(--accent-cyan);
    }

    .hero-name-title {
        font-family: var(--font-display);
        font-size: clamp(2.5rem, 5.4vw, 4.3rem);
        font-weight: 800;
        line-height: 1.06;
        letter-spacing: -0.035em;
        color: #FFFFFF;
        margin-bottom: 0.75rem;
    }
    .hero-positioning {
        font-family: var(--font-display);
        font-size: clamp(1.2rem, 2.2vw, 1.65rem);
        font-weight: 600;
        color: #38BDF8;
        margin-bottom: 1.25rem;
        line-height: 1.3;
    }

    .hero-summary {
        font-size: clamp(0.98rem, 1.4vw, 1.08rem);
        line-height: 1.72;
        color: #CBD5E1;
        max-width: 58ch;
        margin-bottom: 1.85rem;
    }

    .hero-cta-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.85rem;
    }

    .hero-social-strip {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem 1.4rem;
        padding-top: 1.35rem;
        border-top: 1px solid rgba(148, 163, 184, 0.16);
    }
    .hero-social-link {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        color: #94A3B8;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        transition: color var(--duration-fast) ease;
    }
    .hero-social-link i { color: var(--accent-sky); }
    .hero-social-link:hover { color: #FFFFFF; }

    /* Right Hero Dossier Card */
    .dossier-card {
        background: rgba(19, 30, 54, 0.82);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(56, 189, 248, 0.22);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }
    .dossier-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.3rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.14);
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: #94A3B8;
    }
    .dossier-top-dot {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: var(--accent-sky);
    }
    .dossier-head {
        display: flex;
        align-items: center;
        gap: 1.05rem;
        padding: 1.35rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.14);
    }
    .dossier-avatar-mark {
        width: 58px;
        height: 58px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-cyan) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-display);
        font-size: 1.3rem;
        font-weight: 800;
        color: #FFFFFF;
        flex-shrink: 0;
    }
    .dossier-name {
        font-family: var(--font-display);
        font-size: 1.15rem;
        font-weight: 700;
        color: #FFFFFF;
        margin-bottom: 0.15rem;
    }
    .dossier-sub {
        font-family: var(--font-mono);
        font-size: 0.78rem;
        color: var(--accent-sky);
    }
    .dossier-rows { padding: 0.4rem 1.35rem 1.15rem; }
    .dossier-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.72rem 0;
        border-bottom: 1px solid rgba(148, 163, 184, 0.12);
        font-size: 0.85rem;
    }
    .dossier-row:last-child { border-bottom: none; padding-bottom: 0.25rem; }
    .dossier-key { font-family: var(--font-mono); font-size: 0.74rem; color: #94A3B8; flex-shrink: 0; }
    .dossier-val { color: #F8FAFC; font-weight: 500; text-align: right; }
    .dossier-val a { color: var(--accent-sky); text-decoration: none; }
    .dossier-val a:hover { text-decoration: underline; }

    /* =========================================================
       FEATURED PROJECTS (Distinctive Alternating Showcase)
    ========================================================= */
    .project-showcase-list {
        display: flex;
        flex-direction: column;
        gap: clamp(2.25rem, 4.5vw, 3.25rem);
    }
    .project-showcase-row {
        display: grid;
        grid-template-columns: minmax(0, 1.08fr) minmax(0, 0.92fr);
        gap: clamp(1.5rem, 4vw, 3rem);
        align-items: center;
        padding-bottom: clamp(2.25rem, 4.5vw, 3.25rem);
        border-bottom: 1px solid var(--border);
    }
    .project-showcase-row:last-child { padding-bottom: 0; border-bottom: none; }
    .project-showcase-row.is-reversed .project-showcase-media { order: 2; }
    .project-showcase-row.is-reversed .project-showcase-info { order: 1; }

    .project-showcase-media {
        display: block;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-strong);
        overflow: hidden;
        background: var(--navy-deep);
        aspect-ratio: 16 / 10;
        box-shadow: var(--shadow-md);
        text-decoration: none;
        transition: transform var(--duration-base) var(--ease-out), box-shadow var(--duration-base) var(--ease-out), border-color var(--duration-fast) ease;
    }
    .project-showcase-media:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent);
    }
    .project-showcase-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .project-meta-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.6rem;
    }
    .project-index-code { font-family: var(--font-mono); font-size: 0.76rem; font-weight: 600; color: var(--accent); }
    .project-featured-tag {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--text-muted);
        border: 1px solid var(--border);
        background: var(--bg-card);
        padding: 0.16rem 0.5rem;
        border-radius: var(--radius-sm);
    }
    .project-showcase-title {
        font-family: var(--font-display);
        font-size: clamp(1.42rem, 2.5vw, 1.88rem);
        font-weight: 800;
        line-height: 1.22;
        margin-bottom: 0.75rem;
    }
    .project-showcase-title a { color: var(--text-primary); text-decoration: none; }
    .project-showcase-title a:hover { color: var(--accent); }
    .project-showcase-desc { color: var(--text-secondary); font-size: 0.96rem; line-height: 1.68; margin-bottom: 1.1rem; }
    .project-showcase-tags { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-bottom: 1.35rem; }
    .project-action-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.6rem;
        margin-top: auto;
        padding-top: 1.15rem;
        border-top: 1px solid var(--border);
    }
    .project-action-bar .btn {
        min-height: 38px;
        padding: 0.45rem 0.95rem;
        font-size: 0.84rem;
    }

    /* Bespoke Project Architecture Specimen Previews */
    .specimen-plate {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 250px;
        background: linear-gradient(145deg, #070B14 0%, #0F172A 100%);
        color: #F8FAFC;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1.25rem 1.4rem;
        overflow: hidden;
    }
    .specimen-plate::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(56, 189, 248, 0.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(56, 189, 248, 0.035) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
    }
    .specimen-topbar {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.14);
    }
    .specimen-dots { display: inline-flex; align-items: center; gap: 5px; }
    .specimen-dots span { width: 8px; height: 8px; border-radius: 50%; }
    .specimen-dots span:nth-child(1) { background: #EF4444; }
    .specimen-dots span:nth-child(2) { background: #F59E0B; }
    .specimen-dots span:nth-child(3) { background: #10B981; }
    .specimen-host { font-family: var(--font-mono); font-size: 0.72rem; color: #94A3B8; }

    .specimen-terminal-body, .specimen-pipeline-body, .specimen-workflow-body {
        position: relative;
        z-index: 1;
        margin: auto 0;
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        font-family: var(--font-mono);
        font-size: 0.78rem;
    }
    .terminal-line { color: #CBD5E1; }
    .terminal-prompt { color: var(--accent-sky); font-weight: 700; margin-right: 0.35rem; }
    .terminal-output { color: #94A3B8; padding-left: 0.85rem; font-size: 0.74rem; }
    .terminal-badge-ok {
        display: inline-block;
        padding: 0.1rem 0.4rem;
        border-radius: 3px;
        background: rgba(16, 185, 129, 0.2);
        color: #34D399;
        font-weight: 600;
        margin-right: 0.35rem;
    }
    .terminal-highlight { color: #F8FAFC; font-weight: 600; }
    .terminal-tags-row { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.5rem; }
    .specimen-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.55rem;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(148, 163, 184, 0.18);
        font-size: 0.7rem;
        color: #E2E8F0;
    }

    .pipeline-flow, .workflow-steps {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin: 0.5rem 0;
    }
    .pipeline-node, .wf-step {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        background: rgba(15, 23, 42, 0.85);
        border: 1px solid rgba(148, 163, 184, 0.22);
        font-size: 0.72rem;
        color: #CBD5E1;
    }
    .pipeline-node.active, .wf-step.active {
        background: rgba(56, 189, 248, 0.15);
        border-color: var(--accent-sky);
        color: var(--accent-sky);
        font-weight: 600;
    }
    .wf-step.done {
        background: rgba(16, 185, 129, 0.12);
        border-color: #10B981;
        color: #34D399;
    }
    .pipeline-arrow, .wf-sep { color: #64748B; font-weight: 700; }

    /* =========================================================
       EXPERIENCE TIMELINE
    ========================================================= */
    .exp-timeline {
        border-left: 2px solid var(--border-strong);
        padding-left: clamp(1.25rem, 3vw, 2rem);
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
    }
    .exp-entry {
        position: relative;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: clamp(1.35rem, 2.8vw, 1.8rem);
    }
    .exp-entry::before {
        content: '';
        position: absolute;
        left: calc(-1 * clamp(1.25rem, 3vw, 2rem) - 6px);
        top: 1.75rem;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--accent-cyan);
        border: 2px solid #FFFFFF;
        box-shadow: 0 0 0 2px var(--accent);
    }
    .exp-entry-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.65rem;
        margin-bottom: 0.75rem;
    }
    .exp-role-title { font-family: var(--font-display); font-size: 1.18rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.2rem; }
    .exp-company-name { font-weight: 600; color: var(--accent); font-size: 0.96rem; }
    .exp-period-badge {
        font-family: var(--font-mono);
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--text-secondary);
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        padding: 0.25rem 0.65rem;
        border-radius: var(--radius-sm);
        white-space: nowrap;
    }
    .exp-bullets { margin: 0.85rem 0 1rem 1.15rem; color: var(--text-secondary); font-size: 0.93rem; line-height: 1.65; }
    .exp-bullets li { margin-bottom: 0.38rem; }
    .exp-tech-row { display: flex; flex-wrap: wrap; gap: 0.4rem; padding-top: 0.85rem; border-top: 1px solid var(--border); }

    /* =========================================================
       TECHNICAL SKILLS (8 CLEAN GROUPS, ZERO PERCENTAGES)
    ========================================================= */
    .skill-filter-row { display: flex; flex-wrap: wrap; gap: 0.45rem; margin-bottom: 1.65rem; }
    .skill-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.42rem 0.85rem;
        border-radius: var(--radius-sm);
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-size: 0.84rem;
        font-weight: 600;
        cursor: pointer;
        transition: border-color var(--duration-fast) ease, color var(--duration-fast) ease, background var(--duration-fast) ease;
    }
    .skill-filter-btn:hover { border-color: var(--accent); color: var(--accent); }
    .skill-filter-btn.active { background: var(--navy-surface); border-color: var(--navy-surface); color: #FFFFFF; }

    .skills-grid-8 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem;
    }
    .skill-group-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.35rem 1.45rem;
        display: flex;
        flex-direction: column;
        gap: 0.9rem;
    }
    .skill-group-card.is-hidden { display: none; }
    .skill-group-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border);
    }
    .skill-group-title-wrap { display: flex; align-items: center; gap: 0.6rem; }
    .skill-group-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        background: var(--accent-soft);
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.86rem;
    }
    .skill-group-title { font-family: var(--font-display); font-size: 1.04rem; font-weight: 700; color: var(--text-primary); }
    .skill-chip-cloud { display: flex; flex-wrap: wrap; gap: 0.42rem; }
    .skill-tech-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        padding: 0.36rem 0.7rem;
        background: var(--bg-primary);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 0.84rem;
        font-weight: 500;
        color: var(--text-primary);
    }
    .skill-tech-chip i { color: var(--accent); font-size: 0.82rem; }

    /* =========================================================
       ACHIEVEMENTS & EDUCATION (Combined Clean Academic Showcase)
    ========================================================= */
    .academic-split {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
        gap: 1.5rem;
        align-items: stretch;
    }
    .edu-card {
        background: var(--navy-surface);
        color: #F8FAFC;
        border-radius: var(--radius-md);
        padding: clamp(1.5rem, 3vw, 2rem);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: 1px solid var(--navy-border);
    }
    .edu-label { font-family: var(--font-mono); font-size: 0.74rem; color: var(--accent-sky); margin-bottom: 0.5rem; }
    .edu-school { font-family: var(--font-display); font-size: 1.32rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.35rem; }
    .edu-degree { font-size: 0.98rem; color: #CBD5E1; font-weight: 500; margin-bottom: 1.25rem; }
    .edu-stats-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem;
        padding-top: 1.15rem;
        border-top: 1px solid rgba(148, 163, 184, 0.16);
    }
    .edu-stat-val { font-family: var(--font-display); font-size: 1.35rem; font-weight: 800; color: #FFFFFF; }
    .edu-stat-key { font-family: var(--font-mono); font-size: 0.72rem; color: #94A3B8; }

    .ach-list { display: flex; flex-direction: column; gap: 0.85rem; }
    .ach-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.15rem 1.3rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    .ach-badge {
        min-width: 58px;
        text-align: center;
        font-family: var(--font-display);
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--accent);
        background: var(--accent-soft);
        padding: 0.45rem 0.5rem;
        border-radius: var(--radius-sm);
        flex-shrink: 0;
    }
    .ach-item-title { font-family: var(--font-display); font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.2rem; }
    .ach-item-sub { font-size: 0.86rem; color: var(--text-secondary); line-height: 1.5; }

    /* =========================================================
       CREDENTIALS & BLOG
    ========================================================= */
    .docs-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.35rem; }
    .doc-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.35rem;
        display: flex;
        flex-direction: column;
    }
    .doc-card-head { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.85rem; }
    .doc-cat-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.22rem 0.55rem;
        border-radius: var(--radius-sm);
        background: var(--bg-secondary);
        color: var(--text-primary);
    }
    .doc-cat-tag i { color: var(--accent); }
    .doc-title { font-family: var(--font-display); font-size: 1.06rem; font-weight: 700; color: var(--text-primary); line-height: 1.35; margin-bottom: 0.35rem; }
    .doc-issuer { font-size: 0.84rem; font-weight: 600; color: var(--accent); margin-bottom: 0.55rem; }
    .doc-desc { font-size: 0.88rem; color: var(--text-secondary); line-height: 1.55; margin-bottom: 1.15rem; flex: 1; }
    .doc-actions { display: flex; gap: 0.5rem; padding-top: 0.9rem; border-top: 1px solid var(--border); }
    .doc-actions .btn { flex: 1; min-width: 0; }
    .doc-pending-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        width: 100%;
        padding: 0.52rem 0.85rem;
        border-radius: var(--radius-sm);
        background: var(--bg-secondary);
        border: 1px dashed var(--border-strong);
        color: var(--text-muted);
        font-family: var(--font-mono);
        font-size: 0.76rem;
    }

    .blog-magazine-grid { display: grid; grid-template-columns: minmax(0, 1.12fr) minmax(0, 0.88fr); gap: 1.5rem; align-items: stretch; }
    .blog-lead-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }
    .blog-lead-card:hover { border-color: var(--accent); box-shadow: var(--shadow-md); }
    .blog-lead-media { aspect-ratio: 16 / 9; background: var(--navy-deep); overflow: hidden; }
    .blog-lead-media img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .blog-typo-cover {
        width: 100%;
        height: 100%;
        padding: 1.5rem 1.75rem;
        background: linear-gradient(135deg, var(--navy-deep) 0%, var(--navy-elevated) 100%);
        color: #FFFFFF;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .blog-lead-body { padding: 1.4rem; flex: 1; display: flex; flex-direction: column; }
    .blog-meta-line { display: flex; flex-wrap: wrap; gap: 0.65rem; font-family: var(--font-mono); font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.55rem; }
    .blog-lead-title { font-family: var(--font-display); font-size: 1.28rem; font-weight: 700; color: var(--text-primary); line-height: 1.32; margin-bottom: 0.6rem; }
    .blog-stack-list { display: flex; flex-direction: column; gap: 1rem; }
    .blog-stack-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        flex: 1;
        justify-content: center;
    }
    .blog-stack-item:hover { border-color: var(--accent); box-shadow: var(--shadow-sm); }
    .blog-stack-title { font-family: var(--font-display); font-size: 1.06rem; font-weight: 700; color: var(--text-primary); line-height: 1.35; }
    .blog-read-link { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; font-weight: 600; color: var(--accent); margin-top: auto; }

    /* Closing Dark Navy CTA */
    .cta-navy-band {
        background: linear-gradient(135deg, var(--navy-deep) 0%, var(--navy-surface) 100%);
        color: #F8FAFC;
        padding: clamp(3.25rem, 6vw, 4.75rem) 0;
        border-top: 1px solid var(--navy-border);
    }
    .cta-grid { display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(0, 0.7fr); gap: 2rem; align-items: center; }
    .cta-navy-band h2 { color: #FFFFFF; font-size: clamp(1.85rem, 3.8vw, 2.75rem); font-weight: 800; line-height: 1.15; margin-bottom: 0.75rem; }
    .cta-navy-band p { color: #CBD5E1; font-size: 1rem; max-width: 52ch; }
    .cta-right-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 0.85rem; }
    .cta-quick-links { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 1rem; font-family: var(--font-mono); font-size: 0.8rem; }
    .cta-quick-links a { color: var(--accent-sky); text-decoration: none; }

    /* Home Modal */
    .cert-modal { display: none; position: fixed; inset: 0; z-index: 2000; background: rgba(7, 11, 20, 0.72); backdrop-filter: blur(5px); align-items: center; justify-content: center; padding: 1rem; }
    .cert-modal.active { display: flex; }
    .cert-modal-box { background: var(--bg-card); border-radius: var(--radius-lg); width: 100%; max-width: 980px; height: 88vh; max-height: 840px; display: flex; flex-direction: column; overflow: hidden; box-shadow: var(--shadow-lg); }
    .cert-modal-header { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border); }
    .cert-modal-title { display: flex; align-items: center; gap: 0.55rem; font-family: var(--font-display); font-size: 0.98rem; font-weight: 700; min-width: 0; }
    .cert-modal-title span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cert-modal-actions { display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; }
    .btn-modal-close { display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: var(--radius-sm); background: var(--bg-secondary); border: 1px solid var(--border); color: var(--text-primary); cursor: pointer; }
    .cert-modal-body { flex: 1; background: var(--bg-secondary); display: flex; align-items: center; justify-content: center; padding: 0.75rem; overflow: auto; }
    .cert-modal-body iframe { width: 100%; height: 100%; border: none; border-radius: var(--radius-sm); background: #FFFFFF; }
    .cert-modal-body img { max-width: 100%; max-height: 100%; object-fit: contain; }

    @media (max-width: 1024px) {
        .hero-grid { grid-template-columns: 1fr; }
        .academic-split { grid-template-columns: 1fr; }
        .docs-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .blog-magazine-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 820px) {
        .skills-grid-8 { grid-template-columns: 1fr; }
        .project-showcase-row { grid-template-columns: 1fr; gap: 1.35rem; }
        .project-showcase-row.is-reversed .project-showcase-media,
        .project-showcase-row.is-reversed .project-showcase-info { order: initial; }
        .cta-grid { grid-template-columns: 1fr; }
        .cta-right-actions { align-items: flex-start; }
        .cta-quick-links { justify-content: flex-start; }
    }
    @media (max-width: 600px) {
        .docs-grid { grid-template-columns: 1fr; }
        .doc-actions { flex-direction: column; }
        .cert-modal-actions .btn span { display: none; }
    }
</style>
@endsection

@section('content')
@php
    $isVi = app()->getLocale() === 'vi';

    // Phase 3: Deduplicated 8-Category Skills Organizer (No percentages, no excessive descriptions)
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
    // Conceptual/subtopic items omitted from standalone skill cards per Phase 3 cleanup rules
    $omitSubtopics = [
        'agile / scrum',
        'clean code & documentation',
        'mvc & solid principles',
        'design patterns',
        'session management',
        'password hashing',
        'php (backend)', // Deduplicated with PHP in Programming Languages
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

<!-- 1. DARK NAVY HERO -->
<section class="hero-navy">
    <div class="container">
        <div class="hero-top-bar hero-animate">
            <span>{{ $isVi ? 'TP. HỒ CHÍ MINH, VIỆT NAM' : 'HO CHI MINH CITY, VIETNAM' }}</span>
            <span class="availability-pill">
                <span class="availability-dot" aria-hidden="true"></span>
                <span>{{ __('Available for opportunities') }}</span>
            </span>
        </div>

        <div class="hero-grid hero-animate">
            <div>
                <div class="hero-role-tag">Fullstack Developer</div>
                <h1 class="hero-name-title">{{ __('Hồ Thành Thiện') }}</h1>
                <div class="hero-positioning">
                    {{ $isVi ? 'Laravel · Java Spring Boot · ReactJS · Applied AI' : 'Laravel · Java Spring Boot · ReactJS · Applied AI' }}
                </div>

                <p class="hero-summary">
                    {{ $isVi
                        ? 'Kỹ sư Fullstack Developer tốt nghiệp loại Xuất sắc ngành CNTT tại ĐH Giao thông Vận tải TP.HCM (UTH, GPA kỳ gần nhất 4.0/4.0). Chuyên xây dựng các ứng dụng web hiện đại với Laravel, Java Spring Boot, ReactJS và tích hợp giải pháp AI (RAG, Vector Search) với kiến trúc chuẩn mực và hiệu năng tối ưu.'
                        : 'Fullstack Developer graduated with Excellent honors in IT from University of Transport HCMC (UTH, recent semester GPA 4.0/4.0). Specializing in crafting modern, high-performance web applications with Laravel, Java Spring Boot, ReactJS, and practical AI integrations (RAG, Vector Search).' }}
                </p>

                <div class="hero-cta-row">
                    <a href="#featured-projects" class="btn btn-primary">
                        <span>{{ __('View Projects') }}</span>
                        <i class="fas fa-arrow-down" aria-hidden="true"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-dark">
                        <span>{{ __('Contact Me') }}</span>
                    </a>
                </div>

                <div class="hero-social-strip">
                    <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="hero-social-link">
                        <i class="fab fa-github"></i> <span>GitHub</span>
                    </a>
                    <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="hero-social-link">
                        <i class="fab fa-linkedin"></i> <span>LinkedIn</span>
                    </a>
                    <a href="mailto:hothanhthien119@gmail.com" class="hero-social-link">
                        <i class="fas fa-envelope"></i> <span>hothanhthien119@gmail.com</span>
                    </a>
                    <a href="tel:0375227764" class="hero-social-link" title="{{ $isVi ? 'Nhấp để gọi' : 'Click to call' }}">
                        <i class="fas fa-phone-alt"></i> <span>0375 &bull;&bull;&bull; 764</span>
                    </a>
                </div>
            </div>

            <aside class="dossier-card" aria-label="{{ __('Engineering Dossier') }}">
                <div class="dossier-top">
                    <span class="dossier-top-dot"><i class="fas fa-terminal" aria-hidden="true"></i> PROFILE // UTH</span>
                    <span>hothanhthien.io.vn</span>
                </div>

                <div class="dossier-head">
                    <div class="dossier-avatar-mark" aria-hidden="true">HT</div>
                    <div>
                        <div class="dossier-name">{{ __('Hồ Thành Thiện') }}</div>
                        <div class="dossier-sub">Fullstack Developer</div>
                    </div>
                </div>

                <div class="dossier-rows">
                    <div class="dossier-row">
                        <span class="dossier-key">{{ __('Education') }}</span>
                        <span class="dossier-val">{{ __('University of Transport Ho Chi Minh City') }}</span>
                    </div>
                    <div class="dossier-row">
                        <span class="dossier-key">{{ __('Verified Record') }}</span>
                        <span class="dossier-val">GPA 4.0/4.0 · {{ __('Excellent') }} (3.6/4.0)</span>
                    </div>
                    <div class="dossier-row">
                        <span class="dossier-key">{{ __('Experience') }}</span>
                        <span class="dossier-val">Enmasys · AIPower (2026)</span>
                    </div>
                    <div class="dossier-row">
                        <span class="dossier-key">{{ __('Website') }}</span>
                        <span class="dossier-val"><a href="https://hothanhthien.io.vn/" target="_blank" rel="noopener">https://hothanhthien.io.vn/</a></span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- 2. FEATURED PROJECTS -->
@if($featuredProjects->count())
<section class="section" id="featured-projects">
    <div class="container">
        <div class="section-head" data-reveal>
            <div class="section-head-left">
                <div class="section-kicker">01 // {{ __('Selected Works') }}</div>
                <h2>{!! __('heading_featured_projects') !!}</h2>
                <p>{{ __('Some of my recent and favourite work') }}</p>
            </div>
            <a href="{{ route('projects.index') }}" class="btn btn-outline btn-sm">
                <span>{{ __('View All Projects') }}</span>
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        <div class="project-showcase-list">
            @foreach($featuredProjects as $index => $project)
                @php
                    $cleanDesc = $project->summary;
                    $hostLabel = $project->demo_url
                        ? preg_replace('#^https?://#', '', rtrim($project->demo_url, '/'))
                        : ($project->github_url ? preg_replace('#^https?://#', '', rtrim($project->github_url, '/')) : 'project.spec');
                    $isPortfolio = $project->id === 1 || str_contains(strtolower($project->title), 'portfolio');
                    $isTourrist = $project->id === 2 || str_contains(strtolower($project->title), 'tourrist');
                    $isLaundry = $project->id === 3 || str_contains(strtolower($project->title), 'laundry');
                @endphp
                <article class="project-showcase-row {{ $index % 2 === 1 ? 'is-reversed' : '' }}" data-reveal>
                    <a href="{{ route('projects.show', $project->id) }}" class="project-showcase-media" aria-label="{{ $project->title }}">
                        @if($project->image)
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @endif

                        @if($isPortfolio)
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
                            <span class="project-index-code">PROJECT // {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
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

<!-- 3. WORK EXPERIENCE TIMELINE -->
@if($experiences->count())
<section class="section section--alt" id="experience">
    <div class="container">
        <div class="section-head" data-reveal>
            <div class="section-head-left">
                <div class="section-kicker">02 // CAREER TIMELINE</div>
                <h2>{!! __('heading_experience') !!}</h2>
                <p>{{ __('My professional journey so far') }}</p>
            </div>
        </div>

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
                        <p style="color:var(--text-secondary); font-size:0.93rem; line-height:1.65; margin-bottom:1rem;">{{ $exp->localized_description }}</p>
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

<!-- 4. TECHNICAL SKILLS (8 CATEGORIES, DEDUPLICATED, NO PERCENTAGES) -->
@if(count($skillBuckets))
<section class="section" id="skills">
    <div class="container">
        <div class="section-head" data-reveal>
            <div class="section-head-left">
                <div class="section-kicker">03 // TECHNICAL STACK</div>
                <h2>{!! __('heading_my_skills') !!}</h2>
                <p>{{ __('Technologies and tools I work with every day') }}</p>
            </div>
        </div>

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
                        <span class="mono-label">{{ count($bucket['items']) }}</span>
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

<!-- 5. ACHIEVEMENTS & EDUCATION -->
<section class="section section--alt" id="education">
    <div class="container">
        <div class="section-head" data-reveal>
            <div class="section-head-left">
                <div class="section-kicker">04 // ACADEMIC &amp; RECOGNITION</div>
                <h2>{!! __('heading_education') !!} &amp; {!! __('heading_achievements') !!}</h2>
                <p>{{ __('Formal academic background & verified credentials') }}</p>
            </div>
        </div>

        <div class="academic-split" data-reveal>
            <div class="edu-card">
                <div>
                    <div class="edu-label">2023 – 2026 // {{ __('Information Technology') }}</div>
                    <h3 class="edu-school">{{ __('University of Transport Ho Chi Minh City') }}</h3>
                    <div class="edu-degree">{{ __("Bachelor's Degree Information Technology") }}</div>
                    <p style="font-size:0.88rem; color:#94A3B8; line-height:1.55;">
                        {{ __('Member Google Developer Chapter University of Transport Ho Chi Minh City') }}
                    </p>
                </div>

                <div class="edu-stats-row">
                    <div>
                        <div class="edu-stat-val">3.6 / 4.0</div>
                        <div class="edu-stat-key">{{ __('Cumulative GPA') }}</div>
                    </div>
                    <div>
                        <div class="edu-stat-val">{{ __('Excellent') }}</div>
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
                        <p class="ach-item-sub">{{ $isVi ? 'Cử nhân Công nghệ Thông tin — ĐH Giao thông Vận tải TP.HCM.' : 'Bachelor of Information Technology — University of Transport Ho Chi Minh City.' }}</p>
                    </div>
                </div>

                <div class="ach-item">
                    <div class="ach-badge">2025</div>
                    <div>
                        <h3 class="ach-item-title">{{ __('Second prize in the institute/faculty logo design competition') }}</h3>
                        <p class="ach-item-sub">{{ $isVi ? 'Giải Nhì cuộc thi sáng tác biểu trưng chính thức cấp Viện / Khoa CNTT.' : 'Second prize in the faculty/institute official logo design competition.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. CREDENTIALS & DOCUMENTS (HONEST FILE MAPPING) -->
@if($certificates->count())
<section class="section" id="credentials">
    <div class="container">
        <div class="section-head" data-reveal>
            <div class="section-head-left">
                <div class="section-kicker">05 // CREDENTIALS</div>
                <h2>{!! __('heading_credentials') !!}</h2>
                <p>{{ __('credentials_subtitle') }}</p>
            </div>
            <a href="{{ route('certificates') }}" class="btn btn-outline btn-sm">
                <span>{{ __('View All Documents') }}</span>
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

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

                    <div class="doc-footer-meta" style="display:flex; justify-content:space-between; align-items:center; font-family:var(--font-mono); font-size:0.72rem; color:var(--text-muted); margin-bottom:0.75rem; padding-top:0.75rem; border-top:1px solid var(--border-subtle);">
                        @if($isBrochure)
                            <span><i class="fas fa-file-pdf" style="color:var(--accent);"></i> PDF &bull; 402 KB</span>
                            <span style="color:#34D399;"><i class="fas fa-check-circle"></i> {{ $isVi ? 'Đã số hóa' : 'Verified' }}</span>
                        @else
                            <span><i class="fas fa-award" style="color:var(--accent);"></i> {{ $isVi ? 'Bản chính quy UTH' : 'UTH Official Record' }}</span>
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

<!-- 7. BLOG -->
@if($recentPosts->count())
<section class="section section--alt" id="blog">
    <div class="container">
        <div class="section-head" data-reveal>
            <div class="section-head-left">
                <div class="section-kicker">06 // {{ __('Engineering Journal') }}</div>
                <h2>{!! __('heading_recent_articles') !!}</h2>
                <p>{{ __('Thoughts, learnings and insights from my journey') }}</p>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn-outline btn-sm">
                <span>{{ __('Read All Articles') }}</span>
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

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
                        <div class="blog-typo-cover">
                            <span class="mono-label" style="color:var(--accent-sky)">{{ __('Latest Article') }}</span>
                            <div style="font-family:var(--font-display); font-size:clamp(1.2rem,2.2vw,1.5rem); font-weight:700; color:#FFFFFF; line-height:1.25;">{{ $leadPost->title }}</div>
                        </div>
                    @endif
                </div>

                <div class="blog-lead-body">
                    <div class="blog-meta-line">
                        @if($leadPost->published_at)
                            <span>{{ $leadPost->published_at->format('M d, Y') }}</span>
                        @endif
                        <span>·</span>
                        <span>{{ max(1, ceil(str_word_count(strip_tags($leadPost->content)) / 200)) }} {{ __('min read') }}</span>
                    </div>
                    <h3 class="blog-lead-title">{{ $leadPost->title }}</h3>
                    @if($leadPost->excerpt)
                        <p style="color:var(--text-secondary); font-size:0.94rem; line-height:1.65; margin-bottom:1rem;">{{ Str::limit($leadPost->excerpt, 160) }}</p>
                    @endif
                    <span class="blog-read-link">
                        <span>{{ __('Read Article') }}</span>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </span>
                </div>
            </a>
            @endif

            @if($secondaryPosts->count())
            <div class="blog-stack-list" data-stagger>
                @foreach($secondaryPosts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-stack-item">
                    <div class="blog-meta-line">
                        @if($post->published_at)
                            <span>{{ $post->published_at->format('M d, Y') }}</span>
                        @endif
                        <span>·</span>
                        <span>{{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} {{ __('min read') }}</span>
                    </div>
                    <h3 class="blog-stack-title">{{ $post->title }}</h3>
                    @if($post->excerpt)
                        <p style="font-size:0.9rem; color:var(--text-secondary); line-height:1.58;">{{ Str::limit($post->excerpt, 120) }}</p>
                    @endif
                    <span class="blog-read-link">
                        <span>{{ __('Read Article') }}</span>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </span>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- 8. CLOSING DARK NAVY CTA -->
<section class="cta-navy-band" data-reveal>
    <div class="container">
        <div class="cta-grid">
            <div>
                <div class="section-kicker" style="color:var(--accent-sky);">07 // {{ __('Let\'s work together') }}</div>
                <h2>{!! __('cta_heading') !!}</h2>
                <p>{{ __("I'm always open to interesting projects and opportunities.") }}</p>
            </div>

            <div class="cta-right-actions">
                <a href="{{ route('contact') }}" class="btn btn-primary">
                    <i class="fas fa-paper-plane" aria-hidden="true"></i>
                    <span>{{ __('Send Me a Message') }}</span>
                </a>
                <div class="cta-quick-links">
                    <a href="mailto:hothanhthien119@gmail.com">hothanhthien119@gmail.com</a>
                    <a href="tel:0375227764">0375227764</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Home Document Preview Modal -->
<div class="cert-modal" id="homeCertModal" role="dialog" aria-modal="true" aria-labelledby="homeCertModalTitle" onclick="closeHomeCertModal(event)">
    <div class="cert-modal-box" onclick="event.stopPropagation()">
        <div class="cert-modal-header">
            <div class="cert-modal-title" id="homeCertModalTitle">
                <i class="fas fa-file-pdf" style="color:var(--accent)" aria-hidden="true"></i>
                <span id="homeCertModalTitleText">{{ $isVi ? 'Tài liệu' : 'Document' }}</span>
            </div>
            <div class="cert-modal-actions">
                <a id="homeCertModalExt" href="#" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                    <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                    <span>{{ $isVi ? 'Mở tab mới' : 'New Tab' }}</span>
                </a>
                <a id="homeCertModalDl" href="#" download class="btn btn-primary btn-sm">
                    <i class="fas fa-download" aria-hidden="true"></i>
                    <span>{{ __('Download') }}</span>
                </a>
                <button type="button" id="homeCertModalCloseBtn" class="btn-modal-close" onclick="closeHomeCertModalDirect()" aria-label="{{ $isVi ? 'Đóng' : 'Close' }}">
                    <i class="fas fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="cert-modal-body" id="homeCertModalBody"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('[data-skill-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.getAttribute('data-skill-filter');
        document.querySelectorAll('[data-skill-filter]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('[data-skill-group]').forEach(card => {
            const match = target === 'all' || card.getAttribute('data-skill-group') === target;
            card.classList.toggle('is-hidden', !match);
        });
    });
});

let lastHomeCertTrigger = null;

function openHomeCertModal(url, title, type) {
    lastHomeCertTrigger = document.activeElement;
    const modal = document.getElementById('homeCertModal');
    const titleEl = document.getElementById('homeCertModalTitleText');
    const bodyEl = document.getElementById('homeCertModalBody');
    const extBtn = document.getElementById('homeCertModalExt');
    const dlBtn = document.getElementById('homeCertModalDl');

    if (titleEl) titleEl.textContent = title;
    if (extBtn) {
        extBtn.style.display = 'inline-flex';
        extBtn.href = url;
    }
    if (dlBtn) {
        dlBtn.innerHTML = '<i class="fas fa-download"></i> <span>{{ __("Download") }}</span>';
        dlBtn.href = url;
        dlBtn.setAttribute('download', '');
    }

    if (type === 'pdf') {
        bodyEl.innerHTML = `<iframe src="${url}#toolbar=1" title="${title}"></iframe>`;
    } else {
        bodyEl.innerHTML = `<img src="${url}" alt="${title}">`;
    }

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        const closeBtn = document.getElementById('homeCertModalCloseBtn');
        if (closeBtn) closeBtn.focus();
    }, 50);
}

function openCertDetailModal(title, issuer, desc) {
    lastHomeCertTrigger = document.activeElement;
    const modal = document.getElementById('homeCertModal');
    const titleEl = document.getElementById('homeCertModalTitleText');
    const bodyEl = document.getElementById('homeCertModalBody');
    const extBtn = document.getElementById('homeCertModalExt');
    const dlBtn = document.getElementById('homeCertModalDl');

    if (titleEl) titleEl.textContent = title;
    if (extBtn) extBtn.style.display = 'none';
    if (dlBtn) {
        dlBtn.innerHTML = '<i class="fas fa-envelope"></i> <span>{{ $isVi ? "Yêu cầu bản scan" : "Request Scan" }}</span>';
        dlBtn.href = '{{ route("contact") }}?subject=' + encodeURIComponent(title);
        dlBtn.removeAttribute('download');
    }

    bodyEl.innerHTML = `
        <div style="padding:2rem; max-width:620px; margin:auto; text-align:left; color:#CBD5E1; font-family:var(--font-sans);">
            <div style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.3rem 0.8rem; background:rgba(56,189,248,0.12); border:1px solid rgba(56,189,248,0.3); border-radius:4px; color:#38BDF8; font-family:var(--font-mono); font-size:0.78rem; font-weight:600; margin-bottom:1rem;">
                <i class="fas fa-check-circle"></i> {{ $isVi ? "Hồ sơ chính quy đã xác thực" : "Verified Official Record" }}
            </div>
            <h3 style="color:#FFFFFF; font-size:1.35rem; font-weight:700; margin-bottom:0.4rem;">${title}</h3>
            <div style="color:#94A3B8; font-size:0.92rem; font-weight:600; margin-bottom:1.1rem;">${issuer}</div>
            <p style="font-size:0.95rem; line-height:1.7; color:#CBD5E1; margin-bottom:1.5rem;">${desc}</p>
            <div style="background:#0F172A; border:1px solid rgba(148,163,184,0.14); border-radius:8px; padding:1.2rem; font-size:0.86rem; line-height:1.6; color:#94A3B8;">
                <strong style="color:#F8FAFC;">{{ $isVi ? "Tình trạng lưu trữ:" : "Filing status:" }}</strong> {{ $isVi ? "Văn bằng và giải thưởng gốc được lưu giữ tại trường và hồ sơ cá nhân. Nhà tuyển dụng cần bản sao công chứng hoặc scan số hóa độ nét cao vui lòng liên hệ xác nhận trực tiếp." : "The original degree and award credentials are preserved in official personal archives. Recruiters and hiring managers requiring a high-resolution notarized digital scan can request one directly." }}
            </div>
        </div>
    `;

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        const closeBtn = document.getElementById('homeCertModalCloseBtn');
        if (closeBtn) closeBtn.focus();
    }, 50);
}

function closeHomeCertModalDirect() {
    const modal = document.getElementById('homeCertModal');
    const bodyEl = document.getElementById('homeCertModalBody');
    if (!modal || !modal.classList.contains('active')) return;
    modal.classList.remove('active');
    setTimeout(() => {
        if (bodyEl && !modal.classList.contains('active')) bodyEl.innerHTML = '';
    }, 240);
    document.body.style.overflow = '';
    if (lastHomeCertTrigger && typeof lastHomeCertTrigger.focus === 'function') {
        lastHomeCertTrigger.focus();
    }
}

function closeHomeCertModal(e) {
    if (e.target && e.target.id === 'homeCertModal') {
        closeHomeCertModalDirect();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeHomeCertModalDirect();
    }
});
</script>
@endsection
