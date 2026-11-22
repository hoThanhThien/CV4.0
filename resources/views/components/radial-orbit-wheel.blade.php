{{--
  Component: Radial Orbit Skill Wheel (3D Orbit Wheel on Clean Pearl White UI)
  Strict adherence to 3D Trigonometric Radial Orbiting and Pearl White Frosted Glass Aesthetics.
--}}
@php
    $isVi = app()->getLocale() === 'vi';
@endphp

<div id="radialOrbitSection" class="radial-orbit-wrapper w-full my-8">
  <style>
    #radialOrbitSection {
      --radius: 184px;
      --radial-scale: 1;
    }
    #radialOrbitSection .radial-canvas-viewport {
      position: relative;
      width: 100%;
      height: calc(468px * var(--radial-scale, 1));
    }
    #radialOrbitSection .radial-canvas {
      position: absolute;
      left: 50%;
      top: 50%;
      width: 486px;
      height: 468px;
      --radius: 184px;
      transform: translate(-50%, -50%) scale(var(--radial-scale, 1));
      transform-origin: center center;
    }

    /* Ambient blueprint grid pattern */
    #radialOrbitSection .orbit-outer-wrapper {
      background-color: #f8fafc;
      background-image: 
        radial-gradient(rgba(15, 23, 42, 0.045) 1px, transparent 1px),
        linear-gradient(to right, rgba(15, 23, 42, 0.045) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(15, 23, 42, 0.045) 1px, transparent 1px);
      background-size: 38px 38px, 38px 38px, 38px 38px;
    }
    html.dark #radialOrbitSection .orbit-outer-wrapper {
      background-color: rgba(15, 23, 42, 0.72);
      background-image:
        radial-gradient(rgba(148, 163, 184, 0.09) 1px, transparent 1px),
        linear-gradient(to right, rgba(148, 163, 184, 0.06) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(148, 163, 184, 0.06) 1px, transparent 1px);
    }

    /* Vortex Spin-Out Keyframe Formula */
    @keyframes orbitNodeEmerge {
      0% {
        opacity: 0;
        transform: translate(-50%, -50%) rotate(calc(var(--angle) - 160deg)) translateY(0px) scale(0.2);
      }
      70% {
        opacity: 1;
      }
      100% {
        opacity: 1;
        transform: translate(-50%, -50%) rotate(var(--angle)) translateY(calc(-1 * var(--radius))) rotate(calc(-1 * var(--angle))) scale(1);
      }
    }

    @keyframes radialStageRevolve {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    @keyframes radialNodeCounterRevolve {
      from { transform: rotate(0deg); }
      to { transform: rotate(-360deg); }
    }

    #radialOrbitSection .radial-spinning-ring {
      transform-origin: 50% 50%;
      animation: radialStageRevolve 32s linear infinite;
    }

    #radialOrbitSection .orbit-node {
      position: absolute;
      top: 50%;
      left: 50%;
      opacity: 0;
      animation: orbitNodeEmerge 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
      animation-delay: var(--delay);
      user-select: none;
      pointer-events: auto;
      cursor: pointer;
      z-index: 20;
    }
    #radialOrbitSection .orbit-node:hover {
      z-index: 40;
    }

    #radialOrbitSection .radial-node-upright {
      transform-origin: center center;
      perspective: 1000px;
      animation: radialNodeCounterRevolve 32s linear infinite;
    }

    #radialOrbitStage.is-hover-paused .radial-spinning-ring,
    #radialOrbitStage.is-hover-paused .radial-node-upright,
    #radialOrbitStage.is-hover-paused .animate-orbit-ring,
    #radialOrbitStage:has(.orbit-node:hover) .radial-spinning-ring,
    #radialOrbitStage:has(.orbit-node:hover) .radial-node-upright,
    #radialOrbitStage:has(.orbit-node:hover) .animate-orbit-ring {
      animation-play-state: paused;
    }

    /* 3D Flip Card Styling */
    #radialOrbitSection .flip-card-inner {
      position: relative;
      width: 100px;
      height: 42px;
      text-align: center;
      transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
      transform-style: preserve-3d;
      border-radius: 12px;
    }

    #radialOrbitSection .orbit-node:hover .flip-card-inner,
    #radialOrbitSection .orbit-node.is-flipped .flip-card-inner {
      transform: rotateY(180deg) scale(1.08);
      box-shadow: 0 12px 24px -4px rgba(14, 165, 233, 0.28);
    }

    #radialOrbitSection .flip-card-front,
    #radialOrbitSection .flip-card-back {
      position: absolute;
      inset: 0;
      -webkit-backface-visibility: hidden;
      backface-visibility: hidden;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Mặt trước: Nền trắng sạch, viền slate, icon + tên kỹ năng */
    #radialOrbitSection .flip-card-front {
      background: #ffffff;
      border: 1px solid rgba(226, 232, 240, 0.9);
      box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.05);
      padding: 0 6px;
      gap: 5px;
    }
    html.dark #radialOrbitSection .flip-card-front {
      background: #1e293b;
      border-color: rgba(71, 85, 105, 0.9);
      box-shadow: 0 6px 16px -2px rgba(0, 0, 0, 0.45);
    }

    /* Mặt sau: Nền xanh gradient đại dương, hiển thị minh chứng dự án thực tế */
    #radialOrbitSection .flip-card-back {
      background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
      color: #ffffff;
      transform: rotateY(180deg);
      padding: 0 6px;
      flex-direction: column;
      box-shadow: 0 8px 20px -2px rgba(2, 132, 199, 0.35);
      border: 1px solid rgba(125, 211, 252, 0.5);
    }

    /* Vòng tròn quỹ đạo đứt nét xoay chậm liên tục */
    @keyframes orbitRingSpin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    #radialOrbitSection .animate-orbit-ring {
      animation: orbitRingSpin 80s linear infinite;
    }

    /* Nút Spin Again xoay 360 độ khi click */
    #radialOrbitSection .spin-active svg {
      animation: spin360 0.65s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes spin360 {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
  </style>

  <!-- 1. TÔNG MÀU & NỀN (CLEAN PEARL WHITE UI) -->
  <div class="orbit-outer-wrapper p-3 sm:p-6 rounded-[2rem] border border-slate-200/60 dark:border-slate-800 shadow-inner">
    
    <!-- Khung chứa chính (Outer Card) -->
    <div class="max-w-4xl w-full mx-auto bg-white dark:bg-slate-900/95 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-5 sm:p-8 space-y-6">

      <!-- 2. THANH TAB LỌC NHÓM KỸ NĂNG & NÚT SPIN AGAIN (Ở TRÊN) -->
      <div class="space-y-4">
        
        <!-- Hàng 1: 5 nút Tab bo góc rounded-xl -->
        <div id="radialCategoryTabs" class="flex flex-wrap items-center gap-2">
          <!-- Render tự động bằng JavaScript -->
        </div>

        <!-- Hàng 2: Dòng chữ hướng dẫn & Nút Spin Again -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1 border-t border-slate-100 dark:border-slate-800">
          <div class="flex items-center gap-1.5 font-mono text-xs text-sky-700 dark:text-sky-400 font-semibold tracking-wide">
            <span>✨</span>
            <span>{{ $isVi ? 'Rê chuột hoặc chạm vào thẻ kỹ năng để lật 3D & xem minh chứng dự án' : 'Hover or tap any skill card to 3D-flip & inspect real project usage' }}</span>
          </div>

          <button id="radialBtnSpinAgain" type="button" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-sky-700 dark:text-sky-400 font-mono text-xs font-bold hover:bg-sky-50/70 dark:hover:bg-slate-800 hover:border-sky-300 transition-all active:scale-95 shadow-sm self-start sm:self-auto">
            <svg class="w-3.5 h-3.5 text-sky-700 dark:text-sky-400" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span>{{ $isVi ? 'Quay lại' : 'Spin Again' }}</span>
          </button>
        </div>

      </div>

      <!-- 3. KHUNG SÂN KHẤU CHỨA VÒNG XOAY (Inner Stage) -->
      <div id="radialOrbitStage" class="rounded-2xl pt-10 pb-11 px-3 sm:px-4 bg-gradient-to-b from-sky-50/70 via-white to-indigo-50/40 dark:from-slate-950 dark:via-slate-900 dark:to-indigo-950/50 border border-slate-200/70 dark:border-slate-800 flex items-center justify-center relative overflow-hidden select-none">

        <!-- Thông số tọa độ & Technical HUD ở các góc -->
        <div class="absolute top-3 left-3 sm:top-4 sm:left-4 font-mono text-xs text-slate-600 dark:text-slate-400 tracking-wider z-30">
          RADIAL_STAGE // ORBIT_3D
        </div>
        <div class="absolute bottom-3 left-3 sm:bottom-4 sm:left-4 font-mono text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1.5 sm:gap-2 z-30">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
          <span id="radialCoordReadout">R=184px • TOP 10</span>
        </div>
        <div class="absolute top-3 right-3 sm:top-4 sm:right-4 font-mono text-xs text-sky-700 dark:text-sky-400 font-semibold z-30">
          3D PRESERVE
        </div>

        <div id="radialCanvasViewport" class="radial-canvas-viewport">
          <div id="radialCanvas" class="radial-canvas flex items-center justify-center">
            <!-- Vòng tròn quỹ đạo trong -->
            <div id="radialInnerRing" class="w-[228px] h-[228px] rounded-full border border-indigo-500/20 absolute pointer-events-none"></div>

            <!-- Vòng tròn quỹ đạo ngoài nét đứt -->
            <div id="radialOuterRing" class="w-[368px] h-[368px] rounded-full border border-dashed border-sky-500/35 absolute pointer-events-none animate-orbit-ring"></div>

            <!-- VÒNG TRÒN LÕI Ở CHÍNH GIỮA (Central Core Hub) -->
            <div class="relative z-20 w-32 h-32 rounded-full bg-white dark:bg-slate-900 border-2 border-sky-400/60 dark:border-sky-500/50 shadow-xl shadow-sky-500/15 flex flex-col items-center justify-center text-center p-2 transition-transform hover:scale-105 duration-300">
              <div class="absolute -inset-1 rounded-full bg-gradient-to-tr from-sky-400/20 via-indigo-400/15 to-emerald-400/20 blur-sm pointer-events-none"></div>
              
              <div class="relative z-10 space-y-1">
                <div class="text-xs font-mono tracking-widest text-sky-700 dark:text-sky-400 font-bold uppercase">
                  ACTIVE CORE
                </div>
                <div id="radialHubTitle" class="text-sm font-extrabold text-slate-900 dark:text-white leading-tight px-1" style="font-family: var(--font-display);">
                  Core Stack
                </div>
                <div id="radialHubCount" class="text-xs font-mono text-emerald-700 dark:text-emerald-400 font-semibold">
                  10 Skills
                </div>
              </div>
            </div>

            <!-- Container chứa các thẻ kỹ năng (Orbit Nodes) -->
            <div id="radialOrbitNodesContainer" class="absolute inset-0 pointer-events-none radial-spinning-ring">
              <!-- Được gắn động qua JavaScript -->
            </div>
          </div>
        </div>

      </div>

      <!-- Thanh hiển thị chi tiết dự án thực tế khi hover / tap -->
      <div id="radialFooterProofBar" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950/90 border border-slate-200/70 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs font-mono">
        <div class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
          <span class="w-2 h-2 rounded-full bg-sky-600"></span>
          <span class="font-bold text-slate-800 dark:text-white" id="radialProofSkillName">Laravel 11:</span>
          <span id="radialProofDetail" class="text-slate-600 dark:text-slate-300">Personal Portfolio &amp; CMS (hothanhthien.io.vn) — Fullstack SSR &amp; REST APIs</span>
        </div>
        <span class="text-sky-700 dark:text-sky-400 font-bold self-end sm:self-auto">{{ $isVi ? 'Minh chứng Kiểm nghiệm Thực tế' : 'Verified Production Proof' }}</span>
      </div>

    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script src="{{ asset('js/radial-orbit-wheel.js') }}?v={{ @filemtime(public_path('js/radial-orbit-wheel.js')) ?: 1 }}" async></script>
</div>

