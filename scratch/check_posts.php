<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$posts = App\Models\BlogPost::all();
foreach ($posts as $p) {
    echo "ID: {$p->id}\n";
    echo "Title: {$p->title}\n";
    echo "Slug: {$p->slug}\n";
    echo "Image: " . var_export($p->image, true) . "\n";
    echo "OG Image: " . var_export($p->og_image, true) . "\n";
    echo "Content Start (200 chars):\n" . substr($p->content, 0, 200) . "\n";
    echo "----------------------------------------\n";
}

