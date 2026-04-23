<?php
// Configuração para MySQL (Hostinger/local)
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_PORT = getenv('DB_PORT') ?: '3306';
$DB_NAME = getenv('DB_NAME') ?: 'u914400496_imyj';
$DB_USER = getenv('DB_USER') ?: 'u914400496_imyjao';
$DB_PASS = getenv('DB_PASS') ?: 'InstitutoMYJ2@26';

$pdo = null;
$dbError = null;

try {
    $dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";
    $pdo = new PDO(
        $dsn,
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Throwable $e) {
    $dbError = $e->getMessage();
}
