<?php
$zip = new ZipArchive();
if ($zip->open('laravel_core.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $dir = new RecursiveDirectoryIterator('.');
    $ite = new RecursiveIteratorIterator($dir);
    $exclude = ['node_modules', '.git', 'laravel_core.zip', 'public_html.zip', 'storage/framework/cache', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs', 'bootstrap/cache/packages.php', 'bootstrap/cache/services.php', 'bootstrap/cache/config.php', 'bootstrap/cache/routes', 'bootstrap/cache/events.php', 'database/database.sqlite', '.env', 'public'];
    
    $count = 0;
    foreach ($ite as $file) {
        $path = $file->getPathname();
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#^\./+#', '', $path);
        
        $skip = false;
        foreach($exclude as $ex) {
            if(str_starts_with($path, $ex)) {
                $skip = true;
                break;
            }
        }
        if(!$skip && $file->isFile()) {
            $zip->addFile($file->getPathname(), $path);
            $count++;
        }
    }
    $zip->close();
    echo 'Created laravel_core.zip with ' . $count . ' files.' . PHP_EOL;
} else {
    echo 'Failed to create laravel_core.zip';
}

$zip = new ZipArchive();
if ($zip->open('public_html.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $dir = new RecursiveDirectoryIterator('public');
    $ite = new RecursiveIteratorIterator($dir);
    $count = 0;
    foreach ($ite as $file) {
        if($file->isFile()) {
            $path = str_replace('\\', '/', $file->getPathname());
            $localPath = preg_replace('#^public/+#', '', $path);
            $zip->addFile($file->getPathname(), $localPath);
            $count++;
        }
    }
    $zip->close();
    echo 'Created public_html.zip with ' . $count . ' files.' . PHP_EOL;
} else {
    echo 'Failed to create public_html.zip';
}
