<?php
require 'db_connect.php';  // include your database connection

try {
    $conn = connectDB();  // get PDO connection
    $sql = "SELECT * FROM users";
    $stmt = $conn->query($sql);

    if ($stmt->rowCount() > 0) {
        foreach ($stmt as $row) {
            echo "ID: ".$row['ID']." | Username: ".$row['username']." | Email: ".$row['email']."<br>";
        }
    } else {
        echo "No users found.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
