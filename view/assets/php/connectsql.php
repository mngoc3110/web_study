<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$host = 'localhost'; // Thay đổi nếu cần
$db = 'mngocvn_database';
$user = 'mngocvn_database';
$pass = 'Bin31102004';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>