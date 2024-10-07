
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Design by foolishdeveloper.com -->
    <title>Đăng nhập</title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

session_start();

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
    header('Location: uploadblog.php'); // Redirect to the same page
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
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['image']; // Thay đổi từ 'file' thành 'image'
    $driveService = new Drive($client);

    $fileMetadata = new Drive\DriveFile([
        'name' => $file['name'],
        'parents' => ['1q8c4TFFBFLvVGK-nsl-2AZAIlZnqNMUK'] // Optional: specify folder ID
    ]);

    $content = file_get_contents($file['tmp_name']);

    // Create the file in Google Drive
    $fileCreated = $driveService->files->create($fileMetadata, [
        'data' => $content,
        'mimeType' => $file['type'],
        'uploadType' => 'multipart',
        'fields' => 'id'
    ]);

    // Get the file ID and create the link
    $fileId = $fileCreated->id;
    $fileLink = "https://drive.google.com/thumbnail?id={$fileId}&sz=w4000";

    // Save the link to the database
    include "connectsql.php";
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['content'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO blog_db (title, author, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $author, $description, $fileLink);
    
    if ($stmt->execute()) {
        // Hiển thị thông báo thành công
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: "NOTICE",
                text: "UPLOAD SUCCESSFUL",
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
                text: "UPLOAD FAILED",
                icon: "failed",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "../../index.php?page=blog"; 
            });
        });
      </script>';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
   

} else {
    echo json_encode(['error' => 'File upload error.']);
}
?>
</body>
</html>
