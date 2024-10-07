<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Become teacher</title>
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "connectsql.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy username từ session
    $username = $_SESSION['username'] ?? null;

    if ($username === null) {
        die("Lỗi: Username không hợp lệ.");
    }

    $_info = "SELECT username, password, fullname, phone, email, avatar_image FROM login_db WHERE username = ?";
    $_stmtinfo = $conn->prepare($_info);
    if ($_stmtinfo === false) {
        die("Lỗi chuẩn bị câu lệnh: " . $conn->error);
    }

    $_stmtinfo->bind_param("s", $username);
    $_stmtinfo->execute();
    
    // Gán giá trị vào các biến
    $_stmtinfo->bind_result($fetched_username, $hashed_password, $fullname, $phone, $email, $avatar);
    
    // Kiểm tra xem có kết quả không
    if (!$_stmtinfo->fetch()) {
        die("Lỗi: Không tìm thấy thông tin cho người dùng $username.");
    }
    $_stmtinfo->close();

    // Kiểm tra xem có tìm thấy mật khẩu không
    if ($hashed_password === null) {
        die("Lỗi: Không có thông tin mật khẩu cho người dùng $username.");
    }

    // Chèn vào teacher_db
    $insert_into_teacher = "INSERT INTO teacher_db (username, password, fullname, phone, email, avatar) VALUES (?, ?, ?, ?, ?, ?)";
    $insert = $conn->prepare($insert_into_teacher);
    if ($insert === false) {
        die("Lỗi chuẩn bị câu lệnh chèn: " . $conn->error);
    }

    // Gán các biến cho câu lệnh chèn
    $insert->bind_param("ssssss", $fetched_username, $hashed_password, $fullname, $phone, $email, $avatar);
    
    // Thực thi câu lệnh chèn
    if (!$insert->execute()) {
        die("Lỗi khi chèn vào teacher_db: " . $insert->error);
    }

    // Cập nhật category
    $category = $_POST['category'];
    $sql = "UPDATE teacher_db SET category = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Lỗi chuẩn bị câu lệnh cập nhật category: " . $conn->error);
    }
    $stmt->bind_param("ss", $category, $fetched_username);

    // Cập nhật role trong bảng login_db
    $role = 1; // Giả sử bạn muốn cập nhật role thành 2
    $update_role = "UPDATE login_db SET role = ? WHERE username = ?";
    $update = $conn->prepare($update_role);
    if ($update === false) {
        die("Lỗi chuẩn bị câu lệnh cập nhật role: " . $conn->error);
    }
    $update->bind_param("is", $role, $fetched_username);

    // Thực thi tất cả các câu lệnh
    if ($stmt->execute() && $update->execute()) {
        echo '<script>
        Swal.fire({
            title: "NOTICE",
            text: "UPGRADE SUCCESSFUL",
            icon: "success",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "../../../index.php"; 
        });
        </script>';
    } else {
        $error_message = "ERROR: " . $stmt->error . " " . $insert->error . " " . $update->error;
        echo $error_message;
    }

    // Đóng tất cả các câu lệnh
    $stmt->close();
    $insert->close();
    $update->close();
}

if (isset($conn)) {
    $conn->close();
}
?>
</body>
</html>