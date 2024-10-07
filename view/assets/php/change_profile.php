<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Register</title>
</head>
<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "connectsql.php";

// Lấy thông tin từ form
$fullname = $_POST['fullname'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$user_id = $_POST['user_id'] ?? '';
$school = $_POST['school'] ?? '';
$username = $_POST['username'] ?? '';
$current_password = $_POST['currentpassword'] ?? '';
$password = $_POST['newpassword'] ?? '';
$repeat_password = $_POST['repeatpassword'] ?? '';

// Kiểm tra nếu người dùng tồn tại
$sql = "SELECT password FROM login_db WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<script>
            Swal.fire({
                title: "ERROR",
                text: "Không tìm thấy người dùng.",
                icon: "error",
                confirmButtonText: "OK"
            });
          </script>';
    exit;
}

$row = $result->fetch_assoc();

// Kiểm tra mật khẩu hiện tại nếu có
if ($current_password != '') {
    if (!password_verify($current_password, $row['password'])) {
        echo '<script>
                Swal.fire({
                    title: "ERROR",
                    text: "Mật khẩu hiện tại không đúng.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
              </script>';
        exit;
    }

    if ($password !== $repeat_password) {
        echo '<script>
                Swal.fire({
                    title: "ERROR",
                    text: "Mật khẩu mới và xác nhận không khớp.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
              </script>';
        exit;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
} else {
    $hashed_password = $row['password']; // Giữ nguyên mật khẩu cũ
}

// Kiểm tra các trường đầu vào
if (empty($fullname) || empty($phone) || empty($email) || empty($username)) {
    echo '<script>
            Swal.fire({
                title: "ERROR",
                text: "Vui lòng điền tất cả các trường.",
                icon: "error",
                confirmButtonText: "OK"
            });
          </script>';
    exit;
}

// Cập nhật thông tin người dùng
$sql = "UPDATE login_db SET username=?, fullname=?, phone=?, email=?, school=?, password=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisssi", $username, $fullname, $phone, $email, $school, $hashed_password, $user_id);

if ($stmt->execute()) {
    echo '<script>
            Swal.fire({
                title: "NOTICE",
                text: "UPDATE SUCCESSFUL",
                icon: "success",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "../../../index.php"; 
            });
          </script>';
} else {
    echo '<script>
            Swal.fire({
                title: "ERROR",
                text: "Lỗi: ' . $stmt->error . '",
                icon: "error",
                confirmButtonText: "OK"
            });
          </script>';
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>
</body>
</html>