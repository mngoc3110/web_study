<?php
session_start();
$default_page = 'home';

// Kiểm tra xem có tham số 'page' trong URL không
if (isset($_GET['page'])) {
    $default_page = $_GET['page'];
}

// Chọn file cần include dựa trên tham số
switch ($default_page) {
   
    case 'blog':
        $content = 'view/blog.php';
        break;
    case 'course':
        $content = 'view/course.html';
        break;
    case 'main':
        $content = 'view/main.php';
        break;
    default:
        $content = 'view/login.html'; // Hoặc file mặc định
}

// Include các file cần thiết


include 'view/header.html';

include $content; 



?>
