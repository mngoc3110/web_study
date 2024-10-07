<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "./assets/php/connectsql.php";
$sql = "SELECT image,title, author, description,id, public,time FROM blog_db";
$result = $conn->query($sql);



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./assets/css/blog.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin blog</title>
    <style>
        .popup {
    display: none; /* Ẩn popup mặc định */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4); /* Màu nền mờ */
}

.popup-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
    </style>
</head>
<body>
    
    <!-- add blog -->
    <section class="addblog-section">
        <div class="text-container">
            <div class="line"></div>
            <h1 class="section-title">Add blog </h1>
            <div class="line"></div>
        </div>
        <form action="./assets/php/uploadblog.php" id="addblog" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="5" required></textarea>

            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <button type="submit">Publish</button>
        </form>

    </section>
    <!-- list blog -->
    <section class="list-blogs-section"></section>
        <div class="text-container">
            <div class="line"></div>
            <h1 class="section-title">List of blogs </h1>
            <div class="line"></div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Kiểm tra kết quả truy vấn
            if ($result->num_rows > 0) {
                // Lặp qua các hàng dữ liệu
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='" . $row['image'] . "' alt='Image' /></td>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                    echo "<td>
                    <div class='button-container'>
                        <button type='button' class='button' onclick=\"openPopup('" . htmlspecialchars($row['title']) . "', '" . htmlspecialchars($row['author']) . "','" . htmlspecialchars($row['description']) . "','" . $row['image'] . "', '" . $row['id'] . "')\">Sửa</button>
                        <form action='./assets/php/delete_blog.php' method='POST'>
                            <input type='hidden' name='id' value='" . $row['id'] . "' />
                            <button class='button' type='submit'>Xóa</button>
                        </form>
                        <form action='./assets/php/publicblog.php' method='POST'>
                            <input type='hidden' name='id' value='" . $row['id'] . "' />
                            <label class='switch'>
                                <input type='checkbox' name='public' value='1' onchange='this.form.submit()' " . ($row['public'] ? "checked" : "") . ">
                                <span class='slider'></span>
                            </label>
                        </form>
                    </div>
                </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Không có bài viết nào.</td></tr>";
            }
            ?>
            
            </tbody>
        </table>
    </section>
    <div id="editPopup" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <h2>Chỉnh sửa bài viết</h2>
        <form id="editForm" action="./assets/php/change_blog.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="post_id" id="post_id">
            <label for="edit_title">Title:</label>
            <input type="text" id="edit_title" name="title" >

            <label for="edit_title">Author:</label>
            <input type="text" id="edit_author" name="author" >

            <label for="edit_content">Nội dung:</label>
            <textarea id="edit_content" name="content" rows="5" ></textarea>


            <label for="edit_image">Hình ảnh:</label>
            <input type="file" id="edit_image" name="image" accept="image/*">
            
            <img id="current_image" src="" alt="Hình ảnh hiện tại" style="max-width: 100%; height: auto;">


            <button type="submit">Cập nhật</button>
        </form>
    </div>
</div>
    <?php $conn->close(); ?>
    
    <script>
function openPopup(title, author, content, image,id) {
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_author').value = author;
    document.getElementById('edit_content').value = content;
    document.getElementById('current_image').src = image; // Hiển thị hình ảnh hiện tại
    document.getElementById('post_id').value = id;
  
    document.getElementById('current_image').style.display = 'block'; // Hiện hình ảnh
    document.getElementById('editPopup').style.display = 'block'; // Hiện popup
}

function closePopup() {
    document.getElementById('editPopup').style.display = 'none';
}
</script>
</body>
</html>