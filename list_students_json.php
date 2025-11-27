<?php

$file = "students.json";

if (!file_exists($file)) {
    echo "<h2>Aucun étudiant pour le moment.</h2>";
    echo "<a href='/db_project/add_student.php'>
            <button style='padding:10px 15px;'>Ajouter un étudiant</button>
          </a>";
    exit;
}

$students = json_decode(file_get_contents($file), true);

if (!is_array($students)) {
    echo "<h2>Erreur : impossible de lire students.json</h2>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants (JSON)</title>
</head>
<body>

<h2>Liste des étudiants</h2>

<table border="1" cellpadding="10" cellspacing="0">
    <tr style="background:#f0f0f0;">
        <th>ID</th>
        <th>Nom complet</th>
        <th>Groupe</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($students as $s): ?>
    <tr>
        <td><?= htmlspecialchars($s['id']); ?></td>
        <td><?= htmlspecialchars($s['fullname']); ?></td>
        <td><?= htmlspecialchars($s['group']); ?></td>
        <td>
            <a href="/db_project/update_student_json.php?id=<?= urlencode($s['id']); ?>">Edit</a> |
            <a href="/db_project/delete_student_json.php?id=<?= urlencode($s['id']); ?>" onclick="return confirm('Supprimer cet étudiant ?');">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

<br>
<a href="/db_project/add_student.php">
    <button style="padding:10px 15px;">Ajouter un étudiant</button>
</a>

</body>
</html>
