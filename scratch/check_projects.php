<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Project::all() as $p) {
    echo "ID: {$p->id}\n";
    echo "  Title: {$p->title}\n";
    echo "  Image: {$p->image}\n";
    echo "  Summary: {$p->summary}\n";
    echo "  Clean Desc: {$p->clean_description}\n";
    echo "  Raw Desc: " . substr($p->description, 0, 100) . "...\n";
    echo "----------------------------------------\n";
}
