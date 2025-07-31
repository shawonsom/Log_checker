<?php
header('Content-Type: application/json');

// Define the base directory for logs
$baseDir = realpath(__DIR__);

// Get the folder from the 'folder' URL parameter. Default to '.' (current directory) if not set.
$folderName = isset($_GET['folder']) ? basename($_GET['folder']) : '.';

// Construct the path to the folder
$logDir = ($folderName === '.' || $folderName === 'log') ? $baseDir : $baseDir . DIRECTORY_SEPARATOR . $folderName;

// Security check: ensure the resolved path is within the base directory and is a directory
if (!realpath($logDir) || strpos(realpath($logDir), $baseDir) !== 0 || !is_dir($logDir)) {
    // Return a JSON error if the directory doesn't exist or is outside the allowed scope
    echo json_encode(['error' => 'Directory not found or access denied.']);
    exit;
}

// Find all files matching the pattern inside the correct directory
$files = glob($logDir . '/*.txt');

// Sort files by modification time, newest first
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

// Get the 10 most recent files
$latestFiles = array_slice($files, 0, 10);

// Get just the filenames from the paths
$fileNames = array_map('basename', $latestFiles);

// Return the list of filenames as JSON
echo json_encode($fileNames);
