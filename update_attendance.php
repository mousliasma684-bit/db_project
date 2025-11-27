<?php

// 1. Vérifier la date
$date = $_GET['date'] ?? null;
if (!$date) {
    die("Date manquante.");
}

$fileName = "attendance_" . $date . ".json";

if (!file_exists($fileName)) {
    die("Le fichier d'attendance pour cette date n'existe pas.");
}

// Charger attendance existante
$attendance = json_decode(file_get_contents($fileName), true);

// Charger les étudiants
$studentsFile = "students.json";
if (!file_exists($studentsFile)) {
    die("Fichier students.json introuvable.");
}

$students = json_decode(file_get_contents($studentsFile), true);

// Si on soumet le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newAttendance = [];

    foreach ($students as $s) {
        $status = $_POST['status'][$s['id']] ?? 'absent';
        $newAttendance[] = [
            "student_id" => $s['id'],
            "status" => $status
        ];
    }

    file_put_contents($fileName, json_encode($newAttendance, JSON_PRETTY_PRINT));

    echo "<h2>Attendance updated successfully for $date.</h2>";
    echo "<a href='/db_project/list_students_json.php'>
            <button style='padding:10px 15px;'>Retour à la liste</button>
          </a>";
    exit;
}

// Convertir attendance en tableau indexé par student_id
$attendanceMap = [];
foreach ($attendance as $a) {
    $attendanceMap[$a['student_id']] = $a['status'];
}

?>

<h2>Update Attendance for <?= $date ?></h2>

<form method="POST">
<table border="1" cellpadding="8" cellspacing="0">
    <tr style="background:#f0f0f0;">
        <th>ID</th>
        <th>Nom complet</th>
        <th>Groupe</th>
        <th>Présent / Absent</th>
    </tr>

    <?php foreach ($students as $s): 
        $status = $attendanceMap[$s['id']] ?? 'absent';
    ?>
    <tr>
        <td><?= $s['id'] ?></td>
        <td><?= htmlspecialchars($s['fullname']) ?></td>
        <td><?= htmlspecialchars($s['group']) ?></td>
        <td>
            <label>
                <input type="radio" 
                    name="status[<?= $s['id'] ?>]" 
                    value="present"
                    <?= $status === 'present' ? 'checked' : '' ?>
                > Present
            </label>

            <label>
                <input type="radio" 
                    name="status[<?= $s['id'] ?>]" 
                    value="absent"
                    <?= $status === 'absent' ? 'checked' : '' ?>
                > Absent
            </label>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<button type="submit" style="padding:10px 15px;">Update Attendance</button>
</form>
