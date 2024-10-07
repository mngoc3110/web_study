<?php
include "./view/assets/php/connectsql.php"; // Kết nối đến cơ sở dữ liệu
// Bật hiển thị lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Kiểm tra xem có bài viết nào được yêu cầu không
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Nếu có ID, lấy nội dung chi tiết
if ($post_id) {
    $stmt = $conn->prepare("SELECT title, image, description FROM blog_db WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($title, $image, $content);
    $stmt->fetch();
    $stmt->close();

    // Cập nhật đường dẫn hình ảnh
    $image_path = './linh/uploads/' . basename($image);
} else {
    echo "id là: ".$post_id;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TRẦN NỮ HOÀNG LINH</title>
    <style>
        .content-container {
            display: flex;
            padding: 100px;
            justify-content: space-around;
        }
        .description-container {
            font-size: 24px;
            margin-top: 50px;
            white-space: pre-wrap; /* Thêm dòng này */
        }
        .title-container {
            font-size: 70px;
            font-weight: bold;
        }
        .image-container {
            width: 400px;
            height: auto;
        }
        .text-container{
            width: 50%;
        }
        .image-container img {
            object-fit: cover;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 15px 20px rgb(0, 0, 0);
        }
        @media(max-width:1240px){

            .content-container{
                align-items:center;
                justify-content:center;
                flex-direction:column;
                padding:50px;
            }
            .text-container{
                margin-bottom:100px;    
            }
        }
        @media(max-width:650px){
            .title-container{
                font-size:50px;
            }
            .description-container{
                font-size:18px;
            }
            .image-container{
                width: 300px;
            }
            .text-container{
                width:100%;
            }
        }

    </style>
</head>
<body>
    <div id="header"></div>
    <div class="spacer"></div>
    <main>
        <section class="content-section">
            <div class="content-container">
                <div class="text-container">
                    <div class="title-container">
                        <?php echo htmlspecialchars($title); ?>
                    </div>
                    <div class="description-container">
                        <?php echo nl2br(htmlspecialchars($content)); ?>
                    </div>
                </div>
                <div class="image-container">
                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="hinh anh">
                </div>
            </div>
        </section>
    </main>
    <div id="footer"></div>
    <script src="./assets/js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>