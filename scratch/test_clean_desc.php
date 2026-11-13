<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$projects = App\Models\Project::orderBy('order')->get();
foreach ($projects as $proj) {
    echo "=== Project #{$proj->id}: {$proj->title} ===\n";
    echo "Raw: " . substr($proj->description, 0, 80) . "...\n";
    echo "Clean Description:\n" . $proj->clean_description . "\n";
    echo "\n";
}

