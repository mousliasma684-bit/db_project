<?php

$file = "students.json";

if (!file_exists($file)) {
    die("Fichier JSON introuvable.");
}

$students = json_decode(file_get_contents($file), true);

// Check if an ID is provided
$idToEdit = $_GET['id'] ?? null;

if (!$idToEdit) {
    // Show list of students to choose from
    echo "<h2>Select a student to edit:</h2>";
    if (empty($students)) {
        echo "<p>No students found.</p>";
    } else {
        echo "<ul>";
        foreach ($students as $s) {
            echo "<li><a href='update_student_json.php?id={$s['id']}'>".htmlspecialchars($s['fullname'])."</a></li>";
        }
        echo "</ul>";
    }
    exit;
}

// Find the student
$student = null;
foreach ($students as $index => $s) {
    if ($s['id'] == $idToEdit) {
        $student = $s;
        $studentIndex = $index;
        break;
    }
}

if (!$student) {
    die("Étudiant introuvable.");
}

// Form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $group    = trim($_POST['group']);

    if ($fullname === "" || $group === "") {
        echo "Tous les champs sont obligatoires.";
    } else {
        $students[$studentIndex]['fullname'] = $fullname;
        $students[$studentIndex]['group'] = $group;

        file_put_contents($file, json_encode($students, JSON_PRETTY_PRINT));

        echo "<script>
                alert('Étudiant mis à jour avec succès !');
                window.location.href='update_student_json.php';
              </script>";
        exit;
    }
}

?>

<h2>Editer Étudiant : <?= htmlspecialchars($student['fullname']) ?></h2>

<form method="POST">
    Nom complet :<br>
    <input type="text" name="fullname" value="<?= htmlspecialchars($student['fullname']) ?>" required><br><br>

```
Groupe :<br>
<input type="text" name="group" value="<?= htmlspecialchars($student['group']) ?>" required><br><br>

<button type="submit">Mettre à jour</button>
```

</form>

<br>
<a href="/db_project/list_students_json.php">
    <button>Retour à la liste</button>
</a>
