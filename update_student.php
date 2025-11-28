<?php
require 'db_connect.php';

$pdo = getConnection();

// Step 1 — Check if student ID is provided
$id = $_GET['id'] ?? null;

if (!$id) {
    // No ID: show list of students to edit
    $stmt = $pdo->query("SELECT id, fullname FROM students");
    $students = $stmt->fetchAll();

    if (empty($students)) {
        echo "<p>No students found.</p>";
        exit;
    }

    echo "<h2>Select a student to edit:</h2><ul>";
    foreach ($students as $s) {
        echo "<li><a href='update_student.php?id={$s['id']}'>".htmlspecialchars($s['fullname'])."</a></li>";
    }
    echo "</ul>";
    exit;
}

// Step 2 — Fetch student data
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    die("Student not found.");
}

// Step 3 — Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST['fullname']);
    $matricule = trim($_POST['matricule']);
    $group_id = trim($_POST['group_id']);

    if ($fullname === "" || $matricule === "" || $group_id === "") {
        $error = "All fields are required.";
    } else {
        $update = $pdo->prepare("
            UPDATE students 
            SET fullname = ?, matricule = ?, group_id = ?
            WHERE id = ?
        ");
        $update->execute([$fullname, $matricule, $group_id, $id]);
        $success = "Student updated successfully!";
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>Update Student</title>
</head>
<body>

<h2>Update Student</h2>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

<form method="POST">
    Full Name:<br>
    <input type="text" name="fullname" value="<?= htmlspecialchars($student['fullname']); ?>" required><br><br>

```
Matricule:<br>
<input type="text" name="matricule" value="<?= htmlspecialchars($student['matricule']); ?>" required><br><br>

Group ID:<br>
<input type="text" name="group_id" value="<?= htmlspecialchars($student['group_id']); ?>" required><br><br>

<button type="submit">Update</button>
```

</form>

<br>
<a href="list_students.php">Back to list</a>

</body>
</html>
