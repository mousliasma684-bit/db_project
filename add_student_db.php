<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname   = trim($_POST["fullname"]);
    $matricule  = trim($_POST["matricule"]);
    $group_id   = trim($_POST["group_id"]);

    if ($fullname === "" || $matricule === "" || $group_id === "") {
        $error = "All fields are required.";
    } else {

        try {
            $pdo = getConnection();

            $stmt = $pdo->prepare("
                INSERT INTO students (fullname, matricule, group_id)
                VALUES (?, ?, ?)
            ");

            $stmt->execute([$fullname, $matricule, $group_id]);

            $success = "Student added successfully!";

        } catch (PDOException $e) {
            // Optional: log error
            // file_put_contents('db_error.log', $e->getMessage(), FILE_APPEND);

            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Student (DB)</title>
</head>
<body>

<h2>Add Student (Database)</h2>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

<form method="POST">

    Full Name:<br>
    <input type="text" name="fullname" required><br><br>

    Matricule:<br>
    <input type="text" name="matricule" required><br><br>

    Group ID:<br>
    <input type="text" name="group_id" required><br><br>

    <button type="submit">Add Student</button>

</form>

<br>
<a href="list_students.php">View Students</a>

</body>
</html>
