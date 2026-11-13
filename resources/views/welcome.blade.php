@extends('layouts.app')

@section('title', __('Hồ Thành Thiện') . ' | ' . (app()->getLocale() == 'vi' ? 'Kỹ sư Full-Stack Web Developer' : 'Full-Stack Software Engineer'))
@section('description', __('Aspiring Fullstack Developer with hands-on experience in building web applications through academic and personal projects. Skilled in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs, with a solid foundation in database design and software development.'))
@section('keywords', app()->getLocale() == 'vi' ? 'Hồ Thành Thiện, Kỹ sư Full-Stack, Lập trình viên Web, Laravel Developer Việt Nam, PHP Developer, JavaScript, React, Odoo, RAG LLM, Clean Architecture, Tuyển dụng lập trình viên' : 'Ho Thanh Thien, Full-Stack Developer, Web Developer, Laravel Software Engineer, PHP Developer Vietnam, JavaScript, React, Odoo, RAG LLM, Clean Code Architecture')

@section('styles')
<style>
    /* ===== HERO: ASYMMETRIC EDITORIAL ENGINEERING BRIEF ===== */
    .hero {
        padding: 4.5rem 0 4rem;
        background: var(--bg-primary);
        border-bottom: 1px solid var(--border);
    }
    .hero-grid-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.25fr) minmax(0, 0.9fr);
        gap: 3.5rem;
        align-items: start;
    }
    .hero-availability {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.3rem 0.75rem;
        border-radius: 6px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-size: 0.84rem;
        font-weight: 500;
        text-decoration: none;
        margin-bottom: 1.35rem;
        transition: border-color 0.16s ease, color 0.16s ease;
    }
    .hero-availability:hover {
        border-color: var(--text-primary);
        color: var(--text-primary);
    }
    .hero-availability .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--green);
        flex-shrink: 0;
    }
    .hero-content h1 {
        font-size: clamp(2rem, 3.8vw, 2.95rem);
        font-weight: 700;
        line-height: 1.18;
        letter-spacing: -0.03em;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
        max-width: 22ch;
    }
    .hero-content p {
        font-size: 1.05rem;
        color: var(--text-secondary);
        line-height: 1.7;
        max-width: 60ch;
        margin-bottom: 1.85rem;
    }
    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
    }

    /* Engineering Profile Spec Sheet */
    .spec-sheet {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
    }
    .spec-sheet-header {
        padding: 0.9rem 1.25rem;
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .spec-sheet-title {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--text-primary);
    }
    .spec-sheet-meta {
        font-size: 0.82rem;
        color: var(--text-muted);
    }
    .spec-row {
        padding: 0.95rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: grid;
        grid-template-columns: 120px minmax(0, 1fr);
        gap: 1rem;
        align-items: baseline;
    }
    .spec-row:last-child {
        border-bottom: none;
    }
    .spec-label {
        font-size: 0.84rem;
        font-weight: 500;
        color: var(--text-muted);
    }
    .spec-value {
        font-size: 0.92rem;
        font-weight: 500;
        color: var(--text-primary);
        line-height: 1.5;
    }
    .spec-value strong {
        font-weight: 600;
    }
    .spec-value a {
        color: var(--accent);
        text-decoration: none;
        overflow-wrap: anywhere;
    }
    .spec-value a:hover {
        text-decoration: underline;
    }

    /* ===== ACHIEVEMENTS ===== */
    .achievements-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }
    .achievement-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .achievement-card:hover {
        border-color: var(--border-strong);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .achievement-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 1.1rem;
    }
    .achievement-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: var(--bg-primary);
        border: 1px solid var(--border);
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .achievement-icon-box svg {
        width: 18px;
        height: 18px;
    }
    .achievement-year {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
        padding: 0.2rem 0.6rem;
        border-radius: 5px;
        background: var(--bg-primary);
        border: 1px solid var(--border);
    }
    .achievement-kicker {
        font-size: 0.84rem;
        font-weight: 600;
        color: var(--accent);
        margin-bottom: 0.4rem;
    }
    .achievement-title {
        font-size: 1.06rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.38;
        margin-bottom: 0.6rem;
    }
    .achievement-desc {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.15rem;
        flex: 1;
    }
    .achievement-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        padding-top: 0.9rem;
        border-top: 1px solid var(--border);
        margin-top: auto;
    }

    /* ===== SKILLS INDEX ===== */
    .skills-section {
        background: var(--bg-card);
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }
    .skills-categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr));
        gap: 2.25rem 2.5rem;
    }
    .skill-category-block {
        display: flex;
        flex-direction: column;
    }
    .skill-category-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--text-primary);
        padding-bottom: 0.75rem;
        margin-bottom: 0.9rem;
        border-bottom: 1.5px solid var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }
    .skill-category-title i {
        color: var(--accent);
        font-size: 0.9rem;
    }
    .skill-items-list {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .skill-item {
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border);
    }
    .skill-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .skill-item-name {
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .skill-item-name i {
        color: var(--text-muted);
        font-size: 0.84rem;
        width: 16px;
        text-align: center;
    }
    .skill-item-details {
        font-size: 0.85rem;
        color: var(--text-secondary);
        line-height: 1.55;
        margin-top: 0.2rem;
        padding-left: 1.45rem;
    }

    /* ===== EXPERIENCE LEDGER ===== */
    .experience-ledger {
        display: flex;
        flex-direction: column;
        border-top: 1px solid var(--border);
    }
    .exp-row {
        display: grid;
        grid-template-columns: 240px minmax(0, 1fr);
        gap: 2rem;
        padding: 2rem 0;
        border-bottom: 1px solid var(--border);
        align-items: start;
    }
    .exp-meta-col {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .exp-date {
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--text-muted);
    }
    .exp-position {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--accent);
        line-height: 1.4;
    }
    .exp-body-col {
        min-width: 0;
    }
    .exp-company {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }
    .exp-bullets {
        list-style: none;
        padding-left: 0;
        margin: 0 0 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        max-width: 68ch;
    }
    .exp-bullets li {
        position: relative;
        padding-left: 1.1rem;
        color: var(--text-secondary);
        font-size: 0.93rem;
        line-height: 1.62;
    }
    .exp-bullets li::before {
        content: '';
        position: absolute;
        left: 0.1rem;
        top: 0.62rem;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--text-muted);
    }
    .exp-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    /* ===== PROJECTS ===== */
    .projects-section {
        background: var(--bg-card);
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }
    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 320px), 1fr));
        gap: 1.5rem;
    }
    .project-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
        display: flex;
        flex-direction: column;
    }
    .project-card:hover {
        border-color: var(--border-strong);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .project-image {
        height: 190px;
        overflow: hidden;
        position: relative;
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border);
    }
    .project-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform var(--duration-base) var(--ease-out);
    }
    .project-card:hover .project-image img {
        transform: scale(1.025);
    }
    .project-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .project-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-bottom: 0.75rem;
    }
    .project-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.45rem;
        line-height: 1.35;
    }
    .project-desc {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        flex: 1;
        margin-bottom: 1.25rem;
    }
    .project-links {
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
        flex-wrap: wrap;
        width: 100%;
    }
    .project-links .btn {
        height: 36px;
        min-height: 36px;
        padding: 0 0.75rem;
        font-size: 0.84rem;
        font-weight: 600;
        border-radius: 7px;
        gap: 0.4rem;
        white-space: nowrap;
        flex: 1 1 calc(50% - 0.25rem);
        justify-content: center;
    }
    .project-links.has-3-links .btn:first-child,
    .project-links:has(.btn:nth-child(3)) .btn:first-child {
        flex: 1 1 100%;
        width: 100%;
    }
    .project-links.has-3-links .btn:not(:first-child),
    .project-links:has(.btn:nth-child(3)) .btn:not(:first-child) {
        flex: 1 1 calc(50% - 0.25rem);
    }

    /* ===== CREDENTIALS SHOWCASE (UNIFIED QUIET DESIGN) ===== */
    .home-cert-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    .home-cert-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .home-cert-card:hover {
        border-color: var(--border-strong);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .home-cert-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 1.1rem;
    }
    .home-cert-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--bg-primary);
        border: 1px solid var(--border);
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .home-cert-icon svg {
        width: 19px;
        height: 19px;
    }
    .home-cert-badges {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.4rem;
    }
    .home-cert-cat {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.22rem 0.6rem;
        border-radius: 5px;
        background: var(--bg-secondary);
        color: var(--text-secondary);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }
    .home-cert-year {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.22rem 0.55rem;
        border-radius: 5px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-muted);
        white-space: nowrap;
    }
    .home-cert-title {
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.4;
        min-height: 3.05rem;
        margin-bottom: 0.55rem;
        color: var(--text-primary);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .home-cert-issuer {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: 0.8rem;
    }
    .home-cert-issuer svg {
        width: 14px;
        height: 14px;
        color: var(--text-muted);
        flex-shrink: 0;
    }
    .home-cert-issuer span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .home-cert-desc {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .home-cert-footer {
        padding-top: 0.95rem;
        margin-top: auto;
        border-top: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .home-cert-footer-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .home-cert-file-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: var(--text-secondary);
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .home-cert-verified {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        color: var(--green);
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .home-cert-btns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.55rem;
        width: 100%;
    }
    .home-cert-btns .btn {
        width: 100%;
        min-height: 38px;
        height: 38px;
        font-size: 0.84rem;
        font-weight: 600;
        padding: 0 0.75rem;
        border-radius: 7px;
        gap: 0.4rem;
        white-space: nowrap;
    }

    /* Quick Preview Modal */
    .home-cert-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        background: rgba(18, 22, 31, 0.68);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity var(--duration-base) var(--ease-out), visibility var(--duration-base);
    }
    .home-cert-modal.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .home-cert-modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        width: 95vw;
        max-width: 1120px;
        height: 88vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 24px 48px -12px rgba(18, 22, 31, 0.35);
        transform: scale(0.97) translateY(8px);
        transition: transform var(--duration-base) var(--ease-out);
    }
    .home-cert-modal.active .home-cert-modal-box {
        transform: scale(1) translateY(0);
    }
    .home-cert-modal-header {
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        background: var(--bg-card);
    }
    .home-cert-modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        min-width: 0;
        flex: 1;
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }
    .home-cert-modal-title span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .home-cert-modal-body {
        flex: 1;
        background: var(--bg-secondary);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .home-cert-modal-body iframe,
    .home-cert-modal-body img {
        width: 100%;
        height: 100%;
        border: none;
        object-fit: contain;
    }

    /* ===== BLOG ===== */
    .blog-section {
        background: var(--bg-card);
        border-top: 1px solid var(--border);
    }
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr));
        gap: 1.5rem;
    }
    .blog-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: border-color var(--duration-fast) ease, box-shadow var(--duration-base) var(--ease-out), transform var(--duration-base) var(--ease-out);
    }
    .blog-card:hover {
        border-color: var(--border-strong);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .blog-image {
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border);
    }
    .blog-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform var(--duration-base) var(--ease-out);
    }
    .blog-card:hover .blog-image img {
        transform: scale(1.025);
    }
    .blog-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .blog-date {
        color: var(--text-muted);
        font-size: 0.82rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .blog-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.4;
        margin-bottom: 0.5rem;
    }
    .blog-excerpt {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.6;
    }

    /* ===== CTA BAND ===== */
    .cta-section {
        padding: 4rem 0;
        background: var(--bg-primary);
        border-top: 1px solid var(--border);
    }
    .cta-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        flex-wrap: wrap;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 2.25rem 2.5rem;
    }
    .cta-box h2 {
        font-size: clamp(1.4rem, 2.8vw, 1.85rem);
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--text-primary);
        margin-bottom: 0.4rem;
    }
    .cta-box p {
        color: var(--text-secondary);
        font-size: 0.98rem;
        max-width: 58ch;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .hero-grid-layout {
            grid-template-columns: 1fr;
            gap: 2.25rem;
        }
        .achievements-grid,
        .projects-grid,
        .home-cert-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
        .achievement-card:last-child:nth-child(odd),
        .home-cert-card:last-child:nth-child(odd) {
            grid-column: 1 / -1;
            max-width: 560px;
            width: 100%;
        }
    }
    @media (max-width: 768px) {
        .hero { padding: 3rem 0 2.75rem; }
        .exp-row {
            grid-template-columns: 1fr;
            gap: 0.75rem;
            padding: 1.5rem 0;
        }
        .cta-box { padding: 1.75rem 1.5rem; }
    }
    @media (max-width: 640px) {
        .achievements-grid,
        .projects-grid,
        .home-cert-grid,
        .blog-grid {
            grid-template-columns: 1fr;
            gap: 1.15rem;
        }
        .achievement-card:last-child:nth-child(odd),
        .home-cert-card:last-child:nth-child(odd) {
            max-width: 100%;
        }
        .home-cert-title {
            min-height: auto;
        }
        .spec-row {
            grid-template-columns: 1fr;
            gap: 0.25rem;
        }
        .home-cert-modal {
            padding: 0.5rem;
        }
        .home-cert-modal-box {
            width: 100%;
            height: 92vh;
        }
        #homeCertModalExt span,
        #homeCertModalDl span {
            display: none;
        }
    }
    @media (max-width: 480px) {
        .hero-actions { flex-direction: column; align-items: stretch; }
        .hero-actions .btn { width: 100%; justify-content: center; }
        .cta-box .btn { width: 100%; }
        .project-links .btn { min-width: 0; }
        .project-links.has-3-links .btn:first-child,
        .project-links:has(.btn:nth-child(3)) .btn:first-child { flex: 1 1 100%; width: 100%; }
        .project-links.has-3-links .btn:not(:first-child),
        .project-links:has(.btn:nth-child(3)) .btn:not(:first-child) { flex: 1 1 calc(50% - 0.25rem); }
        .home-cert-btns { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="container">
        <div class="hero-grid-layout hero-animate">
            <div class="hero-content">
                <a href="{{ route('contact') }}" class="hero-availability">
                    <span class="status-dot" aria-hidden="true"></span>
                    <span>{{ __('Available for opportunities') }}</span>
                </a>

                <h1>{{ __("Programmers don't just") }} {{ __('write code,') }} {{ __('they create solutions.') }}</h1>

                <p>{{ __('Aspiring Fullstack Developer with hands-on experience in building web applications through academic and personal projects. Skilled in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs, with a solid foundation in database design and software development.') }}</p>

                <div class="hero-actions">
                    <a href="{{ route('projects.index') }}" class="btn btn-primary">
                        {{ __('View My Work') }}
                    </a>
                    <a href="{{ route('certificates') }}" class="btn btn-outline">
                        <i class="fas fa-file-pdf"></i> {{ __('Brochure & Giấy khen') }}
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline">
                        {{ __('Get In Touch') }}
                    </a>
                    <a href="tel:0375227764" class="btn btn-outline" title="{{ __('Phone') }}">
                        <i class="fas fa-phone-alt"></i> 0375227764
                    </a>
                </div>
            </div>

            <!-- Structured Profile Specification Ledger -->
            <aside class="spec-sheet" aria-label="Profile overview">
                <div class="spec-sheet-header">
                    <span class="spec-sheet-title">{{ __('Hồ Thành Thiện') }}</span>
                    <span class="spec-sheet-meta">{{ __('Fullstack Developer') }}</span>
                </div>
                <div class="spec-row">
                    <div class="spec-label">{{ __('Education') }}</div>
                    <div class="spec-value">
                        <strong>{{ __('University of Transport Ho Chi Minh City') }}</strong><br>
                        {{ __("Bachelor's Degree Information Technology") }}
                    </div>
                </div>
                <div class="spec-row">
                    <div class="spec-label">{{ app()->getLocale() == 'vi' ? 'Thành tích' : 'Standing' }}</div>
                    <div class="spec-value">{{ app()->getLocale() == 'vi' ? 'Tốt nghiệp Xuất sắc, GPA 3.6/4.0 (học kỳ gần nhất 4.0/4.0)' : 'Excellent Classification, Cumulative GPA 3.6/4.0 (Recent 4.0/4.0)' }}</div>
                </div>
                <div class="spec-row">
                    <div class="spec-label">{{ app()->getLocale() == 'vi' ? 'Công nghệ chính' : 'Core stack' }}</div>
                    <div class="spec-value">Laravel, Java Spring Boot, ReactJS, Python (RAG/LLM), Odoo ERP</div>
                </div>
                <div class="spec-row">
                    <div class="spec-label">{{ __('Location') }}</div>
                    <div class="spec-value">{{ __('Hồ Chí Minh, Việt Nam') }}</div>
                </div>
                <div class="spec-row">
                    <div class="spec-label">Email</div>
                    <div class="spec-value"><a href="mailto:hothanhthien119@gmail.com">hothanhthien119@gmail.com</a></div>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- ===== KEY ACHIEVEMENTS ===== -->
<section class="section">
    <div class="container">
        <div class="section-header-row" data-reveal>
            <div>
                <h2 class="section-title">{!! __('heading_achievements') !!}</h2>
                <p class="section-subtitle">{{ __('Academic recognition and competition awards') }}</p>
            </div>
        </div>

        <div class="achievements-grid" data-stagger>
            <!-- 1. GPA 4.0 -->
            <div class="achievement-card">
                <div class="achievement-top">
                    <div class="achievement-icon-box" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                    <span class="achievement-year">2026</span>
                </div>
                <div class="achievement-kicker">
                    {{ app()->getLocale() == 'vi' ? 'Học kỳ gần nhất, GPA 4.0/4.0' : 'Latest semester, GPA 4.0/4.0' }}
                </div>
                <h3 class="achievement-title">{{ __('GPA in Most Recent Semester — 4.0/4.0') }}</h3>
                <p class="achievement-desc">
                    {{ app()->getLocale() == 'vi' ? 'Đạt kết quả học tập xuất sắc với điểm GPA 4.0/4.0 trong học kỳ gần nhất, thể hiện tính kỷ luật cao và tư duy logic chuyên sâu.' : 'Achieved a perfect 4.0/4.0 GPA in the most recent semester, demonstrating high dedication, discipline, and strong software engineering competence.' }}
                </p>
                <div class="achievement-tags">
                    <span class="tag">{{ app()->getLocale() == 'vi' ? 'GPA tuyệt đối 4.0' : 'Perfect 4.0 GPA' }}</span>
                    <span class="tag">{{ app()->getLocale() == 'vi' ? 'Tư duy hệ thống' : 'Systems thinking' }}</span>
                </div>
            </div>

            <!-- 2. Excellent Graduation -->
            <div class="achievement-card">
                <div class="achievement-top">
                    <div class="achievement-icon-box" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                    </div>
                    <span class="achievement-year">2026</span>
                </div>
                <div class="achievement-kicker">
                    {{ app()->getLocale() == 'vi' ? 'Tốt nghiệp Xuất sắc tại UTH' : 'Excellent classification at UTH' }}
                </div>
                <h3 class="achievement-title">{{ __('Bachelor’s Degree – Excellent Graduation Classification') }}</h3>
                <p class="achievement-desc">
                    {{ app()->getLocale() == 'vi' ? 'Bằng Cử nhân Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH) với xếp loại Tốt nghiệp Xuất sắc.' : "Bachelor's Degree in Information Technology from University of Transport Ho Chi Minh City with Excellent Graduation Classification." }}
                </p>
                <div class="achievement-tags">
                    <span class="tag">{{ app()->getLocale() == 'vi' ? 'Loại Xuất sắc' : 'Excellent grade' }}</span>
                    <span class="tag">{{ app()->getLocale() == 'vi' ? 'Cử nhân CNTT' : 'IT Graduate' }}</span>
                </div>
            </div>

            <!-- 3. Logo Design Award -->
            <div class="achievement-card">
                <div class="achievement-top">
                    <div class="achievement-icon-box" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="6"></circle>
                            <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                        </svg>
                    </div>
                    <span class="achievement-year">2025</span>
                </div>
                <div class="achievement-kicker">
                    {{ app()->getLocale() == 'vi' ? 'Giải Nhì thiết kế cấp Viện' : 'Second prize in faculty contest' }}
                </div>
                <h3 class="achievement-title">{{ __('Second prize in the institute/faculty logo design competition') }}</h3>
                <p class="achievement-desc">
                    {{ __('Designed a competition-winning logo concept, demonstrating strong creativity, visual communication, and design thinking.') }}
                </p>
                <div class="achievement-tags">
                    <span class="tag">{{ app()->getLocale() == 'vi' ? 'Giải Nhì cấp Viện' : '2nd Prize' }}</span>
                    <span class="tag">{{ app()->getLocale() == 'vi' ? 'Tư duy thị giác' : 'Visual craft' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== SKILLS ===== -->
@if($skills->count())
<section class="section skills-section">
    <div class="container">
        <div class="section-header-row" data-reveal>
            <div>
                <h2 class="section-title">{!! __('heading_my_skills') !!}</h2>
                <p class="section-subtitle">{{ __('Technologies and tools I work with every day') }}</p>
            </div>
        </div>

        <div class="skills-categories-grid" data-stagger>
            @foreach($skills as $category => $categorySkills)
            <div class="skill-category-block">
                <h3 class="skill-category-title">
                    <i class="fas fa-layer-group"></i> {{ $category }}
                </h3>
                <div class="skill-items-list">
                    @foreach($categorySkills as $skill)
                    <div class="skill-item">
                        <div class="skill-item-name">
                            <i class="{{ $skill->icon ?: 'fas fa-circle-dot' }}"></i>
                            <span>{{ $skill->name }}</span>
                        </div>
                        @if($skill->details)
                        <div class="skill-item-details">{{ $skill->details }}</div>
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

<!-- ===== EXPERIENCE ===== -->
@if($experiences->count())
<section class="section">
    <div class="container">
        <div class="section-header-row" data-reveal>
            <div>
                <h2 class="section-title">{!! __('heading_experience') !!}</h2>
                <p class="section-subtitle">{{ __('My professional journey so far') }}</p>
            </div>
        </div>

        <div class="experience-ledger" data-stagger>
            @foreach($experiences as $exp)
            <div class="exp-row">
                <div class="exp-meta-col">
                    <div class="exp-date">{{ $exp->formatted_date }}</div>
                    <div class="exp-position">{{ $exp->localized_position }}</div>
                </div>
                <div class="exp-body-col">
                    <h3 class="exp-company">{{ $exp->localized_company }}</h3>
                    @php
                        $sentences = array_filter(array_map('trim', explode('.', $exp->localized_description)));
                    @endphp
                    @if(count($sentences) > 1)
                        <ul class="exp-bullets">
                            @foreach($sentences as $s)
                                @if(!empty($s))
                                <li>{{ $s }}.</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p style="color:var(--text-secondary); font-size:0.93rem; line-height:1.65; margin-bottom:1rem; max-width:68ch">{{ $exp->localized_description }}</p>
                    @endif

                    @if(count($exp->tech_tags))
                    <div class="exp-tags">
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

<!-- ===== FEATURED PROJECTS ===== -->
@if($featuredProjects->count())
<section class="section projects-section">
    <div class="container">
        <div class="section-header-row" data-reveal>
            <div>
                <h2 class="section-title">{!! __('heading_featured_projects') !!}</h2>
                <p class="section-subtitle">{{ __('Some of my recent and favourite work') }}</p>
            </div>
            <a href="{{ route('projects.index') }}" class="btn btn-outline btn-sm">
                {{ __('View All Projects') }}
            </a>
        </div>

        <div class="projects-grid" data-stagger>
            @foreach($featuredProjects as $project)
            <div class="project-card">
                <div class="project-image">
                    @if($project->image)
                        <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/og-image.webp') }}';">
                    @else
                        <img src="{{ asset('images/og-image.webp') }}" alt="{{ $project->title }} Thumbnail" loading="lazy" decoding="async">
                    @endif
                </div>
                <div class="project-body">
                    <div class="project-tags">
                        @foreach($project->technologies->take(5) as $tech)
                            <span class="tag">{{ $tech->name }}</span>
                        @endforeach
                    </div>
                    <div class="project-title">{{ $project->title }}</div>
                    <p class="project-desc">{{ Str::limit(strip_tags($project->clean_description ?? $project->description), 130) }}</p>
                    @php $has3Links = !empty($project->github_url) && !empty($project->demo_url); @endphp
                    <div class="project-links {{ $has3Links ? 'has-3-links' : '' }}">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary btn-sm">
                            {{ __('View') }}
                        </a>
                        @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                            <i class="fab fa-github"></i> {{ __('Code') }}
                        </a>
                        @endif
                        @if($project->demo_url)
                        <a href="{{ $project->demo_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                            <i class="fas fa-external-link-alt"></i> {{ __('Demo') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===== BROCHURE & CREDENTIALS SHOWCASE ===== -->
@if(isset($certificates) && $certificates->count())
<section class="section">
    <div class="container">
        <div class="section-header-row" data-reveal>
            <div>
                <h2 class="section-title">{!! __('heading_credentials') !!}</h2>
                <p class="section-subtitle">{{ __('credentials_subtitle') }}</p>
            </div>
            <a href="{{ route('certificates') }}" class="btn btn-outline btn-sm">
                {{ __('All Documents') }} ({{ \App\Models\Certificate::count() }})
            </a>
        </div>

        <div class="home-cert-grid" data-stagger>
            @foreach($certificates->take(3) as $cert)
            <div class="home-cert-card">
                <div class="home-cert-top">
                    <div class="home-cert-icon" aria-hidden="true">
                        @if($cert->category === 'brochure')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                <line x1="9" y1="7" x2="15" y2="7"></line>
                                <line x1="9" y1="11" x2="15" y2="11"></line>
                            </svg>
                        @elseif($cert->category === 'degree')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                            </svg>
                        @elseif($cert->category === 'award')
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="6"></circle>
                                <path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"></path>
                            </svg>
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                        @endif
                    </div>

                    <div class="home-cert-badges">
                        <span class="home-cert-cat">{{ $cert->category_label }}</span>
                        @if($cert->issue_date)
                            <span class="home-cert-year">{{ $cert->issue_date }}</span>
                        @endif
                    </div>
                </div>

                <h3 class="home-cert-title" title="{{ $cert->title }}">{{ $cert->title }}</h3>

                @if($cert->issuer)
                <div class="home-cert-issuer" title="{{ $cert->issuer }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V7l8-4v18"></path>
                        <path d="M19 21V11l-6-4"></path>
                    </svg>
                    <span>{{ $cert->issuer }}</span>
                </div>
                @endif

                <p class="home-cert-desc">{{ $cert->description }}</p>

                <div class="home-cert-footer">
                    <div class="home-cert-footer-top">
                        <span class="home-cert-file-chip">
                            <i class="fas {{ $cert->is_pdf ? 'fa-file-pdf' : 'fa-file-image' }}"></i>
                            {{ strtoupper($cert->file_type) }}{{ $cert->file_size ? ' (' . $cert->file_size . ')' : '' }}
                        </span>

                        @if(count($cert->extra_files_with_urls) > 0)
                            <span>+{{ count($cert->extra_files_with_urls) }} {{ app()->getLocale() == 'vi' ? 'tệp đính kèm' : 'attachments' }}</span>
                        @else
                            <span class="home-cert-verified">
                                <i class="fas fa-check-circle"></i>
                                {{ app()->getLocale() == 'vi' ? 'Đã xác thực' : 'Verified' }}
                            </span>
                        @endif
                    </div>

                    <div class="home-cert-btns">
                        <button type="button" class="btn btn-primary btn-sm"
                            onclick="openHomeCertModal('{{ $cert->file_url }}', '{{ addslashes($cert->title) }}', '{{ $cert->is_pdf ? 'pdf' : 'image' }}')">
                            <i class="fas fa-eye"></i>
                            <span>{{ __('View') }}</span>
                        </button>
                        <a href="{{ $cert->file_url }}" download class="btn btn-outline btn-sm">
                            <i class="fas fa-download"></i>
                            <span>{{ __('Download') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Home Quick Document Viewer Modal -->
<div class="home-cert-modal" id="homeCertModal" role="dialog" aria-modal="true" aria-labelledby="homeCertModalTitle" onclick="if(event.target===this) closeHomeCertModal()">
    <div class="home-cert-modal-box">
        <div class="home-cert-modal-header">
            <div class="home-cert-modal-title">
                <i class="fas fa-file-alt" style="color:var(--text-muted)"></i>
                <span id="homeCertModalTitle">{{ app()->getLocale() == 'vi' ? 'Tài liệu' : 'Document' }}</span>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem; flex-shrink:0">
                <a id="homeCertModalExt" href="#" target="_blank" rel="noopener" class="btn btn-outline btn-sm" title="{{ app()->getLocale() == 'vi' ? 'Mở tab mới' : 'Open tab' }}">
                    <i class="fas fa-external-link-alt"></i>
                    <span>{{ app()->getLocale() == 'vi' ? 'Mở tab mới' : 'Open tab' }}</span>
                </a>
                <a id="homeCertModalDl" href="#" download class="btn btn-primary btn-sm" title="{{ __('Download') }}">
                    <i class="fas fa-download"></i>
                    <span>{{ __('Download') }}</span>
                </a>
                <button type="button" id="homeCertModalCloseBtn" onclick="closeHomeCertModal()" aria-label="Close"
                    style="width:36px; height:36px; border-radius:7px; border:1px solid var(--border); background:var(--bg-primary); color:var(--text-secondary); cursor:pointer; display:inline-flex; align-items:center; justify-content:center; font-size:1.1rem">
                    &times;
                </button>
            </div>
        </div>
        <div class="home-cert-modal-body" id="homeCertModalBody"></div>
    </div>
</div>

<script>
    let lastFocusedCertTrigger = null;

    function openHomeCertModal(url, title, type) {
        lastFocusedCertTrigger = document.activeElement;
        const modal = document.getElementById('homeCertModal');
        const body = document.getElementById('homeCertModalBody');
        document.getElementById('homeCertModalTitle').textContent = title;
        document.getElementById('homeCertModalExt').href = url;
        document.getElementById('homeCertModalDl').href = url;
        body.innerHTML = type === 'pdf'
            ? `<iframe src="${url}#toolbar=1" type="application/pdf"></iframe>`
            : `<img src="${url}" alt="${title}">`;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            const closeBtn = document.getElementById('homeCertModalCloseBtn');
            if (closeBtn) closeBtn.focus();
        }, 50);
    }

    function closeHomeCertModal() {
        const modal = document.getElementById('homeCertModal');
        const body = document.getElementById('homeCertModalBody');
        if (!modal || !modal.classList.contains('active')) return;
        modal.classList.remove('active');
        setTimeout(() => {
            if (body && !modal.classList.contains('active')) body.innerHTML = '';
        }, 260);
        document.body.style.overflow = '';
        if (lastFocusedCertTrigger && typeof lastFocusedCertTrigger.focus === 'function') {
            lastFocusedCertTrigger.focus();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeHomeCertModal();
    });
</script>
@endif

<!-- ===== BLOG ===== -->
@if($recentPosts->count())
<section class="section blog-section">
    <div class="container">
        <div class="section-header-row" data-reveal>
            <div>
                <h2 class="section-title">{!! __('heading_recent_articles') !!}</h2>
                <p class="section-subtitle">{{ __('Thoughts, learnings and insights from my journey') }}</p>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn-outline btn-sm">
                {{ __('Read All Articles') }}
            </a>
        </div>

        <div class="blog-grid" data-stagger>
            @foreach($recentPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
                <div class="blog-image">
                    @if($post->thumbnail_url)
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='{{ asset('images/og-image.webp') }}';">
                    @endif
                </div>
                <div class="blog-body">
                    <div class="blog-date">
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
    </div>
</section>
@endif

<!-- ===== CTA ===== -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box" data-reveal>
            <div>
                <h2>{!! __('cta_heading') !!}</h2>
                <p>{{ __("I'm always open to interesting projects and opportunities.") }}</p>
            </div>
            <a href="{{ route('contact') }}" class="btn btn-primary">
                {{ __('Send Me a Message') }}
            </a>
        </div>
    </div>
</section>

@endsection
