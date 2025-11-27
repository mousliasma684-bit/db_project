<?php
// Déterminer la date à modifier
$today = $_GET['date'] ?? date('Y-m-d'); // Par défaut aujourd'hui
$fileName = "attendance_" . $today . ".json";

// Vérifier si le fichier existe
if (!file_exists($fileName)) {
    die("No attendance found for $today.");
}

// Charger l'attendance
$attendance = json_decode(file_get_contents($fileName), true);

// Charger les étudiants pour info
$studentsFile = "students.json";
if (!file_exists($studentsFile)) {
    die("Aucun étudiant trouvé.");
}
$students = json_decode(file_get_contents($studentsFile), true);

// Formulaire soumis : mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($attendance as $index => $att) {
        $id = $att['student_id'];
        $status = $_POST['status'][$id] ?? 'absent';
        $attendance[$index]['status'] = $status;
    }

    file_put_contents($fileName, json_encode($attendance, JSON_PRETTY_PRINT));
    echo "<h2>Attendance updated successfully for $today.</h2>";
    echo "<a href='/Attendance/take_attendance.php'>
            <button style='padding:10px 15px;'>Retour</button>
          </a>";
    exit;
}

?>

<h2>Update Attendance for <?= $today ?></h2>

<form method="POST">
<table border="1" cellpadding="8" cellspacing="0">
    <tr style="background:#f0f0f0;">
        <th>ID</th>
        <th>Nom complet</th>
        <th>Groupe</th>
        <th>Présent / Absent</th>
    </tr>
    <?php foreach ($attendance as $attItem):
        // Récupérer info étudiant
        $studentInfo = array_filter($students, fn($s) => $s['id'] == $attItem['student_id']);
        $studentInfo = array_values($studentInfo)[0];
    ?>
    <tr>
        <td><?= $studentInfo['id'] ?></td>
        <td><?= htmlspecialchars($studentInfo['fullname']) ?></td>
        <td><?= htmlspecialchars($studentInfo['group']) ?></td>
        <td>
            <label><input type="radio" name="status[<?= $studentInfo['id'] ?>]" value="present" <?= $attItem['status']=='present' ? 'checked' : '' ?>> Present</label>
            <label><input type="radio" name="status[<?= $studentInfo['id'] ?>]" value="absent" <?= $attItem['status']=='absent' ? 'checked' : '' ?>> Absent</label>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<button type="submit" style="padding:10px 15px;">Update Attendance</button>
</form>