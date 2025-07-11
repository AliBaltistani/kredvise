<?php

// Define the cache directories to clear
$cacheDirs = [
    __DIR__ . '/../bootstrap/cache/',
    __DIR__ . '/../storage/framework/cache/',
    __DIR__ . '/../storage/framework/views/',
    __DIR__ . '/../storage/framework/sessions/'
];

// Function to clear a directory without deleting the directory itself
function clearDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    
    $files = array_diff(scandir($dir), ['.', '..', '.gitignore']);
    
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            clearDirectory($path);
            @rmdir($path);
        } else {
            @unlink($path);
        }
    }
    
    return true;
}

// Clear each cache directory
foreach ($cacheDirs as $dir) {
    if (clearDirectory($dir)) {
        echo "Cleared: $dir<br>";
    } else {
        echo "Failed to clear: $dir<br>";
    }
}

echo "<br>Cache cleared successfully!";