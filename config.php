<?php
$conn = new mysqli("localhost", "root", "", "student_system");

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>