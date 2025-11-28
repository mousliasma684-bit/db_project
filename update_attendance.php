<?php

// Check date parameter
$date = $_GET['date'] ?? null;

// Get all attendance files
$attendanceFiles = glob("attendance_*.json");

// If no date provided or date file doesn't exist, show a list of available attendance files
if (!$date || !file_exists("attendance_$date.json")) {
    echo "<h2>Select an attendance date to update:</h2>";
    if (empty($attendanceFiles)) {
        echo "<p>No attendance records found.</p>";
    } else {
        echo "<ul>";
        foreach ($attendanceFiles as $file) {
            $fileDate = str_replace(["attendance_", ".json"], "", $file);
            echo "<li><a href='update_attendance.php?date=$fileDate'>$fileDate</a></li>";
        }
        echo "</ul>";
    }
    exit;
}

$fileName = "attendance_$date.json";

// Load existing attendance
$attendance = json_decode(file_get_contents($fileName), true);

// Load students
$studentsFile = "students.json";
if (!file_exists($studentsFile)) {
    die("Fichier students.json introuvable.");
}
$students = json_decode(file_get_contents($studentsFile), true);

// Handle form submission
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
    echo "<script>
            alert('Attendance updated successfully for $date.');
            window.location.href='update_attendance.php';
          </script>";
    exit;
}

// Map attendance by student_id
$attendanceMap = [];
foreach ($attendance as $a) {
    $attendanceMap[$a['student_id']] = $a['status'];
}
?>

<h2>Update Attendance for <?= htmlspecialchars($date) ?></h2>

<form method="POST">
<table border="1" cellpadding="8" cellspacing="0">
    <tr style="background:#f0f0f0;">
        <th>ID</th>
        <th>Nom complet</th>
        <th>Groupe</th>
        <th>Présent / Absent</th>
    </tr>

```
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
```

</table>

<br>
<button type="submit" style="padding:10px 15px;">Update Attendance</button>
</form>
