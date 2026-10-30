<?php
header('Content-Type: text/plain; charset=utf-8');
$host = 'sql209.infinityfree.com';
$user = 'if0_43308456';
$pass = 'Edu4uHost26';
$db   = 'if0_43308456_edu4u';
$file = __DIR__ . '/database_import.sql';

if (!file_exists($file)) {
    http_response_code(404);
    exit("SQL file missing\n");
}

$mysqli = @new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    http_response_code(500);
    exit('Connect failed: ' . $mysqli->connect_error . "\n");
}
$mysqli->set_charset('utf8mb4');

$sql = file_get_contents($file);
if ($sql === false) {
    exit("Could not read SQL\n");
}

// Split on ;\n for statements (good enough for mysqldump)
$parts = preg_split('/;\s*\n/', $sql);
$ok = 0; $fail = 0; $errors = [];
foreach ($parts as $stmt) {
    $stmt = trim($stmt);
    if ($stmt === '' || strpos($stmt, '--') === 0 || strpos($stmt, '/*') === 0) continue;
    if (!$mysqli->query($stmt)) {
        $fail++;
        if (count($errors) < 15) {
            $errors[] = $mysqli->error . ' | ' . substr($stmt, 0, 120);
        }
    } else {
        $ok++;
    }
}

$tables = [];
$res = $mysqli->query('SHOW TABLES');
while ($res && ($row = $res->fetch_array())) { $tables[] = $row[0]; }

echo "OK statements: $ok\nFailed: $fail\nTables: " . count($tables) . "\n";
echo implode(', ', $tables) . "\n";
if ($errors) {
    echo "\nSample errors:\n" . implode("\n", $errors) . "\n";
}

@unlink($file);
@unlink(__FILE__);
echo "Cleanup done.\n";
