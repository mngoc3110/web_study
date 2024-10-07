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
    case 'comment':
        $content = 'view/comment.php';
        break;
    case 'teacher':
        $content = 'view/teacher.php';
        break;
    case 'course':
        $content = 'view/course.php';
        break;
    case 'main':
        $content = 'view/main.php';
        break;
    default:
        $content = 'view/login.html'; // Hoặc file mặc định
}

// Include các file cần thiết

if($default_page!='home'){
    include 'view/header.php';
    include $content; 
}else{
    include $content; 
}

?>
