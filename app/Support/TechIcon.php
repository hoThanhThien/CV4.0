<?php

namespace App\Support;

/**
 * Shared technology and skill icon renderer for Pearl White & Dark Slate views.
 * Produces verified Devicon classes, brand SVGs, or Lucide fallbacks without FontAwesome.
 */
class TechIcon
{
    private const DEVICON_MAP = [
        'laravel' => 'devicon-laravel-original colored',
        'blade' => 'devicon-laravel-original colored',
        'php' => 'devicon-php-plain colored',
        'php (backend)' => 'devicon-php-plain colored',
        'java' => 'devicon-java-plain colored',
        'spring boot' => 'devicon-spring-original colored',
        'spring' => 'devicon-spring-original colored',
        'python' => 'devicon-python-plain colored',
        'django' => 'devicon-django-plain text-emerald-600 dark:text-emerald-400',
        'fastapi' => 'devicon-fastapi-plain colored',
        'react' => 'devicon-react-original colored',
        'reactjs' => 'devicon-react-original colored',
        'nextjs' => 'devicon-nextjs-plain text-slate-900 dark:text-white',
        'next.js' => 'devicon-nextjs-plain text-slate-900 dark:text-white',
        'tailwind css' => 'devicon-tailwindcss-original colored',
        'tailwindcss' => 'devicon-tailwindcss-original colored',
        'bootstrap' => 'devicon-bootstrap-plain colored',
        'mysql' => 'devicon-mysql-original colored',
        'postgresql' => 'devicon-postgresql-plain colored',
        'postgres' => 'devicon-postgresql-plain colored',
        'sqlite' => 'devicon-sqlite-plain colored',
        'sql server' => 'devicon-microsoftsqlserver-plain colored',
        'docker' => 'devicon-docker-plain colored',
        'docker & docker compose' => 'devicon-docker-plain colored',
        'javascript' => 'devicon-javascript-plain colored',
        'javascript (es6+)' => 'devicon-javascript-plain colored',
        'typescript' => 'devicon-typescript-plain colored',
        'html' => 'devicon-html5-plain colored',
        'html5' => 'devicon-html5-plain colored',
        'css' => 'devicon-css3-plain colored',
        'css3' => 'devicon-css3-plain colored',
        'html5 / css3' => 'devicon-html5-plain colored',
        'wordpress' => 'devicon-wordpress-plain colored',
        'figma' => 'devicon-figma-plain colored',
        'git' => 'devicon-git-plain colored',
        'git & github' => 'devicon-git-plain colored',
        'nginx' => 'devicon-nginx-original colored',
        'linux' => 'devicon-linux-plain text-slate-900 dark:text-slate-100',
        'firebase' => 'devicon-firebase-plain colored',
        'firebase authentication' => 'devicon-firebase-plain colored',
        'aws (ec2 / s3)' => 'devicon-amazonwebservices-plain-wordmark colored',
        'graphql' => 'devicon-graphql-plain colored',
        'postman' => 'devicon-postman-plain colored',
    ];

    private const CUSTOM_MAP = [
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
        'cpanel' => 'cpanel',
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
     * Render a crisp HTML icon snippet for the given skill or technology name.
     */
    public static function render(string $name, string $sizeClass = 'w-3.5 h-3.5'): string
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

        return '<i data-lucide="cpu" class="' . e($sizeClass) . ' text-slate-500 dark:text-slate-400 flex-shrink-0" aria-hidden="true"></i>';
    }

    private static function renderCustom(string $type, string $sizeClass): string
    {
        $sz = e($sizeClass);

        return match ($type) {
            'odoo' => '<svg viewBox="0 7.2 24 9.8" class="w-4 h-3 text-[#714B67] dark:text-[#C58FB7] flex-shrink-0" fill="currentColor" aria-hidden="true"><path d="M21.1002 15.7957c-1.6015 0-2.8997-1.2983-2.8997-2.8998s1.2983-2.8997 2.8997-2.8997c1.6015 0 2.8998 1.2982 2.8998 2.8997 0 1.5999-1.2979 2.8998-2.8998 2.8998zm0-1.2c.9388.0006 1.7003-.7601 1.7008-1.6989.0004-.9388-.7602-1.7003-1.699-1.7007h-.0018c-.9388.0004-1.6994.7619-1.699 1.7007.0005.9381.761 1.6985 1.699 1.699zm-6.0655 1.2c-1.6014 0-2.8997-1.2983-2.8997-2.8998s1.2983-2.8997 2.8997-2.8997c1.6015 0 2.8998 1.2982 2.8998 2.8997 0 1.5999-1.2999 2.8998-2.8998 2.8998zm0-1.2c.9389.0006 1.7003-.7601 1.7008-1.6989.0005-.9388-.7602-1.7003-1.699-1.7007h-.0018c-.9388.0004-1.6994.7619-1.699 1.7007.0005.9381.761 1.6985 1.699 1.699zM11.865 12.858c0 1.6199-1.2979 2.9378-2.8977 2.9378s-2.8998-1.314-2.8998-2.9358 1.1799-2.8597 2.8998-2.8597c.6359 0 1.2239.134 1.6998.484v-1.68a.6.6 0 0 1 1.2 0v4.0537h-.002zm-2.8977 1.7399c.9388.0005 1.7002-.7602 1.7007-1.699.0005-.9388-.7602-1.7003-1.699-1.7007h-.0017c-.9389.0004-1.6995.7619-1.699 1.7007.0004.9381.7608 1.6985 1.699 1.699zm-6.0675 1.1979C1.2983 15.7957 0 14.4974 0 12.8959s1.2983-2.8997 2.8998-2.8997 2.8997 1.2982 2.8997 2.8997c0 1.5999-1.2999 2.8998-2.8997 2.8998zm0-1.2c.9388.0006 1.7002-.7601 1.7007-1.699.0005-.9387-.7602-1.7002-1.699-1.7006h-.0017c-.9388.0004-1.6995.7619-1.699 1.7007.0004.9381.7608 1.6985 1.699 1.699z"/></svg>',
            'rag' => '<svg viewBox="0 0 24 24" class="' . $sz . ' flex-shrink-0" fill="none" aria-hidden="true"><path d="M4.5 3.5h8.2L17 7.8v3.8M4.5 3.5v17a1.5 1.5 0 001.5 1.5h4.2M4.5 3.5A1.5 1.5 0 003 5v14.5" stroke="#0EA5E9" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.6 11.2l1.25 3.25 3.25 1.25-3.25 1.25-1.25 3.25-1.25-3.25-3.25-1.25 3.25-1.25 1.25-3.25z" fill="#6366F1"/></svg>',
            'vector' => '<svg viewBox="0 0 24 24" class="' . $sz . ' flex-shrink-0" fill="none" aria-hidden="true"><rect x="3" y="6.5" width="9" height="11" rx="4.5" fill="#F43F5E" fill-opacity="0.9"/><rect x="7.5" y="6.5" width="9" height="11" rx="4.5" fill="#F59E0B" fill-opacity="0.9"/><rect x="12" y="6.5" width="9" height="11" rx="4.5" fill="#0EA5E9" fill-opacity="0.9"/></svg>',
            'llm' => '<svg viewBox="0 0 24 24" class="' . $sz . ' flex-shrink-0" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="8.5" stroke="#6366F1" stroke-width="1.8" stroke-dasharray="3 2.5"/><circle cx="12" cy="5.5" r="2" fill="#0EA5E9"/><circle cx="6.5" cy="16" r="2" fill="#6366F1"/><circle cx="17.5" cy="16" r="2" fill="#10B981"/><circle cx="12" cy="12" r="2.3" fill="#10B981"/></svg>',
            'jwt' => '<img src="https://cdn.simpleicons.org/jsonwebtokens/D63AFF?viewbox=auto" alt="JWT" class="' . $sz . ' object-contain flex-shrink-0" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'inline-block\';"><svg viewBox="0 0 24 24" class="' . $sz . ' hidden flex-shrink-0" fill="none" aria-hidden="true"><path d="M12 2.5L4.5 5.8v5.9c0 5.1 3.2 9.4 7.5 10.8 4.3-1.4 7.5-5.7 7.5-10.8V5.8L12 2.5z" fill="#D63AFF" fill-opacity="0.2" stroke="#D63AFF" stroke-width="1.8"/></svg>',
            'cpanel' => '<img src="https://cdn.simpleicons.org/cpanel/FF6C2C?viewbox=auto" alt="cPanel" class="' . $sz . ' object-contain flex-shrink-0" onerror="this.style.display=\'none\';this.nextElementSibling.style.display=\'inline-block\';"><svg viewBox="0 0 24 24" class="' . $sz . ' hidden flex-shrink-0 text-[#FF6C2C]" fill="currentColor" aria-hidden="true"><rect x="3" y="4" width="18" height="7" rx="2"/><rect x="3" y="13" width="18" height="7" rx="2"/></svg>',
            default => '',
        };
    }
}

