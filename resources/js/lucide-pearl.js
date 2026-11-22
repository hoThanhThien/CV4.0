import {
  createElement,
  AlertCircle,
  AlertTriangle,
  ArrowDownRight,
  ArrowLeft,
  ArrowRight,
  ArrowUpRight,
  AtSign,
  Award,
  BadgeCheck,
  BookOpen,
  Bot,
  Box,
  Briefcase,
  Building2,
  Calculator,
  Calendar,
  Check,
  CheckCircle2,
  ChevronRight,
  Clock,
  Cloud,
  Code2,
  Copy,
  Cpu,
  CreditCard,
  Database,
  Download,
  ExternalLink,
  Eye,
  FileDown,
  FileText,
  FilterX,
  FolderCode,
  FolderGit2,
  FolderOpen,
  Gauge,
  GitBranch,
  Globe,
  GraduationCap,
  HelpCircle,
  Home,
  Image,
  Info,
  KeyRound,
  Layers,
  LayoutGrid,
  Link,
  Mail,
  MapPin,
  Menu,
  MonitorSmartphone,
  Moon,
  Network,
  Newspaper,
  Orbit,
  Pause,
  Phone,
  Play,
  Radio,
  RotateCcw,
  Search,
  Send,
  Server,
  ShieldCheck,
  Sparkles,
  Star,
  Store,
  Sun,
  Tag,
  TerminalSquare,
  Trophy,
  User,
  UserCheck,
  Webhook,
  Wrench,
  X,
  Zap,
} from 'lucide';

// Clean stroke-compatible brand vectors (removed from Lucide v1 core)
const Github = [
  ['path', { d: 'M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4' }],
  ['path', { d: 'M9 18c-4.51 2-5-2-7-2' }],
];

const Linkedin = [
  ['path', { d: 'M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z' }],
  ['rect', { width: '4', height: '12', x: '2', y: '9' }],
  ['circle', { cx: '4', cy: '4', r: '2' }],
];

const PEARL_ICONS = {
  AlertCircle,
  AlertTriangle,
  ArrowDownRight,
  ArrowLeft,
  ArrowRight,
  ArrowUpRight,
  AtSign,
  Award,
  BadgeCheck,
  BookOpen,
  Bot,
  Box,
  Briefcase,
  Building2,
  Calculator,
  Calendar,
  Check,
  CheckCircle2,
  ChevronRight,
  Clock,
  Cloud,
  Code2,
  Copy,
  Cpu,
  CreditCard,
  Database,
  Download,
  ExternalLink,
  Eye,
  FileDown,
  FileText,
  FilterX,
  FolderCode,
  FolderGit2,
  FolderOpen,
  Gauge,
  GitBranch,
  Github,
  Globe,
  GraduationCap,
  HelpCircle,
  Home,
  Image,
  Info,
  KeyRound,
  Layers,
  LayoutGrid,
  Link,
  Linkedin,
  Mail,
  MapPin,
  Menu,
  MonitorSmartphone,
  Moon,
  Network,
  Newspaper,
  Orbit,
  Pause,
  Phone,
  Play,
  Radio,
  RotateCcw,
  Search,
  Send,
  Server,
  ShieldCheck,
  Sparkles,
  Star,
  Store,
  Sun,
  Tag,
  TerminalSquare,
  Trophy,
  User,
  UserCheck,
  Webhook,
  Wrench,
  X,
  Zap,
};

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

function replacePearlIcon(el, extraAttrs) {
  const iconName = el.getAttribute('data-lucide');
  if (!iconName) return;
  const iconNode = PEARL_ICONS[toPascalCase(iconName)];
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

function renderPearlIcons(options = {}) {
  const root = (options && options.root) || document;
  const extraAttrs = options && options.attrs ? options.attrs : null;
  const nodes = Array.from(root.querySelectorAll('[data-lucide]:not(svg)'));
  if (!nodes.length) return;

  if ((options && options.root) || nodes.length <= 8) {
    for (let i = 0; i < nodes.length; i++) {
      replacePearlIcon(nodes[i], extraAttrs);
    }
    return;
  }

  const immediate = [];
  const deferred = [];
  for (let i = 0; i < nodes.length; i++) {
    const el = nodes[i];
    if (el.closest('header') || immediate.length < 6) {
      immediate.push(el);
    } else {
      deferred.push(el);
    }
  }

  for (let i = 0; i < immediate.length; i++) {
    replacePearlIcon(immediate[i], extraAttrs);
  }
  if (!deferred.length) return;

  let idx = 0;
  function processNextChunk() {
    const end = Math.min(idx + 5, deferred.length);
    for (; idx < end; idx++) {
      if (deferred[idx].isConnected) {
        replacePearlIcon(deferred[idx], extraAttrs);
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
  createIcons: renderPearlIcons,
};

if (document.readyState !== 'loading') {
  setTimeout(function () {
    renderPearlIcons();
  }, 0);
}
