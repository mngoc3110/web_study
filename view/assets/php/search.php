<?php
include "connectsql.php"; // Kết nối đến cơ sở dữ liệu

// Bật hiển thị lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Lấy giá trị tìm kiếm từ URL
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Đặt header cho JSON
header('Content-Type: application/json');

$response = [];

if ($query) {
    $searchTerm = '%' . $query . '%';

    // Truy vấn bảng blog_db
    $stmt1 = $conn->prepare("
        SELECT id, title FROM blog_db WHERE title LIKE ? OR description LIKE ?
    ");
    $stmt1->bind_param("ss", $searchTerm, $searchTerm);
    $stmt1->execute();
    $stmt1->store_result();
    $stmt1->bind_result($id, $title);

    // Lưu kết quả vào mảng
    while ($stmt1->fetch()) {
        $response[] = [
            'id' => $id,
            'course_name' => htmlspecialchars($title)
        ];
    }

    $stmt1->close();

    // Nếu không tìm thấy kết quả nào
    if (empty($response)) {
        $response['error'] = "Không tìm thấy bài viết.";
    }
} else {
    $response['error'] = "Vui lòng nhập từ khóa tìm kiếm.";
}

echo json_encode($response); // Gửi phản hồi JSON
$conn->close();
?>