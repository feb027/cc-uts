<?php
require __DIR__ . '/app/Core/Autoloader.php';
require __DIR__ . '/app/Core/Helpers.php';
use App\Core\Autoloader;use App\Core\Database;use PDOException;
Autoloader::register();

$sql = file_get_contents(__DIR__ . '/database.sql');
try {
    $pdo = Database::getInstance();
    $pdo->exec($sql);
    echo "Migrasi selesai. Tabel siap.\n";
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
