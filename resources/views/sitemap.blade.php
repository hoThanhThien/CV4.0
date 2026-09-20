{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
@php
    $staticPages = [
        ['url' => url('/'), 'freq' => 'weekly', 'priority' => '1.0'],
        ['url' => url('/about'), 'freq' => 'monthly', 'priority' => '0.8'],
        ['url' => url('/contact'), 'freq' => 'monthly', 'priority' => '0.9'],
        ['url' => url('/projects'), 'freq' => 'weekly', 'priority' => '0.9'],
        ['url' => url('/blog'), 'freq' => 'weekly', 'priority' => '0.8'],
    ];
@endphp

    {{-- Static Pages for both VI and EN --}}
    @foreach ($staticPages as $page)
        @foreach (['vi', 'en'] as $lang)
        <url>
            <loc>{{ $page['url'] }}?lang={{ $lang }}</loc>
            <xhtml:link rel="alternate" hreflang="vi" href="{{ $page['url'] }}?lang=vi"/>
            <xhtml:link rel="alternate" hreflang="en" href="{{ $page['url'] }}?lang=en"/>
            <xhtml:link rel="alternate" hreflang="x-default" href="{{ $page['url'] }}"/>
            <lastmod>{{ now()->toAtomString() }}</lastmod>
            <changefreq>{{ $page['freq'] }}</changefreq>
            <priority>{{ $page['priority'] }}</priority>
        </url>
        @endforeach
    @endforeach

    {{-- Dynamic Project Pages --}}
    @foreach ($projects as $project)
        @foreach (['vi', 'en'] as $lang)
        <url>
            <loc>{{ url('/projects/' . $project->id) }}?lang={{ $lang }}</loc>
            <xhtml:link rel="alternate" hreflang="vi" href="{{ url('/projects/' . $project->id) }}?lang=vi"/>
            <xhtml:link rel="alternate" hreflang="en" href="{{ url('/projects/' . $project->id) }}?lang=en"/>
            <xhtml:link rel="alternate" hreflang="x-default" href="{{ url('/projects/' . $project->id) }}"/>
            <lastmod>{{ $project->updated_at ? $project->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
        @endforeach
    @endforeach

    {{-- Dynamic Blog Posts --}}
    @foreach ($posts as $post)
        @foreach (['vi', 'en'] as $lang)
        <url>
            <loc>{{ url('/blog/' . $post->slug) }}?lang={{ $lang }}</loc>
            <xhtml:link rel="alternate" hreflang="vi" href="{{ url('/blog/' . $post->slug) }}?lang=vi"/>
            <xhtml:link rel="alternate" hreflang="en" href="{{ url('/blog/' . $post->slug) }}?lang=en"/>
            <xhtml:link rel="alternate" hreflang="x-default" href="{{ url('/blog/' . $post->slug) }}"/>
            <lastmod>{{ $post->updated_at ? $post->updated_at->toAtomString() : now()->toAtomString() }}</lastmod>
            <changefreq>monthly</changefreq>
            <priority>0.7</priority>
        </url>
        @endforeach
    @endforeach
</urlset>
