@php
    $isVi = app()->getLocale() === 'vi';
@endphp
@extends('layouts.pearl')

@section('nav_mode', 'home')
@section('main_class', 'flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-16 space-y-16 sm:space-y-24')
@section('title', $isVi ? 'Hồ Thành Thiện — Kỹ sư Phần mềm Full-Stack & RAG AI' : 'Ho Thanh Thien — Full-Stack & RAG AI Engineer')
@section('description', $isVi ? 'Portfolio chính thức của Kỹ sư Phần mềm Hồ Thành Thiện (UTH - Loại Xuất sắc, GPA 4.0/4.0). Chuyên sâu Java Spring Boot, Laravel, Odoo ERP và RAG AI.' : 'Official portfolio of Software Engineer Ho Thanh Thien (B.Eng IT Distinction @ UTH, 4.0/4.0 GPA). Specialized in Java Spring Boot, Laravel, Odoo ERP & Applied RAG AI.')

@section('styles')
  :root {
    --radius: 184px;
    --orbit-scale: 1;
  }

  .orbit-canvas-viewport {
    position: relative;
    width: 100%;
    height: calc(468px * var(--orbit-scale, 1));
  }
  .orbit-canvas {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 486px;
    height: 468px;
    --radius: 184px;
    transform: translate(-50%, -50%) scale(var(--orbit-scale, 1));
    transform-origin: center center;
  }

  /* SKILL ANIMATION MODE 1: 3D VORTEX SPIN GRID & 180 FLIP CARD */
  @keyframes skillVortexSpinIn {
    0% {
      opacity: 0;
      transform: rotateY(-180deg) rotateX(40deg) scale(0.28);
    }
    100% {
      opacity: 1;
      transform: rotateY(0deg) rotateX(0deg) scale(1);
    }
  }

  @keyframes iconSpin360 {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  .skill-grid-item {
    perspective: 1100px;
    height: 152px;
    opacity: 0;
    animation: skillVortexSpinIn 0.65s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    animation-delay: calc(var(--index, 0) * 55ms);
    transition: transform 0.25s ease, opacity 0.22s ease;
  }
  .skill-grid-item.is-exiting {
    opacity: 0 !important;
    transform: rotateY(90deg) scale(0.65) !important;
    animation: none !important;
  }
  .skill-grid-item .spin-icon-target {
    animation: iconSpin360 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    animation-delay: calc(var(--index, 0) * 55ms);
  }

  .grid-flip-inner {
    position: relative;
    width: 100%;
    height: 100%;
    transform-style: preserve-3d;
    transition: transform 0.62s cubic-bezier(0.16, 1, 0.3, 1);
    cursor: pointer;
  }
  .skill-grid-item:hover .grid-flip-inner,
  .skill-grid-item.is-flipped .grid-flip-inner {
    transform: rotateY(180deg);
  }
  .grid-flip-front, .grid-flip-back {
    position: absolute;
    inset: 0;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    border-radius: 1rem;
  }
  .grid-flip-back {
    transform: rotateY(180deg);
  }

  /* SKILL ANIMATION MODE 2: 3D ORBIT WHEEL */
  @keyframes orbitNodeEmerge {
    0% {
      opacity: 0;
      transform: translate(-50%, -50%) rotate(calc(var(--angle) - 160deg)) translateY(0px) scale(0.2);
    }
    100% {
      opacity: 1;
      transform: translate(-50%, -50%) rotate(var(--angle)) translateY(calc(-1 * var(--radius))) rotate(calc(-1 * var(--angle))) scale(1);
    }
  }
  @keyframes orbitStageRevolve {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }
  @keyframes orbitNodeCounterRevolve {
    from { transform: rotate(0deg); }
    to { transform: rotate(-360deg); }
  }

  .orbit-spinning-ring {
    transform-origin: 50% 50%;
    animation: orbitStageRevolve 32s linear infinite;
  }
  .orbit-node {
    position: absolute;
    top: 50%;
    left: 50%;
    opacity: 0;
    animation: orbitNodeEmerge 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    user-select: none;
    cursor: pointer;
    pointer-events: auto;
    z-index: 20;
  }
  .orbit-node-upright {
    transform-origin: center center;
    animation: orbitNodeCounterRevolve 32s linear infinite;
  }
  .orbit-ring-counter {
    transform-origin: center center;
    animation: orbitNodeCounterRevolve 48s linear infinite;
  }
  #orbitStageBox.is-hover-paused .orbit-spinning-ring,
  #orbitStageBox.is-hover-paused .orbit-node-upright,
  #orbitStageBox.is-hover-paused .animate-spin-slow,
  #orbitStageBox.is-hover-paused .orbit-ring-counter,
  #orbitStageBox.is-manual-paused .orbit-spinning-ring,
  #orbitStageBox.is-manual-paused .orbit-node-upright,
  #orbitStageBox.is-manual-paused .animate-spin-slow,
  #orbitStageBox.is-manual-paused .orbit-ring-counter,
  #orbitStageBox:has(.orbit-node:hover) .orbit-spinning-ring,
  #orbitStageBox:has(.orbit-node:hover) .orbit-node-upright,
  #orbitStageBox:has(.orbit-node:hover) .animate-spin-slow,
  #orbitStageBox:has(.orbit-node:hover) .orbit-ring-counter {
    animation-play-state: paused;
  }
  .orbit-node-pill {
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .orbit-node:hover .orbit-node-pill,
  .orbit-node.is-active .orbit-node-pill {
    border-color: #0ea5e9;
    box-shadow: 0 10px 25px -4px rgba(14, 165, 233, 0.28), 0 0 0 2px rgba(14, 165, 233, 0.22);
    transform: scale(1.08);
  }
  .orbit-node:hover,
  .orbit-node.is-active {
    z-index: 40;
  }

  /* Terminal Typewriter Cursor */
  .cli-cursor {
    display: inline-block;
    width: 8px;
    height: 14px;
    background: #38bdf8;
    vertical-align: middle;
    margin-left: 3px;
    animation: blinkCursor 1s step-end infinite;
  }
  @keyframes blinkCursor {
    50% { opacity: 0; }
  }

  /* Spin replay icon spin */
  .replay-spinning svg {
    animation: spin 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  }
@endsection

@section('content')

    <!-- =========================================================================
         SECTION 1: HERO SECTION (SPLIT LAYOUT 7:5 — IMPRESSIVE IN 3 SECONDS)
    ========================================================================= -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
      
      <!-- Cột Trái (7 cols) -->
      <div class="lg:col-span-7 min-w-0 space-y-6">
        
        <!-- Status Availability Badge -->
        <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-700/80 shadow-sm text-xs font-mono font-semibold text-slate-700 dark:text-slate-200">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
          </span>
          <span data-i18n="hero_badge">Available for Full-Stack &amp; AI Engineer Roles • Ho Chi Minh City</span>
        </div>

        <!-- Giant Gradient Headline -->
        <h1 class="font-display text-[1.95rem] sm:text-5xl lg:text-[3.45rem] font-extrabold tracking-tight leading-[1.1] text-slate-900 dark:text-white">
          <span data-i18n="hero_title_1">Building scalable</span>
          <span class="bg-gradient-to-r from-sky-600 via-indigo-600 to-emerald-600 dark:from-sky-400 dark:via-indigo-400 dark:to-emerald-400 bg-clip-text text-transparent" data-i18n="hero_title_highlight">
            Web Systems &amp; RAG AI
          </span>
          <span data-i18n="hero_title_2">that solve real problems</span>
        </h1>

        <!-- Concise Verified Bio -->
        <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl" data-i18n="hero_desc">
          Honors B.Eng in Information Technology graduate at UTH with a 4.0/4.0 latest semester GPA. Hands-on experience architecting Java Spring Boot APIs, Laravel ecosystems, Odoo ERP workflows, and Python RAG/LLM chatbots.
        </p>

        <!-- 3 Verified Experience & Award Chips -->
        <div class="flex flex-wrap items-center gap-2.5 pt-1">
          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm text-xs font-semibold text-slate-700 dark:text-slate-200">
            <i data-lucide="briefcase" class="w-3.5 h-3.5 text-sky-500"></i>
            <span data-i18n="chip_aipower">Full-Stack Dev @ AIPower</span>
          </span>
          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm text-xs font-semibold text-slate-700 dark:text-slate-200">
            <i data-lucide="layers" class="w-3.5 h-3.5 text-indigo-500"></i>
            <span data-i18n="chip_enmasys">Odoo ERP @ Enmasys</span>
          </span>
          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm text-xs font-semibold text-slate-700 dark:text-slate-200">
            <i data-lucide="award" class="w-3.5 h-3.5 text-amber-500"></i>
            <span data-i18n="chip_award">2nd Prize UTH IT Logo Design</span>
          </span>
        </div>

        <!-- Đúng 2 nút CTA chính (Không chứa SĐT ở Hero) -->
        <div class="flex flex-wrap items-center gap-3.5 pt-2">
          <a href="#projects" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-slate-900 dark:bg-sky-500 text-white font-semibold text-sm hover:bg-slate-800 dark:hover:bg-sky-600 shadow-lg shadow-slate-900/15 dark:shadow-sky-500/25 hover:-translate-y-0.5 transition-all">
            <span data-i18n="cta_explore">Explore Featured Work</span>
            <i data-lucide="arrow-down-right" class="w-4 h-4"></i>
          </a>
          <a href="{{ asset('assets/docs/HoThanhThien_CV_Brochure.pdf') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 font-semibold text-sm hover:border-sky-400 hover:bg-sky-50/40 dark:hover:bg-slate-800 shadow-sm hover:-translate-y-0.5 transition-all">
            <i data-lucide="file-down" class="w-4 h-4 text-sky-600 dark:text-sky-400"></i>
            <span data-i18n="cta_brochure">Download Brochure PDF</span>
          </a>
        </div>

      </div>

      <!-- Cột Phải (5 cols): Interactive CLI Terminal + 3 Mini Bento KPI Cards -->
      <div class="lg:col-span-5 min-w-0 space-y-4">
        
        <!-- 1. Interactive CLI Terminal (macOS Dark Slate #0f172a) -->
        <div class="rounded-2xl bg-[#0f172a] border border-slate-800 shadow-2xl shadow-slate-900/20 overflow-hidden">
          <!-- macOS Titlebar -->
          <div class="px-4 py-3 bg-slate-900/90 border-b border-slate-800 flex items-center justify-between gap-2">
            <div class="flex items-center gap-1.5 flex-shrink-0">
              <span class="w-3 h-3 rounded-full bg-rose-500"></span>
              <span class="w-3 h-3 rounded-full bg-amber-400"></span>
              <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
            </div>
            <span class="font-mono text-[10px] sm:text-[11px] text-slate-400 truncate">thien@hothanhthien.io.vn — zsh</span>
            <span class="hidden sm:inline-block w-10"></span>
          </div>

          <!-- 4 Interactive Command Triggers -->
          <div class="px-4 py-2.5 bg-slate-900/50 border-b border-slate-800/80 flex flex-wrap gap-1.5">
            <button type="button" data-cli-key="whoami" onclick="runTerminalCmd('whoami')" class="cli-cmd-btn px-2.5 py-1 rounded-lg bg-sky-500/15 text-sky-400 border border-sky-500/30 font-mono text-[11px] font-semibold hover:bg-sky-500 hover:text-white transition-colors">
              whoami
            </button>
            <button type="button" data-cli-key="skills" onclick="runTerminalCmd('skills')" class="cli-cmd-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700 font-mono text-[11px] font-semibold hover:border-sky-400 hover:text-white transition-colors">
              skills --top
            </button>
            <button type="button" data-cli-key="awards" onclick="runTerminalCmd('awards')" class="cli-cmd-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700 font-mono text-[11px] font-semibold hover:border-sky-400 hover:text-white transition-colors">
              awards --verify
            </button>
            <button type="button" data-cli-key="rag" onclick="runTerminalCmd('rag')" class="cli-cmd-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700 font-mono text-[11px] font-semibold hover:border-sky-400 hover:text-white transition-colors">
              rag --status
            </button>
          </div>

          <!-- Terminal Output Window with Typewriter -->
          <div class="p-4 font-mono text-xs leading-relaxed min-h-[185px] text-slate-200 space-y-2 overflow-x-auto">
            <div class="text-slate-400">
              <span class="text-emerald-400 font-bold">$</span> <span id="cliTypedCommand" class="text-sky-300 font-semibold">whoami</span>
            </div>
            <pre id="cliOutputBody" class="whitespace-pre-wrap break-words text-slate-300 font-mono text-[11px] sm:text-[11.5px] leading-relaxed"></pre>
          </div>
        </div>

        <!-- 2. Hàng 3 thẻ Mini Bento khoe 3 con số đắt giá -->
        <div class="grid grid-cols-1 min-[420px]:grid-cols-3 gap-2.5 sm:gap-3">
          <div class="spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-3 sm:p-3.5 shadow-sm">
            <div class="font-display text-lg sm:text-2xl font-extrabold text-sky-600 dark:text-sky-400">4.0/4.0</div>
            <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400 mt-0.5 leading-tight" data-i18n="kpi_gpa">Latest Term GPA</div>
          </div>

          <div class="spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-3 sm:p-3.5 shadow-sm">
            <div class="font-display text-base sm:text-xl font-extrabold text-indigo-600 dark:text-indigo-400 leading-tight" data-i18n="kpi_degree_val">Distinction</div>
            <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400 mt-0.5 leading-tight" data-i18n="kpi_degree">B.Eng IT @ UTH</div>
          </div>

          <div class="spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-3 sm:p-3.5 shadow-sm">
            <div class="font-display text-base sm:text-xl font-extrabold text-emerald-600 dark:text-emerald-400 leading-tight" data-i18n="kpi_award_val">2nd Prize</div>
            <div class="text-[11px] font-mono text-slate-500 dark:text-slate-400 mt-0.5 leading-tight" data-i18n="kpi_award">UTH IT Logo Design</div>
          </div>
        </div>

      </div>
    </section>

    <!-- =========================================================================
         SECTION 2: TECHNICAL SKILLS — 3D VORTEX FLIP GRID & 3D ORBIT WHEEL
    ========================================================================= -->
    <section id="skills" class="space-y-6">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="space-y-2">
          <div class="font-mono text-xs font-bold tracking-widest uppercase text-sky-600 dark:text-sky-400 flex items-center gap-2">
            <span class="w-5 h-[1px] bg-sky-500"></span>
            <span data-i18n="skills_kicker">01 // TECHNICAL ARCHITECTURE</span>
          </div>
          <h2 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white" data-i18n="skills_title">
            Interactive Stack Matrix &amp; Orbit Wheel
          </h2>
        </div>

        <!-- Mode Switcher Toggle + Spin Again -->
        <div class="flex flex-wrap items-center gap-2">
          <div class="inline-flex flex-wrap p-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm font-mono text-[11px] sm:text-xs">
            <button type="button" id="btnModeGrid" onclick="switchSkillMode('grid')" class="px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 transition-all">
              <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
              <span data-i18n="mode_grid">3D Spin &amp; Flip Grid</span>
            </button>
            <button type="button" id="btnModeOrbit" onclick="switchSkillMode('orbit')" class="px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 transition-all">
              <i data-lucide="orbit" class="w-3.5 h-3.5"></i>
              <span data-i18n="mode_orbit">3D Orbit Wheel</span>
            </button>
          </div>

          <button type="button" id="btnSkillReplay" onclick="replaySkillsAnimation()" class="inline-flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sky-600 dark:text-sky-400 font-mono text-[11px] sm:text-xs font-bold hover:bg-sky-50 dark:hover:bg-slate-800 shadow-sm active:scale-95 transition-all">
            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
            <span data-i18n="btn_spin_again">Spin Again</span>
          </button>
        </div>
      </div>

      <!-- Outer Card Wrapper -->
      <div class="bg-white dark:bg-slate-900/90 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-4 sm:p-8 space-y-5 sm:space-y-6">
        
        <!-- Category Filter Tabs (5 Category Pills) -->
        <div id="skillCategoryTabs" class="flex flex-wrap items-center gap-2 pb-4 border-b border-slate-100 dark:border-slate-800">
          <!-- Injected by Vanilla JS -->
        </div>

        <!-- Hint Line -->
        <div class="flex flex-wrap items-center justify-between gap-2 text-xs font-mono text-sky-600 dark:text-sky-400 font-medium">
          <span data-i18n="skill_hint">✨ Hover or tap any skill card to 3D-flip &amp; inspect real project usage</span>
          <span id="activeSkillCountMeta" class="text-slate-400 text-[11px]">10 SKILLS</span>
        </div>

        <!-- CHẾ ĐỘ 1: 3D SPIN & FLIP GRID -->
        <div id="skillGridModeWrap" class="grid grid-cols-1 min-[440px]:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
          <!-- Dynamically populated by JS -->
        </div>

        <!-- CHẾ ĐỘ 2: 3D ORBIT WHEEL + RIGHT INSPECTOR -->
        <div id="skillOrbitModeWrap" class="hidden grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-stretch">
          
          <!-- Left Stage: Ice-Blue Radial Orbit Visualizer -->
          <div id="orbitStageBox" class="lg:col-span-7 min-w-0 w-full rounded-2xl pt-10 pb-12 px-3 sm:px-4 bg-gradient-to-b from-sky-50/70 via-white to-indigo-50/40 dark:from-slate-950 dark:via-slate-900 dark:to-indigo-950/40 border border-slate-200/70 dark:border-slate-800 flex items-center justify-center relative overflow-hidden">
            
            <div class="absolute top-3 left-3 sm:top-3.5 sm:left-4 font-mono text-[10px] sm:text-[11px] text-slate-400 z-30">ORBIT // TRIG_ROTATION</div>
            <div class="absolute top-3 right-3 sm:top-3.5 sm:right-4 font-mono text-[10px] sm:text-[11px] text-sky-600/70 font-semibold z-30 hidden sm:block">3D PRESERVE</div>
            <div class="absolute bottom-3 left-3 sm:bottom-3.5 sm:left-4 font-mono text-[10px] sm:text-[11px] text-slate-400 flex items-center gap-1.5 sm:gap-2 z-30">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <span id="orbitRadiusReadout">R=184px</span>
            </div>
            <button type="button" id="btnOrbitSpinToggle" onclick="toggleOrbitSpin()" class="absolute bottom-2.5 right-2.5 sm:bottom-3 sm:right-3.5 z-30 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/90 dark:bg-slate-900/90 border border-slate-200/90 dark:border-slate-700 text-[10px] sm:text-[11px] font-mono font-semibold text-sky-600 dark:text-sky-400 hover:border-sky-400 shadow-sm transition-all">
              <i data-lucide="pause" id="orbitSpinPauseIcon" class="w-3 h-3"></i>
              <i data-lucide="play" id="orbitSpinPlayIcon" class="w-3 h-3 hidden"></i>
              <span id="orbitSpinToggleLabel">Auto Orbit</span>
            </button>

            <!-- Proportional Scaling Orbit Viewport (Prevents Overflow & Adjacent Pill Overlap at Every Screen Width) -->
            <div id="orbitCanvasViewport" class="orbit-canvas-viewport">
              <div id="orbitCanvas" class="orbit-canvas flex items-center justify-center">
                <!-- 2 Concentric Dashed Orbit Rings -->
                <div id="orbitInnerRing" class="w-[228px] h-[228px] rounded-full border border-dashed border-indigo-500/25 dark:border-indigo-400/25 absolute pointer-events-none orbit-ring-counter"></div>
                <div id="orbitOuterRing" class="w-[368px] h-[368px] rounded-full border border-dashed border-sky-500/35 dark:border-sky-400/35 absolute pointer-events-none animate-spin-slow"></div>

                <!-- Central Circular Core Hub -->
                <div class="relative z-10 w-32 h-32 rounded-full bg-white dark:bg-slate-900 border-2 border-sky-400/60 shadow-xl shadow-sky-500/15 flex flex-col items-center justify-center text-center p-2">
                  <div class="text-[10px] font-mono tracking-widest text-sky-600 dark:text-sky-400 font-bold uppercase">ACTIVE CORE</div>
                  <div id="orbitHubCategory" class="text-sm font-extrabold text-slate-900 dark:text-white leading-tight mt-0.5 px-1">Core Stack</div>
                  <div id="orbitHubCount" class="text-[10px] font-mono text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5">10 Skills</div>
                </div>

                <!-- Dynamic Orbit Nodes Container -->
                <div id="orbitNodesStage" class="absolute inset-0 pointer-events-none orbit-spinning-ring">
                  <!-- Populated via JS -->
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: 3D Orbit Inspector Card -->
          <div class="lg:col-span-5 min-w-0 w-full spotlight-card bg-slate-50/70 dark:bg-slate-950/60 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6 flex flex-col justify-between space-y-6">
            <div class="space-y-5 relative z-10">
              <div class="flex items-center justify-between border-b border-slate-200/70 dark:border-slate-800 pb-3.5">
                <span class="font-mono text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400" data-i18n="inspector_title">3D Orbit Inspector</span>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono font-semibold bg-emerald-100/80 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300" data-i18n="inspector_verified">Verified Proof</span>
              </div>

              <!-- Icon & Name -->
              <div class="flex items-center gap-4">
                <div id="inspectorIconBox" class="w-14 h-14 rounded-2xl bg-white dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center text-3xl">
                  <i class="devicon-laravel-original colored"></i>
                </div>
                <div>
                  <h3 id="inspectorSkillName" class="font-display text-2xl font-extrabold text-slate-900 dark:text-white">Laravel 11</h3>
                  <span id="inspectorDomain" class="text-xs font-mono text-slate-500 dark:text-slate-400">Backend &amp; CMS</span>
                </div>
              </div>

              <!-- Stack Specialization & Production Validation -->
              <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200/70 dark:border-slate-800 flex items-center justify-between gap-3">
                <div class="space-y-0.5 min-w-0">
                  <div class="text-[10px] font-mono uppercase tracking-wider text-slate-400" data-i18n="inspector_mastery">Engineering Scope</div>
                  <div id="inspectorScopeVal" class="text-xs font-mono font-bold text-slate-800 dark:text-slate-100 truncate">Web Architecture</div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-200/80 dark:border-emerald-500/30 font-mono text-[10px] font-bold text-emerald-700 dark:text-emerald-300 flex-shrink-0">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                  <span data-i18n="inspector_status_val">Production Ready</span>
                </span>
              </div>

              <!-- Production Project Proof -->
              <div class="space-y-2">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-slate-400" data-i18n="inspector_proof_label">Real-World Production Proof</div>
                <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 space-y-1.5">
                  <div id="inspectorProjectTitle" class="font-mono text-xs font-bold text-slate-900 dark:text-white">Personal Portfolio &amp; CMS</div>
                  <p id="inspectorProjectDesc" class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    Architected dynamic multilingual CMS, Blade SSR templates, SQLite/MySQL migrations &amp; automated XML sitemaps deployed live on hothanhthien.io.vn.
                  </p>
                </div>
              </div>
            </div>

            <div class="pt-3 border-t border-slate-200/70 dark:border-slate-800 flex items-center justify-between font-mono text-[11px] text-slate-400 relative z-10">
              <span data-i18n="inspector_footer_hint">Hover / Tap wheel nodes to inspect</span>
              <span class="text-sky-500 font-bold">ACTIVE</span>
            </div>
          </div>

        </div>

      </div>
    </section>

    <!-- =========================================================================
         SECTION 3: ASYMMETRIC BENTO GRID (FEATURED PROJECTS & HONORS)
    ========================================================================= -->
    <section id="projects" class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div class="space-y-2">
          <div class="font-mono text-xs font-bold tracking-widest uppercase text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
            <span class="w-5 h-[1px] bg-indigo-500"></span>
            <span data-i18n="bento_kicker">02 // ASYMMETRIC CASE STUDIES &amp; HONORS</span>
          </div>
          <h2 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white" data-i18n="bento_title">
            Production Systems &amp; Recognition
          </h2>
        </div>

        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 text-sm font-mono font-semibold text-sky-600 dark:text-sky-400 hover:underline">
          <span data-i18n="bento_view_all">View all projects</span>
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

      @php
          $isVi = app()->getLocale() === 'vi';
          $tourRist = $featuredProjects->first(fn ($p) => stripos($p->title, 'tourrist') !== false);
          $laundry = $featuredProjects->first(fn ($p) => stripos($p->title, 'laundry') !== false);
          $awardCert = $certificates->firstWhere('category', 'award');
          $extraProjects = $featuredProjects
              ->reject(fn ($p) => ($tourRist && $p->id === $tourRist->id) || ($laundry && $p->id === $laundry->id))
              ->take(3);
      @endphp

      <!-- Bento Grid 3 Columns -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- THẺ 1 (Chiếm 2 cột - lg:col-span-2): TourRist RAG AI Concierge -->
        <article class="lg:col-span-2 min-w-0 spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-7 flex flex-col justify-between gap-6">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center relative z-10">
            <div class="md:col-span-6 min-w-0 space-y-4">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-sky-50 dark:bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-200/80 dark:border-sky-500/30">
                  {{ $tourRist && $tourRist->role ? strtoupper($tourRist->role) . ' // APPLIED AI' : 'FLAGSHIP // APPLIED AI' }}
                </span>
                <span class="font-mono text-[11px] text-slate-400">{{ $tourRist && $tourRist->period ? $tourRist->period : '01/2026 — 06/2026' }}</span>
              </div>

              @if($tourRist)
                <h3 class="font-display text-2xl font-extrabold text-slate-900 dark:text-white leading-snug">
                  {{ $tourRist->localized_title }}
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                  {{ \Illuminate\Support\Str::limit($tourRist->summary ?: $tourRist->clean_description, 220) }}
                </p>
                <div class="flex flex-wrap gap-1.5">
                  @forelse($tourRist->technologies->take(5) as $tech)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                      {!! \App\Support\TechIcon::render($tech->name, 'w-3 h-3') !!}
                      <span>{{ $tech->name }}</span>
                    </span>
                  @empty
                    <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Python</span>
                    <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">FastAPI</span>
                    <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">RAG / LLM</span>
                  @endforelse
                </div>
                <div class="flex items-center gap-3 pt-1 flex-wrap">
                  <a href="{{ route('projects.show', $tourRist->id) }}" class="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-sky-600 dark:text-sky-400 hover:underline">
                    <span data-i18n="btn_case_study">{{ $isVi ? 'Xem Case Study' : 'Read Case Study' }}</span>
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                  </a>
                  @if($tourRist->github_url)
                    <a href="{{ $tourRist->github_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-mono text-slate-500 hover:text-slate-900 dark:hover:text-white">
                      <i data-lucide="github" class="w-3.5 h-3.5"></i>
                      <span>GitHub</span>
                    </a>
                  @endif
                  @if($tourRist->demo_url)
                    <a href="{{ $tourRist->demo_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-mono text-emerald-600 dark:text-emerald-400 hover:underline">
                      <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                      <span>Live</span>
                    </a>
                  @endif
                </div>
              @else
                <h3 class="font-display text-2xl font-extrabold text-slate-900 dark:text-white leading-snug" data-i18n="proj1_title">
                  TourRist — Smart Travel Platform &amp; RAG AI Concierge
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-i18n="proj1_desc">
                  Engineered a document-based RAG chatbot pairing semantic ChromaDB vector search with LLM grounding to generate zero-hallucination travel itineraries from curated local knowledge bases.
                </p>
                <div class="flex flex-wrap gap-1.5">
                  <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Python</span>
                  <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">FastAPI</span>
                  <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">RAG / LLM</span>
                  <span class="px-2.5 py-1 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Vector Search</span>
                </div>
                <div class="flex items-center gap-3 pt-1">
                  <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-sky-600 dark:text-sky-400 hover:underline">
                    <span data-i18n="btn_case_study">{{ $isVi ? 'Xem Case Study' : 'Read Case Study' }}</span>
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5"></i>
                  </a>
                  <a href="https://github.com/hoThanhThien" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-mono text-slate-500 hover:text-slate-900 dark:hover:text-white">
                    <i data-lucide="github" class="w-3.5 h-3.5"></i>
                    <span>GitHub</span>
                  </a>
                </div>
              @endif
            </div>

            <!-- Interactive RAG Architecture Preview & Live Copilot Trigger -->
            <div class="md:col-span-6 min-w-0 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 p-4 space-y-3 font-mono text-xs">
              <div class="flex items-center justify-between border-b border-slate-200/70 dark:border-slate-800 pb-2 gap-2">
                <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1.5">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                  Live RAG Architecture
                </span>
                <div class="flex items-center gap-1.5">
                  <span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold">Vector + BM25</span>
                  <span class="px-2 py-0.5 rounded bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300 text-[10px] font-bold">LLM Grounded</span>
                </div>
              </div>

              <div class="p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/70 dark:border-slate-800 text-slate-700 dark:text-slate-200">
                <span class="text-sky-600 dark:text-sky-400 font-bold">Pipeline:</span>
                <span data-i18n="rag_sim_q">DB Corpus Chunking &rarr; Cosine Vector + BM25 Top-K &rarr; Real LLM Citation</span>
              </div>

              <div class="p-3 rounded-xl bg-sky-50/70 dark:bg-sky-950/30 border border-sky-200/70 dark:border-sky-800/50 text-slate-700 dark:text-slate-300 space-y-2">
                <div class="text-[10px] text-sky-600 dark:text-sky-400 font-bold flex items-center gap-1">
                  <i data-lucide="database" class="w-3 h-3"></i>
                  <span data-i18n="rag_sim_trace">Connected to Live Portfolio Corpus &amp; AI Provider</span>
                </div>
                <p class="font-sans text-xs leading-relaxed" data-i18n="rag_sim_a">
                  Click below to run a real Retrieval-Augmented Generation query through the backend LLM endpoint with source citations and live latency metrics.
                </p>
                <button type="button" onclick="triggerSampleRagQuery()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white text-[11px] font-semibold transition-colors shadow-sm">
                  <i data-lucide="sparkles" class="w-3 h-3"></i>
                  <span data-i18n="rag_try_live">{{ $isVi ? 'Chạy thử RAG AI thật trên TourRist' : 'Test Live RAG Query on TourRist' }}</span>
                </button>
              </div>
            </div>
          </div>
        </article>

        <!-- THẺ 2 (Chiếm 1 cột): Giải Nhì Thiết kế Logo Khoa CNTT UTH -->
        <article class="spotlight-card hover:-translate-y-1 bg-gradient-to-br from-white via-amber-50/30 to-sky-50/40 dark:from-slate-900 dark:via-slate-900 dark:to-indigo-950/30 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-7 flex flex-col justify-between">
          <div class="space-y-3 relative z-10">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-amber-100/80 dark:bg-amber-500/15 text-amber-800 dark:text-amber-300 border border-amber-200/80 dark:border-amber-500/30">
              <i data-lucide="trophy" class="w-3.5 h-3.5"></i>
              <span data-i18n="award_badge">DESIGN RECOGNITION</span>
            </span>

            <!-- Floating Vector Emblem IT•UTH -->
            <div class="py-5 flex items-center justify-center">
              <div class="animate-float relative w-28 h-28 rounded-3xl bg-gradient-to-br from-sky-500 via-indigo-600 to-slate-900 p-0.5 shadow-xl shadow-indigo-500/20 flex items-center justify-center">
                <div class="w-full h-full rounded-[22px] bg-white dark:bg-slate-950 flex flex-col items-center justify-center">
                  <span class="font-display text-2xl font-extrabold tracking-tight bg-gradient-to-r from-sky-600 to-indigo-600 bg-clip-text text-transparent">
                    IT&bull;UTH
                  </span>
                  <span class="font-mono text-[9px] tracking-widest text-slate-400 uppercase mt-0.5">IDENTITY</span>
                </div>
              </div>
            </div>

            <h3 class="font-display text-xl font-extrabold text-slate-900 dark:text-white leading-snug" data-i18n="award_title">
              2nd Prize — IT Faculty Logo &amp; Visual Identity (UTH)
            </h3>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-i18n="award_desc">
              Awarded by University of Transport HCMC, proving strong visual craft, typography precision, and clean UI/UX thinking alongside backend engineering.
            </p>
          </div>

          <a href="{{ route('certificates', $awardCert ? ['category' => 'award'] : []) }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-mono font-bold text-indigo-600 dark:text-indigo-400 hover:underline relative z-10">
            <span data-i18n="award_verify">{{ $isVi ? 'Xác thực Giấy khen & Bằng cấp' : 'Verify Official Certificate' }}</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
          </a>
        </article>

        <!-- THẺ 3 (Chiếm 1 cột): Laundry & Multi-Service Platform (Spring Boot) -->
        <article class="spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-7 flex flex-col justify-between space-y-5">
          <div class="space-y-3 relative z-10">
            <div class="flex items-center justify-between gap-2">
              <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-emerald-50 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-500/30">
                ENTERPRISE JAVA
              </span>
              <span class="font-mono text-[11px] text-slate-400">{{ $laundry && $laundry->period ? $laundry->period : '01/2025 — 05/2025' }}</span>
            </div>

            @if($laundry)
              <h3 class="font-display text-xl font-extrabold text-slate-900 dark:text-white leading-snug">
                {{ $laundry->localized_title }}
              </h3>
              <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                {{ \Illuminate\Support\Str::limit($laundry->summary ?: $laundry->clean_description, 185) }}
              </p>
            @else
              <h3 class="font-display text-xl font-extrabold text-slate-900 dark:text-white leading-snug" data-i18n="proj3_title">
                Laundry &amp; Multi-Service Platform
              </h3>
              <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed" data-i18n="proj3_desc">
                Order lifecycle state machine (Received -> Washing -> Ready -> Delivered) with Spring Boot REST APIs, JWT/OTP Auth &amp; Role-Based Access Control.
              </p>
            @endif

            <!-- Microservices Architecture Flow Diagram -->
            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200/80 dark:border-slate-800 font-mono text-[10px] space-y-2">
              <div class="text-slate-400 font-bold">MICROSERVICES FLOW</div>
              <div class="flex flex-wrap items-center gap-1.5 text-slate-700 dark:text-slate-200">
                @if($laundry && $laundry->technologies->isNotEmpty())
                  @foreach($laundry->technologies->take(4) as $tIdx => $tech)
                    @if($tIdx > 0)<span class="text-sky-500">&rarr;</span>@endif
                    <span class="px-2 py-1 rounded {{ $tIdx === 1 ? 'bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-400/40 font-bold' : ($tIdx === 2 ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-400/40 font-bold' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700') }}">{{ $tech->name }}</span>
                  @endforeach
                @else
                  <span class="px-2 py-1 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">NextJS</span>
                  <span class="text-sky-500">&rarr;</span>
                  <span class="px-2 py-1 rounded bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-400/40 font-bold">JWT Guard</span>
                  <span class="text-sky-500">&rarr;</span>
                  <span class="px-2 py-1 rounded bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-400/40 font-bold">Spring Boot</span>
                  <span class="text-sky-500">&rarr;</span>
                  <span class="px-2 py-1 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">MySQL</span>
                @endif
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800 relative z-10">
            <a href="{{ $laundry ? route('projects.show', $laundry->id) : route('projects.index') }}" class="inline-flex items-center gap-1 text-xs font-mono font-bold text-sky-600 dark:text-sky-400 hover:underline">
              <span data-i18n="btn_case_study">{{ $isVi ? 'Xem Case Study' : 'Read Case Study' }}</span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
            <a href="{{ $laundry && $laundry->github_url ? $laundry->github_url : 'https://github.com/hoThanhThien/LT_JAVA_010412213603' }}" target="_blank" rel="noopener" class="text-xs font-mono text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center gap-1">
              <i data-lucide="github" class="w-3.5 h-3.5"></i>
              <span>Code</span>
            </a>
          </div>
        </article>

        <!-- THẺ 4 (Chiếm 2 cột - lg:col-span-2): Kinh nghiệm Thực tế từ DB ($experiences) -->
        <article class="lg:col-span-2 min-w-0 spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-7 flex flex-col justify-between space-y-6">
          @if($experiences->isNotEmpty())
            <div class="space-y-3 relative z-10">
              <div class="flex items-center justify-between flex-wrap gap-2">
                <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-indigo-50 dark:bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-500/30" data-i18n="exp_badge">
                  {{ $isVi ? 'KINH NGHIỆM THỰC TẾ' : 'INDUSTRY EXPERIENCE' }}
                </span>
                <span class="font-mono text-[11px] text-slate-400">{{ $experiences->first()->formatted_date }}</span>
              </div>

              <h3 class="font-display text-2xl font-extrabold text-slate-900 dark:text-white leading-snug">
                {{ $experiences->take(2)->map(fn ($e) => trim($e->localized_position . ' @ ' . $e->localized_company))->implode(' & ') }}
              </h3>
            </div>

            <div class="grid grid-cols-1 {{ $experiences->count() >= 3 ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }} gap-4 relative z-10">
              @foreach($experiences->take(3) as $expIdx => $exp)
                @php
                    $expAccent = ['text-indigo-600 dark:text-indigo-400', 'text-sky-600 dark:text-sky-400', 'text-emerald-600 dark:text-emerald-400'][$expIdx % 3];
                @endphp
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/70 dark:border-slate-800 flex flex-col justify-between gap-2.5">
                  <div class="space-y-1.5">
                    <div class="flex items-center justify-between gap-2">
                      <span class="font-mono text-[10px] font-semibold text-slate-400">{{ $exp->formatted_date }}</span>
                      @if($exp->current)
                        <span class="px-1.5 py-0.5 rounded bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 font-mono text-[9px] font-bold">ACTIVE</span>
                      @endif
                    </div>
                    <div class="font-display text-base font-extrabold {{ $expAccent }} leading-snug">{{ $exp->localized_company }}</div>
                    <div class="text-xs font-semibold text-slate-800 dark:text-slate-200">{{ $exp->localized_position }}</div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-3">
                      {{ \Illuminate\Support\Str::limit(strip_tags((string) $exp->localized_description), 125) }}
                    </p>
                  </div>
                  @if(!empty($exp->tech_tags))
                    <div class="flex flex-wrap gap-1 pt-1">
                      @foreach(array_slice($exp->tech_tags, 0, 3) as $tag)
                        <span class="px-2 py-0.5 rounded text-[10px] font-mono bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700 text-slate-600 dark:text-slate-300">{{ $tag }}</span>
                      @endforeach
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          @else
            <div class="space-y-3 relative z-10">
              <div class="flex items-center justify-between flex-wrap gap-2">
                <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-indigo-50 dark:bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border border-indigo-200/80 dark:border-indigo-500/30" data-i18n="exp_badge">
                  INDUSTRY EXPERIENCE
                </span>
                <span class="font-mono text-[11px] text-slate-400" data-i18n="exp_period">03/2026 — PRESENT</span>
              </div>

              <h3 class="font-display text-2xl font-extrabold text-slate-900 dark:text-white" data-i18n="exp_bento_title">
                Odoo ERP Engineering @ Enmasys &amp; Full-Stack Web @ AIPower
              </h3>

              <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed max-w-3xl" data-i18n="exp_bento_desc">
                Configured enterprise Odoo modules (Retail, Attendance, Accounting, B2B Sales) at Enmasys; engineered corporate &amp; education platforms using PHP, WordPress &amp; PostgreSQL at AIPower while maintaining hothanhthien.io.vn CMS in production.
              </p>
            </div>

            <!-- 3 Hộp Chỉ Số Kết Quả Thực Tế -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 relative z-10">
              <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/70 dark:border-slate-800">
                <div class="font-display text-xl font-extrabold text-indigo-600 dark:text-indigo-400" data-i18n="exp_stat1_val">4+ Modules</div>
                <div class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-1" data-i18n="exp_stat1_lbl">Odoo Retail, Sales, Accounting &amp; HR configured</div>
              </div>

              <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/70 dark:border-slate-800">
                <div class="font-display text-xl font-extrabold text-sky-600 dark:text-sky-400" data-i18n="exp_stat2_val">PHP &amp; PostgreSQL</div>
                <div class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-1" data-i18n="exp_stat2_lbl">Production corporate &amp; education portals shipped</div>
              </div>

              <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200/70 dark:border-slate-800">
                <div class="font-display text-xl font-extrabold text-emerald-600 dark:text-emerald-400" data-i18n="exp_stat3_val">Laravel 11 SSR</div>
                <div class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-1" data-i18n="exp_stat3_lbl">Live bilingual CMS running at hothanhthien.io.vn</div>
              </div>
            </div>
          @endif
        </article>

      </div>

      @if($extraProjects->isNotEmpty())
        <div class="grid grid-cols-1 {{ $extraProjects->count() === 1 ? '' : ($extraProjects->count() === 2 ? 'md:grid-cols-2' : 'md:grid-cols-2 lg:grid-cols-3') }} gap-6">
          @foreach($extraProjects as $proj)
            <article class="spotlight-card hover:-translate-y-1 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 flex flex-col justify-between gap-4">
              <div class="space-y-3 relative z-10">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                  <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                    {{ strtoupper($proj->role ?: 'FEATURED SYSTEM') }}
                  </span>
                  @if($proj->period)
                    <span class="font-mono text-[11px] text-slate-400">{{ $proj->period }}</span>
                  @endif
                </div>

                <h3 class="font-display text-xl font-extrabold text-slate-900 dark:text-white leading-snug">
                  <a href="{{ route('projects.show', $proj->id) }}" class="hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                    {{ $proj->localized_title }}
                  </a>
                </h3>

                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-3">
                  {{ \Illuminate\Support\Str::limit($proj->summary ?: $proj->clean_description, 165) }}
                </p>

                @if($proj->technologies->isNotEmpty())
                  <div class="flex flex-wrap gap-1.5">
                    @foreach($proj->technologies->take(5) as $tech)
                      <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        {!! \App\Support\TechIcon::render($tech->name, 'w-3 h-3') !!}
                        <span>{{ $tech->name }}</span>
                      </span>
                    @endforeach
                  </div>
                @endif
              </div>

              <div class="flex items-center justify-between pt-2.5 border-t border-slate-100 dark:border-slate-800 relative z-10">
                <a href="{{ route('projects.show', $proj->id) }}" class="inline-flex items-center gap-1 text-xs font-mono font-bold text-sky-600 dark:text-sky-400 hover:underline">
                  <span data-i18n="btn_case_study">{{ $isVi ? 'Xem Case Study' : 'Read Case Study' }}</span>
                  <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
                <div class="flex items-center gap-3">
                  @if($proj->github_url)
                    <a href="{{ $proj->github_url }}" target="_blank" rel="noopener" class="text-xs font-mono text-slate-500 hover:text-slate-900 dark:hover:text-white inline-flex items-center gap-1">
                      <i data-lucide="github" class="w-3.5 h-3.5"></i>
                      <span>Code</span>
                    </a>
                  @endif
                  @if($proj->demo_url)
                    <a href="{{ $proj->demo_url }}" target="_blank" rel="noopener" class="text-xs font-mono text-emerald-600 dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                      <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                      <span>Demo</span>
                    </a>
                  @endif
                </div>
              </div>
            </article>
          @endforeach
        </div>
      @endif
    </section>

    <!-- =========================================================================
         SECTION 4: CREDENTIALS & ENGINEERING BLOG (100% SYNCHRONIZED EN/VI)
    ========================================================================= -->
    <section id="credentials" class="grid grid-cols-1 lg:grid-cols-12 gap-8">

      <!-- Credentials & Brochure PDF (5 cols) -->
      <div class="lg:col-span-5 min-w-0 space-y-5">
        <div class="flex items-end justify-between gap-3">
          <div class="space-y-1.5">
            <div class="font-mono text-xs font-bold tracking-widest uppercase text-emerald-600 dark:text-emerald-400" data-i18n="cred_kicker">03 // OFFICIAL CREDENTIALS</div>
            <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white" data-i18n="cred_title">Degrees &amp; Portfolio PDF</h2>
          </div>
          <a href="{{ route('certificates') }}" class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 hover:underline whitespace-nowrap">
            {{ $isVi ? 'Xem tất cả' : 'View Vault' }} &rarr;
          </a>
        </div>

        <div class="space-y-3">
          @forelse($certificates->take(4) as $cert)
            @php
                $certMeta = match ($cert->category) {
                    'brochure' => ['icon' => 'file-text', 'box' => 'bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400', 'action' => 'download'],
                    'degree' => ['icon' => 'graduation-cap', 'box' => 'bg-indigo-50 dark:bg-indigo-500/15 text-indigo-600 dark:text-indigo-400', 'action' => 'external-link'],
                    'award' => ['icon' => 'award', 'box' => 'bg-amber-50 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400', 'action' => 'external-link'],
                    default => ['icon' => 'badge-check', 'box' => 'bg-emerald-50 dark:bg-emerald-500/15 text-emerald-600 dark:text-emerald-400', 'action' => 'external-link'],
                };
                $hasLocalFile = !empty($cert->file_path) && is_file(public_path('storage/' . ltrim($cert->file_path, '/')));
                $certUrl = ($cert->category === 'brochure' && $hasLocalFile)
                    ? $cert->file_url
                    : route('certificates', ['category' => $cert->category ?: 'all']);
                $isDirectFile = ($cert->category === 'brochure' && $hasLocalFile);
                $certSub = collect([$cert->localized_issuer, $cert->issue_date])->filter()->implode(' • ');
            @endphp
            <a href="{{ $certUrl }}" @if($isDirectFile) target="_blank" rel="noopener" @endif class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
              <div class="flex items-start justify-between gap-3 relative z-10">
                <div class="flex items-start gap-3">
                  <span class="w-10 h-10 rounded-xl {{ $certMeta['box'] }} flex items-center justify-center flex-shrink-0">
                    <i data-lucide="{{ $certMeta['icon'] }}" class="w-5 h-5"></i>
                  </span>
                  <div>
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white leading-snug">{{ $cert->localized_title }}</h3>
                    <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-0.5">{{ $certSub ?: ($cert->localized_description ?: 'Verified Portfolio Credential') }}</p>
                  </div>
                </div>
                <i data-lucide="{{ $certMeta['action'] }}" class="w-4 h-4 {{ $isDirectFile ? 'text-sky-500' : 'text-slate-400' }} flex-shrink-0"></i>
              </div>
            </a>
          @empty
            <a href="{{ asset('assets/docs/HoThanhThien_CV_Brochure.pdf') }}" target="_blank" rel="noopener" class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
              <div class="flex items-start justify-between gap-3 relative z-10">
                <div class="flex items-start gap-3">
                  <span class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                  </span>
                  <div>
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white" data-i18n="cert1_title">Engineer Portfolio &amp; CV Brochure (PDF)</h3>
                    <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="cert1_sub">Ho Thanh Thien &bull; Ready for Instant Download</p>
                  </div>
                </div>
                <i data-lucide="download" class="w-4 h-4 text-sky-500 flex-shrink-0"></i>
              </div>
            </a>

            <a href="{{ route('certificates') }}" class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
              <div class="flex items-start justify-between gap-3 relative z-10">
                <div class="flex items-start gap-3">
                  <span class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                  </span>
                  <div>
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white" data-i18n="cert2_title">B.Eng in Information Technology — Distinction</h3>
                    <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="cert2_sub">University of Transport HCMC (UTH) &bull; GPA 3.6 / 4.0</p>
                  </div>
                </div>
                <i data-lucide="external-link" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
              </div>
            </a>

            <a href="{{ route('certificates') }}" class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
              <div class="flex items-start justify-between gap-3 relative z-10">
                <div class="flex items-start gap-3">
                  <span class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="award" class="w-5 h-5"></i>
                  </span>
                  <div>
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white" data-i18n="cert3_title">2nd Prize — IT Faculty Logo Design Contest</h3>
                    <p class="text-xs font-mono text-slate-500 dark:text-slate-400 mt-0.5" data-i18n="cert3_sub">Faculty of Information Technology &bull; UTH Official Award</p>
                  </div>
                </div>
                <i data-lucide="external-link" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
              </div>
            </a>
          @endforelse
        </div>
      </div>

      <!-- Engineering Blog (7 cols) -->
      <div id="blog" class="lg:col-span-7 min-w-0 space-y-5">
        <div class="flex items-end justify-between">
          <div class="space-y-1.5">
            <div class="font-mono text-xs font-bold tracking-widest uppercase text-sky-600 dark:text-sky-400" data-i18n="blog_kicker">04 // ENGINEERING JOURNAL</div>
            <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white" data-i18n="blog_title">Technical Articles &amp; Notes</h2>
          </div>
          <a href="{{ route('blog.index') }}" class="text-xs font-mono font-bold text-sky-600 dark:text-sky-400 hover:underline" data-i18n="blog_all">All Posts &rarr;</a>
        </div>

        <div class="space-y-3">
          @forelse($recentPosts as $idx => $post)
            @php
                $postAccent = ['text-sky-600 dark:text-sky-400', 'text-emerald-600 dark:text-emerald-400', 'text-indigo-600 dark:text-indigo-400'][$idx % 3];
                $postCategory = !empty($post->category)
                    ? strtoupper($post->category)
                    : ($post->ai_generated ? 'AI STUDIO // ENGINEERING' : 'ENGINEERING JOURNAL');
                $postSummary = $post->localized_excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $post->localized_content), 145);
            @endphp
            <a href="{{ route('blog.show', $post->slug) }}" class="spotlight-card hover:-translate-y-1 block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-sm">
              <div class="flex items-center justify-between gap-4 relative z-10">
                <div class="space-y-1 min-w-0">
                  <div class="flex items-center gap-2 flex-wrap font-mono text-[11px]">
                    <span class="{{ $postAccent }} font-bold">{{ $postCategory }}</span>
                    @if($post->published_at)
                      <span class="text-slate-400">&bull; {{ $post->published_at->format('M d, Y') }}</span>
                    @endif
                  </div>
                  <h3 class="font-display font-bold text-base text-slate-900 dark:text-white leading-snug">{{ $post->localized_title }}</h3>
                  <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-1">{{ $postSummary }}</p>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
              </div>
            </a>
          @empty
            <a href="{{ route('blog.index') }}" class="spotlight-card block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
              <div class="flex items-center justify-between gap-4 relative z-10">
                <div class="space-y-1">
                  <div class="font-mono text-[11px] text-sky-600 dark:text-sky-400 font-bold">ENGINEERING JOURNAL</div>
                  <h3 class="font-display font-bold text-base text-slate-900 dark:text-white">
                    {{ $isVi ? 'Các bài viết kỹ thuật đang được xuất bản' : 'Technical Articles Coming Soon' }}
                  </h3>
                  <p class="text-xs text-slate-600 dark:text-slate-400">
                    {{ $isVi ? 'Xem toàn bộ ghi chú kiến trúc tại trang Blog.' : 'Browse all engineering notes on the blog archive.' }}
                  </p>
                </div>
                <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-400 flex-shrink-0"></i>
              </div>
            </a>
          @endforelse
        </div>
      </div>

    </section>

@endsection

@section('floating_widgets')
  <!-- =========================================================================
       SECTION 6: FLOATING RAG AI COPILOT ("ASK THIEN'S RAG AI")
  ========================================================================= -->
  <div class="fixed bottom-4 right-4 sm:bottom-5 sm:right-5 z-50 flex flex-col items-end gap-3 max-w-[calc(100vw-2rem)]">
    
    <!-- Chat Copilot Drawer Window -->
    <div id="ragCopilotPanel" class="hidden w-[calc(100vw-2rem)] max-w-[385px] max-h-[min(78vh,540px)] flex flex-col bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-700 rounded-3xl shadow-2xl overflow-hidden transition-all">
      <!-- Header -->
      <div class="px-4 sm:px-5 py-3.5 sm:py-4 bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-2.5">
          <span class="w-8 h-8 rounded-xl bg-sky-500/20 border border-sky-400/40 flex items-center justify-center text-sky-300">
            <i data-lucide="bot" class="w-4 h-4"></i>
          </span>
          <div>
            <div class="text-xs font-display font-bold flex items-center gap-1.5">
              <span data-i18n="copilot_title">Thien's RAG AI Copilot</span>
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            </div>
            <div class="text-[10px] font-mono text-sky-300" data-i18n="copilot_sub">Grounded in verified UTH &amp; CV corpus</div>
          </div>
        </div>
        <button type="button" onclick="toggleCopilot()" class="p-1 rounded-lg hover:bg-white/10 text-slate-300">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>

      <!-- Messages Container -->
      <div id="copilotMessages" class="p-4 flex-1 min-h-[160px] max-h-[260px] overflow-y-auto space-y-3 bg-slate-50/70 dark:bg-slate-950/60 text-xs">
        <div class="p-3 rounded-2xl rounded-tl-sm bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-200 leading-relaxed shadow-sm" data-i18n="copilot_welcome">
          Hi recruiter! Ask any question or click a prompt below — I retrieve live Portfolio DB records via Hybrid Vector/BM25 and answer via real LLM with citations.
        </div>
      </div>

      <!-- 4 Quick Prompts for Recruiters -->
      <div id="copilotQuickPrompts" class="px-3.5 py-2.5 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 flex flex-wrap gap-1.5">
        <!-- Populated by JS in active EN/VI language -->
      </div>

      <!-- Input Box -->
      <form onsubmit="handleCopilotSubmit(event)" class="p-3 bg-white dark:bg-slate-900 border-t border-slate-200/80 dark:border-slate-800 flex items-center gap-2">
        <input id="copilotInput" type="text" data-i18n-placeholder="copilot_placeholder" placeholder="Ask about GPA, RAG AI, Enmasys..." class="flex-1 px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500">
        <button id="copilotSendBtn" type="submit" class="p-2 rounded-xl bg-sky-500 text-white hover:bg-sky-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
          <i data-lucide="send" class="w-3.5 h-3.5"></i>
        </button>
      </form>
    </div>

    <!-- Floating Trigger Button -->
    <button type="button" onclick="toggleCopilot()" class="inline-flex items-center gap-2.5 px-4 py-3 rounded-2xl bg-slate-900 dark:bg-sky-500 text-white font-semibold text-xs shadow-xl shadow-slate-900/25 dark:shadow-sky-500/30 hover:scale-105 active:scale-95 transition-all border border-slate-700/80 dark:border-sky-400/50">
      <span class="relative flex h-2.5 w-2.5">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 dark:bg-white opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-sky-400 dark:bg-white"></span>
      </span>
      <i data-lucide="sparkles" class="w-4 h-4 text-sky-400 dark:text-white"></i>
      <span data-i18n="copilot_btn">Ask Thien's RAG AI</span>
    </button>

  </div>
@endsection

@section('scripts')
  <script>
    let currentSkillCategory = 0;
    let currentSkillMode = 'grid'; // 'grid' | 'orbit'
    let activeOrbitSkillIndex = 0;
    let typewriterTimer = null;

    window.PAGE_I18N = {
      en: {
        nav_all_projects: 'Archive',
        hero_badge: 'Available for Full-Stack & AI Engineer Roles • Ho Chi Minh City',
        hero_title_1: 'Building scalable',
        hero_title_highlight: 'Web Systems & RAG AI',
        hero_title_2: 'that solve real problems',
        hero_desc: 'Honors B.Eng in Information Technology graduate at UTH with a 4.0/4.0 latest semester GPA. Hands-on experience architecting Java Spring Boot APIs, Laravel ecosystems, Odoo ERP workflows, and Python RAG/LLM chatbots.',
        chip_aipower: 'Full-Stack Dev @ AIPower',
        chip_enmasys: 'Odoo ERP @ Enmasys',
        chip_award: '2nd Prize UTH IT Logo Design',
        cta_explore: 'Explore Featured Work',
        cta_brochure: 'Download Brochure PDF',
        kpi_gpa: 'Latest Term GPA',
        kpi_degree_val: 'Distinction',
        kpi_degree: 'B.Eng IT @ UTH',
        kpi_award_val: '2nd Prize',
        kpi_award: 'UTH IT Logo Design',
        skills_kicker: '01 // TECHNICAL ARCHITECTURE',
        skills_title: 'Interactive Stack Matrix & Orbit Wheel',
        mode_grid: '3D Spin & Flip Grid',
        mode_orbit: '3D Orbit Wheel',
        btn_spin_again: 'Spin Again',
        skill_hint: '✨ Hover or tap any skill card to 3D-flip & inspect real project usage',
        inspector_title: '3D Orbit Inspector',
        inspector_verified: 'Verified Proof',
        inspector_mastery: 'Engineering Scope',
        inspector_status_val: 'Production Ready',
        inspector_proof_label: 'Real-World Production Proof',
        inspector_footer_hint: 'Hover / Tap wheel nodes to inspect',
        bento_kicker: '02 // ASYMMETRIC CASE STUDIES & HONORS',
        bento_title: 'Production Systems & Recognition',
        bento_view_all: 'View all projects',
        proj1_title: 'TourRist — Smart Travel Platform & RAG AI Concierge',
        proj1_desc: 'Engineered a document-based RAG chatbot pairing semantic ChromaDB vector search with LLM grounding to generate zero-hallucination travel itineraries from curated local knowledge bases.',
        btn_case_study: 'Read Case Study',
        rag_sim_q: 'DB Corpus Chunking -> Cosine Vector + BM25 Top-K -> Real LLM Citation',
        rag_sim_trace: 'Connected to Live Portfolio Corpus & AI Provider',
        rag_sim_a: 'Click below to run a real Retrieval-Augmented Generation query through the backend LLM endpoint with source citations and live latency metrics.',
        rag_try_live: 'Test Live RAG Query on TourRist',
        award_badge: 'DESIGN RECOGNITION',
        award_title: '2nd Prize — IT Faculty Logo & Visual Identity (UTH)',
        award_desc: 'Awarded by University of Transport HCMC, proving strong visual craft, typography precision, and clean UI/UX thinking alongside backend engineering.',
        award_verify: 'Verify Official Certificate',
        proj3_title: 'Laundry & Multi-Service Platform',
        proj3_desc: 'Order lifecycle state machine (Received -> Washing -> Ready -> Delivered) with Spring Boot REST APIs, JWT/OTP Auth & Role-Based Access Control.',
        exp_badge: 'INDUSTRY EXPERIENCE',
        exp_period: '03/2026 — PRESENT',
        exp_bento_title: 'Odoo ERP Engineering @ Enmasys & Full-Stack Web @ AIPower',
        exp_bento_desc: 'Configured enterprise Odoo modules (Retail, Attendance, Accounting, B2B Sales) at Enmasys; engineered corporate & education platforms using PHP, WordPress & PostgreSQL at AIPower while maintaining hothanhthien.io.vn CMS in production.',
        exp_stat1_val: '4+ Modules',
        exp_stat1_lbl: 'Odoo Retail, Sales, Accounting & HR configured',
        exp_stat2_val: 'PHP & PostgreSQL',
        exp_stat2_lbl: 'Production corporate & education portals shipped',
        exp_stat3_val: 'Laravel 11 SSR',
        exp_stat3_lbl: 'Live bilingual CMS running at hothanhthien.io.vn',
        cred_kicker: '03 // OFFICIAL CREDENTIALS',
        cred_title: 'Degrees & Portfolio PDF',
        cert1_title: 'Engineer Portfolio & CV Brochure (PDF)',
        cert1_sub: 'Ho Thanh Thien • Ready for Instant Download',
        cert2_title: 'B.Eng in Information Technology — Distinction',
        cert2_sub: 'University of Transport HCMC (UTH) • GPA 3.6 / 4.0',
        cert3_title: '2nd Prize — IT Faculty Logo Design Contest',
        cert3_sub: 'Faculty of Information Technology • UTH Official Award',
        blog_kicker: '04 // ENGINEERING JOURNAL',
        blog_title: 'Technical Articles & Notes',
        blog_all: 'All Posts →',
        post1_title: 'Building Document-Based RAG Systems with LLMs',
        post1_desc: 'How to design a retrieval-augmented generation pipeline using vector search, chunking, and modern LLMs.',
        post2_title: 'Building Secure RESTful APIs with Spring Boot',
        post2_desc: 'A comprehensive guide to creating scalable, production-ready REST APIs using Java Spring Boot and JWT security.',
        post3_title: 'Mastering React Hooks & State Management',
        post3_desc: 'Learn how to utilize React Hooks effectively for scalable state management and functional components.',
        copilot_title: "Thien's RAG AI Copilot",
        copilot_sub: 'Live Hybrid Vector + LLM Assistant',
        copilot_welcome: "Hi recruiter! Ask any question or click a prompt below — I retrieve live Portfolio DB records via Hybrid Vector/BM25 and answer via real LLM with citations.",
        copilot_placeholder: 'Ask about GPA, RAG AI, Enmasys...',
        copilot_btn: "Ask Thien's RAG AI"
      },
      vi: {
        nav_all_projects: 'Kho Dự án',
        hero_badge: 'Sẵn sàng cho vị trí Kỹ sư Full-Stack & AI • TP. Hồ Chí Minh',
        hero_title_1: 'Xây dựng Hệ thống Web',
        hero_title_highlight: '& RAG AI thực chiến',
        hero_title_2: 'giải quyết bài toán thực tế',
        hero_desc: 'Kỹ sư Công nghệ Thông tin tốt nghiệp Loại Xuất sắc tại ĐH GTVT TP.HCM (UTH) với GPA kỳ gần nhất 4.0/4.0. Kinh nghiệm thực chiến Java Spring Boot, Laravel, Odoo ERP và Chatbot AI RAG/LLM.',
        chip_aipower: 'Full-Stack Dev @ AIPower',
        chip_enmasys: 'Odoo ERP @ Enmasys',
        chip_award: 'Giải Nhì Thiết kế Logo CNTT UTH',
        cta_explore: 'Khám phá Dự án Tiêu biểu',
        cta_brochure: 'Tải Hồ sơ Brochure PDF',
        kpi_gpa: 'GPA Kỳ Gần Nhất',
        kpi_degree_val: 'Xuất sắc',
        kpi_degree: 'Kỹ sư CNTT @ UTH',
        kpi_award_val: 'Giải Nhì',
        kpi_award: 'Thiết kế Logo CNTT UTH',
        skills_kicker: '01 // KIẾN TRÚC KỸ NĂNG CHUYÊN MÔN',
        skills_title: 'Ma trận Kỹ năng 3D & Vòng xoay Quỹ đạo',
        mode_grid: 'Lưới Xoay & Lật 3D',
        mode_orbit: 'Vòng xoay Quỹ đạo 3D',
        btn_spin_again: 'Quay lại',
        skill_hint: '✨ Rê chuột hoặc chạm vào thẻ kỹ năng để lật 3D 180° & xem dự án áp dụng thực tế',
        inspector_title: 'Bảng soi Kỹ năng 3D',
        inspector_verified: 'Đã kiểm chứng',
        inspector_mastery: 'Lĩnh vực Ứng dụng',
        inspector_status_val: 'Đã triển khai',
        inspector_proof_label: 'Minh chứng Dự án Production',
        inspector_footer_hint: 'Rê chuột / Chạm node trên vòng quay để xem',
        bento_kicker: '02 // DỰ ÁN TRỌNG ĐIỂM & VINH DANH',
        bento_title: 'Hệ thống Thực tế & Thành tích Đạt được',
        bento_view_all: 'Xem tất cả dự án',
        proj1_title: 'TourRist — Nền tảng Du lịch Thông minh & Trợ lý AI RAG',
        proj1_desc: 'Phát triển chatbot AI dựa trên tài liệu kết hợp tìm kiếm vector ChromaDB và sinh câu trả lời LLM không bịa đặt (zero-hallucination), cung cấp gợi ý lịch trình chính xác từ kho tri thức nội bộ.',
        btn_case_study: 'Xem Case Study',
        rag_sim_q: 'Tách chunk DB -> Vector Cosine + BM25 Top-K -> LLM Sinh câu trả lời có nguồn',
        rag_sim_trace: 'Kết nối trực tiếp Kho dữ liệu DB & AI Provider thật',
        rag_sim_a: 'Bấm nút dưới để chạy một truy vấn RAG AI thực tế qua API backend & LLM với trích dẫn nguồn và độ trễ đo thật.',
        rag_try_live: 'Chạy thử RAG AI thật trên TourRist',
        award_badge: 'GIẢI THƯỞNG THIẾT KẾ',
        award_title: 'Giải Nhì — Sáng tác Logo & Nhận diện Khoa CNTT (UTH)',
        award_desc: 'Chứng nhận từ ĐH GTVT TP.HCM, khẳng định tư duy thẩm mỹ UI/UX, typography và nghệ thuật thiết kế giao diện chuẩn mực bên cạnh nền tảng lập trình hệ thống.',
        award_verify: 'Xác thực Giấy khen & Bằng cấp',
        proj3_title: 'Hệ thống Quản lý Giặt ủi & Đa dịch vụ',
        proj3_desc: 'Máy trạng thái vòng đời đơn hàng (Tiếp nhận -> Đang giặt -> Sẵn sàng -> Đã giao) xây dựng trên Spring Boot REST API, bảo mật JWT/OTP và phân quyền RBAC.',
        exp_badge: 'KINH NGHIỆM THỰC TẾ',
        exp_period: '03/2026 — HIỆN TẠI',
        exp_bento_title: 'Kỹ sư Odoo ERP @ Enmasys & Full-Stack Web @ AIPower',
        exp_bento_desc: 'Triển khai và tùy chỉnh các phân hệ Odoo ERP (Bán lẻ, Chấm công, Kế toán, Sales B2B) tại Enmasys; phát triển website doanh nghiệp và giáo dục với PHP, WordPress & PostgreSQL tại AIPower.',
        exp_stat1_val: '4+ Phân hệ',
        exp_stat1_lbl: 'Tùy chỉnh Odoo Bán lẻ, Sales, Kế toán & Nhân sự',
        exp_stat2_val: 'PHP & PostgreSQL',
        exp_stat2_lbl: 'Bàn giao website giáo dục & doanh nghiệp tại AIPower',
        exp_stat3_val: 'Laravel 11 SSR',
        exp_stat3_lbl: 'Hệ thống Portfolio CMS song ngữ chạy live tại hothanhthien.io.vn',
        cred_kicker: '03 // BẰNG CẤP & CHỨNG NHẬN CHÍNH QUY',
        cred_title: 'Hồ sơ Năng lực & Brochure PDF',
        cert1_title: 'Brochure & Hồ sơ Năng lực Kỹ sư Phần mềm (PDF)',
        cert1_sub: 'Hồ Thành Thiện • Xem trước & Tải xuống trực tiếp',
        cert2_title: 'Bằng Cử nhân Công nghệ Thông tin — Loại Xuất sắc',
        cert2_sub: 'Trường ĐH Giao thông Vận tải TP.HCM (UTH) • GPA 3.6 / 4.0',
        cert3_title: 'Giải Nhì Sáng tác Logo & Nhận diện Khoa CNTT',
        cert3_sub: 'Khoa Công nghệ Thông tin - UTH • Khen thưởng chính thức',
        blog_kicker: '04 // BÀI VIẾT KỸ THUẬT',
        blog_title: 'Chia sẻ Kiến thức & Ghi chép Hệ thống',
        blog_all: 'Xem tất cả →',
        post1_title: 'Xây dựng Hệ thống RAG AI Dựa trên Tài liệu với LLM',
        post1_desc: 'Hướng dẫn thiết kế pipeline Retrieval-Augmented Generation sử dụng tìm kiếm vector ChromaDB, chia đoạn dữ liệu và LLM hiện đại.',
        post2_title: 'Thiết kế RESTful API Bảo mật với Java Spring Boot & JWT',
        post2_desc: 'Cẩm nang xây dựng REST API chuẩn production, phân quyền RBAC và xác thực Stateless JWT với Spring Boot.',
        post3_title: 'Làm chủ React Hooks & Quản lý Trạng thái Hệ thống',
        post3_desc: 'Áp dụng Custom Hooks, Context và mô hình component hàm tối ưu hiệu năng cho ứng dụng React/Next.js.',
        copilot_title: 'Trợ lý RAG AI của Thiện',
        copilot_sub: 'Truy xuất Hybrid Vector + LLM thật từ DB',
        copilot_welcome: 'Chào nhà tuyển dụng! Hãy hỏi bất kỳ câu nào hoặc bấm gợi ý bên dưới — tôi truy xuất trực tiếp từ cơ sở dữ liệu Portfolio (Hybrid Vector/BM25) và trả lời qua LLM thật kèm trích dẫn nguồn.',
        copilot_placeholder: 'Hỏi về GPA UTH, RAG AI, Enmasys...',
        copilot_btn: 'Hỏi Trợ lý RAG AI'
      }
    };

    // 5. DB-SYNCHRONIZED SKILL CATEGORIES DATASET (HYBRID CMS + EN/VI PROOFS)
    const SKILL_CATEGORIES = @json($skillCategories ?? \App\Support\SkillMatrixBuilder::build(collect()));

    // 6. TERMINAL COMMANDS & TYPEWRITER ENGINE
    const CLI_RESPONSES = {
      whoami: {
        cmd: 'whoami',
        en: `> engineer: Ho Thanh Thien (Hồ Thành Thiện)
> degree:   B.Eng Information Technology @ UTH [Distinction]
> term_gpa: 4.0 / 4.0 (Latest Semester Perfect Score)
> roles:    Full-Stack Dev @ AIPower | Odoo ERP @ Enmasys
> location: Ho Chi Minh City, Vietnam`,
        vi: `> kỹ_sư:    Hồ Thành Thiện
> bằng_cấp: Cử nhân CNTT @ ĐH GTVT TP.HCM (UTH) [Loại Xuất sắc]
> điểm_gpa: 4.0 / 4.0 (Kỳ gần nhất đạt điểm tuyệt đối)
> kinh_nghiệm: Full-Stack @ AIPower | Odoo ERP @ Enmasys
> địa_điểm: TP. Hồ Chí Minh, Việt Nam`
      },
      skills: {
        cmd: 'skills --top',
        en: `[✓] Backend:   Java Spring Boot, PHP Laravel 11, FastAPI, Odoo ERP
[✓] AI / Data: Python RAG Pipelines, ChromaDB, Vector Search, MySQL/PostgreSQL
[✓] Frontend:  ReactJS, Next.js, Tailwind CSS, Blade SSR, ES6+
[✓] Infra:     Docker Compose, Nginx, Git, Linux & cPanel Deployment`,
        vi: `[✓] Backend:   Java Spring Boot, PHP Laravel 11, FastAPI, Odoo ERP
[✓] AI / Data: Pipeline Python RAG, ChromaDB, Vector Search, MySQL/PostgreSQL
[✓] Frontend:  ReactJS, Next.js, Tailwind CSS, Blade SSR, ES6+
[✓] Hạ tầng:   Docker Compose, Nginx, Git, Linux & Triển khai cPanel`
      },
      awards: {
        cmd: 'awards --verify',
        en: `★ GPA Achievement: 4.0 / 4.0 Latest Semester | 3.6 / 4.0 Cumulative
★ Graduation:      Bachelor of IT — Excellent Honors (UTH)
★ Design Honor:    2nd Prize — UTH IT Faculty Logo & Visual Identity`,
        vi: `★ Học tập:    GPA Kỳ gần nhất 4.0 / 4.0 | Tích lũy 3.6 / 4.0
★ Tốt nghiệp: Cử nhân CNTT — Loại Xuất sắc (ĐH GTVT TP.HCM)
★ Thiết kế:   Giải Nhì Cuộc thi Sáng tác Logo Khoa CNTT UTH`
      },
      rag: {
        cmd: 'rag --status',
        en: `PIPELINE: TourRist Document RAG Engine
STATUS:   ● ONLINE (ChromaDB Vector Index Active)
METRICS:  Cosine Top-K Match 98.4% | Avg Retrieval Latency ~180ms`,
        vi: `HỆ THỐNG: Động cơ RAG Tài liệu TourRist
TRẠNG THÁI: ● ĐANG HOẠT ĐỘNG (Chỉ mục Vector ChromaDB Sẵn sàng)
THÔNG SỐ:   Khớp Vector Top-K 98.4% | Độ trễ truy xuất ~180ms`
      }
    };

    let activeCliKey = 'whoami';

    function runTerminalCmd(key) {
      activeCliKey = key;
      const payload = CLI_RESPONSES[key];
      if (!payload) return;

      document.querySelectorAll('.cli-cmd-btn').forEach(btn => {
        const isActive = btn.getAttribute('data-cli-key') === key;
        btn.className = isActive
          ? 'cli-cmd-btn px-2.5 py-1 rounded-lg bg-sky-500/15 text-sky-400 border border-sky-500/30 font-mono text-[11px] font-semibold hover:bg-sky-500 hover:text-white transition-colors'
          : 'cli-cmd-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700 font-mono text-[11px] font-semibold hover:border-sky-400 hover:text-white transition-colors';
      });

      document.getElementById('cliTypedCommand').textContent = payload.cmd;
      const outputEl = document.getElementById('cliOutputBody');
      const text = payload[currentLang];

      if (typewriterTimer) clearInterval(typewriterTimer);
      outputEl.textContent = '';

      let i = 0;
      typewriterTimer = setInterval(() => {
        if (i < text.length) {
          i = Math.min(text.length, i + 2);
          outputEl.textContent = text.slice(0, i);
        } else {
          outputEl.textContent = text;
          const cursor = document.createElement('span');
          cursor.className = 'cli-cursor';
          outputEl.appendChild(cursor);
          clearInterval(typewriterTimer);
        }
      }, 14);
    }

    // 7. SKILLS ENGINE (TABS, 3D SPIN/FLIP GRID, 3D ORBIT WHEEL & INSPECTOR)
    function escapeHtml(value) {
      return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    function renderSkillTabs() {
      const container = document.getElementById('skillCategoryTabs');
      container.innerHTML = '';

      SKILL_CATEGORIES.forEach((cat, idx) => {
        const isActive = idx === currentSkillCategory;
        const btn = document.createElement('button');
        btn.type = 'button';

        btn.className = isActive
          ? 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-sky-500 text-white shadow-lg shadow-sky-500/20 flex items-center gap-2 transition-all'
          : 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center gap-2 transition-all';

        const iconColor = isActive ? 'text-white' : escapeHtml(cat.iconColor);
        const tabLabel = (cat.label && (cat.label[currentLang] || cat.label.en)) || '';
        btn.innerHTML = `<i data-lucide="${escapeHtml(cat.icon)}" class="w-3.5 h-3.5 ${iconColor}"></i><span>${escapeHtml(tabLabel)}</span>`;

        btn.addEventListener('click', () => {
          if (currentSkillCategory !== idx) {
            switchCategoryAnimated(idx);
          }
        });
        container.appendChild(btn);
      });

      if (window.lucide) lucide.createIcons();
    }

    function switchCategoryAnimated(nextIndex) {
      if (currentSkillMode === 'grid') {
        const oldCards = document.querySelectorAll('#skillGridModeWrap .skill-grid-item');
        oldCards.forEach(c => c.classList.add('is-exiting'));
        setTimeout(() => {
          currentSkillCategory = nextIndex;
          activeOrbitSkillIndex = 0;
          renderSkillTabs();
          renderActiveSkillView();
        }, 210);
      } else {
        currentSkillCategory = nextIndex;
        activeOrbitSkillIndex = 0;
        renderSkillTabs();
        renderActiveSkillView();
      }
    }

    function switchSkillMode(mode, shouldRender = true) {
      currentSkillMode = mode;
      const btnGrid = document.getElementById('btnModeGrid');
      const btnOrbit = document.getElementById('btnModeOrbit');
      const gridWrap = document.getElementById('skillGridModeWrap');
      const orbitWrap = document.getElementById('skillOrbitModeWrap');

      if (mode === 'grid') {
        btnGrid.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 bg-sky-500 text-white shadow-sm transition-all';
        btnOrbit.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all';
        gridWrap.classList.remove('hidden');
        orbitWrap.classList.add('hidden');
      } else {
        btnOrbit.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 bg-sky-500 text-white shadow-sm transition-all';
        btnGrid.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all';
        gridWrap.classList.add('hidden');
        orbitWrap.classList.remove('hidden');
      }
      if (shouldRender) {
        renderActiveSkillView();
      }
    }

    function replaySkillsAnimation() {
      const btn = document.getElementById('btnSkillReplay');
      btn.classList.add('replay-spinning');
      renderActiveSkillView();
      setTimeout(() => btn.classList.remove('replay-spinning'), 620);
    }

    function renderActiveSkillView() {
      const cat = SKILL_CATEGORIES[currentSkillCategory];
      const items = cat.items;
      document.getElementById('activeSkillCountMeta').textContent = `${items.length} SKILLS`;

      if (currentSkillMode === 'grid') {
        render3DSpinFlipGrid(items);
      } else {
        render3DOrbitWheel(cat, items);
      }
    }

    const CUSTOM_BRAND_SVGS = @json(\App\Support\TechIcon::jsSvgMap());

    function renderSkillIcon(item, variant = 'grid') {
      const iconKey = item.icon || '';
      const boxSizes = {
        grid: { svg: 'w-6 h-6', odoo: 'w-8 h-5', orbitText: '', lucide: 'w-5 h-5' },
        orbit: { svg: 'w-4 h-4', odoo: 'w-6 h-3.5', orbitText: 'text-base sm:text-lg', lucide: 'w-3.5 h-3.5' },
        inspector: { svg: 'w-8 h-8', odoo: 'w-11 h-6', orbitText: '', lucide: 'w-7 h-7' }
      }[variant] || { svg: 'w-6 h-6', odoo: 'w-8 h-5', orbitText: '', lucide: 'w-5 h-5' };

      if (CUSTOM_BRAND_SVGS[iconKey]) {
        return CUSTOM_BRAND_SVGS[iconKey]
          .replace(/__SZ__/g, boxSizes.svg)
          .replace(/__ODOO__/g, boxSizes.odoo);
      }

      if (iconKey.startsWith('lucide:')) {
        const lucideName = iconKey.slice(7);
        return `<i data-lucide="${escapeHtml(lucideName)}" class="${boxSizes.lucide} text-sky-600 dark:text-sky-400"></i>`;
      }

      const cls = boxSizes.orbitText ? `${iconKey} ${boxSizes.orbitText}` : iconKey;
      return `<i class="${escapeHtml(cls)}"></i>`;
    }

    function render3DSpinFlipGrid(items) {
      const wrap = document.getElementById('skillGridModeWrap');
      wrap.innerHTML = '';

      items.forEach((item, index) => {
        const card = document.createElement('div');
        card.className = 'skill-grid-item';
        card.style.setProperty('--index', index);
        card.style.animationDelay = `${index * 55}ms`;

        const domainTxt = (item.domain && (item.domain[currentLang] || item.domain.en)) || '';
        const projectTxt = (item.project && (item.project[currentLang] || item.project.en)) || '';
        const descTxt = (item.desc && (item.desc[currentLang] || item.desc.en)) || '';

        card.innerHTML = `
          <div class="grid-flip-inner">
            <!-- Front Face -->
            <div class="grid-flip-front spotlight-card bg-slate-50/70 dark:bg-slate-950/70 border border-slate-200/80 dark:border-slate-800 p-4 flex flex-col justify-between">
              <div class="flex items-start justify-between relative z-10">
                <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-center text-2xl shadow-sm spin-icon-target">
                  ${renderSkillIcon(item, 'grid')}
                </div>
                <span class="inline-flex items-center gap-1 font-mono text-[10px] font-semibold text-slate-400 dark:text-slate-500 bg-slate-100/80 dark:bg-slate-800/80 border border-slate-200/70 dark:border-slate-700/70 px-2 py-0.5 rounded-md">
                  <svg class="w-2.5 h-2.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 0114.93-4M20 4v4h-4m4 4a8 8 0 01-14.93 4M4 20v-4h4"/></svg>
                  <span>3D</span>
                </span>
              </div>
              <div class="relative z-10 text-left">
                <div class="font-display font-bold text-sm text-slate-900 dark:text-white">${escapeHtml(item.name)}</div>
                <div class="font-mono text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5">${escapeHtml(domainTxt)}</div>
              </div>
            </div>

            <!-- Back Face (180deg Flipped Project Proof) -->
            <div class="grid-flip-back bg-gradient-to-br from-slate-900 via-slate-800 to-sky-950 text-white p-4 flex flex-col justify-between border border-sky-400/40 shadow-lg">
              <div class="space-y-1.5 text-left">
                <div class="font-mono text-[10px] uppercase tracking-wider text-sky-300 font-bold leading-snug line-clamp-1">${escapeHtml(projectTxt)}</div>
                <p class="text-[11px] text-slate-200 leading-relaxed line-clamp-3">${escapeHtml(descTxt)}</p>
              </div>
              <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-700/70 font-mono text-[10px]">
                <span class="inline-flex items-center gap-1.5 text-emerald-300 font-semibold whitespace-nowrap">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                  <span>${currentLang === 'vi' ? 'Đã áp dụng' : 'Production Proof'}</span>
                </span>
                <span class="text-sky-300/90 font-bold truncate">${escapeHtml(item.name)}</span>
              </div>
            </div>
          </div>
        `;

        card.addEventListener('click', () => card.classList.toggle('is-flipped'));
        wrap.appendChild(card);
      });

      if (window.lucide) lucide.createIcons();
    }

    let isOrbitManualPaused = false;

    function updateOrbitSpinLabel() {
      const label = document.getElementById('orbitSpinToggleLabel');
      const pauseIcon = document.getElementById('orbitSpinPauseIcon');
      const playIcon = document.getElementById('orbitSpinPlayIcon');
      if (pauseIcon) pauseIcon.classList.toggle('hidden', isOrbitManualPaused);
      if (playIcon) playIcon.classList.toggle('hidden', !isOrbitManualPaused);
      if (label) {
        label.textContent = isOrbitManualPaused
          ? (currentLang === 'vi' ? 'Tạm dừng' : 'Paused')
          : (currentLang === 'vi' ? 'Đang xoay' : 'Auto Orbit');
      }
    }

    function toggleOrbitSpin() {
      const stageBox = document.getElementById('orbitStageBox');
      isOrbitManualPaused = !isOrbitManualPaused;
      if (stageBox) {
        stageBox.classList.toggle('is-manual-paused', isOrbitManualPaused);
      }
      updateOrbitSpinLabel();
    }

    function syncOrbitGeometry(nodeCount) {
      const stageBox = document.getElementById('orbitStageBox');
      const viewport = document.getElementById('orbitCanvasViewport');
      if (!stageBox || !viewport) return 184;

      const availableWidth = viewport.clientWidth || stageBox.clientWidth || window.innerWidth;
      const scale = Math.max(0.52, Math.min(1, availableWidth / 486));

      viewport.style.setProperty('--orbit-scale', scale.toFixed(4));

      const readout = document.getElementById('orbitRadiusReadout');
      if (readout && typeof nodeCount === 'number') {
        const effR = Math.round(184 * scale);
        readout.textContent = `R=${effR}px • N=${nodeCount}`;
      }
      return 184;
    }

    function render3DOrbitWheel(cat, items) {
      const stageBox = document.getElementById('orbitStageBox');
      const stage = document.getElementById('orbitNodesStage');
      const N = items.length;

      document.getElementById('orbitHubCategory').textContent = (cat.hubName && (cat.hubName[currentLang] || cat.hubName.en)) || '';
      document.getElementById('orbitHubCount').textContent = currentLang === 'vi' ? `${N} Kỹ năng` : `${N} Skills`;

      if (stageBox) {
        stageBox.classList.remove('is-hover-paused');
      }

      stage.innerHTML = '';
      syncOrbitGeometry(N);
      requestAnimationFrame(() => syncOrbitGeometry(N));
      // Synchronize outer ring rotation and child counter-rotation clocks at t=0
      stage.classList.remove('orbit-spinning-ring');
      void stage.offsetWidth;
      stage.classList.add('orbit-spinning-ring');

      items.forEach((item, index) => {
        const angle = Math.round((360 / items.length) * index);
        const node = document.createElement('div');
        node.className = `orbit-node ${index === activeOrbitSkillIndex ? 'is-active' : ''}`;
        node.style.setProperty('--angle', `${angle}deg`);
        node.style.animationDelay = `${index * 65}ms`;

        node.innerHTML = `
          <div class="orbit-node-upright">
            <div class="orbit-node-pill px-2 py-1 rounded-xl bg-white dark:bg-slate-800/95 border border-slate-200/90 dark:border-slate-600/80 shadow-md flex items-center gap-1">
              ${renderSkillIcon(item, 'orbit')}
              <span class="text-[11px] font-bold tracking-tight text-slate-800 dark:text-slate-100 whitespace-nowrap">${escapeHtml(item.name)}</span>
            </div>
          </div>
        `;

        const handleInspect = () => {
          activeOrbitSkillIndex = index;
          stage.querySelectorAll('.orbit-node').forEach((n, idx) => n.classList.toggle('is-active', idx === index));
          updateOrbitInspector(item);
        };

        node.addEventListener('mouseenter', () => {
          if (stageBox) stageBox.classList.add('is-hover-paused');
          handleInspect();
        });
        node.addEventListener('mouseleave', () => {
          if (stageBox) stageBox.classList.remove('is-hover-paused');
        });
        node.addEventListener('click', handleInspect);
        stage.appendChild(node);
      });

      if (items[activeOrbitSkillIndex]) {
        updateOrbitInspector(items[activeOrbitSkillIndex]);
      }
      updateOrbitSpinLabel();
      if (window.lucide) lucide.createIcons();
    }

    function updateOrbitInspector(item) {
      document.getElementById('inspectorIconBox').innerHTML = renderSkillIcon(item, 'inspector');
      document.getElementById('inspectorSkillName').textContent = item.name;
      const domainTxt = (item.domain && (item.domain[currentLang] || item.domain.en)) || '';
      const projectTxt = (item.project && (item.project[currentLang] || item.project.en)) || '';
      const descTxt = (item.desc && (item.desc[currentLang] || item.desc.en)) || '';
      document.getElementById('inspectorDomain').textContent = domainTxt;
      const scopeEl = document.getElementById('inspectorScopeVal');
      if (scopeEl) scopeEl.textContent = domainTxt;
      document.getElementById('inspectorProjectTitle').textContent = projectTxt;
      document.getElementById('inspectorProjectDesc').textContent = descTxt;
      if (window.lucide) lucide.createIcons();
    }

    // 8. PAGE LANGUAGE CHANGE HOOK (CALLED BY LAYOUT ENGINE)
    window.onPearlLangChange = function () {
      updateOrbitSpinLabel();
      renderSkillTabs();
      renderActiveSkillView();
      renderCopilotPrompts();
      runTerminalCmd(activeCliKey);
    };

    // 9. REAL LIVE RAG AI COPILOT ENGINE (CONNECTED TO /api/v1/copilot/ask + ACTIVE LLM)
    const COPILOT_QUICK_PROMPTS = [
      {
        label: { en: 'UTH GPA & Honors?', vi: 'GPA & Bằng cấp UTH?' },
        query: {
          en: 'Summarize Ho Thanh Thien’s UTH GPA, academic honors, and logo award.',
          vi: 'Tóm tắt điểm GPA UTH, bằng cấp và giải thưởng của Hồ Thành Thiện.'
        }
      },
      {
        label: { en: 'TourRist RAG AI?', vi: 'Kiến trúc RAG TourRist?' },
        query: {
          en: 'How did Thien architect the TourRist RAG AI chatbot and tech stack?',
          vi: 'Thiện đã thiết kế kiến trúc chatbot RAG AI TourRist và dùng những công nghệ gì?'
        }
      },
      {
        label: { en: 'Enmasys & AIPower?', vi: 'Kinh nghiệm Enmasys & AIPower?' },
        query: {
          en: 'What did Thien build during his Enmasys Odoo ERP internship and AIPower role?',
          vi: 'Thiện đã làm gì trong kỳ thực tập Odoo ERP tại Enmasys và công việc tại AIPower?'
        }
      },
      {
        label: { en: 'Latest Blog & Contact?', vi: 'Bài blog mới & Liên hệ?' },
        query: {
          en: 'What technical blog posts has Thien published recently and how can a recruiter contact him?',
          vi: 'Thiện có những bài viết kỹ thuật nào gần đây và nhà tuyển dụng liên hệ bằng cách nào?'
        }
      }
    ];

    let copilotHistory = [];
    let isCopilotBusy = false;

    function toggleCopilot(forceOpen = null) {
      const panel = document.getElementById('ragCopilotPanel');
      if (forceOpen === true) {
        panel.classList.remove('hidden');
      } else if (forceOpen === false) {
        panel.classList.add('hidden');
      } else {
        panel.classList.toggle('hidden');
      }
      if (window.lucide) lucide.createIcons();
    }

    function triggerSampleRagQuery() {
      const q = currentLang === 'vi'
        ? 'Giải thích kiến trúc RAG AI và công nghệ trong dự án TourRist dựa trên hồ sơ?'
        : 'Explain the TourRist RAG AI architecture and technologies used based on portfolio records.';
      toggleCopilot(true);
      askCopilotQuestion(q);
    }

    function renderCopilotPrompts() {
      const wrap = document.getElementById('copilotQuickPrompts');
      wrap.innerHTML = '';
      COPILOT_QUICK_PROMPTS.forEach((item) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'px-2.5 py-1 rounded-lg bg-sky-50 dark:bg-sky-500/15 border border-sky-200/80 dark:border-sky-500/30 text-[11px] font-mono font-semibold text-sky-700 dark:text-sky-300 hover:bg-sky-500 hover:text-white transition-colors';
        btn.textContent = item.label[currentLang] || item.label.en;
        btn.addEventListener('click', () => askCopilotQuestion(item.query[currentLang] || item.query.en));
        wrap.appendChild(btn);
      });
    }

    function formatCopilotAnswerHtml(text) {
      let safe = escapeHtml(String(text || '').trim());
      safe = safe.replace(/\*\*(.+?)\*\*/g, '<strong class="font-semibold text-slate-900 dark:text-white">$1</strong>');
      safe = safe.replace(/\[(S\d+)\]/g, '<span class="inline-block px-1 py-0.5 mx-0.5 rounded bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300 font-mono text-[10px] font-bold">[$1]</span>');
      return safe.replace(/\n/g, '<br>');
    }

    function setCopilotBusyState(busy) {
      isCopilotBusy = busy;
      const sendBtn = document.getElementById('copilotSendBtn');
      const input = document.getElementById('copilotInput');
      if (sendBtn) sendBtn.disabled = busy;
      if (input) input.disabled = busy;
    }

    function handleCopilotSubmit(e) {
      e.preventDefault();
      if (isCopilotBusy) return;
      const input = document.getElementById('copilotInput');
      const query = input.value.trim();
      if (query.length < 2) return;
      input.value = '';
      askCopilotQuestion(query);
    }

    async function askCopilotQuestion(query) {
      if (isCopilotBusy) return;
      const box = document.getElementById('copilotMessages');

      // 1. User message bubble
      const uBubble = document.createElement('div');
      uBubble.className = 'ml-auto max-w-[85%] p-2.5 rounded-2xl rounded-tr-sm bg-sky-500 text-white font-medium shadow-sm';
      uBubble.textContent = query;
      box.appendChild(uBubble);

      // 2. Pending AI bubble with telemetry spinner
      const aBubble = document.createElement('div');
      aBubble.className = 'mr-auto max-w-[94%] p-3 rounded-2xl rounded-tl-sm bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-200 leading-relaxed shadow-sm space-y-2';

      const meta = document.createElement('div');
      meta.className = 'font-mono text-[10px] font-bold text-sky-600 dark:text-sky-400 flex items-center gap-1.5';
      meta.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-ping"></span><span>${currentLang === 'vi' ? 'Đang truy xuất DB & gọi LLM...' : 'Retrieving DB corpus & querying LLM...'}</span>`;

      const body = document.createElement('div');
      body.className = 'text-xs leading-relaxed text-slate-400 italic';
      body.textContent = currentLang === 'vi' ? 'Đang tổng hợp ngữ cảnh có trích dẫn...' : 'Generating grounded answer...';

      aBubble.appendChild(meta);
      aBubble.appendChild(body);
      box.appendChild(aBubble);
      box.scrollTop = box.scrollHeight;

      setCopilotBusyState(true);

      try {
        const resp = await fetch('/api/v1/copilot/ask', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            query,
            locale: currentLang,
            history: copilotHistory.slice(-4)
          })
        });

        if (resp.status === 429) {
          meta.className = 'font-mono text-[10px] font-bold text-amber-600 dark:text-amber-400';
          meta.textContent = 'Rate Limited (429)';
          body.className = 'text-xs leading-relaxed text-slate-700 dark:text-slate-200';
          body.textContent = currentLang === 'vi'
            ? 'Bạn gửi hơi nhanh — vui lòng đợi khoảng 1 phút rồi hỏi tiếp nhé.'
            : 'Too many requests in a short window — please wait a minute and try again.';
          return;
        }

        const data = await resp.json();
        if (!resp.ok || !data || !data.success) {
          throw new Error((data && data.message) ? data.message : 'RAG request failed');
        }

        const isLiveLlm = data.mode === 'llm-rag';
        const methodBadge = data.retrieval_method === 'hybrid-vector' ? 'Hybrid Vector' : 'BM25 RAG';
        meta.className = isLiveLlm
          ? 'font-mono text-[10px] font-bold text-emerald-600 dark:text-emerald-400 flex flex-wrap items-center gap-1.5'
          : 'font-mono text-[10px] font-bold text-amber-600 dark:text-amber-400 flex flex-wrap items-center gap-1.5';

        const modelLabel = isLiveLlm ? `LLM: ${data.model || data.provider || 'Active AI'}` : 'Retrieval Fallback';
        const matchPart = typeof data.match_score === 'number' ? ` • Match ${data.match_score}%` : '';
        meta.textContent = `${modelLabel} • ${methodBadge}${matchPart} • ${data.latency_ms || 0}ms`;

        body.className = 'text-xs leading-relaxed text-slate-700 dark:text-slate-200';
        body.innerHTML = formatCopilotAnswerHtml(data.answer);

        if (Array.isArray(data.sources) && data.sources.length > 0) {
          const sourcesWrap = document.createElement('div');
          sourcesWrap.className = 'pt-2 border-t border-slate-100 dark:border-slate-800 flex flex-wrap gap-1.5';
          data.sources.slice(0, 3).forEach((src) => {
            const link = document.createElement('a');
            link.href = src.url || '#';
            link.className = 'inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-sky-50 dark:hover:bg-sky-900/40 text-[10px] font-mono text-slate-600 dark:text-slate-300 hover:text-sky-600 dark:hover:text-sky-300 transition-colors';
            link.textContent = `[${src.ref}] ${src.title}`;
            sourcesWrap.appendChild(link);
          });
          aBubble.appendChild(sourcesWrap);
        }

        copilotHistory.push({ role: 'user', text: query });
        copilotHistory.push({ role: 'assistant', text: String(data.answer || '').slice(0, 450) });
      } catch (err) {
        meta.className = 'font-mono text-[10px] font-bold text-rose-600 dark:text-rose-400';
        meta.textContent = 'Connection Alert';
        body.className = 'text-xs leading-relaxed text-slate-700 dark:text-slate-200';
        body.textContent = currentLang === 'vi'
          ? 'Không kết nối được tới endpoint RAG lúc này. Vui lòng thử lại sau giây lát.'
          : 'Could not reach the RAG endpoint right now. Please try again shortly.';
      } finally {
        setCopilotBusyState(false);
        box.scrollTop = box.scrollHeight;
        if (window.lucide) lucide.createIcons();
      }
    }

    // 10. INITIAL BOOTSTRAP
    document.addEventListener('DOMContentLoaded', () => {
      switchSkillMode('grid', false);

      const orbitBox = document.getElementById('orbitStageBox');
      if (orbitBox && typeof ResizeObserver !== 'undefined') {
        const ro = new ResizeObserver(() => {
          if (currentSkillMode === 'orbit') {
            const items = SKILL_CATEGORIES[currentSkillCategory].items;
            syncOrbitGeometry(items.length);
          }
        });
        ro.observe(orbitBox);
      } else {
        window.addEventListener('resize', () => {
          if (currentSkillMode === 'orbit') {
            const items = SKILL_CATEGORIES[currentSkillCategory].items;
            syncOrbitGeometry(items.length);
          }
        });
      }
    });
  </script>
@endsection
