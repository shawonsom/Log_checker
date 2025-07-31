<?php
if (isset($_POST['file']) && isset($_POST['keyword']) && isset($_POST['folder'])) {
    $folderName = basename($_POST['folder']);
    $fileName = basename($_POST['file']);
    $keyword = $_POST['keyword'];

    // Define the base directory for logs
    $baseDir = realpath(__DIR__);

    // Construct the path to the folder
    $dir = ($folderName === '.' || $folderName === 'log') ? $baseDir : $baseDir . DIRECTORY_SEPARATOR . $folderName;

    // Construct the full file path
    $filePath = $dir . DIRECTORY_SEPARATOR . $fileName;

    // Security check: ensure the resolved path is within the base directory
    if (realpath($filePath) && strpos(realpath($filePath), $baseDir) === 0 && file_exists($filePath)) {
        $results = [];
        $handle = fopen($filePath, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                // Use case-insensitive search for better usability
                if (stripos($line, $keyword) !== false) {
                    $results[] = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
                }
            }
            fclose($handle);
        }
        // Return results as plain text, but HTML-encoded
        header('Content-Type: text/plain');
        echo implode("", $results);
    } else {
        // Return a clear error message
        header("HTTP/1.0 404 Not Found");
        echo "File not found or access denied.";
    }
} else {
    header("HTTP/1.0 400 Bad Request");
    echo "Invalid request.";
}
