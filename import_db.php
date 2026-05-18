<?php
$host = '127.0.0.1';
$port = '5432';
$dbname = 'soad';
$user = 'postgres';
$pass = 'kochomuonok1';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = file_get_contents('soad_postgres.sql');
    if ($sql !== false) {
        $pdo->exec($sql);
        echo "Database imported successfully\n";
    } else {
        echo "Failed to read SQL file\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
