<?php


// Kết nối đến cơ sở dữ liệu
include "./view/assets/php/connectsql.php";



$username = $_SESSION['username']; // Lấy tên người dùng từ session

// Truy vấn để lấy khóa học mà người dùng đã đăng ký
$sql = "SELECT course_id FROM enroll_db WHERE student_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row['course_id']; // Lưu course_id vào mảng
    }
}

// Truy vấn để lấy thông tin chi tiết của các khóa học
if (count($courses) > 0) {
    $course_ids = implode(',', array_map('intval', $courses)); // Chuyển mảng thành chuỗi và đảm bảo an toàn
    $sql_courses = "SELECT * FROM course_db WHERE id IN ($course_ids)";
    $result_courses = $conn->query($sql_courses);
} else {
    $result_courses = [];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khóa Học Của Bạn</title>
    <link rel="stylesheet" href="./view/assets/css/study.css">
    <style>
        body h1{
            text-align:center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>COURSE</h1>
    <div class="course-list">
        <?php if ($result_courses && $result_courses->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($course = $result_courses->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['name']); ?></td>
                            <td><?php echo htmlspecialchars($course['description']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($course['avatar']); ?>" alt="<?php echo htmlspecialchars($course['name']); ?>" style="width: 100px;"></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>none.</p>
        <?php endif; ?>
    </div>
    <script src="./view/assets/js/main.js"></script>
</body>
</html>