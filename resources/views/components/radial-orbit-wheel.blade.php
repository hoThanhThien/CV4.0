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
      --radius: 168px;
    }
    @media (max-width: 640px) {
      #radialOrbitSection {
        --radius: 128px;
      }
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
      width: 108px;
      height: 48px;
      text-align: center;
      transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
      transform-style: preserve-3d;
      border-radius: 14px;
    }

    @media (max-width: 640px) {
      #radialOrbitSection .flip-card-inner {
        width: 88px;
        height: 42px;
      }
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
      padding: 0 8px;
      gap: 7px;
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
  <div class="orbit-outer-wrapper p-3 sm:p-6 rounded-[2rem] border border-slate-200/60 shadow-inner">
    
    <!-- Khung chứa chính (Outer Card) -->
    <div class="max-w-4xl w-full mx-auto bg-white rounded-3xl border border-slate-200/80 shadow-sm p-5 sm:p-8 space-y-6">

      <!-- 2. THANH TAB LỌC NHÓM KỸ NĂNG & NÚT SPIN AGAIN (Ở TRÊN) -->
      <div class="space-y-4">
        
        <!-- Hàng 1: 5 nút Tab bo góc rounded-xl -->
        <div id="radialCategoryTabs" class="flex flex-wrap items-center gap-2">
          <!-- Render tự động bằng JavaScript -->
        </div>

        <!-- Hàng 2: Dòng chữ hướng dẫn & Nút Spin Again -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1 border-t border-slate-100">
          <div class="flex items-center gap-1.5 font-mono text-xs text-sky-600 font-semibold tracking-wide">
            <span>✨</span>
            <span>{{ $isVi ? 'Rê chuột hoặc chạm vào thẻ kỹ năng để lật 3D & xem minh chứng dự án' : 'Hover or tap any skill card to 3D-flip & inspect real project usage' }}</span>
          </div>

          <button id="radialBtnSpinAgain" type="button" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white border border-slate-200 text-sky-600 font-mono text-xs font-bold hover:bg-sky-50/70 hover:border-sky-300 transition-all active:scale-95 shadow-sm self-start sm:self-auto">
            <svg class="w-3.5 h-3.5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            <span>{{ $isVi ? 'Quay lại' : 'Spin Again' }}</span>
          </button>
        </div>

      </div>

      <!-- 3. KHUNG SÂN KHẤU CHỨA VÒNG XOAY (Inner Stage) -->
      <div id="radialOrbitStage" class="rounded-2xl min-h-[440px] sm:min-h-[480px] bg-gradient-to-b from-sky-50/70 via-white to-indigo-50/40 border border-slate-200/70 flex items-center justify-center relative overflow-hidden select-none">

        <!-- Thông số tọa độ & Technical HUD ở các góc -->
        <div class="absolute top-4 left-4 font-mono text-[11px] text-slate-400 tracking-wider">
          RADIAL_STAGE // ORBIT_3D
        </div>
        <div class="absolute bottom-4 left-4 font-mono text-[11px] text-slate-400 flex items-center gap-2">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
          <span id="radialCoordReadout">R=168px • TOP 10</span>
        </div>
        <div class="absolute top-4 right-4 font-mono text-[11px] text-sky-600/70 font-semibold">
          3D PRESERVE
        </div>

        <!-- Vòng tròn quỹ đạo trong: w-[210px] h-[210px] -->
        <div class="w-[210px] h-[210px] rounded-full border border-indigo-500/20 absolute pointer-events-none"></div>

        <!-- Vòng tròn quỹ đạo ngoài: w-[330px] h-[330px] nét đứt (nơi các thẻ nằm đè lên) -->
        <div class="w-[330px] h-[330px] rounded-full border border-dashed border-sky-500/35 absolute pointer-events-none animate-orbit-ring"></div>

        <!-- VÒNG TRÒN LÕI Ở CHÍNH GIỮA (Central Core Hub) -->
        <div class="relative z-20 w-32 h-32 rounded-full bg-white border-2 border-sky-400/60 shadow-xl shadow-sky-500/15 flex flex-col items-center justify-center text-center p-2 transition-transform hover:scale-105 duration-300">
          <div class="absolute -inset-1 rounded-full bg-gradient-to-tr from-sky-400/20 via-indigo-400/15 to-emerald-400/20 blur-sm pointer-events-none"></div>
          
          <div class="relative z-10 space-y-1">
            <!-- Dòng 1: ACTIVE CORE -->
            <div class="text-[10px] font-mono tracking-widest text-sky-600 font-bold uppercase">
              ACTIVE CORE
            </div>
            <!-- Dòng 2: Tên nhóm đang chọn -->
            <div id="radialHubTitle" class="text-sm font-extrabold text-slate-900 leading-tight px-1" style="font-family: var(--font-display);">
              Core Stack
            </div>
            <!-- Dòng 3: Số lượng kỹ năng -->
            <div id="radialHubCount" class="text-[10px] font-mono text-emerald-600 font-semibold">
              10 Skills
            </div>
          </div>
        </div>

        <!-- Container chứa các thẻ kỹ năng (Orbit Nodes) -->
        <div id="radialOrbitNodesContainer" class="absolute inset-0 pointer-events-none radial-spinning-ring">
          <!-- Được gắn động qua JavaScript -->
        </div>

      </div>

      <!-- Thanh hiển thị chi tiết dự án thực tế khi hover / tap -->
      <div id="radialFooterProofBar" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs font-mono">
        <div class="flex items-center gap-2 text-slate-600">
          <span class="w-2 h-2 rounded-full bg-sky-500"></span>
          <span class="font-bold text-slate-800" id="radialProofSkillName">Laravel 11:</span>
          <span id="radialProofDetail" class="text-slate-600">Personal Portfolio &amp; CMS (hothanhthien.io.vn) — Fullstack SSR &amp; REST APIs</span>
        </div>
        <span class="text-sky-600 font-bold self-end sm:self-auto">{{ $isVi ? 'Minh chứng Kiểm nghiệm Thực tế' : 'Verified Production Proof' }}</span>
      </div>

    </div>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    (function() {
      const ICONS = {
        java: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#EA2D2E] flex-shrink-0" fill="currentColor"><path d="M8.85 16.84c0-.02-.02-.03-.02-.05.44.29 1.12.54 1.83.67 2.06.39 4.3-.12 5.06-1.74.04-.08.07-.17.09-.26-1.07.72-2.73.96-4.22.68-1.04-.2-1.99-.68-2.74-1.3zM12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm4.23 8.35c.27 1.34-.33 2.65-1.57 3.51-1.15.8-2.7 1.09-4.04.83-1.05-.2-2-.72-2.72-1.47 1.25.96 2.94 1.35 4.54 1.05 1.5-.28 2.76-1.28 3.24-2.67.19-.55.22-1.16.09-1.73-.18-.77-.66-1.46-1.33-1.89 1.15.42 1.63 1.31 1.79 2.37z"/></svg>`,
        php: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#777BB4] flex-shrink-0" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm-3.23 15.65H6.68l.84-4.83H9.2c1.28 0 2.05.6 1.83 1.83-.24 1.34-1.28 3-2.26 3zm6.65-4.83h-1.68l-.84 4.83h1.68l.33-1.92h1.34c1.28 0 2.05-.6 2.27-1.83.24-1.35-.45-1.08-3.1-1.08zm-7.61 2.2c.11-.63-.23-.88-.86-.88h-.65l-.47 2.68h.55c.78 0 1.32-.98 1.43-1.8zm6.54 0c-.11.63-.58 1.13-1.36 1.13h-.54l.47-2.68h.65c.63 0 .89.92.78 1.55z"/></svg>`,
        python: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#3776AB] flex-shrink-0" fill="currentColor"><path d="M11.91 0c-5.74 0-5.38 2.48-5.38 2.48l.01 2.57h5.47v.77H4.35S0 5.34 0 11.13c0 5.78 3.8 5.6 3.8 5.6h2.27v-3.17s-.12-3.8 3.74-3.8h5.53s3.62.06 3.62-3.51V2.48S19.5 0 11.91 0zm-2.9 1.71a1.08 1.08 0 110 2.16 1.08 1.08 0 010-2.16zm3.08 22.29c5.74 0 5.38-2.49 5.38-2.49l-.01-2.56h-5.47v-.78h7.66s4.35.48 4.35-5.31c0-5.79-3.8-5.6-3.8-5.6h-2.27v3.17s.12 3.79-3.74 3.79H9.46s-3.62-.05-3.62 3.52v3.77s-.54 2.49 7.05 2.49zm2.9-1.71a1.08 1.08 0 110-2.16 1.08 1.08 0 010 2.16z"/></svg>`,
        odoo: `<svg viewBox="0 7.2 24 9.8" width="22" height="12" class="w-5 h-3 text-[#714B67] flex-shrink-0" fill="currentColor" role="img" aria-label="Odoo Logo"><path d="M21.1002 15.7957c-1.6015 0-2.8997-1.2983-2.8997-2.8998s1.2983-2.8997 2.8997-2.8997c1.6015 0 2.8998 1.2982 2.8998 2.8997 0 1.5999-1.2979 2.8998-2.8998 2.8998zm0-1.2c.9388.0006 1.7003-.7601 1.7008-1.6989.0004-.9388-.7602-1.7003-1.699-1.7007h-.0018c-.9388.0004-1.6994.7619-1.699 1.7007.0005.9381.761 1.6985 1.699 1.699zm-6.0655 1.2c-1.6014 0-2.8997-1.2983-2.8997-2.8998s1.2983-2.8997 2.8997-2.8997c1.6015 0 2.8998 1.2982 2.8998 2.8997 0 1.5999-1.2999 2.8998-2.8998 2.8998zm0-1.2c.9389.0006 1.7003-.7601 1.7008-1.6989.0005-.9388-.7602-1.7003-1.699-1.7007h-.0018c-.9388.0004-1.6994.7619-1.699 1.7007.0005.9381.761 1.6985 1.699 1.699zM11.865 12.858c0 1.6199-1.2979 2.9378-2.8977 2.9378s-2.8998-1.314-2.8998-2.9358 1.1799-2.8597 2.8998-2.8597c.6359 0 1.2239.134 1.6998.484v-1.68a.6.6 0 0 1 1.2 0v4.0537h-.002zm-2.8977 1.7399c.9388.0005 1.7002-.7602 1.7007-1.699.0005-.9388-.7602-1.7003-1.699-1.7007h-.0017c-.9389.0004-1.6995.7619-1.699 1.7007.0004.9381.7608 1.6985 1.699 1.699zm-6.0675 1.1979C1.2983 15.7957 0 14.4974 0 12.8959s1.2983-2.8997 2.8998-2.8997 2.8997 1.2982 2.8997 2.8997c0 1.5999-1.2999 2.8998-2.8997 2.8998zm0-1.2c.9388.0006 1.7002-.7601 1.7007-1.699.0005-.9387-.7602-1.7002-1.699-1.7006h-.0017c-.9388.0004-1.6995.7619-1.699 1.7007.0004.9381.7608 1.6985 1.699 1.699z"/></svg>`,
        rag: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 flex-shrink-0" fill="none" role="img" aria-label="RAG AI Logo"><path d="M4.5 3.5h8.2L17 7.8v3.8M4.5 3.5v17a1.5 1.5 0 001.5 1.5h4.2M4.5 3.5A1.5 1.5 0 003 5v14.5" stroke="#0EA5E9" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.2 10h5.2M7.2 13.5h3.2" stroke="#6366F1" stroke-width="1.8" stroke-linecap="round"/><path d="M16.6 11.2l1.25 3.25 3.25 1.25-3.25 1.25-1.25 3.25-1.25-3.25-3.25-1.25 3.25-1.25 1.25-3.25z" fill="#6366F1"/><circle cx="19.5" cy="11" r="1.1" fill="#10B981"/></svg>`,
        react: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#0ea5e9] flex-shrink-0" fill="currentColor"><path d="M12 9.5a2.5 2.5 0 100 5 2.5 2.5 0 000-5zm0-7.5c-4.48 0-8.86 1.46-11.43 4.02-2.1 2.1-1.07 4.7 1.45 6.78-1.55 1.5-2.02 3.12-1.45 4.7.77 2.15 3.32 3.84 6.78 4.7-1.5 2.52-.77 5.07 1.45 6.78 2.57 1.98 6.95 2.02 11.43 0 2.22-1.71 2.95-4.26 1.45-6.78 3.46-.86 6.01-2.55 6.78-4.7.57-1.58.1-3.2-1.45-4.7 2.52-2.08 3.55-4.68 1.45-6.78-2.57-2.56-6.95-4.02-11.43-4.02z"/></svg>`,
        tailwind: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#06B6D4] flex-shrink-0" fill="currentColor"><path d="M12.001 4.8c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624C13.666 10.618 15.027 12 18.001 12c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C16.337 6.182 14.976 4.8 12.001 4.8z"/></svg>`,
        postgres: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#336791] flex-shrink-0" fill="currentColor"><path d="M12.01 0c-6.63 0-12 5.37-12 12 0 6.62 5.37 12 12 12 6.62 0 12-5.38 12-12 0-6.63-5.38-12-12-12zm6.27 15.65c-.17.65-.63 1.14-1.29 1.37-.58.2-1.2.22-1.78.07-.36-.09-.7-.25-1-.47-.43-.31-.77-.73-1-1.22l-.24-.52c-.12-.26-.26-.51-.43-.73-.24-.31-.53-.57-.86-.77-.45-.27-.96-.44-1.49-.49h-.21c-.48.04-.94.19-1.34.45-.48.31-.85.74-1.07 1.25-.19.44-.26.92-.2 1.4.08.61.36 1.17.8 1.6.45.44 1.03.71 1.66.78.29.03.58.01.86-.06.4-.1.78-.28 1.11-.53.25-.19.48-.41.67-.66.08-.1.15-.21.21-.32l.14-.24c.05-.09.11-.17.18-.24.16-.17.38-.27.61-.27.24 0 .47.1.63.28.16.18.23.42.2.66-.05.42-.23.8-.52 1.1-.38.4-.85.7-1.37.89-.58.21-1.2.29-1.81.23-.9-.08-1.75-.48-2.38-1.12-.66-.67-1.06-1.55-1.14-2.49-.07-.74.07-1.49.4-2.17.38-.79 1-1.43 1.77-1.85.75-.41 1.6-.58 2.45-.49.77.08 1.51.38 2.13.85.5.38.92.86 1.23 1.42.27.49.48 1.02.62 1.57.1.39.27.76.51 1.08.2.27.46.49.76.64.33.17.7.23 1.07.17.41-.07.78-.27 1.05-.58.3-.35.46-.8.44-1.26-.01-.4-.14-.79-.37-1.11-.2-.27-.47-.48-.78-.61-.35-.15-.74-.19-1.12-.13-.24.04-.48.12-.7.24-.18.1-.39.14-.6.1-.21-.04-.39-.16-.49-.34-.11-.18-.13-.4-.05-.6.08-.2.24-.35.44-.42.38-.13.78-.18 1.18-.15.65.05 1.28.28 1.8.66.49.36.87.85 1.09 1.42.25.64.29 1.34.12 2.01z"/></svg>`,
        docker: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#2496ED] flex-shrink-0" fill="currentColor"><path d="M13.983 11.078h2.119a.186.186 0 00.186-.185V9.006a.186.186 0 00-.186-.186h-2.119a.185.185 0 00-.185.185v1.888c0 .102.083.185.185.185m-2.954-5.43h2.118a.186.186 0 00.186-.186V3.574a.186.186 0 00-.186-.185h-2.118a.185.185 0 00-.185.185v1.888c0 .102.082.185.185.185"/></svg>`,
        figma: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#F24E1E] flex-shrink-0" fill="currentColor"><path d="M5.5 19a3.5 3.5 0 100-7H9v3.5A3.5 3.5 0 015.5 19zM12 0H8.5a3.5 3.5 0 100 7H12V0zm0 7H8.5a3.5 3.5 0 100 7H12V7zm0 0h3.5a3.5 3.5 0 100-7H12v7zm0 7h3.5a3.5 3.5 0 100-7H12v7z"/></svg>`,
        laravel: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#FF2D20] flex-shrink-0" fill="currentColor"><path d="M22.95 7.15l-4.14-2.4a1.86 1.86 0 00-1.87 0l-5.6 3.23-1.6-2.77a1.86 1.86 0 00-1.61-.93H3.66a1.86 1.86 0 00-1.61.93L.2 8.42a1.86 1.86 0 000 1.86l1.85 3.21a1.86 1.86 0 001.61.93h4.07a1.86 1.86 0 001.61-.93l1.6-2.77 5.6 3.23a1.86 1.86 0 001.87 0l4.14-2.4a1.86 1.86 0 00.93-1.61V8.76a1.86 1.86 0 00-.93-1.61z"/></svg>`,
        spring: `<img src="https://cdn.simpleicons.org/springboot/6DB33F?viewbox=auto" alt="Spring Boot Logo" width="16" height="16" class="w-4 h-4 object-contain flex-shrink-0" style="width:16px;height:16px;flex-shrink:0;" onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block';"><svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 hidden flex-shrink-0" style="display:none;flex-shrink:0;" fill="#6DB33F" role="img" aria-label="Spring Boot Logo"><path d="M12 2L3.3 7v10L12 22l8.7-5V7L12 2zm5.1 12.6c-1.8 1.8-4.7 2-6.9.7-.9.6-1.7 1.3-2.3 2.1l-1.1-.8c.8-1.1 1.7-2 2.8-2.7-1.6-2-1.4-5 .5-6.9 2.1-2.1 6-2.2 8.8-1.1-.4 3.1-1 6.7-1.8 8.7z"/></svg>`,
        jwt: `<img src="https://cdn.simpleicons.org/jsonwebtokens/D63AFF?viewbox=auto" alt="JWT Logo" width="16" height="16" class="w-4 h-4 object-contain flex-shrink-0" style="width:16px;height:16px;flex-shrink:0;" onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block';"><svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 hidden flex-shrink-0" style="display:none;flex-shrink:0;" fill="none" role="img" aria-label="JWT Security Logo"><path d="M12 2.5L4.5 5.8v5.9c0 5.1 3.2 9.4 7.5 10.8 4.3-1.4 7.5-5.7 7.5-10.8V5.8L12 2.5z" fill="#D63AFF" fill-opacity="0.18" stroke="#D63AFF" stroke-width="1.8"/><path d="M12 8.2a2.2 2.2 0 00-2.2 2.2v1.1h4.4v-1.1A2.2 2.2 0 0012 8.2zm-3.2 3.3h6.4v4.2H8.8v-4.2z" fill="#D63AFF"/></svg>`,
        fastapi: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#059669] flex-shrink-0" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm-.88 4.39l6.55 7.15-4.49.49 3.06 7.58-6.84-7.2 4.41-.49-2.69-7.53z"/></svg>`,
        chromadb: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 flex-shrink-0" fill="none" role="img" aria-label="ChromaDB Vector Logo"><rect x="3" y="6.5" width="9" height="11" rx="4.5" fill="#F43F5E" fill-opacity="0.88"/><rect x="7.5" y="6.5" width="9" height="11" rx="4.5" fill="#F59E0B" fill-opacity="0.88"/><rect x="12" y="6.5" width="9" height="11" rx="4.5" fill="#0EA5E9" fill-opacity="0.88"/></svg>`,
        mysql: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#00758F] flex-shrink-0" fill="currentColor"><path d="M17.48 9.38c-.37-.02-.73.08-1.02.28-.5.36-.66.97-.47 1.56.24.77.95 1.25 1.76 1.18.91-.07 1.54-.77 1.48-1.64-.06-.9-.78-1.38-1.75-1.38zM12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0z"/></svg>`,
        sqlite: `<img src="https://cdn.simpleicons.org/sqlite/003B57?viewbox=auto" alt="SQLite Logo" width="16" height="16" class="w-4 h-4 object-contain flex-shrink-0" style="width:16px;height:16px;flex-shrink:0;" onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block';"><svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 hidden flex-shrink-0" style="display:none;flex-shrink:0;" fill="none" role="img" aria-label="SQLite Logo"><ellipse cx="12" cy="5.5" rx="7.5" ry="3" fill="#0F80CC" fill-opacity="0.22" stroke="#0F80CC" stroke-width="1.8"/><path d="M4.5 5.5v13c0 1.66 3.36 3 7.5 3s7.5-1.34 7.5-3v-13" stroke="#0F80CC" stroke-width="1.8"/><path d="M4.5 12c0 1.66 3.36 3 7.5 3s7.5-1.34 7.5-3" stroke="#003B57" stroke-width="1.6"/></svg>`,
        js: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 flex-shrink-0" role="img" aria-label="JavaScript Logo"><rect width="24" height="24" rx="3" fill="#F7DF1E"/><path d="M13.6 18.2c.45.73 1.03 1.27 2.06 1.27.87 0 1.42-.43 1.42-1.03 0-.72-.57-.97-1.52-1.39l-.52-.22c-1.51-.64-2.51-1.45-2.51-3.15 0-1.57 1.2-2.76 3.07-2.76 1.33 0 2.29.46 2.98 1.67l-1.63 1.05c-.36-.64-.75-.9-1.35-.9-.61 0-1 .39-1 .9 0 .63.39.88 1.29 1.27l.52.22c1.77.76 2.77 1.53 2.77 3.27 0 1.88-1.47 2.91-3.45 2.91-1.93 0-3.18-.92-3.79-2.13l1.66-.98zm-7.18.17c.32.57.61 1.05 1.31 1.05.67 0 1.09-.26 1.09-1.28v-6.92h2.04v6.95c0 2.11-1.24 3.07-3.04 3.07-1.63 0-2.57-.84-3.05-1.86l1.65-1.01z" fill="#0F172A"/></svg>`,
        nextjs: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 flex-shrink-0" role="img" aria-label="Next.js Logo"><circle cx="12" cy="12" r="10.5" fill="#0F172A"/><path d="M8.5 16.5v-9l8.2 10.6a9.2 9.2 0 01-1.5.9L10.3 12v4.5H8.5zm6.2-4.8V7.5h1.6v6.2l-1.6-2z" fill="#FFFFFF"/></svg>`,
        nginx: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#009639] flex-shrink-0" fill="currentColor"><path d="M12 0L1.605 6v12L12 24l10.395-6V6L12 0zm0 2.215l8.475 4.89v9.79L12 21.785l-8.475-4.89V7.105L12 2.215z"/></svg>`,
        git: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#F05032] flex-shrink-0" fill="currentColor"><path d="M23.546 10.93L13.067.452a1.505 1.505 0 00-2.128 0L8.88 2.51l3.056 3.057a1.787 1.787 0 011.839 1.839l3.057 3.056a1.79 1.79 0 11-1.065 1.065L12.8 8.56a1.788 1.788 0 01-1.07.13L9.67 10.75a1.79 1.79 0 11-1.063-1.064l2.062-2.062a1.786 1.786 0 01.129-1.07L7.742 3.498.454 10.785a1.505 1.505 0 000 2.128l10.48 10.478a1.505 1.505 0 002.127 0l10.485-10.334a1.505 1.505 0 000-2.127z"/></svg>`,
        linux: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-slate-800 flex-shrink-0" fill="currentColor"><path d="M12.016 0c-3.144 0-4.664 2.28-4.664 5.344 0 1.344.368 3.528.984 4.88-.704.56-1.576 1.488-2.288 2.536-1.12 1.648-1.744 3.656-1.744 5.656 0 1.832.968 3.28 2.592 3.872 1.152.424 2.624.28 3.976-.176 1.256.92 2.76 1.408 4.384 1.408 1.584 0 3.056-.472 4.288-1.368 1.352.448 2.808.584 3.944.16 1.624-.6 2.576-2.048 2.576-3.872 0-2-.616-4.008-1.736-5.656-.704-1.048-1.576-1.976-2.28-2.536.616-1.352.984-3.536.984-4.88C17.68 2.28 16.16 0 13.016 0h-1z"/></svg>`,
        cpanel: `<img src="https://cdn.simpleicons.org/cpanel/FF6C2C?viewbox=auto" alt="cPanel Logo" width="16" height="16" class="w-4 h-4 object-contain flex-shrink-0" style="width:16px;height:16px;flex-shrink:0;" onerror="this.style.display='none';this.nextElementSibling.style.display='inline-block';"><svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 hidden flex-shrink-0" style="display:none;flex-shrink:0;" role="img" aria-label="cPanel Logo"><rect width="24" height="24" rx="5" fill="#FF6C2C"/><text x="12" y="16" text-anchor="middle" fill="#FFFFFF" font-family="ui-sans-serif,system-ui,sans-serif" font-weight="800" font-size="11">cP</text></svg>`
      };

      const TAB_ICONS = {
        core: `<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`,
        backend: `<svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>`,
        rag: `<svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>`,
        frontend: `<svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`,
        devops: `<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7l-8-4-8 4zM4 7l8 4m0 0l8-4m-8 4v10"/></svg>`
      };

      const CATEGORIES = [
        {
          id: 'core',
          title: 'Core Stack (Top 10)',
          hubName: 'Core Stack',
          icon: TAB_ICONS.core,
          items: [
            { name: 'Java', icon: ICONS.java, proof: 'Laundry System', desc: 'Spring Boot REST APIs & finite state order lifecycle' },
            { name: 'PHP', icon: ICONS.php, proof: 'Portfolio & CMS', desc: 'Laravel 11, Blade SSR, dynamic sitemaps & SQLite' },
            { name: 'Python', icon: ICONS.python, proof: 'TourRist RAG', desc: 'FastAPI async gateway, document ingestion pipeline' },
            { name: 'Odoo ERP', icon: ICONS.odoo, proof: 'Enmasys ERP', desc: 'Custom addons, ORM models & corporate sales flow' },
            { name: 'RAG AI', icon: ICONS.rag, proof: 'ChromaDB Search', desc: 'Cosine semantic lookup & zero-hallucination prompts' },
            { name: 'ReactJS', icon: ICONS.react, proof: 'Admin Dashboards', desc: 'Modular components, responsive reactive portals' },
            { name: 'Tailwind', icon: ICONS.tailwind, proof: 'Swiss Grid UI', desc: 'Editorial token system, high-contrast dark & light' },
            { name: 'PostgreSQL', icon: ICONS.postgres, proof: 'AIPower DB', desc: 'JSONB schema, indexed queries & ACID transactions' },
            { name: 'Docker', icon: ICONS.docker, proof: 'DevOps Stacks', desc: 'Compose orchestration for multi-container microservices' },
            { name: 'Figma', icon: ICONS.figma, proof: 'Design Honors', desc: '2nd Prize UTH Design Award, typography & brand identity' }
          ]
        },
        {
          id: 'backend',
          title: 'Backend & ERP',
          hubName: 'Backend & ERP',
          icon: TAB_ICONS.backend,
          items: [
            { name: 'Laravel 11', icon: ICONS.laravel, proof: 'Production CMS', desc: 'Dynamic portfolio, bilingual VI/EN & Sanctum auth' },
            { name: 'Spring Boot', icon: ICONS.spring, proof: 'Laundry App', desc: 'Spring Data JPA, Hibernate & JWT security guards' },
            { name: 'Odoo Python', icon: ICONS.odoo, proof: 'Enterprise ERP', desc: 'Automated invoice workflows & stock management' },
            { name: 'FastAPI', icon: ICONS.fastapi, proof: 'TourRist API', desc: 'High-speed async Python REST endpoints' },
            { name: 'MySQL 8.0', icon: ICONS.mysql, proof: 'Normalized DB', desc: 'Relational 3NF schemas with cascade constraints' },
            { name: 'JWT Security', icon: ICONS.jwt, proof: 'RBAC Guards', desc: 'Token lifecycle & multi-tier authorization layers' }
          ]
        },
        {
          id: 'rag',
          title: 'RAG AI & Databases',
          hubName: 'RAG & Data',
          icon: TAB_ICONS.rag,
          items: [
            { name: 'ChromaDB', icon: ICONS.chromadb, proof: 'Vector Index', desc: 'Private doc embeddings & k-NN semantic search' },
            { name: 'Applied RAG', icon: ICONS.rag, proof: 'TourRist Chatbot', desc: 'Document chunking & grounded LLM generation' },
            { name: 'PostgreSQL', icon: ICONS.postgres, proof: 'Enterprise DB', desc: 'Reliable transactional storage & JSONB support' },
            { name: 'MySQL 8.0', icon: ICONS.mysql, proof: 'Portfolio DB', desc: 'Relational data for projects, skills & certificates' },
            { name: 'SQLite', icon: ICONS.sqlite, proof: 'Embedded Engine', desc: 'Fast in-memory tests & zero-config WAL database' },
            { name: 'Vector Search', icon: ICONS.chromadb, proof: 'Cosine Metric', desc: 'Dense embeddings for instant semantic document lookup' }
          ]
        },
        {
          id: 'frontend',
          title: 'Frontend & UI/UX',
          hubName: 'Frontend / UI',
          icon: TAB_ICONS.frontend,
          items: [
            { name: 'React 18', icon: ICONS.react, proof: 'Web Interfaces', desc: 'Functional hooks, clean state machines & portals' },
            { name: 'Tailwind CSS', icon: ICONS.tailwind, proof: 'Editorial Bento', desc: 'High-contrast design tokens & fluid mobile grids' },
            { name: 'Blade SSR', icon: ICONS.laravel, proof: 'Laravel Core', desc: 'Modular server-rendered components with zero bloat' },
            { name: 'JavaScript', icon: ICONS.js, proof: 'Trig Orbit Wheel', desc: 'Dynamic trigonometric radial math & custom palette' },
            { name: 'Figma Design', icon: ICONS.figma, proof: 'Brand Identity', desc: 'Award-winning Swiss grid layouts & WCAG accessibility' },
            { name: 'Next.js', icon: ICONS.nextjs, proof: 'Laundry Client', desc: 'SSR and customer pickup booking self-service' }
          ]
        },
        {
          id: 'devops',
          title: 'DevOps & Architecture',
          hubName: 'DevOps & Infra',
          icon: TAB_ICONS.devops,
          items: [
            { name: 'Docker', icon: ICONS.docker, proof: 'Containerization', desc: 'Multi-stage Dockerfiles & isolated compose services' },
            { name: 'Nginx', icon: ICONS.nginx, proof: 'Live Server', desc: 'Reverse proxy, SSL termination & HTTP/2 routing' },
            { name: 'Git & GitHub', icon: ICONS.git, proof: 'Clean Branches', desc: 'Semantic commits & feature PR workflows' },
            { name: 'Linux / Bash', icon: ICONS.linux, proof: 'Daemon Cron', desc: 'Scheduled queue workers & automated sitemaps' },
            { name: 'cPanel Host', icon: ICONS.cpanel, proof: 'hothanhthien.io.vn', desc: 'Production DNS, database & cron configuration' }
          ]
        }
      ];

      let currentCatIndex = 0;

      const categoryTabs = document.getElementById('radialCategoryTabs');
      const radialOrbitStage = document.getElementById('radialOrbitStage');
      const orbitNodesContainer = document.getElementById('radialOrbitNodesContainer');
      const hubTitle = document.getElementById('radialHubTitle');
      const hubCount = document.getElementById('radialHubCount');
      const coordReadout = document.getElementById('radialCoordReadout');
      const btnSpinAgain = document.getElementById('radialBtnSpinAgain');
      const proofSkillName = document.getElementById('radialProofSkillName');
      const proofDetail = document.getElementById('radialProofDetail');

      if (!categoryTabs || !orbitNodesContainer) return;

      function renderTabs() {
        categoryTabs.innerHTML = '';
        CATEGORIES.forEach((cat, index) => {
          const btn = document.createElement('button');
          btn.type = 'button';
          const isActive = index === currentCatIndex;

          btn.className = isActive
            ? 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-sky-500 text-white shadow-lg shadow-sky-500/20 flex items-center gap-2 transition-all duration-200'
            : 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 flex items-center gap-2 transition-all duration-200';

          btn.innerHTML = `${cat.icon} <span>${cat.title}</span>`;
          btn.addEventListener('click', () => {
            if (currentCatIndex !== index) {
              currentCatIndex = index;
              renderTabs();
              renderWheel(true);
            }
          });
          categoryTabs.appendChild(btn);
        });
      }

      function renderWheel(animate = true) {
        const cat = CATEGORIES[currentCatIndex];
        const items = cat.items;
        const N = items.length;

        hubTitle.textContent = cat.hubName;
        hubCount.textContent = `${N} Skills`;
        coordReadout.textContent = `R=168px • ${N} SKILLS`;

        if (radialOrbitStage) radialOrbitStage.classList.remove('is-hover-paused');
        orbitNodesContainer.innerHTML = '';
        orbitNodesContainer.classList.remove('radial-spinning-ring');
        void orbitNodesContainer.offsetWidth;
        orbitNodesContainer.classList.add('radial-spinning-ring');

        items.forEach((item, index) => {
          const angle = Math.round((360 / items.length) * index);

          const node = document.createElement('div');
          node.className = 'orbit-node';
          node.style.setProperty('--angle', `${angle}deg`);
          node.style.setProperty('--delay', `${index * 65}ms`);

          node.innerHTML = `
            <div class="radial-node-upright">
              <div class="flip-card-inner">
                <div class="flip-card-front">
                  <span class="flex-shrink-0">${item.icon}</span>
                  <span class="text-xs font-bold text-slate-800 tracking-tight whitespace-nowrap">${item.name}</span>
                </div>
                <div class="flip-card-back">
                  <span class="text-[9px] font-mono font-bold tracking-tight uppercase leading-tight line-clamp-1">${item.proof}</span>
                  <span class="text-[8px] text-sky-100 font-mono line-clamp-1">${item.name}</span>
                </div>
              </div>
            </div>
          `;

          const updateProof = () => {
            proofSkillName.textContent = `${item.name}:`;
            proofDetail.textContent = `${item.proof} — ${item.desc}`;
          };

          node.addEventListener('mouseenter', () => {
            if (radialOrbitStage) radialOrbitStage.classList.add('is-hover-paused');
            updateProof();
          });
          node.addEventListener('mouseleave', () => {
            if (radialOrbitStage) radialOrbitStage.classList.remove('is-hover-paused');
          });
          node.addEventListener('click', () => {
            node.classList.toggle('is-flipped');
            updateProof();
          });

          orbitNodesContainer.appendChild(node);
        });

        if (items.length > 0) {
          proofSkillName.textContent = `${items[0].name}:`;
          proofDetail.textContent = `${items[0].proof} — ${items[0].desc}`;
        }
      }

      btnSpinAgain.addEventListener('click', () => {
        btnSpinAgain.classList.add('spin-active');
        renderWheel(true);
        setTimeout(() => btnSpinAgain.classList.remove('spin-active'), 650);
      });

      renderTabs();
      renderWheel(true);
    })();
  </script>
</div>

