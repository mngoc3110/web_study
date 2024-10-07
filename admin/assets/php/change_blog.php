<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login Admin</title>
</head>
<body>
<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

// Kết nối đến cơ sở dữ liệu
include "connectsql.php";

// Xác thực Google Drive
$client = new Client();
$client->setApplicationName('Your App Name');
$client->setScopes(Drive::DRIVE_FILE);
$client->setAuthConfig('change_blog.json');
$client->setAccessType('offline');

// Handle the OAuth 2.0 flow
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
    header('Location: change_blog.php'); // Redirect to the same page
    exit;
}

// Kiểm tra và thiết lập access token
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} else {
    // Nếu không có access token, yêu cầu xác thực
    $authUrl = $client->createAuthUrl();
    echo "Open the following link in your browser:\n<a href='$authUrl'>$authUrl</a>";
    exit;
}

// Nếu access token đã hết hạn, cần làm mới
if ($client->isAccessTokenExpired()) {
    if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    } else {
        // Nếu không có refresh token, yêu cầu xác thực lại
        $authUrl = $client->createAuthUrl();
        echo "Open the following link in your browser:\n<a href='$authUrl'>$authUrl</a>";
        exit;
    }
}

// Kiểm tra và xử lý upload tệp
if (isset($_POST['post_id'])) {
    $postId = $_POST['post_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['content'];
    echo $postId;

    // Kiểm tra xem có hình ảnh mới không
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $driveService = new Drive($client);

        $fileMetadata = new Drive\DriveFile([
            'name' => $file['name'],
            'parents' => ['1q8c4TFFBFLvVGK-nsl-2AZAIlZnqNMUK']
        ]);

        $content = file_get_contents($file['tmp_name']);

        // Tạo tệp trong Google Drive
        $fileCreated = $driveService->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $file['type'],
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        // Lấy ID tệp và tạo liên kết
        $fileId = $fileCreated->id;
        $fileLink = "https://drive.google.com/thumbnail?id={$fileId}&sz=w4000";

        // Cập nhật bài viết trong cơ sở dữ liệu
        $stmt = $conn->prepare("UPDATE blog_db SET title = ?, author = ?, description = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $title, $author, $description, $fileLink, $postId);
    } else {
        // Nếu không có hình ảnh mới, chỉ cập nhật nội dung
        $stmt = $conn->prepare("UPDATE blog_db SET title = ?, author = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $author, $description, $postId);
    }

    // Kiểm tra và thực thi câu lệnh
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'link' => $fileLink]);
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "NOTICE",
                    text: "UPDATE SUCCESSFUL",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location.href = "../../index.php?page=blog"; 
                });
            });
        </script>';
    } else {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "WARNING",
                    text: "UPDATE FAILED",
                    icon: "error",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location.href = "../../index.php?page=blog"; 
                });
            });
        </script>';
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'No post ID provided.']);
}
?>