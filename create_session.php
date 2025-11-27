<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $course_id = trim($_POST["course_id"]);
    $group_id = trim($_POST["group_id"]);
    $opened_by = trim($_POST["opened_by"]);
    $date = date("Y-m-d");

    if ($course_id === "" || $group_id === "" || $opened_by === "") {
        echo "All fields are required.";
        exit;
    }

    try {
        $pdo = getConnection();

        $stmt = $pdo->prepare("
            INSERT INTO attendance_sessions (course_id, group_id, date, opened_by, status)
            VALUES (?, ?, ?, ?, 'open')
        ");

        $stmt->execute([$course_id, $group_id, $date, $opened_by]);

        $session_id = $pdo->lastInsertId();

        echo "<h2>Session created successfully!</h2>";
        echo "<p>Session ID: <strong>$session_id</strong></p>";

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Attendance Session</title>
</head>
<body>

<h2>Create Attendance Session</h2>

<form method="POST">

    Course ID:<br>
    <input type="text" name="course_id" required><br><br>

    Group ID:<br>
    <input type="text" name="group_id" required><br><br>

    Professor ID:<br>
    <input type="text" name="opened_by" required><br><br>

    <button type="submit">Create Session</button>

</form>

</body>
</html>
