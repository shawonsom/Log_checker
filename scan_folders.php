<?php
header('Content-Type: application/json');

// Define the base directory for logs
$baseDir = realpath(__DIR__);

// Get all directories directly under the base directory
$subDirs = array_filter(glob($baseDir . '/*', GLOB_ONLYDIR));

// Create a list of folder data
$folders = [];

// Add the root log directory to the list
$folders[] = [
    'path' => '.',
    'name' => 'log (root)' // A user-friendly name
];

// Add subdirectories
foreach ($subDirs as $dir) {
    $folders[] = [
        'path' => basename($dir),
        'name' => basename($dir)
    ];
}

echo json_encode($folders);
