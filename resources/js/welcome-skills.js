const SKILLS_CSS = `:root{--radius:184px;--orbit-scale:1}
.orbit-canvas-viewport{position:relative;width:100%;height:calc(468px * var(--orbit-scale,1))}
.orbit-canvas{position:absolute;left:50%;top:50%;width:486px;height:468px;--radius:184px;transform:translate(-50%,-50%) scale(var(--orbit-scale,1));transform-origin:center center}
@keyframes skillVortexSpinIn{0%{opacity:0;transform:translateY(14px) scale(.86)}100%{opacity:1;transform:none}}
@keyframes iconSpin360{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
.skill-grid-item{perspective:1100px;-webkit-perspective:1100px;height:152px;transition:transform .25s ease,opacity .22s ease;touch-action:manipulation;-webkit-tap-highlight-color:transparent;user-select:none}
.skill-grid-item.is-animating{opacity:0;animation:skillVortexSpinIn .65s cubic-bezier(.34,1.56,.64,1) forwards;animation-delay:calc(var(--index,0) * 55ms)}
.skill-grid-item.is-exiting{opacity:0 !important;transform:rotateY(90deg) scale(.65) !important;animation:none !important}
.skill-grid-item.is-animating .spin-icon-target{animation:iconSpin360 .8s cubic-bezier(.34,1.56,.64,1) forwards;animation-delay:calc(var(--index,0) * 55ms)}
.grid-flip-inner{position:relative;width:100%;height:100%;transform-style:preserve-3d;-webkit-transform-style:preserve-3d;transition:transform .62s cubic-bezier(.16,1,.3,1);cursor:pointer;will-change:transform}
@media (hover:hover) and (pointer:fine){#skillGridModeWrap:not(.is-touch-input) .skill-grid-item:hover .grid-flip-inner{transform:rotateY(180deg)}}
.skill-grid-item.is-flipped .grid-flip-inner{transform:rotateY(180deg)}
.grid-flip-front,.grid-flip-back{position:absolute;inset:0;backface-visibility:hidden;-webkit-backface-visibility:hidden;border-radius:1rem}
.grid-flip-front{transform:rotateY(0deg) translateZ(1px);-webkit-transform:rotateY(0deg) translateZ(1px);z-index:2;pointer-events:auto}
.grid-flip-back{transform:rotateY(180deg) translateZ(1px);-webkit-transform:rotateY(180deg) translateZ(1px);z-index:1;pointer-events:none}
.skill-grid-item.is-flipped .grid-flip-front{pointer-events:none}
.skill-grid-item.is-flipped .grid-flip-back{pointer-events:auto}
@media (hover:hover) and (pointer:fine){#skillGridModeWrap:not(.is-touch-input) .skill-grid-item:hover .grid-flip-front{pointer-events:none}#skillGridModeWrap:not(.is-touch-input) .skill-grid-item:hover .grid-flip-back{pointer-events:auto}}
@keyframes orbitNodeEmerge{0%{opacity:0;transform:translate(-50%,-50%) rotate(calc(var(--angle) - 160deg)) translateY(0px) scale(.2)}100%{opacity:1;transform:translate(-50%,-50%) rotate(var(--angle)) translateY(calc(-1 * var(--radius))) rotate(calc(-1 * var(--angle))) scale(1)}}
@keyframes orbitStageRevolve{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
@keyframes orbitNodeCounterRevolve{from{transform:rotate(0deg)}to{transform:rotate(-360deg)}}
.orbit-spinning-ring{transform-origin:50% 50%;animation:orbitStageRevolve 32s linear infinite}
.orbit-node{position:absolute;top:50%;left:50%;opacity:0;animation:orbitNodeEmerge .8s cubic-bezier(.34,1.56,.64,1) forwards;user-select:none;cursor:pointer;pointer-events:auto;z-index:20}
.orbit-node-upright{transform-origin:center center;animation:orbitNodeCounterRevolve 32s linear infinite}
.orbit-ring-counter{transform-origin:center center;animation:orbitNodeCounterRevolve 48s linear infinite}
#orbitStageBox.is-hover-paused .orbit-spinning-ring,#orbitStageBox.is-hover-paused .orbit-node-upright,#orbitStageBox.is-hover-paused .animate-spin-slow,#orbitStageBox.is-hover-paused .orbit-ring-counter,#orbitStageBox.is-manual-paused .orbit-spinning-ring,#orbitStageBox.is-manual-paused .orbit-node-upright,#orbitStageBox.is-manual-paused .animate-spin-slow,#orbitStageBox.is-manual-paused .orbit-ring-counter{animation-play-state:paused}
.orbit-node-pill{transition:all .2s cubic-bezier(.16,1,.3,1)}
.orbit-node:hover .orbit-node-pill,.orbit-node.is-active .orbit-node-pill{border-color:#0ea5e9;box-shadow:0 10px 25px -4px rgba(14,165,233,.28),0 0 0 2px rgba(14,165,233,.22);transform:scale(1.08)}
.orbit-node:hover,.orbit-node.is-active{z-index:40}
.replay-spinning svg{animation:spin .6s cubic-bezier(.16,1,.3,1)}`;

export function injectSkillsStyles() {
  if (document.getElementById('pearl-skills-css')) return;
  const st = document.createElement('style');
  st.id = 'pearl-skills-css';
  st.textContent = SKILLS_CSS;
  document.head.appendChild(st);
}

export function createSkillsController({ getLang, escapeHtml, categories, customBrandSvgs, ensureBrandSvgs }) {
  let currentCategory = 0;
  let currentMode = 'grid';
  let activeOrbitIdx = 0;
  let isManualPaused = false;
  let resizeBound = false;

  function renderSkillIcon(item, variant = 'grid') {
    const iconKey = item.icon || '';
    const boxSizes = {
      grid: { svg: 'w-6 h-6', odoo: 'w-8 h-5', orbitText: '', lucide: 'w-5 h-5' },
      orbit: { svg: 'w-4 h-4', odoo: 'w-6 h-3.5', orbitText: 'text-base sm:text-lg', lucide: 'w-3.5 h-3.5' },
      inspector: { svg: 'w-8 h-8', odoo: 'w-11 h-6', orbitText: '', lucide: 'w-7 h-7' }
    }[variant] || { svg: 'w-6 h-6', odoo: 'w-8 h-5', orbitText: '', lucide: 'w-5 h-5' };

    if (customBrandSvgs[iconKey]) {
      return customBrandSvgs[iconKey]
        .replace(/__SZ__/g, boxSizes.svg)
        .replace(/__ODOO__/g, boxSizes.odoo);
    }

    if (iconKey.startsWith('lucide:')) {
      const lucideName = iconKey.slice(7);
      return `<i data-lucide="${escapeHtml(lucideName)}" class="${boxSizes.lucide} text-sky-700 dark:text-sky-400"></i>`;
    }

    const cls = boxSizes.orbitText ? `${iconKey} ${boxSizes.orbitText}` : iconKey;
    return `<i class="${escapeHtml(cls)}"></i>`;
  }

  function updateOrbitSpinLabel() {
    const lang = getLang();
    const label = document.getElementById('orbitSpinToggleLabel');
    const pauseIcon = document.getElementById('orbitSpinPauseIcon');
    const playIcon = document.getElementById('orbitSpinPlayIcon');
    if (pauseIcon) pauseIcon.classList.toggle('hidden', isManualPaused);
    if (playIcon) playIcon.classList.toggle('hidden', !isManualPaused);
    if (label) {
      label.textContent = isManualPaused
        ? (lang === 'vi' ? 'Tạm dừng' : 'Paused')
        : (lang === 'vi' ? 'Đang xoay' : 'Auto Orbit');
    }
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

  function bindOrbitResize() {
    if (resizeBound) return;
    resizeBound = true;
    const orbitBox = document.getElementById('orbitStageBox');
    if (orbitBox && typeof ResizeObserver !== 'undefined') {
      const ro = new ResizeObserver(() => {
        if (currentMode === 'orbit') {
          const items = (categories[currentCategory] && categories[currentCategory].items) || [];
          syncOrbitGeometry(items.length);
        }
      });
      ro.observe(orbitBox);
    } else {
      window.addEventListener('resize', () => {
        if (currentMode === 'orbit') {
          const items = (categories[currentCategory] && categories[currentCategory].items) || [];
          syncOrbitGeometry(items.length);
        }
      });
    }
  }

  function updateOrbitInspector(item) {
    const lang = getLang();
    const iconBox = document.getElementById('inspectorIconBox');
    if (iconBox) iconBox.innerHTML = renderSkillIcon(item, 'inspector');
    const nameEl = document.getElementById('inspectorSkillName');
    if (nameEl) nameEl.textContent = item.name;
    const domainTxt = (item.domain && (item.domain[lang] || item.domain.en)) || '';
    const projectTxt = (item.project && (item.project[lang] || item.project.en)) || '';
    const descTxt = (item.desc && (item.desc[lang] || item.desc.en)) || '';
    const domEl = document.getElementById('inspectorDomain');
    if (domEl) domEl.textContent = domainTxt;
    const scopeEl = document.getElementById('inspectorScopeVal');
    if (scopeEl) scopeEl.textContent = domainTxt;
    const titleEl = document.getElementById('inspectorProjectTitle');
    if (titleEl) titleEl.textContent = projectTxt;
    const descEl = document.getElementById('inspectorProjectDesc');
    if (descEl) descEl.textContent = descTxt;
    if (window.lucide && iconBox) lucide.createIcons({ root: iconBox });
  }

  function render3DOrbitWheel(cat, items) {
    bindOrbitResize();
    const stageBox = document.getElementById('orbitStageBox');
    const stage = document.getElementById('orbitNodesStage');
    if (!stage) return;
    const N = items.length;
    const lang = getLang();

    const hubCat = document.getElementById('orbitHubCategory');
    const hubCount = document.getElementById('orbitHubCount');
    if (hubCat) hubCat.textContent = (cat.hubName && (cat.hubName[lang] || cat.hubName.en)) || '';
    if (hubCount) hubCount.textContent = lang === 'vi' ? `${N} Kỹ năng` : `${N} Skills`;

    if (stageBox) stageBox.classList.remove('is-hover-paused');

    stage.innerHTML = '';
    syncOrbitGeometry(N);
    requestAnimationFrame(() => syncOrbitGeometry(N));
    if (stage.classList.contains('orbit-spinning-ring')) {
      stage.classList.remove('orbit-spinning-ring');
      void stage.offsetWidth;
    }
    stage.classList.add('orbit-spinning-ring');

    items.forEach((item, index) => {
      const angle = Math.round((360 / items.length) * index);
      const node = document.createElement('div');
      node.className = `orbit-node ${index === activeOrbitIdx ? 'is-active' : ''}`;
      node.style.setProperty('--angle', `${angle}deg`);
      node.style.animationDelay = `${index * 65}ms`;

      node.innerHTML = `
        <div class="orbit-node-upright">
          <div class="orbit-node-pill px-2 py-1 rounded-xl bg-white dark:bg-slate-800/95 border border-slate-200/90 dark:border-slate-600/80 shadow-md flex items-center gap-1">
            ${renderSkillIcon(item, 'orbit')}
            <span class="text-xs font-bold tracking-tight text-slate-800 dark:text-slate-100 whitespace-nowrap">${escapeHtml(item.name)}</span>
          </div>
        </div>
      `;

      const handleInspect = () => {
        activeOrbitIdx = index;
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

    if (items[activeOrbitIdx]) updateOrbitInspector(items[activeOrbitIdx]);
    updateOrbitSpinLabel();
    if (window.lucide) lucide.createIcons({ root: stage });
  }

  function render3DSpinFlipGrid(items, skipLucide = false) {
    const wrap = document.getElementById('skillGridModeWrap');
    if (!wrap) return;
    wrap.innerHTML = '';
    if (!wrap.dataset.touchBound) {
      wrap.dataset.touchBound = '1';
      wrap.addEventListener('pointerdown', (e) => {
        wrap.classList.toggle('is-touch-input', e.pointerType === 'touch' || e.pointerType === 'pen');
      }, { passive: true });
    }
    const lang = getLang();

    function appendCard(item, index) {
      const card = document.createElement('div');
      card.className = skipLucide ? 'skill-grid-item' : 'skill-grid-item is-animating';
      card.style.setProperty('--index', index);
      if (!skipLucide) {
        card.style.animationDelay = `${index * 45}ms`;
        card.addEventListener('animationend', (ev) => {
          if (ev.target === card) card.classList.remove('is-animating');
        }, { once: true });
        setTimeout(() => card.classList.remove('is-animating'), 850 + index * 55);
      }

      const domainTxt = (item.domain && (item.domain[lang] || item.domain.en)) || '';
      const projectTxt = (item.project && (item.project[lang] || item.project.en)) || '';
      const descTxt = (item.desc && (item.desc[lang] || item.desc.en)) || '';

      card.innerHTML = `
        <div class="grid-flip-inner">
          <div class="grid-flip-front bg-white dark:bg-slate-950/90 border border-slate-200/80 dark:border-slate-800 hover:border-sky-300 dark:hover:border-sky-500/50 p-4 flex flex-col justify-between shadow-sm">
            <div class="flex items-start justify-between relative z-10">
              <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-center text-2xl shadow-sm spin-icon-target">
                ${renderSkillIcon(item, 'grid')}
              </div>
              <span class="font-mono text-xs font-semibold text-slate-600 dark:text-slate-400 bg-slate-100/80 dark:bg-slate-800/80 border border-slate-200/70 dark:border-slate-700/70 px-2 py-0.5 rounded-md">↻ 3D</span>
            </div>
            <div class="relative z-10 text-left font-display font-bold text-sm text-slate-900 dark:text-white">${escapeHtml(item.name)}<span class="block font-mono font-normal text-xs text-slate-600 dark:text-slate-400 truncate mt-0.5">${escapeHtml(domainTxt)}</span></div>
          </div>
          <div class="grid-flip-back bg-gradient-to-br from-slate-900 via-slate-800 to-sky-950 text-white p-4 flex flex-col justify-between border border-sky-400/40 shadow-lg">
            <div class="font-mono text-xs uppercase tracking-wider text-sky-300 font-bold leading-snug line-clamp-1 text-left">${escapeHtml(projectTxt)}</div>
            <p class="text-xs text-slate-200 leading-relaxed line-clamp-3 text-left">${escapeHtml(descTxt)}</p>
            <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-700/70 font-mono text-xs">
              <span class="text-emerald-300 font-semibold whitespace-nowrap">● ${lang === 'vi' ? 'Đã áp dụng' : 'Production'}</span>
              <span class="text-sky-300 font-bold truncate">${escapeHtml(item.name)}</span>
            </div>
          </div>
        </div>
      `;

      card.addEventListener('click', () => card.classList.toggle('is-flipped'));
      wrap.appendChild(card);
    }

    if (skipLucide && items.length > 2) {
      let idx = 0;
      function nextCardSlice() {
        const end = Math.min(idx + 2, items.length);
        for (; idx < end; idx++) appendCard(items[idx], idx);
        if (idx < items.length) setTimeout(nextCardSlice, 0);
      }
      nextCardSlice();
      return;
    }

    items.forEach(appendCard);
    if (!skipLucide && window.lucide) lucide.createIcons({ root: wrap });
  }

  function renderTabs(skipLucide = false) {
    const container = document.getElementById('skillCategoryTabs');
    if (!container) return;
    container.innerHTML = '';
    const lang = getLang();

    categories.forEach((cat, idx) => {
      const isActive = idx === currentCategory;
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = isActive
        ? 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-sky-700 text-white shadow-lg shadow-sky-700/20 flex items-center gap-2 transition-all'
        : 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center gap-2 transition-all';

      const iconColor = isActive ? 'text-white' : escapeHtml(cat.iconColor);
      const tabLabel = (cat.label && (cat.label[lang] || cat.label.en)) || '';
      btn.innerHTML = `<i data-lucide="${escapeHtml(cat.icon)}" class="w-3.5 h-3.5 ${iconColor}"></i><span>${escapeHtml(tabLabel)}</span>`;
      btn.addEventListener('click', () => {
        if (currentCategory !== idx) switchCategoryAnimated(idx);
      });
      container.appendChild(btn);
    });

    if (!skipLucide && window.lucide) lucide.createIcons({ root: container });
  }

  function renderView(skipLucide = false) {
    const cat = categories[currentCategory];
    if (!cat) return;
    const items = cat.items || [];
    const metaEl = document.getElementById('activeSkillCountMeta');
    if (metaEl) metaEl.textContent = `${items.length} SKILLS`;

    if (currentMode === 'grid') {
      render3DSpinFlipGrid(items, skipLucide);
    } else {
      render3DOrbitWheel(cat, items);
    }
  }

  function switchCategoryAnimated(nextIndex) {
    ensureBrandSvgs(() => {
      if (currentMode === 'grid') {
        const oldCards = document.querySelectorAll('#skillGridModeWrap .skill-grid-item');
        oldCards.forEach((c) => c.classList.add('is-exiting'));
        setTimeout(() => {
          currentCategory = nextIndex;
          activeOrbitIdx = 0;
          renderTabs();
          renderView();
        }, 210);
      } else {
        currentCategory = nextIndex;
        activeOrbitIdx = 0;
        renderTabs();
        renderView();
      }
    });
  }

  function switchMode(mode, shouldRender = true) {
    currentMode = mode;
    const btnGrid = document.getElementById('btnModeGrid');
    const btnOrbit = document.getElementById('btnModeOrbit');
    const gridWrap = document.getElementById('skillGridModeWrap');
    const orbitWrap = document.getElementById('skillOrbitModeWrap');
    if (!btnGrid || !btnOrbit || !gridWrap || !orbitWrap) return;

    if (mode === 'grid') {
      btnGrid.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 bg-sky-700 text-white shadow-sm transition-all';
      btnOrbit.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all';
      gridWrap.classList.remove('hidden');
      orbitWrap.classList.add('hidden');
    } else {
      btnOrbit.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 bg-sky-700 text-white shadow-sm transition-all';
      btnGrid.className = 'px-2.5 sm:px-3 py-1.5 rounded-lg font-semibold flex items-center gap-1.5 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all';
      gridWrap.classList.add('hidden');
      orbitWrap.classList.remove('hidden');
    }
    if (shouldRender) ensureBrandSvgs(() => renderView());
  }

  function replay() {
    const btn = document.getElementById('btnSkillReplay');
    if (btn) btn.classList.add('replay-spinning');
    ensureBrandSvgs(() => renderView());
    if (btn) setTimeout(() => btn.classList.remove('replay-spinning'), 620);
  }

  function toggleSpin() {
    const stageBox = document.getElementById('orbitStageBox');
    isManualPaused = !isManualPaused;
    if (stageBox) stageBox.classList.toggle('is-manual-paused', isManualPaused);
    updateOrbitSpinLabel();
  }

  return {
    hydrate(isInitial = true) {
      injectSkillsStyles();
      renderTabs(isInitial);
      ensureBrandSvgs(() => {
        setTimeout(() => renderView(isInitial), 0);
      });
    },
    selectTab(nextIndex) {
      if (currentCategory !== nextIndex) switchCategoryAnimated(nextIndex);
    },
    switchMode,
    replay,
    toggleSpin,
    updateOrbitSpinLabel,
    updateOrbitInspector
  };
}
