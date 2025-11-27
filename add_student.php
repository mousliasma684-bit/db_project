<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $student_id = trim($_POST["student_id"]);
    $fullname   = trim($_POST["fullname"]);
    $group      = trim($_POST["group"]);

    if ($student_id === "" || $fullname === "" || $group === "") {
        $error = "Erreur : tous les champs sont obligatoires.";
    } else {

        $file = "students.json";

        if (file_exists($file)) {
            $students = json_decode(file_get_contents($file), true);
        } else {
            $students = [];
        }

        $students[] = [
            "id" => $student_id,
            "fullname" => $fullname,
            "group" => $group
        ];

        file_put_contents($file, json_encode($students, JSON_PRETTY_PRINT));

        $success = "Étudiant ajouté avec succès !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un étudiant</title>
</head>
<body>

<h2>Ajouter un étudiant</h2>

<?php
if (!empty($error)) echo "<p style='color:red;'>$error</p>";
if (!empty($success)) {
    echo "<p style='color:green;'>$success</p>";
    echo "<a href='/db_project/list_students_json.php'><button>Voir la liste</button></a>";
}
?>

<form method="POST">

    ID Étudiant :<br>
    <input type="text" name="student_id" required><br><br>

    Nom complet :<br>
    <input type="text" name="fullname" required><br><br>

    Groupe :<br>
    <input type="text" name="group" required><br><br>

    <button type="submit">Ajouter</button>

</form>

<br>
<a href="/db_project/list_students_json.php">
    <button>Voir la liste</button>
</a>

</body>
</html>
