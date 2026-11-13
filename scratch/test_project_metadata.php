<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Project::all() as $p) {
    echo "Project: {$p->title}\n";
    echo "  Period: {$p->period}\n";
    echo "  Role: {$p->role}\n";
    echo "  Summary: {$p->summary}\n";
    echo "  Has real image: " . ($p->has_real_image ? 'YES' : 'NO') . "\n";
    echo "--------------------------------------------------------\n";
}
