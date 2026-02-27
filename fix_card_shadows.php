<?php

$directory = __DIR__ . '/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$count = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $originalContent = $content;

        // Look for lines containing both 'card' and 'shadow-sm' in the class attribute
        // This is a naive line-by-line check which is usually sufficient for blade files
        $lines = explode("\n", $content);
        $newLines = [];
        $modified = false;

        foreach ($lines as $line) {
            if (strpos($line, 'class=') !== false && strpos($line, 'card') !== false && strpos($line, 'shadow-sm') !== false) {
                 // Remove shadow-sm
                 $newLine = str_replace('shadow-sm', '', $line);
                 // Clean up double spaces
                 $newLine = str_replace('  ', ' ', $newLine);
                 $newLines[] = $newLine;
                 $modified = true;
            } else {
                $newLines[] = $line;
            }
        }

        if ($modified) {
            file_put_contents($file->getPathname(), implode("\n", $newLines));
            echo "Updated: " . $file->getPathname() . "\n";
            $count++;
        }
    }
}

echo "Total files updated: $count\n";
