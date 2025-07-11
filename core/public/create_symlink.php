<?php

// Create a symbolic link from the assets directory to the public directory
$target = realpath(__DIR__ . '/../../assets');
$link = __DIR__ . '/assets';

if (!file_exists($link)) {
    if (symlink($target, $link)) {
        echo "Symbolic link created successfully.\n";
    } else {
        echo "Failed to create symbolic link.\n";
        
        // If symlink fails, try to create a directory junction (Windows alternative)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = 'mklink /J "' . $link . '" "' . $target . '"';
            echo "Attempting to create directory junction with command: $command\n";
            exec($command, $output, $return);
            echo "Command output: " . implode("\n", $output) . "\n";
            echo "Return code: $return\n";
        }
    }
} else {
    echo "Link already exists.\n";
}

// If symlink fails, copy the assets directory to the public directory
if (!file_exists($link)) {
    echo "Attempting to copy assets directory...\n";
    
    function recursiveCopy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (($file = readdir($dir)) !== false) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    recursiveCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    
    recursiveCopy($target, $link);
    echo "Copy completed.\n";
}

echo "Done.\n";