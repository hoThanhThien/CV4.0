{{--
  Component: Radial Orbit Skill Wheel (Dark Editorial Tech — Self-Contained CSS)
  Zero external Tailwind CSS dependency so it works identically inside layouts/app.blade.php.
--}}
@php
    $isVi = app()->getLocale() === 'vi';
@endphp

<div id="radialOrbitSection" class="radial-orbit-wrapper">
  <style>
    #radialOrbitSection {
      --radius: 168px;
      width: 100%;
      margin: 1.5rem 0 0;
    }
    @media (max-width: 640px) {
      #radialOrbitSection {
        --radius: 124px;
      }
    }

    #radialOrbitSection .orbit-card-shell {
      background-color: #0B111E;
      background-image:
        linear-gradient(to right, rgba(148, 163, 184, 0.05) 1px, transparent 1px),
        linear-gradient(to bottom, rgba(148, 163, 184, 0.05) 1px, transparent 1px);
      background-size: 38px 38px;
      border: 1px solid rgba(148, 163, 184, 0.16);
      border-radius: var(--radius-lg, 14px);
      padding: clamp(1rem, 3vw, 1.75rem);
      display: flex;
      flex-direction: column;
      gap: 1.15rem;
      box-shadow: 0 18px 40px -14px rgba(0, 0, 0, 0.55);
    }

    #radialOrbitSection .orbit-controls {
      display: flex;
      flex-direction: column;
      gap: 0.9rem;
    }

    #radialOrbitSection .orbit-tabs-row {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.5rem;
    }

    #radialOrbitSection .orbit-tab-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.48rem 0.9rem;
      border-radius: var(--radius-sm, 6px);
      border: 1px solid rgba(148, 163, 184, 0.18);
      background: #06090F;
      color: #94A3B8;
      font-family: var(--font-mono, monospace);
      font-size: 0.78rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.18s ease;
    }
    #radialOrbitSection .orbit-tab-btn svg {
      width: 14px;
      height: 14px;
      flex-shrink: 0;
    }
    #radialOrbitSection .orbit-tab-btn:hover {
      border-color: rgba(198, 244, 50, 0.55);
      color: #F8FAFC;
    }
    #radialOrbitSection .orbit-tab-btn.is-active {
      background: #C6F432;
      border-color: #C6F432;
      color: #06090F;
      font-weight: 700;
      box-shadow: 0 0 14px rgba(198, 244, 50, 0.3);
    }
    #radialOrbitSection .orbit-tab-btn.is-active svg {
      color: #06090F !important;
    }

    #radialOrbitSection .orbit-subbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
      padding-top: 0.85rem;
      border-top: 1px solid rgba(148, 163, 184, 0.12);
    }
    #radialOrbitSection .orbit-hint {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      font-family: var(--font-mono, monospace);
      font-size: 0.76rem;
      font-weight: 600;
      color: #38BDF8;
    }

    #radialOrbitSection .orbit-replay-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.42rem;
      padding: 0.42rem 0.85rem;
      border-radius: var(--radius-sm, 6px);
      background: #10182A;
      border: 1px solid rgba(148, 163, 184, 0.24);
      color: #C6F432;
      font-family: var(--font-mono, monospace);
      font-size: 0.76rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.18s ease;
    }
    #radialOrbitSection .orbit-replay-btn:hover {
      border-color: #C6F432;
      background: rgba(198, 244, 50, 0.1);
    }
    #radialOrbitSection .orbit-replay-btn svg {
      width: 14px;
      height: 14px;
    }
    #radialOrbitSection .orbit-replay-btn.spin-active svg {
      animation: spin360 0.65s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Inner Radial Stage: MUST keep position: relative + overflow: hidden so nodes NEVER escape */
    #radialOrbitSection .orbit-stage {
      position: relative;
      overflow: hidden;
      min-height: 450px;
      border-radius: var(--radius-md, 10px);
      background: radial-gradient(circle at center, rgba(56, 189, 248, 0.08) 0%, #06090F 74%);
      border: 1px solid rgba(148, 163, 184, 0.16);
      display: flex;
      align-items: center;
      justify-content: center;
      user-select: none;
    }
    @media (max-width: 640px) {
      #radialOrbitSection .orbit-stage {
        min-height: 365px;
      }
    }

    #radialOrbitSection .orbit-hud {
      position: absolute;
      font-family: var(--font-mono, monospace);
      font-size: 0.68rem;
      color: #64748B;
      letter-spacing: 0.05em;
      pointer-events: none;
      z-index: 5;
    }
    #radialOrbitSection .orbit-hud--tl { top: 0.9rem; left: 1rem; }
    #radialOrbitSection .orbit-hud--tr { top: 0.9rem; right: 1rem; color: #38BDF8; font-weight: 600; }
    #radialOrbitSection .orbit-hud--bl {
      bottom: 0.9rem;
      left: 1rem;
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
    }
    #radialOrbitSection .orbit-hud-dot {
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #C6F432;
      box-shadow: 0 0 8px #C6F432;
      display: inline-block;
    }

    /* Concentric Guide Rings */
    #radialOrbitSection .orbit-ring-inner,
    #radialOrbitSection .orbit-ring-outer {
      position: absolute;
      border-radius: 9999px;
      pointer-events: none;
    }
    #radialOrbitSection .orbit-ring-inner {
      width: 210px;
      height: 210px;
      border: 1px solid rgba(99, 102, 241, 0.25);
    }
    #radialOrbitSection .orbit-ring-outer {
      width: 336px;
      height: 336px;
      border: 1px dashed rgba(56, 189, 248, 0.35);
      animation: orbitRingSpin 80s linear infinite;
    }
    @media (max-width: 640px) {
      #radialOrbitSection .orbit-ring-inner {
        width: 162px;
        height: 162px;
      }
      #radialOrbitSection .orbit-ring-outer {
        width: 248px;
        height: 248px;
      }
    }

    /* Central Core Hub */
    #radialOrbitSection .orbit-core-hub {
      position: relative;
      z-index: 20;
      width: 126px;
      height: 126px;
      border-radius: 9999px;
      background: #0B111E;
      border: 2px solid rgba(198, 244, 50, 0.5);
      box-shadow: 0 0 32px rgba(56, 189, 248, 0.16), inset 0 0 18px rgba(198, 244, 50, 0.06);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 0.5rem;
      transition: transform 0.28s ease;
    }
    #radialOrbitSection .orbit-core-hub:hover {
      transform: scale(1.04);
    }
    @media (max-width: 640px) {
      #radialOrbitSection .orbit-core-hub {
        width: 102px;
        height: 102px;
      }
    }
    #radialOrbitSection .hub-kicker {
      font-family: var(--font-mono, monospace);
      font-size: 0.62rem;
      font-weight: 700;
      letter-spacing: 0.08em;
      color: #38BDF8;
      text-transform: uppercase;
    }
    #radialOrbitSection .hub-title {
      font-family: var(--font-display, sans-serif);
      font-size: 0.92rem;
      font-weight: 800;
      color: #F8FAFC;
      line-height: 1.2;
      margin: 0.2rem 0;
    }
    #radialOrbitSection .hub-count {
      font-family: var(--font-mono, monospace);
      font-size: 0.66rem;
      font-weight: 700;
      color: #C6F432;
    }

    /* Orbit Nodes Layer */
    #radialOrbitSection .orbit-nodes-container {
      position: absolute;
      inset: 0;
      pointer-events: auto;
    }

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

    #radialOrbitSection .orbit-node {
      position: absolute;
      top: 50%;
      left: 50%;
      opacity: 0;
      animation: orbitNodeEmerge 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
      animation-delay: var(--delay);
      perspective: 1000px;
      user-select: none;
      cursor: pointer;
      z-index: 25;
    }
    #radialOrbitSection .orbit-node:hover {
      z-index: 40;
    }

    #radialOrbitSection .flip-card-inner {
      position: relative;
      width: 112px;
      height: 44px;
      text-align: center;
      transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
      transform-style: preserve-3d;
      border-radius: 10px;
    }
    @media (max-width: 640px) {
      #radialOrbitSection .flip-card-inner {
        width: 88px;
        height: 38px;
      }
    }

    #radialOrbitSection .orbit-node:hover .flip-card-inner,
    #radialOrbitSection .orbit-node.is-flipped .flip-card-inner {
      transform: rotateY(180deg) scale(1.06);
      box-shadow: 0 12px 24px -4px rgba(198, 244, 50, 0.24);
    }

    #radialOrbitSection .flip-card-front,
    #radialOrbitSection .flip-card-back {
      position: absolute;
      inset: 0;
      -webkit-backface-visibility: hidden;
      backface-visibility: hidden;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    #radialOrbitSection .flip-card-front {
      background: #10182A;
      border: 1px solid rgba(148, 163, 184, 0.25);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.45);
      padding: 0 9px;
      gap: 6px;
    }
    #radialOrbitSection .orbit-node-icon {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    #radialOrbitSection .orbit-node-icon svg {
      width: 15px;
      height: 15px;
    }
    #radialOrbitSection .orbit-node-label {
      font-family: var(--font-sans, sans-serif);
      font-size: 0.74rem;
      font-weight: 700;
      color: #F8FAFC;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    @media (max-width: 640px) {
      #radialOrbitSection .orbit-node-label {
        font-size: 0.66rem;
      }
    }

    #radialOrbitSection .flip-card-back {
      background: linear-gradient(135deg, #0D2036 0%, #10182A 100%);
      border: 1px solid #C6F432;
      color: #F8FAFC;
      transform: rotateY(180deg);
      padding: 2px 6px;
      flex-direction: column;
      gap: 1px;
    }
    #radialOrbitSection .flip-back-proof {
      font-family: var(--font-mono, monospace);
      font-size: 0.56rem;
      font-weight: 700;
      color: #C6F432;
      text-transform: uppercase;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 100%;
    }
    #radialOrbitSection .flip-back-sub {
      font-family: var(--font-mono, monospace);
      font-size: 0.52rem;
      color: #94A3B8;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 100%;
    }

    /* Footer Proof Readout Bar */
    #radialOrbitSection .orbit-proof-bar {
      padding: 0.8rem 1rem;
      border-radius: var(--radius-sm, 6px);
      background: #06090F;
      border: 1px solid rgba(148, 163, 184, 0.16);
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 0.65rem;
      font-family: var(--font-mono, monospace);
      font-size: 0.76rem;
    }
    #radialOrbitSection .orbit-proof-left {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 0.5rem;
      color: #CBD5E1;
    }
    #radialOrbitSection .orbit-proof-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #38BDF8;
      display: inline-block;
      flex-shrink: 0;
    }
    #radialOrbitSection .orbit-proof-skill {
      font-weight: 700;
      color: #F8FAFC;
    }
    #radialOrbitSection .orbit-proof-detail {
      color: #94A3B8;
    }
    #radialOrbitSection .orbit-proof-badge {
      color: #C6F432;
      font-weight: 700;
    }

    @keyframes orbitRingSpin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    @keyframes spin360 {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
  </style>

  <div class="orbit-card-shell">
    <!-- Tabs & Replay Trigger -->
    <div class="orbit-controls">
      <div id="radialCategoryTabs" class="orbit-tabs-row"></div>

      <div class="orbit-subbar">
        <div class="orbit-hint">
          <i class="fas fa-circle-nodes" aria-hidden="true"></i>
          <span>{{ $isVi ? 'Rê chuột hoặc chạm vào thẻ kỹ năng để lật 3D & xem minh chứng dự án thực tế' : 'Hover or tap any skill card to 3D-flip & inspect real production usage' }}</span>
        </div>

        <button id="radialBtnSpinAgain" type="button" class="orbit-replay-btn">
          <svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          <span>{{ $isVi ? 'Quay lại hiệu ứng' : 'Spin Again' }}</span>
        </button>
      </div>
    </div>

    <!-- Inner Radial Orbit Stage -->
    <div class="orbit-stage">
      <div class="orbit-hud orbit-hud--tl">RADIAL_STAGE // ORBIT_3D</div>
      <div class="orbit-hud orbit-hud--bl">
        <span class="orbit-hud-dot"></span>
        <span id="radialCoordReadout">R=168px • TOP 10</span>
      </div>
      <div class="orbit-hud orbit-hud--tr">3D PRESERVE</div>

      <div class="orbit-ring-inner"></div>
      <div class="orbit-ring-outer"></div>

      <!-- Central Core Hub -->
      <div class="orbit-core-hub">
        <div class="hub-kicker">ACTIVE CORE</div>
        <div id="radialHubTitle" class="hub-title">Core Stack</div>
        <div id="radialHubCount" class="hub-count">10 {{ $isVi ? 'Kỹ năng' : 'Skills' }}</div>
      </div>

      <!-- Orbit Nodes -->
      <div id="radialOrbitNodesContainer" class="orbit-nodes-container"></div>
    </div>

    <!-- Bottom Verified Proof Bar -->
    <div id="radialFooterProofBar" class="orbit-proof-bar">
      <div class="orbit-proof-left">
        <span class="orbit-proof-dot"></span>
        <span class="orbit-proof-skill" id="radialProofSkillName">Laravel 11:</span>
        <span class="orbit-proof-detail" id="radialProofDetail">Personal Portfolio &amp; CMS (hothanhthien.io.vn) — Fullstack SSR &amp; REST APIs</span>
      </div>
      <span class="orbit-proof-badge">{{ $isVi ? 'Minh chứng Kiểm nghiệm Thực tế' : 'Verified Production Proof' }}</span>
    </div>
  </div>

  <script>
    (function() {
      const IS_VI = {{ $isVi ? 'true' : 'false' }};
      const ICONS = {
        java: `<svg viewBox="0 0 24 24" style="color:#F87171" fill="currentColor"><path d="M8.85 16.84c0-.02-.02-.03-.02-.05.44.29 1.12.54 1.83.67 2.06.39 4.3-.12 5.06-1.74.04-.08.07-.17.09-.26-1.07.72-2.73.96-4.22.68-1.04-.2-1.99-.68-2.74-1.3zM12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm4.23 8.35c.27 1.34-.33 2.65-1.57 3.51-1.15.8-2.7 1.09-4.04.83-1.05-.2-2-.72-2.72-1.47 1.25.96 2.94 1.35 4.54 1.05 1.5-.28 2.76-1.28 3.24-2.67.19-.55.22-1.16.09-1.73-.18-.77-.66-1.46-1.33-1.89 1.15.42 1.63 1.31 1.79 2.37z"/></svg>`,
        php: `<svg viewBox="0 0 24 24" style="color:#A5B4FC" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm-3.23 15.65H6.68l.84-4.83H9.2c1.28 0 2.05.6 1.83 1.83-.24 1.34-1.28 3-2.26 3zm6.65-4.83h-1.68l-.84 4.83h1.68l.33-1.92h1.34c1.28 0 2.05-.6 2.27-1.83.24-1.35-.45-1.08-3.1-1.08zm-7.61 2.2c.11-.63-.23-.88-.86-.88h-.65l-.47 2.68h.55c.78 0 1.32-.98 1.43-1.8zm6.54 0c-.11.63-.58 1.13-1.36 1.13h-.54l.47-2.68h.65c.63 0 .89.92.78 1.55z"/></svg>`,
        python: `<svg viewBox="0 0 24 24" style="color:#60A5FA" fill="currentColor"><path d="M11.91 0c-5.74 0-5.38 2.48-5.38 2.48l.01 2.57h5.47v.77H4.35S0 5.34 0 11.13c0 5.78 3.8 5.6 3.8 5.6h2.27v-3.17s-.12-3.8 3.74-3.8h5.53s3.62.06 3.62-3.51V2.48S19.5 0 11.91 0zm-2.9 1.71a1.08 1.08 0 110 2.16 1.08 1.08 0 010-2.16zm3.08 22.29c5.74 0 5.38-2.49 5.38-2.49l-.01-2.56h-5.47v-.78h7.66s4.35.48 4.35-5.31c0-5.79-3.8-5.6-3.8-5.6h-2.27v3.17s.12 3.79-3.74 3.79H9.46s-3.62-.05-3.62 3.52v3.77s-.54 2.49 7.05 2.49zm2.9-1.71a1.08 1.08 0 110-2.16 1.08 1.08 0 010 2.16z"/></svg>`,
        odoo: `<svg viewBox="0 0 24 24" style="color:#D8B4FE" fill="currentColor"><path d="M12 0A12 12 0 1024 12 12.013 12.013 0 0012 0zm0 18.75a6.75 6.75 0 116.75-6.75 6.757 6.757 0 01-6.75 6.75z"/></svg>`,
        rag: `<svg viewBox="0 0 24 24" style="color:#C6F432" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>`,
        react: `<svg viewBox="0 0 24 24" style="color:#38BDF8" fill="currentColor"><path d="M12 9.5a2.5 2.5 0 100 5 2.5 2.5 0 000-5zm0-7.5c-4.48 0-8.86 1.46-11.43 4.02-2.1 2.1-1.07 4.7 1.45 6.78-1.55 1.5-2.02 3.12-1.45 4.7.77 2.15 3.32 3.84 6.78 4.7-1.5 2.52-.77 5.07 1.45 6.78 2.57 1.98 6.95 2.02 11.43 0 2.22-1.71 2.95-4.26 1.45-6.78 3.46-.86 6.01-2.55 6.78-4.7.57-1.58.1-3.2-1.45-4.7 2.52-2.08 3.55-4.68 1.45-6.78-2.57-2.56-6.95-4.02-11.43-4.02z"/></svg>`,
        tailwind: `<svg viewBox="0 0 24 24" style="color:#22D3EE" fill="currentColor"><path d="M12.001 4.8c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624C13.666 10.618 15.027 12 18.001 12c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C16.337 6.182 14.976 4.8 12.001 4.8z"/></svg>`,
        postgres: `<svg viewBox="0 0 24 24" style="color:#93C5FD" fill="currentColor"><path d="M12.01 0c-6.63 0-12 5.37-12 12 0 6.62 5.37 12 12 12 6.62 0 12-5.38 12-12 0-6.63-5.38-12-12-12zm6.27 15.65c-.17.65-.63 1.14-1.29 1.37-.58.2-1.2.22-1.78.07-.36-.09-.7-.25-1-.47-.43-.31-.77-.73-1-1.22l-.24-.52c-.12-.26-.26-.51-.43-.73-.24-.31-.53-.57-.86-.77-.45-.27-.96-.44-1.49-.49h-.21c-.48.04-.94.19-1.34.45-.48.31-.85.74-1.07 1.25-.19.44-.26.92-.2 1.4.08.61.36 1.17.8 1.6.45.44 1.03.71 1.66.78.29.03.58.01.86-.06.4-.1.78-.28 1.11-.53.25-.19.48-.41.67-.66.08-.1.15-.21.21-.32l.14-.24c.05-.09.11-.17.18-.24.16-.17.38-.27.61-.27.24 0 .47.1.63.28.16.18.23.42.2.66-.05.42-.23.8-.52 1.1-.38.4-.85.7-1.37.89-.58.21-1.2.29-1.81.23-.9-.08-1.75-.48-2.38-1.12-.66-.67-1.06-1.55-1.14-2.49-.07-.74.07-1.49.4-2.17.38-.79 1-1.43 1.77-1.85.75-.41 1.6-.58 2.45-.49.77.08 1.51.38 2.13.85.5.38.92.86 1.23 1.42.27.49.48 1.02.62 1.57.1.39.27.76.51 1.08.2.27.46.49.76.64.33.17.7.23 1.07.17.41-.07.78-.27 1.05-.58.3-.35.46-.8.44-1.26-.01-.4-.14-.79-.37-1.11-.2-.27-.47-.48-.78-.61-.35-.15-.74-.19-1.12-.13-.24.04-.48.12-.7.24-.18.1-.39.14-.6.1-.21-.04-.39-.16-.49-.34-.11-.18-.13-.4-.05-.6.08-.2.24-.35.44-.42.38-.13.78-.18 1.18-.15.65.05 1.28.28 1.8.66.49.36.87.85 1.09 1.42.25.64.29 1.34.12 2.01z"/></svg>`,
        docker: `<svg viewBox="0 0 24 24" style="color:#38BDF8" fill="currentColor"><path d="M13.983 11.078h2.119a.186.186 0 00.186-.185V9.006a.186.186 0 00-.186-.186h-2.119a.185.185 0 00-.185.185v1.888c0 .102.083.185.185.185m-2.954-5.43h2.118a.186.186 0 00.186-.186V3.574a.186.186 0 00-.186-.185h-2.118a.185.185 0 00-.185.185v1.888c0 .102.082.185.185.185"/></svg>`,
        figma: `<svg viewBox="0 0 24 24" style="color:#FB923C" fill="currentColor"><path d="M5.5 19a3.5 3.5 0 100-7H9v3.5A3.5 3.5 0 015.5 19zM12 0H8.5a3.5 3.5 0 100 7H12V0zm0 7H8.5a3.5 3.5 0 100 7H12V7zm0 0h3.5a3.5 3.5 0 100-7H12v7zm0 7h3.5a3.5 3.5 0 100-7H12v7z"/></svg>`,
        laravel: `<svg viewBox="0 0 24 24" style="color:#F87171" fill="currentColor"><path d="M22.95 7.15l-4.14-2.4a1.86 1.86 0 00-1.87 0l-5.6 3.23-1.6-2.77a1.86 1.86 0 00-1.61-.93H3.66a1.86 1.86 0 00-1.61.93L.2 8.42a1.86 1.86 0 000 1.86l1.85 3.21a1.86 1.86 0 001.61.93h4.07a1.86 1.86 0 001.61-.93l1.6-2.77 5.6 3.23a1.86 1.86 0 001.87 0l4.14-2.4a1.86 1.86 0 00.93-1.61V8.76a1.86 1.86 0 00-.93-1.61z"/></svg>`,
        fastapi: `<svg viewBox="0 0 24 24" style="color:#34D399" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm-.88 4.39l6.55 7.15-4.49.49 3.06 7.58-6.84-7.2 4.41-.49-2.69-7.53z"/></svg>`,
        chromadb: `<svg viewBox="0 0 24 24" style="color:#FBBF24" fill="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>`,
        mysql: `<svg viewBox="0 0 24 24" style="color:#38BDF8" fill="currentColor"><path d="M17.48 9.38c-.37-.02-.73.08-1.02.28-.5.36-.66.97-.47 1.56.24.77.95 1.25 1.76 1.18.91-.07 1.54-.77 1.48-1.64-.06-.9-.78-1.38-1.75-1.38zM12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0z"/></svg>`,
        nginx: `<svg viewBox="0 0 24 24" style="color:#34D399" fill="currentColor"><path d="M12 0L1.605 6v12L12 24l10.395-6V6L12 0zm0 2.215l8.475 4.89v9.79L12 21.785l-8.475-4.89V7.105L12 2.215z"/></svg>`,
        git: `<svg viewBox="0 0 24 24" style="color:#FB923C" fill="currentColor"><path d="M23.546 10.93L13.067.452a1.505 1.505 0 00-2.128 0L8.88 2.51l3.056 3.057a1.787 1.787 0 011.839 1.839l3.057 3.056a1.79 1.79 0 11-1.065 1.065L12.8 8.56a1.788 1.788 0 01-1.07.13L9.67 10.75a1.79 1.79 0 11-1.063-1.064l2.062-2.062a1.786 1.786 0 01.129-1.07L7.742 3.498.454 10.785a1.505 1.505 0 000 2.128l10.48 10.478a1.505 1.505 0 002.127 0l10.485-10.334a1.505 1.505 0 000-2.127z"/></svg>`,
        linux: `<svg viewBox="0 0 24 24" style="color:#E2E8F0" fill="currentColor"><path d="M12.016 0c-3.144 0-4.664 2.28-4.664 5.344 0 1.344.368 3.528.984 4.88-.704.56-1.576 1.488-2.288 2.536-1.12 1.648-1.744 3.656-1.744 5.656 0 1.832.968 3.28 2.592 3.872 1.152.424 2.624.28 3.976-.176 1.256.92 2.76 1.408 4.384 1.408 1.584 0 3.056-.472 4.288-1.368 1.352.448 2.808.584 3.944.16 1.624-.6 2.576-2.048 2.576-3.872 0-2-.616-4.008-1.736-5.656-.704-1.048-1.576-1.976-2.28-2.536.616-1.352.984-3.536.984-4.88C17.68 2.28 16.16 0 13.016 0h-1z"/></svg>`
      };

      const TAB_ICONS = {
        core: `<svg style="color:#FBBF24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>`,
        backend: `<svg style="color:#A5B4FC" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>`,
        rag: `<svg style="color:#38BDF8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>`,
        frontend: `<svg style="color:#FBBF24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>`,
        devops: `<svg style="color:#34D399" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10l8 4 8-4V7l-8-4-8 4zM4 7l8 4m0 0l8-4m-8 4v10"/></svg>`
      };

      const CATEGORIES = [
        {
          id: 'core',
          title: IS_VI ? 'Công nghệ Cốt lõi (Top 10)' : 'Core Stack (Top 10)',
          hubName: 'Core Stack',
          icon: TAB_ICONS.core,
          items: [
            { name: 'Java', icon: ICONS.java, proof: 'Laundry System', desc: IS_VI ? 'Spring Boot REST API & máy trạng thái vòng đời đơn hàng' : 'Spring Boot REST APIs & finite state order lifecycle' },
            { name: 'PHP', icon: ICONS.php, proof: 'Portfolio & CMS', desc: IS_VI ? 'Laravel 11, Blade SSR, sitemap động & SQLite/MySQL' : 'Laravel 11, Blade SSR, dynamic sitemaps & SQLite/MySQL' },
            { name: 'Python', icon: ICONS.python, proof: 'TourRist RAG', desc: IS_VI ? 'FastAPI async gateway & pipeline xử lý tài liệu' : 'FastAPI async gateway & document ingestion pipeline' },
            { name: 'Odoo ERP', icon: ICONS.odoo, proof: 'Enmasys ERP', desc: IS_VI ? 'Cấu hình module Bán lẻ, Kế toán & Sales B2B' : 'Custom addons, ORM models & corporate sales flow' },
            { name: 'RAG AI', icon: ICONS.rag, proof: 'ChromaDB Search', desc: IS_VI ? 'Tìm kiếm vector cosine & prompt chống ảo giác LLM' : 'Cosine semantic lookup & zero-hallucination prompts' },
            { name: 'ReactJS', icon: ICONS.react, proof: 'Web Interfaces', desc: IS_VI ? 'Component tái sử dụng & cổng quản trị web' : 'Modular components, responsive reactive portals' },
            { name: 'Tailwind', icon: ICONS.tailwind, proof: 'UI Systems', desc: IS_VI ? 'Design tokens, bố cục bento & độ tương phản cao' : 'Editorial token system, high-contrast UI & grids' },
            { name: 'PostgreSQL', icon: ICONS.postgres, proof: 'AIPower DB', desc: IS_VI ? 'Lược đồ JSONB, tối ưu truy vấn & ACID' : 'JSONB schema, indexed queries & ACID transactions' },
            { name: 'Docker', icon: ICONS.docker, proof: 'DevOps Stacks', desc: IS_VI ? 'Điều phối Docker Compose đa container' : 'Compose orchestration for multi-container microservices' },
            { name: 'Figma', icon: ICONS.figma, proof: 'Design Award', desc: IS_VI ? 'Giải Nhì Thiết kế Logo Khoa CNTT UTH' : '2nd Prize UTH Design Award, typography & brand identity' }
          ]
        },
        {
          id: 'backend',
          title: 'Backend & ERP',
          hubName: 'Backend & ERP',
          icon: TAB_ICONS.backend,
          items: [
            { name: 'Laravel 11', icon: ICONS.laravel, proof: 'Production CMS', desc: IS_VI ? 'Portfolio động song ngữ VI/EN & Sanctum auth' : 'Dynamic portfolio, bilingual VI/EN & Sanctum auth' },
            { name: 'Spring Boot', icon: ICONS.java, proof: 'Laundry App', desc: IS_VI ? 'Spring Data JPA, Hibernate & bảo mật JWT' : 'Spring Data JPA, Hibernate & JWT security guards' },
            { name: 'Odoo Python', icon: ICONS.odoo, proof: 'Enterprise ERP', desc: IS_VI ? 'Tự động hóa hóa đơn & quy trình bán hàng' : 'Automated invoice workflows & stock management' },
            { name: 'FastAPI', icon: ICONS.fastapi, proof: 'TourRist API', desc: IS_VI ? 'API Python bất đồng bộ tốc độ cao' : 'High-speed async Python REST endpoints' },
            { name: 'MySQL 8.0', icon: ICONS.mysql, proof: 'Normalized DB', desc: IS_VI ? 'Chuẩn hóa CSDL quan hệ 3NF & ràng buộc khóa' : 'Relational 3NF schemas with cascade constraints' },
            { name: 'JWT Security', icon: ICONS.rag, proof: 'RBAC Guards', desc: IS_VI ? 'Vòng đời token & phân quyền đa lớp' : 'Token lifecycle & multi-tier authorization layers' }
          ]
        },
        {
          id: 'rag',
          title: IS_VI ? 'RAG AI & Cơ sở dữ liệu' : 'RAG AI & Databases',
          hubName: 'RAG & Data',
          icon: TAB_ICONS.rag,
          items: [
            { name: 'ChromaDB', icon: ICONS.chromadb, proof: 'Vector Index', desc: IS_VI ? 'Vector embedding tài liệu & tìm kiếm k-NN' : 'Private doc embeddings & k-NN semantic search' },
            { name: 'Applied RAG', icon: ICONS.rag, proof: 'TourRist Chatbot', desc: IS_VI ? 'Chia đoạn văn bản & ràng buộc ngữ cảnh LLM' : 'Document chunking & grounded LLM generation' },
            { name: 'PostgreSQL', icon: ICONS.postgres, proof: 'Enterprise DB', desc: IS_VI ? 'Lưu trữ giao dịch & hỗ trợ JSONB' : 'Reliable transactional storage & JSONB support' },
            { name: 'MySQL 8.0', icon: ICONS.mysql, proof: 'Portfolio DB', desc: IS_VI ? 'Dữ liệu dự án, kỹ năng & chứng chỉ' : 'Relational data for projects, skills & certificates' },
            { name: 'SQLite', icon: ICONS.mysql, proof: 'Embedded Engine', desc: IS_VI ? 'Kiểm thử tự động tốc độ cao & chế độ WAL' : 'Fast in-memory tests & zero-config WAL database' },
            { name: 'Vector Search', icon: ICONS.rag, proof: 'Cosine Metric', desc: IS_VI ? 'Tra cứu ngữ nghĩa tức thì trên tài liệu' : 'Dense embeddings for instant semantic document lookup' }
          ]
        },
        {
          id: 'frontend',
          title: 'Frontend & UI/UX',
          hubName: 'Frontend / UI',
          icon: TAB_ICONS.frontend,
          items: [
            { name: 'React 18', icon: ICONS.react, proof: 'Web Interfaces', desc: IS_VI ? 'Functional hooks & quản lý trạng thái sạch sẽ' : 'Functional hooks, clean state machines & portals' },
            { name: 'Tailwind CSS', icon: ICONS.tailwind, proof: 'Editorial UI', desc: IS_VI ? 'Hệ thống token & lưới đáp ứng trên di động' : 'High-contrast design tokens & fluid mobile grids' },
            { name: 'Blade SSR', icon: ICONS.laravel, proof: 'Laravel Core', desc: IS_VI ? 'Component SSR tối giản không độ trễ' : 'Modular server-rendered components with zero bloat' },
            { name: 'JavaScript', icon: ICONS.rag, proof: 'Trig Orbit Wheel', desc: IS_VI ? 'Lượng giác quỹ đạo & vi tương tác DOM' : 'Dynamic trigonometric radial math & interactions' },
            { name: 'Figma Design', icon: ICONS.figma, proof: 'Brand Identity', desc: IS_VI ? 'Bố cục editorial grid & khả năng tiếp cận WCAG' : 'Award-winning Swiss grid layouts & WCAG accessibility' },
            { name: 'Next.js', icon: ICONS.react, proof: 'Laundry Client', desc: IS_VI ? 'Cổng khách hàng theo dõi đơn & SSR' : 'SSR and customer pickup booking self-service' }
          ]
        },
        {
          id: 'devops',
          title: IS_VI ? 'DevOps & Kiến trúc' : 'DevOps & Architecture',
          hubName: 'DevOps & Infra',
          icon: TAB_ICONS.devops,
          items: [
            { name: 'Docker', icon: ICONS.docker, proof: 'Containerization', desc: IS_VI ? 'Multi-stage Dockerfile & cô lập dịch vụ' : 'Multi-stage Dockerfiles & isolated compose services' },
            { name: 'Nginx', icon: ICONS.nginx, proof: 'Live Server', desc: IS_VI ? 'Reverse proxy, SSL & định tuyến HTTP/2' : 'Reverse proxy, SSL termination & HTTP/2 routing' },
            { name: 'Git & GitHub', icon: ICONS.git, proof: 'Clean Branches', desc: IS_VI ? 'Commit semantic & quy trình nhánh sạch sẽ' : 'Semantic commits & feature PR workflows' },
            { name: 'Linux / Bash', icon: ICONS.linux, proof: 'Daemon Cron', desc: IS_VI ? 'Queue worker lập lịch & tự động sitemap' : 'Scheduled queue workers & automated sitemaps' },
            { name: 'cPanel Host', icon: ICONS.rag, proof: 'hothanhthien.io.vn', desc: IS_VI ? 'Cấu hình DNS, CSDL & cron production' : 'Production DNS, database & cron configuration' }
          ]
        }
      ];

      let currentCatIndex = 0;

      const categoryTabs = document.getElementById('radialCategoryTabs');
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
          btn.className = index === currentCatIndex ? 'orbit-tab-btn is-active' : 'orbit-tab-btn';
          btn.innerHTML = `${cat.icon} <span>${cat.title}</span>`;
          btn.addEventListener('click', () => {
            if (currentCatIndex !== index) {
              currentCatIndex = index;
              renderTabs();
              renderWheel();
            }
          });
          categoryTabs.appendChild(btn);
        });
      }

      function renderWheel() {
        const cat = CATEGORIES[currentCatIndex];
        const items = cat.items;
        const N = items.length;
        const radiusLabel = window.innerWidth <= 640 ? 'R=124px' : 'R=168px';

        hubTitle.textContent = cat.hubName;
        hubCount.textContent = `${N} ${IS_VI ? 'Kỹ năng' : 'Skills'}`;
        coordReadout.textContent = `${radiusLabel} • ${N} SKILLS`;

        orbitNodesContainer.innerHTML = '';

        items.forEach((item, index) => {
          const angle = Math.round((360 / N) * index);

          const node = document.createElement('div');
          node.className = 'orbit-node';
          node.style.setProperty('--angle', `${angle}deg`);
          node.style.setProperty('--delay', `${index * 65}ms`);

          node.innerHTML = `
            <div class="flip-card-inner">
              <div class="flip-card-front">
                <span class="orbit-node-icon">${item.icon}</span>
                <span class="orbit-node-label">${item.name}</span>
              </div>
              <div class="flip-card-back">
                <span class="flip-back-proof">${item.proof}</span>
                <span class="flip-back-sub">${item.name}</span>
              </div>
            </div>
          `;

          const updateProof = () => {
            proofSkillName.textContent = `${item.name}:`;
            proofDetail.textContent = `${item.proof} — ${item.desc}`;
          };

          node.addEventListener('mouseenter', updateProof);
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
        renderWheel();
        setTimeout(() => btnSpinAgain.classList.remove('spin-active'), 650);
      });

      renderTabs();
      renderWheel();
    })();
  </script>
</div>
