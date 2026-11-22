function initRadialOrbitWheel() {
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

  import(/* webpackChunkName: "brand-svgs" */ './brand-svgs').then(({ BRAND_SVGS }) => {
    const renderBrand = (key) => (BRAND_SVGS[key] || '')
      .replace(/__SZ__/g, 'w-4 h-4')
      .replace(/__ODOO__/g, 'w-6 h-3.5');

    const ICONS = {
      java: renderBrand('custom:java'),
      php: renderBrand('custom:php'),
      python: renderBrand('custom:python'),
      odoo: renderBrand('custom:odoo'),
      rag: renderBrand('custom:rag'),
      react: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#0ea5e9] flex-shrink-0" fill="currentColor"><path d="M12 9.5a2.5 2.5 0 100 5 2.5 2.5 0 000-5zm0-7.5c-4.48 0-8.86 1.46-11.43 4.02-2.1 2.1-1.07 4.7 1.45 6.78-1.55 1.5-2.02 3.12-1.45 4.7.77 2.15 3.32 3.84 6.78 4.7-1.5 2.52-.77 5.07 1.45 6.78 2.57 1.98 6.95 2.02 11.43 0 2.22-1.71 2.95-4.26 1.45-6.78 3.46-.86 6.01-2.55 6.78-4.7.57-1.58.1-3.2-1.45-4.7 2.52-2.08 3.55-4.68 1.45-6.78-2.57-2.56-6.95-4.02-11.43-4.02z"/></svg>`,
      tailwind: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#06B6D4] flex-shrink-0" fill="currentColor"><path d="M12.001 4.8c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624C13.666 10.618 15.027 12 18.001 12c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C16.337 6.182 14.976 4.8 12.001 4.8z"/></svg>`,
      postgres: renderBrand('custom:postgresql'),
      docker: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#2496ED] flex-shrink-0" fill="currentColor"><path d="M13.983 11.078h2.119a.186.186 0 00.186-.185V9.006a.186.186 0 00-.186-.186h-2.119a.185.185 0 00-.185.185v1.888c0 .102.083.185.185.185m-2.954-5.43h2.118a.186.186 0 00.186-.186V3.574a.186.186 0 00-.186-.185h-2.118a.185.185 0 00-.185.185v1.888c0 .102.082.185.185.185"/></svg>`,
      figma: renderBrand('custom:figma'),
      laravel: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#FF2D20] flex-shrink-0" fill="currentColor"><path d="M22.95 7.15l-4.14-2.4a1.86 1.86 0 00-1.87 0l-5.6 3.23-1.6-2.77a1.86 1.86 0 00-1.61-.93H3.66a1.86 1.86 0 00-1.61.93L.2 8.42a1.86 1.86 0 000 1.86l1.85 3.21a1.86 1.86 0 001.61.93h4.07a1.86 1.86 0 001.61-.93l1.6-2.77 5.6 3.23a1.86 1.86 0 001.87 0l4.14-2.4a1.86 1.86 0 00.93-1.61V8.76a1.86 1.86 0 00-.93-1.61z"/></svg>`,
      spring: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 flex-shrink-0" fill="none" role="img" aria-label="Spring Boot Logo"><path d="M12 2L3.3 7v10L12 22l8.7-5V7L12 2z" fill="#6DB33F"/><path d="M15.8 8.3c-1.8-.3-4 .4-5.3 1.9-1.1 1.3-1.2 3.1-.3 4.4-.7.6-1.4 1.3-2 2.1l.9.7c.6-.7 1.3-1.4 2-1.9 1.3.7 3 .4 4.1-.8 1.3-1.5 1.8-4.2.6-6.4z" fill="#FFFFFF"/></svg>`,
      jwt: renderBrand('custom:jwt'),
      fastapi: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#009688] flex-shrink-0" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm-.88 4.39l6.55 7.15-4.49.49 3.06 7.58-6.84-7.2 4.41-.49-2.69-7.53z"/></svg>`,
      chromadb: renderBrand('custom:vector'),
      mysql: renderBrand('custom:mysql'),
      sqlite: renderBrand('custom:sqlite'),
      js: renderBrand('custom:js'),
      nextjs: renderBrand('custom:nextjs'),
      nginx: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#009639] flex-shrink-0" fill="currentColor"><path d="M12 0L1.605 6v12L12 24l10.395-6V6L12 0zm0 2.215l8.475 4.89v9.79L12 21.785l-8.475-4.89V7.105L12 2.215z"/></svg>`,
      git: `<svg viewBox="0 0 24 24" width="16" height="16" class="w-4 h-4 text-[#F05032] flex-shrink-0" fill="currentColor"><path d="M23.546 10.93L13.067.452a1.505 1.505 0 00-2.128 0L8.88 2.51l3.056 3.057a1.787 1.787 0 011.839 1.839l3.057 3.056a1.79 1.79 0 11-1.065 1.065L12.8 8.56a1.788 1.788 0 01-1.07.13L9.67 10.75a1.79 1.79 0 11-1.063-1.064l2.062-2.062a1.786 1.786 0 01.129-1.07L7.742 3.498.454 10.785a1.505 1.505 0 000 2.128l10.48 10.478a1.505 1.505 0 002.127 0l10.485-10.334a1.505 1.505 0 000-2.127z"/></svg>`,
      linux: renderBrand('custom:linux'),
      cpanel: renderBrand('custom:cpanel')
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

    function renderTabs() {
      categoryTabs.innerHTML = '';
      CATEGORIES.forEach((cat, index) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        const isActive = index === currentCatIndex;
        btn.className = isActive
          ? 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-sky-700 text-white shadow-lg shadow-sky-700/20 flex items-center gap-2 transition-all duration-200'
          : 'px-3.5 py-2 rounded-xl text-xs font-semibold bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 hover:border-slate-300 flex items-center gap-2 transition-all duration-200';

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

    function syncRadialGeometry(nodeCount) {
      const viewport = document.getElementById('radialCanvasViewport');
      const section = document.getElementById('radialOrbitSection');
      if (!radialOrbitStage || !viewport || !section) return 184;

      const availWidth = viewport.clientWidth || radialOrbitStage.clientWidth || window.innerWidth;
      const scale = Math.max(0.52, Math.min(1, availWidth / 486));

      section.style.setProperty('--radial-scale', scale.toFixed(4));

      if (coordReadout && typeof nodeCount === 'number') {
        const effR = Math.round(184 * scale);
        coordReadout.textContent = `R=${effR}px • ${nodeCount} SKILLS`;
      }
      return 184;
    }

    function renderWheel(animate = true) {
      const cat = CATEGORIES[currentCatIndex];
      const items = cat.items;
      const N = items.length;

      hubTitle.textContent = cat.hubName;
      hubCount.textContent = `${N} Skills`;

      if (radialOrbitStage) radialOrbitStage.classList.remove('is-hover-paused');
      syncRadialGeometry(N);
      requestAnimationFrame(() => syncRadialGeometry(N));
      orbitNodesContainer.innerHTML = '';
      orbitNodesContainer.classList.remove('radial-spinning-ring');
      if (animate) {
        void orbitNodesContainer.offsetWidth;
      }
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
                <span class="text-xs font-bold text-slate-800 dark:text-slate-100 tracking-tight whitespace-nowrap">${item.name}</span>
              </div>
              <div class="flip-card-back">
                <span class="text-xs font-mono font-bold tracking-tight uppercase leading-tight line-clamp-1">${item.proof}</span>
                <span class="text-xs text-sky-100 font-mono line-clamp-1">${item.name}</span>
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

    if (btnSpinAgain) {
      btnSpinAgain.addEventListener('click', () => {
        btnSpinAgain.classList.add('spin-active');
        renderWheel(true);
        setTimeout(() => btnSpinAgain.classList.remove('spin-active'), 650);
      });
    }

    renderTabs();
    renderWheel(false);

    if (radialOrbitStage && typeof ResizeObserver !== 'undefined') {
      const ro = new ResizeObserver(() => {
        syncRadialGeometry(CATEGORIES[currentCatIndex].items.length);
      });
      ro.observe(radialOrbitStage);
    } else {
      window.addEventListener('resize', () => {
        syncRadialGeometry(CATEGORIES[currentCatIndex].items.length);
      });
    }
  });
}

function scheduleRadialInit() {
  const run = () => setTimeout(initRadialOrbitWheel, 0);
  if (window.requestAnimationFrame) {
    requestAnimationFrame(run);
  } else {
    run();
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', scheduleRadialInit);
} else {
  scheduleRadialInit();
}
