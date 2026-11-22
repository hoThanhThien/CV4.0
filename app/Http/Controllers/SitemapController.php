<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Project;
use App\Support\PortfolioCache;
use Illuminate\Http\Response;

/**
 * Generates bilingual hreflang XML sitemap from live database projects and published blog posts.
 */
class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = PortfolioCache::sitemapXml(url('/'), function (): string {
            $projects = Project::all();
            $posts = BlogPost::published()->get();

            $staticPages = [
                ['url' => url('/'), 'freq' => 'weekly', 'priority' => '1.0'],
                ['url' => url('/about'), 'freq' => 'monthly', 'priority' => '0.8'],
                ['url' => url('/contact'), 'freq' => 'monthly', 'priority' => '0.9'],
                ['url' => url('/projects'), 'freq' => 'weekly', 'priority' => '0.9'],
                ['url' => url('/certificates'), 'freq' => 'weekly', 'priority' => '0.85'],
                ['url' => url('/blog'), 'freq' => 'weekly', 'priority' => '0.8'],
            ];

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

            foreach ($staticPages as $page) {
                $xml .= $this->buildUrlEntries($page['url'], now()->toAtomString(), $page['freq'], $page['priority']);
            }

            foreach ($projects as $project) {
                $lastmod = $project->updated_at ? $project->updated_at->toAtomString() : now()->toAtomString();
                $xml .= $this->buildUrlEntries(url('/projects/' . $project->id), $lastmod, 'monthly', '0.7');
            }

            foreach ($posts as $post) {
                $lastmod = $post->updated_at ? $post->updated_at->toAtomString() : now()->toAtomString();
                $xml .= $this->buildUrlEntries(url('/blog/' . $post->slug), $lastmod, 'monthly', '0.7');
            }

            $xml .= '</urlset>';

            return $xml;
        });

        return response($xml, 200, [
            'Content-Type' => 'text/xml; charset=utf-8',
        ]);
    }

    private function buildUrlEntries(string $baseUrl, string $lastmod, string $changefreq, string $priority): string
    {
        $entries = '';
        $viHref = htmlspecialchars($baseUrl . '?lang=vi', ENT_XML1);
        $enHref = htmlspecialchars($baseUrl . '?lang=en', ENT_XML1);
        $defaultHref = htmlspecialchars($baseUrl, ENT_XML1);

        foreach (['vi', 'en'] as $lang) {
            $loc = htmlspecialchars($baseUrl . '?lang=' . $lang, ENT_XML1);
            $entries .= "  <url>\n";
            $entries .= "    <loc>{$loc}</loc>\n";
            $entries .= "    <xhtml:link rel=\"alternate\" hreflang=\"vi\" href=\"{$viHref}\"/>\n";
            $entries .= "    <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"{$enHref}\"/>\n";
            $entries .= "    <xhtml:link rel=\"alternate\" hreflang=\"x-default\" href=\"{$defaultHref}\"/>\n";
            $entries .= "    <lastmod>{$lastmod}</lastmod>\n";
            $entries .= "    <changefreq>{$changefreq}</changefreq>\n";
            $entries .= "    <priority>{$priority}</priority>\n";
            $entries .= "  </url>\n";
        }

        return $entries;
    }
}
