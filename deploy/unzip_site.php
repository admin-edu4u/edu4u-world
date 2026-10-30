<?php
$zipFile = __DIR__ . '/site.zip';
$dest = __DIR__;
if (!file_exists($zipFile)) {
    http_response_code(404);
    exit('site.zip not found');
}
$zip = new ZipArchive();
if ($zip->open($zipFile) !== true) {
    http_response_code(500);
    exit('Failed to open zip');
}
$ok = $zip->extractTo($dest);
$count = $zip->numFiles;
$zip->close();
if (!$ok) {
    http_response_code(500);
    exit('Extract failed');
}
@unlink($zipFile);
@unlink(__FILE__);
echo "Extracted $count files. Cleanup done.";
