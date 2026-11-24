<?php

namespace App\Support;

use App\Models\Skill;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Builds the 3D Skill Matrix & Orbit Wheel dataset by combining curated bilingual
 * presentation categories with live database records from the `skills` table.
 */
class SkillMatrixBuilder
{
    /**
     * Normalized skill aliases mapping curated titles to seeded DB skill names.
     */
    private const ALIASES = [
        'odoo erp' => ['odoo erp', 'odoo'],
        'rag ai' => ['rag ai', 'rag'],
        'laravel 11' => ['laravel 11', 'laravel'],
        'odoo orm' => ['odoo orm', 'odoo erp', 'odoo'],
        'jwt & rbac' => ['jwt & rbac', 'jwt authentication', 'role-based access control (rbac)'],
        'llm grounding' => ['llm grounding', 'llm'],
        'mysql 8.0' => ['mysql 8.0', 'mysql'],
        'next.js' => ['next.js', 'nextjs'],
        'javascript es6+' => ['javascript es6+', 'javascript (es6+)', 'javascript'],
        'blade engine' => ['blade engine', 'blade'],
        'ui/ux craft' => ['ui/ux craft', 'figma'],
        'docker compose' => ['docker compose', 'docker & docker compose', 'docker'],
        'git & github' => ['git & github', 'git'],
        'linux / cron' => ['linux / cron', 'linux', 'cpanel'],
    ];

    /**
     * Default syllabus `details` values inserted by DatabaseSeeder so customized
     * admin edits override curated descriptions cleanly without clobbering defaults.
     */
    private const SEEDED_DETAILS = [
        'oop, collections, exception handling, multithreading',
        'oop, async programming, scripting',
        'mvc architecture, composer',
        'dom manipulation, event handling, async/await, fetch api',
        'ddl, dml, joins, stored procedures, indexing',
        'hooks, jsx, functional components, react router, context api, react query, state management, component reusability, responsive design',
        'semantic html',
        'flexbox, grid, animations',
        'responsive design, utility classes, custom design systems',
        'responsive grid, ui components, fast prototyping',
        'spring web, spring data jpa, spring security, maven, dependency injection',
        'async api development, pydantic, dependency injection, websockets, openapi',
        'mvt architecture, orm, admin site, authentication',
        'mvc, crud, session, authentication',
        'eloquent orm, blade, routing, middleware, artisan, mvc',
        'database design, normalization, joins, transactions, indexing',
        'relational modeling, indexing, performance optimization',
        't-sql, stored procedures, relational database management',
        'crud operations, json, api documentation (swagger/openapi), versioning',
        'schemas, queries, mutations, resolvers',
        'sprint planning, daily standups, iterative workflow',
        'automated deployment, code integration, build pipelines',
        'readable code, comprehensive documentation, openapi/swagger',
        'single responsibility, open/closed, clean architecture',
        'factory, singleton, repository pattern',
        'stateless token auth, refresh tokens, token validation',
        'time-based & counter-based one time passwords, email / sms otp',
        'permissions, role hierarchies, middleware & route guards',
        'secure cookies, session lifecycles, multi-device sessions',
        'bcrypt, argon2, salt hashing, secure password storage',
        'branching, merge, pull request, version control',
        'containerization, multi-container orchestration, environment isolation',
        'firestore, realtime database, cloud functions, hosting',
        'cloud compute (ec2), object storage (s3), iam permissions',
        'cms customization, theme development, plugin integration',
        'api testing, automated test suites, mock servers',
        'bi-directional real-time communication, event streaming',
        'mobile-first architecture, fluid grids, cross-browser compatibility',
        'momo, paypal api integration, ipn callbacks',
        'email/password, social auth, oauth2 provider flow',
    ];

    /**
     * Secondary broad resume keywords seeded for project tagging that should not
     * overcrowd the 10-node Orbit Wheel unless linked to a project or customized.
     */
    private const SEEDED_SECONDARY_NAMES = [
        'sql', 'html5', 'css3', 'bootstrap', 'django', 'php (backend)', 'sql server',
        'restful api design', 'graphql', 'agile / scrum', 'ci/cd pipelines',
        'clean code & documentation', 'mvc & solid principles', 'design patterns',
        'otp verification', 'role-based access control (rbac)', 'session management',
        'password hashing', 'docker & docker compose', 'firebase', 'aws (ec2 / s3)',
        'wordpress', 'postman', 'websocket / socket.io', 'responsive web design',
        'payment gateway integration', 'firebase authentication', 'blade', 'cpanel',
    ];

    /**
     * Build merged bilingual categories ready for `@json(...)` injection into welcome view.
     *
     * @param  Collection<int, Skill>  $skills
     * @return array<int, array<string, mixed>>
     */
    public static function build(Collection $skills): array
    {
        $categories = self::curatedDefaults();

        if ($skills->isEmpty()) {
            return $categories;
        }

        $skillsByName = [];
        foreach ($skills as $skill) {
            $skillsByName[strtolower(trim((string) $skill->name))] = $skill;
        }

        $matchedDbIds = [];

        foreach ($categories as &$cat) {
            foreach ($cat['items'] as &$item) {
                $dbSkill = self::findMatchingDbSkill($item['name'], $skillsByName);
                if (!$dbSkill) {
                    continue;
                }

                $matchedDbIds[$dbSkill->id] = true;

                // Apply linked DB project title when present on the skill relation
                if ($dbSkill->relationLoaded('projects') && $dbSkill->projects->isNotEmpty()) {
                    $firstProject = $dbSkill->projects->first();
                    $item['project'] = [
                        'en' => $firstProject->title,
                        'vi' => $firstProject->title,
                    ];
                }

                // Sync customized DB details when admin has edited beyond default seeder copy
                $rawDetails = trim((string) $dbSkill->details);
                if ($rawDetails !== '' && !in_array(strtolower($rawDetails), self::SEEDED_DETAILS, true)) {
                    $item['desc'] = [
                        'en' => $rawDetails,
                        'vi' => $rawDetails,
                    ];
                }
            }
            unset($item);
        }
        unset($cat);

        // Append newly created / customized DB skills into matching tabs or new category tabs
        $extraCategories = [];
        foreach ($skills as $skill) {
            if (isset($matchedDbIds[$skill->id])) {
                continue;
            }

            $normalizedName = strtolower(trim((string) $skill->name));
            $rawDetails = trim((string) $skill->details);
            $hasCustomDetails = $rawDetails !== '' && !in_array(strtolower($rawDetails), self::SEEDED_DETAILS, true);
            $hasLinkedProjects = $skill->relationLoaded('projects') && $skill->projects->isNotEmpty();

            if (in_array($normalizedName, self::SEEDED_SECONDARY_NAMES, true) && !$hasCustomDetails && !$hasLinkedProjects) {
                continue;
            }

            $entry = self::transformDbSkill($skill);
            $targetId = self::mapCategoryToTab((string) $skill->category);

            if ($targetId !== null) {
                $targetIdx = self::findCategoryIndex($categories, $targetId);
                if ($targetIdx !== null && count($categories[$targetIdx]['items']) < 10) {
                    $categories[$targetIdx]['items'][] = $entry;
                    continue;
                }
            }

            $bucketKey = trim((string) $skill->category) ?: 'Specializations';
            $bucketId = 'db-' . Str::slug($bucketKey);
            if (!isset($extraCategories[$bucketId])) {
                $extraCategories[$bucketId] = [
                    'id' => $bucketId,
                    'label' => ['en' => $bucketKey, 'vi' => $bucketKey],
                    'hubName' => ['en' => Str::limit($bucketKey, 16, ''), 'vi' => Str::limit($bucketKey, 16, '')],
                    'icon' => 'layers',
                    'iconColor' => 'text-sky-700 dark:text-sky-400',
                    'items' => [],
                ];
            }

            if (count($extraCategories[$bucketId]['items']) < 10) {
                $extraCategories[$bucketId]['items'][] = $entry;
            }
        }

        foreach ($extraCategories as $extraCat) {
            if (!empty($extraCat['items'])) {
                $categories[] = $extraCat;
            }
        }

        return $categories;
    }

    /**
     * Find a matching DB skill row using exact or aliased names.
     *
     * @param  array<string, Skill>  $skillsByName
     */
    private static function findMatchingDbSkill(string $curatedName, array $skillsByName): ?Skill
    {
        $key = strtolower(trim($curatedName));
        $candidates = self::ALIASES[$key] ?? [$key];

        foreach ($candidates as $candidate) {
            if (isset($skillsByName[$candidate])) {
                return $skillsByName[$candidate];
            }
        }

        return null;
    }

    /**
     * Map free-form DB skill category strings to one of the 5 curated tabs when possible.
     */
    private static function mapCategoryToTab(string $category): ?string
    {
        $cat = strtolower(trim($category));

        return match (true) {
            str_contains($cat, 'backend') || str_contains($cat, 'api') || str_contains($cat, 'auth') || str_contains($cat, 'erp') => 'backend',
            str_contains($cat, 'database') || str_contains($cat, 'rag') || str_contains($cat, 'ai') || str_contains($cat, 'data') => 'rag',
            str_contains($cat, 'frontend') || str_contains($cat, 'ui') || str_contains($cat, 'ux') => 'frontend',
            str_contains($cat, 'devops') || str_contains($cat, 'tool') || str_contains($cat, 'cloud') || str_contains($cat, 'infra') => 'devops',
            str_contains($cat, 'language') || str_contains($cat, 'core') => 'core',
            default => null,
        };
    }

    private static function findCategoryIndex(array $categories, string $id): ?int
    {
        foreach ($categories as $idx => $cat) {
            if ($cat['id'] === $id) {
                return $idx;
            }
        }

        return null;
    }

    /**
     * Convert an arbitrary DB Skill model into the normalized matrix item payload.
     *
     * @return array<string, mixed>
     */
    private static function transformDbSkill(Skill $skill): array
    {
        $catLabel = trim((string) $skill->category) ?: 'Engineering';
        $projectTitle = $skill->relationLoaded('projects') && $skill->projects->isNotEmpty()
            ? $skill->projects->first()->title
            : null;
        $rawDetails = trim((string) $skill->details);

        return [
            'name' => $skill->name,
            'icon' => TechIcon::jsKey((string) $skill->name),
            'domain' => [
                'en' => $catLabel,
                'vi' => $catLabel,
            ],
            'project' => [
                'en' => $projectTitle ?: ($catLabel . ' Stack'),
                'vi' => $projectTitle ?: ('Chuyên môn ' . $catLabel),
            ],
            'desc' => [
                'en' => $rawDetails !== '' ? $rawDetails : ('Hands-on production implementation in ' . $catLabel . '.'),
                'vi' => $rawDetails !== '' ? $rawDetails : ('Áp dụng thực tế trong nhóm kỹ năng ' . $catLabel . '.'),
            ],
        ];
    }

    /**
     * Curated 5-tab showcase defaults ensuring clean EN/VI phrasing and balanced Orbit Wheel sizing.
     *
     * @return array<int, array<string, mixed>>
     */
    private static function curatedDefaults(): array
    {
        return [
            [
                'id' => 'core',
                'label' => ['en' => 'Core Stack (Top 10)', 'vi' => 'Công nghệ Cốt lõi (Top 10)'],
                'hubName' => ['en' => 'Core Stack', 'vi' => 'Core Stack'],
                'icon' => 'star',
                'iconColor' => 'text-amber-600 dark:text-amber-400',
                'items' => [
                    ['name' => 'Java', 'icon' => 'custom:java', 'domain' => ['en' => 'Enterprise Backend', 'vi' => 'Backend Doanh nghiệp'], 'project' => ['en' => 'Laundry Management System', 'vi' => 'Hệ thống Quản lý Giặt ủi'], 'desc' => ['en' => 'Spring Boot REST APIs, finite state order lifecycle & JPA', 'vi' => 'Spring Boot REST API, máy trạng thái đơn hàng & JPA']],
                    ['name' => 'PHP', 'icon' => 'custom:php', 'domain' => ['en' => 'Web Architecture', 'vi' => 'Kiến trúc Web'], 'project' => ['en' => 'Personal Portfolio & AIPower', 'vi' => 'Portfolio CMS & AIPower'], 'desc' => ['en' => 'Laravel 11 SSR, OOP modules & corporate portals', 'vi' => 'Laravel 11 SSR, module OOP & cổng thông tin doanh nghiệp']],
                    ['name' => 'Python', 'icon' => 'custom:python', 'domain' => ['en' => 'AI & Data Pipelines', 'vi' => 'AI & Xử lý dữ liệu'], 'project' => ['en' => 'Tourest Hybrid RAG', 'vi' => 'Chatbot Hybrid RAG Tourest'], 'desc' => ['en' => 'FastAPI, scikit-learn K-Means & FAISS pipelines', 'vi' => 'FastAPI, phân cụm K-Means & pipeline FAISS RAG']],
                    ['name' => 'Odoo ERP', 'icon' => 'custom:odoo', 'domain' => ['en' => 'Enterprise ERP', 'vi' => 'Hệ thống ERP'], 'project' => ['en' => 'Enmasys Enterprise Internship', 'vi' => 'Thực tập Doanh nghiệp Enmasys'], 'desc' => ['en' => 'Configured Retail, Accounting & B2B Sales ORM', 'vi' => 'Cấu hình phân hệ Bán lẻ, Kế toán & Sales B2B']],
                    ['name' => 'RAG AI', 'icon' => 'custom:rag', 'domain' => ['en' => 'Applied GenAI', 'vi' => 'AI Ứng dụng'], 'project' => ['en' => 'Tourest Travel Assistant', 'vi' => 'Trợ lý Du lịch AI Tourest'], 'desc' => ['en' => 'FAISS + BM25 hybrid retrieval, Cross-Encoder & OOS routing', 'vi' => 'Truy xuất lai FAISS + BM25, Cross-Encoder & chống ảo giác LLM']],
                    ['name' => 'ReactJS', 'icon' => 'custom:react', 'domain' => ['en' => 'Reactive Frontend', 'vi' => 'Giao diện Phản ứng'], 'project' => ['en' => 'Web Admin & Service UIs', 'vi' => 'Giao diện Quản trị Web'], 'desc' => ['en' => 'Modular hooks architecture & reactive components', 'vi' => 'Kiến trúc React Hooks tối ưu trạng thái']],
                    ['name' => 'Tailwind CSS', 'icon' => 'custom:tailwindcss', 'domain' => ['en' => 'UI Styling Engine', 'vi' => 'Hệ thống Giao diện'], 'project' => ['en' => 'Portfolio Design System', 'vi' => 'Hệ thống Design Portfolio'], 'desc' => ['en' => 'Design tokens, glassmorphism & dark/light parity', 'vi' => 'Design tokens, hiệu ứng kính mờ & Dark/Light Mode']],
                    ['name' => 'PostgreSQL', 'icon' => 'custom:postgresql', 'domain' => ['en' => 'Relational Database', 'vi' => 'CSDL Quan hệ'], 'project' => ['en' => 'AIPower & Odoo DBs', 'vi' => 'CSDL AIPower & Odoo'], 'desc' => ['en' => 'ACID transactions, JSONB columns & query tuning', 'vi' => 'Giao dịch ACID, trường JSONB & tối ưu truy vấn']],
                    ['name' => 'Docker', 'icon' => 'custom:docker', 'domain' => ['en' => 'Containerization', 'vi' => 'Đóng gói Container'], 'project' => ['en' => 'Microservice Compose Stacks', 'vi' => 'Môi trường Docker Compose'], 'desc' => ['en' => 'Isolated container networking & clean CI/CD parity', 'vi' => 'Cô lập dịch vụ container & đồng bộ môi trường deploy']],
                    ['name' => 'Figma', 'icon' => 'custom:figma', 'domain' => ['en' => 'UI/UX & Branding', 'vi' => 'Thiết kế UI/UX'], 'project' => ['en' => '2nd Prize UTH IT Logo', 'vi' => 'Giải Nhì Logo CNTT UTH'], 'desc' => ['en' => 'Editorial grid layouts, wireframes & vector brand art', 'vi' => 'Lưới bố cục tạp chí, wireframe & thiết kế nhận diện vector']],
                ],
            ],
            [
                'id' => 'backend',
                'label' => ['en' => 'Backend & ERP', 'vi' => 'Backend & ERP'],
                'hubName' => ['en' => 'Backend / ERP', 'vi' => 'Backend / ERP'],
                'icon' => 'server',
                'iconColor' => 'text-indigo-600 dark:text-indigo-400',
                'items' => [
                    ['name' => 'Laravel 11', 'icon' => 'custom:laravel', 'domain' => ['en' => 'Fullstack PHP', 'vi' => 'Fullstack PHP'], 'project' => ['en' => 'hothanhthien.io.vn CMS', 'vi' => 'CMS hothanhthien.io.vn'], 'desc' => ['en' => 'Blade SSR, Eloquent ORM, dynamic sitemaps & Auth', 'vi' => 'Blade SSR, Eloquent ORM, sitemap động & phân quyền']],
                    ['name' => 'Spring Boot', 'icon' => 'custom:spring', 'domain' => ['en' => 'Java Microservices', 'vi' => 'Java Backend'], 'project' => ['en' => 'Laundry Management API', 'vi' => 'API Quản lý Giặt ủi'], 'desc' => ['en' => 'Spring Data JPA, Hibernate & stateless REST', 'vi' => 'Spring Data JPA, Hibernate & RESTful API bảo mật']],
                    ['name' => 'FastAPI', 'icon' => 'custom:fastapi', 'domain' => ['en' => 'Async Python API', 'vi' => 'API Python Bất đồng bộ'], 'project' => ['en' => 'Tourest Backend API', 'vi' => 'Cổng API Tourest'], 'desc' => ['en' => 'SQLAlchemy ORM, WebSocket CSKH, PayPal & MoMo webhooks', 'vi' => 'SQLAlchemy ORM, WebSocket CSKH, thanh toán PayPal & MoMo']],
                    ['name' => 'Odoo ORM', 'icon' => 'custom:odoo', 'domain' => ['en' => 'ERP Workflows', 'vi' => 'Nghiệp vụ ERP'], 'project' => ['en' => 'Enmasys Enterprise ERP', 'vi' => 'Thực tập Enmasys ERP'], 'desc' => ['en' => 'B2B Sales workflow mapping & accounting customization', 'vi' => 'Chuẩn hóa quy trình Sales B2B & kế toán doanh nghiệp']],
                    ['name' => 'JWT & RBAC', 'icon' => 'custom:jwt', 'domain' => ['en' => 'Auth Security', 'vi' => 'Bảo mật & Phân quyền'], 'project' => ['en' => 'Laundry Security Guard', 'vi' => 'Lớp bảo vệ JWT Giặt ủi'], 'desc' => ['en' => 'Token lifecycle, OTP verification & role guards', 'vi' => 'Vòng đời token, xác thực OTP & kiểm soát vai trò']],
                ],
            ],
            [
                'id' => 'rag',
                'label' => ['en' => 'RAG AI & Databases', 'vi' => 'RAG AI & Cơ sở Dữ liệu'],
                'hubName' => ['en' => 'RAG & Data', 'vi' => 'RAG & Dữ liệu'],
                'icon' => 'database',
                'iconColor' => 'text-sky-700 dark:text-sky-400',
                'items' => [
                    ['name' => 'Vector Search', 'icon' => 'custom:vector', 'domain' => ['en' => 'Semantic Retrieval', 'vi' => 'Tìm kiếm Ngữ nghĩa'], 'project' => ['en' => 'Tourest Knowledge Store', 'vi' => 'Kho Tri thức Tourest'], 'desc' => ['en' => 'FAISS vector index + BM25 + Redis 2-tier cache', 'vi' => 'Chỉ mục vector FAISS + BM25 + bộ nhớ đệm Redis']],
                    ['name' => 'LLM Grounding', 'icon' => 'custom:llm', 'domain' => ['en' => 'RAG Pipeline', 'vi' => 'Chuỗi Xử lý RAG'], 'project' => ['en' => 'Zero-Hallucination Chatbot', 'vi' => 'Chatbot Chống Ảo giác'], 'desc' => ['en' => 'Context assembly strictly bound to private corpus', 'vi' => 'Tổng hợp ngữ cảnh chặt chẽ từ tài liệu nội bộ']],
                    ['name' => 'MySQL 8.0', 'icon' => 'custom:mysql', 'domain' => ['en' => 'Relational Engine', 'vi' => 'CSDL Quan hệ'], 'project' => ['en' => 'Portfolio & Laundry DB', 'vi' => 'CSDL Portfolio & Giặt ủi'], 'desc' => ['en' => '3NF normalization, foreign keys & index tuning', 'vi' => 'Chuẩn hóa 3NF, khóa ngoại & tối ưu chỉ mục']],
                    ['name' => 'PostgreSQL', 'icon' => 'custom:postgresql', 'domain' => ['en' => 'Enterprise SQL', 'vi' => 'SQL Doanh nghiệp'], 'project' => ['en' => 'AIPower & Odoo ERP', 'vi' => 'Hệ thống AIPower & Odoo'], 'desc' => ['en' => 'Relational views, JSONB indexing & reliability', 'vi' => 'View quan hệ, JSONB & đảm bảo toàn vẹn dữ liệu']],
                    ['name' => 'SQLite', 'icon' => 'custom:sqlite', 'domain' => ['en' => 'Embedded SQL', 'vi' => 'CSDL Nhúng'], 'project' => ['en' => 'CI/CD Testing & Dev', 'vi' => 'Kiểm thử & Phát triển'], 'desc' => ['en' => 'WAL concurrency & fast in-memory automated tests', 'vi' => 'Chế độ WAL & chạy test tự động tốc độ cao']],
                ],
            ],
            [
                'id' => 'frontend',
                'label' => ['en' => 'Frontend & UI/UX', 'vi' => 'Frontend & UI/UX'],
                'hubName' => ['en' => 'Frontend / UI', 'vi' => 'Giao diện UI'],
                'icon' => 'layers',
                'iconColor' => 'text-emerald-700 dark:text-emerald-400',
                'items' => [
                    ['name' => 'Next.js', 'icon' => 'custom:nextjs', 'domain' => ['en' => 'React SSR', 'vi' => 'React SSR'], 'project' => ['en' => 'Laundry Customer Portal', 'vi' => 'Cổng Khách hàng Giặt ủi'], 'desc' => ['en' => 'Server rendering, order tracking & API integration', 'vi' => 'SSR, theo dõi trạng thái đơn hàng & tích hợp API']],
                    ['name' => 'JavaScript ES6+', 'icon' => 'custom:js', 'domain' => ['en' => 'Interactive DOM', 'vi' => 'Tương tác DOM'], 'project' => ['en' => 'Orbit Wheel & Terminal UI', 'vi' => 'Vòng xoay 3D & Terminal'], 'desc' => ['en' => 'Trigonometry orbital math, spotlight & typewriter FX', 'vi' => 'Toán học lượng giác quỹ đạo, spotlight & hiệu ứng gõ chữ']],
                    ['name' => 'Tailwind CSS', 'icon' => 'custom:tailwindcss', 'domain' => ['en' => 'Design Systems', 'vi' => 'Hệ thống Thiết kế'], 'project' => ['en' => 'Pearl White Portfolio', 'vi' => 'Giao diện Portfolio Sáng'], 'desc' => ['en' => 'Responsive bento layout & clean dark/light modes', 'vi' => 'Bố cục bento đáp ứng & chế độ Sáng/Tối đồng bộ']],
                    ['name' => 'Blade Engine', 'icon' => 'custom:laravel', 'domain' => ['en' => 'SSR Components', 'vi' => 'Component SSR'], 'project' => ['en' => 'hothanhthien.io.vn Core', 'vi' => 'Lõi hothanhthien.io.vn'], 'desc' => ['en' => 'Zero hydration lag modular views & i18n bindings', 'vi' => 'Hiển thị tức thì không độ trễ & gắn kết đa ngôn ngữ']],
                    ['name' => 'UI/UX Craft', 'icon' => 'custom:figma', 'domain' => ['en' => 'Visual Identity', 'vi' => 'Nhận diện Thương hiệu'], 'project' => ['en' => '2nd Prize Logo UTH', 'vi' => 'Giải Nhì Logo CNTT UTH'], 'desc' => ['en' => 'Typography hierarchy, WCAG contrast & micro-UX', 'vi' => 'Phân cấp chữ, độ tương phản WCAG & vi tương tác']],
                ],
            ],
            [
                'id' => 'devops',
                'label' => ['en' => 'DevOps & Architecture', 'vi' => 'DevOps & Kiến trúc'],
                'hubName' => ['en' => 'DevOps / Ops', 'vi' => 'Vận hành'],
                'icon' => 'box',
                'iconColor' => 'text-rose-600 dark:text-rose-400',
                'items' => [
                    ['name' => 'Docker Compose', 'icon' => 'custom:docker', 'domain' => ['en' => 'Container Ops', 'vi' => 'Vận hành Container'], 'project' => ['en' => 'Local & AI Stacks', 'vi' => 'Môi trường Dev & AI'], 'desc' => ['en' => 'Multi-container services & environment parity', 'vi' => 'Đa dịch vụ container & đồng bộ dev-to-prod']],
                    ['name' => 'Nginx', 'icon' => 'custom:nginx', 'domain' => ['en' => 'Web Server', 'vi' => 'Máy chủ Web'], 'project' => ['en' => 'hothanhthien.io.vn Prod', 'vi' => 'Production hothanhthien.io.vn'], 'desc' => ['en' => 'Reverse proxy, SSL termination & HTTP/2 tuning', 'vi' => 'Reverse proxy, chứng chỉ SSL & tối ưu HTTP/2']],
                    ['name' => 'Git & GitHub', 'icon' => 'custom:git', 'domain' => ['en' => 'Version Control', 'vi' => 'Quản lý Mã nguồn'], 'project' => ['en' => '10+ Production Repos', 'vi' => '10+ Repo Thực tế'], 'desc' => ['en' => 'Semantic commits, clean branches & PR workflows', 'vi' => 'Commit chuẩn semantic & quản lý nhánh sạch sẽ']],
                    ['name' => 'Linux / Cron', 'icon' => 'custom:linux', 'domain' => ['en' => 'SysAdmin', 'vi' => 'Quản trị Hệ thống'], 'project' => ['en' => 'cPanel Scheduled Jobs', 'vi' => 'Tác vụ Tự động cPanel'], 'desc' => ['en' => 'Automated schedulers, queues & bash diagnostics', 'vi' => 'Lập lịch cron tự động, worker & xử lý log hệ thống']],
                ],
            ],
        ];
    }
}
