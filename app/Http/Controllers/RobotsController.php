<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * Serves dynamic robots.txt rules protecting admin and operational setup routes.
 */
class RobotsController extends Controller
{
    public function index(): Response
    {
        $sitemapUrl = url('/sitemap.xml');
        $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /admin/\nDisallow: /setup-db\nDisallow: /setup-db/\nDisallow: /setup-migrate\nDisallow: /cron/\n\nSitemap: {$sitemapUrl}\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
