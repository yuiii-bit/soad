<?php
$host = '127.0.0.1';
$port = '5432';
$user = 'postgres';
$pass = 'kochomuonok1';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE soad");
    echo "Database created successfully\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
