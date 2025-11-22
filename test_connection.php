<?php
require_once "db_connect.php";

$conn = connectDB();

if ($conn) {
    echo "Connection successful";
} else {
    echo "Connection failed";
}
