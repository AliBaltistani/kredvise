<?php

// Create a symbolic link from the storage/app/public directory to the public/storage directory
$target = realpath(__DIR__ . '/../storage/app/public');
$link = __DIR__ . '/storage';

// Remove the placeholder file if it exists
if (file_exists($link) && !is_dir($link)) {
    unlink($link);
}

if (!file_exists($link)) {
    if (symlink($target, $link)) {
        echo "Storage symbolic link created successfully.\n";
    } else {
        echo "Failed to create storage symbolic link.\n";
        
        // If symlink fails, try to create a directory junction (Windows alternative)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = 'mklink /J "' . $link . '" "' . $target . '"';
            echo "Attempting to create storage directory junction with command: $command\n";
            exec($command, $output, $return);
            echo "Command output: " . implode("\n", $output) . "\n";
            echo "Return code: $return\n";
        }
    }
} else {
    echo "Storage link already exists.\n";
}

echo "Done.\n";