<?php

$directory = __DIR__ . '/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$count = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $originalContent = $content;

        // Replace 'card-header bg-primary text-white' with 'card-header'
        $content = preg_replace('/class="card-header\s+bg-primary\s+text-white"/', 'class="card-header"', $content);
        
        // Replace 'card-header bg-primary' with 'card-header' (trailing quote)
        $content = preg_replace('/class="card-header\s+bg-primary"/', 'class="card-header"', $content);

        // Replace 'card-header bg-primary text-white ' (with trailing space for extra classes)
        $content = preg_replace('/class="card-header\s+bg-primary\s+text-white\s+/', 'class="card-header ', $content);

        // Replace 'card-header bg-primary ' (with trailing space)
        $content = preg_replace('/class="card-header\s+bg-primary\s+/', 'class="card-header ', $content);

        if ($content !== $originalContent) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
            $count++;
        }
    }
}

echo "Total files updated: $count\n";
