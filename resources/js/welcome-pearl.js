let typewriterTimer = null;
let copilotHistory = [];
let isCopilotBusy = false;
let activeCliKey = 'whoami';
let brandSvgsPromise = null;
let welcomeI18nPromise = null;
let welcomeSkillsPromise = null;
let loadedCliResponses = null;
let skillsController = null;
let copilotPromptsRendered = false;
let skillsHydrated = false;
let skillsScheduleAttached = false;
let hasPageI18nApplied = false;

function getLang() {
  return window.currentLang || document.documentElement.lang || 'en';
}

function escapeHtml(value) {
  return String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

const HOME_DATA = window.__PEARL_HOME__ || {};
const SKILL_CATEGORIES = Array.isArray(HOME_DATA.skills) ? HOME_DATA.skills : [];
const CUSTOM_BRAND_SVGS = Object.assign({}, HOME_DATA.brandSvgs || {});

function ensureBrandSvgs(cb) {
  if (Object.keys(CUSTOM_BRAND_SVGS).length > 0) {
    if (cb) cb();
    return;
  }
  if (!brandSvgsPromise) {
    brandSvgsPromise = import(/* webpackChunkName: "brand-svgs" */ './brand-svgs').then((m) => {
      Object.assign(CUSTOM_BRAND_SVGS, m.BRAND_SVGS || {});
    });
  }
  if (cb) brandSvgsPromise.then(cb);
}

function ensureWelcomeI18n(cb) {
  if (loadedCliResponses && window.PAGE_I18N) {
    if (cb) cb(loadedCliResponses);
    return;
  }
  if (!welcomeI18nPromise) {
    welcomeI18nPromise = import(/* webpackChunkName: "welcome-i18n" */ './welcome-i18n').then((m) => {
      window.PAGE_I18N = m.WELCOME_I18N || {};
      loadedCliResponses = m.CLI_RESPONSES || {};
      return loadedCliResponses;
    });
  }
  if (cb) welcomeI18nPromise.then(cb);
}

function ensureSkillsController(cb) {
  if (skillsController) {
    if (cb) cb(skillsController);
    return;
  }
  if (!welcomeSkillsPromise) {
    welcomeSkillsPromise = import(/* webpackChunkName: "welcome-skills" */ './welcome-skills').then((m) => {
      skillsController = m.createSkillsController({
        getLang,
        escapeHtml,
        categories: SKILL_CATEGORIES,
        customBrandSvgs: CUSTOM_BRAND_SVGS,
        ensureBrandSvgs
      });
      return skillsController;
    });
  }
  if (cb) welcomeSkillsPromise.then(cb);
}

function hydrateSkillsSection(isInitial = true) {
  if (skillsHydrated && isInitial) return;
  skillsHydrated = true;
  ensureSkillsController((ctrl) => ctrl.hydrate(isInitial));
}

function scheduleSkillsHydration() {
  if (skillsHydrated || skillsScheduleAttached) return;
  skillsScheduleAttached = true;

  const trigger = () => {
    if (!skillsHydrated) hydrateSkillsSection(true);
  };

  const skillsEl = document.getElementById('skills');
  if (skillsEl && typeof IntersectionObserver !== 'undefined') {
    const io = new IntersectionObserver((entries) => {
      if (entries.some((entry) => entry.isIntersecting)) {
        io.disconnect();
        trigger();
      }
    }, { rootMargin: '80px 0px' });
    io.observe(skillsEl);
  }

  if (skillsEl) {
    ['pointerdown', 'touchstart', 'focusin'].forEach((evt) => {
      skillsEl.addEventListener(evt, trigger, { once: true, passive: true });
    });
  }

  document.querySelectorAll('a[href="#skills"]').forEach((link) => {
    link.addEventListener('click', trigger, { once: true, passive: true });
  });

  const fallbackDelay = window.location.hash === '#skills' ? 50 : 3800;
  if (document.readyState === 'complete') {
    setTimeout(trigger, fallbackDelay);
  } else {
    window.addEventListener('load', () => setTimeout(trigger, fallbackDelay), { once: true });
  }
}

function runTerminalCmd(key, animate = true) {
  activeCliKey = key;
  document.querySelectorAll('.cli-cmd-btn').forEach((btn) => {
    const isActive = btn.getAttribute('data-cli-key') === key;
    btn.className = isActive
      ? 'cli-cmd-btn px-2.5 py-1 rounded-lg bg-sky-500/20 text-sky-300 border border-sky-400/40 font-mono text-xs font-semibold hover:bg-sky-700 hover:text-white transition-colors'
      : 'cli-cmd-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-200 border border-slate-700 font-mono text-xs font-semibold hover:border-sky-400 hover:text-white transition-colors';
  });

  ensureWelcomeI18n((cliResponses) => {
    const payload = cliResponses[key];
    if (!payload) return;

    const lang = getLang();
    const typedEl = document.getElementById('cliTypedCommand');
    const outputEl = document.getElementById('cliOutputBody');
    if (!typedEl || !outputEl) return;

    typedEl.textContent = payload.cmd;
    const text = payload[lang] || payload.en;

    if (typewriterTimer) clearInterval(typewriterTimer);

    if (!animate) {
      outputEl.textContent = text;
      const cursor = document.createElement('span');
      cursor.className = 'cli-cursor';
      outputEl.appendChild(cursor);
      return;
    }

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
  });
}

window.selectSkillTab = function (nextIndex) {
  skillsHydrated = true;
  ensureSkillsController((ctrl) => ctrl.selectTab(nextIndex));
};

function switchSkillMode(mode, shouldRender = true) {
  skillsHydrated = true;
  ensureSkillsController((ctrl) => ctrl.switchMode(mode, shouldRender));
}

function replaySkillsAnimation() {
  skillsHydrated = true;
  ensureSkillsController((ctrl) => ctrl.replay());
}

function toggleOrbitSpin() {
  skillsHydrated = true;
  ensureSkillsController((ctrl) => ctrl.toggleSpin());
}

function updateOrbitInspector(item) {
  ensureSkillsController((ctrl) => ctrl.updateOrbitInspector(item));
}

window.onPearlLangChange = function (lang, dict, isManual = true) {
  if (skillsController) skillsController.updateOrbitSpinLabel();
  const htmlLang = document.documentElement.lang || 'en';
  if (isManual) {
    ensureWelcomeI18n(() => {
      hydrateSkillsSection(false);
      if (copilotPromptsRendered) {
        setTimeout(() => renderCopilotPrompts(), 0);
      }
      runTerminalCmd(activeCliKey, true);
    });
    return;
  }

  if (lang !== htmlLang || (lang === 'vi' && !hasPageI18nApplied)) {
    ensureWelcomeI18n(() => {
      if (!hasPageI18nApplied && typeof window.setLanguage === 'function') {
        hasPageI18nApplied = true;
        window.setLanguage(lang, false);
        return;
      }
      if (lang !== htmlLang) {
        runTerminalCmd(activeCliKey, false);
      }
      scheduleSkillsHydration();
    });
    return;
  }

  scheduleSkillsHydration();
};

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

function toggleCopilot(forceOpen = null) {
  const panel = document.getElementById('ragCopilotPanel');
  if (!panel) return;
  if (forceOpen === true) {
    panel.classList.remove('hidden');
  } else if (forceOpen === false) {
    panel.classList.add('hidden');
  } else {
    panel.classList.toggle('hidden');
  }
  if (!panel.classList.contains('hidden') && !copilotPromptsRendered) {
    renderCopilotPrompts();
    copilotPromptsRendered = true;
  }
  if (window.lucide) lucide.createIcons({ root: panel });
}

function triggerSampleRagQuery() {
  const lang = getLang();
  const q = lang === 'vi'
    ? 'Giải thích kiến trúc RAG AI và công nghệ trong dự án TourRist dựa trên hồ sơ?'
    : 'Explain the TourRist RAG AI architecture and technologies used based on portfolio records.';
  toggleCopilot(true);
  askCopilotQuestion(q);
}

function renderCopilotPrompts() {
  const wrap = document.getElementById('copilotQuickPrompts');
  if (!wrap) return;
  const lang = getLang();
  wrap.innerHTML = '';
  COPILOT_QUICK_PROMPTS.forEach((item) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'px-2.5 py-1 rounded-lg bg-sky-50 dark:bg-sky-500/15 border border-sky-200/80 dark:border-sky-500/30 text-xs font-mono font-semibold text-sky-700 dark:text-sky-300 hover:bg-sky-700 hover:text-white transition-colors';
    btn.textContent = item.label[lang] || item.label.en;
    btn.addEventListener('click', () => askCopilotQuestion(item.query[lang] || item.query.en));
    wrap.appendChild(btn);
  });
}

function formatCopilotAnswerHtml(text) {
  let safe = escapeHtml(String(text || '').trim());
  safe = safe.replace(/\*\*(.+?)\*\*/g, '<strong class="font-semibold text-slate-900 dark:text-white">$1</strong>');
  safe = safe.replace(/\[(S\d+)\]/g, '<span class="inline-block px-1 py-0.5 mx-0.5 rounded bg-sky-100 dark:bg-sky-500/20 text-sky-700 dark:text-sky-300 font-mono text-xs font-bold">[$1]</span>');
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
  if (!input) return;
  const query = input.value.trim();
  if (query.length < 2) return;
  input.value = '';
  askCopilotQuestion(query);
}

function askCopilotQuestion(query) {
  if (isCopilotBusy) return;
  const box = document.getElementById('copilotMessages');
  if (!box) return;
  const lang = getLang();

  const uBubble = document.createElement('div');
  uBubble.className = 'ml-auto max-w-[85%] p-2.5 rounded-2xl rounded-tr-sm bg-sky-700 text-white font-medium shadow-sm';
  uBubble.textContent = query;
  box.appendChild(uBubble);

  const aBubble = document.createElement('div');
  aBubble.className = 'mr-auto max-w-[94%] p-3 rounded-2xl rounded-tl-sm bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-200 leading-relaxed shadow-sm space-y-2';

  const meta = document.createElement('div');
  meta.className = 'font-mono text-xs font-bold text-sky-700 dark:text-sky-400 flex items-center gap-1.5';
  meta.innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-sky-600 animate-ping"></span><span>${lang === 'vi' ? 'Đang truy xuất DB & gọi LLM...' : 'Retrieving DB corpus & querying LLM...'}</span>`;

  const body = document.createElement('div');
  body.className = 'text-xs leading-relaxed text-slate-600 dark:text-slate-400 italic';
  body.textContent = lang === 'vi' ? 'Đang tổng hợp ngữ cảnh có trích dẫn...' : 'Generating grounded answer...';

  aBubble.appendChild(meta);
  aBubble.appendChild(body);
  box.appendChild(aBubble);
  box.scrollTop = box.scrollHeight;

  setCopilotBusyState(true);

  fetch('/api/v1/copilot/ask', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({
      query,
      locale: lang,
      history: copilotHistory.slice(-4)
    })
  })
    .then((resp) => {
      if (resp.status === 429) {
        meta.className = 'font-mono text-xs font-bold text-amber-800 dark:text-amber-400';
        meta.textContent = 'Rate Limited (429)';
        body.className = 'text-xs leading-relaxed text-slate-700 dark:text-slate-200';
        body.textContent = lang === 'vi'
          ? 'Bạn gửi hơi nhanh — vui lòng đợi khoảng 1 phút rồi hỏi tiếp nhé.'
          : 'Too many requests in a short window — please wait a minute and try again.';
        return null;
      }
      return resp.json().then((data) => {
        if (!resp.ok || !data || !data.success) {
          throw new Error((data && data.message) ? data.message : 'RAG request failed');
        }
        return data;
      });
    })
    .then((data) => {
      if (!data) return;

      const isLiveLlm = data.mode === 'llm-rag';
      const methodBadge = data.retrieval_method === 'hybrid-vector' ? 'Hybrid Vector' : 'BM25 RAG';
      meta.className = isLiveLlm
        ? 'font-mono text-xs font-bold text-emerald-700 dark:text-emerald-400 flex flex-wrap items-center gap-1.5'
        : 'font-mono text-xs font-bold text-amber-800 dark:text-amber-400 flex flex-wrap items-center gap-1.5';

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
          link.className = 'inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 hover:bg-sky-50 dark:hover:bg-sky-900/40 text-xs font-mono text-slate-700 dark:text-slate-300 hover:text-sky-700 dark:hover:text-sky-300 transition-colors';
          link.textContent = `[${src.ref}] ${src.title}`;
          sourcesWrap.appendChild(link);
        });
        aBubble.appendChild(sourcesWrap);
      }

      copilotHistory.push({ role: 'user', text: query });
      copilotHistory.push({ role: 'assistant', text: String(data.answer || '').slice(0, 450) });
    })
    .catch(() => {
      meta.className = 'font-mono text-xs font-bold text-rose-700 dark:text-rose-400';
      meta.textContent = 'Connection Alert';
      body.className = 'text-xs leading-relaxed text-slate-700 dark:text-slate-200';
      body.textContent = lang === 'vi'
        ? 'Không kết nối được tới endpoint RAG lúc này. Vui lòng thử lại sau giây lát.'
        : 'Could not reach the RAG endpoint right now. Please try again shortly.';
    })
    .finally(() => {
      setCopilotBusyState(false);
      box.scrollTop = box.scrollHeight;
      if (window.lucide) lucide.createIcons({ root: box });
    });
}

window.runTerminalCmd = runTerminalCmd;
window.switchSkillMode = switchSkillMode;
window.replaySkillsAnimation = replaySkillsAnimation;
window.toggleOrbitSpin = toggleOrbitSpin;
window.toggleCopilot = toggleCopilot;
window.triggerSampleRagQuery = triggerSampleRagQuery;
window.handleCopilotSubmit = handleCopilotSubmit;
window.updateOrbitInspector = updateOrbitInspector;

function bootWelcomePearl() {
  document.querySelectorAll('.cli-cmd-btn, #btnLangEn, #btnLangVi').forEach((el) => {
    el.addEventListener('pointerenter', () => ensureWelcomeI18n(), { once: true, passive: true });
    el.addEventListener('focus', () => ensureWelcomeI18n(), { once: true, passive: true });
  });

  if (window.__pearlHydrated) {
    const activeLang = getLang();
    if ((activeLang !== (document.documentElement.lang || 'en') || (activeLang === 'vi' && !hasPageI18nApplied)) && typeof window.setLanguage === 'function') {
      ensureWelcomeI18n(() => {
        hasPageI18nApplied = true;
        window.setLanguage(activeLang, false);
      });
    } else {
      window.onPearlLangChange(activeLang, {}, false);
    }
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', bootWelcomePearl);
} else {
  setTimeout(bootWelcomePearl, 0);
}
