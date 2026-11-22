<?php

namespace App\Services\RAG;

use App\Models\BlogPost;
use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Support\Str;

/**
 * Extracts and normalizes live portfolio entities into retrievable knowledge chunks for RAG.
 */
class PortfolioCorpusBuilder
{
    /**
     * Build grounded knowledge chunks from the database and verified profile records.
     *
     * @return array<int, array{id: string, type: string, title: string, url: string, content: string}>
     */
    public static function buildChunks(): array
    {
        $chunks = [];

        // 1. Verified Candidate Profile & Academic Honors
        $chunks[] = [
            'id' => 'profile:uth',
            'type' => 'profile',
            'title' => 'Ho Thanh Thien — Academic & Core Profile (UTH)',
            'url' => '/about',
            'content' => implode("\n", [
                'Candidate: Ho Thanh Thien (Hồ Thành Thiện), Full-Stack Web & Applied RAG AI Software Engineer based in Ho Chi Minh City, Vietnam.',
                'Education: Bachelor of Engineering (B.Eng) in Information Technology at University of Transport Ho Chi Minh City (UTH — Trường ĐH Giao thông Vận tải TP.HCM), Distinction / Excellent Classification.',
                'Academic Metrics: Cumulative GPA 3.6/4.0, Latest Semester GPA 4.0/4.0 (Perfect Term Score).',
                'Design Honor: 2nd Prize in the UTH IT Faculty Logo & Visual Identity Design Contest.',
            ]),
        ];

        $chunks[] = [
            'id' => 'profile:contact',
            'type' => 'profile',
            'title' => 'Contact, Availability & Direct Channels',
            'url' => '/contact',
            'content' => implode("\n", [
                'Availability: Open for Full-Stack Developer, Backend Engineer (Java/PHP/Python), Odoo ERP, and Applied AI/RAG Engineer roles in Ho Chi Minh City or remote.',
                'Email: hothanhthien119@gmail.com | Phone: +84 375 227 764 | GitHub: https://github.com/hoThanhThien | Portfolio CMS: https://hothanhthien.io.vn',
            ]),
        ];

        // 2. Work Experiences from DB
        foreach (Experience::orderBy('order')->orderByDesc('start_date')->get() as $exp) {
            $period = method_exists($exp, 'getFormattedDateAttribute') ? $exp->formatted_date : '';
            $tags = !empty($exp->tech_tags) ? ' Technologies: ' . implode(', ', $exp->tech_tags) . '.' : '';

            $chunks[] = [
                'id' => 'exp:' . $exp->id,
                'type' => 'experience',
                'title' => trim($exp->position . ' @ ' . $exp->company),
                'url' => '/about',
                'content' => trim(sprintf(
                    "Experience: %s as %s (%s).\nDetails: %s.%s",
                    $exp->company,
                    $exp->position,
                    $period,
                    trim(strip_tags((string) $exp->description)),
                    $tags
                )),
            ];
        }

        // 3. Projects from DB (with technologies & bilingual translations)
        foreach (Project::with('technologies')->orderBy('order')->get() as $proj) {
            $techNames = $proj->technologies->pluck('name')->implode(', ');
            $descEn = Str::limit(trim((string) ($proj->cleanDescriptionFor('en') ?: strip_tags((string) $proj->description))), 900);
            $descVi = Str::limit(trim((string) ($proj->cleanDescriptionFor('vi') ?: strip_tags((string) $proj->description))), 900);

            $chunks[] = [
                'id' => 'project:' . $proj->id,
                'type' => 'project',
                'title' => $proj->title,
                'url' => '/projects/' . $proj->id,
                'content' => trim(implode("\n", array_filter([
                    self::formatBilingualLine(
                        'Project',
                        $proj->title . ($proj->role ? ' (' . $proj->role . ')' : '') . ($proj->period ? ' [' . $proj->period . ']' : ''),
                        $proj->translationValue('title', 'en'),
                        $proj->translationValue('title', 'vi')
                    ),
                    self::formatBilingualLine('Summary', $descEn !== '' ? $descEn : $descVi, $descEn, $descVi),
                    $techNames !== '' ? 'Tech Stack: ' . $techNames : null,
                    $proj->github_url ? 'GitHub: ' . $proj->github_url : null,
                    $proj->demo_url ? 'Live Demo: ' . $proj->demo_url : null,
                ]))),
            ];
        }

        // 4. Certificates & Official Documents from DB (with bilingual translations)
        foreach (Certificate::orderBy('order')->get() as $cert) {
            $chunks[] = [
                'id' => 'cert:' . $cert->id,
                'type' => 'certificate',
                'title' => $cert->title,
                'url' => '/certificates?category=' . urlencode((string) ($cert->category ?: 'all')),
                'content' => trim(implode("\n", array_filter([
                    self::formatBilingualLine(
                        'Credential (' . ($cert->category ?: 'certificate') . ')',
                        $cert->title,
                        $cert->translationValue('title', 'en'),
                        $cert->translationValue('title', 'vi')
                    ),
                    self::formatBilingualLine(
                        'Issuer',
                        $cert->issuer,
                        $cert->translationValue('issuer', 'en'),
                        $cert->translationValue('issuer', 'vi')
                    ),
                    $cert->issue_date ? 'Issued: ' . $cert->issue_date : null,
                    self::formatBilingualLine(
                        'Description',
                        $cert->description ? trim(strip_tags((string) $cert->description)) : null,
                        self::cleanPlainText($cert->translationValue('description', 'en'), 500),
                        self::cleanPlainText($cert->translationValue('description', 'vi'), 500)
                    ),
                ]))),
            ];
        }

        // 5. Skills Grouped by Category from DB
        $groupedSkills = Skill::orderBy('order')->get()->groupBy('category');
        foreach ($groupedSkills as $category => $items) {
            $catName = trim((string) $category) ?: 'General';
            $skillLines = $items->map(function (Skill $s) {
                $det = trim((string) $s->details);
                return $det !== '' ? "{$s->name} ({$det})" : $s->name;
            })->implode('; ');

            $chunks[] = [
                'id' => 'skill:' . Str::slug($catName),
                'type' => 'skill',
                'title' => 'Skills — ' . $catName,
                'url' => '/#skills',
                'content' => "Technical Skills ({$catName}): {$skillLines}",
            ];
        }

        // 6. Published Blog Posts ONLY (strictly excludes drafts, pending_review, and future scheduled posts)
        foreach (BlogPost::published()->take(15)->get() as $post) {
            $bodyExcerpt = self::cleanPlainText($post->content, 900);
            $bodyEn = self::cleanPlainText($post->translationValue('content', 'en'), 700);
            $bodyVi = self::cleanPlainText($post->translationValue('content', 'vi'), 700);

            $chunks[] = [
                'id' => 'blog:' . $post->id,
                'type' => 'blog',
                'title' => $post->title,
                'url' => '/blog/' . $post->slug,
                'content' => trim(implode("\n", array_filter([
                    self::formatBilingualLine(
                        'Published Technical Article' . ($post->category ? ' [' . $post->category . ']' : ''),
                        $post->title,
                        $post->translationValue('title', 'en'),
                        $post->translationValue('title', 'vi')
                    ),
                    self::formatBilingualLine(
                        'Excerpt',
                        self::cleanPlainText($post->excerpt, 500),
                        self::cleanPlainText($post->translationValue('excerpt', 'en'), 500),
                        self::cleanPlainText($post->translationValue('excerpt', 'vi'), 500)
                    ),
                    self::formatBilingualLine('Article Highlights', $bodyExcerpt, $bodyEn, $bodyVi),
                ]))),
            ];
        }

        return $chunks;
    }

    /**
     * Format a canonical field along with any distinct EN/VI translations for hybrid RAG retrieval.
     */
    protected static function formatBilingualLine(string $label, ?string $canonical, ?string $en = null, ?string $vi = null): ?string
    {
        $parts = [];
        $seen = [];

        foreach (['' => $canonical, 'EN' => $en, 'VI' => $vi] as $tag => $value) {
            $clean = trim((string) $value);
            if ($clean === '') {
                continue;
            }
            $fingerprint = mb_strtolower($clean);
            if (isset($seen[$fingerprint])) {
                continue;
            }
            $seen[$fingerprint] = true;
            $parts[] = ($tag !== '' && !empty($parts)) ? "{$tag}: {$clean}" : $clean;
        }

        return empty($parts) ? null : $label . ': ' . implode(' | ', $parts);
    }

    protected static function cleanPlainText(?string $html, int $limit): ?string
    {
        $text = trim((string) preg_replace('/\s+/', ' ', strip_tags((string) $html)));
        return $text !== '' ? Str::limit($text, $limit) : null;
    }
}
