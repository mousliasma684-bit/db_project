<?php
require_once "config.php";

function connectDB() {
    global $host, $user, $pass, $dbname;
    $logFile = __DIR__ . "/db_errors.log";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        file_put_contents($logFile, date("Y-m-d H:i:s") . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return null;
    }
}
