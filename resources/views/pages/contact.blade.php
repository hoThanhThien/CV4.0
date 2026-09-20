@extends('layouts.app')

@section('title', __('Contact') . ' | ' . __('Hồ Thành Thiện'))
@section('description', __('Feel free to reach out for collaborations, project inquiries, or just to say hello!'))
@section('keywords', app()->getLocale() == 'vi' ? 'Liên hệ Hồ Thành Thiện, Tuyển dụng lập trình viên, Thuê lập trình viên Laravel, Hợp tác dự án Web' : 'Contact Ho Thanh Thien, Hire Full-Stack Developer, Web Development Collaboration, Software Engineer Inquiries')

@section('styles')
<style>
    .contact-hero {
        padding: 4.5rem 0 2rem; text-align: center;
        background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(124,58,237,0.15) 0%, transparent 70%);
    }
    .contact-hero h1 {
        font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 900;
        margin-bottom: 0.75rem; letter-spacing: -0.02em;
    }
    .contact-hero p {
        color: var(--text-secondary); font-size: 1.05rem; max-width: 600px; margin: 0 auto;
    }

    .contact-layout {
        display: grid; grid-template-columns: 1fr 1.35fr; gap: 2.5rem;
        align-items: start; padding-bottom: 4rem;
    }

    /* Left Info Cards */
    .info-group {
        display: flex; flex-direction: column; gap: 1.25rem;
    }
    .info-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; padding: 1.5rem;
        box-shadow: 0 4px 18px -2px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .info-card:hover {
        border-color: rgba(99, 102, 241, 0.4);
        transform: translateY(-3px);
        box-shadow: 0 12px 25px -5px rgba(99, 102, 241, 0.12);
    }
    .info-card-header {
        display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;
    }
    .info-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.2);
        color: var(--accent); display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem; flex-shrink: 0;
    }
    .info-card-title {
        font-size: 0.9rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.05em; color: var(--text-secondary);
    }
    .info-card-value {
        font-size: 1.05rem; font-weight: 600; color: var(--text-primary);
        word-break: break-word; text-decoration: none; display: block;
    }
    .info-card-value:hover {
        color: var(--accent);
    }
    .info-card-sub {
        font-size: 0.85rem; color: var(--text-muted); margin-top: 0.35rem;
    }

    .status-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.35rem 0.85rem; border-radius: 50px;
        background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3);
        color: #059669; font-size: 0.85rem; font-weight: 600;
    }
    .status-badge .dot {
        width: 8px; height: 8px; border-radius: 50%; background: #10b981;
        box-shadow: 0 0 8px #10b981; animation: pulse 2s infinite;
    }

    /* Right Form Card */
    .form-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 20px; padding: 2.25rem 2rem;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.06);
    }
    .form-card-title {
        font-size: 1.4rem; font-weight: 800; margin-bottom: 0.5rem;
        display: flex; align-items: center; gap: 0.6rem;
    }
    .form-card-title i { color: var(--accent); }
    .form-card-subtitle {
        color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 1.75rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-label {
        display: block; font-size: 0.88rem; font-weight: 600;
        margin-bottom: 0.45rem; color: var(--text-primary);
    }
    .form-label .required { color: #ef4444; }
    .form-control {
        width: 100%; padding: 0.85rem 1rem; border-radius: 12px;
        border: 1px solid var(--border); background: var(--bg-primary);
        color: var(--text-primary); font-size: 0.95rem; font-family: inherit;
        transition: all 0.25s ease;
    }
    .form-control:focus {
        outline: none; border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        background: #ffffff;
    }
    .form-control.is-invalid {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
    }
    .invalid-feedback {
        color: #ef4444; font-size: 0.82rem; margin-top: 0.35rem; display: block; font-weight: 500;
    }

    /* Google reCAPTCHA v3 Floating Badge & Footer Notice */
    .recaptcha-v3-text {
        font-size: 0.78rem;
        color: var(--text-muted);
        text-align: center;
        margin-top: 1rem;
        line-height: 1.5;
    }
    .recaptcha-v3-text a {
        color: var(--text-secondary);
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    .recaptcha-v3-text a:hover {
        color: var(--accent);
    }

    /* Hide Google reCAPTCHA v3 floating badge completely (Officially permitted since disclaimer text is included below form) */
    .grecaptcha-badge {
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }

    /* Submit Button */
    .btn-submit {
        width: 100%; padding: 0.95rem 1.5rem; border-radius: 12px;
        background: var(--gradient); color: #ffffff; border: none;
        font-size: 1.05rem; font-weight: 700; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        box-shadow: 0 4px 15px var(--accent-glow);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        margin-top: 0.5rem;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.35);
    }
    .btn-submit:active {
        transform: scale(0.98);
    }

    /* Alert Success */
    .alert-custom-success {
        padding: 1.15rem 1.25rem; border-radius: 14px;
        background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3);
        color: #065f46; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.75rem;
        animation: fadeInDown 0.5s ease;
    }
    .alert-custom-success i {
        color: #10b981; font-size: 1.3rem; margin-top: 0.1rem;
    }

    /* Quick Contact Chips under Hero */
    .contact-quick-chips {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.65rem;
        margin-top: 1.25rem;
    }
    .contact-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 1rem;
        border-radius: 50px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        color: var(--text-primary);
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .contact-chip:hover {
        border-color: var(--accent);
        color: var(--accent);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.18);
    }
    .contact-chip i {
        color: var(--accent);
        font-size: 0.95rem;
    }

    @media (max-width: 860px) {
        .contact-hero { padding: 3.5rem 0 1.5rem; }
        .contact-layout {
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
            padding-bottom: 5rem;
        }
        .form-card {
            order: 1;
            padding: 1.75rem 1.5rem;
        }
        .info-group {
            order: 2;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        .info-card.info-card-full {
            grid-column: 1 / -1;
        }
        .reveal-left, .reveal-right {
            transform: translateY(20px) !important;
        }
    }

    @media (max-width: 580px) {
        .contact-hero h1 { font-size: 1.85rem; }
        .contact-quick-chips { gap: 0.5rem; }
        .contact-chip { font-size: 0.82rem; padding: 0.38rem 0.75rem; }
        .info-group {
            grid-template-columns: 1fr;
            gap: 0.85rem;
        }
        .info-card {
            padding: 1.15rem 1.15rem;
            border-radius: 14px;
        }
        .info-icon {
            width: 38px;
            height: 38px;
            font-size: 1rem;
            border-radius: 10px;
        }
        .info-card-title {
            font-size: 0.82rem;
        }
        .info-card-value {
            font-size: 0.98rem;
        }
        .form-card {
            padding: 1.35rem 1.15rem;
            border-radius: 16px;
        }
        .form-card-title {
            font-size: 1.25rem;
        }
        .form-card-subtitle {
            font-size: 0.88rem;
            margin-bottom: 1.25rem;
        }
        .form-control {
            font-size: 16px !important; /* Prevents iOS Safari auto-zoom */
            padding: 0.78rem 0.9rem;
            border-radius: 10px;
        }
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.35rem;
        }
        .btn-submit {
            min-height: 48px;
            font-size: 1rem;
            border-radius: 10px;
            margin-top: 0.75rem;
            margin-bottom: 0.5rem;
        }
        .recaptcha-v3-text {
            font-size: 0.75rem;
            margin-top: 0.5rem;
            padding-bottom: 0.25rem;
        }
    }
</style>
@endsection

@section('content')
<div class="contact-hero">
    <div class="container">
        <span class="hero-badge reveal-fade">
            <span class="dot"></span>
            {{ __('Contact') }}
        </span>
        <h1 class="reveal">{!! __('heading_contact') !!}</h1>
        <p class="reveal delay-1">{{ __('I\'m always open to interesting projects and opportunities.') }}</p>

        <div class="contact-quick-chips reveal delay-2">
            <a href="mailto:hothanhthien119@gmail.com" class="contact-chip">
                <i class="fas fa-envelope"></i> hothanhthien119@gmail.com
            </a>
            <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="contact-chip">
                <i class="fab fa-linkedin"></i> LinkedIn
            </a>
            <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="contact-chip">
                <i class="fab fa-github"></i> GitHub
            </a>
        </div>
    </div>
</div>

<section class="section" style="padding-top: 1.5rem">
    <div class="container">
        <div class="contact-layout">

            <!-- Left: Info Cards -->
            <div class="info-group reveal-left">
                <!-- Status -->
                <div class="info-card info-card-full">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem">
                        <span class="info-card-title">{{ __('Availability') }}</span>
                        <span class="status-badge">
                            <span class="dot"></span>
                            {{ __('Available for opportunities') }}
                        </span>
                    </div>
                    <p style="color:var(--text-secondary); font-size:0.92rem; line-height:1.6">
                        {{ __('I\'m currently open to freelance projects, remote roles, and engineering collaborations.') }}
                    </p>
                </div>

                <!-- Email -->
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="info-card-title">Email</div>
                            <a href="mailto:hothanhthien119@gmail.com" class="info-card-value">hothanhthien119@gmail.com</a>
                        </div>
                    </div>
                    <div class="info-card-sub">{{ __('Response time: Usually within 24 hours') }}</div>
                </div>

                <!-- Location -->
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-icon"><i class="fas fa-location-dot"></i></div>
                        <div>
                            <div class="info-card-title">{{ __('Location') }}</div>
                            <span class="info-card-value">{{ __('Hồ Chí Minh, Việt Nam') }}</span>
                        </div>
                    </div>
                    <div class="info-card-sub">UTC+7 (Indochina Time)</div>
                </div>

                <!-- Socials -->
                <div class="info-card info-card-full">
                    <div class="info-card-title" style="margin-bottom:0.75rem">{{ __('Follow & Connect') }}</div>
                    <div style="display:flex; gap:0.75rem; flex-wrap:wrap">
                        <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="btn btn-outline btn-sm" style="flex:1; min-width:120px; justify-content:center">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                        <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="btn btn-outline btn-sm" style="flex:1; min-width:120px; justify-content:center">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Contact Form -->
            <div class="form-card reveal-right">
                <h2 class="form-card-title">
                    <i class="fas fa-paper-plane"></i>
                    {{ __('Send Me a Message') }}
                </h2>
                <p class="form-card-subtitle">
                    {{ __('Fill out the form below and I will get back to you as soon as possible.') }}
                </p>

                @if(session('success'))
                <div class="alert-custom-success">
                    <i class="fas fa-circle-check"></i>
                    <div>
                        <strong style="font-size:1rem">{{ __('Sent successfully!') }}</strong>
                        <div style="margin-top:0.25rem; font-size:0.92rem">{{ session('success') }}</div>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}" id="contactForm" novalidate>
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            {{ __('Your Name') }} <span class="required">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('Enter your full name...') }}" required>
                        @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            {{ __('Your Email') }} <span class="required">*</span>
                        </label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@example.com" required>
                        @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div class="form-group">
                        <label for="subject" class="form-label">
                            {{ __('Subject') }}
                        </label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="{{ __('Project inquiry, collaboration, etc.') }}">
                        @error('subject')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div class="form-group">
                        <label for="message" class="form-label">
                            {{ __('Message') }} <span class="required">*</span>
                        </label>
                        <textarea name="message" id="message" rows="5" class="form-control @error('message') is-invalid @enderror" placeholder="{{ __('Describe your project or message in detail...') }}" required>{{ old('message') }}</textarea>
                        @error('message')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Invisible Google reCAPTCHA v3 Elements -->
                    <input type="hidden" name="g-recaptcha-response" id="gRecaptchaResponse" value="">
                    <!-- Anti-spam Honeypot -->
                    <input type="text" name="website_hp_check" value="" tabindex="-1" autocomplete="off" style="display:none !important" aria-hidden="true">

                    @error('recaptcha')
                    <div class="invalid-feedback" style="display:block; text-align:center; margin-bottom:1rem; font-size:0.9rem">
                        <i class="fas fa-triangle-exclamation"></i> {{ $message }}
                    </div>
                    @enderror

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <i class="fas fa-paper-plane"></i>
                        <span>{{ __('Send Message') }}</span>
                    </button>

                    <!-- reCAPTCHA v3 Notice -->
                    <div class="recaptcha-v3-text">
                        {{ __('This site is protected by reCAPTCHA and the Google') }}
                        <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">{{ __('Privacy Policy') }}</a>
                        {{ __('and') }}
                        <a href="https://policies.google.com/terms" target="_blank" rel="noopener">{{ __('Terms of Service') }}</a>
                        {{ __('apply.') }}
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');
        const tokenInput = document.getElementById('gRecaptchaResponse');

        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                if (!tokenInput.value) {
                    e.preventDefault();
                    grecaptcha.ready(function() {
                        grecaptcha.execute("{{ config('services.recaptcha.site_key') }}", {action: 'contact_submit'})
                            .then(function(token) {
                                tokenInput.value = token;
                                contactForm.submit();
                            })
                            .catch(function(err) {
                                contactForm.submit();
                            });
                    });
                }
            });
        }
    });
</script>
@else
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tokenInput = document.getElementById('gRecaptchaResponse');
        if (tokenInput) {
            // Invisible automated client token
            tokenInput.value = 'invisible_v3_' + Date.now() + '_' + Math.random().toString(36).substring(2);
        }
    });
</script>
@endif

<script>
    // Mobile-friendly focus interaction:
    // Only auto-focus on desktop screens to prevent mobile viewport jump/keyboard trigger
    window.addEventListener('DOMContentLoaded', () => {
        const nameInput = document.getElementById('name');
        if (window.innerWidth > 768 && nameInput && !nameInput.value) {
            nameInput.focus();
        }

        // Auto-hide floating back-to-top button when focusing inputs in the form
        const backToTopBtn = document.getElementById('backToTop');
        const contactForm = document.getElementById('contactForm');
        if (contactForm && backToTopBtn) {
            contactForm.addEventListener('focusin', () => {
                backToTopBtn.classList.add('hide-on-focus');
            });
            contactForm.addEventListener('focusout', () => {
                setTimeout(() => {
                    if (!contactForm.contains(document.activeElement)) {
                        backToTopBtn.classList.remove('hide-on-focus');
                    }
                }, 100);
            });
        }
    });
</script>
@endsection
