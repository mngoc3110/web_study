<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendora/autoload.php';

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
    header('Location: update_profile.php'); // Redirect to the same page
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
        'parents' => ['16wrzM_rPCgmDyF-4LfO0T5jEVjiyChkw'] // Optional: specify folder ID
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
   
    $username = $_SESSION['username'];
    
    // Prepare and bind
    $stmt = $conn->prepare("UPDATE login_db SET avatar = ?, avatar_image = ? WHERE username = ?");
    $stmt->bind_param("sss", $file['name'], $fileLink, $username);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'link' => $fileLink]);
    } else {
        echo json_encode(['error' => 'Database error: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    
} else {
    echo json_encode(['error' => 'File upload error.']);
}