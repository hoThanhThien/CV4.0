<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== EXPERIENCES ===\n";
foreach (App\Models\Experience::orderBy('order')->get() as $e) {
    echo "{$e->id} | {$e->role} @ {$e->company} | {$e->start_date} - {$e->end_date}\n{$e->description}\n\n";
}

echo "=== BLOG POSTS ===\n";
foreach (App\Models\BlogPost::published()->latest()->take(5)->get() as $b) {
    echo "{$b->id} | {$b->title} | {$b->slug} | {$b->excerpt}\n";
}

echo "=== CERTIFICATES / FILES ===\n";
if (class_exists('App\Models\Certificate')) {
    foreach (App\Models\Certificate::all() as $c) {
        echo "{$c->id} | {$c->title} | {$c->issuer} | {$c->file_path}\n";
    }
}

echo "=== SKILLS ===\n";
foreach (App\Models\Skill::all() as $s) {
    echo "{$s->name} ({$s->category} - {$s->level}%)\n";
}

