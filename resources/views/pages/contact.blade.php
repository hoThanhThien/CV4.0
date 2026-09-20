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

    /* Google reCAPTCHA v2 Style Widget */
    .recaptcha-widget {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        background: #f9f9f9;
        border: 1px solid #d3d3d3;
        border-radius: 4px;
        box-shadow: 0 0 4px 1px rgba(0, 0, 0, 0.08);
        width: 304px;
        height: 78px;
        padding: 0 12px;
        box-sizing: border-box;
        user-select: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        margin-bottom: 0.5rem;
    }
    .recaptcha-widget:hover {
        border-color: #c1c1c1;
    }
    .recaptcha-widget.is-invalid {
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2) !important;
    }

    .recaptcha-checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Checkbox square */
    .recaptcha-checkbox {
        width: 28px;
        height: 28px;
        border: 2px solid #c1c1c1;
        border-radius: 2px;
        background: #ffffff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 0;
        outline: none;
        transition: border-color 0.2s ease, background-color 0.2s ease;
    }
    .recaptcha-checkbox:hover {
        border-color: #b2b2b2;
    }

    /* Spinner Animation */
    .recaptcha-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(66, 133, 244, 0.2);
        border-top-color: #4285F4;
        border-radius: 50%;
        animation: recaptchaSpin 0.75s linear infinite;
    }
    @keyframes recaptchaSpin {
        to { transform: rotate(360deg); }
    }

    /* Checkmark */
    .recaptcha-checkmark {
        display: none;
        width: 28px;
        height: 28px;
        align-items: center;
        justify-content: center;
        animation: checkmarkPop 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes checkmarkPop {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Active States */
    .recaptcha-checkbox.loading {
        border-color: transparent !important;
        background: transparent !important;
        cursor: wait;
    }
    .recaptcha-checkbox.loading .recaptcha-spinner {
        display: block;
    }

    .recaptcha-checkbox.verified {
        border-color: transparent !important;
        background: transparent !important;
        cursor: default;
    }
    .recaptcha-checkbox.verified .recaptcha-checkmark {
        display: flex;
    }

    /* Text Label */
    .recaptcha-label {
        font-family: Roboto, -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
        font-size: 14px;
        font-weight: 500;
        color: #282727;
        cursor: pointer;
        margin: 0;
        line-height: 1.2;
    }

    /* Badge on Right */
    .recaptcha-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 100%;
        text-align: center;
    }
    .recaptcha-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2px;
    }
    .recaptcha-logo svg {
        display: block;
        transition: transform 0.35s ease;
    }
    .recaptcha-widget:hover .recaptcha-logo svg {
        transform: rotate(30deg);
    }
    .recaptcha-brand {
        font-family: Roboto, -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 10px;
        font-weight: 600;
        color: #555555;
        letter-spacing: 0.02em;
        line-height: 1.1;
        display: block;
    }
    .recaptcha-links {
        font-size: 8px;
        color: #555555;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        line-height: 1.1;
        margin-top: 2px;
    }
    .recaptcha-links a {
        color: #555555;
        text-decoration: none;
    }
    .recaptcha-links a:hover {
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .recaptcha-widget {
            width: 100%;
            max-width: 320px;
        }
        .recaptcha-label {
            font-size: 13px;
        }
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

    @media (max-width: 860px) {
        .contact-layout { grid-template-columns: 1fr; gap: 2rem; }
        .contact-hero { padding: 3.5rem 0 1.5rem; }
    }
    @media (max-width: 480px) {
        .form-card { padding: 1.5rem 1.25rem; }
        .captcha-container { flex-direction: column; align-items: stretch; }
        .captcha-img-wrap { justify-content: center; }
        .captcha-img-wrap img { width: 100%; max-width: 180px; }
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
    </div>
</div>

<section class="section" style="padding-top: 1.5rem">
    <div class="container">
        <div class="contact-layout">

            <!-- Left: Info Cards -->
            <div class="info-group reveal-left">
                <!-- Status -->
                <div class="info-card">
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
                <div class="info-card">
                    <div class="info-card-title" style="margin-bottom:0.75rem">{{ __('Follow & Connect') }}</div>
                    <div style="display:flex; gap:0.75rem">
                        <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="btn btn-outline btn-sm" style="flex:1; justify-content:center">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                        <a href="#" class="btn btn-outline btn-sm" style="flex:1; justify-content:center">
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

                    <!-- GOOGLE reCAPTCHA STYLE WIDGET -->
                    <div class="form-group" style="margin-top: 0.5rem">
                        <label class="form-label" style="margin-bottom: 0.45rem">
                            {{ __('Security Verification') }} <span class="required">*</span>
                        </label>
                        <div>
                            <div class="recaptcha-widget @error('recaptcha_token') is-invalid @enderror" id="recaptchaWidget">
                                <div class="recaptcha-checkbox-wrapper">
                                    <button type="button" class="recaptcha-checkbox" id="recaptchaCheckbox" role="checkbox" aria-checked="false" aria-label="{{ __('I\'m not a robot') }}">
                                        <span class="recaptcha-spinner"></span>
                                        <span class="recaptcha-checkmark">
                                            <svg viewBox="0 0 24 24" width="22" height="22">
                                                <polyline points="4 12 9 17 20 6" fill="none" stroke="#0f9d58" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </button>
                                    <label for="recaptchaCheckbox" class="recaptcha-label" id="recaptchaLabel">
                                        {{ __('I\'m not a robot') }}
                                    </label>
                                </div>
                                <div class="recaptcha-badge">
                                    <div class="recaptcha-logo">
                                        <svg viewBox="0 0 48 48" width="30" height="30" aria-hidden="true">
                                            <!-- Google reCAPTCHA 3-arrow logo -->
                                            <path fill="#4285F4" d="M24 4C14.1 4 5.9 11.2 4.3 20.6l5.9 1c1.2-7.5 7.7-13.2 15.6-13.2 5.1 0 9.7 2.4 12.6 6.2L31 20h17V3l-6.3 6.3C37.5 5.2 31.1 4 24 4z"/>
                                            <path fill="#1A4B9C" d="M43.7 27.4l-5.9-1c-1.2 7.5-7.7 13.2-15.6 13.2-5.1 0-9.7-2.4-12.6-6.2L17 28H0v17l6.3-6.3C10.5 42.8 16.9 44 24 44c9.9 0 18.1-7.2 19.7-16.6z"/>
                                            <path fill="#9E9E9E" d="M12.6 34.2C9.7 30.4 8 25.8 8 20.8c0-1.4.2-2.8.5-4.1l-5.9-1C2.2 17.5 2 19.3 2 21.1c0 6.2 2.2 12 5.9 16.7l-4.8 4.8h17V26l-7.5 8.2z"/>
                                        </svg>
                                    </div>
                                    <span class="recaptcha-brand">reCAPTCHA</span>
                                    <div class="recaptcha-links">
                                        <a href="https://www.google.com/intl/{{ app()->getLocale() }}/policies/privacy/" target="_blank" rel="noopener">{{ __('Privacy') }}</a>
                                        <span>-</span>
                                        <a href="https://www.google.com/intl/{{ app()->getLocale() }}/policies/terms/" target="_blank" rel="noopener">{{ __('Terms') }}</a>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="recaptcha_token" id="recaptchaToken" value="">
                            <!-- Anti-spam honeypot -->
                            <input type="text" name="website_hp_check" value="" tabindex="-1" autocomplete="off" style="display:none !important" aria-hidden="true">
                            <span class="invalid-feedback" id="recaptchaError" style="{{ $errors->has('recaptcha_token') ? 'display:block' : 'display:none' }}">
                                {{ $errors->first('recaptcha_token') ?? __('Please verify that you are not a robot.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <i class="fas fa-paper-plane"></i>
                        <span>{{ __('Send Message') }}</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto focus name input
        const nameInput = document.getElementById('name');
        if (nameInput && !nameInput.value) {
            nameInput.focus();
        }

        // Google reCAPTCHA interactive behavior
        const checkbox = document.getElementById('recaptchaCheckbox');
        const label = document.getElementById('recaptchaLabel');
        const tokenInput = document.getElementById('recaptchaToken');
        const widget = document.getElementById('recaptchaWidget');
        const contactForm = document.getElementById('contactForm');
        const errorMsg = document.getElementById('recaptchaError');

        let isVerifying = false;
        let isVerified = false;

        function triggerRecaptcha() {
            if (isVerified || isVerifying) return;

            isVerifying = true;
            checkbox.classList.add('loading');
            widget.classList.remove('is-invalid');
            if (errorMsg) errorMsg.style.display = 'none';

            // Natural reCAPTCHA verification delay (simulate human behavioral check)
            setTimeout(() => {
                fetch("{{ route('contact.verify-recaptcha') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        timestamp: Date.now()
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.token) {
                        tokenInput.value = data.token;
                        isVerified = true;
                        isVerifying = false;
                        checkbox.classList.remove('loading');
                        checkbox.classList.add('verified');
                        checkbox.setAttribute('aria-checked', 'true');
                    } else {
                        throw new Error('Verification failed');
                    }
                })
                .catch(err => {
                    isVerifying = false;
                    checkbox.classList.remove('loading');
                    widget.classList.add('is-invalid');
                    if (errorMsg) errorMsg.style.display = 'block';
                });
            }, 650);
        }

        if (checkbox) {
            checkbox.addEventListener('click', triggerRecaptcha);
        }
        if (label) {
            label.addEventListener('click', triggerRecaptcha);
        }

        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                if (!tokenInput.value || !isVerified) {
                    e.preventDefault();
                    widget.classList.add('is-invalid');
                    if (errorMsg) {
                        errorMsg.style.display = 'block';
                        errorMsg.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        }
    });
</script>
@endsection
