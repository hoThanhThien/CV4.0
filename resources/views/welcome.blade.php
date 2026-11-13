@extends('layouts.app')

@section('title', app()->getLocale() === 'vi' ? 'Hồ Thành Thiện | Kỹ sư Full-Stack Web Developer' : 'Ho Thanh Thien | Full-Stack Software Engineer')

@section('styles')
<style>
    /* =========================================================
       HOMEPAGE — SWISS EDITORIAL & CREATIVE ENGINEER PORTFOLIO
    ========================================================= */

    /* 1. Asymmetric Editorial Hero */
    .hero-editorial {
        padding: clamp(2.5rem, 5vw, 4.5rem) 0 clamp(2.5rem, 4.5vw, 3.75rem);
        border-bottom: 1px solid var(--border-strong);
        background: var(--bg-primary);
    }

    .hero-top-ledger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.85rem;
        padding-bottom: 1.5rem;
        margin-bottom: clamp(1.75rem, 3.5vw, 2.75rem);
        border-bottom: 1px solid var(--border);
        font-family: var(--font-mono);
        font-size: 0.78rem;
        color: var(--text-muted);
    }

    .availability-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.3rem 0.78rem;
        border-radius: 999px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-primary);
        font-family: var(--font-sans);
        font-size: 0.8rem;
        font-weight: 600;
    }
    .availability-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--green);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.16);
        animation: pulseLive 2.2s ease-in-out infinite;
    }
    @keyframes pulseLive {
        0%, 100% { box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.16); }
        50% { box-shadow: 0 0 0 6px rgba(15, 118, 110, 0.04); }
    }

    .hero-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.32fr) minmax(0, 0.92fr);
        gap: clamp(2rem, 4.5vw, 4rem);
        align-items: start;
    }

    .hero-role-eyebrow {
        font-family: var(--font-mono);
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--accent);
        letter-spacing: 0.04em;
        margin-bottom: 0.9rem;
    }

    .hero-title {
        font-family: var(--font-display);
        font-size: clamp(2.35rem, 5.2vw, 4.15rem);
        font-weight: 800;
        line-height: 1.06;
        letter-spacing: -0.038em;
        color: var(--text-primary);
        margin-bottom: 1.35rem;
    }
    .hero-title .editorial-italic {
        color: var(--accent);
        font-weight: 500;
    }

    .hero-summary {
        font-size: clamp(1rem, 1.45vw, 1.12rem);
        line-height: 1.72;
        color: var(--text-secondary);
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
        gap: 0.65rem 1.35rem;
        padding-top: 1.35rem;
        border-top: 1px solid var(--border);
    }
    .hero-social-link {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        transition: color var(--duration-fast) ease;
    }
    .hero-social-link i {
        color: var(--text-muted);
        transition: color var(--duration-fast) ease;
    }
    .hero-social-link:hover,
    .hero-social-link:hover i {
        color: var(--accent);
    }

    /* Architectural Engineer Dossier Plate (Right Hero Column) */
    .dossier-plate {
        background: var(--surface-ink);
        color: #F3F4F6;
        border-radius: var(--radius-lg);
        border: 1px solid #232938;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        transition: transform var(--duration-base) var(--ease-out), box-shadow var(--duration-base) var(--ease-out);
    }
    .dossier-plate:hover {
        transform: translateY(-2px);
    }
    .dossier-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.35rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: #9CA3AF;
    }
    .dossier-id-head {
        display: flex;
        align-items: center;
        gap: 1.1rem;
        padding: 1.4rem 1.35rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .dossier-monogram {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, #1D4ED8 0%, #1E3A8A 100%);
        border: 1px solid rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
    }
    .dossier-monogram span {
        font-family: var(--font-display);
        font-size: 1.35rem;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: -0.04em;
    }
    .dossier-name {
        font-family: var(--font-display);
        font-size: 1.18rem;
        font-weight: 700;
        color: #FFFFFF;
        line-height: 1.25;
        margin-bottom: 0.2rem;
    }
    .dossier-subrole {
        font-family: var(--font-mono);
        font-size: 0.78rem;
        color: #93C5FD;
    }

    .dossier-rows {
        padding: 0.4rem 1.35rem 1.25rem;
    }
    .dossier-row {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.78rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        font-size: 0.85rem;
    }
    .dossier-row:last-child {
        border-bottom: none;
        padding-bottom: 0.25rem;
    }
    .dossier-key {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: #9CA3AF;
        flex-shrink: 0;
    }
    .dossier-val {
        color: #F9FAFB;
        font-weight: 500;
        text-align: right;
        line-height: 1.45;
    }
    .dossier-val a {
        color: #93C5FD;
        text-decoration: none;
    }
    .dossier-val a:hover {
        text-decoration: underline;
    }

    /* 2. Achievements Folio Strip (Compact High-Impact Numbers) */
    .achievements-folio {
        background: var(--bg-card);
        border-bottom: 1px solid var(--border-strong);
    }
    .folio-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
    .folio-cell {
        padding: clamp(1.5rem, 3vw, 2.25rem) clamp(1rem, 2vw, 1.5rem);
        border-right: 1px solid var(--border);
        transition: background var(--duration-fast) ease;
    }
    .folio-cell:first-child {
        padding-left: 0;
    }
    .folio-cell:last-child {
        border-right: none;
        padding-right: 0;
    }
    .folio-meta {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: var(--accent);
        margin-bottom: 0.55rem;
        font-weight: 600;
    }
    .folio-num {
        font-family: var(--font-display);
        font-size: clamp(2rem, 3.6vw, 2.85rem);
        font-weight: 800;
        line-height: 1.02;
        letter-spacing: -0.04em;
        color: var(--text-primary);
        font-variant-numeric: tabular-nums;
        margin-bottom: 0.45rem;
    }
    .folio-num span {
        font-size: 0.46em;
        font-weight: 600;
        color: var(--text-muted);
        letter-spacing: -0.01em;
    }
    .folio-title {
        font-weight: 700;
        font-size: 0.94rem;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
        line-height: 1.35;
    }
    .folio-caption {
        font-size: 0.82rem;
        color: var(--text-muted);
        line-height: 1.45;
    }

    /* 3. Technical Skills Categorized Showcase (8 Clear Groups) */
    .skill-filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        margin-bottom: 1.75rem;
    }
    .skill-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.42rem 0.85rem;
        border-radius: var(--radius-sm);
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-family: var(--font-sans);
        font-size: 0.84rem;
        font-weight: 600;
        cursor: pointer;
        transition: border-color var(--duration-fast) ease, color var(--duration-fast) ease, background var(--duration-fast) ease;
    }
    .skill-filter-btn:hover {
        border-color: var(--text-primary);
        color: var(--text-primary);
    }
    .skill-filter-btn.active {
        background: var(--surface-ink);
        border-color: var(--surface-ink);
        color: #FFFFFF;
    }
    .skill-count-pill {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        opacity: 0.75;
    }

    .skills-architecture-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem;
    }
    .skill-group-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.4rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out);
    }
    .skill-group-card:hover {
        border-color: var(--border-strong);
        box-shadow: var(--shadow-sm);
    }
    .skill-group-card.is-hidden {
        display: none;
    }
    .skill-group-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid var(--border);
    }
    .skill-group-title-wrap {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .skill-group-icon {
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        background: var(--accent-soft);
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.88rem;
        flex-shrink: 0;
    }
    .skill-group-title {
        font-family: var(--font-display);
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .skill-group-index {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: var(--text-muted);
    }

    .skill-chip-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }
    .skill-tech-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        padding: 0.36rem 0.68rem;
        background: var(--bg-primary);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 0.84rem;
        font-weight: 500;
        color: var(--text-primary);
        transition: border-color var(--duration-fast) ease, background var(--duration-fast) ease;
    }
    .skill-tech-chip i {
        color: var(--accent);
        font-size: 0.8rem;
    }
    .skill-tech-chip:hover {
        border-color: var(--accent);
        background: var(--bg-card);
    }

    .skill-notes-details {
        margin-top: auto;
        border-top: 1px dashed var(--border);
        padding-top: 0.7rem;
    }
    .skill-notes-summary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-family: var(--font-mono);
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        user-select: none;
        list-style: none;
        transition: color var(--duration-fast) ease;
    }
    .skill-notes-summary::-webkit-details-marker { display: none; }
    .skill-notes-summary:hover {
        color: var(--accent);
    }
    .skill-notes-details[open] .skill-notes-summary {
        color: var(--accent);
        margin-bottom: 0.65rem;
    }
    .skill-notes-list {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        font-size: 0.84rem;
        line-height: 1.55;
        color: var(--text-secondary);
    }
    .skill-note-row strong {
        color: var(--text-primary);
        font-weight: 600;
    }

    /* 4. Work Experience Timeline */
    .experience-split {
        display: grid;
        grid-template-columns: minmax(0, 0.85fr) minmax(0, 1.65fr);
        gap: clamp(2rem, 5vw, 4.5rem);
        align-items: start;
    }
    .experience-sticky-col {
        position: sticky;
        top: 96px;
    }
    .experience-sticky-col h2 {
        font-size: clamp(1.85rem, 3.6vw, 2.65rem);
        font-weight: 800;
        line-height: 1.12;
        margin-bottom: 0.85rem;
    }
    .experience-sticky-col p {
        color: var(--text-secondary);
        font-size: 0.96rem;
        line-height: 1.68;
        margin-bottom: 1.5rem;
    }

    .exp-timeline {
        border-left: 1.5px solid var(--border-strong);
        padding-left: clamp(1.25rem, 3vw, 2rem);
        display: flex;
        flex-direction: column;
        gap: 2.25rem;
    }
    .exp-entry {
        position: relative;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: clamp(1.35rem, 2.8vw, 1.85rem);
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out);
    }
    .exp-entry:hover {
        border-color: var(--border-strong);
        box-shadow: var(--shadow-sm);
    }
    .exp-entry::before {
        content: '';
        position: absolute;
        left: calc(-1 * clamp(1.25rem, 3vw, 2rem) - 5.5px);
        top: 1.8rem;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--bg-card);
        border: 2.5px solid var(--accent);
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
        font-size: 1.22rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.25;
        margin-bottom: 0.2rem;
    }
    .exp-company-name {
        font-weight: 600;
        color: var(--accent);
        font-size: 0.96rem;
    }
    .exp-period-badge {
        font-family: var(--font-mono);
        font-size: 0.76rem;
        font-weight: 500;
        color: var(--text-secondary);
        background: var(--bg-primary);
        border: 1px solid var(--border);
        padding: 0.25rem 0.65rem;
        border-radius: var(--radius-sm);
        white-space: nowrap;
    }
    .exp-bullets {
        margin: 0.85rem 0 1rem 1.15rem;
        color: var(--text-secondary);
        font-size: 0.93rem;
        line-height: 1.65;
    }
    .exp-bullets li {
        margin-bottom: 0.4rem;
    }
    .exp-summary-p {
        color: var(--text-secondary);
        font-size: 0.93rem;
        line-height: 1.65;
        margin: 0.75rem 0 1rem;
    }
    .exp-tech-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        padding-top: 0.85rem;
        border-top: 1px solid var(--border);
    }

    /* 5. Featured Projects — Alternating Asymmetric Editorial Showcase */
    .project-showcase-list {
        display: flex;
        flex-direction: column;
        gap: clamp(2.25rem, 4.5vw, 3.5rem);
    }
    .project-showcase-row {
        display: grid;
        grid-template-columns: minmax(0, 1.08fr) minmax(0, 0.92fr);
        gap: clamp(1.5rem, 4vw, 3.25rem);
        align-items: center;
        padding-bottom: clamp(2.25rem, 4.5vw, 3.5rem);
        border-bottom: 1px solid var(--border);
    }
    .project-showcase-row:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }
    .project-showcase-row.is-reversed .project-showcase-media {
        order: 2;
    }
    .project-showcase-row.is-reversed .project-showcase-info {
        order: 1;
    }

    .project-showcase-media {
        display: block;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-strong);
        overflow: hidden;
        background: var(--surface-ink);
        aspect-ratio: 16 / 10;
        box-shadow: var(--shadow-md);
        text-decoration: none;
        transition: transform var(--duration-base) var(--ease-out), box-shadow var(--duration-base) var(--ease-out), border-color var(--duration-fast) ease;
    }
    .project-showcase-media:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
        border-color: var(--text-primary);
    }
    .project-showcase-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform var(--duration-slow) var(--ease-out);
    }
    .project-showcase-media:hover img {
        transform: scale(1.025);
    }

    .project-meta-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.65rem;
    }
    .project-index-code {
        font-family: var(--font-mono);
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--accent);
    }
    .project-featured-tag {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--text-muted);
        border: 1px solid var(--border);
        background: var(--bg-card);
        padding: 0.18rem 0.52rem;
        border-radius: var(--radius-sm);
    }
    .project-showcase-title {
        font-family: var(--font-display);
        font-size: clamp(1.45rem, 2.6vw, 1.95rem);
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: -0.025em;
        margin-bottom: 0.8rem;
    }
    .project-showcase-title a {
        color: var(--text-primary);
        text-decoration: none;
        transition: color var(--duration-fast) ease;
    }
    .project-showcase-title a:hover {
        color: var(--accent);
    }
    .project-showcase-desc {
        color: var(--text-secondary);
        font-size: 0.96rem;
        line-height: 1.68;
        margin-bottom: 1.15rem;
    }
    .project-showcase-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 1.4rem;
    }
    .project-action-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.6rem;
    }

    /* 6. Credentials & Verified Documents */
    .docs-editorial-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.35rem;
    }
    .doc-plate {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .doc-plate:hover {
        border-color: var(--text-primary);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .doc-preview-frame {
        position: relative;
        height: 170px;
        background: var(--bg-primary);
        border-bottom: 1px solid var(--border);
        padding: 1.1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }
    .doc-preview-frame img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .doc-type-row {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }
    .doc-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.22rem 0.55rem;
        border-radius: var(--radius-sm);
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-primary);
    }
    .doc-type-badge.is-degree i { color: #0F766E; }
    .doc-type-badge.is-award i { color: #B45309; }
    .doc-type-badge.is-brochure i { color: var(--accent); }
    .doc-year-mono {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: var(--text-muted);
        background: var(--bg-card);
        padding: 0.18rem 0.5rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border);
    }
    .doc-paper-lines {
        position: relative;
        z-index: 1;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px dashed var(--border-strong);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        color: var(--text-muted);
    }

    .doc-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .doc-title {
        font-family: var(--font-display);
        font-size: 1.06rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.35;
        margin-bottom: 0.35rem;
    }
    .doc-issuer {
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--accent);
        margin-bottom: 0.65rem;
    }
    .doc-desc {
        font-size: 0.88rem;
        color: var(--text-secondary);
        line-height: 1.55;
        margin-bottom: 1.15rem;
        flex: 1;
    }
    .doc-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-top: 0.9rem;
        border-top: 1px solid var(--border);
    }
    .doc-actions .btn {
        flex: 1;
        min-width: 0;
    }

    /* 7. Blog Editorial Magazine Split */
    .blog-magazine-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.12fr) minmax(0, 0.88fr);
        gap: 1.75rem;
        align-items: stretch;
    }
    .blog-lead-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .blog-lead-card:hover {
        border-color: var(--text-primary);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .blog-lead-media {
        aspect-ratio: 16 / 9;
        background: var(--surface-ink);
        overflow: hidden;
        position: relative;
        border-bottom: 1px solid var(--border);
    }
    .blog-lead-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .blog-typographic-cover {
        width: 100%;
        height: 100%;
        padding: 1.5rem 1.75rem;
        background: var(--surface-ink);
        color: #FFFFFF;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background-image: radial-gradient(circle at 85% 20%, rgba(29, 78, 216, 0.25), transparent 55%);
    }
    .blog-cover-label {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: #93C5FD;
    }
    .blog-cover-title {
        font-family: var(--font-display);
        font-size: clamp(1.2rem, 2.2vw, 1.55rem);
        font-weight: 700;
        line-height: 1.25;
        color: #FFFFFF;
    }
    .blog-lead-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .blog-meta-line {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.75rem;
        font-family: var(--font-mono);
        font-size: 0.76rem;
        color: var(--text-muted);
        margin-bottom: 0.65rem;
    }
    .blog-lead-title {
        font-family: var(--font-display);
        font-size: 1.32rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.32;
        margin-bottom: 0.65rem;
    }
    .blog-lead-excerpt {
        color: var(--text-secondary);
        font-size: 0.94rem;
        line-height: 1.65;
        flex: 1;
        margin-bottom: 1.15rem;
    }

    .blog-stack-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .blog-stack-item {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 1.25rem 1.35rem;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex: 1;
        justify-content: center;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-fast) ease;
    }
    .blog-stack-item:hover {
        border-color: var(--text-primary);
        box-shadow: var(--shadow-sm);
        transform: translateY(-1px);
    }
    .blog-stack-title {
        font-family: var(--font-display);
        font-size: 1.08rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.35;
    }
    .blog-stack-item:hover .blog-stack-title,
    .blog-lead-card:hover .blog-lead-title {
        color: var(--accent);
    }
    .blog-read-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--accent);
    }

    /* 8. Closing Editorial CTA Band */
    .cta-dark-band {
        background: var(--surface-ink);
        color: #F9FAFB;
        padding: clamp(3.5rem, 6vw, 5.25rem) 0;
    }
    .cta-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.3fr) minmax(0, 0.7fr);
        gap: 2.5rem;
        align-items: center;
    }
    .cta-dark-band h2 {
        color: #FFFFFF;
        font-size: clamp(1.85rem, 4vw, 2.95rem);
        font-weight: 800;
        line-height: 1.12;
        letter-spacing: -0.03em;
        margin-bottom: 0.85rem;
    }
    .cta-dark-band p {
        color: #D1D5DB;
        font-size: 1.02rem;
        max-width: 54ch;
        line-height: 1.65;
    }
    .cta-right-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.85rem;
    }
    .cta-quick-links {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 1rem;
        font-family: var(--font-mono);
        font-size: 0.8rem;
    }
    .cta-quick-links a {
        color: #93C5FD;
        text-decoration: none;
    }
    .cta-quick-links a:hover {
        text-decoration: underline;
    }

    /* Document Modal Styles */
    .cert-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 2000;
        background: rgba(17, 20, 27, 0.65);
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        transition: opacity 0.22s ease;
    }
    .cert-modal.active {
        display: flex;
        opacity: 1;
    }
    .cert-modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 980px;
        height: 88vh;
        max-height: 840px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(17, 20, 27, 0.25);
        transform: translateY(10px);
        transition: transform 0.24s var(--ease-out);
    }
    .cert-modal.active .cert-modal-box {
        transform: translateY(0);
    }
    .cert-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border);
        background: var(--bg-card);
    }
    .cert-modal-title {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-family: var(--font-display);
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--text-primary);
        min-width: 0;
    }
    .cert-modal-title span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .cert-modal-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    .btn-modal-close {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: var(--radius-sm);
        background: var(--bg-primary);
        border: 1px solid var(--border);
        color: var(--text-primary);
        cursor: pointer;
    }
    .cert-modal-body {
        flex: 1;
        background: var(--bg-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
        overflow: auto;
    }
    .cert-modal-body iframe {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: var(--radius-sm);
        background: #FFFFFF;
    }
    .cert-modal-body img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: var(--radius-sm);
    }

    /* =========================================================
       RESPONSIVE BREAKPOINTS (1440 -> 1024 -> 768 -> 390 -> 360)
    ========================================================= */
    @media (max-width: 1024px) {
        .hero-grid { grid-template-columns: 1fr; }
        .folio-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .folio-cell { border-bottom: 1px solid var(--border); padding: 1.5rem 1.25rem; }
        .folio-cell:nth-child(2n) { border-right: none; }
        .folio-cell:first-child, .folio-cell:last-child { padding-left: 1.25rem; padding-right: 1.25rem; }
        .folio-cell:nth-last-child(-n+2) { border-bottom: none; }
        .experience-split { grid-template-columns: 1fr; }
        .experience-sticky-col { position: static; }
        .docs-editorial-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .blog-magazine-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 820px) {
        .skills-architecture-grid { grid-template-columns: 1fr; }
        .project-showcase-row { grid-template-columns: 1fr; gap: 1.35rem; }
        .project-showcase-row.is-reversed .project-showcase-media,
        .project-showcase-row.is-reversed .project-showcase-info { order: initial; }
        .cta-grid { grid-template-columns: 1fr; gap: 1.75rem; }
        .cta-right-actions { align-items: flex-start; }
        .cta-quick-links { justify-content: flex-start; }
    }

    @media (max-width: 600px) {
        .folio-grid { grid-template-columns: 1fr; }
        .folio-cell { border-right: none; padding: 1.25rem 0; }
        .folio-cell:first-child, .folio-cell:last-child { padding-left: 0; padding-right: 0; }
        .folio-cell:nth-last-child(-n+2) { border-bottom: 1px solid var(--border); }
        .folio-cell:last-child { border-bottom: none; }
        .docs-editorial-grid { grid-template-columns: 1fr; }
        .hero-cta-row .btn { flex: 1 1 auto; }
        .doc-actions { flex-direction: column; align-items: stretch; }
        .cert-modal-header { flex-wrap: wrap; }
        .cert-modal-actions .btn span { display: none; }
    }
</style>
@endsection

@section('content')
@php
    $isVi = app()->getLocale() === 'vi';

    // Helper: Map DB Skills cleanly into 8 clear engineering categories without dropping any row
    $skillBuckets = [
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
            if ($catLow === 'languages') {
                $skillBuckets['languages']['items'][] = $sk;
            } elseif ($catLow === 'frontend' || str_contains($nameLow, 'next') || str_contains($nameLow, 'responsive') || str_contains($nameLow, 'tailwind') || str_contains($nameLow, 'blade')) {
                $skillBuckets['frontend']['items'][] = $sk;
            } elseif ($catLow === 'backend') {
                $skillBuckets['backend']['items'][] = $sk;
            } elseif ($catLow === 'database') {
                $skillBuckets['databases']['items'][] = $sk;
            } elseif (str_contains($nameLow, 'rag') || str_contains($nameLow, 'llm') || str_contains($nameLow, 'vector') || str_contains($nameLow, 'ai ') || $nameLow === 'ai') {
                $skillBuckets['ai']['items'][] = $sk;
            } elseif (str_contains($nameLow, 'docker') || str_contains($nameLow, 'aws') || str_contains($nameLow, 'nginx') || str_contains($nameLow, 'cpanel') || str_contains($nameLow, 'ci/cd') || $nameLow === 'firebase') {
                $skillBuckets['devops']['items'][] = $sk;
            } elseif (in_array($catLow, ['api development', 'software engineering', 'authentication']) || str_contains($nameLow, 'websocket') || str_contains($nameLow, 'socket.io') || str_contains($nameLow, 'payment') || str_contains($nameLow, 'auth')) {
                $skillBuckets['architecture']['items'][] = $sk;
            } else {
                $skillBuckets['tools']['items'][] = $sk;
            }
        }
    }
    $skillBuckets = array_filter($skillBuckets, fn($b) => count($b['items']) > 0);
@endphp

<!-- 1. ASYMMETRIC EDITORIAL HERO -->
<section class="hero-editorial">
    <div class="container">
        <div class="hero-top-ledger hero-animate">
            <span>{{ $isVi ? 'TP. HỒ CHÍ MINH, VIỆT NAM — UTC+7' : 'HO CHI MINH CITY, VIETNAM — UTC+7' }}</span>
            <span class="availability-pill">
                <span class="availability-dot" aria-hidden="true"></span>
                <span>{{ __('Available for opportunities') }}</span>
            </span>
        </div>

        <div class="hero-grid hero-animate">
            <div>
                <div class="hero-role-eyebrow">{{ __('Hồ Thành Thiện') }} // Full-Stack Software Engineer</div>
                <h1 class="hero-title">
                    @if($isVi)
                        Xây dựng hệ thống web <span class="editorial-italic">bền vững</span>, tối ưu và có chiều sâu.
                    @else
                        Engineering <span class="editorial-italic">resilient</span> web software &amp; applied AI systems.
                    @endif
                </h1>

                <p class="hero-summary">
                    {{ __('Aspiring Fullstack Developer with hands-on experience in building web applications through academic and personal projects. Skilled in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs, with a solid foundation in database design and software development.') }}
                </p>

                <div class="hero-cta-row">
                    <a href="#featured-projects" class="btn btn-primary">
                        <span>{{ __('Explore Projects') }}</span>
                        <i class="fas fa-arrow-down" aria-hidden="true"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline">
                        <span>{{ __('Contact Me') }}</span>
                    </a>
                    <a href="{{ route('certificates') }}" class="btn btn-outline">
                        <i class="fas fa-file-lines" aria-hidden="true"></i>
                        <span>{{ __('Credentials') }}</span>
                    </a>
                </div>

                <div class="hero-social-strip">
                    <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="hero-social-link">
                        <i class="fab fa-github"></i>
                        <span>github.com/hoThanhThien</span>
                    </a>
                    <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="hero-social-link">
                        <i class="fab fa-linkedin"></i>
                        <span>LinkedIn</span>
                    </a>
                    <a href="mailto:hothanhthien119@gmail.com" class="hero-social-link">
                        <i class="fas fa-envelope"></i>
                        <span>hothanhthien119@gmail.com</span>
                    </a>
                    <a href="tel:0375227764" class="hero-social-link">
                        <i class="fas fa-phone-alt"></i>
                        <span>0375227764</span>
                    </a>
                </div>
            </div>

            <!-- Architectural Engineer Dossier Plate -->
            <aside class="dossier-plate" aria-label="{{ __('Engineering Dossier') }}">
                <div class="dossier-top">
                    <span>DOSSIER // 2026</span>
                    <span>VERIFIED PORTFOLIO</span>
                </div>

                <div class="dossier-id-head">
                    <div class="dossier-monogram" aria-hidden="true">
                        <span>HT</span>
                    </div>
                    <div>
                        <div class="dossier-name">{{ __('Hồ Thành Thiện') }}</div>
                        <div class="dossier-subrole">B.Sc. Information Technology · UTH</div>
                    </div>
                </div>

                <div class="dossier-rows">
                    <div class="dossier-row">
                        <span class="dossier-key">{{ __('Focus Stack') }}</span>
                        <span class="dossier-val">Laravel · Spring Boot · React · RAG</span>
                    </div>
                    <div class="dossier-row">
                        <span class="dossier-key">{{ __('Education') }}</span>
                        <span class="dossier-val">{{ __('University of Transport Ho Chi Minh City') }}</span>
                    </div>
                    <div class="dossier-row">
                        <span class="dossier-key">{{ __('Verified Record') }}</span>
                        <span class="dossier-val">GPA 4.0/4.0 · {{ $isVi ? 'Xuất sắc (3.6/4.0)' : 'Excellent (3.6/4.0)' }}</span>
                    </div>
                    <div class="dossier-row">
                        <span class="dossier-key">{{ __('Website') }}</span>
                        <span class="dossier-val"><a href="https://hothanhthien.io.vn/" target="_blank" rel="noopener">hothanhthien.io.vn</a></span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- 2. COMPACT EDITORIAL ACHIEVEMENTS STRIP -->
<section class="achievements-folio" aria-label="{{ __('Editorial Highlights') }}">
    <div class="container">
        <div class="folio-grid" data-stagger>
            <div class="folio-cell">
                <div class="folio-meta">01 / {{ $isVi ? 'HỌC KỲ GẦN NHẤT' : 'LATEST SEMESTER' }}</div>
                <div class="folio-num">4.0<span>/4.0</span></div>
                <div class="folio-title">{{ $isVi ? 'GPA Tuyệt Đối Học Kỳ' : 'Perfect Semester GPA' }}</div>
                <div class="folio-caption">{{ $isVi ? 'Kỷ luật cao & tư duy thuật toán chuyên sâu' : 'Demonstrating discipline & algorithmic mastery' }}</div>
            </div>

            <div class="folio-cell">
                <div class="folio-meta">02 / {{ $isVi ? 'TỐT NGHIỆP ĐẠI HỌC' : 'CUMULATIVE RECORD' }}</div>
                <div class="folio-num">3.6<span>/4.0</span></div>
                <div class="folio-title">{{ $isVi ? 'Xếp Loại Xuất Sắc' : 'Excellent Classification' }}</div>
                <div class="folio-caption">{{ $isVi ? 'Cử nhân Công nghệ Thông tin · ĐH GTVT TP.HCM (UTH)' : 'B.Sc. Information Technology · UTH (2023–2026)' }}</div>
            </div>

            <div class="folio-cell">
                <div class="folio-meta">03 / {{ $isVi ? 'GIẢI THƯỞNG THIẾT KẾ' : 'DESIGN RECOGNITION' }}</div>
                <div class="folio-num">2<span style="font-size:0.42em">nd</span></div>
                <div class="folio-title">{{ $isVi ? 'Giải Nhì Thiết Kế Logo' : '2nd Prize Logo Design' }}</div>
                <div class="folio-caption">{{ $isVi ? 'Cuộc thi Sáng tác Logo Viện/Khoa CNTT · 2025' : 'UTH Faculty of IT Identity Competition · 2025' }}</div>
            </div>

            <div class="folio-cell">
                <div class="folio-meta">04 / {{ $isVi ? 'CỘNG ĐỒNG KỸ THUẬT' : 'ENGINEERING COMMUNITY' }}</div>
                <div class="folio-num">GDC</div>
                <div class="folio-title">{{ $isVi ? 'Google Developer Chapter' : 'Google Developer Chapter' }}</div>
                <div class="folio-caption">{{ $isVi ? 'Thành viên tích cực tại ĐH GTVT TP.HCM' : 'Active chapter member at UTH Campus' }}</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. TECHNICAL SKILLS ARCHITECTURE (8 CLEAN GROUPS, ZERO FAKE BARS) -->
@if(count($skillBuckets))
<section class="section" id="skills">
    <div class="container">
        <div class="editorial-head" data-reveal>
            <div class="editorial-head-left">
                <div class="editorial-kicker">01 // CAPABILITIES MATRIX</div>
                <h2>{!! __('heading_my_skills') !!}</h2>
                <p>{{ __('Technologies and tools I work with every day') }}</p>
            </div>
            <div class="mono-label">{{ count($skillBuckets) }} {{ $isVi ? 'NHÓM CHUYÊN MÔN' : 'TECHNICAL DOMAINS' }}</div>
        </div>

        <div class="skill-filter-row" role="tablist" aria-label="{{ __('heading_my_skills') }}">
            <button type="button" class="skill-filter-btn active" data-skill-filter="all">
                <span>{{ __('All Stacks') }}</span>
            </button>
            @foreach($skillBuckets as $bucketKey => $bucket)
                <button type="button" class="skill-filter-btn" data-skill-filter="{{ $bucketKey }}">
                    <span>{{ $bucket['label'] }}</span>
                    <span class="skill-count-pill">({{ count($bucket['items']) }})</span>
                </button>
            @endforeach
        </div>

        <div class="skills-architecture-grid" id="skillsGrid" data-stagger>
            @php $groupNum = 1; @endphp
            @foreach($skillBuckets as $bucketKey => $bucket)
                @php
                    $detailedItems = array_filter($bucket['items'], fn($item) => !empty(trim((string) $item->details)));
                @endphp
                <article class="skill-group-card" data-skill-group="{{ $bucketKey }}">
                    <div class="skill-group-head">
                        <div class="skill-group-title-wrap">
                            <span class="skill-group-icon" aria-hidden="true"><i class="{{ $bucket['icon'] }}"></i></span>
                            <h3 class="skill-group-title">{{ $bucket['label'] }}</h3>
                        </div>
                        <span class="skill-group-index">{{ str_pad($groupNum++, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="skill-chip-cloud">
                        @foreach($bucket['items'] as $skill)
                            <span class="skill-tech-chip" title="{{ $skill->details ?: $skill->name }}">
                                <i class="{{ $skill->icon ?: 'fas fa-check' }}" aria-hidden="true"></i>
                                <span>{{ $skill->name }}</span>
                            </span>
                        @endforeach
                    </div>

                    @if(count($detailedItems))
                    <details class="skill-notes-details">
                        <summary class="skill-notes-summary">
                            <i class="fas fa-chevron-down" aria-hidden="true"></i>
                            <span>{{ __('Technical notes') }} ({{ count($detailedItems) }})</span>
                        </summary>
                        <div class="skill-notes-list">
                            @foreach($detailedItems as $detailSkill)
                                <div class="skill-note-row">
                                    <strong>{{ $detailSkill->name }}:</strong> {{ $detailSkill->details }}
                                </div>
                            @endforeach
                        </div>
                    </details>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 4. WORK EXPERIENCE TIMELINE -->
@if($experiences->count())
<section class="section section--alt" id="experience">
    <div class="container">
        <div class="experience-split">
            <div class="experience-sticky-col" data-reveal>
                <div class="editorial-kicker">02 // CAREER CHRONICLE</div>
                <h2>{!! __('heading_experience') !!}</h2>
                <p>{{ __('My professional journey so far') }}</p>
                <a href="{{ route('about') }}" class="btn btn-outline btn-sm">
                    <span>{{ __('About') }}</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
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
                            <p class="exp-summary-p">{{ $exp->localized_description }}</p>
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
    </div>
</section>
@endif

<!-- 5. FEATURED PROJECTS SHOWCASE (ALTERNATING ASYMMETRIC LAYOUT) -->
@if($featuredProjects->count())
<section class="section" id="featured-projects">
    <div class="container">
        <div class="editorial-head" data-reveal>
            <div class="editorial-head-left">
                <div class="editorial-kicker">03 // {{ __('Selected Works') }}</div>
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
                    $rawDesc = (string) ($project->clean_description ?? $project->description);
                    // Clean raw structured prefix safely if present in seeded strings without mutating core meaning
                    $cleanBody = preg_replace('/^[^|]+\|\s*\d{2}\/\d{4}\s*-\s*(?:\d{2}\/\d{4}|Present)\s*/u', '', $rawDesc);
                    $cleanBody = str_ireplace(['+Project Description:', 'Project Description:'], '', (string) $cleanBody);
                    $cleanBody = trim((string) $cleanBody);
                    $hostLabel = $project->demo_url
                        ? preg_replace('#^https?://#', '', rtrim($project->demo_url, '/'))
                        : ($project->github_url ? preg_replace('#^https?://#', '', rtrim($project->github_url, '/')) : 'architecture.spec');
                @endphp
                <article class="project-showcase-row {{ $index % 2 === 1 ? 'is-reversed' : '' }}" data-reveal>
                    <!-- Visual / Architectural Specimen Plate -->
                    <a href="{{ route('projects.show', $project->id) }}" class="project-showcase-media" aria-label="{{ $project->title }}">
                        @if($project->image)
                            <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="specimen-plate" style="display:none;">
                                <div class="specimen-topbar">
                                    <span class="specimen-dots"><span></span><span></span><span></span></span>
                                    <span class="specimen-host">{{ $hostLabel }}</span>
                                </div>
                                <div class="specimen-center">
                                    <div class="specimen-index">PRJ // {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="specimen-title">{{ $project->title }}</div>
                                </div>
                                <div class="specimen-footer">
                                    @foreach($project->technologies->take(5) as $tech)
                                        <span class="specimen-pill">{{ $tech->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="specimen-plate">
                                <div class="specimen-topbar">
                                    <span class="specimen-dots"><span></span><span></span><span></span></span>
                                    <span class="specimen-host">{{ $hostLabel }}</span>
                                </div>
                                <div class="specimen-center">
                                    <div class="specimen-index">PRJ // {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                                    <div class="specimen-title">{{ $project->title }}</div>
                                </div>
                                <div class="specimen-footer">
                                    @foreach($project->technologies->take(5) as $tech)
                                        <span class="specimen-pill">{{ $tech->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </a>

                    <!-- Case Study Info -->
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
                            {{ Str::limit($cleanBody !== '' ? $cleanBody : strip_tags($project->description), 240) }}
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

<!-- 6. CREDENTIALS & VERIFIED DOCUMENTS -->
@if($certificates->count())
<section class="section section--alt" id="credentials">
    <div class="container">
        <div class="editorial-head" data-reveal>
            <div class="editorial-head-left">
                <div class="editorial-kicker">04 // CREDENTIALS VAULT</div>
                <h2>{!! __('heading_credentials') !!}</h2>
                <p>{{ __('credentials_subtitle') }}</p>
            </div>
            <a href="{{ route('certificates') }}" class="btn btn-outline btn-sm">
                <span>{{ __('View All Documents') }}</span>
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        <div class="docs-editorial-grid" data-stagger>
            @foreach($certificates as $cert)
                <article class="doc-plate">
                    <div class="doc-preview-frame">
                        @if($cert->thumbnail && !$cert->thumbnail_is_pdf)
                            <img src="{{ $cert->thumbnail_url }}" alt="{{ $cert->title }}" loading="lazy">
                        @elseif(!$cert->is_pdf)
                            <img src="{{ $cert->file_url }}" alt="{{ $cert->title }}" loading="lazy">
                        @endif

                        <div class="doc-type-row">
                            <span class="doc-type-badge is-{{ $cert->category }}">
                                <i class="{{ $cert->category_icon }}" aria-hidden="true"></i>
                                <span>{{ $cert->category_label }}</span>
                            </span>
                            @if($cert->issue_date)
                                <span class="doc-year-mono">{{ $cert->issue_date }}</span>
                            @endif
                        </div>

                        @if($cert->is_pdf && (!$cert->thumbnail || $cert->thumbnail_is_pdf))
                            <div class="doc-paper-lines">
                                <span><i class="fas fa-file-pdf" style="color:#B91C1C" aria-hidden="true"></i> PDF {{ $cert->file_size ? '· ' . $cert->file_size : '' }}</span>
                                <span>{{ $isVi ? 'Đã xác thực' : 'Verified' }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="doc-body">
                        <h3 class="doc-title">{{ $cert->title }}</h3>
                        @if($cert->issuer)
                            <div class="doc-issuer">{{ $cert->issuer }}</div>
                        @endif
                        <p class="doc-desc">{{ Str::limit($cert->description, 120) }}</p>

                        <div class="doc-actions">
                            <button type="button" class="btn btn-primary btn-sm" onclick="openHomeCertModal('{{ $cert->file_url }}', '{{ addslashes($cert->title) }}', '{{ $cert->file_type }}')">
                                <i class="fas fa-eye" aria-hidden="true"></i>
                                <span>{{ __('View Document') }}</span>
                            </button>
                            <a href="{{ $cert->file_url }}" download class="btn btn-outline btn-sm">
                                <i class="fas fa-download" aria-hidden="true"></i>
                                <span>{{ __('Download') }}</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- 7. RECENT BLOG ARTICLES (EDITORIAL MAGAZINE LAYOUT) -->
@if($recentPosts->count())
<section class="section" id="blog">
    <div class="container">
        <div class="editorial-head" data-reveal>
            <div class="editorial-head-left">
                <div class="editorial-kicker">05 // {{ __('Engineering Journal') }}</div>
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
                        <img src="{{ $leadPost->thumbnail_url }}" alt="{{ $leadPost->title }}" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="blog-typographic-cover" style="display:none;">
                            <span class="blog-cover-label">{{ __('Latest Article') }}</span>
                            <div class="blog-cover-title">{{ $leadPost->title }}</div>
                        </div>
                    @else
                        <div class="blog-typographic-cover">
                            <span class="blog-cover-label">{{ __('Latest Article') }}</span>
                            <div class="blog-cover-title">{{ $leadPost->title }}</div>
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
                        <p class="blog-lead-excerpt">{{ Str::limit($leadPost->excerpt, 165) }}</p>
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
                        <p style="font-size:0.9rem; color:var(--text-secondary); line-height:1.58;">{{ Str::limit($post->excerpt, 125) }}</p>
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

<!-- 8. EDITORIAL CLOSING CTA BAND -->
<section class="cta-dark-band" data-reveal>
    <div class="container">
        <div class="cta-grid">
            <div>
                <div class="editorial-kicker" style="color:#93C5FD;">06 // {{ __('Let\'s work together') }}</div>
                <h2>{!! __('cta_heading') !!}</h2>
                <p>{{ __("I'm always open to interesting projects and opportunities.") }}</p>
            </div>

            <div class="cta-right-actions">
                <a href="{{ route('contact') }}" class="btn btn-accent">
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
// Interactive Skill Category Filter Tabs
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

// Accessible Home Certificate Viewer Modal
let lastHomeCertTrigger = null;

function openHomeCertModal(url, title, type) {
    lastHomeCertTrigger = document.activeElement;
    const modal = document.getElementById('homeCertModal');
    const titleEl = document.getElementById('homeCertModalTitleText');
    const bodyEl = document.getElementById('homeCertModalBody');
    const extBtn = document.getElementById('homeCertModalExt');
    const dlBtn = document.getElementById('homeCertModalDl');

    if (titleEl) titleEl.textContent = title;
    if (extBtn) extBtn.href = url;
    if (dlBtn) dlBtn.href = url;

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
