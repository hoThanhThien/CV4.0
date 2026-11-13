@extends('layouts.app')

@section('title', __('Contact') . ' | ' . __('Hồ Thành Thiện'))
@section('description', __('Feel free to reach out for collaborations, project inquiries, or just to say hello!'))
@section('keywords', app()->getLocale() == 'vi' ? 'Liên hệ Hồ Thành Thiện, Tuyển dụng lập trình viên, Thuê lập trình viên Laravel, Hợp tác dự án Web' : 'Contact Ho Thanh Thien, Hire Full-Stack Developer, Web Development Collaboration, Software Engineer Inquiries')

@section('styles')
<style>
    .contact-header {
        padding: 3.5rem 0 2.5rem;
        background: var(--bg-secondary, #eef1f5);
        border-bottom: 1px solid var(--border, #dce1e9);
    }
    .contact-header h1 {
        font-size: clamp(1.9rem, 4vw, 2.75rem);
        font-weight: 700;
        letter-spacing: -0.025em;
        color: var(--text-primary, #12161f);
        line-height: 1.15;
        margin-bottom: 0.6rem;
    }
    .contact-header p {
        color: var(--text-secondary, #434d5e);
        font-size: 1.02rem;
        max-width: 60ch;
        line-height: 1.65;
        margin-bottom: 1.5rem;
    }
    .contact-quick-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
    }
    .contact-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.42rem 0.85rem;
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 6px;
        font-size: 0.84rem;
        font-weight: 500;
        color: var(--text-secondary, #434d5e);
        text-decoration: none;
        transition: border-color 0.15s ease, color 0.15s ease;
    }
    .contact-chip:hover {
        border-color: var(--text-primary, #12161f);
        color: var(--text-primary, #12161f);
    }
    .contact-chip i {
        color: var(--accent, #1e40af);
    }

    /* Main Split Layout */
    .contact-section {
        padding: 3rem 0 5rem;
    }
    .contact-layout {
        display: grid;
        grid-template-columns: 1fr 1.35fr;
        gap: 2.25rem;
        align-items: start;
    }
    @media (max-width: 900px) {
        .contact-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Left Contact Ledger */
    .contact-ledger {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 10px;
        overflow: hidden;
    }
    .contact-ledger-status {
        padding: 1.4rem 1.5rem;
        background: var(--bg-primary, #f7f8fa);
        border-bottom: 1px solid var(--border, #dce1e9);
    }
    .contact-status-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-bottom: 0.65rem;
    }
    .ledger-row-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted, #687385);
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .status-badge .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #16a34a;
    }
    .contact-status-note {
        font-size: 0.9rem;
        color: var(--text-secondary, #434d5e);
        line-height: 1.6;
        margin: 0;
    }

    .contact-ledger-item {
        display: flex;
        align-items: flex-start;
        gap: 0.9rem;
        padding: 1.15rem 1.5rem;
        border-bottom: 1px solid #eef1f5;
    }
    .contact-ledger-item:last-child {
        border-bottom: none;
    }
    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 7px;
        background: var(--bg-secondary, #eef1f5);
        border: 1px solid var(--border, #dce1e9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent, #1e40af);
        font-size: 0.9rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .info-content {
        min-width: 0;
        flex: 1;
    }
    .info-card-value {
        display: block;
        margin-top: 0.15rem;
        font-size: 0.98rem;
        font-weight: 600;
        color: var(--text-primary, #12161f);
        text-decoration: none;
        overflow-wrap: anywhere;
    }
    a.info-card-value:hover {
        color: var(--accent, #1e40af);
        text-decoration: underline;
    }
    @media (max-width: 480px) {
        .contact-ledger-status,
        .contact-ledger-item,
        .contact-socials-row {
            padding-left: 1.1rem;
            padding-right: 1.1rem;
        }
    }
    .info-card-sub {
        margin-top: 0.2rem;
        font-size: 0.82rem;
        color: var(--text-muted, #687385);
    }

    .contact-socials-row {
        padding: 1.25rem 1.5rem;
        background: var(--bg-primary, #f7f8fa);
        border-top: 1px solid var(--border, #dce1e9);
    }
    .contact-socials-btns {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-top: 0.65rem;
    }

    /* Right Form Card */
    .form-card {
        background: var(--bg-card, #ffffff);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 10px;
        padding: 2rem;
    }
    @media (max-width: 640px) {
        .form-card {
            padding: 1.35rem;
        }
    }
    .form-card-title {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary, #12161f);
        margin-bottom: 0.35rem;
    }
    .form-card-title i {
        color: var(--accent, #1e40af);
        font-size: 1.05rem;
    }
    .form-card-subtitle {
        font-size: 0.92rem;
        color: var(--text-secondary, #434d5e);
        margin-bottom: 1.5rem;
        line-height: 1.55;
    }

    .alert-custom-success {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem 1.15rem;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    .alert-custom-success i {
        font-size: 1.15rem;
        margin-top: 2px;
        color: #16a34a;
    }

    .form-group {
        margin-bottom: 1.15rem;
    }
    .form-label {
        display: block;
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--text-primary, #12161f);
        margin-bottom: 0.42rem;
    }
    .form-label .required {
        color: #b91c1c;
    }
    .form-control {
        width: 100%;
        padding: 0.68rem 0.9rem;
        background: var(--bg-primary, #f7f8fa);
        border: 1px solid var(--border, #dce1e9);
        border-radius: 7px;
        color: var(--text-primary, #12161f);
        font-family: inherit;
        font-size: 0.94rem;
        transition: border-color 0.16s ease, box-shadow 0.16s ease, background 0.16s ease;
        box-sizing: border-box;
    }
    .form-control::placeholder {
        color: var(--text-muted, #687385);
    }
    .form-control:focus {
        outline: none;
        background: #ffffff;
        border-color: var(--accent, #1e40af);
        box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.12);
    }
    .form-control.is-invalid {
        border-color: #dc2626;
        background: #fef2f2;
    }
    .invalid-feedback {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.82rem;
        color: #b91c1c;
        font-weight: 500;
    }

    .btn-submit {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        padding: 0.8rem 1.25rem;
        background: var(--text-primary, #12161f);
        border: 1px solid var(--text-primary, #12161f);
        border-radius: 7px;
        color: #ffffff;
        font-family: inherit;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.16s ease, border-color 0.16s ease;
    }
    .btn-submit:hover {
        background: var(--accent, #1e40af);
        border-color: var(--accent, #1e40af);
    }

    .recaptcha-v3-text {
        margin-top: 0.9rem;
        font-size: 0.78rem;
        color: var(--text-muted, #687385);
        text-align: center;
        line-height: 1.5;
    }
    .recaptcha-v3-text a {
        color: var(--accent, #1e40af);
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="contact-header">
    <div class="container hero-animate">
        <h1>{!! __('heading_contact') !!}</h1>
        <p>{{ __('I\'m always open to interesting projects and opportunities.') }}</p>

        <div class="contact-quick-chips">
            <a href="tel:0375227764" class="contact-chip">
                <i class="fas fa-phone-alt"></i> 0375227764
            </a>
            <a href="mailto:hothanhthien119@gmail.com" class="contact-chip">
                <i class="fas fa-envelope"></i> hothanhthien119@gmail.com
            </a>
            <a href="https://hothanhthien.io.vn/" target="_blank" rel="noopener" class="contact-chip">
                <i class="fas fa-globe"></i> hothanhthien.io.vn
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

<section class="contact-section">
    <div class="container">
        <div class="contact-layout">

            <!-- Left: Contact Details Ledger -->
            <div class="contact-ledger">
                <div class="contact-ledger-status">
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

                <!-- Phone -->
                <div class="contact-ledger-item">
                    <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="info-content">
                        <div class="ledger-row-label">{{ __('Phone') }}</div>
                        <a href="tel:0375227764" class="info-card-value">0375227764</a>
                        <div class="info-card-sub">{{ __('Call / Zalo') }}</div>
                    </div>
                </div>

                <!-- Email -->
                <div class="contact-ledger-item">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div class="info-content">
                        <div class="ledger-row-label">Email</div>
                        <a href="mailto:hothanhthien119@gmail.com" class="info-card-value">hothanhthien119@gmail.com</a>
                        <div class="info-card-sub">{{ __('Response time: Usually within 24 hours') }}</div>
                    </div>
                </div>

                <!-- Website -->
                <div class="contact-ledger-item">
                    <div class="info-icon"><i class="fas fa-globe"></i></div>
                    <div class="info-content">
                        <div class="ledger-row-label">{{ __('Website') }}</div>
                        <a href="https://hothanhthien.io.vn/" target="_blank" rel="noopener" class="info-card-value">hothanhthien.io.vn</a>
                        <div class="info-card-sub">https://hothanhthien.io.vn/</div>
                    </div>
                </div>

                <!-- Location -->
                <div class="contact-ledger-item">
                    <div class="info-icon"><i class="fas fa-location-dot"></i></div>
                    <div class="info-content">
                        <div class="ledger-row-label">{{ __('Location') }}</div>
                        <span class="info-card-value">{{ __('Hồ Chí Minh, Việt Nam') }}</span>
                        <div class="info-card-sub">HCM / UTC+7</div>
                    </div>
                </div>

                <!-- Socials -->
                <div class="contact-socials-row">
                    <div class="ledger-row-label">{{ __('Follow & Connect') }}</div>
                    <div class="contact-socials-btns">
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
