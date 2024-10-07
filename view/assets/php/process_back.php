<?php

include "connectsql.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=login");
    exit();
}

$user_name = $_SESSION['username'];

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    // Lấy thông tin từ bill_db
    $select_sql = "SELECT course_id, course_name, teacher_id, price FROM bill_db WHERE user_id = ?";
    $select_stmt = $conn->prepare($select_sql);
    $select_stmt->bind_param("s", $user_name);
    $select_stmt->execute();
    $select_stmt->store_result(); // Lưu kết quả để có thể sử dụng lại
    $select_stmt->bind_result($course_id, $course_name, $teacher_id, $price);

    // Thêm lại vào cart_db
    while ($select_stmt->fetch()) {
        $insert_sql = "INSERT INTO cart_db (user_id, course_id, course_name, teacher_id, price) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssss", $user_name, $course_id, $course_name, $teacher_id, $price);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    $select_stmt->close(); // Đóng câu truy vấn sau khi sử dụng

    // Xóa thông tin trong bill_db
    $delete_sql = "DELETE FROM bill_db WHERE user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("s", $user_name);
    $delete_stmt->execute();
    $delete_stmt->close();

    // Cam kết giao dịch
    $conn->commit();

    // Chuyển hướng về giỏ hàng
    header("Location: ../../../index.php?page=cart");
    exit();
} catch (Exception $e) {
    // Nếu có lỗi, hủy giao dịch
    $conn->rollback();
    error_log("Lỗi khi quay lại giỏ hàng: " . $e->getMessage());
    header("Location: index.php?page=error"); // Chuyển hướng đến trang lỗi
    exit();
}
?>