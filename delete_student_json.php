<?php

$file = "students.json";

// Only allow access if called via GET with an ID
if (!isset($_GET['id'])) {
    // Redirect to the list page instead of showing code
    header("Location: /db_project/list_students_json.php");
    exit;
}

$idToDelete = $_GET['id'];

// Load students
if (!file_exists($file)) {
    header("Location: /db_project/list_students_json.php");
    exit;
}

$students = json_decode(file_get_contents($file), true);

$found = false;
$students = array_filter($students, function($s) use ($idToDelete, &$found) {
    if ($s['id'] == $idToDelete) {
        $found = true;
        return false; // remove this student
    }
    return true;
});

if ($found) {
    $students = array_values($students);
    file_put_contents($file, json_encode($students, JSON_PRETTY_PRINT));
}

// Always redirect back to the list page
header("Location: /db_project/list_students_json.php");
exit;

?>
