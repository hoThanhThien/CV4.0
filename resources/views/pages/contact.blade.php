@extends('layouts.app')

@section('title', __('Contact') . ' | ' . __('Hồ Thành Thiện'))
@section('description', app()->getLocale() == 'vi' ? 'Liên hệ với Hồ Thành Thiện để trao đổi về dự án công nghệ, cơ hội tuyển dụng Full-Stack Developer hoặc hợp tác freelance.' : 'Get in touch with Ho Thanh Thien for software project inquiries, full-stack development roles, or technical collaborations.')
@section('keywords', app()->getLocale() == 'vi' ? 'Liên hệ Hồ Thành Thiện, Thuê lập trình viên Laravel, Hợp tác phần mềm, Fullstack Freelancer' : 'Contact Ho Thanh Thien, Hire Full-Stack Developer, Freelance Laravel Expert, Contact Software Engineer')

@section('styles')
<style>
    .contact-studio-grid {
        display: grid;
        grid-template-columns: minmax(0, 0.88fr) minmax(0, 1.12fr);
        gap: clamp(1.75rem, 4vw, 3.25rem);
        align-items: start;
    }

    .contact-dossier-panel {
        background: var(--surface-ink);
        color: #F3F4F6;
        border-radius: var(--radius-lg);
        border: 1px solid #232938;
        padding: clamp(1.4rem, 3vw, 2rem);
        box-shadow: var(--shadow-lg);
    }

    .contact-status-box {
        padding-bottom: 1.25rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .contact-status-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 0.6rem;
    }
    .ledger-row-label {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: #9CA3AF;
        letter-spacing: 0.04em;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.28rem 0.7rem;
        border-radius: 999px;
        background: rgba(15, 118, 110, 0.22);
        border: 1px solid rgba(45, 212, 191, 0.35);
        color: #99F6E4;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .status-badge .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #2DD4BF;
    }
    .contact-status-note {
        font-size: 0.9rem;
        color: #D1D5DB;
        line-height: 1.6;
    }

    .contact-ledger-item {
        display: flex;
        align-items: flex-start;
        gap: 0.9rem;
        padding: 0.95rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #93C5FD;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .info-content { min-width: 0; }
    .info-card-value {
        display: block;
        color: #FFFFFF;
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        margin: 0.15rem 0 0.1rem;
        word-break: break-word;
    }
    a.info-card-value:hover {
        color: #93C5FD;
        text-decoration: underline;
    }
    .info-card-sub {
        font-family: var(--font-mono);
        font-size: 0.74rem;
        color: #9CA3AF;
    }

    .contact-socials-row {
        padding-top: 1.25rem;
    }
    .contact-socials-btns {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        margin-top: 0.65rem;
    }
    .social-dark-btn {
        flex: 1;
        min-width: 120px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        padding: 0.55rem 0.9rem;
        border-radius: var(--radius-sm);
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.14);
        color: #FFFFFF;
        font-size: 0.86rem;
        font-weight: 600;
        text-decoration: none;
        transition: background var(--duration-fast) ease, border-color var(--duration-fast) ease;
    }
    .social-dark-btn:hover {
        background: var(--accent);
        border-color: var(--accent);
    }

    /* Tactile Inquiry Form Panel */
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: clamp(1.5rem, 3.5vw, 2.35rem);
        box-shadow: var(--shadow-sm);
    }
    .form-card-title {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-family: var(--font-display);
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.4rem;
    }
    .form-card-title i {
        color: var(--accent);
        font-size: 1.15rem;
    }
    .form-card-subtitle {
        color: var(--text-secondary);
        font-size: 0.94rem;
        margin-bottom: 1.65rem;
        line-height: 1.6;
    }

    .alert-custom-success {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem 1.15rem;
        border-radius: var(--radius-sm);
        background: rgba(15, 118, 110, 0.08);
        border: 1px solid rgba(15, 118, 110, 0.3);
        color: #0F766E;
        margin-bottom: 1.5rem;
    }
    .alert-custom-success i { font-size: 1.15rem; margin-top: 0.1rem; }

    .form-row-2col {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }
    .form-group { margin-bottom: 1.15rem; }
    .form-label {
        display: block;
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.42rem;
    }
    .form-label .required { color: var(--danger); }

    .form-control {
        width: 100%;
        padding: 0.68rem 0.88rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-strong);
        background: var(--bg-primary);
        color: var(--text-primary);
        font-family: var(--font-sans);
        font-size: 0.95rem;
        transition: border-color var(--duration-fast) ease, background var(--duration-fast) ease, box-shadow var(--duration-fast) ease;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        background: var(--bg-card);
        box-shadow: 0 0 0 3px var(--accent-soft);
    }
    .form-control.is-invalid {
        border-color: var(--danger);
    }
    .invalid-feedback {
        display: block;
        color: var(--danger);
        font-size: 0.82rem;
        font-weight: 500;
        margin-top: 0.35rem;
    }

    .btn-submit {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        padding: 0.82rem 1.5rem;
        border: none;
        border-radius: var(--radius-sm);
        background: var(--surface-ink);
        color: #FFFFFF;
        font-family: var(--font-sans);
        font-size: 0.96rem;
        font-weight: 600;
        cursor: pointer;
        transition: background var(--duration-fast) ease, transform var(--duration-fast) ease;
    }
    .btn-submit:hover {
        background: var(--accent);
        transform: translateY(-1px);
    }
    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .recaptcha-v3-text {
        margin-top: 1rem;
        text-align: center;
        font-size: 0.76rem;
        color: var(--text-muted);
        line-height: 1.5;
    }
    .recaptcha-v3-text a { color: var(--text-secondary); text-decoration: underline; }

    @media (max-width: 960px) {
        .contact-studio-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .form-row-2col { grid-template-columns: 1fr; gap: 0; }
    }
</style>
@endsection

@section('content')
<section class="page-header-band">
    <div class="container hero-animate">
        <div class="section-kicker" style="color:var(--accent-sky);">DIRECT CHANNELS // AVAILABILITY</div>
        <h1>{!! __('heading_contact') !!}</h1>
        <p>{{ __('Feel free to reach out for collaborations, project inquiries, or just to say hello!') }}</p>
    </div>
</section>

<section class="section" style="padding-top:2.5rem">
    <div class="container">
        <div class="contact-studio-grid hero-animate">
            <!-- Left: Contact Ledger -->
            <aside class="contact-dossier-panel">
                <div class="contact-status-box">
                    <div class="contact-status-top">
                        <span class="ledger-row-label">{{ __('Availability') }}</span>
                        <span class="status-badge">
                            <span class="dot"></span>
                            {{ __('Available for opportunities') }}
                        </span>
                    </div>
                    <p class="contact-status-note">
                        {{ __('I\'m currently open to freelance projects, remote roles, and engineering collaborations.') }}
                    </p>
                </div>

                <div class="contact-ledger-item">
                    <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="info-content">
                        <div class="ledger-row-label">{{ __('Phone') }}</div>
                        <a href="tel:0375227764" class="info-card-value">0375227764</a>
                        <div class="info-card-sub">{{ __('Call / Zalo') }}</div>
                    </div>
                </div>

                <div class="contact-ledger-item">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div class="info-content">
                        <div class="ledger-row-label">Email</div>
                        <a href="mailto:hothanhthien119@gmail.com" class="info-card-value">hothanhthien119@gmail.com</a>
                        <div class="info-card-sub">{{ __('Response time: Usually within 24 hours') }}</div>
                    </div>
                </div>

                <div class="contact-ledger-item">
                    <div class="info-icon"><i class="fas fa-globe"></i></div>
                    <div class="info-content">
                        <div class="ledger-row-label">{{ __('Website') }}</div>
                        <a href="https://hothanhthien.io.vn/" target="_blank" rel="noopener" class="info-card-value">hothanhthien.io.vn</a>
                        <div class="info-card-sub">https://hothanhthien.io.vn/</div>
                    </div>
                </div>

                <div class="contact-ledger-item">
                    <div class="info-icon"><i class="fas fa-location-dot"></i></div>
                    <div class="info-content">
                        <div class="ledger-row-label">{{ __('Location') }}</div>
                        <span class="info-card-value">{{ __('Hồ Chí Minh, Việt Nam') }}</span>
                        <div class="info-card-sub">HCM / UTC+7</div>
                    </div>
                </div>

                <div class="contact-socials-row">
                    <div class="ledger-row-label">{{ __('Follow & Connect') }}</div>
                    <div class="contact-socials-btns">
                        <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="social-dark-btn">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                        <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="social-dark-btn">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Right: Contact Form -->
            <div class="form-card">
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
                        <strong style="font-size:0.96rem">{{ __('Sent successfully!') }}</strong>
                        <div style="margin-top:0.2rem; font-size:0.9rem">{{ session('success') }}</div>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}" id="contactForm" novalidate>
                    @csrf

                    <div class="form-row-2col">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                {{ __('Your Name') }} <span class="required">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('Enter your full name...') }}" required>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                {{ __('Your Email') }} <span class="required">*</span>
                            </label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@example.com" required>
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label">{{ __('Subject') }}</label>
                        <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="{{ __('Project inquiry, collaboration, etc.') }}">
                        @error('subject')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

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

                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <i class="fas fa-paper-plane"></i>
                        <span>{{ __('Send Message') }}</span>
                    </button>

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
<script>
    function setContactSubmitLoading() {
        const btn = document.getElementById('btnSubmit');
        if (!btn) return;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i><span>{{ app()->getLocale() === "vi" ? "Đang gửi..." : "Sending..." }}</span>';
    }
</script>
@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');
        const tokenInput = document.getElementById('gRecaptchaResponse');

        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                setContactSubmitLoading();
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
        const contactForm = document.getElementById('contactForm');
        if (tokenInput) {
            tokenInput.value = 'invisible_v3_' + Date.now() + '_' + Math.random().toString(36).substring(2);
        }
        if (contactForm) {
            contactForm.addEventListener('submit', function() {
                setContactSubmitLoading();
            });
        }
    });
</script>
@endif

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const nameInput = document.getElementById('name');
        if (window.innerWidth > 768 && nameInput && !nameInput.value) {
            nameInput.focus();
        }

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
