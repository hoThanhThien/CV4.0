<?php

namespace App\Services\AI\Support;

use Illuminate\Support\Str;

class VisualAssetRenderer
{
    /**
     * Process native AI raster image bytes into optimized Thumbnail (16:9) & OpenGraph (1200x630) payloads.
     * Works both WITH ext-gd (exact cover-crop & resize) and WITHOUT ext-gd (direct lossless binary passthrough).
     *
     * @return array{
     *     thumbnail: array{bytes: string, extension: string, mime_type: string},
     *     og: array{bytes: string, extension: string, mime_type: string}
     * }
     */
    public function processNativeRasterImage(
        string $binary,
        string $mimeType = 'image/png',
        int $thumbWidth = 1280,
        int $thumbHeight = 720,
        int $ogWidth = 1200,
        int $ogHeight = 630
    ): array {
        $detectedExt = $this->extensionFromMimeOrBytes($mimeType, $binary);
        $detectedMime = $this->mimeFromExtension($detectedExt);

        if (function_exists('imagecreatefromstring') && function_exists('imagecreatetruecolor')) {
            $src = @imagecreatefromstring($binary);
            if ($src !== false) {
                $thumbCanvas = $this->coverResizeGd($src, $thumbWidth, $thumbHeight);
                $ogCanvas = $this->coverResizeGd($src, $ogWidth, $ogHeight);
                imagedestroy($src);

                $thumbEncoded = $this->encodeGdImage($thumbCanvas, preferWebp: true);
                $ogEncoded = $this->encodeGdImage($ogCanvas, preferWebp: false); // PNG for universal social crawler support

                imagedestroy($thumbCanvas);
                imagedestroy($ogCanvas);

                if ($thumbEncoded !== null && $ogEncoded !== null) {
                    return [
                        'thumbnail' => $thumbEncoded,
                        'og' => $ogEncoded,
                    ];
                }
            }
        }

        // Lossless passthrough when ext-gd is not installed on the host PHP runtime
        return [
            'thumbnail' => [
                'bytes' => $binary,
                'extension' => $detectedExt,
                'mime_type' => $detectedMime,
            ],
            'og' => [
                'bytes' => $binary,
                'extension' => $detectedExt,
                'mime_type' => $detectedMime,
            ],
        ];
    }

    /**
     * Render bespoke AI-art-directed Thumbnail (16:9 vector/raster) and OpenGraph (1200x630 raster PNG)
     * when native raster image generation is unavailable or restricted on Free Tier.
     *
     * @param array<string, mixed> $spec
     * @return array{
     *     thumbnail: array{bytes: string, extension: string, mime_type: string},
     *     og: array{bytes: string, extension: string, mime_type: string}
     * }
     */
    public function renderAiDesignedAssets(
        array $spec,
        int $thumbWidth = 1280,
        int $thumbHeight = 720,
        int $ogWidth = 1200,
        int $ogHeight = 630
    ): array {
        $normalized = $this->normalizeDesignSpec($spec);

        $svgBytes = $this->buildEditorialSvg($normalized, $thumbWidth, $thumbHeight);

        if (function_exists('imagecreatetruecolor') && function_exists('imagepng')) {
            $ogPngBytes = $this->renderGdOgPng($normalized, $ogWidth, $ogHeight);
        } else {
            $ogPngBytes = $this->renderPurePhpOgPng($normalized, $ogWidth, $ogHeight);
        }

        return [
            'thumbnail' => [
                'bytes' => $svgBytes,
                'extension' => 'svg',
                'mime_type' => 'image/svg+xml',
            ],
            'og' => [
                'bytes' => $ogPngBytes,
                'extension' => 'png',
                'mime_type' => 'image/png',
            ],
        ];
    }

    /**
     * Build a deterministic, topic-aware fallback design spec when LLM design JSON is omitted.
     */
    public function buildFallbackSpec(string $title, string $category = 'Technology', string $style = 'modern_tech', string $author = ''): array
    {
        $palettes = [
            'modern_tech' => ['#0b1120', '#1e1b4b', '#6366f1', '#06b6d4'],
            '3d_isometric' => ['#0f172a', '#172554', '#3b82f6', '#a855f7'],
            'minimalist_gradient' => ['#090d16', '#18181b', '#10b981', '#38bdf8'],
            'editorial_illustration' => ['#111827', '#31102f', '#ec4899', '#f59e0b'],
            'cyberpunk_neon' => ['#090014', '#1a0b2e', '#f43f5e', '#22d3ee'],
        ];

        $hash = crc32(mb_strtolower($title . '|' . $category, 'UTF-8'));
        $palette = $palettes[$style] ?? $palettes['modern_tech'];

        // Subtle hue variation per article so consecutive posts look distinct
        if (($hash % 3) === 1 && $style === 'modern_tech') {
            $palette = ['#091526', '#162447', '#0ea5e9', '#8b5cf6'];
        } elseif (($hash % 3) === 2 && $style === 'modern_tech') {
            $palette = ['#0c1322', '#1b2a4a', '#8b5cf6', '#14b8a6'];
        }

        $motifs = ['cloud_nodes', 'neural_net', 'code_matrix', 'shield_security', 'microservices', 'database_cluster'];
        $lowerTitle = mb_strtolower($title . ' ' . $category, 'UTF-8');

        if (str_contains($lowerTitle, 'ai') || str_contains($lowerTitle, 'gemini') || str_contains($lowerTitle, 'llm') || str_contains($lowerTitle, 'gpt')) {
            $motif = 'neural_net';
        } elseif (str_contains($lowerTitle, 'security') || str_contains($lowerTitle, 'auth') || str_contains($lowerTitle, 'bảo mật')) {
            $motif = 'shield_security';
        } elseif (str_contains($lowerTitle, 'sql') || str_contains($lowerTitle, 'database') || str_contains($lowerTitle, 'redis') || str_contains($lowerTitle, 'cache')) {
            $motif = 'database_cluster';
        } elseif (str_contains($lowerTitle, 'docker') || str_contains($lowerTitle, 'microservice') || str_contains($lowerTitle, 'cloud') || str_contains($lowerTitle, 'kubernetes')) {
            $motif = 'microservices';
        } elseif (str_contains($lowerTitle, 'laravel') || str_contains($lowerTitle, 'php') || str_contains($lowerTitle, 'code') || str_contains($lowerTitle, 'clean')) {
            $motif = 'code_matrix';
        } else {
            $motif = $motifs[$hash % count($motifs)];
        }

        return [
            'bg_from' => $palette[0],
            'bg_to' => $palette[1],
            'accent_primary' => $palette[2],
            'accent_secondary' => $palette[3],
            'badge' => mb_strtoupper(Str::limit(trim($category) ?: 'TECH BLOG', 24, ''), 'UTF-8'),
            'headline' => Str::limit(trim($title), 92),
            'subtitle' => $author !== '' ? $author : (string) config('ai.author_name', 'Tech Blog'),
            'motif' => $motif,
            'svg_elements' => '',
            'site_host' => $this->resolveSiteHost(),
        ];
    }

    protected function normalizeDesignSpec(array $spec): array
    {
        $fallback = $this->buildFallbackSpec(
            (string) ($spec['title'] ?? $spec['headline'] ?? 'Tech Insights'),
            (string) ($spec['category'] ?? $spec['badge'] ?? 'Technology'),
            (string) ($spec['style'] ?? 'modern_tech'),
            (string) ($spec['author'] ?? '')
        );

        return [
            'bg_from' => $this->sanitizeHexColor((string) ($spec['bg_from'] ?? ''), $fallback['bg_from']),
            'bg_to' => $this->sanitizeHexColor((string) ($spec['bg_to'] ?? ''), $fallback['bg_to']),
            'accent_primary' => $this->sanitizeHexColor((string) ($spec['accent_primary'] ?? ''), $fallback['accent_primary']),
            'accent_secondary' => $this->sanitizeHexColor((string) ($spec['accent_secondary'] ?? ''), $fallback['accent_secondary']),
            'badge' => mb_strtoupper(Str::limit(trim((string) ($spec['badge'] ?? $fallback['badge'])), 26, ''), 'UTF-8'),
            'headline' => Str::limit(trim((string) ($spec['title'] ?? $spec['headline'] ?? $fallback['headline'])), 96),
            'subtitle' => Str::limit(trim((string) ($spec['subtitle'] ?? $fallback['subtitle'])), 72),
            'motif' => in_array(($spec['motif'] ?? ''), ['cloud_nodes', 'neural_net', 'code_matrix', 'shield_security', 'microservices', 'database_cluster'], true)
                ? $spec['motif']
                : $fallback['motif'],
            'svg_elements' => $this->sanitizeSvgPrimitives((string) ($spec['svg_elements'] ?? '')),
            'author' => trim((string) ($spec['author'] ?? config('ai.author_name', 'Tech Blog'))),
            'site_host' => $this->resolveSiteHost(),
        ];
    }

    /**
     * Strictly whitelist safe SVG vector primitives from LLM output (preventing XSS / external fetches).
     */
    public function sanitizeSvgPrimitives(string $raw): string
    {
        $raw = trim($raw);
        if ($raw === '') {
            return '';
        }

        // Strip dangerous tags and event/href attributes
        $clean = preg_replace('#<(script|foreignObject|iframe|image|use|style|text|a)\b[^>]*>.*?</\1>#is', '', $raw) ?? '';
        $clean = preg_replace('#<(script|foreignObject|iframe|image|use|style|text|a)\b[^>]*/?>#is', '', $clean) ?? '';
        $clean = preg_replace('/\son[a-z]+\s*=\s*(["\']).*?\1/is', '', $clean) ?? '';
        $clean = preg_replace('/\s(xlink:)?href\s*=\s*(["\']).*?\2/is', '', $clean) ?? '';
        $clean = preg_replace('/url\s*\(\s*["\']?\s*(?:https?:|data:|javascript:).*?\)/is', 'none', $clean) ?? '';

        $allowed = '<g><path><circle><rect><line><polyline><polygon><ellipse>';
        $clean = strip_tags($clean, $allowed);

        // Validate XML well-formedness before embedding into master SVG
        if (function_exists('simplexml_load_string')) {
            $prev = libxml_use_internal_errors(true);
            $parsed = simplexml_load_string('<g>' . $clean . '</g>');
            libxml_clear_errors();
            libxml_use_internal_errors($prev);
            if ($parsed === false) {
                return '';
            }
        }

        return $clean;
    }

    /**
     * Build a studio-grade 16:9 SVG editorial cover illustration.
     */
    protected function buildEditorialSvg(array $s, int $width, int $height): string
    {
        $bgFrom = $s['bg_from'];
        $bgTo = $s['bg_to'];
        $acc1 = $s['accent_primary'];
        $acc2 = $s['accent_secondary'];
        $badge = htmlspecialchars($s['badge'], ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $author = htmlspecialchars($s['author'], ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $siteHost = htmlspecialchars($s['site_host'], ENT_XML1 | ENT_QUOTES, 'UTF-8');

        $lines = $this->wrapUnicodeLines($s['headline'], 26, 4);
        $lineCount = count($lines);
        $fontSize = $lineCount <= 2 ? 50 : ($lineCount === 3 ? 44 : 38);
        $lineHeight = (int) round($fontSize * 1.26);
        $startY = 265 - (int) (($lineCount - 1) * ($lineHeight * 0.28));

        $titleTspans = '';
        foreach ($lines as $idx => $line) {
            $y = $startY + ($idx * $lineHeight);
            $escaped = htmlspecialchars($line, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $titleTspans .= "<text x=\"84\" y=\"{$y}\" fill=\"#f8fafc\" font-family=\"'Inter','Segoe UI',system-ui,-apple-system,sans-serif\" font-size=\"{$fontSize}\" font-weight=\"800\" letter-spacing=\"-0.02em\">{$escaped}</text>\n";
        }

        $artwork = $s['svg_elements'] !== ''
            ? $s['svg_elements']
            : $this->defaultMotifSvg($s['motif'], $acc1, $acc2);

        $badgeWidth = max(140, min(340, (mb_strlen($s['badge'], 'UTF-8') * 12) + 46));

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$width} {$height}" width="{$width}" height="{$height}">
  <defs>
    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$bgFrom}"/>
      <stop offset="100%" stop-color="{$bgTo}"/>
    </linearGradient>
    <linearGradient id="accentGrad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$acc1}"/>
      <stop offset="100%" stop-color="{$acc2}"/>
    </linearGradient>
    <radialGradient id="glow1" cx="78%" cy="32%" r="48%">
      <stop offset="0%" stop-color="{$acc1}" stop-opacity="0.34"/>
      <stop offset="100%" stop-color="{$acc1}" stop-opacity="0"/>
    </radialGradient>
    <radialGradient id="glow2" cx="22%" cy="85%" r="45%">
      <stop offset="0%" stop-color="{$acc2}" stop-opacity="0.24"/>
      <stop offset="100%" stop-color="{$acc2}" stop-opacity="0"/>
    </radialGradient>
    <pattern id="techGrid" width="48" height="48" patternUnits="userSpaceOnUse">
      <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#ffffff" stroke-opacity="0.045" stroke-width="1"/>
    </pattern>
  </defs>

  <rect width="{$width}" height="{$height}" fill="url(#bgGrad)"/>
  <rect width="{$width}" height="{$height}" fill="url(#techGrid)"/>
  <rect width="{$width}" height="{$height}" fill="url(#glow1)"/>
  <rect width="{$width}" height="{$height}" fill="url(#glow2)"/>

  <!-- Glass Frame -->
  <rect x="40" y="40" width="1200" height="640" rx="28" fill="#ffffff" fill-opacity="0.025" stroke="#ffffff" stroke-opacity="0.1" stroke-width="1.5"/>
  <rect x="40" y="40" width="8" height="640" rx="4" fill="url(#accentGrad)"/>

  <!-- Category Pill -->
  <rect x="84" y="96" width="{$badgeWidth}" height="42" rx="21" fill="{$acc1}" fill-opacity="0.2" stroke="{$acc1}" stroke-opacity="0.55" stroke-width="1.5"/>
  <circle cx="108" cy="117" r="5" fill="{$acc2}"/>
  <text x="124" y="123" fill="#e2e8f0" font-family="'JetBrains Mono','Segoe UI',monospace" font-size="15" font-weight="700" letter-spacing="0.08em">{$badge}</text>

  <!-- Wrapped Headline -->
  {$titleTspans}

  <!-- Brand Footer -->
  <line x1="84" y1="572" x2="680" y2="572" stroke="#ffffff" stroke-opacity="0.1" stroke-width="1"/>
  <circle cx="102" cy="614" r="18" fill="url(#accentGrad)"/>
  <text x="132" y="610" fill="#f8fafc" font-family="'Inter','Segoe UI',system-ui,sans-serif" font-size="18" font-weight="700">{$author}</text>
  <text x="132" y="633" fill="#94a3b8" font-family="'JetBrains Mono','Segoe UI',monospace" font-size="14">{$siteHost}</text>

  <!-- Right-hand AI Visual Scene -->
  <g transform="translate(790, 145)">
    <rect x="0" y="0" width="420" height="420" rx="28" fill="#0f172a" fill-opacity="0.48" stroke="#ffffff" stroke-opacity="0.12" stroke-width="1.5"/>
    {$artwork}
  </g>
</svg>
SVG;
    }

    protected function defaultMotifSvg(string $motif, string $acc1, string $acc2): string
    {
        return match ($motif) {
            'neural_net' => <<<SVG
    <g stroke="{$acc2}" stroke-opacity="0.45" stroke-width="2">
      <line x1="95" y1="110" x2="210" y2="80"/><line x1="95" y1="110" x2="210" y2="210"/><line x1="95" y1="110" x2="210" y2="330"/>
      <line x1="95" y1="210" x2="210" y2="80"/><line x1="95" y1="210" x2="210" y2="210"/><line x1="95" y1="210" x2="210" y2="330"/>
      <line x1="95" y1="310" x2="210" y2="210"/><line x1="95" y1="310" x2="210" y2="330"/>
      <line x1="210" y1="80" x2="330" y2="155"/><line x1="210" y1="210" x2="330" y2="155"/><line x1="210" y1="210" x2="330" y2="265"/><line x1="210" y1="330" x2="330" y2="265"/>
    </g>
    <circle cx="95" cy="110" r="16" fill="{$acc1}"/><circle cx="95" cy="210" r="16" fill="{$acc1}"/><circle cx="95" cy="310" r="16" fill="{$acc1}"/>
    <circle cx="210" cy="80" r="20" fill="{$acc2}"/><circle cx="210" cy="210" r="26" fill="url(#accentGrad)" stroke="#fff" stroke-width="2"/><circle cx="210" cy="330" r="20" fill="{$acc2}"/>
    <circle cx="330" cy="155" r="22" fill="{$acc1}"/><circle cx="330" cy="265" r="22" fill="{$acc2}"/>
SVG,
            'code_matrix' => <<<SVG
    <rect x="46" y="64" width="328" height="292" rx="18" fill="#090d16" fill-opacity="0.85" stroke="{$acc1}" stroke-opacity="0.5" stroke-width="1.5"/>
    <circle cx="74" cy="92" r="6" fill="#ef4444"/><circle cx="94" cy="92" r="6" fill="#f59e0b"/><circle cx="114" cy="92" r="6" fill="#10b981"/>
    <line x1="46" y1="116" x2="374" y2="116" stroke="#ffffff" stroke-opacity="0.1"/>
    <rect x="74" y="142" width="110" height="12" rx="6" fill="{$acc1}"/>
    <rect x="196" y="142" width="84" height="12" rx="6" fill="{$acc2}" fill-opacity="0.8"/>
    <rect x="96" y="174" width="190" height="12" rx="6" fill="#e2e8f0" fill-opacity="0.55"/>
    <rect x="96" y="204" width="145" height="12" rx="6" fill="{$acc2}"/>
    <rect x="118" y="234" width="205" height="12" rx="6" fill="#94a3b8" fill-opacity="0.45"/>
    <rect x="96" y="264" width="160" height="12" rx="6" fill="{$acc1}" fill-opacity="0.85"/>
    <rect x="74" y="296" width="92" height="12" rx="6" fill="{$acc2}"/>
SVG,
            'shield_security' => <<<SVG
    <path d="M210 62 L334 114 V216 C334 292 278 344 210 368 C142 344 86 292 86 216 V114 Z" fill="{$acc1}" fill-opacity="0.18" stroke="url(#accentGrad)" stroke-width="3"/>
    <rect x="166" y="188" width="88" height="74" rx="14" fill="{$acc2}" fill-opacity="0.9"/>
    <path d="M184 188 V160 C184 144 236 144 236 160 V188" fill="none" stroke="#f8fafc" stroke-width="8" stroke-linecap="round"/>
    <circle cx="210" cy="222" r="9" fill="#0f172a"/>
SVG,
            'database_cluster' => <<<SVG
    <g fill="{$acc1}" fill-opacity="0.22" stroke="{$acc2}" stroke-width="2.2">
      <ellipse cx="210" cy="108" rx="116" ry="34"/>
      <path d="M94 108 V196 C94 215 146 230 210 230 C274 230 326 215 326 196 V108"/>
      <path d="M94 196 V288 C94 307 146 322 210 322 C274 322 326 307 326 288 V196"/>
    </g>
    <circle cx="286" cy="162" r="7" fill="{$acc2}"/><circle cx="286" cy="254" r="7" fill="{$acc1}"/>
SVG,
            default => <<<SVG
    <polygon points="210,68 332,138 332,278 210,348 88,278 88,138" fill="{$acc1}" fill-opacity="0.14" stroke="{$acc2}" stroke-opacity="0.65" stroke-width="2"/>
    <polygon points="210,118 288,163 288,253 210,298 132,253 132,163" fill="{$acc2}" fill-opacity="0.2" stroke="{$acc1}" stroke-width="2"/>
    <circle cx="210" cy="208" r="34" fill="url(#accentGrad)"/>
    <circle cx="210" cy="68" r="10" fill="{$acc2}"/><circle cx="332" cy="138" r="10" fill="{$acc1}"/><circle cx="332" cy="278" r="10" fill="{$acc2}"/>
    <circle cx="210" cy="348" r="10" fill="{$acc1}"/><circle cx="88" cy="278" r="10" fill="{$acc2}"/><circle cx="88" cy="138" r="10" fill="{$acc1}"/>
SVG,
        };
    }

    /**
     * Render a crisp 1200x630 PNG OpenGraph card using PHP GD (when ext-gd is available).
     */
    protected function renderGdOgPng(array $s, int $width, int $height): string
    {
        $im = imagecreatetruecolor($width, $height);
        imagealphablending($im, true);
        imagesavealpha($im, true);

        [$r1, $g1, $b1] = $this->hexToRgb($s['bg_from']);
        [$r2, $g2, $b2] = $this->hexToRgb($s['bg_to']);
        [$ar1, $ag1, $ab1] = $this->hexToRgb($s['accent_primary']);
        [$ar2, $ag2, $ab2] = $this->hexToRgb($s['accent_secondary']);

        for ($y = 0; $y < $height; $y++) {
            $t = $y / max(1, $height - 1);
            $r = (int) round($r1 + ($r2 - $r1) * $t);
            $g = (int) round($g1 + ($g2 - $g1) * $t);
            $b = (int) round($b1 + ($b2 - $b1) * $t);
            $col = imagecolorallocate($im, $r, $g, $b);
            imageline($im, 0, $y, $width - 1, $y, $col);
        }

        // Subtle tech grid
        $gridCol = imagecolorallocatealpha($im, 255, 255, 255, 120);
        for ($x = 0; $x < $width; $x += 48) {
            imageline($im, $x, 0, $x, $height - 1, $gridCol);
        }
        for ($y = 0; $y < $height; $y += 48) {
            imageline($im, 0, $y, $width - 1, $y, $gridCol);
        }

        // Left accent bar & frame
        $accCol1 = imagecolorallocate($im, $ar1, $ag1, $ab1);
        $accCol2 = imagecolorallocate($im, $ar2, $ag2, $ab2);
        $frameBorder = imagecolorallocatealpha($im, 255, 255, 255, 108);
        imagerectangle($im, 36, 36, $width - 37, $height - 37, $frameBorder);
        imagefilledrectangle($im, 36, 36, 44, $height - 37, $accCol1);

        // Right-hand decorative motif nodes
        $panelBg = imagecolorallocatealpha($im, 15, 23, 42, 65);
        imagefilledrectangle($im, 780, 115, 1135, 515, $panelBg);
        imagerectangle($im, 780, 115, 1135, 515, $frameBorder);

        $lineCol = imagecolorallocatealpha($im, $ar2, $ag2, $ab2, 60);
        $nodes = [[860, 200], [960, 165], [1055, 225], [885, 335], [975, 295], [1045, 390], [935, 440]];
        foreach ($nodes as $i => [$nx, $ny]) {
            for ($j = $i + 1; $j < count($nodes); $j++) {
                if (($i + $j) % 2 === 0) {
                    imageline($im, $nx, $ny, $nodes[$j][0], $nodes[$j][1], $lineCol);
                }
            }
        }
        foreach ($nodes as $i => [$nx, $ny]) {
            $nodeCol = ($i % 2 === 0) ? $accCol1 : $accCol2;
            imagefilledellipse($im, $nx, $ny, 28, 28, $nodeCol);
        }

        $white = imagecolorallocate($im, 248, 250, 252);
        $muted = imagecolorallocate($im, 148, 163, 184);
        $pillBg = imagecolorallocatealpha($im, $ar1, $ag1, $ab1, 95);
        imagefilledrectangle($im, 76, 84, 340, 124, $pillBg);
        imagerectangle($im, 76, 84, 340, 124, $accCol1);

        $fontPath = $this->resolveUnicodeFontPath();
        if ($fontPath !== null && function_exists('imagettftext')) {
            imagettftext($im, 13, 0, 94, 110, $white, $fontPath, $s['badge']);
            $lines = $this->wrapUnicodeLines($s['headline'], 25, 4);
            $fontSize = count($lines) <= 2 ? 34 : 29;
            $y = 215;
            foreach ($lines as $line) {
                imagettftext($im, $fontSize, 0, 76, $y, $white, $fontPath, $line);
                $y += (int) round($fontSize * 1.48);
            }
            imagettftext($im, 15, 0, 76, $height - 96, $white, $fontPath, $s['author']);
            imagettftext($im, 12, 0, 76, $height - 70, $muted, $fontPath, $s['site_host']);
        } else {
            imagestring($im, 5, 92, 96, Str::upper(Str::ascii($s['badge'])), $white);
            $lines = $this->wrapUnicodeLines(Str::ascii($s['headline']), 32, 4);
            $y = 195;
            foreach ($lines as $line) {
                imagestring($im, 5, 76, $y, $line, $white);
                $y += 34;
            }
            imagestring($im, 4, 76, $height - 92, Str::ascii($s['author']) . ' - ' . $s['site_host'], $muted);
        }

        ob_start();
        imagepng($im, null, 6);
        $bytes = (string) ob_get_clean();
        imagedestroy($im);

        return $bytes;
    }

    /**
     * Pure-PHP + zlib truecolor PNG generator (exact 1200x630 RGB PNG) for environments without ext-gd.
     * Renders on a 600x315 logical grid and 2x-upscales scanlines to exact 1200x630 in ~45ms.
     */
    protected function renderPurePhpOgPng(array $s, int $targetW = 1200, int $targetH = 630): string
    {
        $lw = max(300, (int) intdiv($targetW, 2));
        $lh = max(160, (int) intdiv($targetH, 2));

        [$r1, $g1, $b1] = $this->hexToRgb($s['bg_from']);
        [$r2, $g2, $b2] = $this->hexToRgb($s['bg_to']);
        [$ar1, $ag1, $ab1] = $this->hexToRgb($s['accent_primary']);
        [$ar2, $ag2, $ab2] = $this->hexToRgb($s['accent_secondary']);

        // 1. Build logical RGB canvas buffer ($lw * $lh * 3 bytes)
        $canvas = str_repeat("\x00", $lw * $lh * 3);

        for ($y = 0; $y < $lh; $y++) {
            $ty = $y / max(1, $lh - 1);
            $baseR = $r1 + ($r2 - $r1) * $ty;
            $baseG = $g1 + ($g2 - $g1) * $ty;
            $baseB = $b1 + ($b2 - $b1) * $ty;
            $isGridY = ($y % 24 === 0);
            $rowOffset = $y * $lw * 3;

            for ($x = 0; $x < $lw; $x++) {
                $tx = $x / max(1, $lw - 1);
                $pr = (int) ($baseR + ($ar1 - $baseR) * 0.14 * $tx);
                $pg = (int) ($baseG + ($ag1 - $baseG) * 0.14 * $tx);
                $pb = (int) ($baseB + ($ab1 - $baseB) * 0.14 * $tx);

                if ($isGridY || ($x % 24 === 0)) {
                    $pr = min(255, $pr + 7);
                    $pg = min(255, $pg + 9);
                    $pb = min(255, $pb + 12);
                }

                $idx = $rowOffset + ($x * 3);
                $canvas[$idx] = chr(max(0, min(255, $pr)));
                $canvas[$idx + 1] = chr(max(0, min(255, $pg)));
                $canvas[$idx + 2] = chr(max(0, min(255, $pb)));
            }
        }

        // 2. Frame & Left Accent Bar
        $this->drawRect($canvas, $lw, $lh, 18, 18, $lw - 19, $lh - 19, [45, 58, 84], false);
        $this->drawRect($canvas, $lw, $lh, 18, 18, 22, $lh - 19, [$ar1, $ag1, $ab1], true);

        // 3. Category Badge Pill
        $badgeAscii = strtoupper(preg_replace('/[^A-Z0-9 _\-\/\.]/i', '', Str::ascii($s['badge'])) ?: 'TECH BLOG');
        $badgeW = min(220, max(78, strlen($badgeAscii) * 12 + 24));
        $this->drawRect($canvas, $lw, $lh, 38, 40, 38 + $badgeW, 62, [$ar1, $ag1, $ab1], true);
        $this->drawBitmapText($canvas, $lw, $lh, 48, 45, $badgeAscii, [255, 255, 255], 2);

        // 4. Headline Lines (crisp 5x7 bitmap font scaled 3x -> 6x on 1200x630)
        $headlineAscii = strtoupper(preg_replace('/[^A-Z0-9 _\-\:\.\,\/\(\)\+\#\&]/i', ' ', Str::ascii($s['headline'])) ?: 'AI TECH ARTICLE');
        $headlineAscii = trim(preg_replace('/\s+/', ' ', $headlineAscii));
        $lines = $this->wrapUnicodeLines($headlineAscii, 21, 4);
        $ty = 88;
        foreach ($lines as $line) {
            $this->drawBitmapText($canvas, $lw, $lh, 38, $ty, $line, [248, 250, 252], 3);
            $ty += 30;
        }

        // 5. Footer Domain
        $this->drawRect($canvas, $lw, $lh, 38, $lh - 58, 340, $lh - 57, [51, 65, 85], true);
        $footerAscii = strtoupper(Str::ascii($s['site_host'] ?: 'HOTHANHTHIEN.IO.VN'));
        $this->drawBitmapText($canvas, $lw, $lh, 38, $lh - 44, $footerAscii, [$ar2, $ag2, $ab2], 2);

        // 6. Right-hand Visual Tech Card & Connected Nodes
        $this->drawRect($canvas, $lw, $lh, 395, 52, $lw - 36, $lh - 52, [13, 20, 38], true);
        $this->drawRect($canvas, $lw, $lh, 395, 52, $lw - 36, $lh - 52, [$ar1, $ag1, $ab1], false);

        $nodes = [[435, 102], [492, 86], [536, 128], [442, 185], [496, 162], [534, 214], [478, 232]];
        foreach ($nodes as $i => [$nx, $ny]) {
            if (isset($nodes[$i + 1])) {
                $this->drawLine($canvas, $lw, $lh, $nx, $ny, $nodes[$i + 1][0], $nodes[$i + 1][1], [$ar2, $ag2, $ab2]);
            }
            if (isset($nodes[$i + 2])) {
                $this->drawLine($canvas, $lw, $lh, $nx, $ny, $nodes[$i + 2][0], $nodes[$i + 2][1], [$ar1, $ag1, $ab1]);
            }
        }
        foreach ($nodes as $i => [$nx, $ny]) {
            $col = ($i % 2 === 0) ? [$ar1, $ag1, $ab1] : [$ar2, $ag2, $ab2];
            $this->drawCircle($canvas, $lw, $lh, $nx, $ny, 9, $col);
            $this->drawCircle($canvas, $lw, $lh, $nx, $ny, 4, [248, 250, 252]);
        }

        // 7. Expand 2x horizontally & vertically into exact targetW x targetH PNG scanlines
        $scanlines = '';
        for ($y = 0; $y < $lh; $y++) {
            $rowOff = $y * $lw * 3;
            $rowPixels = '';
            for ($x = 0; $x < $lw; $x++) {
                $px = substr($canvas, $rowOff + ($x * 3), 3);
                $rowPixels .= $px . $px;
            }
            if (($lw * 2) < $targetW) {
                $rowPixels .= str_repeat("\x0f\x17\x2a", $targetW - ($lw * 2));
            } elseif (($lw * 2) > $targetW) {
                $rowPixels = substr($rowPixels, 0, $targetW * 3);
            }

            $line = "\x00" . $rowPixels;
            $scanlines .= $line . $line;
        }

        $currentRows = $lh * 2;
        if ($currentRows < $targetH) {
            $padLine = "\x00" . str_repeat("\x0f\x17\x2a", $targetW);
            $scanlines .= str_repeat($padLine, $targetH - $currentRows);
        }

        $ihdr = pack('NNCCCCC', $targetW, $targetH, 8, 2, 0, 0, 0);
        $idat = gzcompress($scanlines, 6);

        return "\x89PNG\r\n\x1a\n"
            . $this->pngChunk('IHDR', $ihdr)
            . $this->pngChunk('IDAT', $idat)
            . $this->pngChunk('IEND', '');
    }

    protected function pngChunk(string $type, string $data): string
    {
        return pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
    }

    protected function drawRect(string &$canvas, int $w, int $h, int $x1, int $y1, int $x2, int $y2, array $rgb, bool $fill): void
    {
        $x1 = max(0, min($w - 1, $x1));
        $x2 = max(0, min($w - 1, $x2));
        $y1 = max(0, min($h - 1, $y1));
        $y2 = max(0, min($h - 1, $y2));
        $px = chr($rgb[0]) . chr($rgb[1]) . chr($rgb[2]);

        if ($fill) {
            $span = $x2 - $x1 + 1;
            if ($span <= 0) {
                return;
            }
            $rowChunk = str_repeat($px, $span);
            for ($y = $y1; $y <= $y2; $y++) {
                $offset = ($y * $w + $x1) * 3;
                $canvas = substr_replace($canvas, $rowChunk, $offset, $span * 3);
            }
            return;
        }

        for ($x = $x1; $x <= $x2; $x++) {
            $this->setPixel($canvas, $w, $h, $x, $y1, $px);
            $this->setPixel($canvas, $w, $h, $x, $y2, $px);
        }
        for ($y = $y1; $y <= $y2; $y++) {
            $this->setPixel($canvas, $w, $h, $x1, $y, $px);
            $this->setPixel($canvas, $w, $h, $x2, $y, $px);
        }
    }

    protected function drawCircle(string &$canvas, int $w, int $h, int $cx, int $cy, int $r, array $rgb): void
    {
        $px = chr($rgb[0]) . chr($rgb[1]) . chr($rgb[2]);
        $r2 = $r * $r;
        for ($dy = -$r; $dy <= $r; $dy++) {
            for ($dx = -$r; $dx <= $r; $dx++) {
                if (($dx * $dx + $dy * $dy) <= $r2) {
                    $this->setPixel($canvas, $w, $h, $cx + $dx, $cy + $dy, $px);
                }
            }
        }
    }

    protected function drawLine(string &$canvas, int $w, int $h, int $x0, int $y0, int $x1, int $y1, array $rgb): void
    {
        $px = chr($rgb[0]) . chr($rgb[1]) . chr($rgb[2]);
        $steps = max(abs($x1 - $x0), abs($y1 - $y0), 1);
        for ($i = 0; $i <= $steps; $i++) {
            $x = (int) round($x0 + ($x1 - $x0) * ($i / $steps));
            $y = (int) round($y0 + ($y1 - $y0) * ($i / $steps));
            $this->setPixel($canvas, $w, $h, $x, $y, $px);
            $this->setPixel($canvas, $w, $h, $x + 1, $y, $px);
        }
    }

    protected function setPixel(string &$canvas, int $w, int $h, int $x, int $y, string $px): void
    {
        if ($x < 0 || $x >= $w || $y < 0 || $y >= $h) {
            return;
        }
        $off = ($y * $w + $x) * 3;
        $canvas[$off] = $px[0];
        $canvas[$off + 1] = $px[1];
        $canvas[$off + 2] = $px[2];
    }

    /**
     * Compact 5x7 uppercase/digit/symbol bitmap font for dependency-free PNG headline rendering.
     */
    protected function drawBitmapText(string &$canvas, int $w, int $h, int $x, int $y, string $text, array $rgb, int $scale = 2): void
    {
        static $glyphs = [
            'A' => [0x0E, 0x11, 0x11, 0x1F, 0x11, 0x11, 0x11],
            'B' => [0x1E, 0x11, 0x11, 0x1E, 0x11, 0x11, 0x1E],
            'C' => [0x0E, 0x11, 0x10, 0x10, 0x10, 0x11, 0x0E],
            'D' => [0x1C, 0x12, 0x11, 0x11, 0x11, 0x12, 0x1C],
            'E' => [0x1F, 0x10, 0x10, 0x1E, 0x10, 0x10, 0x1F],
            'F' => [0x1F, 0x10, 0x10, 0x1E, 0x10, 0x10, 0x10],
            'G' => [0x0E, 0x11, 0x10, 0x17, 0x11, 0x11, 0x0F],
            'H' => [0x11, 0x11, 0x11, 0x1F, 0x11, 0x11, 0x11],
            'I' => [0x0E, 0x04, 0x04, 0x04, 0x04, 0x04, 0x0E],
            'J' => [0x07, 0x02, 0x02, 0x02, 0x12, 0x12, 0x0C],
            'K' => [0x11, 0x12, 0x14, 0x18, 0x14, 0x12, 0x11],
            'L' => [0x10, 0x10, 0x10, 0x10, 0x10, 0x10, 0x1F],
            'M' => [0x11, 0x1B, 0x15, 0x15, 0x11, 0x11, 0x11],
            'N' => [0x11, 0x19, 0x15, 0x13, 0x11, 0x11, 0x11],
            'O' => [0x0E, 0x11, 0x11, 0x11, 0x11, 0x11, 0x0E],
            'P' => [0x1E, 0x11, 0x11, 0x1E, 0x10, 0x10, 0x10],
            'Q' => [0x0E, 0x11, 0x11, 0x11, 0x15, 0x12, 0x0D],
            'R' => [0x1E, 0x11, 0x11, 0x1E, 0x14, 0x12, 0x11],
            'S' => [0x0F, 0x10, 0x10, 0x0E, 0x01, 0x01, 0x1E],
            'T' => [0x1F, 0x04, 0x04, 0x04, 0x04, 0x04, 0x04],
            'U' => [0x11, 0x11, 0x11, 0x11, 0x11, 0x11, 0x0E],
            'V' => [0x11, 0x11, 0x11, 0x11, 0x11, 0x0A, 0x04],
            'W' => [0x11, 0x11, 0x11, 0x15, 0x15, 0x1B, 0x11],
            'X' => [0x11, 0x11, 0x0A, 0x04, 0x0A, 0x11, 0x11],
            'Y' => [0x11, 0x11, 0x0A, 0x04, 0x04, 0x04, 0x04],
            'Z' => [0x1F, 0x01, 0x02, 0x04, 0x08, 0x10, 0x1F],
            '0' => [0x0E, 0x11, 0x13, 0x15, 0x19, 0x11, 0x0E],
            '1' => [0x04, 0x0C, 0x04, 0x04, 0x04, 0x04, 0x0E],
            '2' => [0x0E, 0x11, 0x01, 0x06, 0x08, 0x10, 0x1F],
            '3' => [0x1F, 0x02, 0x04, 0x06, 0x01, 0x11, 0x0E],
            '4' => [0x02, 0x06, 0x0A, 0x12, 0x1F, 0x02, 0x02],
            '5' => [0x1F, 0x10, 0x1E, 0x01, 0x01, 0x11, 0x0E],
            '6' => [0x06, 0x08, 0x10, 0x1E, 0x11, 0x11, 0x0E],
            '7' => [0x1F, 0x01, 0x02, 0x04, 0x08, 0x08, 0x08],
            '8' => [0x0E, 0x11, 0x11, 0x0E, 0x11, 0x11, 0x0E],
            '9' => [0x0E, 0x11, 0x11, 0x0F, 0x01, 0x02, 0x0C],
            '-' => [0x00, 0x00, 0x00, 0x1F, 0x00, 0x00, 0x00],
            '.' => [0x00, 0x00, 0x00, 0x00, 0x00, 0x0C, 0x0C],
            ':' => [0x00, 0x0C, 0x0C, 0x00, 0x0C, 0x0C, 0x00],
            '/' => [0x01, 0x02, 0x02, 0x04, 0x08, 0x08, 0x10],
            '+' => [0x00, 0x04, 0x04, 0x1F, 0x04, 0x04, 0x00],
            '&' => [0x0C, 0x12, 0x14, 0x08, 0x15, 0x12, 0x0D],
            '(' => [0x02, 0x04, 0x08, 0x08, 0x08, 0x04, 0x02],
            ')' => [0x08, 0x04, 0x02, 0x02, 0x02, 0x04, 0x08],
        ];

        $px = chr($rgb[0]) . chr($rgb[1]) . chr($rgb[2]);
        $cursorX = $x;
        $len = strlen($text);

        for ($i = 0; $i < $len; $i++) {
            $ch = $text[$i];
            if ($ch === ' ') {
                $cursorX += (4 * $scale);
                continue;
            }
            $rows = $glyphs[$ch] ?? null;
            if ($rows !== null) {
                for ($ry = 0; $ry < 7; $ry++) {
                    $bits = $rows[$ry];
                    for ($rx = 0; $rx < 5; $rx++) {
                        if (($bits >> (4 - $rx)) & 1) {
                            for ($sy = 0; $sy < $scale; $sy++) {
                                for ($sx = 0; $sx < $scale; $sx++) {
                                    $this->setPixel($canvas, $w, $h, $cursorX + ($rx * $scale) + $sx, $y + ($ry * $scale) + $sy, $px);
                                }
                            }
                        }
                    }
                }
            }
            $cursorX += (6 * $scale);
            if ($cursorX > ($w - 230)) {
                break;
            }
        }
    }

    protected function coverResizeGd($src, int $dstW, int $dstH)
    {
        $srcW = max(1, imagesx($src));
        $srcH = max(1, imagesy($src));

        $srcRatio = $srcW / $srcH;
        $dstRatio = $dstW / $dstH;

        if ($srcRatio > $dstRatio) {
            $cropH = $srcH;
            $cropW = (int) round($srcH * $dstRatio);
            $srcX = (int) intdiv($srcW - $cropW, 2);
            $srcY = 0;
        } else {
            $cropW = $srcW;
            $cropH = (int) round($srcW / $dstRatio);
            $srcX = 0;
            $srcY = (int) intdiv($srcH - $cropH, 2);
        }

        $dst = imagecreatetruecolor($dstW, $dstH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $dstW, $dstH, $cropW, $cropH);

        return $dst;
    }

    /**
     * @return array{bytes: string, extension: string, mime_type: string}|null
     */
    protected function encodeGdImage($canvas, bool $preferWebp = true): ?array
    {
        if ($preferWebp && function_exists('imagewebp')) {
            ob_start();
            $ok = @imagewebp($canvas, null, 88);
            $bytes = (string) ob_get_clean();
            if ($ok && strlen($bytes) > 64) {
                return [
                    'bytes' => $bytes,
                    'extension' => 'webp',
                    'mime_type' => 'image/webp',
                ];
            }
        }

        if (function_exists('imagepng')) {
            ob_start();
            $ok = @imagepng($canvas, null, 6);
            $bytes = (string) ob_get_clean();
            if ($ok && strlen($bytes) > 64) {
                return [
                    'bytes' => $bytes,
                    'extension' => 'png',
                    'mime_type' => 'image/png',
                ];
            }
        }

        return null;
    }

    protected function resolveUnicodeFontPath(): ?string
    {
        $configured = trim((string) config('ai.image.font_path', ''));
        $candidates = array_filter([
            $configured,
            public_path('fonts/Inter-Bold.ttf'),
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
            'C:/Windows/Fonts/segoeuib.ttf',
            'C:/Windows/Fonts/arialbd.ttf',
        ]);

        foreach ($candidates as $path) {
            if (@is_file($path) && @is_readable($path)) {
                return $path;
            }
        }

        return null;
    }

    protected function wrapUnicodeLines(string $text, int $maxCharsPerLine = 26, int $maxLines = 4): array
    {
        $words = preg_split('/\s+/u', trim($text)) ?: [];
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $candidate = $current === '' ? $word : ($current . ' ' . $word);
            if (mb_strlen($candidate, 'UTF-8') <= $maxCharsPerLine || $current === '') {
                $current = $candidate;
            } else {
                $lines[] = $current;
                $current = $word;
                if (count($lines) >= ($maxLines - 1)) {
                    break;
                }
            }
        }

        if ($current !== '' && count($lines) < $maxLines) {
            $lines[] = Str::limit($current, $maxCharsPerLine + 4);
        }

        return $lines ?: ['Tech Insights'];
    }

    protected function sanitizeHexColor(string $hex, string $default): string
    {
        $hex = trim($hex);
        if (preg_match('/^#[0-9a-fA-F]{6}$/', $hex)) {
            return strtolower($hex);
        }
        return $default;
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) {
            return [15, 23, 42];
        }
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    protected function resolveSiteHost(): string
    {
        $url = trim((string) config('ai.site_url', config('app.url', '')));
        $host = (string) (parse_url($url, PHP_URL_HOST) ?? '');
        if ($host !== '' && $host !== 'localhost' && $host !== '127.0.0.1') {
            return $host;
        }
        return $host ?: 'portfolio.blog';
    }

    protected function extensionFromMimeOrBytes(string $mime, string $binary): string
    {
        if (str_starts_with($binary, "\x89PNG")) {
            return 'png';
        }
        if (str_starts_with($binary, "\xFF\xD8\xFF")) {
            return 'jpg';
        }
        if (str_starts_with($binary, 'RIFF') && substr($binary, 8, 4) === 'WEBP') {
            return 'webp';
        }

        $lower = strtolower($mime);
        if (str_contains($lower, 'webp')) {
            return 'webp';
        }
        if (str_contains($lower, 'jpeg') || str_contains($lower, 'jpg')) {
            return 'jpg';
        }

        return 'png';
    }

    protected function mimeFromExtension(string $ext): string
    {
        return match ($ext) {
            'webp' => 'image/webp',
            'jpg', 'jpeg' => 'image/jpeg',
            'svg' => 'image/svg+xml',
            default => 'image/png',
        };
    }
}
