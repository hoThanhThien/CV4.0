<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Project::with('technologies')->get() as $p) {
    echo "ID: " . $p->id . "\n";
    echo "Title: " . $p->title . "\n";
    echo "Image: " . $p->image . "\n";
    $imgPath = public_path('storage/' . $p->image);
    echo "Storage file exists? " . (file_exists($imgPath) ? 'YES' : 'NO') . " (" . $imgPath . ")\n";
    echo "Raw description: " . $p->description . "\n";
    echo "Clean description: " . $p->clean_description . "\n";
    echo "Summary: " . ($p->summary ?? 'NULL') . "\n";
    echo "Featured: " . ($p->featured ? 'YES' : 'NO') . "\n";
    echo "Technologies: " . $p->technologies->pluck('name')->implode(', ') . "\n";
    echo "--------------------------------------------------------\n";
}
