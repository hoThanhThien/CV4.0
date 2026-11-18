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

        // 3. Projects from DB (with technologies relation)
        foreach (Project::with('technologies')->orderBy('order')->get() as $proj) {
            $techNames = $proj->technologies->pluck('name')->implode(', ');
            $desc = trim((string) ($proj->clean_description ?: strip_tags((string) $proj->description)));

            $chunks[] = [
                'id' => 'project:' . $proj->id,
                'type' => 'project',
                'title' => $proj->title,
                'url' => '/projects/' . $proj->id,
                'content' => trim(implode("\n", array_filter([
                    'Project: ' . $proj->title . ($proj->role ? ' (' . $proj->role . ')' : '') . ($proj->period ? ' [' . $proj->period . ']' : ''),
                    'Summary: ' . Str::limit($desc, 900),
                    $techNames !== '' ? 'Tech Stack: ' . $techNames : null,
                    $proj->github_url ? 'GitHub: ' . $proj->github_url : null,
                    $proj->demo_url ? 'Live Demo: ' . $proj->demo_url : null,
                ]))),
            ];
        }

        // 4. Certificates & Official Documents from DB
        foreach (Certificate::orderBy('order')->get() as $cert) {
            $chunks[] = [
                'id' => 'cert:' . $cert->id,
                'type' => 'certificate',
                'title' => $cert->title,
                'url' => '/certificates?category=' . urlencode((string) ($cert->category ?: 'all')),
                'content' => trim(implode("\n", array_filter([
                    'Credential (' . ($cert->category ?: 'certificate') . '): ' . $cert->title,
                    $cert->issuer ? 'Issuer: ' . $cert->issuer : null,
                    $cert->issue_date ? 'Issued: ' . $cert->issue_date : null,
                    $cert->description ? 'Description: ' . trim(strip_tags((string) $cert->description)) : null,
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
            $bodyExcerpt = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $post->content))), 900);

            $chunks[] = [
                'id' => 'blog:' . $post->id,
                'type' => 'blog',
                'title' => $post->title,
                'url' => '/blog/' . $post->slug,
                'content' => trim(implode("\n", array_filter([
                    'Published Technical Article: ' . $post->title . ($post->category ? ' [' . $post->category . ']' : ''),
                    $post->excerpt ? 'Excerpt: ' . trim(strip_tags((string) $post->excerpt)) : null,
                    $bodyExcerpt !== '' ? 'Article Highlights: ' . $bodyExcerpt : null,
                ]))),
            ];
        }

        return $chunks;
    }
}
