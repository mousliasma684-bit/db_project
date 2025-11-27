<?php
// Nom du fichier pour aujourd'hui
$today = date('Y-m-d');
$fileName = "attendance_" . $today . ".json";

// Si le fichier d'aujourd'hui existe déjà
if (file_exists($fileName)) {
    echo "<h2>Attendance for today has already been taken.</h2>";
    echo "<a href='/Attendance/list_student.php'>
            <button style='padding:10px 15px;'>Retour à la liste des étudiants</button>
          </a> ";
    echo "<a href='/Attendance/update_attendance.php?date=$today'>
            <button style='padding:10px 15px;'>Modifier l'attendance</button>
          </a>";
    exit;
}

// Charger les étudiants
$studentsFile = "students.json";

if (!file_exists($studentsFile)) {
    die("Aucun étudiant trouvé. Veuillez ajouter des étudiants d'abord.");
}

$students = json_decode(file_get_contents($studentsFile), true);

if (!$students) {
    die("Erreur de lecture du fichier students.json.");
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendance = [];

    foreach ($students as $s) {
        $status = $_POST['status'][$s['id']] ?? 'absent';
        $attendance[] = [
            "student_id" => $s['id'],
            "status" => $status
        ];
    }

    file_put_contents($fileName, json_encode($attendance, JSON_PRETTY_PRINT));

    echo "<h2>Attendance saved successfully for $today.</h2>";
    echo "<a href='/Attendance/list_student.php'>
            <button style='padding:10px 15px;'>Retour à la liste des étudiants</button>
          </a> ";
    echo "<a href='/Attendance/update_attendance.php?date=$today'>
            <button style='padding:10px 15px;'>Modifier l'attendance</button>
          </a>";
    exit;
}

?>

<h2>Take Attendance for <?= $today ?></h2>

<form method="POST">
<table border="1" cellpadding="8" cellspacing="0">
    <tr style="background:#f0f0f0;">
        <th>ID</th>
        <th>Nom complet</th>
        <th>Groupe</th>
        <th>Présent / Absent</th>
    </tr>
    <?php foreach ($students as $s): ?>
    <tr>
        <td><?= $s['id'] ?></td>
        <td><?= htmlspecialchars($s['fullname']) ?></td>
        <td><?= htmlspecialchars($s['group']) ?></td>
        <td>
            <label><input type="radio" name="status[<?= $s['id'] ?>]" value="present" required> Present</label>
            <label><input type="radio" name="status[<?= $s['id'] ?>]" value="absent"> Absent</label>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<button type="submit" style="padding:10px 15px;">Submit Attendance</button>
</form>