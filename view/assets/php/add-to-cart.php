<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "connectsql.php";

if (!isset($_SESSION['username'])) {
    die("Bạn cần đăng nhập để thêm khoá học vào giỏ hàng.");
}

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];
    $user_id = $_SESSION['username']; // Giả định bạn đã lưu user_id trong session
    echo $course_id;
    echo $user_id;
    // Lấy thông tin khóa học từ course_db
    $stmt = $conn->prepare("SELECT name, teacher_id,cost FROM course_db WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($course_name, $teacher_id,$price);
    $stmt->fetch();

    if ($stmt->num_rows === 0) {
        die("Khóa học không hợp lệ.");
    }

    // Thêm khóa học vào giỏ hàng (có user_id)
    $stmt = $conn->prepare("INSERT INTO cart_db (course_id, course_name, teacher_id, user_id,price) VALUES (?, ?, ?, ?,?)");
    $stmt->bind_param("issss", $course_id, $course_name, $teacher_id, $user_id,$price);

    if ($stmt->execute()) {
        echo '<script>alert("Đã thêm khoá học vào giỏ hàng."); window.location.href = "../../../index.php?page=home";</script>';
    } else {
        echo '<script>alert("Lỗi khi thêm khoá học vào giỏ hàng."); window.location.href = "../../../index.php?page=home";</script>';
    }

    $stmt->close();
} else {
    die("Khóa học không hợp lệ.");
}

$conn->close();
?>