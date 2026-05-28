<?php
require __DIR__ . '/src/Config/Database.php';
$db = (new App\Config\Database())->getConnection();

$tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "Tables:\n";
print_r($tables);

foreach ($tables as $table) {
    echo "\nTable: $table\n";
    $columns = $db->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo " - {$col['Field']} ({$c`ol['Type']})\n";
    }
}
