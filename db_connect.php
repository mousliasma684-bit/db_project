<?php
function getConnection() {
    $config = require __DIR__ . '/config.php';

    try {
        $dsn = "mysql:host={$config['host']};dbname={$config['db']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['user'], $config['pass'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        return $pdo;

    } catch (PDOException $e) {
        // Optional: log the error
        // file_put_contents('db_error.log', $e->getMessage() . PHP_EOL, FILE_APPEND);

        die("Database connection failed: " . $e->getMessage());
    }
}
