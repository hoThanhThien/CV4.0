<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$certs = App\Models\Certificate::orderBy('order')->get();
foreach ($certs as $c) {
    echo "ID: {$c->id} | Title: {$c->title}\n";
    echo "Category: {$c->category} | File Path: {$c->file_path} | Size: {$c->file_size} | Type: {$c->file_type}\n";
    echo "Thumbnail: {$c->thumbnail}\n";
    echo "Description: {$c->description}\n";
    echo "Has actual file on disk? " . (file_exists(public_path('storage/' . ltrim($c->file_path, '/'))) || file_exists(public_path(ltrim($c->file_path, '/'))) ? 'YES' : 'NO') . "\n";
    echo "----------------------------------------\n";
}

