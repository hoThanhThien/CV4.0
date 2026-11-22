<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Centralized driver-agnostic query and response cache for public portfolio pages.
 * Invalidated automatically by PortfolioContentObserver and scheduler/pivot hooks.
 */
class PortfolioCache
{
    public const KEY_HOME_SKILLS = 'portfolio.home.skills';
    public const KEY_HOME_SKILL_CATEGORIES = 'portfolio.home.skill_categories';
    public const KEY_HOME_EXPERIENCES = 'portfolio.home.experiences';
    public const KEY_HOME_FEATURED_PROJECTS = 'portfolio.home.featured_projects';
    public const KEY_HOME_RECENT_POSTS = 'portfolio.home.recent_posts';
    public const KEY_HOME_FEATURED_CERTIFICATES = 'portfolio.home.featured_certificates';
    public const KEY_CERTIFICATE_COUNTS = 'portfolio.certificates.counts';
    public const KEY_PROJECT_ARCHIVE_META = 'portfolio.projects.archive_meta';
    public const KEY_SITEMAP_XML = 'portfolio.sitemap.xml';
    private const KEY_SITEMAP_VARIANTS = 'portfolio.sitemap.variants';

    private const TTL_LONG_SECONDS = 21600; // 6 hours
    private const TTL_MEDIUM_SECONDS = 1800; // 30 minutes for time-sensitive published/sitemap views

    /**
     * @return Collection<int, Skill>
     */
    public static function rememberSkills(): Collection
    {
        return Cache::remember(self::KEY_HOME_SKILLS, self::TTL_LONG_SECONDS, static function (): Collection {
            return Skill::with('projects')->orderBy('order')->get();
        });
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function rememberSkillCategories(): array
    {
        return Cache::remember(self::KEY_HOME_SKILL_CATEGORIES, self::TTL_LONG_SECONDS, static function (): array {
            return SkillMatrixBuilder::build(self::rememberSkills());
        });
    }

    /**
     * @return Collection<int, Experience>
     */
    public static function rememberExperiences(): Collection
    {
        return Cache::remember(self::KEY_HOME_EXPERIENCES, self::TTL_LONG_SECONDS, static function (): Collection {
            return Experience::orderBy('order')->orderByDesc('start_date')->get();
        });
    }

    /**
     * @return Collection<int, Project>
     */
    public static function rememberFeaturedProjects(): Collection
    {
        return Cache::remember(self::KEY_HOME_FEATURED_PROJECTS, self::TTL_LONG_SECONDS, static function (): Collection {
            return Project::where('featured', true)
                ->with('technologies')
                ->orderBy('order')
                ->take(6)
                ->get();
        });
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public static function rememberRecentPosts(): Collection
    {
        return Cache::remember(self::KEY_HOME_RECENT_POSTS, self::TTL_MEDIUM_SECONDS, static function (): Collection {
            return BlogPost::published()->take(3)->get();
        });
    }

    /**
     * @return Collection<int, Certificate>
     */
    public static function rememberFeaturedCertificates(): Collection
    {
        return Cache::remember(self::KEY_HOME_FEATURED_CERTIFICATES, self::TTL_LONG_SECONDS, static function (): Collection {
            return Certificate::where('featured', true)
                ->orderBy('order')
                ->orderByDesc('created_at')
                ->get();
        });
    }

    /**
     * Aggregated category counters for /certificates (replaces 5 separate COUNT queries).
     *
     * @return array<string, int>
     */
    public static function certificateCategoryCounts(): array
    {
        return Cache::remember(self::KEY_CERTIFICATE_COUNTS, self::TTL_LONG_SECONDS, static function (): array {
            $grouped = Certificate::selectRaw('category, COUNT(*) as aggregate')
                ->groupBy('category')
                ->pluck('aggregate', 'category')
                ->all();

            $counts = [
                'all' => 0,
                'brochure' => (int) ($grouped['brochure'] ?? 0),
                'degree' => (int) ($grouped['degree'] ?? 0),
                'award' => (int) ($grouped['award'] ?? 0),
                'certificate' => (int) ($grouped['certificate'] ?? 0),
            ];

            $counts['all'] = array_sum(array_map('intval', $grouped));

            return $counts;
        });
    }

    /**
     * Top tech stack filters and totals for /projects archive hero and filter bar.
     *
     * @return array{allTechNames: array<int, string>, totalCount: int, featuredCount: int}
     */
    public static function projectArchiveMeta(): array
    {
        return Cache::remember(self::KEY_PROJECT_ARCHIVE_META, self::TTL_LONG_SECONDS, static function (): array {
            $allProjects = Project::with('technologies')->get();

            return [
                'allTechNames' => $allProjects->flatMap->technologies->pluck('name')->countBy()->sortDesc()->take(10)->keys()->all(),
                'totalCount' => $allProjects->count(),
                'featuredCount' => $allProjects->where('featured', true)->count(),
            ];
        });
    }

    /**
     * Cache rendered XML sitemap keyed cleanly per base host URL.
     */
    public static function sitemapXml(string $baseUrl, Closure $builder): string
    {
        $variantKey = self::KEY_SITEMAP_XML . '.' . md5(rtrim($baseUrl, '/'));

        $variants = Cache::get(self::KEY_SITEMAP_VARIANTS, []);
        if (!is_array($variants)) {
            $variants = [];
        }
        if (!in_array($variantKey, $variants, true)) {
            $variants[] = $variantKey;
            Cache::put(self::KEY_SITEMAP_VARIANTS, $variants, self::TTL_LONG_SECONDS);
        }

        return Cache::remember($variantKey, self::TTL_MEDIUM_SECONDS, $builder);
    }

    /**
     * Invalidate all cached portfolio fragments and host sitemap variants.
     */
    public static function forgetAll(): void
    {
        foreach ([
            self::KEY_HOME_SKILLS,
            self::KEY_HOME_SKILL_CATEGORIES,
            self::KEY_HOME_EXPERIENCES,
            self::KEY_HOME_FEATURED_PROJECTS,
            self::KEY_HOME_RECENT_POSTS,
            self::KEY_HOME_FEATURED_CERTIFICATES,
            self::KEY_CERTIFICATE_COUNTS,
            self::KEY_PROJECT_ARCHIVE_META,
            self::KEY_SITEMAP_XML,
        ] as $key) {
            Cache::forget($key);
        }

        $variants = Cache::get(self::KEY_SITEMAP_VARIANTS, []);
        if (is_array($variants)) {
            foreach ($variants as $variantKey) {
                if (is_string($variantKey) && $variantKey !== '') {
                    Cache::forget($variantKey);
                }
            }
        }
        Cache::forget(self::KEY_SITEMAP_VARIANTS);
    }
}
