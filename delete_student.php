<?php
require 'db_connect.php';

$pdo = getConnection();

if (!isset($_GET['id'])) {
    die("No student ID provided.");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
$stmt->execute([$id]);

header("Location: list_students.php");
exit;
?>
