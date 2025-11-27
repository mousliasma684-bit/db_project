<?php
require 'db_connect.php';

$session_id = $_GET['id'] ?? null;

if ($session_id === null) {
    die("Error: session ID is missing.");
}

try {
    $pdo = getConnection();

    // Check if session exists
    $check = $pdo->prepare("SELECT * FROM attendance_sessions WHERE id = ?");
    $check->execute([$session_id]);
    $session = $check->fetch();

    if (!$session) {
        die("Error: session not found.");
    }

    // Update status
    $stmt = $pdo->prepare("
        UPDATE attendance_sessions
        SET status = 'closed'
        WHERE id = ?
    ");

    $stmt->execute([$session_id]);

    echo "<h2>Session #$session_id has been closed successfully.</h2>";
    echo "<a href='create_session.php'><button>Back</button></a>";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
