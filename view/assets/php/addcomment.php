<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Comment</title>
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendora/autoload.php';

use Google\Client;
use Google\Service\Drive;

session_start();
include "connectsql.php"; // Đảm bảo kết nối

// Load credentials
$client = new Client();
$client->setApplicationName('Your App Name');
$client->setScopes(Drive::DRIVE_FILE);
$client->setAuthConfig('credential.json');
$client->setAccessType('offline');

// Handle the OAuth 2.0 flow
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $_SESSION['access_token'] = $token;
    header('Location: addcomment.php'); // Redirect to the same page
    exit;
}

// Authenticate
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} else {
    $authUrl = $client->createAuthUrl();
    echo json_encode(['url' => $authUrl]);
    exit;
}

// Handle file upload
if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    
    $file = $_FILES['file'];
    $driveService = new Drive($client);
    
    $fileMetadata = new Drive\DriveFile([
        'name' => $file['name'],
        'parents' => ['1a4UEMXO5AHRi1Pf0hSY29Izv9LmuDnb0'] // Optional: specify folder ID
    ]);
    
    $content = file_get_contents($file['tmp_name']);
    
    // Create the file in Google Drive
    $fileCreated = $driveService->files->create($fileMetadata, [
        'data' => $content,
        'mimeType' => $file['type'],
        'uploadType' => 'multipart',
        'fields' => 'id'
    ]);
    
    if ($fileCreated) {
        // Get the file ID and create the link
        $fileId = $fileCreated->id;
        $fileLink = "https://drive.google.com/thumbnail?id={$fileId}&sz=w4000";

        // Get post data
        $studentname = $_POST['student-name'];
        $comment = $_POST['student-comment'];
        $rating = (int)$_POST['rating'];
        $course = $_POST['course'];
        $course_id = 0;

        // Check for empty fields
        if (empty($studentname) || empty($comment) || empty($rating) || empty($course)) {
            echo "Vui lòng điền tất cả các trường.";
            exit;
        }

        // Insert into database
        $sql = "INSERT INTO comment_db (name, content, post_type, rating, post_id, image) VALUES ('$studentname', '$comment', '$course', $rating, $course_id, '$fileLink')";
        if ($conn->query($sql) === TRUE) {
            echo '<script>
            Swal.fire({
                title: "NOTICE",
                text: "ADD SUCCESSFUL",
                icon: "success",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "../../../index.php"; 
            });
            </script>';
        } else {
            echo "Lỗi: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Lỗi khi tạo tệp trên Google Drive.";
    }
}
?>
</body>
</html>