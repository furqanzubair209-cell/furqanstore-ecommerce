<?php
require_once(__DIR__ . '/../config/db.php');
$conn = getConnection();

$sqlFile = __DIR__ . '/database_enhancements.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found.");
}

$sql = file_get_contents($sqlFile);

echo "<h1>Applying SQL Enhancements</h1><pre>";

if ($conn->multi_query($sql)) {
    do {
        if ($res = $conn->store_result()) {
            $res->free();
        }
    } while ($conn->more_results() && $conn->next_result());
    echo "All queries executed successfully.\n";
} else {
    echo "Error: " . $conn->error . "\n";
}

echo "</pre><a href='demo_reports.php'>View Reports</a>";
?>
