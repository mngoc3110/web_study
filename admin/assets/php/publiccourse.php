

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $state = isset($_POST['public']) ? 1 : 0;
    $id = $_POST['id']; // Nhận ID từ form
    // Kết nối tới database
    include "connectsql.php";
    // Truy vấn cập nhật chỉ cho bản ghi có ID cụ thể
    $sql = "UPDATE course_db SET public = $state WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../index.php?page=course");
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }

    $conn->close();
   
}
?>
