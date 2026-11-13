<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$urls = [
    '/' => 'Home default',
    '/?lang=vi' => 'Home VI',
    '/?lang=en' => 'Home EN',
    '/about?lang=vi' => 'About VI',
    '/about?lang=en' => 'About EN',
    '/projects?lang=vi' => 'Projects VI',
    '/projects?lang=en' => 'Projects EN',
    '/projects/1?lang=vi' => 'Project 1 VI',
    '/projects/1?lang=en' => 'Project 1 EN',
    '/certificates?lang=vi' => 'Certificates VI',
    '/certificates?lang=en' => 'Certificates EN',
    '/blog?lang=vi' => 'Blog VI',
    '/blog?lang=en' => 'Blog EN',
    '/blog/mastering-react-hooks?lang=vi' => 'Blog Post VI',
    '/blog/mastering-react-hooks?lang=en' => 'Blog Post EN',
    '/contact?lang=vi' => 'Contact VI',
    '/contact?lang=en' => 'Contact EN',
];

$allOk = true;
foreach ($urls as $url => $label) {
    $parts = parse_url($url);
    $path = $parts['path'] ?? '/';
    $query = [];
    if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
    }

    $request = Illuminate\Http\Request::create($path, 'GET', $query);
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();

    $hasErrors = str_contains($content, 'Fatal error') || str_contains($content, 'Exception') || str_contains($content, 'ParseError');

    if ($status === 200 && !$hasErrors) {
        echo "[PASS] $label ($url) -> 200 OK\n";
    } else {
        echo "[FAIL] $label ($url) -> Status: $status, HasErrors: " . ($hasErrors ? 'YES' : 'NO') . "\n";
        $allOk = false;
    }
    $kernel->terminate($request, $response);
}

if ($allOk) {
    echo "\n>>> ALL 17 PAGES PASSED WITH 200 OK AND ZERO ERRORS! <<<\n";
} else {
    echo "\n>>> SOME PAGES FAILED! <<<\n";
    exit(1);
}

