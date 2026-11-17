<?php

namespace App\Support;

/**
 * Single source of truth for true multi-color technology & skill icons across all views.
 * Replaces monochrome icon-font glyphs (e.g., Figma, Python, Java, Linux, SQLite, JS, JWT)
 * with exact multi-color inline brand SVGs that work seamlessly in both Light and Dark themes.
 */
class TechIcon
{
    private const DEVICON_MAP = [
        'laravel' => 'devicon-laravel-original colored',
        'blade' => 'devicon-laravel-original colored',
        'spring boot' => 'devicon-spring-original colored',
        'spring' => 'devicon-spring-original colored',
        'django' => 'devicon-django-plain text-emerald-600 dark:text-emerald-400',
        'fastapi' => 'devicon-fastapi-plain colored',
        'react' => 'devicon-react-original colored',
        'reactjs' => 'devicon-react-original colored',
        'tailwind css' => 'devicon-tailwindcss-original colored',
        'tailwindcss' => 'devicon-tailwindcss-original colored',
        'bootstrap' => 'devicon-bootstrap-plain colored',
        'sql server' => 'devicon-microsoftsqlserver-plain colored',
        'docker' => 'devicon-docker-plain colored',
        'docker & docker compose' => 'devicon-docker-plain colored',
        'typescript' => 'devicon-typescript-plain colored',
        'html' => 'devicon-html5-plain colored',
        'html5' => 'devicon-html5-plain colored',
        'css' => 'devicon-css3-plain colored',
        'css3' => 'devicon-css3-plain colored',
        'html5 / css3' => 'devicon-html5-plain colored',
        'wordpress' => 'devicon-wordpress-plain colored dark:text-sky-400',
        'git' => 'devicon-git-plain colored',
        'git & github' => 'devicon-git-plain colored',
        'nginx' => 'devicon-nginx-original colored',
        'firebase' => 'devicon-firebase-plain colored',
        'firebase authentication' => 'devicon-firebase-plain colored',
        'graphql' => 'devicon-graphql-plain colored',
        'postman' => 'devicon-postman-plain colored',
    ];

    private const CUSTOM_MAP = [
        'java' => 'java',
        'php' => 'php',
        'php (backend)' => 'php',
        'python' => 'python',
        'postgresql' => 'postgresql',
        'postgres' => 'postgresql',
        'figma' => 'figma',
        'odoo' => 'odoo',
        'odoo erp' => 'odoo',
        'rag' => 'rag',
        'rag ai' => 'rag',
        'ai' => 'rag',
        'llm' => 'llm',
        'vector search' => 'vector',
        'chroma' => 'vector',
        'chromadb' => 'vector',
        'jwt' => 'jwt',
        'jwt authentication' => 'jwt',
        'jwt & otp' => 'jwt',
        'mysql' => 'mysql',
        'sqlite' => 'sqlite',
        'javascript' => 'js',
        'javascript (es6+)' => 'js',
        'nextjs' => 'nextjs',
        'next.js' => 'nextjs',
        'linux' => 'linux',
        'cpanel' => 'cpanel',
        'aws (ec2 / s3)' => 'aws',
        'aws' => 'aws',
    ];

    private const LUCIDE_MAP = [
        'sql' => 'database',
        'restful api design' => 'webhook',
        'restful api' => 'webhook',
        'ci/cd pipelines' => 'git-branch',
        'otp verification' => 'key-round',
        'role-based access control (rbac)' => 'shield-check',
        'websocket / socket.io' => 'radio',
        'responsive web design' => 'monitor-smartphone',
        'payment gateway integration' => 'credit-card',
        'b2b sales' => 'briefcase',
        'retail management' => 'store',
        'accounting' => 'calculator',
        'real-time logic' => 'zap',
        'frontend optimization' => 'gauge',
    ];

    /**
     * Render an HTML icon snippet for the given skill or technology name.
     */
    public static function render(string $name, string $sizeClass = 'w-3.5 h-3.5', bool $fallback = true): string
    {
        $key = strtolower(trim($name));

        if (isset(self::CUSTOM_MAP[$key])) {
            return self::renderCustom(self::CUSTOM_MAP[$key], $sizeClass);
        }

        if (isset(self::DEVICON_MAP[$key])) {
            return '<i class="' . e(self::DEVICON_MAP[$key]) . ' text-xs leading-none" aria-hidden="true"></i>';
        }

        if (isset(self::LUCIDE_MAP[$key])) {
            return '<i data-lucide="' . e(self::LUCIDE_MAP[$key]) . '" class="' . e($sizeClass) . ' text-sky-600 dark:text-sky-400 flex-shrink-0" aria-hidden="true"></i>';
        }

        if (!$fallback) {
            return '';
        }

        return '<i data-lucide="cpu" class="' . e($sizeClass) . ' text-slate-500 dark:text-slate-400 flex-shrink-0" aria-hidden="true"></i>';
    }

    /**
     * Export brand SVG templates for frontend JS modules with placeholder size tokens.
     *
     * @return array<string, string>
     */
    public static function jsSvgMap(): array
    {
        $keys = [
            'java', 'php', 'python', 'postgresql', 'figma', 'odoo',
            'rag', 'vector', 'llm', 'jwt', 'mysql',
            'sqlite', 'js', 'nextjs', 'linux', 'cpanel', 'aws',
        ];

        $map = [];
        foreach ($keys as $key) {
            $sizeToken = $key === 'odoo' ? '__ODOO__' : '__SZ__';
            $map['custom:' . $key] = self::svgTemplate($key, $sizeToken);
        }
        return $map;
    }

    private static function renderCustom(string $type, string $sizeClass): string
    {
        $sz = e($sizeClass);
        $odooClass = str_contains($sizeClass, 'w-3.5') ? 'w-4 h-3' : $sz;
        return self::svgTemplate($type, $type === 'odoo' ? $odooClass : $sz);
    }

    private static function svgTemplate(string $type, string $classToken): string
    {
        return match ($type) {
            // Dual-tone Java: Warm Orange steam + Steel Blue / Icy Sky cup in Dark mode
            'java' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="Java Logo">'
                . '<path d="M12.4 2.1c.9 1.7-.9 3.1-1.3 4.8-.3 1.2.4 2.1 1.2 2.8-.7-1-.6-2 .1-2.9.7-.9 1.5-1.9.8-3.6-.2-.4-.5-.8-.8-1.1zm2.9 2.2c.6 1.2-.7 2-.9 3.1-.2.8.3 1.4.8 1.9-.4-.7-.3-1.3.2-1.9.5-.6 1-1.2.5-2.4-.2-.3-.4-.5-.6-.7z" fill="#E76F00" class="dark:fill-[#FB923C]"/>'
                . '<path d="M7.3 11.1h8.8c.4 2.6-.9 5.1-3.3 5.8v.1c-2.5.4-4.7-.6-5.4-3-.1-.9-.2-1.9-.1-2.9zm9.6.8h1.2c1.3 0 2.1.7 2.1 1.8 0 1.2-1 2.1-2.5 2.1h-.9c.4-.5.7-1.1.8-1.7.5 0 1.4-.1 1.4-.9 0-.5-.4-.8-1.1-.8h-.9l-.1-.5zm-10.3 6.2c2.8.7 6.9.7 10-.3.5-.2.9.2.6.6-1 .9-3.6 1.5-5.7 1.5-2.6 0-5.1-.6-5.3-1.4-.1-.3.1-.5.4-.4zm-1.4 2.1c3.5.9 8.7.9 12.2-.3.5-.2.9.2.5.6-1.4 1.1-4.5 1.8-7 1.8-3.2 0-6.2-.8-6.4-1.7-.1-.3.3-.5.7-.4z" fill="#5382A1" class="dark:fill-[#7DD3FC]"/>'
                . '</svg>',

            // Official PHP oval emblem lifted brightly on dark backgrounds
            'php' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" role="img" aria-label="PHP Logo">'
                . '<ellipse cx="12" cy="12" rx="11" ry="6.8" fill="#777BB4" stroke="#4F5B93" class="dark:fill-[#818CF8] dark:stroke-[#C7D2FE]" stroke-width="0.9"/>'
                . '<text x="12" y="14.4" text-anchor="middle" fill="#FFFFFF" font-family="ui-sans-serif,system-ui,sans-serif" font-style="italic" font-weight="800" font-size="7.6" letter-spacing="-0.3">php</text>'
                . '</svg>',

            // Dual-color Python: Blue upper snake (#3776AB / bright #60A5FA dark) + Gold lower snake (#FFD43B)
            'python' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" role="img" aria-label="Python Logo">'
                . '<path fill="#3776AB" class="dark:fill-[#60A5FA]" d="M11.91 0c-5.74 0-5.38 2.48-5.38 2.48l.01 2.57h5.47v.77H4.35S0 5.34 0 11.13c0 5.78 3.8 5.6 3.8 5.6h2.27v-3.17s-.12-3.8 3.74-3.8h5.53s3.62.06 3.62-3.51V2.48S19.5 0 11.91 0zm-2.9 1.71a1.08 1.08 0 110 2.16 1.08 1.08 0 010-2.16z"/>'
                . '<path fill="#FFD43B" d="M12.09 24c5.74 0 5.38-2.49 5.38-2.49l-.01-2.56h-5.47v-.78h7.66s4.35.48 4.35-5.31c0-5.79-3.8-5.6-3.8-5.6h-2.27v3.17s.12 3.79-3.74 3.79H9.46s-3.62-.05-3.62 3.52v3.77s-.54 2.49 7.05 2.49zm2.9-1.71a1.08 1.08 0 110-2.16 1.08 1.08 0 010 2.16z"/>'
                . '</svg>',

            // High-contrast PostgreSQL elephant (#336791 light / bright #60A5FA dark + white highlights)
            'postgresql' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="PostgreSQL Logo">'
                . '<path d="M16.8 4.2c-1.5-.8-3.4-.9-4.9-.2-1.4-.6-3.3-.4-4.7.6C4.7 6.3 3.7 9.6 4.7 12.9c.7 2.2 2.2 3.8 4 4.2.5.1.9.5 1 1l.4 2.1c.1.6.7 1 1.3.9.5-.1.9-.5 1-1.1l.2-1.5c.7 0 1.4-.1 2.1-.3l.3 1.7c.1.6.6 1 1.2.9.6-.1 1-.6.9-1.2l-.4-2.1c1.1-.7 2-1.7 2.5-3 .3.9.4 2-.1 3.1-.3.6.1 1.3.8 1.3.5 0 .9-.3 1.1-.8.9-2.2.7-4.8-.6-6.9-.4-.7-.9-1.4-1-2.2-.2-2-.9-3.9-2.6-4.8z" fill="#336791" class="dark:fill-[#60A5FA]"/>'
                . '<path d="M10.2 7.2c-1.6-.6-3.4.3-3.9 2.1-.5 1.8.2 3.8 1.6 4.6" stroke="#FFFFFF" stroke-width="1.25" stroke-linecap="round"/>'
                . '<path d="M16.2 13.6c.4 1.3 1.4 2.2 2.5 2.4" stroke="#FFFFFF" stroke-width="1.35" stroke-linecap="round"/>'
                . '<circle cx="15.1" cy="8.7" r="0.95" fill="#FFFFFF"/>'
                . '</svg>',

            // True 5-color Figma: #F24E1E, #FF7262, #A259FF, #1ABCFE, #0ACF83
            'figma' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="Figma Logo">'
                . '<path d="M8.5 1.5H12v7H8.5a3.5 3.5 0 0 1 0-7Z" fill="#F24E1E"/>'
                . '<path d="M12 1.5h3.5a3.5 3.5 0 1 1 0 7H12v-7Z" fill="#FF7262"/>'
                . '<path d="M8.5 8.5H12v7H8.5a3.5 3.5 0 1 1 0-7Z" fill="#A259FF"/>'
                . '<circle cx="15.5" cy="12" r="3.5" fill="#1ABCFE"/>'
                . '<path d="M8.5 15.5H12V19a3.5 3.5 0 1 1-3.5-3.5Z" fill="#0ACF83"/>'
                . '</svg>',

            // Official bi-color Odoo: Plum first 'o' (#714B67 / bright #E091C9 dark) + Slate 'doo'
            'odoo' => '<svg viewBox="0 7.2 24 9.8" class="' . $classToken . ' flex-shrink-0" role="img" aria-label="Odoo Logo">'
                . '<path fill="#714B67" class="dark:fill-[#E091C9]" d="M2.8998 15.7958C1.2983 15.7957 0 14.4974 0 12.8959s1.2983-2.8997 2.8998-2.8997 2.8997 1.2982 2.8997 2.8997c0 1.5999-1.2999 2.8998-2.8997 2.8998zm0-1.2c.9388.0006 1.7002-.7601 1.7007-1.699.0005-.9387-.7602-1.7002-1.699-1.7006h-.0017c-.9388.0004-1.6995.7619-1.699 1.7007.0004.9381.7608 1.6985 1.699 1.699z"/>'
                . '<path fill="#8F8F8F" class="dark:fill-slate-200" d="M21.1002 15.7957c-1.6015 0-2.8997-1.2983-2.8997-2.8998s1.2983-2.8997 2.8997-2.8997c1.6015 0 2.8998 1.2982 2.8998 2.8997 0 1.5999-1.2979 2.8998-2.8998 2.8998zm0-1.2c.9388.0006 1.7003-.7601 1.7008-1.6989.0004-.9388-.7602-1.7003-1.699-1.7007h-.0018c-.9388.0004-1.6994.7619-1.699 1.7007.0005.9381.761 1.6985 1.699 1.699zm-6.0655 1.2c-1.6014 0-2.8997-1.2983-2.8997-2.8998s1.2983-2.8997 2.8997-2.8997c1.6015 0 2.8998 1.2982 2.8998 2.8997 0 1.5999-1.2999 2.8998-2.8998 2.8998zm0-1.2c.9389.0006 1.7003-.7601 1.7008-1.6989.0005-.9388-.7602-1.7003-1.699-1.7007h-.0018c-.9388.0004-1.6994.7619-1.699 1.7007.0005.9381.761 1.6985 1.699 1.699zM11.865 12.858c0 1.6199-1.2979 2.9378-2.8977 2.9378s-2.8998-1.314-2.8998-2.9358 1.1799-2.8597 2.8998-2.8597c.6359 0 1.2239.134 1.6998.484v-1.68a.6.6 0 0 1 1.2 0v4.0537h-.002zm-2.8977 1.7399c.9388.0005 1.7002-.7602 1.7007-1.699.0005-.9388-.7602-1.7003-1.699-1.7007h-.0017c-.9389.0004-1.6995.7619-1.699 1.7007.0004.9381.7608 1.6985 1.699 1.699z"/>'
                . '</svg>',

            // RAG AI multi-color emblem
            'rag' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="RAG AI Logo">'
                . '<path d="M4.5 3.5h8.2L17 7.8v3.8M4.5 3.5v17a1.5 1.5 0 001.5 1.5h4.2M4.5 3.5A1.5 1.5 0 003 5v14.5" stroke="#0EA5E9" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>'
                . '<path d="M7.2 10h5.2M7.2 13.5h3.2" stroke="#6366F1" stroke-width="1.8" stroke-linecap="round"/>'
                . '<path d="M16.6 11.2l1.25 3.25 3.25 1.25-3.25 1.25-1.25 3.25-1.25-3.25-3.25-1.25 3.25-1.25 1.25-3.25z" fill="#6366F1"/>'
                . '<circle cx="19.5" cy="11" r="1.1" fill="#10B981"/>'
                . '</svg>',

            // ChromaDB tri-color Venn mark (#F43F5E, #F59E0B, #0EA5E9)
            'vector' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="ChromaDB Vector Logo">'
                . '<rect x="3" y="6.5" width="9" height="11" rx="4.5" fill="#F43F5E" fill-opacity="0.9"/>'
                . '<rect x="7.5" y="6.5" width="9" height="11" rx="4.5" fill="#F59E0B" fill-opacity="0.9"/>'
                . '<rect x="12" y="6.5" width="9" height="11" rx="4.5" fill="#0EA5E9" fill-opacity="0.9"/>'
                . '</svg>',

            // LLM Grounding tri-node network
            'llm' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="LLM Grounding Logo">'
                . '<circle cx="12" cy="12" r="8.5" stroke="#6366F1" stroke-width="1.8" stroke-dasharray="3 2.5"/>'
                . '<circle cx="12" cy="5.5" r="2" fill="#0EA5E9"/>'
                . '<circle cx="6.5" cy="16" r="2" fill="#6366F1"/>'
                . '<circle cx="17.5" cy="16" r="2" fill="#10B981"/>'
                . '<path d="M12 7.5v3.2m-3.6 3.7l2.5-1.8m4.7 1.8l-2.5-1.8" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"/>'
                . '<circle cx="12" cy="12" r="2.3" fill="#10B981"/>'
                . '</svg>',

            // Official JWT starburst colors (#FFFFFF, #FB015B, #D63AFF, #00B9F1, #00F2E6) on distinct badge
            'jwt' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="JWT Security Logo">'
                . '<circle cx="12" cy="12" r="10.8" fill="#0F172A" stroke="#334155" class="dark:fill-[#1E293B] dark:stroke-slate-400" stroke-width="1"/>'
                . '<path d="M12 4.2V9.2M12 14.8V19.8" stroke="#FFFFFF" stroke-width="1.9" stroke-linecap="round"/>'
                . '<path d="M16.6 5.7L13.6 9.7M10.4 14.3L7.4 18.3" stroke="#FB015B" stroke-width="1.9" stroke-linecap="round"/>'
                . '<path d="M19.4 9.6L14.7 11.1M9.3 12.9L4.6 14.4" stroke="#D63AFF" stroke-width="1.9" stroke-linecap="round"/>'
                . '<path d="M19.4 14.4L14.7 12.9M9.3 11.1L4.6 9.6" stroke="#00B9F1" stroke-width="1.9" stroke-linecap="round"/>'
                . '<path d="M16.6 18.3L13.6 14.3M10.4 9.7L7.4 5.7" stroke="#00F2E6" stroke-width="1.9" stroke-linecap="round"/>'
                . '</svg>',

            // Dual-color MySQL dolphin (#00758F / bright #38BDF8 dark + #F29111 orange accent)
            'mysql' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="MySQL Logo">'
                . '<path d="M21 15.8c-1.8-.3-3.2.1-4.3.7-1.1-2-2.7-3.6-4.7-4.7-1.5-3.3-4.6-6.2-8.2-6.8-.4-.1-.7.3-.4.7 1.1 1.3 1.7 2.8 1.9 4.5-1.3.7-2.2 1.8-2.6 3.1-.1.4.2.7.6.6.9-.2 1.8-.1 2.6.2.5.2.9.6 1.2 1.1.3.5.9.5 1.2 0 .5-.7 1.1-1.3 1.9-1.6 1.9.9 3.4 2.4 4.3 4.4.3.6.9.9 1.6.8 1.5-.3 3.2-.1 4.8.7.5.2.9-.3.6-.8-.2-.4-.3-.7-.5-1.1z" fill="#00758F" class="dark:fill-[#38BDF8]"/>'
                . '<circle cx="8.5" cy="9.2" r="0.9" fill="#F29111"/>'
                . '<path d="M15.8 16.4c1.3-.8 2.9-1.1 4.6-.7.3.1.4.4.2.6-1.2 1-3.1 1.4-4.7 1.2-.4-.1-.5-.7-.1-1.1z" fill="#F29111"/>'
                . '</svg>',

            // Official dual-tone SQLite document & feather (#003B57 light / #0284C7 dark)
            'sqlite' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="SQLite Logo">'
                . '<rect x="3.5" y="2.5" width="17" height="19" rx="3" fill="#003B57" class="dark:fill-[#0284C7]"/>'
                . '<path d="M14.5 2.5H17.5A3 3 0 0120.5 5.5V8.5H14.5V2.5Z" fill="#0F80CC" class="dark:fill-[#7DD3FC]"/>'
                . '<path d="M8 18.5C9.2 13 13 7.5 18 5c-1.8 4.2-4.6 8.2-8.2 11.4l-.8 2.1H8z" fill="#FFFFFF"/>'
                . '<path d="M9.5 17.5c2.3-2 4.8-4.5 6.8-7.5" stroke="#38BDF8" class="dark:stroke-[#0284C7]" stroke-width="1.2" stroke-linecap="round"/>'
                . '</svg>',

            // Authentic JavaScript yellow badge (#F7DF1E) + deep slate letters
            'js' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" role="img" aria-label="JavaScript Logo">'
                . '<rect width="24" height="24" rx="4" fill="#F7DF1E"/>'
                . '<path d="M13.6 18.2c.45.73 1.03 1.27 2.06 1.27.87 0 1.42-.43 1.42-1.03 0-.72-.57-.97-1.52-1.39l-.52-.22c-1.51-.64-2.51-1.45-2.51-3.15 0-1.57 1.2-2.76 3.07-2.76 1.33 0 2.29.46 2.98 1.67l-1.63 1.05c-.36-.64-.75-.9-1.35-.9-.61 0-1 .39-1 .9 0 .63.39.88 1.29 1.27l.52.22c1.77.76 2.77 1.53 2.77 3.27 0 1.88-1.47 2.91-3.45 2.91-1.93 0-3.18-.92-3.79-2.13l1.66-.98zm-7.18.17c.32.57.61 1.05 1.31 1.05.67 0 1.09-.26 1.09-1.28v-6.92h2.04v6.95c0 2.11-1.24 3.07-3.04 3.07-1.63 0-2.57-.84-3.05-1.86l1.65-1.01z" fill="#0F172A"/>'
                . '</svg>',

            // Adaptive Next.js circle mark (black circle in Light, bright white circle in Dark)
            'nextjs' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" role="img" aria-label="Next.js Logo">'
                . '<circle cx="12" cy="12" r="10.5" fill="#0F172A" stroke="#475569" class="dark:fill-white dark:stroke-slate-200" stroke-width="0.9"/>'
                . '<path d="M8.5 16.5v-9l8.2 10.6a9.2 9.2 0 01-1.5.9L10.3 12v4.5H8.5zm6.2-4.8V7.5h1.6v6.2l-1.6-2z" fill="#FFFFFF" class="dark:fill-[#0F172A]"/>'
                . '</svg>',

            // Multi-color Tux Linux Penguin with crisp outline for Dark Mode
            'linux' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="Linux Tux Logo">'
                . '<path d="M12 2c-2.6 0-4.2 1.8-4.2 4.5 0 1.3.4 2.7 1 3.9-1.3 1.5-2.6 3.7-2.6 6.2 0 1.7.7 3 2 3.7 1.1.6 2.5.8 3.8.8s2.7-.2 3.8-.8c1.3-.7 2-2 2-3.7 0-2.5-1.3-4.7-2.6-6.2.6-1.2 1-2.6 1-3.9C16.2 3.8 14.6 2 12 2z" fill="#1E293B" stroke="#475569" class="dark:fill-[#334155] dark:stroke-slate-300" stroke-width="0.9"/>'
                . '<ellipse cx="12" cy="15.3" rx="3.4" ry="4.1" fill="#FFFFFF"/>'
                . '<circle cx="10.4" cy="5.8" r="1" fill="#FFFFFF"/><circle cx="13.6" cy="5.8" r="1" fill="#FFFFFF"/>'
                . '<circle cx="10.5" cy="5.8" r="0.5" fill="#0F172A"/><circle cx="13.5" cy="5.8" r="0.5" fill="#0F172A"/>'
                . '<path d="M10.4 7.7c1.1.7 2.1.7 3.2 0 .3.8-.5 1.7-1.6 1.7s-1.9-.9-1.6-1.7z" fill="#F59E0B"/>'
                . '<ellipse cx="8.6" cy="20.4" rx="2.1" ry="1.1" fill="#F59E0B"/><ellipse cx="15.4" cy="20.4" rx="2.1" ry="1.1" fill="#F59E0B"/>'
                . '</svg>',

            // cPanel signature orange (#FF6C2C) + white mark
            'cpanel' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" role="img" aria-label="cPanel Logo">'
                . '<rect width="24" height="24" rx="5" fill="#FF6C2C"/>'
                . '<text x="12" y="16" text-anchor="middle" fill="#FFFFFF" font-family="ui-sans-serif,system-ui,sans-serif" font-weight="800" font-size="11">cP</text>'
                . '</svg>',

            // AWS dual-tone (#232F3E dark navy wordmark + #FF9900 signature smile arrow)
            'aws' => '<svg viewBox="0 0 24 24" class="' . $classToken . ' flex-shrink-0" fill="none" role="img" aria-label="AWS Logo">'
                . '<text x="12" y="12.5" text-anchor="middle" fill="#232F3E" class="dark:fill-white" font-family="ui-sans-serif,system-ui,sans-serif" font-weight="800" font-size="8.2" letter-spacing="-0.2">aws</text>'
                . '<path d="M5.5 16.3c3.9 2.4 8.9 2.5 12.8.3" stroke="#FF9900" stroke-width="1.9" stroke-linecap="round"/>'
                . '<path d="M16.5 15.2l2.4 1.1-.7 2.5" stroke="#FF9900" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>'
                . '</svg>',

            default => '',
        };
    }
}
