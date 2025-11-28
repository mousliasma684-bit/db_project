<?php 
require 'db_connect.php';

$session_id = $_GET['id'] ?? null;
$pdo = getConnection();

// CASE 1 — No ID provided → show available sessions to close
if ($session_id === null) {

    echo "<h2>Select a session to close</h2>";

    $stmt = $pdo->query("SELECT * FROM attendance_sessions WHERE status = 'open'");
    $sessions = $stmt->fetchAll();

    if (!$sessions) {
        echo "<p>No open sessions found.</p>";
        echo "<a href='create_session.php'><button>Create New Session</button></a>";
        exit;
    }

    echo "<ul>";
    foreach ($sessions as $s) {
        echo "<li>
                Session #{$s['id']} — Course {$s['course_id']} — Group {$s['group_id']}  
                <a href='close_session.php?id={$s['id']}'>
                    <button>Close This Session</button>
                </a>
              </li>";
    }
    echo "</ul>";

    exit;
}

// CASE 2 — ID is provided → Close the session

try {

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
