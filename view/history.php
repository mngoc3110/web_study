<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiển Thị Hóa Đơn</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .bill{
            margin: 200px;
        }
    </style>
</head>
<body>
<div class="bill">
<div class="container mt-5" >
    <h2>Your bill</h2>

    <?php
   
    if (!isset($_SESSION['username'])) {
        header("Location: index.php?page=login");
        exit();
    }

    $user_name = $_SESSION['username'];

    include './view/assets/php/connectsql.php'; // Kết nối cơ sở dữ liệu

    // Lấy hóa đơn của người dùng
    $sql = "SELECT * FROM bill_db WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered">';
        echo '<tr><th>ID</th><th>Course ID</th><th>Tên Khóa Học</th><th>Teacher ID</th><th>User ID</th><th>Ngày</th><th>Giá (VNĐ)</th><th>Mã Giao Dịch</th></tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['course_id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['course_name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['teacher_id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['date']) . '</td>';
            echo '<td>' . htmlspecialchars($row['price']) . ' VNĐ</td>';
            echo '<td>' . htmlspecialchars($row['code']) . '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>None.</p>';
    }

    $stmt->close();
    $conn->close();
    ?>
</div>
</div>
<script src="./view/assets/js/main.js"></script>
</body>
</html>