<?php
/**
 * Manual Update Script
 * Run this from command line: php manual_update.php
 */

// Set paths
$updateDir = __DIR__ . '/writable/updates';
$backupDir = __DIR__ . '/writable/backups';
$versionFile = __DIR__ . '/version.json';

echo "=== Adyatama School Manual Update ===\n\n";

// Find latest update file
$files = glob($updateDir . '/adyatama-school-v*.zip');
if (empty($files)) {
    die("Error: No update file found in writable/updates/\n");
}

// Get the latest file
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

$updateFile = $files[0];
$fileName = basename($updateFile);

echo "Found update file: $fileName\n";
echo "File size: " . round(filesize($updateFile) / 1024 / 1024, 2) . " MB\n\n";

// Extract version from filename
preg_match('/v([\d\.]+)\.zip$/', $fileName, $matches);
$newVersion = $matches[1] ?? 'unknown';

echo "New version: $newVersion\n";

// Read current version
$versionData = json_decode(file_get_contents($versionFile), true);
$currentVersion = $versionData['current_version'] ?? '1.0.0';

echo "Current version: $currentVersion\n\n";

// Confirm
echo "Do you want to proceed with the update? (yes/no): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if (strtolower($line) !== 'yes') {
    die("Update cancelled.\n");
}

echo "\n=== Starting Update Process ===\n\n";

// Step 1: Extract update
echo "[1/4] Extracting update files...\n";
$tempDir = $updateDir . '/temp-' . time();

$zip = new ZipArchive();
if ($zip->open($updateFile) !== true) {
    die("Error: Failed to open ZIP file\n");
}

if (!$zip->extractTo($tempDir)) {
    $zip->close();
    die("Error: Failed to extract ZIP file\n");
}

$zip->close();
echo "      ✓ Extraction completed\n\n";

// Step 2: Copy files
echo "[2/4] Copying files to application directory...\n";

function copyDirectory($src, $dst, $exclude = []) {
    $dir = opendir($src);
    if (!is_dir($dst)) {
        mkdir($dst, 0755, true);
    }
    
    $count = 0;
    while (($file = readdir($dir)) !== false) {
        if ($file != '.' && $file != '..') {
            if (in_array($file, $exclude)) {
                continue;
            }
            
            $srcPath = $src . '/' . $file;
            $dstPath = $dst . '/' . $file;
            
            if (is_dir($srcPath)) {
                copyDirectory($srcPath, $dstPath, $exclude);
            } else {
                copy($srcPath, $dstPath);
                $count++;
            }
        }
    }
    closedir($dir);
    return $count;
}

$filesCopied = copyDirectory($tempDir, __DIR__, ['.env', 'writable']);
echo "      ✓ Copied $filesCopied files\n\n";

// Step 3: Clean up
echo "[3/4] Cleaning up temporary files...\n";

function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }
    rmdir($dir);
}

deleteDirectory($tempDir);
echo "      ✓ Cleanup completed\n\n";

// Step 4: Update version file
echo "[4/4] Updating version information...\n";

$versionData['current_version'] = $newVersion;
$versionData['last_check'] = date('Y-m-d H:i:s');
$versionData['updated_at'] = date('Y-m-d H:i:s');

file_put_contents($versionFile, json_encode($versionData, JSON_PRETTY_PRINT));
echo "      ✓ Version updated to $newVersion\n\n";

echo "=== Update Completed Successfully! ===\n\n";
echo "Current version: $newVersion\n";
echo "Please restart your web server if needed.\n";
