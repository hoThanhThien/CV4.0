<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$projects = App\Models\Project::with('technologies')->orderBy('order')->get();
foreach ($projects as $proj) {
    echo "ID: {$proj->id}\n";
    echo "Title: {$proj->title}\n";
    echo "Slug: {$proj->slug}\n";
    echo "Image: " . var_export($proj->image, true) . "\n";
    echo "Demo URL: " . var_export($proj->demo_url, true) . "\n";
    echo "Github URL: " . var_export($proj->github_url, true) . "\n";
    echo "Raw Description:\n{$proj->description}\n";
    echo "Techs: " . $proj->technologies->pluck('name')->implode(', ') . "\n";
    echo "========================================\n";
}

