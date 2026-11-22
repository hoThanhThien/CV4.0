let currentSkillCategory = 0;
let currentSkillMode = 'grid';
let activeOrbitSkillIndex = 0;
let typewriterTimer = null;
let isOrbitManualPaused = false;
let copilotHistory = [];
let isCopilotBusy = false;
let activeCliKey = 'whoami';

function getLang() {
  return window.currentLang || document.documentElement.lang || 'en';
}

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

const HOME_DATA = window.__PEARL_HOME__ || {};
const SKILL_CATEGORIES = Array.isArray(HOME_DATA.skills) ? HOME_DATA.skills : [];
const CUSTOM_BRAND_SVGS = Object.assign({}, HOME_DATA.brandSvgs || {});
let brandSvgsPromise = null;
let copilotPromptsRendered = false;

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

const CLI_RESPONSES = {
  whoami: {
    cmd: 'whoami',
    en: `> engineer: Ho Thanh Thien
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

function runTerminalCmd(key, animate = true) {
  activeCliKey = key;
  const payload = CLI_RESPONSES[key];
  if (!payload) return;

  const lang = getLang();
  document.querySelectorAll('.cli-cmd-btn').forEach((btn) => {
    const isActive = btn.getAttribute('data-cli-key') === key;
    btn.className = isActive
      ? 'cli-cmd-btn px-2.5 py-1 rounded-lg bg-sky-500/20 text-sky-300 border border-sky-400/40 font-mono text-xs font-semibold hover:bg-sky-700 hover:text-white transition-colors'
      : 'cli-cmd-btn px-2.5 py-1 rounded-lg bg-slate-800 text-slate-200 border border-slate-700 font-mono text-xs font-semibold hover:border-sky-400 hover:text-white transition-colors';
  });

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
}

function escapeHtml(value) {
  return String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}

function renderSkillTabs(skipLucide = false) {
  const container = document.getElementById('skillCategoryTabs');
  if (!container) return;
  container.innerHTML = '';
  const lang = getLang();

  SKILL_CATEGORIES.forEach((cat, idx) => {
    const isActive = idx === currentSkillCategory;
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = isActive
      ? 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-sky-700 text-white shadow-lg shadow-sky-700/20 flex items-center gap-2 transition-all'
      : 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center gap-2 transition-all';

    const iconColor = isActive ? 'text-white' : escapeHtml(cat.iconColor);
    const tabLabel = (cat.label && (cat.label[lang] || cat.label.en)) || '';
    btn.innerHTML = `<i data-lucide="${escapeHtml(cat.icon)}" class="w-3.5 h-3.5 ${iconColor}"></i><span>${escapeHtml(tabLabel)}</span>`;

    btn.addEventListener('click', () => {
      if (currentSkillCategory !== idx) {
        switchCategoryAnimated(idx);
      }
    });
    container.appendChild(btn);
  });

  if (!skipLucide && window.lucide) lucide.createIcons({ root: container });
}

function switchCategoryAnimated(nextIndex) {
  ensureBrandSvgs(() => {
    if (currentSkillMode === 'grid') {
      const oldCards = document.querySelectorAll('#skillGridModeWrap .skill-grid-item');
      oldCards.forEach((c) => c.classList.add('is-exiting'));
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
  });
}

window.selectSkillTab = function (nextIndex) {
  if (currentSkillCategory !== nextIndex) {
    switchCategoryAnimated(nextIndex);
  }
};

function switchSkillMode(mode, shouldRender = true) {
  currentSkillMode = mode;
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
  if (shouldRender) {
    ensureBrandSvgs(() => renderActiveSkillView());
  }
}

function replaySkillsAnimation() {
  const btn = document.getElementById('btnSkillReplay');
  if (btn) btn.classList.add('replay-spinning');
  ensureBrandSvgs(() => renderActiveSkillView());
  if (btn) setTimeout(() => btn.classList.remove('replay-spinning'), 620);
}

function renderActiveSkillView(skipLucide = false) {
  const cat = SKILL_CATEGORIES[currentSkillCategory];
  if (!cat) return;
  const items = cat.items || [];
  const metaEl = document.getElementById('activeSkillCountMeta');
  if (metaEl) metaEl.textContent = `${items.length} SKILLS`;

  if (currentSkillMode === 'grid') {
    render3DSpinFlipGrid(items, skipLucide);
  } else {
    render3DOrbitWheel(cat, items);
  }
}

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
    return `<i data-lucide="${escapeHtml(lucideName)}" class="${boxSizes.lucide} text-sky-700 dark:text-sky-400"></i>`;
  }

  const cls = boxSizes.orbitText ? `${iconKey} ${boxSizes.orbitText}` : iconKey;
  return `<i class="${escapeHtml(cls)}"></i>`;
}

function render3DSpinFlipGrid(items, skipLucide = false) {
  const wrap = document.getElementById('skillGridModeWrap');
  if (!wrap) return;
  wrap.innerHTML = '';
  const lang = getLang();

  function appendCard(item, index) {
    const card = document.createElement('div');
    card.className = skipLucide ? 'skill-grid-item' : 'skill-grid-item is-animating';
    card.style.setProperty('--index', index);
    if (!skipLucide) {
      card.style.animationDelay = `${index * 45}ms`;
    }

    const domainTxt = (item.domain && (item.domain[lang] || item.domain.en)) || '';
    const projectTxt = (item.project && (item.project[lang] || item.project.en)) || '';
    const descTxt = (item.desc && (item.desc[lang] || item.desc.en)) || '';

    card.innerHTML = `
      <div class="grid-flip-inner">
        <div class="grid-flip-front spotlight-card bg-slate-50/70 dark:bg-slate-950/70 border border-slate-200/80 dark:border-slate-800 p-4 flex flex-col justify-between">
          <div class="flex items-start justify-between relative z-10">
            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-center text-2xl shadow-sm spin-icon-target">
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

function updateOrbitSpinLabel() {
  const lang = getLang();
  const label = document.getElementById('orbitSpinToggleLabel');
  const pauseIcon = document.getElementById('orbitSpinPauseIcon');
  const playIcon = document.getElementById('orbitSpinPlayIcon');
  if (pauseIcon) pauseIcon.classList.toggle('hidden', isOrbitManualPaused);
  if (playIcon) playIcon.classList.toggle('hidden', !isOrbitManualPaused);
  if (label) {
    label.textContent = isOrbitManualPaused
      ? (lang === 'vi' ? 'Tạm dừng' : 'Paused')
      : (lang === 'vi' ? 'Đang xoay' : 'Auto Orbit');
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
  if (!stage) return;
  const N = items.length;
  const lang = getLang();

  const hubCat = document.getElementById('orbitHubCategory');
  const hubCount = document.getElementById('orbitHubCount');
  if (hubCat) hubCat.textContent = (cat.hubName && (cat.hubName[lang] || cat.hubName.en)) || '';
  if (hubCount) hubCount.textContent = lang === 'vi' ? `${N} Kỹ năng` : `${N} Skills`;

  if (stageBox) {
    stageBox.classList.remove('is-hover-paused');
  }

  stage.innerHTML = '';
  syncOrbitGeometry(N);
  requestAnimationFrame(() => syncOrbitGeometry(N));
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
          <span class="text-xs font-bold tracking-tight text-slate-800 dark:text-slate-100 whitespace-nowrap">${escapeHtml(item.name)}</span>
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
  if (window.lucide) lucide.createIcons({ root: stage });
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

window.onPearlLangChange = function (lang, dict, isManual = true) {
  updateOrbitSpinLabel();
  ensureBrandSvgs(() => {
    renderSkillTabs(!isManual);
    setTimeout(() => {
      renderActiveSkillView(!isManual);
      if (copilotPromptsRendered || isManual) {
        setTimeout(() => {
          renderCopilotPrompts();
          copilotPromptsRendered = true;
        }, 0);
      }
      if (isManual || lang !== (document.documentElement.lang || 'en')) {
        runTerminalCmd(activeCliKey, isManual);
      }
    }, 0);
  });
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

  if (window.__pearlHydrated) {
    const activeLang = getLang();
    if (activeLang !== (document.documentElement.lang || 'en') && typeof window.setLanguage === 'function') {
      window.setLanguage(activeLang, false);
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
