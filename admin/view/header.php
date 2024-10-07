<?php
// Kết nối tới cơ sở dữ liệu
include "./assets/php/connectsql.php";

// Truy vấn để đếm số lượng bản ghi có public = 0
$sql = "SELECT COUNT(*) AS count FROM teacher_db WHERE status = 0";
$result = $conn->query($sql);

$count = 0;
if ($result->num_rows > 0) {
    // Lấy số lượng từ kết quả
    $row = $result->fetch_assoc();
    $count = $row['count'];
}

// Đóng kết nối
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Header Admin</title>
</head>
<body>
   <header>
    <div class="header-section">
        <div class="header-container">
            <!-- logo (side bar) section -->
            <div class="logo-container">
                <i class="fa-solid fa-bars"></i>
            </div>
                
            <div class="header-main-content-container">
                <div class="header-main-content">
                    <ul>
                        <li><a href='index.php?page=main'>Home</a></li>
                        <li><a href="index.php?page=teacher">Teacher</a></li>
                        <li><a href="index.php?page=course">Course</a></li>
                        <li><a href='index.php?page=comment'>Comment</a></li>
                        <li><a href='index.php?page=blog'>Blog</a></li>
                       
                    </ul>
                </div>
            </div>
            <div class="request">
                Request<?php if ($count > 0) echo " <span style='color:#CB0000'>($count)</span>"; ?>
            </div>
            <div class="header-admin-content">
                <div class="darkmode-container">
                    <!-- light mode -->
                    <i class="fa-regular fa-moon" class="light-mode" id="light-mode"></i>
                    <!-- dark mode -->
                    <i class="fa-solid fa-moon" class="dark-mode" id="dark-mode"></i>
                </div>
                <div class="admin-container">
                    <!-- logo user cho admin -->
                    <!-- light mode -->
                    <i class="fa-regular fa-user" id="light-mode"></i>
                    <!-- dark mode -->
                    <i class="fa-solid fa-user" id="dark-mode"></i>
                </div>
            </div>
        </div>
    </div>
   </header>
</body>
</html>