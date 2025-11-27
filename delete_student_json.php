<?php

$file = "students.json";

if (!file_exists($file)) {
    die("Fichier JSON introuvable.");
}

$students = json_decode(file_get_contents($file), true);

$idToDelete = $_GET['id'] ?? null;
if ($idToDelete === null) {
    die("ID manquant.");
}

// Supprimer l'étudiant
$students = array_filter($students, function($s) use ($idToDelete) {
    return $s['id'] != $idToDelete;
});

$students = array_values($students);

// Sauvegarder
file_put_contents($file, json_encode($students, JSON_PRETTY_PRINT));

// Redirection vers la liste JSON
header("Location: /db_project/list_students_json.php");
exit;

?>
