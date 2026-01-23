<?php

$host = getenv('DB_HOST') ?: 'db';
$db   = getenv('DB_NAME') ?: 'tasksdb';
$user = getenv('DB_USER') ?: 'appuser';
$pass = getenv('DB_PASSWORD') ?: 'example';
$port = getenv('DB_PORT') ?: '3306';

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

function get_pdo() {
    global $dsn, $user, $pass, $options;
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // message simple pour debug ; en prod on loggerait
            http_response_code(500);
            echo "DB connection failed: " . htmlspecialchars($e->getMessage());
            exit;
        }
    }
    return $pdo;
}
