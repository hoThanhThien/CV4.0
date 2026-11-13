{{-- Interactive 3D Orbit Skill Wheel & Inspector Component --}}
<div id="orbitSkillSection" class="orbit-skill-wrapper w-full my-8">
  <div class="glass-card rounded-3xl p-5 sm:p-8 border border-slate-200/80 bg-slate-50/90 shadow-sm">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-5 border-b border-slate-200/60">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-mono font-semibold tracking-wider uppercase bg-indigo-50 border border-indigo-200/70 text-indigo-700 mb-2">
          <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
          {{ app()->getLocale() === 'vi' ? 'Trực quan hóa Kỹ năng 3D' : 'Interactive 3D Skill Visualizer' }}
        </div>
        <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: var(--font-display);">
          {{ app()->getLocale() === 'vi' ? 'Vòng quay Kỹ năng & Minh chứng Thực chiến' : 'Skill Wheel & Production Proof' }}
        </h3>
        <p class="text-xs sm:text-sm text-slate-600 mt-1">
          {{ app()->getLocale() === 'vi' ? 'Vị trí quỹ đạo tính theo lượng giác với chuyển động xoáy ốc Vortex. Rê chuột hoặc chạm vào node để xem dự án thực tế.' : 'Trigonometric radial orbital positioning with vortex spin-out physics. Hover or tap any skill node to inspect verified project proofs.' }}
        </p>
      </div>

      <!-- Spin Again Button -->
      <button id="btnOrbitReplay" type="button" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-mono font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition-all active:scale-95 shadow-sm self-start sm:self-auto">
        <svg class="w-3.5 h-3.5 text-indigo-600 transition-transform" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <span>{{ app()->getLocale() === 'vi' ? 'Quay lại' : 'Spin Again' }}</span>
      </button>
    </div>

    <!-- Category Filter Tabs -->
    <div id="orbitCategoryTabs" class="flex flex-wrap items-center gap-2 mb-8">
      <!-- Injected by JavaScript -->
    </div>

    <!-- Dual Column Orbit + Inspector -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
      
      <!-- Left Column: Radial Orbit Visualizer -->
      <div class="lg:col-span-7 bg-white/80 rounded-2xl p-4 sm:p-6 border border-slate-200/80 relative overflow-hidden flex flex-col items-center justify-center min-h-[440px]">
        <div class="absolute top-4 left-4 font-mono text-[11px] text-slate-400">
          ORBIT // RADIAL_TRIG_V3
        </div>
        <div class="absolute bottom-4 left-4 flex items-center gap-2 font-mono text-[11px] text-slate-400">
          <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
          <span id="bladeOrbitCoords">R=168px • N=5</span>
        </div>
        <div class="absolute top-4 right-4 font-mono text-[11px] text-slate-400">
          {{ app()->getLocale() === 'vi' ? 'RÊ CHUỘT ĐỂ XEM' : 'HOVER TO INSPECT' }}
        </div>

        <div class="relative w-[340px] h-[340px] sm:w-[420px] sm:h-[420px] flex items-center justify-center">
          <!-- Concentric Orbit Ring 1 (Outer Dashed) -->
          <div class="absolute w-[290px] h-[290px] sm:w-[360px] sm:h-[360px] rounded-full border border-dashed border-indigo-200/60 pointer-events-none animate-spin" style="animation-duration: 75s;"></div>
          
          <!-- Concentric Orbit Ring 2 (Inner Guide) -->
          <div class="absolute w-[210px] h-[210px] sm:w-[260px] sm:h-[260px] rounded-full border border-dashed border-slate-300/60 pointer-events-none"></div>

          <!-- Crosshairs -->
          <div class="absolute inset-x-8 top-1/2 h-[1px] bg-slate-200/50 pointer-events-none"></div>
          <div class="absolute inset-y-8 left-1/2 w-[1px] bg-slate-200/50 pointer-events-none"></div>

          <!-- Central Circular Hub -->
          <div class="relative z-20 w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-white/95 border-2 border-indigo-500/30 shadow-lg flex flex-col items-center justify-center text-center p-2 transition-transform hover:scale-105 group cursor-default">
            <div class="absolute -inset-1 rounded-full bg-gradient-to-tr from-indigo-500/20 via-sky-400/20 to-emerald-400/20 blur-sm pointer-events-none"></div>
            
            <div class="relative z-10 space-y-1">
              <span id="bladeHubCategoryBadge" class="inline-block px-2 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700">
                Core Stack
              </span>
              <div id="bladeHubCategoryName" class="text-xs sm:text-sm font-bold text-slate-800 leading-tight line-clamp-2 px-1" style="font-family: var(--font-display);">
                Primary Stack
              </div>
              <div id="bladeHubSkillCount" class="text-[11px] font-mono text-slate-500 font-semibold">
                5 SKILLS
              </div>
            </div>
          </div>

          <!-- Orbiting Skill Pills Container -->
          <div id="bladeOrbitWheelContainer" class="absolute inset-0 pointer-events-auto">
            <!-- Dynamically populated with .orbit-node elements -->
          </div>
        </div>
      </div>

      <!-- Right Column: Interactive Skill Inspector Card -->
      <div class="lg:col-span-5 bg-white/95 rounded-2xl p-6 sm:p-7 border border-slate-200/80 shadow-sm flex flex-col justify-between">
        <div class="space-y-5">
          <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
              <span id="bladeInspectDomain" class="text-xs font-mono uppercase tracking-wider text-slate-500 font-bold">
                Backend Architecture
              </span>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-mono font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
              <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
              </svg>
              {{ app()->getLocale() === 'vi' ? 'Đã kiểm chứng' : 'Production Verified' }}
            </span>
          </div>

          <!-- Logo & Title -->
          <div class="flex items-center gap-4">
            <div id="bladeInspectLogoBox" class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center p-2.5 flex-shrink-0">
              <!-- SVG Icon -->
            </div>
            <div>
              <h4 id="bladeInspectSkillName" class="text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: var(--font-display);">
                Laravel 11
              </h4>
              <p id="bladeInspectTagline" class="text-xs font-medium text-slate-500">
                Enterprise PHP Framework &amp; Robust Web Architecture
              </p>
            </div>
          </div>

          <!-- Proficiency Progress Bar -->
          <div class="space-y-1.5 bg-slate-50 p-3.5 rounded-2xl border border-slate-200/60">
            <div class="flex items-center justify-between text-xs font-mono font-semibold">
              <span class="text-slate-600">{{ app()->getLocale() === 'vi' ? 'Độ thành thạo thực chiến' : 'Engineering Proficiency' }}</span>
              <span id="bladeInspectPercent" class="text-indigo-600 font-bold text-sm">95%</span>
            </div>
            <div class="w-full h-2.5 rounded-full bg-slate-200 overflow-hidden relative">
              <div id="bladeInspectProgressBar" class="h-full rounded-full bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-400" style="width: 95%; transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1);"></div>
            </div>
            <div class="flex items-center justify-between text-[11px] font-mono text-slate-400 pt-0.5">
              <span>{{ app()->getLocale() === 'vi' ? 'Kinh nghiệm thực chiến' : 'Production Experience' }}</span>
              <span id="bladeInspectYears">3+ Years Practical</span>
            </div>
          </div>

          <!-- Strengths -->
          <div class="space-y-1.5">
            <label class="text-[11px] font-mono uppercase tracking-wider text-slate-400 font-bold">
              {{ app()->getLocale() === 'vi' ? 'Thế mạnh kỹ thuật then chốt' : 'Core Technical Strengths' }}
            </label>
            <div id="bladeInspectChips" class="flex flex-wrap gap-1.5">
              <!-- Chips -->
            </div>
          </div>

          <!-- Real World Project Proof -->
          <div class="space-y-2">
            <label class="text-[11px] font-mono uppercase tracking-wider text-slate-400 font-bold flex items-center gap-1.5">
              <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
              </svg>
              {{ app()->getLocale() === 'vi' ? 'Minh chứng Dự án Thực tế' : 'Real-World Production Proof' }}
            </label>
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 shadow-sm space-y-1.5">
              <div class="flex items-center justify-between">
                <span id="bladeInspectProofProject" class="text-xs font-bold text-slate-900 font-mono">
                  Personal Portfolio &amp; CMS
                </span>
                <span id="bladeInspectProofRole" class="text-[10px] font-mono font-medium px-2 py-0.5 rounded bg-indigo-50 text-indigo-700">
                  Fullstack Lead
                </span>
              </div>
              <p id="bladeInspectProofDesc" class="text-xs text-slate-600 leading-relaxed">
                Engineered full-stack CMS with dynamic Blade SSR, RESTful API endpoints, SQLite/MySQL support, and dynamic sitemaps.
              </p>
            </div>
          </div>
        </div>

        <div class="pt-4 mt-6 border-t border-slate-100 flex items-center justify-between text-[11px] font-mono text-slate-400">
          <span>{{ app()->getLocale() === 'vi' ? 'Tương tác trực tiếp trên vòng quay' : 'Interactive node selection' }}</span>
          <span class="text-indigo-600 font-semibold">{{ app()->getLocale() === 'vi' ? 'Bảng soi kỹ năng trực tiếp' : 'Active Inspector' }}</span>
        </div>
      </div>

    </div>
  </div>
</div>

