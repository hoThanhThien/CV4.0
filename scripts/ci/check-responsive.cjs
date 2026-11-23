/**
 * CI Quality Gate: Cross-platform Headless Chrome responsive & runtime check.
 * Tests scrollWidth <= innerWidth and zero console/runtime JS errors across
 * 375px (mobile), 768px (tablet/iPad), and 1280px (desktop) on public routes + /admin/login,
 * plus 3D skill flip-card touch verification on Mobile & iPad.
 *
 * Uses zero external npm dependencies (built-in net/crypto CDP client for Node 18/20/22+).
 */

const { spawn, execFileSync } = require('child_process');
const crypto = require('crypto');
const fs = require('fs');
const http = require('http');
const net = require('net');
const os = require('os');
const path = require('path');

const ROOT = path.resolve(__dirname, '..', '..');
const PUBLIC_DIR = path.join(ROOT, 'public');
const ARGS = new Set(process.argv.slice(2));
const USE_LIVE_DB = ARGS.has('--live-db');

const ROUTES = ['/', '/about', '/projects/3', '/certificates', '/blog', '/projects', '/projects/1', '/contact', '/admin/login'];
const VIEWPORTS = [
  { name: 'mobile-375', width: 375, height: 812, mobile: true },
  { name: 'tablet-768', width: 768, height: 1024, mobile: true },
  { name: 'desktop-1280', width: 1280, height: 800, mobile: false },
];

const MIME = {
  '.css': 'text/css; charset=utf-8',
  '.js': 'application/javascript; charset=utf-8',
  '.woff2': 'font/woff2',
  '.webp': 'image/webp',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.svg': 'image/svg+xml',
  '.ico': 'image/x-icon',
  '.json': 'application/json',
};

function findChromeBinary() {
  if (process.env.CHROME_BIN && fs.existsSync(process.env.CHROME_BIN)) {
    return process.env.CHROME_BIN;
  }
  const candidates = process.platform === 'win32'
    ? [
      'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
      'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
      'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
    ]
    : [
      '/usr/bin/google-chrome',
      '/usr/bin/google-chrome-stable',
      '/usr/bin/chromium',
      '/usr/bin/chromium-browser',
      '/snap/bin/chromium',
    ];
  for (const bin of candidates) {
    if (fs.existsSync(bin)) return bin;
  }
  return null;
}

function getFreePort() {
  return new Promise((resolve, reject) => {
    const srv = net.createServer();
    srv.unref();
    srv.on('error', reject);
    srv.listen(0, '127.0.0.1', () => {
      const { port } = srv.address();
      srv.close(() => resolve(port));
    });
  });
}

function startStaticAndPhpProxy(phpPort, proxyPort) {
  return new Promise((resolve) => {
    const server = http.createServer((req, res) => {
      const reqPath = (req.url || '/').split('?')[0];
      const rel = decodeURIComponent(reqPath).replace(/^\/+/, '');
      const localPath = path.join(PUBLIC_DIR, rel);
      const ext = path.extname(localPath).toLowerCase();
      if (rel && ext && fs.existsSync(localPath) && fs.statSync(localPath).isFile()) {
        res.writeHead(200, { 'Content-Type': MIME[ext] || 'application/octet-stream' });
        fs.createReadStream(localPath).pipe(res);
        return;
      }
      const proxy = http.request(
        {
          hostname: '127.0.0.1',
          port: phpPort,
          path: req.url,
          method: req.method,
          headers: { ...req.headers, host: `127.0.0.1:${proxyPort}` },
        },
        (up) => {
          res.writeHead(up.statusCode || 500, up.headers);
          up.pipe(res);
        }
      );
      proxy.on('error', (err) => {
        res.writeHead(502);
        res.end(err.message);
      });
      req.pipe(proxy);
    });
    server.listen(proxyPort, '127.0.0.1', () => resolve(server));
  });
}

function httpGetJson(url) {
  return new Promise((resolve, reject) => {
    http.get(url, (res) => {
      let raw = '';
      res.setEncoding('utf8');
      res.on('data', (chunk) => { raw += chunk; });
      res.on('end', () => {
        try { resolve(JSON.parse(raw)); } catch (e) { reject(e); }
      });
    }).on('error', reject);
  });
}

/**
 * Minimal dependency-free RFC-6455 WebSocket client for localhost Chrome DevTools.
 */
function connectCdp(wsUrl) {
  return new Promise((resolve, reject) => {
    const u = new URL(wsUrl);
    const socket = net.connect(Number(u.port), u.hostname);
    const wsKey = crypto.randomBytes(16).toString('base64');
    let upgraded = false;
    let buffer = Buffer.alloc(0);
    let seq = 0;
    const pending = new Map();
    const errors = [];

    const encodeFrame = (text) => {
      const payload = Buffer.from(text, 'utf8');
      const len = payload.length;
      const mask = crypto.randomBytes(4);
      let header;
      if (len < 126) {
        header = Buffer.alloc(2);
        header[0] = 0x81;
        header[1] = 0x80 | len;
      } else if (len < 65536) {
        header = Buffer.alloc(4);
        header[0] = 0x81;
        header[1] = 0x80 | 126;
        header.writeUInt16BE(len, 2);
      } else {
        header = Buffer.alloc(10);
        header[0] = 0x81;
        header[1] = 0x80 | 127;
        header.writeBigUInt64BE(BigInt(len), 2);
      }
      const masked = Buffer.alloc(len);
      for (let i = 0; i < len; i++) {
        masked[i] = payload[i] ^ mask[i % 4];
      }
      return Buffer.concat([header, mask, masked]);
    };

    const handleMessage = (rawJson) => {
      let msg;
      try { msg = JSON.parse(rawJson); } catch { return; }
      if (msg.id && pending.has(msg.id)) {
        const { res, rej } = pending.get(msg.id);
        pending.delete(msg.id);
        if (msg.error) rej(new Error(msg.error.message || JSON.stringify(msg.error)));
        else res(msg.result || {});
      } else if (msg.method === 'Runtime.exceptionThrown') {
        errors.push(msg.params?.exceptionDetails?.text || 'Uncaught runtime exception');
      } else if (msg.method === 'Runtime.consoleAPICalled' && msg.params?.type === 'error') {
        const text = (msg.params.args || []).map((a) => a.value || a.description || '').join(' ').trim();
        if (text && !text.includes('favicon')) {
          errors.push(text);
        }
      }
    };

    let fragmented = [];
    const parseFrames = () => {
      while (buffer.length >= 2) {
        const b0 = buffer[0];
        const b1 = buffer[1];
        const fin = (b0 & 0x80) !== 0;
        const opcode = b0 & 0x0f;
        let payloadLen = b1 & 0x7f;
        let offset = 2;

        if (payloadLen === 126) {
          if (buffer.length < 4) return;
          payloadLen = buffer.readUInt16BE(2);
          offset = 4;
        } else if (payloadLen === 127) {
          if (buffer.length < 10) return;
          payloadLen = Number(buffer.readBigUInt64BE(2));
          offset = 10;
        }

        if (buffer.length < offset + payloadLen) return;
        const payload = buffer.subarray(offset, offset + payloadLen);
        buffer = buffer.subarray(offset + payloadLen);

        if (opcode === 0x8) {
          socket.end();
          return;
        }
        if (opcode === 0x9) {
          // Pong frame back cleanly
          const pong = Buffer.concat([Buffer.from([0x8a, 0x80 | Math.min(payload.length, 125)]), Buffer.alloc(4)]);
          socket.write(pong);
          continue;
        }
        if (opcode === 0x1 || opcode === 0x0) {
          fragmented.push(payload);
          if (fin) {
            const full = Buffer.concat(fragmented).toString('utf8');
            fragmented = [];
            handleMessage(full);
          }
        }
      }
    };

    socket.on('error', reject);
    socket.on('data', (chunk) => {
      buffer = Buffer.concat([buffer, chunk]);
      if (!upgraded) {
        const idx = buffer.indexOf('\r\n\r\n');
        if (idx === -1) return;
        upgraded = true;
        buffer = buffer.subarray(idx + 4);
        resolve({
          cdp(method, params = {}) {
            return new Promise((res, rej) => {
              const id = ++seq;
              pending.set(id, { res, rej });
              socket.write(encodeFrame(JSON.stringify({ id, method, params })));
            });
          },
          errors,
          close() {
            try { socket.end(); } catch { }
          },
        });
      }
      parseFrames();
    });

    socket.on('connect', () => {
      socket.write(
        `GET ${u.pathname} HTTP/1.1\r\n` +
        `Host: ${u.host}\r\n` +
        `Upgrade: websocket\r\n` +
        `Connection: Upgrade\r\n` +
        `Sec-WebSocket-Key: ${wsKey}\r\n` +
        `Sec-WebSocket-Version: 13\r\n\r\n`
      );
    });
  });
}

(async () => {
  const chromeBin = findChromeBinary();
  if (!chromeBin) {
    console.error('Chrome/Chromium binary not found. Set CHROME_BIN or install google-chrome.');
    process.exit(1);
  }

  let tempDbPath = null;
  const phpEnv = { ...process.env };
  if (!USE_LIVE_DB) {
    tempDbPath = path.join(os.tmpdir(), `cv_ci_resp_${process.pid}_${Date.now()}.sqlite`);
    fs.writeFileSync(tempDbPath, '');
    Object.assign(phpEnv, {
      APP_ENV: 'testing',
      DB_CONNECTION: 'sqlite',
      DB_DATABASE: tempDbPath,
      CACHE_DRIVER: 'array',
      SESSION_DRIVER: 'array',
      QUEUE_CONNECTION: 'sync',
      MAIL_MAILER: 'array',
    });
    execFileSync('php', ['artisan', 'migrate', '--force'], { cwd: ROOT, env: phpEnv, stdio: 'inherit' });
    execFileSync('php', ['artisan', 'db:seed', '--force'], { cwd: ROOT, env: phpEnv, stdio: 'inherit' });
  }

  const phpPort = await getFreePort();
  const proxyPort = await getFreePort();
  const cdpPort = await getFreePort();
  const chromeProfile = path.join(os.tmpdir(), `cv_ci_chrome_${process.pid}_${Date.now()}`);

  const php = spawn('php', ['-S', `127.0.0.1:${phpPort}`, '-t', PUBLIC_DIR], {
    cwd: ROOT,
    env: phpEnv,
    stdio: 'ignore',
  });
  const proxyServer = await startStaticAndPhpProxy(phpPort, proxyPort);
  const chrome = spawn(
    chromeBin,
    [
      '--headless=new',
      '--no-sandbox',
      '--disable-gpu',
      '--disable-dev-shm-usage',
      `--remote-debugging-port=${cdpPort}`,
      `--user-data-dir=${chromeProfile}`,
      'about:blank',
    ],
    { stdio: 'ignore' }
  );

  let client = null;
  const failures = [];

  try {
    let targets = [];
    for (let i = 0; i < 40; i++) {
      try {
        targets = await httpGetJson(`http://127.0.0.1:${cdpPort}/json/list`);
        if (Array.isArray(targets) && targets.length > 0) break;
      } catch { }
      await new Promise((r) => setTimeout(r, 200));
    }

    const pageTarget = targets.find((t) => t.type === 'page') || targets[0];
    if (!pageTarget || !pageTarget.webSocketDebuggerUrl) {
      throw new Error('Could not attach to Headless Chrome CDP target');
    }

    client = await connectCdp(pageTarget.webSocketDebuggerUrl);
    await client.cdp('Page.enable');
    await client.cdp('Runtime.enable');

    const evalJs = async (expression) => {
      const r = await client.cdp('Runtime.evaluate', {
        expression,
        awaitPromise: true,
        returnByValue: true,
      });
      if (r.exceptionDetails) {
        throw new Error(r.exceptionDetails.text || 'Evaluation failed');
      }
      return r.result ? r.result.value : undefined;
    };

    for (const vp of VIEWPORTS) {
      await client.cdp('Emulation.setDeviceMetricsOverride', {
        width: vp.width,
        height: vp.height,
        deviceScaleFactor: vp.mobile ? 2 : 1,
        mobile: vp.mobile,
      });
      await client.cdp('Emulation.setTouchEmulationEnabled', { enabled: vp.mobile });

      for (const route of ROUTES) {
        client.errors.length = 0;
        const targetUrl = `http://127.0.0.1:${proxyPort}${route}`;
        await client.cdp('Page.navigate', { url: targetUrl });

        for (let w = 0; w < 25; w++) {
          const ready = await evalJs('document.readyState === "complete"');
          if (ready) break;
          await new Promise((r) => setTimeout(r, 120));
        }
        await new Promise((r) => setTimeout(r, 450));

        // Scroll directly to lazy sections on homepage / about to hydrate IntersectionObserver modules
        if (route === '/' || route === '/about') {
          await evalJs(`(async () => {
            const target = document.getElementById('skills') || document.getElementById('radialOrbitSection');
            if (target) target.scrollIntoView({ behavior: 'instant' });
            else window.scrollTo(0, 900);
            await new Promise(r => setTimeout(r, 450));
            window.scrollTo(0, 0);
            await new Promise(r => setTimeout(r, 150));
          })()`);
        }

        const metrics = await evalJs(`(() => {
          const docEl = document.documentElement;
          const body = document.body;
          return {
            title: document.title,
            innerWidth: window.innerWidth,
            scrollWidth: docEl ? docEl.scrollWidth : 0,
            bodyScrollWidth: body ? body.scrollWidth : 0,
            lucideSvgs: document.querySelectorAll('svg.lucide').length
          };
        })()`);

        const overflow = metrics.scrollWidth > vp.width || metrics.scrollWidth > metrics.innerWidth;
        if (overflow) {
          failures.push(`[${vp.name}] ${route}: horizontal overflow scrollWidth=${metrics.scrollWidth} > viewport=${vp.width} (innerWidth=${metrics.innerWidth})`);
        }
        if (!metrics.title) {
          failures.push(`[${vp.name}] ${route}: empty document.title`);
        }
        if (client.errors.length > 0) {
          failures.push(`[${vp.name}] ${route}: runtime/console errors -> ${client.errors.join('; ')}`);
        }

        // Verify mobile & tablet 3D skill flip cards work without sticking on touch viewports
        if (route === '/' && vp.mobile) {
          await client.cdp('Emulation.setTouchEmulationEnabled', { enabled: true });
          const flipCheck = await evalJs(`(async () => {
            const sec = document.getElementById('skills');
            if (sec) sec.scrollIntoView({ behavior: 'instant' });
            for (let i = 0; i < 30; i++) {
              if (typeof window.switchSkillMode === 'function') break;
              await new Promise(r => setTimeout(r, 100));
            }
            if (typeof window.switchSkillMode === 'function') window.switchSkillMode('grid');
            let firstCard = null;
            for (let i = 0; i < 30; i++) {
              firstCard = document.querySelector('#skillGridModeWrap .skill-grid-item');
              if (firstCard) break;
              await new Promise(r => setTimeout(r, 100));
            }
            if (!firstCard) return { found: false };
            firstCard.scrollIntoView({ block: 'center', behavior: 'instant' });
            firstCard.dispatchEvent(new PointerEvent('pointerdown', { bubbles: true, pointerType: 'touch' }));
            firstCard.click();
            await new Promise(r => setTimeout(r, 200));
            const flipped1 = firstCard.classList.contains('is-flipped');
            firstCard.click();
            await new Promise(r => setTimeout(r, 200));
            const flipped2 = firstCard.classList.contains('is-flipped');
            return { found: true, flipped1, flipped2 };
          })()`);

          if (!flipCheck.found || !flipCheck.flipped1 || flipCheck.flipped2) {
            failures.push(`[${vp.name}] /: 3D skill card flip toggle check failed (${JSON.stringify(flipCheck)})`);
          }
        }

        console.log(
          `${overflow ? 'FAIL' : 'PASS'} [${vp.name}] ${route} | scrollWidth=${metrics.scrollWidth}/${metrics.innerWidth} | lucide=${metrics.lucideSvgs}`
        );
      }
    }

    if (failures.length > 0) {
      console.error(`\nRESPONSIVE GATE FAILURES (${failures.length}):`);
      failures.forEach((f) => console.error(` - ${f}`));
      process.exitCode = 1;
    } else {
      console.log(`\nALL RESPONSIVE CHECKS PASSED (${ROUTES.length} routes x ${VIEWPORTS.length} viewports)!`);
    }
  } finally {
    if (client) client.close();
    try { proxyServer.close(); } catch { }
    try { php.kill(); } catch { }
    try { chrome.kill(); } catch { }
    await new Promise((r) => setTimeout(r, 250));
    try { fs.rmSync(chromeProfile, { recursive: true, force: true }); } catch { }
    if (tempDbPath && fs.existsSync(tempDbPath)) {
      try { fs.unlinkSync(tempDbPath); } catch { }
    }
  }
})();
