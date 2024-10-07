<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Kiểm tra nếu có yêu cầu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacherId = $_POST['id'];

    // Kết nối đến cơ sở dữ liệu
    include "connectsql.php";



    // Xóa giáo viên khỏi cơ sở dữ liệu
    $sql = "DELETE FROM teacher_db WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $teacherId);
    $stmt->execute();
    $stmt->close();

    header("Location: ../../index.php?page=teacher");
    $conn->close();
}
?>