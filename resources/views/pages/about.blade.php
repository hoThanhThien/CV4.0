@extends('layouts.pearl')

@section('active_nav', 'about')
@section('title', (app()->getLocale() === 'vi' ? 'Về Tôi | Hồ Thành Thiện - Kỹ sư Phần mềm Full-Stack & RAG AI' : 'About | Ho Thanh Thien - Full-Stack & RAG AI Engineer'))
@section('description', app()->getLocale() === 'vi'
    ? 'Tìm hiểu về Hồ Thành Thiện — Kỹ sư Công nghệ Thông tin tốt nghiệp loại Xuất sắc ĐH GTVT TP.HCM (GPA kỳ gần nhất 4.0/4.0), chuyên sâu Spring Boot, Laravel, Odoo ERP & RAG AI.'
    : 'Learn about Ho Thanh Thien — Software Engineer graduating with Distinction from UTH (4.0/4.0 latest GPA), specializing in Spring Boot, Laravel, Odoo ERP, and Applied RAG AI.')

@section('content')
@php
    $isVi = app()->getLocale() === 'vi';
@endphp

<!-- =========================================================================
     SECTION 1: PROFILE HERO BENTO CARD
========================================================================= -->
<section class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm">
  <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

    <!-- Monogram & Academic Identity Plate -->
    <div class="lg:col-span-4 flex flex-col items-center lg:items-stretch">
      <div class="w-full max-w-xs mx-auto rounded-3xl bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950 border border-slate-800 p-7 text-center text-white shadow-xl relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-sky-500/20 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-emerald-500/15 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-4 flex flex-col items-center">
          <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 p-0.5 shadow-lg shadow-sky-500/25">
            <img src="{{ asset('android-chrome-192x192.png') }}" width="80" height="80" alt="Ho Thanh Thien Logo" class="w-full h-full object-cover rounded-[14px]">
          </div>

          <div class="font-display text-3xl font-extrabold tracking-tight text-white">HT</div>

          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-500/15 border border-sky-400/30 font-mono text-xs font-bold tracking-widest uppercase text-sky-300">
            FULL-STACK // AI
          </span>

          <div class="pt-3 border-t border-slate-800 w-full font-mono text-xs text-slate-300 leading-relaxed space-y-0.5">
            <div class="font-bold text-white">B.Eng Information Technology</div>
            <div class="text-slate-300">UTH &bull; 2023 &ndash; 2026</div>
            <div class="text-emerald-300 font-semibold pt-1">GPA 3.6/4.0 &bull; Latest 4.0</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Narrative & Meta -->
    <div class="lg:col-span-8 space-y-5">
      <nav class="flex items-center gap-2 font-mono text-xs text-slate-600 dark:text-slate-400" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="hover:text-sky-700 dark:hover:text-sky-400 transition-colors inline-flex items-center gap-1">
          <i data-lucide="home" class="w-3.5 h-3.5"></i>
          <span data-i18n="breadcrumb_home">{{ $isVi ? 'Trang chủ' : 'Home' }}</span>
        </a>
        <span>/</span>
        <span class="text-slate-900 dark:text-white font-semibold" data-i18n="nav_about">{{ $isVi ? 'Giới thiệu' : 'About' }}</span>
      </nav>

      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200/80 dark:border-emerald-500/30 font-mono text-xs font-bold tracking-widest uppercase text-emerald-800 dark:text-emerald-300">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        <span data-i18n="about_kicker">{{ $isVi ? 'VỀ BẢN THÂN // HỒ SƠ KỸ SƯ' : 'ABOUT ME // ENGINEERING PROFILE' }}</span>
      </div>

      <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.75rem] font-extrabold tracking-tight leading-[1.12] text-slate-900 dark:text-white">
        <span data-i18n="about_greeting">{{ $isVi ? 'Xin chào, tôi là' : "Hi, I'm" }}</span>
        <span class="bg-gradient-to-r from-sky-700 via-indigo-700 to-emerald-700 dark:from-sky-400 dark:via-indigo-400 dark:to-emerald-400 bg-clip-text text-transparent" data-i18n="brand_name">
          {{ $isVi ? 'Hồ Thành Thiện' : 'Ho Thanh Thien' }}
        </span>
      </h1>

      <div class="font-mono text-xs sm:text-sm font-semibold text-sky-700 dark:text-sky-400">
        Full-Stack Web Developer &bull; Laravel &bull; Java Spring Boot &bull; Odoo ERP &bull; RAG AI
      </div>

      <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl" data-en="Passionate Full-Stack Software Engineer with practical experience building scalable enterprise web systems, Odoo ERP business workflows, and Applied RAG AI applications. Graduating with Excellent distinction at UTH (4.0/4.0 latest semester GPA)." data-vi="Lập trình viên Full-Stack với kinh nghiệm thực chiến xây dựng hệ thống web doanh nghiệp, quy trình Odoo ERP và ứng dụng AI RAG/LLM. Tốt nghiệp Kỹ sư CNTT loại Xuất sắc tại ĐH GTVT TP.HCM với điểm GPA học kỳ gần nhất đạt 4.0/4.0.">
        {{ __("Passionate Full-Stack developer with hands-on experience building modern web applications through personal and academic projects. Proficient in ReactJS, Java Spring Boot, PHP, HTML, CSS, JavaScript, and RESTful APIs with a strong foundation in database design and software development.") }}
      </p>

      <!-- Clickable Contact Strip -->
      <div class="flex flex-wrap gap-2.5 text-xs font-mono">
        <a href="tel:+84375227764" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-sky-400 transition-colors">
          <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-700 dark:text-emerald-400"></i>
          <span>0375227764</span>
        </a>
        <a href="mailto:hothanhthien119@gmail.com" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-sky-400 transition-colors">
          <i data-lucide="mail" class="w-3.5 h-3.5 text-sky-700 dark:text-sky-400"></i>
          <span>hothanhthien119@gmail.com</span>
        </a>
        <a href="https://hothanhthien.io.vn" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-sky-400 transition-colors">
          <i data-lucide="globe" class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400"></i>
          <span>hothanhthien.io.vn</span>
        </a>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-300">
          <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-600 dark:text-rose-400"></i>
          <span>Ho Chi Minh City</span>
        </span>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center gap-2.5 pt-1">
        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-700 text-white text-xs font-mono font-bold hover:bg-sky-800 transition-all shadow-lg shadow-sky-700/20">
          <i data-lucide="folder-git-2" class="w-3.5 h-3.5"></i>
          <span data-i18n="cta_projects">{{ $isVi ? 'Xem Dự án' : 'View Projects' }}</span>
        </a>
        <a href="{{ route('certificates') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:border-sky-400 transition-all">
          <i data-lucide="award" class="w-3.5 h-3.5 text-amber-700 dark:text-amber-400"></i>
          <span data-i18n="cta_certificates">{{ $isVi ? 'Brochure & Bằng cấp' : 'Brochure & Certificates' }}</span>
        </a>
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 dark:bg-slate-950 border border-slate-900 dark:border-slate-700 text-white text-xs font-mono font-semibold hover:bg-slate-800 dark:hover:border-sky-400 transition-all">
          <i data-lucide="send" class="w-3.5 h-3.5"></i>
          <span data-i18n="nav_hire">{{ $isVi ? 'Liên hệ' : 'Contact Me' }}</span>
        </a>
        <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:text-sky-700 dark:hover:text-sky-300 transition-all">
          <i data-lucide="github" class="w-3.5 h-3.5"></i>
          <span>GitHub</span>
        </a>
        <a href="https://www.linkedin.com/in/h%E1%BB%93-th%C3%A0nh-thi%E1%BB%87n-1a8559394" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 text-xs font-mono font-semibold text-slate-700 dark:text-slate-200 hover:text-sky-700 dark:hover:text-sky-300 transition-all">
          <i data-lucide="linkedin" class="w-3.5 h-3.5"></i>
          <span>LinkedIn</span>
        </a>
      </div>
    </div>

  </div>
</section>

<!-- =========================================================================
     SECTION 2: PERSONAL STORY & ENGINEERING PRINCIPLES
========================================================================= -->
<section class="space-y-6">
  <div class="space-y-1.5">
    <div class="font-mono text-xs font-bold uppercase tracking-widest text-sky-700 dark:text-sky-400" data-i18n="sec1_kicker">
      01 // {{ $isVi ? 'HÀNH TRÌNH & TRIẾT LÝ KỸ THUẬT' : 'PERSONAL STORY & PHILOSOPHY' }}
    </div>
    <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white" data-i18n="sec1_title">
      {{ $isVi ? 'Nguyên tắc Thiết kế & Xây dựng Hệ thống' : 'Design & Engineering Principles' }}
    </h2>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Narrative Box -->
    <div class="lg:col-span-5 spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
      <div class="relative z-10 space-y-4 text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed">
        <p data-en="I'm a passionate developer with a love for building clean, performant, and user-friendly web applications. I bridge the gap between design and functionality to create digital experiences that matter." data-vi="Tôi là kỹ sư phần mềm đam mê xây dựng các ứng dụng web sạch, hiệu năng cao và thân thiện với người dùng, kết nối tư duy thẩm mỹ UI/UX với kiến trúc backend bền vững.">
          {{ __("I'm a passionate developer with a love for building clean, performant, and user-friendly web applications. I bridge the gap between design and functionality to create digital experiences that matter.") }}
        </p>
        <p data-en="When I'm not coding, you'll find me exploring new technologies, contributing to open source, or brewing the perfect cup of coffee." data-vi="Ngoài thời gian lập trình, tôi thường nghiên cứu kiến trúc RAG/LLM mới, tối ưu hóa quy trình triển khai hệ thống và khám phá công nghệ nguồn mở.">
          {{ __("When I'm not coding, you'll find me exploring new technologies, contributing to open source, or brewing the perfect cup of coffee") }}
        </p>
      </div>
    </div>

    <!-- 3 Pillars -->
    <div class="lg:col-span-7 grid grid-cols-1 gap-4">
      <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
        <span class="relative z-10 w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-500/15 border border-sky-200 dark:border-sky-500/30 font-mono text-sm font-extrabold text-sky-700 dark:text-sky-400 flex items-center justify-center flex-shrink-0">01</span>
        <div class="relative z-10 space-y-1">
          <h3 class="font-display font-bold text-base text-slate-900 dark:text-white" data-i18n="pillar1_title">{{ $isVi ? 'Kiến trúc Sạch & Bền vững' : 'Engineering Craft' }}</h3>
          <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-en="Prioritizing normalized schema design, predictable APIs, and maintainable architecture." data-vi="Ưu tiên thiết kế CSDL chuẩn hóa, API rõ ràng và mã nguồn dễ bảo trì lâu dài.">
            {{ $isVi ? 'Ưu tiên thiết kế CSDL chuẩn hóa, API rõ ràng và mã nguồn dễ bảo trì lâu dài.' : 'Prioritizing normalized schema design, predictable APIs, and maintainable architecture.' }}
          </p>
        </div>
      </div>

      <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
        <span class="relative z-10 w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/15 border border-indigo-200 dark:border-indigo-500/30 font-mono text-sm font-extrabold text-indigo-700 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">02</span>
        <div class="relative z-10 space-y-1">
          <h3 class="font-display font-bold text-base text-slate-900 dark:text-white" data-i18n="pillar2_title">{{ $isVi ? 'Hệ thống Fullstack & AI' : 'Fullstack & AI Systems' }}</h3>
          <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-en="Bridging expressive interfaces with resilient backends and practical RAG / LLM pipelines." data-vi="Kết hợp frontend trực quan với backend vững chắc (Laravel, Spring Boot) và ứng dụng RAG / LLM thực tiễn.">
            {{ $isVi ? 'Kết hợp frontend trực quan với backend vững chắc (Laravel, Spring Boot) và ứng dụng RAG / LLM thực tiễn.' : 'Bridging expressive interfaces with resilient backends and practical RAG / LLM pipelines.' }}
          </p>
        </div>
      </div>

      <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
        <span class="relative z-10 w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200 dark:border-emerald-500/30 font-mono text-sm font-extrabold text-emerald-700 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">03</span>
        <div class="relative z-10 space-y-1">
          <h3 class="font-display font-bold text-base text-slate-900 dark:text-white" data-i18n="pillar3_title">{{ $isVi ? 'Kỷ luật Học thuật' : 'Academic Discipline' }}</h3>
          <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-en="Sustaining high rigor (4.0 semester GPA & Excellent graduation standing at UTH)." data-vi="Duy trì kỷ luật học thuật cao (GPA 4.0/4.0 học kỳ gần nhất, tốt nghiệp Xuất sắc ĐH GTVT TP.HCM).">
            {{ $isVi ? 'Duy trì kỷ luật học thuật cao (GPA 4.0/4.0 học kỳ gần nhất, tốt nghiệp Xuất sắc ĐH GTVT TP.HCM).' : 'Sustaining high rigor (4.0 semester GPA & Excellent graduation standing at UTH).' }}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =========================================================================
     SECTION 3: KEY MILESTONES & AWARDS
========================================================================= -->
<section class="space-y-6">
  <div class="space-y-1.5">
    <div class="font-mono text-xs font-bold uppercase tracking-widest text-sky-700 dark:text-sky-400">02 // MILESTONES</div>
    <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white" data-i18n="sec2_title">
      {{ $isVi ? 'Thành tích & Giải thưởng Nổi bật' : 'Key Achievements & Honors' }}
    </h2>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <article class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between gap-4 hover:-translate-y-1 transition-all">
      <div class="relative z-10 space-y-3">
        <div class="flex items-center justify-between">
          <span class="font-display text-3xl font-extrabold text-sky-700 dark:text-sky-400">4.0</span>
          <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 font-mono text-xs font-semibold text-slate-600 dark:text-slate-400">2026</span>
        </div>
        <h3 class="font-display text-lg font-extrabold text-slate-900 dark:text-white" data-i18n="ach1_title">
          {{ $isVi ? 'GPA Học kỳ Gần nhất — 4.0/4.0' : 'GPA in Most Recent Semester — 4.0/4.0' }}
        </h3>
        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-en="Achieved a perfect 4.0/4.0 GPA in the most recent semester, demonstrating high dedication, discipline, and strong software engineering competence." data-vi="Đạt kết quả học tập xuất sắc với điểm GPA 4.0/4.0 trong học kỳ gần nhất, thể hiện tính kỷ luật cao và tư duy logic chuyên sâu.">
          {{ $isVi ? 'Đạt kết quả học tập xuất sắc với điểm GPA 4.0/4.0 trong học kỳ gần nhất, thể hiện tính kỷ luật cao và tư duy logic chuyên sâu.' : 'Achieved a perfect 4.0/4.0 GPA in the most recent semester, demonstrating high dedication, discipline, and strong software engineering competence.' }}
        </p>
      </div>
      <div class="relative z-10 flex flex-wrap gap-2 font-mono text-xs">
        <span class="px-2.5 py-1 rounded-lg bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 font-semibold" data-i18n="ach1_tag1">{{ $isVi ? 'GPA tuyệt đối 4.0' : 'Perfect 4.0 GPA' }}</span>
        <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300" data-i18n="ach1_tag2">{{ $isVi ? 'Kỷ luật cao' : 'High Discipline' }}</span>
      </div>
    </article>

    <article class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between gap-4 hover:-translate-y-1 transition-all">
      <div class="relative z-10 space-y-3">
        <div class="flex items-center justify-between">
          <span class="font-display text-3xl font-extrabold text-emerald-700 dark:text-emerald-400">3.6</span>
          <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 font-mono text-xs font-semibold text-slate-600 dark:text-slate-400">2026</span>
        </div>
        <h3 class="font-display text-lg font-extrabold text-slate-900 dark:text-white" data-i18n="ach2_title">
          {{ $isVi ? 'Tốt nghiệp Xuất sắc — Cử nhân CNTT' : "Bachelor's Degree – Excellent Standing" }}
        </h3>
        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-en="Bachelor's Degree in Information Technology from University of Transport Ho Chi Minh City with Excellent Graduation Classification." data-vi="Bằng Cử nhân Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH) với xếp loại Tốt nghiệp Xuất sắc.">
          {{ $isVi ? 'Bằng Cử nhân Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH) với xếp loại Tốt nghiệp Xuất sắc.' : "Bachelor's Degree in Information Technology from University of Transport Ho Chi Minh City with Excellent Graduation Classification." }}
        </p>
      </div>
      <div class="relative z-10 flex flex-wrap gap-2 font-mono text-xs">
        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 font-semibold" data-i18n="ach2_tag1">{{ $isVi ? 'Loại Xuất sắc' : 'Excellent' }}</span>
        <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300" data-i18n="ach2_tag2">{{ $isVi ? 'ĐH GTVT TP.HCM' : 'UTH' }}</span>
      </div>
    </article>

    <article class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 shadow-sm flex flex-col justify-between gap-4 hover:-translate-y-1 transition-all">
      <div class="relative z-10 space-y-3">
        <div class="flex items-center justify-between">
          <span class="font-display text-3xl font-extrabold text-indigo-700 dark:text-indigo-400">2nd</span>
          <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 font-mono text-xs font-semibold text-slate-600 dark:text-slate-400">2025</span>
        </div>
        <h3 class="font-display text-lg font-extrabold text-slate-900 dark:text-white" data-i18n="ach3_title">
          {{ $isVi ? 'Giải Nhì Thiết kế Logo Khoa/Viện CNTT' : '2nd Prize IT Faculty Logo Design' }}
        </h3>
        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-en="Designed a competition-winning logo concept, demonstrating strong creativity, visual communication, and design thinking." data-vi="Thiết kế ý tưởng biểu trưng đạt giải cấp Khoa/Viện, minh chứng cho tư duy thẩm mỹ UI/UX, truyền thông thị giác và sáng tạo.">
          {{ __('Designed a competition-winning logo concept, demonstrating strong creativity, visual communication, and design thinking.') }}
        </p>
      </div>
      <div class="relative z-10 flex flex-wrap gap-2 font-mono text-xs">
        <span class="px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 font-semibold">Design Thinking</span>
        <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300" data-i18n="ach3_tag2">{{ $isVi ? 'Truyền thông thị giác' : 'Visual Identity' }}</span>
      </div>
    </article>
  </div>
</section>

<!-- =========================================================================
     SECTION 4: EDUCATION RECORD (UTH)
========================================================================= -->
<section class="space-y-6">
  <div class="space-y-1.5">
    <div class="font-mono text-xs font-bold uppercase tracking-widest text-sky-700 dark:text-sky-400">03 // ACADEMIC RECORD</div>
    <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white" data-i18n="sec3_title">
      {{ $isVi ? 'Học vấn & Nền tảng Chuyên môn' : 'Education & Academic Record' }}
    </h2>
  </div>

  <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-200/80 dark:border-slate-800">
      <div class="space-y-1">
        <h3 class="font-display text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white" data-i18n="edu_school">
          {{ __('University of Transport Ho Chi Minh City') }}
        </h3>
        <div class="text-sm font-semibold text-sky-700 dark:text-sky-400" data-i18n="edu_degree">
          {{ __("Bachelor's Degree Information Technology") }}
        </div>
      </div>
      <span class="px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 font-mono text-xs font-bold text-slate-700 dark:text-slate-200 self-start sm:self-center">
        2023 &ndash; 2026
      </span>
    </div>

    <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 space-y-1">
        <div class="font-mono text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400" data-i18n="edu_gpa_lbl">{{ __('Cumulative GPA') }}</div>
        <div class="font-display text-2xl font-extrabold text-sky-700 dark:text-sky-400">3.6 / 4.0</div>
      </div>
      <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 space-y-1">
        <div class="font-mono text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400" data-i18n="edu_rank_lbl">{{ __('Classification') }}</div>
        <div class="font-display text-2xl font-extrabold text-emerald-700 dark:text-emerald-400" data-i18n="edu_rank_val">{{ __('Excellent') }}</div>
      </div>
      <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 space-y-1">
        <div class="font-mono text-xs uppercase tracking-wider text-slate-600 dark:text-slate-400" data-i18n="edu_club_lbl">{{ __('Community & Clubs') }}</div>
        <div class="text-sm font-bold text-slate-800 dark:text-slate-200" data-i18n="edu_club_val">{{ __('Member Google Developer Chapter University of Transport Ho Chi Minh City') }}</div>
      </div>
    </div>
  </div>
</section>

<!-- =========================================================================
     SECTION 5: CAPABILITIES — 3D RADIAL ORBIT WHEEL + DOMAIN SKILL MATRIX
========================================================================= -->
@if($skills->count())
@php
    $aboutBuckets = [
        'languages' => ['label_en' => 'Programming Languages', 'label_vi' => 'Ngôn ngữ Lập trình', 'icon' => 'code-2', 'items' => []],
        'frontend' => ['label_en' => 'Frontend Development', 'label_vi' => 'Phát triển Frontend', 'icon' => 'layers', 'items' => []],
        'backend' => ['label_en' => 'Backend Development', 'label_vi' => 'Phát triển Backend', 'icon' => 'server', 'items' => []],
        'database' => ['label_en' => 'Database Management', 'label_vi' => 'Quản trị Cơ sở dữ liệu', 'icon' => 'database', 'items' => []],
        'api' => ['label_en' => 'API & Security', 'label_vi' => 'API & Bảo mật', 'icon' => 'network', 'items' => []],
        'ai' => ['label_en' => 'AI / RAG / LLM', 'label_vi' => 'AI / RAG / LLM', 'icon' => 'cpu', 'items' => []],
        'devops' => ['label_en' => 'DevOps & Deployment', 'label_vi' => 'DevOps & Triển khai', 'icon' => 'cloud', 'items' => []],
        'tools' => ['label_en' => 'Tools & Platforms', 'label_vi' => 'Công cụ & Nền tảng', 'icon' => 'wrench', 'items' => []],
    ];
    $seenSkills = [];
    $omitSubtopics = [
        'agile / scrum', 'clean code & documentation', 'mvc & solid principles',
        'design patterns', 'session management', 'password hashing', 'php (backend)',
    ];
    foreach ($skills as $rawCat => $catItems) {
        foreach ($catItems as $sk) {
            $nameLow = strtolower(trim($sk->name));
            if (in_array($nameLow, $omitSubtopics, true) || isset($seenSkills[$nameLow])) { continue; }
            $seenSkills[$nameLow] = true;
            $catLow = strtolower($rawCat);
            if ($catLow === 'languages') { $aboutBuckets['languages']['items'][] = $sk; }
            elseif ($catLow === 'frontend' || str_contains($nameLow, 'next') || str_contains($nameLow, 'responsive') || str_contains($nameLow, 'blade')) { $aboutBuckets['frontend']['items'][] = $sk; }
            elseif ($catLow === 'backend') { $aboutBuckets['backend']['items'][] = $sk; }
            elseif ($catLow === 'database') { $aboutBuckets['database']['items'][] = $sk; }
            elseif (str_contains($nameLow, 'rag') || str_contains($nameLow, 'llm') || str_contains($nameLow, 'vector')) { $aboutBuckets['ai']['items'][] = $sk; }
            elseif (str_contains($nameLow, 'docker') || str_contains($nameLow, 'aws') || str_contains($nameLow, 'nginx') || str_contains($nameLow, 'cpanel') || str_contains($nameLow, 'ci/cd')) { $aboutBuckets['devops']['items'][] = $sk; }
            elseif ($catLow === 'api development' || str_contains($nameLow, 'jwt') || str_contains($nameLow, 'rbac') || str_contains($nameLow, 'otp') || str_contains($nameLow, 'websocket') || str_contains($nameLow, 'payment')) { $aboutBuckets['api']['items'][] = $sk; }
            else { $aboutBuckets['tools']['items'][] = $sk; }
        }
    }
    $aboutBuckets = array_filter($aboutBuckets, fn($b) => count($b['items']) > 0);
@endphp
<section class="space-y-6">
  <div class="space-y-1.5">
    <div class="font-mono text-xs font-bold uppercase tracking-widest text-sky-700 dark:text-sky-400">04 // CAPABILITIES</div>
    <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white" data-i18n="sec4_title">
      {{ $isVi ? 'Kỹ năng Kỹ thuật & Năng lực Công nghệ' : 'Technical Skills & Stack Matrix' }}
    </h2>
    <p class="text-sm text-slate-600 dark:text-slate-300" data-i18n="sec4_subtitle">
      {{ __('Technologies and tools I work with every day') }}
    </p>
  </div>

  <!-- Interactive 3D Radial Orbit Skill Wheel -->
  <x-radial-orbit-wheel />

  <!-- Domain Skill Panels -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
    @foreach($aboutBuckets as $bucket)
      <div class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 shadow-sm space-y-4">
        <div class="relative z-10 flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
          <div class="flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-xl bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-400 flex items-center justify-center">
              <i data-lucide="{{ $bucket['icon'] }}" class="w-4 h-4"></i>
            </span>
            <h3 class="font-display font-bold text-base text-slate-900 dark:text-white" data-en="{{ $bucket['label_en'] }}" data-vi="{{ $bucket['label_vi'] }}">
              {{ $isVi ? $bucket['label_vi'] : $bucket['label_en'] }}
            </h3>
          </div>
          <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 font-mono text-xs font-bold text-slate-600 dark:text-slate-300">
            {{ count($bucket['items']) }}
          </span>
        </div>

        <div class="relative z-10 flex flex-wrap gap-2">
          @foreach($bucket['items'] as $skill)
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200">
              {!! \App\Support\TechIcon::render($skill->name) !!}
              <span>{{ $skill->name }}</span>
            </span>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
</section>
@endif

<!-- =========================================================================
     SECTION 6: WORK EXPERIENCE TIMELINE
========================================================================= -->
@if($experiences->count())
<section class="space-y-6">
  <div class="space-y-1.5">
    <div class="font-mono text-xs font-bold uppercase tracking-widest text-sky-700 dark:text-sky-400">05 // WORK HISTORY</div>
    <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white" data-i18n="sec5_title">
      {{ $isVi ? 'Kinh nghiệm Làm việc & Dự án Thực chiến' : 'Work Experience & Career Timeline' }}
    </h2>
  </div>

  <div class="space-y-5">
    @foreach($experiences as $exp)
      @php
          $companyEn = __($exp->company, [], 'en');
          $companyVi = __($exp->company, [], 'vi');
          $posEn = __($exp->position, [], 'en');
          $posVi = __($exp->position, [], 'vi');
          $descEn = __($exp->description, [], 'en');
          $descVi = __($exp->description, [], 'vi');
          $sentencesEn = array_values(array_filter(array_map('trim', explode('.', (string) $descEn))));
          $sentencesVi = array_values(array_filter(array_map('trim', explode('.', (string) $descVi))));
      @endphp
      <article class="spotlight-card bg-white/95 dark:bg-slate-900/95 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-sm">
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

          <div class="lg:col-span-4 space-y-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-50 dark:bg-sky-500/15 border border-sky-200/80 dark:border-sky-500/30 font-mono text-xs font-bold text-sky-700 dark:text-sky-300">
              <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
              <span>{{ $exp->formatted_date }}</span>
            </span>
            <div class="font-display font-extrabold text-lg text-slate-900 dark:text-white pt-1" data-en="{{ $companyEn }}" data-vi="{{ $companyVi }}">
              {{ $exp->localized_company }}
            </div>
          </div>

          <div class="lg:col-span-8 space-y-4">
            <h3 class="font-display text-xl font-extrabold text-slate-900 dark:text-white" data-en="{{ $posEn }}" data-vi="{{ $posVi }}">
              {{ $exp->localized_position }}
            </h3>

            @if(count($sentencesEn) > 1 || count($sentencesVi) > 1)
              <ul data-lang-block="en" class="{{ $isVi ? 'hidden ' : '' }}space-y-2 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                @foreach($sentencesEn as $s)
                  <li class="flex items-start gap-2.5">
                    <i data-lucide="check" class="w-4 h-4 text-emerald-700 dark:text-emerald-400 flex-shrink-0 mt-0.5"></i>
                    <span>{{ $s }}.</span>
                  </li>
                @endforeach
              </ul>
              <ul data-lang-block="vi" class="{{ $isVi ? '' : 'hidden ' }}space-y-2 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                @foreach($sentencesVi as $s)
                  <li class="flex items-start gap-2.5">
                    <i data-lucide="check" class="w-4 h-4 text-emerald-700 dark:text-emerald-400 flex-shrink-0 mt-0.5"></i>
                    <span>{{ $s }}.</span>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-en="{{ $descEn }}" data-vi="{{ $descVi }}">
                {{ $exp->localized_description }}
              </p>
            @endif

            @if(count($exp->tech_tags))
              <div class="flex flex-wrap gap-2 pt-1">
                @foreach($exp->tech_tags as $tag)
                  <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 font-mono text-xs font-semibold text-slate-700 dark:text-slate-200">
                    {!! \App\Support\TechIcon::render($tag) !!}
                    <span>{{ $tag }}</span>
                  </span>
                @endforeach
              </div>
            @endif
          </div>

        </div>
      </article>
    @endforeach
  </div>
</section>
@endif
@endsection

@section('scripts')
<script>
  window.PAGE_I18N = {
    en: {
      about_kicker: 'ABOUT ME // ENGINEERING PROFILE',
      about_greeting: "Hi, I'm",
      cta_projects: 'View Projects',
      cta_certificates: 'Brochure & Certificates',
      sec1_kicker: '01 // PERSONAL STORY & PHILOSOPHY',
      sec1_title: 'Design & Engineering Principles',
      pillar1_title: 'Engineering Craft',
      pillar2_title: 'Fullstack & AI Systems',
      pillar3_title: 'Academic Discipline',
      sec2_title: 'Key Achievements & Honors',
      ach1_title: 'GPA in Most Recent Semester — 4.0/4.0',
      ach1_tag1: 'Perfect 4.0 GPA',
      ach1_tag2: 'High Discipline',
      ach2_title: "Bachelor's Degree – Excellent Standing",
      ach2_tag1: 'Excellent',
      ach2_tag2: 'UTH',
      ach3_title: '2nd Prize IT Faculty Logo Design',
      ach3_tag2: 'Visual Identity',
      sec3_title: 'Education & Academic Record',
      edu_school: 'University of Transport Ho Chi Minh City',
      edu_degree: "Bachelor's Degree Information Technology",
      edu_gpa_lbl: 'Cumulative GPA',
      edu_rank_lbl: 'Classification',
      edu_rank_val: 'Excellent',
      edu_club_lbl: 'Community & Clubs',
      edu_club_val: 'Member Google Developer Chapter University of Transport Ho Chi Minh City',
      sec4_title: 'Technical Skills & Stack Matrix',
      sec4_subtitle: 'Technologies and tools I work with every day',
      sec5_title: 'Work Experience & Career Timeline'
    },
    vi: {
      about_kicker: 'VỀ BẢN THÂN // HỒ SƠ KỸ SƯ',
      about_greeting: 'Xin chào, tôi là',
      cta_projects: 'Xem Dự án',
      cta_certificates: 'Brochure & Bằng cấp',
      sec1_kicker: '01 // HÀNH TRÌNH & TRIẾT LÝ KỸ THUẬT',
      sec1_title: 'Nguyên tắc Thiết kế & Xây dựng Hệ thống',
      pillar1_title: 'Kiến trúc Sạch & Bền vững',
      pillar2_title: 'Hệ thống Fullstack & AI',
      pillar3_title: 'Kỷ luật Học thuật',
      sec2_title: 'Thành tích & Giải thưởng Nổi bật',
      ach1_title: 'GPA Học kỳ Gần nhất — 4.0/4.0',
      ach1_tag1: 'GPA tuyệt đối 4.0',
      ach1_tag2: 'Kỷ luật cao',
      ach2_title: 'Tốt nghiệp Xuất sắc — Cử nhân CNTT',
      ach2_tag1: 'Loại Xuất sắc',
      ach2_tag2: 'ĐH GTVT TP.HCM',
      ach3_title: 'Giải Nhì Thiết kế Logo Khoa/Viện CNTT',
      ach3_tag2: 'Truyền thông thị giác',
      sec3_title: 'Học vấn & Nền tảng Chuyên môn',
      edu_school: 'Trường Đại học Giao thông Vận tải TP.HCM (UTH)',
      edu_degree: 'Kỹ sư Công nghệ Thông tin',
      edu_gpa_lbl: 'Điểm trung bình tích lũy (GPA)',
      edu_rank_lbl: 'Xếp loại Tốt nghiệp',
      edu_rank_val: 'Xuất sắc',
      edu_club_lbl: 'Hoạt động Cộng đồng',
      edu_club_val: 'Thành viên Google Developer Chapter — ĐH GTVT TP.HCM',
      sec4_title: 'Kỹ năng Kỹ thuật & Năng lực Công nghệ',
      sec4_subtitle: 'Các công nghệ, framework và công cụ thực chiến hàng ngày',
      sec5_title: 'Kinh nghiệm Làm việc & Dự án Thực chiến'
    }
  };
</script>
@endsection
