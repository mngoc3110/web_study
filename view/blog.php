<?php
// Bắt đầu phiên dịch xem có bài viết nào được public hay không
include "./view/assets/php/connectsql.php";
$sql = "SELECT id, title, author, description, time, image, public FROM blog_db WHERE public = 1";
$result = $conn->query($sql);
// Suggest blog
$sql_suggest = "SELECT title, author, description, time, image, public FROM blog_db WHERE public = 1 ORDER BY time DESC";
$result_suggest = $conn->query($sql_suggest);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./view/assets/css/blog.css">
    <title>Web Study</title>
</head>
<body>
    <!-- banner section -->

    <!-- blog section -->
    <section class="blog-section">
        <div class="blog-container">
            <div class="left-side-container">
                <h2 class="section-title">" BLOG "</h2>

                <?php
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo '<div class="blog-post">';
                        echo '    <a href="index.php?page=blog-detail&id=' . htmlspecialchars($row['id']) . '">'; // Mở thẻ <a> bao bọc toàn bộ
                        echo '        <div class="blog-image-container">';
                        echo '            <img src="' . htmlspecialchars($row['image']) . '" alt="hinh anh">';
                        echo '        </div>';
                        echo '        <div class="blog-text-container">';
                        echo '            <h3>' . htmlspecialchars($row['title']) . '</h3>';
                        echo '            <p>Author: ' . htmlspecialchars($row['author']) . '</p>';
                        echo '            <p>Date: ' . htmlspecialchars($row['time']) . '</p>';
                        echo '        </div>';
                        echo '    </a>'; // Đóng thẻ <a>
                        echo '</div>';
                    }
                }
                ?>
            </div>

            <div class="right-side-container" id="suggest">
                <h2 class="section-title">" SUGGEST "</h2>
                <?php
                if($result_suggest->num_rows > 0){
                    while($row = $result_suggest->fetch_assoc()){
                        
                        echo '<div class="suggested-post">';
                        echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                        echo '<p>Author: ' . htmlspecialchars($row['author']) . '</p>';
                        echo '<img src="' . htmlspecialchars($row['image']) . '" alt="hinh anh">';
                        echo '</div>';
                    }
                }
                ?>
                <!-- Thêm nhiều gợi ý nếu cần -->
            </div>
        </div>
    </section>
    <script src="./view/assets/js/main.js"></script>
</body>
</html>