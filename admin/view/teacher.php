<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "./assets/php/connectsql.php";
$sql = "SELECT phone, fullname,id, status,email, avatar,category FROM teacher_db";
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
</head>
<body>
    

    <!-- list blog -->
    <section class="list-blogs-section"></section>
        <div class="text-container">
            <div class="line"></div>
            <h1 class="section-title">List of Teachers </h1>
            <div class="line"></div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>avatar</th>
                    <th>ID</th>
                    <th>Fullname</th>
                    <th>phone</th>
                    <th>email</th>
                    <th>Category</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Kiểm tra kết quả truy vấn
            if ($result->num_rows > 0) {
                // Lặp qua các hàng dữ liệu
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='" . $row['avatar'] . "' alt='Image' /></td>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo "<td>
                    <div class='button-container'>
                        <form action='./assets/php/delete_teacher.php' method='POST'>
                            <input type='hidden' name='id' value='" . $row['id'] . "' /> 
                            <button class='button' type='submit'>Xóa</button>
                        </form>
                        
                        <form action='./assets/php/publicteacher.php' method='POST'>
                            <input type='hidden' name='id' value='" . $row['id'] . "' /> 
                            <label class='switch'>
                                <input type='checkbox' name='status' value='1' onchange='this.form.submit()' " . ($row['status'] ? "checked" : "") . ">
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
    <?php $conn->close(); ?>
    
  
</body>
</html>