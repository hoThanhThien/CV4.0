@php
    $isVi = app()->getLocale() === 'vi';
@endphp
@extends('layouts.pearl')

@section('active_nav', 'contact')
@section('main_class', 'flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14 space-y-8')
@section('title', __('Contact') . ' | ' . ($isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien'))
@section('description', $isVi ? 'Liên hệ với Hồ Thành Thiện để trao đổi về dự án công nghệ, cơ hội tuyển dụng Full-Stack Developer & AI Engineer hoặc hợp tác freelance.' : 'Get in touch with Ho Thanh Thien for software project inquiries, full-stack & RAG AI engineering roles, or technical collaborations.')
@section('keywords', $isVi ? 'Liên hệ Hồ Thành Thiện, Thuê lập trình viên Laravel, Hợp tác phần mềm, Fullstack Freelancer, RAG AI Engineer' : 'Contact Ho Thanh Thien, Hire Full-Stack Developer, Freelance Laravel Expert, Contact Software Engineer')

@section('content')

    <!-- =========================================================================
         SECTION 1: CONTACT HERO BAND (PEARL WHITE FROSTED BENTO HEADER)
    ========================================================================= -->
    <section class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm">
      <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

        <div class="lg:col-span-8 space-y-4">
          <!-- Breadcrumb -->
          <nav class="flex items-center gap-2 font-mono text-xs text-slate-500 dark:text-slate-400" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors inline-flex items-center gap-1">
              <i data-lucide="home" class="w-3.5 h-3.5"></i>
              <span data-i18n="breadcrumb_home">{{ __('Home') }}</span>
            </a>
            <span>/</span>
            <span class="text-slate-900 dark:text-white font-semibold" data-i18n="breadcrumb_contact">{{ __('Contact') }}</span>
          </nav>

          <!-- Kicker Badge -->
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200/80 dark:border-emerald-500/30 font-mono text-[11px] font-bold tracking-widest uppercase text-emerald-700 dark:text-emerald-300">
            <span class="relative flex h-2 w-2">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span data-i18n="contact_kicker">DIRECT CHANNELS // AVAILABILITY</span>
          </div>

          <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.85rem] font-extrabold tracking-tight leading-[1.12] text-slate-900 dark:text-white">
            <span data-i18n="contact_title_1">{{ $isVi ? 'Liên hệ & Hợp tác' : 'Get in Touch &' }}</span>
            <span class="bg-gradient-to-r from-sky-600 via-indigo-600 to-emerald-600 dark:from-sky-400 dark:via-indigo-400 dark:to-emerald-400 bg-clip-text text-transparent" data-i18n="contact_title_highlight">
              {{ $isVi ? 'Cùng xây dựng' : 'Build Together' }}
            </span>
          </h1>

          <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 max-w-2xl leading-relaxed" data-i18n="contact_subtitle">
            {{ $isVi
              ? 'Sẵn sàng trao đổi về cơ hội Kỹ sư Phần mềm Full-Stack, kiến trúc RAG AI / LLM, hệ thống Laravel & Spring Boot, tùy chỉnh Odoo ERP hoặc các dự án freelance.'
              : 'Feel free to reach out for Full-Stack & RAG AI engineering roles, Laravel & Spring Boot system architecture, Odoo ERP consulting, or technical collaborations.' }}
          </p>
        </div>

        <!-- Right Quick SLA & Actions -->
        <div class="lg:col-span-4 space-y-3">
          <div class="grid grid-cols-3 gap-2.5">
            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
              <div class="font-display text-xl sm:text-2xl font-extrabold text-sky-600 dark:text-sky-400">&lt; 24h</div>
              <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="stat_reply">Response</div>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
              <div class="font-display text-xl sm:text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">UTC+7</div>
              <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="stat_tz">Ho Chi Minh</div>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-center">
              <div class="font-display text-xl sm:text-2xl font-extrabold text-emerald-600 dark:text-emerald-400" data-i18n="stat_status_val">Open</div>
              <div class="font-mono text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="stat_status_lbl">Hire / Work</div>
            </div>
          </div>

          <div class="flex flex-wrap sm:flex-nowrap gap-2">
            <a href="{{ route('home') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all">
              <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
              <span data-i18n="btn_back_home">Overview</span>
            </a>
            <a href="{{ asset('assets/docs/HoThanhThien_CV_Brochure.pdf') }}" download class="flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-slate-900 dark:bg-sky-500 text-white text-xs font-mono font-semibold hover:bg-slate-800 dark:hover:bg-sky-600 transition-all shadow-sm">
              <i data-lucide="download" class="w-3.5 h-3.5"></i>
              <span data-i18n="btn_brochure">Brochure PDF</span>
            </a>
          </div>
        </div>

      </div>
    </section>

    <!-- =========================================================================
         SECTION 2: SPLIT CONTACT WORKSPACE (5:7 CHANNELS DOSSIER & FORM)
    ========================================================================= -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

      <!-- Left Column (5 cols): Contact Dossier & Direct Channels -->
      <aside class="lg:col-span-5 spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6 hover:-translate-y-0.5">
        <div class="relative z-10 space-y-6">

          <!-- Availability Summary Box -->
          <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-br from-sky-50/90 via-white to-emerald-50/70 dark:from-sky-950/50 dark:via-slate-900 dark:to-emerald-950/30 border border-sky-200/70 dark:border-sky-500/25 space-y-2.5">
            <div class="flex items-center justify-between gap-2 flex-wrap">
              <span class="font-mono text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400" data-i18n="availability_label">{{ __('Availability') }}</span>
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 dark:bg-emerald-500/20 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 font-mono text-[11px] font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span data-i18n="availability_badge">{{ __('Available for opportunities') }}</span>
              </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-i18n="availability_note">
              {{ $isVi
                ? 'Hiện tôi đang sẵn sàng cho các vị trí Full-Stack Web / AI Engineer chính thức, làm việc từ xa (remote) hoặc hợp tác triển khai dự án.'
                : "I'm currently open to freelance projects, full-time software engineering roles, remote teams, and technical collaborations." }}
            </p>
          </div>

          <!-- Direct Contact Ledger Items -->
          <div class="divide-y divide-slate-200/70 dark:divide-slate-800/80">

            <!-- Email Row with One-Click Copy -->
            <div class="py-4 first:pt-0 flex items-start justify-between gap-3">
              <div class="flex items-start gap-3.5 min-w-0">
                <span class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-500/15 border border-sky-200/70 dark:border-sky-500/30 flex items-center justify-center text-sky-600 dark:text-sky-400 flex-shrink-0">
                  <i data-lucide="mail" class="w-4 h-4"></i>
                </span>
                <div class="min-w-0">
                  <div class="font-mono text-[11px] uppercase tracking-wider text-slate-400 dark:text-slate-500">Email</div>
                  <a href="mailto:hothanhthien119@gmail.com" class="block font-display font-bold text-sm sm:text-base text-slate-900 dark:text-white hover:text-sky-600 dark:hover:text-sky-400 transition-colors break-all mt-0.5">
                    hothanhthien119@gmail.com
                  </a>
                  <div class="font-mono text-[11px] text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="email_sub">Usually replies within 24 hours</div>
                </div>
              </div>
              <button type="button" onclick="copyContactValue('hothanhthien119@gmail.com', this)" class="px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-sky-50 dark:hover:bg-sky-500/20 border border-slate-200/80 dark:border-slate-700 text-[11px] font-mono font-semibold text-slate-600 dark:text-slate-300 hover:text-sky-600 dark:hover:text-sky-300 transition-all inline-flex items-center gap-1 flex-shrink-0" title="Copy Email">
                <i data-lucide="copy" class="w-3 h-3"></i>
                <span class="copy-label" data-i18n="btn_copy">Copy</span>
              </button>
            </div>

            <!-- Phone / Zalo Row with One-Click Copy -->
            <div class="py-4 flex items-start justify-between gap-3">
              <div class="flex items-start gap-3.5 min-w-0">
                <span class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200/70 dark:border-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                  <i data-lucide="phone" class="w-4 h-4"></i>
                </span>
                <div class="min-w-0">
                  <div class="font-mono text-[11px] uppercase tracking-wider text-slate-400 dark:text-slate-500" data-i18n="phone_label">Phone / Zalo</div>
                  <a href="tel:+84375227764" class="block font-display font-bold text-sm sm:text-base text-slate-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors mt-0.5">
                    0375 227 764
                  </a>
                  <div class="font-mono text-[11px] text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="phone_sub">Call or message via Zalo / SMS</div>
                </div>
              </div>
              <button type="button" onclick="copyContactValue('0375227764', this)" class="px-2.5 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-emerald-50 dark:hover:bg-emerald-500/20 border border-slate-200/80 dark:border-slate-700 text-[11px] font-mono font-semibold text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-300 transition-all inline-flex items-center gap-1 flex-shrink-0" title="Copy Phone">
                <i data-lucide="copy" class="w-3 h-3"></i>
                <span class="copy-label" data-i18n="btn_copy">Copy</span>
              </button>
            </div>

            <!-- Website Row -->
            <div class="py-4 flex items-start gap-3.5">
              <span class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/15 border border-indigo-200/70 dark:border-indigo-500/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                <i data-lucide="globe" class="w-4 h-4"></i>
              </span>
              <div class="min-w-0">
                <div class="font-mono text-[11px] uppercase tracking-wider text-slate-400 dark:text-slate-500" data-i18n="website_label">Portfolio &amp; Blog</div>
                <a href="https://hothanhthien.io.vn/" target="_blank" rel="noopener" class="inline-flex items-center gap-1 font-display font-bold text-sm sm:text-base text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors mt-0.5">
                  <span>hothanhthien.io.vn</span>
                  <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 opacity-60"></i>
                </a>
                <div class="font-mono text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">https://hothanhthien.io.vn/</div>
              </div>
            </div>

            <!-- Location Row -->
            <div class="py-4 last:pb-0 flex items-start gap-3.5">
              <span class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/15 border border-amber-200/70 dark:border-amber-500/30 flex items-center justify-center text-amber-600 dark:text-amber-400 flex-shrink-0">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
              </span>
              <div class="min-w-0">
                <div class="font-mono text-[11px] uppercase tracking-wider text-slate-400 dark:text-slate-500" data-i18n="location_label">Location</div>
                <div class="font-display font-bold text-sm sm:text-base text-slate-900 dark:text-white mt-0.5" data-i18n="location_val">
                  {{ $isVi ? 'Hồ Chí Minh, Việt Nam' : 'Ho Chi Minh City, Vietnam' }}
                </div>
                <div class="font-mono text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">HCM • UTC+7</div>
              </div>
            </div>

          </div>

          <!-- Quick Subject Presets -->
          <div class="pt-4 border-t border-slate-200/80 dark:border-slate-800 space-y-2.5">
            <div class="font-mono text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
              <i data-lucide="zap" class="w-3.5 h-3.5 text-sky-500"></i>
              <span data-i18n="presets_label">Quick Inquiry Topics (Click to prefill)</span>
            </div>
            <div class="flex flex-wrap gap-2">
              <button type="button" onclick="selectInquiryTopic(this)" data-topic-en="Interview / Full-Stack & AI Engineer Role" data-topic-vi="Mời phỏng vấn vị trí Kỹ sư Full-Stack & AI" class="topic-preset-btn px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/90 dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 hover:border-sky-400 hover:text-sky-600 dark:hover:text-sky-400 transition-all" data-i18n="topic_1">
                Full-Stack / AI Role
              </button>
              <button type="button" onclick="selectInquiryTopic(this)" data-topic-en="Applied RAG AI & LLM Chatbot Project" data-topic-vi="Tư vấn & Xây dựng Hệ thống RAG AI / LLM" class="topic-preset-btn px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/90 dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 hover:border-sky-400 hover:text-sky-600 dark:hover:text-sky-400 transition-all" data-i18n="topic_2">
                RAG AI / LLM System
              </button>
              <button type="button" onclick="selectInquiryTopic(this)" data-topic-en="Odoo ERP & Business Workflow Customization" data-topic-vi="Triển khai & Tùy chỉnh phân hệ Odoo ERP" class="topic-preset-btn px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/90 dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 hover:border-sky-400 hover:text-sky-600 dark:hover:text-sky-400 transition-all" data-i18n="topic_3">
                Odoo ERP Consulting
              </button>
              <button type="button" onclick="selectInquiryTopic(this)" data-topic-en="Laravel / Spring Boot Web Application" data-topic-vi="Hợp tác phát triển Web App Laravel / Spring Boot" class="topic-preset-btn px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/90 dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 hover:border-sky-400 hover:text-sky-600 dark:hover:text-sky-400 transition-all" data-i18n="topic_4">
                Freelance Web App
              </button>
            </div>
          </div>

          <!-- Social Profiles -->
          <div class="pt-4 border-t border-slate-200/80 dark:border-slate-800 space-y-2.5">
            <div class="font-mono text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500" data-i18n="social_label">Follow &amp; Connect</div>
            <div class="grid grid-cols-2 gap-3">
              <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 dark:bg-slate-950 border border-slate-800 dark:border-slate-700 text-white text-xs font-semibold hover:bg-sky-600 dark:hover:bg-sky-500 hover:border-sky-500 transition-all shadow-sm">
                <i data-lucide="github" class="w-4 h-4"></i>
                <span>GitHub</span>
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 opacity-70"></i>
              </a>
              <a href="https://www.linkedin.com/in/httblackhat" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-sky-50 dark:bg-sky-500/15 border border-sky-200/90 dark:border-sky-500/30 text-sky-700 dark:text-sky-300 text-xs font-semibold hover:bg-sky-500 hover:text-white dark:hover:bg-sky-500 dark:hover:text-white transition-all shadow-sm">
                <i data-lucide="linkedin" class="w-4 h-4"></i>
                <span>LinkedIn</span>
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 opacity-70"></i>
              </a>
            </div>
          </div>

        </div>
      </aside>

      <!-- Right Column (7 cols): Frosted Inquiry Form Panel -->
      <div class="lg:col-span-7 spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm">
        <div class="relative z-10 space-y-6">

          <!-- Form Header -->
          <div class="space-y-2 border-b border-slate-200/80 dark:border-slate-800 pb-5">
            <div class="flex items-center gap-3">
              <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white flex items-center justify-center shadow-md shadow-sky-500/20 flex-shrink-0">
                <i data-lucide="send" class="w-4 h-4"></i>
              </span>
              <div>
                <h2 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white" data-i18n="form_title">
                  {{ __('Send Me a Message') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400" data-i18n="form_subtitle">
                  {{ __('Fill out the form below and I will get back to you as soon as possible.') }}
                </p>
              </div>
            </div>
          </div>

          <!-- Success Feedback Alert -->
          @if(session('success'))
          <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-700/60 text-emerald-800 dark:text-emerald-200 flex items-start gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5"></i>
            <div class="space-y-0.5">
              <div class="font-display font-bold text-sm" data-i18n="success_title">{{ __('Sent successfully!') }}</div>
              <p class="text-xs sm:text-sm opacity-90">{{ session('success') }}</p>
            </div>
          </div>
          @endif

          <!-- reCAPTCHA / Anti-Spam Alert -->
          @error('recaptcha')
          <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-700/60 text-rose-700 dark:text-rose-200 flex items-center gap-2.5 text-sm font-medium">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 dark:text-rose-400 flex-shrink-0"></i>
            <span>{{ $message }}</span>
          </div>
          @enderror

          <form method="POST" action="{{ route('contact.send') }}" id="contactForm" class="space-y-5" novalidate>
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Name Field -->
              <div class="space-y-1.5">
                <label for="name" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                  <span data-i18n="label_name">{{ __('Your Name') }}</span>
                  <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                  <i data-lucide="user" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                  <input
                    type="text"
                    name="name"
                    id="name"
                    value="{{ old('name') }}"
                    data-i18n-placeholder="placeholder_name"
                    placeholder="{{ __('Enter your full name...') }}"
                    required
                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50/90 dark:bg-slate-950/80 border {{ $errors->has('name') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 dark:border-slate-700 focus:border-sky-500 focus:ring-sky-500/20' }} text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-4 transition-all"
                  >
                </div>
                @error('name')
                <p class="text-xs font-medium text-rose-600 dark:text-rose-400 flex items-center gap-1 mt-1">
                  <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                  <span>{{ $message }}</span>
                </p>
                @enderror
              </div>

              <!-- Email Field -->
              <div class="space-y-1.5">
                <label for="email" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                  <span data-i18n="label_email">{{ __('Your Email') }}</span>
                  <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                  <i data-lucide="at-sign" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                  <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    placeholder="name@company.com"
                    required
                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50/90 dark:bg-slate-950/80 border {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 dark:border-slate-700 focus:border-sky-500 focus:ring-sky-500/20' }} text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-4 transition-all"
                  >
                </div>
                @error('email')
                <p class="text-xs font-medium text-rose-600 dark:text-rose-400 flex items-center gap-1 mt-1">
                  <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                  <span>{{ $message }}</span>
                </p>
                @enderror
              </div>
            </div>

            <!-- Subject Field -->
            <div class="space-y-1.5">
              <label for="subject" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <span data-i18n="label_subject">{{ __('Subject') }}</span>
              </label>
              <div class="relative">
                <i data-lucide="tag" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input
                  type="text"
                  name="subject"
                  id="subject"
                  value="{{ old('subject') }}"
                  data-i18n-placeholder="placeholder_subject"
                  placeholder="{{ __('Project inquiry, collaboration, etc.') }}"
                  class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-50/90 dark:bg-slate-950/80 border {{ $errors->has('subject') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 dark:border-slate-700 focus:border-sky-500 focus:ring-sky-500/20' }} text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-4 transition-all"
                >
              </div>
              @error('subject')
              <p class="text-xs font-medium text-rose-600 dark:text-rose-400 flex items-center gap-1 mt-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                <span>{{ $message }}</span>
              </p>
              @enderror
            </div>

            <!-- Message Field -->
            <div class="space-y-1.5">
              <div class="flex items-center justify-between">
                <label for="message" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                  <span data-i18n="label_message">{{ __('Message') }}</span>
                  <span class="text-rose-500">*</span>
                </label>
                <span id="messageCharCount" class="font-mono text-[11px] text-slate-400 dark:text-slate-500">0 / 4000</span>
              </div>
              <textarea
                name="message"
                id="message"
                rows="5"
                maxlength="4000"
                data-i18n-placeholder="placeholder_message"
                placeholder="{{ __('Describe your project or message in detail...') }}"
                required
                class="w-full px-4 py-3 rounded-xl bg-slate-50/90 dark:bg-slate-950/80 border {{ $errors->has('message') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 dark:border-slate-700 focus:border-sky-500 focus:ring-sky-500/20' }} text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-4 transition-all resize-y min-h-[140px]"
              >{{ old('message') }}</textarea>
              @error('message')
              <p class="text-xs font-medium text-rose-600 dark:text-rose-400 flex items-center gap-1 mt-1">
                <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                <span>{{ $message }}</span>
              </p>
              @enderror
            </div>

            <!-- Invisible Google reCAPTCHA v3 & Honeypot Elements -->
            <input type="hidden" name="g-recaptcha-response" id="gRecaptchaResponse" value="">
            <input type="text" name="website_hp_check" value="" tabindex="-1" autocomplete="off" style="display:none !important" aria-hidden="true">

            <!-- Submit Button -->
            <button
              type="submit"
              id="btnSubmit"
              class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-slate-900 dark:bg-sky-500 text-white font-semibold text-sm hover:bg-sky-600 dark:hover:bg-sky-400 active:scale-[0.99] transition-all shadow-lg shadow-slate-900/15 dark:shadow-sky-500/25 disabled:opacity-65 disabled:cursor-not-allowed"
            >
              <i data-lucide="send" class="w-4 h-4"></i>
              <span data-i18n="btn_submit">{{ __('Send Message') }}</span>
            </button>

            <!-- reCAPTCHA Notice -->
            <p class="text-center text-[11px] font-mono text-slate-400 dark:text-slate-500 leading-relaxed">
              <span data-i18n="recaptcha_prefix">{{ __('This site is protected by reCAPTCHA and the Google') }}</span>
              <a href="https://policies.google.com/privacy" target="_blank" rel="noopener" class="underline hover:text-sky-600 dark:hover:text-sky-400" data-i18n="recaptcha_privacy">{{ __('Privacy Policy') }}</a>
              <span data-i18n="recaptcha_and">{{ __('and') }}</span>
              <a href="https://policies.google.com/terms" target="_blank" rel="noopener" class="underline hover:text-sky-600 dark:hover:text-sky-400" data-i18n="recaptcha_terms">{{ __('Terms of Service') }}</a>
              <span data-i18n="recaptcha_suffix">{{ __('apply.') }}</span>
            </p>
          </form>

        </div>
      </div>

    </section>

@endsection

@section('scripts')
  <script>
    window.PAGE_I18N = {
      en: {
        breadcrumb_contact: 'Contact',
        contact_kicker: 'DIRECT CHANNELS // AVAILABILITY',
        contact_title_1: 'Get in Touch &',
        contact_title_highlight: 'Build Together',
        contact_subtitle: 'Feel free to reach out for Full-Stack & RAG AI engineering roles, Laravel & Spring Boot system architecture, Odoo ERP consulting, or technical collaborations.',
        stat_reply: 'Response',
        stat_tz: 'Ho Chi Minh',
        stat_status_val: 'Open',
        stat_status_lbl: 'Hire / Work',
        availability_label: 'Availability',
        availability_badge: 'Available for opportunities',
        availability_note: "I'm currently open to freelance projects, full-time software engineering roles, remote teams, and technical collaborations.",
        email_sub: 'Usually replies within 24 hours',
        phone_label: 'Phone / Zalo',
        phone_sub: 'Call or message via Zalo / SMS',
        website_label: 'Portfolio & Blog',
        location_label: 'Location',
        location_val: 'Ho Chi Minh City, Vietnam',
        btn_copy: 'Copy',
        btn_copied: 'Copied',
        presets_label: 'Quick Inquiry Topics (Click to prefill)',
        topic_1: 'Full-Stack / AI Role',
        topic_2: 'RAG AI / LLM System',
        topic_3: 'Odoo ERP Consulting',
        topic_4: 'Freelance Web App',
        social_label: 'Follow & Connect',
        form_title: 'Send Me a Message',
        form_subtitle: 'Fill out the form below and I will get back to you as soon as possible.',
        success_title: 'Sent successfully!',
        label_name: 'Your Name',
        label_email: 'Your Email',
        label_subject: 'Subject',
        label_message: 'Message',
        placeholder_name: 'Enter your full name...',
        placeholder_subject: 'Project inquiry, collaboration, etc.',
        placeholder_message: 'Describe your project or message in detail...',
        btn_submit: 'Send Message',
        btn_sending: 'Sending...',
        recaptcha_prefix: 'This site is protected by reCAPTCHA and the Google',
        recaptcha_privacy: 'Privacy Policy',
        recaptcha_and: 'and',
        recaptcha_terms: 'Terms of Service',
        recaptcha_suffix: 'apply.'
      },
      vi: {
        breadcrumb_contact: 'Liên hệ',
        contact_kicker: 'KÊNH KẾT NỐI TRỰC TIẾP // SẴN SÀNG',
        contact_title_1: 'Liên hệ & Hợp tác',
        contact_title_highlight: 'Cùng xây dựng',
        contact_subtitle: 'Sẵn sàng trao đổi về cơ hội Kỹ sư Phần mềm Full-Stack, kiến trúc RAG AI / LLM, hệ thống Laravel & Spring Boot, tùy chỉnh Odoo ERP hoặc các dự án freelance.',
        stat_reply: 'Phản hồi',
        stat_tz: 'Hồ Chí Minh',
        stat_status_val: 'Sẵn sàng',
        stat_status_lbl: 'Tuyển dụng',
        availability_label: 'Trạng thái',
        availability_badge: 'Sẵn sàng đón nhận cơ hội',
        availability_note: 'Hiện tôi đang sẵn sàng cho các vị trí Kỹ sư Full-Stack / AI chính thức, làm việc từ xa (remote) hoặc hợp tác triển khai dự án.',
        email_sub: 'Thường phản hồi trong vòng 24 giờ',
        phone_label: 'Điện thoại / Zalo',
        phone_sub: 'Gọi trực tiếp hoặc nhắn qua Zalo / SMS',
        website_label: 'Portfolio & Blog',
        location_label: 'Địa điểm',
        location_val: 'Hồ Chí Minh, Việt Nam',
        btn_copy: 'Sao chép',
        btn_copied: 'Đã chép',
        presets_label: 'Chủ đề gợi ý (Nhấn để điền nhanh)',
        topic_1: 'Tuyển dụng Full-Stack / AI',
        topic_2: 'Hệ thống RAG AI / LLM',
        topic_3: 'Tư vấn Odoo ERP',
        topic_4: 'Dự án Web Freelance',
        social_label: 'Kết nối Mạng xã hội',
        form_title: 'Gửi tin nhắn trực tiếp',
        form_subtitle: 'Điền thông tin bên dưới, tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất.',
        success_title: 'Gửi thành công!',
        label_name: 'Họ và tên',
        label_email: 'Địa chỉ Email',
        label_subject: 'Tiêu đề',
        label_message: 'Nội dung tin nhắn',
        placeholder_name: 'Nhập họ và tên của bạn...',
        placeholder_subject: 'Trao đổi dự án, cơ hội việc làm, v.v.',
        placeholder_message: 'Mô tả chi tiết nhu cầu hoặc lời nhắn của bạn...',
        btn_submit: 'Gửi tin nhắn',
        btn_sending: 'Đang gửi...',
        recaptcha_prefix: 'Trang được bảo vệ bởi reCAPTCHA cùng',
        recaptcha_privacy: 'Chính sách bảo mật',
        recaptcha_and: 'và',
        recaptcha_terms: 'Điều khoản dịch vụ',
        recaptcha_suffix: 'của Google.'
      }
    };

    function copyContactValue(text, btn) {
      const applyCopiedFeedback = () => {
        const label = btn.querySelector('.copy-label');
        const dict = getMergedDict(currentLang);
        if (label) label.textContent = dict.btn_copied;
        btn.classList.add('border-emerald-400', 'text-emerald-600', 'dark:text-emerald-400');
        setTimeout(() => {
          if (label) label.textContent = dict.btn_copy;
          btn.classList.remove('border-emerald-400', 'text-emerald-600', 'dark:text-emerald-400');
        }, 1800);
      };

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(applyCopiedFeedback).catch(() => {});
      } else {
        const temp = document.createElement('textarea');
        temp.value = text;
        temp.style.position = 'fixed';
        temp.style.opacity = '0';
        document.body.appendChild(temp);
        temp.select();
        try { document.execCommand('copy'); applyCopiedFeedback(); } catch (e) {}
        document.body.removeChild(temp);
      }
    }

    function selectInquiryTopic(btn) {
      const subjectInput = document.getElementById('subject');
      const messageInput = document.getElementById('message');
      const val = currentLang === 'vi'
        ? btn.getAttribute('data-topic-vi')
        : btn.getAttribute('data-topic-en');

      if (subjectInput && val) {
        subjectInput.value = val;
      }

      document.querySelectorAll('.topic-preset-btn').forEach(chip => {
        const isActive = chip === btn;
        chip.className = isActive
          ? 'topic-preset-btn px-3 py-1.5 rounded-xl bg-sky-500 border border-sky-500 text-xs font-medium text-white shadow-sm transition-all'
          : 'topic-preset-btn px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/90 dark:border-slate-800 text-xs font-medium text-slate-700 dark:text-slate-200 hover:border-sky-400 hover:text-sky-600 dark:hover:text-sky-400 transition-all';
      });

      if (messageInput && !messageInput.value.trim()) {
        messageInput.focus();
      }
    }

    function setContactSubmitLoading() {
      const btn = document.getElementById('btnSubmit');
      if (!btn) return;
      const sendingLabel = getMergedDict(currentLang).btn_sending || 'Sending...';
      btn.disabled = true;
      btn.innerHTML = `<svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle><path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"></path></svg><span>${sendingLabel}</span>`;
    }

    document.addEventListener('DOMContentLoaded', () => {
      const msgEl = document.getElementById('message');
      const counterEl = document.getElementById('messageCharCount');
      const updateCounter = () => {
        if (msgEl && counterEl) {
          counterEl.textContent = `${msgEl.value.length} / 4000`;
        }
      };
      if (msgEl) {
        updateCounter();
        msgEl.addEventListener('input', updateCounter);
      }

      const nameInput = document.getElementById('name');
      if (window.innerWidth > 768 && nameInput && !nameInput.value && !document.querySelector('.bg-emerald-50')) {
        nameInput.focus();
      }
    });
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
              grecaptcha.execute("{{ config('services.recaptcha.site_key') }}", { action: 'contact_submit' })
                .then(function(token) {
                  tokenInput.value = token;
                  contactForm.submit();
                })
                .catch(function() {
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
@endsection
