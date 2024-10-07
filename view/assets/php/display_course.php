<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendora/autoload.php';
use Google\Client;
use Google\Service\Drive;

include "connectsql.php"; // Kết nối đến cơ sở dữ liệu

function getDriveService() {
    $client = new Client();
    $client->setApplicationName('Your Application Name');
    $client->setScopes(Drive::DRIVE_READONLY);
    $client->setDeveloperKey('AIzaSyBNvI4BM8WInUM1pa74sZwB5N3diO-iI2A'); // Thay thế bằng API key của bạn

    return new Drive($client);
}

// Hàm lấy danh sách thư mục con
function listSubfolders($folderId) {
    $driveService = getDriveService();
    
    $query = "'$folderId' in parents and mimeType='application/vnd.google-apps.folder'";
    $response = $driveService->files->listFiles([
        'q' => $query,
        'fields' => 'nextPageToken, files(id, name)',
    ]);
    
    return $response->files;
}

// Hàm lấy danh sách video .mov trong thư mục
function listVideosInFolder($folderId) {
    $driveService = getDriveService();
    
    // Cập nhật truy vấn để bao gồm video .mp4
    $query = "'$folderId' in parents and (mimeType='video/quicktime' or mimeType='video/mp4')";
    $response = $driveService->files->listFiles([
        'q' => $query,
        'fields' => 'nextPageToken, files(id, name)',
    ]);
    
    return $response->files;
}
// Lấy course_id từ URL
if (!isset($_GET['course_id'])) {
    die("Không có course_id.");
}

$courseId = $_GET['course_id'];
echo $courseId;
// Lấy ID thư mục từ bảng course_db
$stmt = $conn->prepare("SELECT link FROM course_db WHERE id = ?");
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();
$parentFolderId = null;

if ($row = $result->fetch_assoc()) {
    $parentFolderId = $row['link'];
}

// Kiểm tra xem ID thư mục có hợp lệ không
if (!$parentFolderId) {
    die("Không tìm thấy ID thư mục.");
}

// Lấy danh sách thư mục con
$subfolders = listSubfolders($parentFolderId);


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Video Từ Google Drive</title>
    <style>
         .video-container {
            margin-top:100px;
            display: flex;
            height: 800px;
        }
        #video-player {
            flex: 3; /* Video chiếm 3 phần */
            background-color:  #F0F5FF;
            padding: 20px;
            position: relative;
        }
        #video-source {
            width: 100%; /* Chiếm toàn bộ chiều rộng */
            height: 100%; /* Chiếm toàn bộ chiều cao */
            border: none; /* Không có viền */
        }
        .folder-container {
            flex: 1; /* Danh sách thư mục chiếm 1 phần */
            overflow-y: auto;
            padding: 10px;
            border-left: 2px solid #ddd;
        }
        .folder-list a, .video-list a {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #007BFF;
        }
        .folder-list a:hover, .video-list a:hover {
            text-decoration: underline;
        }
        .folder-list h3 {
            margin: 10px 0;
            box-shadow:0 0 10px rgba(0,0,0,0.5);
            padding:40px;
        }
        .video-list{
            display:none;
        }
        p{
            box-shadow:0 0 5px rgba(0,0,0,0.5);
            padding:10px;
        }
    </style>
</head>
<body>

<section class="video-container">
<div id="video-player">
    <iframe id="video-source" width="auto" height="auto" allow="autoplay" frameborder="0" allowfullscreen></iframe>
</div>
<div class="folder-container">
<div class="folder-list">
   
    <?php foreach ($subfolders as $folder): ?>
        <h3 onclick="toggleVideos(this)"><?php echo htmlspecialchars($folder->name); ?></h3>
        <div class="video-list">
            <?php 
            // Lấy danh sách video .mov trong thư mục con
            $videos = listVideosInFolder($folder->id);
            if (count($videos) > 0): 
                foreach ($videos as $video): 
                    $videoUrl = 'https://drive.google.com/file/d/' . $video->id . '/preview';

                    // Kiểm tra video đã tồn tại trong course_db chưa
                   
            ?>
                <p>
                    <a href="#" onclick="playVideo('<?php echo $video->id; ?>'); return false;"><?php echo htmlspecialchars($video->name); ?></a>
                </p>
            <?php 
                endforeach; 
            else: 
            ?>
                <p>Không có video nào trong thư mục này.</p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
            </div>
</section>
<script>
    function toggleVideos(h3Element) {
    // Lấy đoạn văn ngay sau h3
    var videoList = h3Element.nextElementSibling;

    // Kiểm tra xem đoạn văn có đang hiển thị không
    if (videoList.style.display === "block") {
        videoList.style.display = "none"; // Ẩn nếu đang hiển thị
    } else {
        videoList.style.display = "block"; // Hiện nếu đang ẩn
    }
}
function playVideo(videoId) {
    var videoPlayer = document.getElementById('video-player');
    var videoSource = document.getElementById('video-source');
    
    // Cập nhật URL video để tải từ Google Drive
    videoSource.src = 'https://drive.google.com/file/d/' + videoId + '/preview';
    
    // Hiện video player
    videoPlayer.style.display = 'block';
}

document.getElementById('video-player').addEventListener('dblclick', function() {
    if (this.requestFullscreen) {
        this.requestFullscreen();
    } else if (this.mozRequestFullScreen) { // Firefox
        this.mozRequestFullScreen();
    } else if (this.webkitRequestFullscreen) { // Chrome, Safari and Opera
        this.webkitRequestFullscreen();
    } else if (this.msRequestFullscreen) { // IE/Edge
        this.msRequestFullscreen();
    }
});
</script>

</body>
</html>