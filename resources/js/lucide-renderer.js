import { createElement } from 'lucide';

function toPascalCase(str) {
  let out = '';
  let upper = true;
  for (let i = 0; i < str.length; i++) {
    const ch = str[i];
    if (ch === '-' || ch === '_' || ch <= ' ') {
      if (out.length > 0) upper = true;
      continue;
    }
    out += upper ? ch.toUpperCase() : ch;
    upper = false;
  }
  return out;
}

function appendSplitClasses(target, str) {
  if (!str) return;
  const parts = String(str).split(/\s+/);
  for (let i = 0; i < parts.length; i++) {
    if (parts[i]) target.push(parts[i]);
  }
}

export function installLucideRenderer(icons) {
  function replaceIcon(el, extraAttrs) {
    const iconName = el.getAttribute('data-lucide');
    if (!iconName) return;
    const iconNode = icons[toPascalCase(iconName)];
    if (!iconNode) return;

    const iconAttrs = { 'data-lucide': iconName };
    let hasA11y = false;
    const elClasses = ['lucide', `lucide-${iconName}`];
    for (let i = 0; i < el.attributes.length; i++) {
      const attr = el.attributes[i];
      if (attr.name === 'class') {
        appendSplitClasses(elClasses, attr.value);
      } else {
        iconAttrs[attr.name] = attr.value;
      }
      if ((attr.name.startsWith('aria-') || attr.name === 'role' || attr.name === 'title') && attr.value != null) {
        hasA11y = true;
      }
    }
    if (!hasA11y) {
      iconAttrs['aria-hidden'] = 'true';
    }
    if (extraAttrs) {
      const keys = Object.keys(extraAttrs);
      for (let i = 0; i < keys.length; i++) {
        const k = keys[i];
        if (k === 'class' && typeof extraAttrs.class === 'string') {
          appendSplitClasses(elClasses, extraAttrs.class);
        } else {
          iconAttrs[k] = extraAttrs[k];
        }
      }
    }
    iconAttrs.class = Array.from(new Set(elClasses)).join(' ');

    const svg = createElement(iconNode, iconAttrs);
    if (el.parentNode) {
      el.parentNode.replaceChild(svg, el);
    }
  }

  let chunkTimer = null;

  function renderIcons(options = {}) {
    const root = (options && options.root) || document;
    const extraAttrs = options && options.attrs ? options.attrs : null;
    const nodes = Array.from(root.querySelectorAll('[data-lucide]:not(svg)'));
    if (!nodes.length) return;

    if ((options && options.root) || nodes.length <= 6) {
      for (let i = 0; i < nodes.length; i++) {
        replaceIcon(nodes[i], extraAttrs);
      }
      return;
    }

    const immediate = [];
    const deferred = [];
    for (let i = 0; i < nodes.length; i++) {
      const el = nodes[i];
      if (el.closest('header') || immediate.length < 4) {
        immediate.push(el);
      } else {
        deferred.push(el);
      }
    }

    for (let i = 0; i < immediate.length; i++) {
      replaceIcon(immediate[i], extraAttrs);
    }
    if (!deferred.length) return;

    let idx = 0;
    function processNextChunk() {
      const end = Math.min(idx + 3, deferred.length);
      for (; idx < end; idx++) {
        if (deferred[idx].isConnected) {
          replaceIcon(deferred[idx], extraAttrs);
        }
      }
      if (idx < deferred.length) {
        chunkTimer = setTimeout(processNextChunk, 0);
      }
    }
    if (chunkTimer) clearTimeout(chunkTimer);
    chunkTimer = setTimeout(processNextChunk, 0);
  }

  window.lucide = {
    createIcons: renderIcons,
  };

  if (document.readyState !== 'loading') {
    setTimeout(function () {
      renderIcons();
    }, 0);
  }
}
