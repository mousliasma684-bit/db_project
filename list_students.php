<?php
require 'db_connect.php';

try {
    $pdo = getConnection();

    $stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
    $students = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Students List (DB)</title>
</head>
<body>

<h2>Students List (Database)</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Matricule</th>
        <th>Group</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($students as $s): ?>
    <tr>
        <td><?= $s['id']; ?></td>
        <td><?= htmlspecialchars($s['fullname']); ?></td>
        <td><?= htmlspecialchars($s['matricule']); ?></td>
        <td><?= htmlspecialchars($s['group_id']); ?></td>
        <td>
            <a href="update_student.php?id=<?= $s['id']; ?>">Edit</a> |
            <a href="delete_student.php?id=<?= $s['id']; ?>" onclick="return confirm('Delete this student?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

<br>
<a href="add_student_db.php">Add Student</a>

</body>
</html>
